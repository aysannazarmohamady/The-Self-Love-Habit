<?php
// config.php
define('BOT_TOKEN', '');
define('GEMINI_API_KEY', '');
define('DATA_FILE', 'users.json');

// Include all required files
require_once 'challenges.php';
require_once 'challenges_fa.php';
require_once 'lang_en.php';
require_once 'lang_fa.php';
require_once 'translate.php';

// Load users data from JSON
function loadUsers() {
    if (file_exists(DATA_FILE)) {
        $json = file_get_contents(DATA_FILE);
        return json_decode($json, true) ?: [];
    }
    return [];
}

// Save users data to JSON
function saveUsers($users) {
    return file_put_contents(DATA_FILE, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Get user data
function getUser($user_id) {
    $users = loadUsers();
    return isset($users[$user_id]) ? $users[$user_id] : null;
}

// Save or update user
function saveUser($user_id, $data) {
    $users = loadUsers();
    $users[$user_id] = $data;
    return saveUsers($users);
}

// Send message to user
function sendMessage($chat_id, $text, $reply_markup = null) {
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'Markdown'
    ];
    
    if ($reply_markup) {
        $data['reply_markup'] = json_encode($reply_markup);
    }
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

// Detect language of text (simple Persian detection)
function detectLanguage($text) {
    $persian_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
    return preg_match($persian_pattern, $text) ? 'fa' : 'en';
}

// Generate AI coaching response using Gemini API
function generateCoachingResponse($challenge_title, $user_response, $language = 'en') {
    if ($language == 'fa') {
        $prompt = "شما یک مربی اعتماد به نفس حرفه‌ای و همدل هستید که به شخصی پاسخ می‌دهید که تازه چالش روزانه اعتماد به نفس را تکمیل کرده است.

چالش: \"{$challenge_title}\"
پاسخ کاربر: \"{$user_response}\"

ابتدا پاسخ کاربر را با دقت بخوانید و به احساس، لحن و محتوای آن توجه کنید:
- اگر پاسخ مثبت و پرانرژی است، آن را جشن بگیرید
- اگر پاسخ با تردید یا چالش همراه است، همدلی نشان دهید
- اگر پاسخ کوتاه یا ساده است، آن را تأیید کنید بدون قضاوت

یک پاسخ کوتاه (2-3 جمله) بنویسید که:
1. اول، به محتوای خاص پاسخشان واکنش نشان دهید (نه تعریف کلی!)
2. اگر نیاز به راهنمایی یا نکته‌ای دارند، فقط یک جمله مختصر اضافه کنید
3. لحن شما باید با لحن پاسخ کاربر هماهنگ باشد

مهم: از کلمات و مفاهیمی که خود کاربر استفاده کرده بازتاب دهید. پاسخ را شخصی‌سازی کنید نه عمومی.
بدون تعارف کلیشه‌ای - آن را اصیل و نزدیک به احساس کاربر حس کنید.";
    } else {
        $prompt = "You are a professional, empathetic confidence coach responding to someone who just completed a daily self-confidence challenge.

Challenge: \"{$challenge_title}\"
User's response: \"{$user_response}\"

First, carefully read their response and notice the emotion, tone, and content:
- If their response is positive and energetic, celebrate with them
- If their response shows doubt or struggle, show empathy
- If their response is brief or simple, validate it without judgment

Write a short response (2-3 sentences) that:
1. First, react specifically to THEIR response content (not generic praise!)
2. If they need guidance or a helpful tip, add just ONE brief sentence
3. Your tone should match the tone of their response

Important: Mirror back words and concepts THEY used. Make it personal, not generic.
No cookie-cutter praise - make it feel authentic and closely aligned with their emotion.";
    }

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]
    ];

    $options = [
        'http' => [
            'header' => [
                "Content-Type: application/json",
                "X-goog-api-key: " . GEMINI_API_KEY
            ],
            'method' => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent', false, $context);
    
    if ($response === false) {
        return $language == 'fa' 
            ? "چه قدم فوق‌العاده‌ای! آمادگی شما برای به اشتراک‌گذاری و رشد شجاعت واقعی می‌طلبد. شما در حال ساختن چیزی شگفت‌انگیز هستید، یک روز در یک زمان!"
            : "What an incredible step forward! Your willingness to share and grow takes real courage. You're building something amazing, one day at a time!";
    }
    
    $result = json_decode($response, true);
    
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return trim($result['candidates'][0]['content']['parts'][0]['text']);
    }
    
    return $language == 'fa' 
        ? "چه قدم فوق‌العاده‌ای! آمادگی شما برای به اشتراک‌گذاری و رشد شجاعت واقعی می‌طلبد. شما در حال ساختن چیزی شگفت‌انگیز هستید، یک روز در یک زمان!"
        : "What an incredible step forward! Your willingness to share and grow takes real courage. You're building something amazing, one day at a time!";
}

// Calculate user points
function calculatePoints($user) {
    $points = 0;
    $completed_days = $user['completed_days'] ?? [];
    
    foreach ($completed_days as $day => $data) {
        if ($data['completed']) {
            $points += 10;
        }
    }
    
    return $points;
}

// Get user progress
function getUserProgress($user) {
    $completed_count = 0;
    $completed_days = $user['completed_days'] ?? [];
    
    foreach ($completed_days as $day => $data) {
        if ($data['completed']) {
            $completed_count++;
        }
    }
    
    return [
        'completed' => $completed_count,
        'total' => 30,
        'percentage' => round(($completed_count / 30) * 100, 1)
    ];
}

// Handle active challenge response
function handleChallengeResponse($user_id, $user, $day, $text) {
    $response = trim($text);
    $lang = getUserLanguage($user);
    
    if (strlen($response) >= 3) {
        // Detect language of response
        $response_language = detectLanguage($response);
        
        // Mark as completed and award points
        $completed_days = $user['completed_days'] ?? [];
        $completed_days[$day] = [
            'completed' => true,
            'completed_at' => date('Y-m-d H:i:s'),
            'response' => $response,
            'language' => $response_language
        ];
        
        // Get challenge title for AI response
        $challenge_data = getChallengeText($day, $lang);
        $challenge = $challenge_data['challenge'];
        $challenge_title = $challenge ? $challenge['title'] : "Day {$day} Challenge";
        
        // Generate AI coaching response in appropriate language
        $ai_response = generateCoachingResponse($challenge_title, $response, $response_language);
        
        $points = calculatePoints(array_merge($user, ['completed_days' => $completed_days]));
        
        $completion_message = "*{$user['name']}, {$ai_response}*\n\n";
        $completion_message .= t('challenge_completed', $response_language, [
            'day' => $day,
            'points' => $points
        ]);
        
        sendMessage($user['chat_id'], $completion_message, getMainKeyboard($lang));
        
        // Update user data
        $next_day = $day + 1;
        saveUser($user_id, array_merge($user, [
            'step' => $next_day <= 30 ? 'waiting_for_next_day' : 'challenge_completed',
            'completed_days' => $completed_days,
            'current_day' => min($next_day, 30),
            'last_activity' => date('Y-m-d H:i:s')
        ]));
        
        // If challenge is complete
        if ($day == 30) {
            $final_message = t('final_message', $lang);
            sendMessage($user['chat_id'], $final_message);
        }
        
        return true;
    } else {
        $encourage_message = getEncouragementMessage($day);
        sendMessage($user['chat_id'], $encourage_message);
        return false;
    }
}

// Main webhook handler
$input = file_get_contents('php://input');
$update = json_decode($input, true);

// Handle callback queries (inline button clicks)
if (isset($update['callback_query'])) {
    $callback = $update['callback_query'];
    $chat_id = $callback['message']['chat']['id'];
    $user_id = $callback['from']['id'];
    $data = $callback['data'];
    
    $user = getUser($user_id);
    $lang = getUserLanguage($user);
    
    // Handle language selection
    if ($data == 'set_lang_en' || $data == 'set_lang_fa') {
        $selected_lang = ($data == 'set_lang_en') ? 'en' : 'fa';
        
        // Update user language
        if ($user) {
            $user['language'] = $selected_lang;
            saveUser($user_id, $user);
            
            $message = t('language_set', $selected_lang);
            sendMessage($chat_id, $message, getMainKeyboard($selected_lang));
            
            // If user hasn't started yet, continue with name or start process
            if (!isset($user['start_date']) && isset($user['step']) && $user['step'] !== 'waiting_for_start') {
                // They just selected language, continue flow
                if ($user['step'] === 'waiting_for_language') {
                    $welcome = t('welcome_description', $selected_lang);
                    sendMessage($chat_id, $welcome);
                    
                    // Update step to waiting for name
                    saveUser($user_id, array_merge($user, [
                        'step' => 'waiting_for_name'
                    ]));
                }
            }
        }
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
        exit;
    }
    
    if ($data == 'start_now' && $user && $user['step'] == 'waiting_for_start') {
        $start_text = t('start_now', $lang, ['name' => $user['name']]);
        sendMessage($chat_id, $start_text);
        
        // Get Day 1 challenge
        $challenge_data = getChallengeText(1, $lang);
        $challenge = $challenge_data['challenge'];
        formatChallengeMessageMultilang(1, $challenge, $user['name'], $chat_id, $lang);
        
        // Update user status to day 1 active
        saveUser($user_id, array_merge($user, [
            'step' => 'day_1_active',
            'start_date' => date('Y-m-d'),
            'current_day' => 1,
            'completed_days' => [],
            'last_activity' => date('Y-m-d H:i:s')
        ]));
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
    elseif ($data == 'start_later' && $user && $user['step'] == 'waiting_for_start') {
        $later_text = t('start_later', $lang, ['name' => $user['name']]);
        sendMessage($chat_id, $later_text);
        
        saveUser($user_id, array_merge($user, [
            'step' => 'postponed'
        ]));
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
    // Handle translate buttons
    elseif (strpos($data, 'translate_') === 0) {
        $parts = explode('_', $data);
        $target_lang = $parts[1]; // 'en' or 'fa'
        $day = intval($parts[2]);
        
        $challenge_data = getChallengeText($day, $target_lang);
        $challenge = $challenge_data['challenge'];
        
        if ($challenge) {
            formatChallengeMessageMultilang($day, $challenge, $user['name'], $chat_id, $target_lang);
        }
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
    // Handle view day buttons
    elseif (strpos($data, 'view_day_') === 0) {
        $day = intval(str_replace('view_day_', '', $data));
        $completed_days = $user['completed_days'] ?? [];
        $challenge_data = getChallengeText($day, $lang);
        $challenge = $challenge_data['challenge'];
        
        if (!$challenge) {
            file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id'] . "&text=" . urlencode(t('challenge_not_found', $lang)));
            return;
        }
        
        $challenge_title = $challenge['title'];
        $is_completed = isset($completed_days[$day]) && $completed_days[$day]['completed'];
        
        if ($is_completed) {
            $current_response = $completed_days[$day]['response'];
            $completed_at = $completed_days[$day]['completed_at'];
            
            $view_message = t('day_view_completed', $lang, [
                'day' => $day,
                'title' => $challenge_title,
                'date' => date('M j, Y', strtotime($completed_at)),
                'response' => $current_response
            ]);
            
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => t('btn_edit', $lang), 'callback_data' => "edit_day_{$day}"],
                        ['text' => t('btn_back_all_days', $lang), 'callback_data' => 'all_days']
                    ]
                ]
            ];
            
            sendMessage($chat_id, $view_message, $keyboard);
        } else {
            // Show challenge and let user complete it
            formatChallengeMessageMultilang($day, $challenge, $user['name'], $chat_id, $lang);
            
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => t('btn_back_all_days', $lang), 'callback_data' => 'all_days']]
                ]
            ];
            
            $response_hint = t('day_view_not_completed', $lang);
            sendMessage($chat_id, $response_hint, $keyboard);
            
            // Set user to active for this day
            saveUser($user_id, array_merge($user, [
                'step' => "day_{$day}_active",
                'last_activity' => date('Y-m-d H:i:s')
            ]));
        }
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
    // Handle edit day buttons
    elseif (strpos($data, 'edit_day_') === 0) {
        $day = intval(str_replace('edit_day_', '', $data));
        $completed_days = $user['completed_days'] ?? [];
        
        if (isset($completed_days[$day]) && $completed_days[$day]['completed']) {
            $current_response = $completed_days[$day]['response'];
            $challenge_data = getChallengeText($day, $lang);
            $challenge = $challenge_data['challenge'];
            $challenge_title = $challenge ? $challenge['title'] : "Day {$day}";
            
            $edit_message = t('edit_prompt', $lang, [
                'day' => $day,
                'title' => $challenge_title,
                'response' => $current_response
            ]);
            
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => t('btn_cancel_edit', $lang), 'callback_data' => "view_day_{$day}"]]
                ]
            ];
            
            sendMessage($chat_id, $edit_message, $keyboard);
            
            // Set user to edit mode for this day
            saveUser($user_id, array_merge($user, [
                'step' => "edit_day_{$day}",
                'last_activity' => date('Y-m-d H:i:s')
            ]));
        }
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
    // Handle all days button
    elseif ($data == 'all_days') {
        list($message, $keyboard) = generateAllDaysViewMultilang($user, $lang);
        sendMessage($chat_id, $message, $keyboard);
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
    // Handle locked day
    elseif (strpos($data, 'locked_day_') === 0) {
        $day = intval(str_replace('locked_day_', '', $data));
        $error_msg = t('day_locked', $lang, ['day' => $day]);
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id'] . "&text=" . urlencode($error_msg));
    }
    // Handle gratitude language switch
    elseif ($data == 'gratitude_fa' || $data == 'gratitude_en') {
        $gratitude_lang = ($data == 'gratitude_fa') ? 'fa' : 'en';
        $other_lang = ($gratitude_lang == 'fa') ? 'en' : 'fa';
        
        $gratitude_prompt = t('gratitude_prompt', $gratitude_lang);
        
        $keyboard = [
            'inline_keyboard' => [
                [['text' => t('btn_translate_' . $other_lang, $gratitude_lang), 'callback_data' => 'gratitude_' . $other_lang]]
            ]
        ];
        
        sendMessage($chat_id, $gratitude_prompt, $keyboard);
        
        saveUser($user_id, array_merge($user, [
            'step' => 'gratitude_active',
            'last_activity' => date('Y-m-d H:i:s')
        ]));
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
}

// Handle regular messages
if (isset($update['message'])) {
    $message = $update['message'];
    $chat_id = $message['chat']['id'];
    $user_id = $message['from']['id'];
    $text = $message['text'] ?? '';
    $first_name = $message['from']['first_name'] ?? '';
    
    $user = getUser($user_id);
    
    // Handle /start command
    if ($text == '/start') {
        if ($user && isset($user['start_date'])) {
            // User already started challenge
            $lang = getUserLanguage($user);
            $welcome_back = t('welcome_back', $lang, ['name' => $user['name']]);
            sendMessage($chat_id, $welcome_back, getMainKeyboard($lang));
        } else {
            // New user or user who hasn't started - ask for language first
            if (!$user || !isset($user['language'])) {
                $language_prompt = t('choose_language', 'en'); // Show bilingual
                sendMessage($chat_id, $language_prompt, getLanguageSelectionKeyboard());
                
                // Save user as waiting for language
                saveUser($user_id, [
                    'chat_id' => $chat_id,
                    'first_name' => $first_name,
                    'step' => 'waiting_for_language',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                // User has language but hasn't started, ask for name
                $lang = $user['language'];
                $welcome_text = t('welcome_title', $lang) . "\n\n" . t('welcome_description', $lang);
                sendMessage($chat_id, $welcome_text);
                
                saveUser($user_id, array_merge($user, [
                    'step' => 'waiting_for_name'
                ]));
            }
        }
    }
    // Handle keyboard menu buttons
    elseif ($user && isset($user['start_date'])) {
        $lang = getUserLanguage($user);
        
        // Match buttons by comparing with translations
        if ($text == t('btn_progress', $lang)) {
            $report = generateProgressReportMultilang($user, $lang);
            sendMessage($chat_id, $report, getMainKeyboard($lang));
        }
        elseif ($text == t('btn_all_days', $lang)) {
            list($message, $keyboard) = generateAllDaysViewMultilang($user, $lang);
            sendMessage($chat_id, $message, $keyboard);
        }
        elseif ($text == t('btn_today_challenge', $lang)) {
            $current_day = $user['current_day'] ?? 1;
            $completed_days = $user['completed_days'] ?? [];
            
            if ($current_day > 30) {
                sendMessage($chat_id, t('all_days_completed', $lang), getMainKeyboard($lang));
            } elseif (isset($completed_days[$current_day]) && $completed_days[$current_day]['completed']) {
                sendMessage($chat_id, t('day_already_completed', $lang, ['day' => $current_day]), getMainKeyboard($lang));
            } else {
                $challenge_data = getChallengeText($current_day, $lang);
                $challenge = $challenge_data['challenge'];
                if ($challenge) {
                    formatChallengeMessageMultilang($current_day, $challenge, $user['name'], $chat_id, $lang);
                    
                    saveUser($user_id, array_merge($user, [
                        'step' => "day_{$current_day}_active",
                        'last_activity' => date('Y-m-d H:i:s')
                    ]));
                }
            }
        }
        elseif ($text == t('btn_gratitude', $lang)) {
            $gratitude_prompt = t('gratitude_prompt', $lang);
            
            $other_lang = ($lang === 'en') ? 'fa' : 'en';
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => t('btn_translate_' . $other_lang, $lang), 'callback_data' => 'gratitude_' . $other_lang]]
                ]
            ];
            
            sendMessage($chat_id, $gratitude_prompt, $keyboard);
            
            saveUser($user_id, array_merge($user, [
                'step' => 'gratitude_active',
                'last_activity' => date('Y-m-d H:i:s')
            ]));
        }
        elseif ($text == t('btn_help', $lang)) {
            $help_text = t('help_text', $lang);
            sendMessage($chat_id, $help_text, getMainKeyboard($lang));
        }
        elseif ($text == t('btn_change_language', $lang)) {
            // Show language selection
            $language_prompt = t('choose_language', 'en'); // Show bilingual
            sendMessage($chat_id, $language_prompt, getLanguageSelectionKeyboard());
        }
        else {
            // Handle challenge responses
            if (preg_match('/^day_(\d+)_active$/', $user['step'], $matches)) {
                $day = intval($matches[1]);
                handleChallengeResponse($user_id, $user, $day, $text);
            } 
            // Handle gratitude responses
            elseif ($user['step'] == 'gratitude_active') {
                $gratitude_text = trim($text);
                
                if (strlen($gratitude_text) >= 3) {
                    $response_language = detectLanguage($gratitude_text);
                    
                    // Generate AI response for gratitude
                    $ai_gratitude = generateCoachingResponse("Daily Gratitude Practice", $gratitude_text, $response_language);
                    
                    $thank_message = t('gratitude_response', $response_language, ['response' => $ai_gratitude]);
                    
                    sendMessage($chat_id, $thank_message, getMainKeyboard($lang));
                    
                    saveUser($user_id, array_merge($user, [
                        'step' => 'waiting_for_next_day',
                        'last_activity' => date('Y-m-d H:i:s')
                    ]));
                } else {
                    $short_msg = t('gratitude_short', $lang);
                    sendMessage($chat_id, $short_msg);
                }
            }
            // Handle edit responses
            elseif (preg_match('/^edit_day_(\d+)$/', $user['step'], $matches)) {
                $day = intval($matches[1]);
                $new_response = trim($text);
                
                if (strlen($new_response) >= 3) {
                    $response_language = detectLanguage($new_response);
                    $completed_days = $user['completed_days'] ?? [];
                    $completed_days[$day]['response'] = $new_response;
                    $completed_days[$day]['edited_at'] = date('Y-m-d H:i:s');
                    $completed_days[$day]['language'] = $response_language;
                    
                    $edit_success = t('edit_success', $response_language, [
                        'day' => $day,
                        'response' => $new_response
                    ]);
                    
                    sendMessage($chat_id, $edit_success, getMainKeyboard($lang));
                    
                    // Return user to normal state
                    saveUser($user_id, array_merge($user, [
                        'step' => 'waiting_for_next_day',
                        'completed_days' => $completed_days,
                        'last_activity' => date('Y-m-d H:i:s')
                    ]));
                } else {
                    $short_msg = t('edit_short', $lang);
                    sendMessage($chat_id, $short_msg);
                }
            } else {
                $use_menu_msg = t('use_menu', $lang, ['name' => $user['name']]);
                sendMessage($chat_id, $use_menu_msg, getMainKeyboard($lang));
            }
        }
    }
    // Handle name input
    elseif ($user && $user['step'] == 'waiting_for_name') {
        $name = trim($text);
        $lang = isset($user['language']) ? $user['language'] : 'en';
        
        if (strlen($name) > 2 && strlen($name) < 50) {
            $intro_text = t('intro_text', $lang, ['name' => $name]);
            
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => t('btn_start_now', $lang), 'callback_data' => 'start_now'],
                        ['text' => t('btn_start_later', $lang), 'callback_data' => 'start_later']
                    ]
                ]
            ];
            
            sendMessage($chat_id, $intro_text, $keyboard);
            
            saveUser($user_id, array_merge($user, [
                'name' => $name,
                'step' => 'waiting_for_start'
            ]));
        } else {
            $invalid_msg = t('invalid_name', $lang);
            sendMessage($chat_id, $invalid_msg);
        }
    }
    // Handle users who postponed and want to restart
    elseif ($user && $user['step'] == 'postponed' && $text == '/start') {
        $lang = getUserLanguage($user);
        $restart_text = t('welcome_back', $lang, ['name' => $user['name']]);
        $restart_text .= "\n\n" . ($lang === 'fa' ? 'آماده شروع روز 1 هستید؟' : 'Are you ready to start with Day 1?');
        
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => t('btn_start_now', $lang), 'callback_data' => 'start_now'],
                    ['text' => t('btn_start_later', $lang), 'callback_data' => 'start_later']
                ]
            ]
        ];
        
        sendMessage($chat_id, $restart_text, $keyboard);
        
        saveUser($user_id, array_merge($user, [
            'step' => 'waiting_for_start'
        ]));
    }
    // Handle users without language set (shouldn't happen but safety check)
    elseif ($user && $user['step'] == 'waiting_for_language') {
        // They should use buttons, but just in case
        $language_prompt = t('choose_language', 'en');
        sendMessage($chat_id, $language_prompt, getLanguageSelectionKeyboard());
    }
    // Default response for new users
    else {
        if ($user && isset($user['language'])) {
            $lang = $user['language'];
            $use_menu_msg = t('use_menu', $lang, ['name' => $user['name'] ?? $first_name]);
            sendMessage($chat_id, $use_menu_msg, getMainKeyboard($lang));
        } else {
            $start_msg = t('use_start', 'en');
            sendMessage($chat_id, $start_msg);
        }
    }
}
?>
