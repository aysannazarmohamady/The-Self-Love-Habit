<?php
// config.php
define('BOT_TOKEN', '');
define('GEMINI_API_KEY', '');
define('DATA_FILE', 'users.json');

// Include challenges data
require_once 'challenges.php';

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

// Generate AI coaching response using Gemini API
function generateCoachingResponse($challenge_title, $user_response) {
    $prompt = "You are a professional confidence coach responding to someone who just completed a daily self-confidence challenge.

Challenge: \"{$challenge_title}\"
User's response: \"{$user_response}\"

Write a short, empowering, and supportive response (2-3 sentences max) that:
- Acknowledges their effort and courage
- Validates their experience 
- Encourages continued growth
- Feels personal and genuine
- Uses a warm, professional coaching tone

Keep it concise but impactful. No generic praise - make it feel authentic.";

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
        // Fallback to default message if API fails
        return "What an incredible step forward! Your willingness to share and grow takes real courage. You're building something amazing, one day at a time!";
    }
    
    $result = json_decode($response, true);
    
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return trim($result['candidates'][0]['content']['parts'][0]['text']);
    }
    
    // Fallback message
    return "What an incredible step forward! Your willingness to share and grow takes real courage. You're building something amazing, one day at a time!";
}

// Get main keyboard menu
function getMainKeyboard() {
    return [
        'keyboard' => [
            [['text' => '📊 My Progress'], ['text' => '📅 All Days']],
            [['text' => '🎯 Today\'s Challenge'], ['text' => '❓ Help']]
        ],
        'resize_keyboard' => true,
        'persistent' => true
    ];
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

// Generate progress report
function generateProgressReport($user) {
    $progress = getUserProgress($user);
    $points = calculatePoints($user);
    
    $message = "*🌟 {$user['name']}'s Confidence Journey 🌟*\n\n";
    $message .= "📊 *Progress:* {$progress['completed']}/30 days ({$progress['percentage']}%)\n";
    $message .= "🏆 *Total Points:* {$points}\n";
    $message .= "📅 *Started:* " . ($user['start_date'] ?? 'Not started') . "\n\n";
    
    // Progress bar
    $completed = $progress['completed'];
    $bar_length = 20;
    $filled = round(($completed / 30) * $bar_length);
    $empty = $bar_length - $filled;
    $progress_bar = str_repeat('🟩', $filled) . str_repeat('⬜', $empty);
    $message .= "Progress: {$progress_bar}\n\n";
    
    if ($completed > 0) {
        $message .= "Keep up the amazing work! 🚀\n";
    } else {
        $message .= "Ready to start your journey? 💪\n";
    }
    
    $message .= "\nUse '📅 All Days' to see and edit your responses!";
    
    return $message;
}

// Generate all days view
function generateAllDaysView($user) {
    $completed_days = $user['completed_days'] ?? [];
    $current_day = $user['current_day'] ?? 1;
    
    $message = "*📅 Your 30-Day Challenge Overview*\n\n";
    
    // Create inline keyboard with all days
    $buttons = [];
    $row = [];
    
    for ($day = 1; $day <= 30; $day++) {
        $is_completed = isset($completed_days[$day]) && $completed_days[$day]['completed'];
        $is_available = $day <= $current_day;
        
        if ($is_completed) {
            $status = '✅';
        } elseif ($is_available) {
            $status = '⭕';
        } else {
            $status = '🔒';
        }
        
        $button_text = "Day {$day} {$status}";
        $callback_data = $is_available ? "view_day_{$day}" : "locked_day_{$day}";
        
        $row[] = ['text' => $button_text, 'callback_data' => $callback_data];
        
        if (count($row) == 3) {
            $buttons[] = $row;
            $row = [];
        }
    }
    
    if (!empty($row)) {
        $buttons[] = $row;
    }
    
    $keyboard = ['inline_keyboard' => $buttons];
    
    $message .= "✅ = Completed | ⭕ = Available | 🔒 = Locked\n\n";
    $message .= "Tap any available day to view or edit your response!";
    
    return [$message, $keyboard];
}

// Handle active challenge response
function handleChallengeResponse($user_id, $user, $day, $text) {
    $response = trim($text);
    
    if (strlen($response) >= 3) {
        // Mark as completed and award points
        $completed_days = $user['completed_days'] ?? [];
        $completed_days[$day] = [
            'completed' => true,
            'completed_at' => date('Y-m-d H:i:s'),
            'response' => $response
        ];
        
        // Get challenge title for AI response
        $challenge = getChallenge($day);
        $challenge_title = $challenge ? $challenge['title'] : "Day {$day} Challenge";
        
        // Generate AI coaching response
        $ai_response = generateCoachingResponse($challenge_title, $response);
        
        $points = calculatePoints(array_merge($user, ['completed_days' => $completed_days]));
        
        $completion_message = "*{$user['name']}, {$ai_response}*\n\n";
        $completion_message .= "*🎉 Day {$day} Complete!*\n\n";
        $completion_message .= "🏆 *+10 Points! Total: {$points} points*";
        
        sendMessage($user['chat_id'], $completion_message, getMainKeyboard());
        
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
            $final_message = "\n\n🎊 *INCREDIBLE! You've completed the entire 30-Day Challenge!* 🎊\n\n";
            $final_message .= "You can still view and edit your responses anytime using '📅 All Days'!";
            sendMessage($user['chat_id'], $final_message);
        }
        
        return true;
    } else {
        $encourage_message = getEncouragementMessage($day);
        sendMessage($user['chat_id'], $encourage_message);
        return false;
    }
}

// Calculate days since start
function getDaysSinceStart($start_date) {
    $start = new DateTime($start_date);
    $today = new DateTime();
    $interval = $start->diff($today);
    return $interval->days + 1;
}

// Check if user should receive daily challenge
function shouldReceiveChallenge($user) {
    if (!isset($user['start_date']) || !isset($user['current_day'])) {
        return false;
    }
    
    if ($user['current_day'] > 30) {
        return false;
    }
    
    if (isset($user['step']) && $user['step'] == 'postponed') {
        return false;
    }
    
    return true;
}

// Process daily challenges (called by cron job)
function processDailyChallenges() {
    $users = loadUsers();
    $updated_users = [];
    $sent_count = 0;
    
    foreach ($users as $user_id => $user) {
        if (!shouldReceiveChallenge($user)) {
            $updated_users[$user_id] = $user;
            continue;
        }
        
        $days_since_start = getDaysSinceStart($user['start_date']);
        $current_day = $user['current_day'] ?? 1;
        $completed_days = $user['completed_days'] ?? [];
        
        // Check if user missed previous days
        if ($days_since_start > $current_day) {
            $missed_days = $days_since_start - $current_day;
            
            // Mark missed days
            for ($day = $current_day; $day < $days_since_start && $day <= 30; $day++) {
                if (!isset($completed_days[$day]) || !$completed_days[$day]['completed']) {
                    $completed_days[$day] = [
                        'completed' => false,
                        'missed_at' => date('Y-m-d H:i:s'),
                        'response' => null
                    ];
                }
            }
            
            // Send missed days notification
            if ($missed_days > 0 && $days_since_start <= 30) {
                $missed_text = "*Hey {$user['name']}, I missed you! 💙*\n\n";
                $missed_text .= "It looks like you missed {$missed_days} day(s) of your confidence challenge. That's totally okay - life happens! 🤗\n\n";
                $missed_text .= "The important thing is that you're here now. Let's get back on track with today's challenge!\n\n";
                $missed_text .= "Use '📅 All Days' to see which days you can still complete. Remember, every step forward counts! 🌟";
                
                sendMessage($user['chat_id'], $missed_text, getMainKeyboard());
            }
        }
        
        // Send today's challenge if within 30 days
        if ($days_since_start <= 30) {
            $today_day = $days_since_start;
            
            // Check if today's challenge is already completed
            if (!isset($completed_days[$today_day]) || !$completed_days[$today_day]['completed']) {
                $challenge = getChallenge($today_day);
                
                if ($challenge) {
                    $morning_greeting = "*🌅 Good morning, {$user['name']}! 🌅*\n\n";
                    $morning_greeting .= "Ready for another step forward in your confidence journey? Let's make today amazing! ✨\n\n";
                    
                    $challenge_message = formatChallengeMessage($today_day, $challenge, $user['name']);
                    $full_message = $morning_greeting . $challenge_message;
                    
                    sendMessage($user['chat_id'], $full_message, getMainKeyboard());
                    $sent_count++;
                    
                    // Update user step to today's challenge
                    $user['step'] = "day_{$today_day}_active";
                    $user['current_day'] = $today_day;
                }
            }
        } else {
            // Challenge completed - send congratulations if not sent before
            if (!isset($user['final_congratulation_sent'])) {
                $final_text = "*🎊 AMAZING! You've reached the end of your 30-day journey! 🎊*\n\n";
                $final_text .= "What an incredible accomplishment, {$user['name']}! You've shown up for yourself for 30 days and transformed your confidence along the way.\n\n";
                $final_text .= "Use '📊 My Progress' to see your complete journey summary. You should be incredibly proud of yourself! 🌟\n\n";
                $final_text .= "Remember, this isn't the end - it's the beginning of a more confident you! 💪";
                
                sendMessage($user['chat_id'], $final_text, getMainKeyboard());
                $user['final_congratulation_sent'] = true;
                $user['step'] = 'challenge_completed';
            }
        }
        
        // Update user data
        $user['completed_days'] = $completed_days;
        $user['last_daily_check'] = date('Y-m-d H:i:s');
        $updated_users[$user_id] = $user;
    }
    
    // Save updated user data
    saveUsers($updated_users);
    
    // Log the operation
    $log_message = date('Y-m-d H:i:s') . " - Daily challenges processed. Sent: {$sent_count} messages\n";
    file_put_contents('daily_log.txt', $log_message, FILE_APPEND);
    
    return $sent_count;
}

// Check if this is a cron job request
if ($_POST['cron_job'] ?? false) {
    if (($_POST['action'] ?? '') === 'daily_challenges') {
        $sent_count = processDailyChallenges();
        echo json_encode(['status' => 'success', 'sent_count' => $sent_count]);
        exit;
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
    
    if ($data == 'start_now' && $user && $user['step'] == 'waiting_for_start') {
        $start_text = "*🎉 Wonderful, {$user['name']}! Your journey begins now!*\n\n";
        $start_text .= "Remember, I'm here as your trusted companion throughout this journey. Think of me as your personal confidence coach who's always here to listen, encourage, and celebrate your wins - big and small! 🤗\n\n";
        $start_text .= "Let's dive into your first challenge...\n\n";
        
        // Get Day 1 challenge from external file
        $challenge = getChallenge(1);
        $start_text .= formatChallengeMessage(1, $challenge, $user['name']);
        
        sendMessage($chat_id, $start_text, getMainKeyboard());
        
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
        $later_text = "*No problem at all, {$user['name']}! 😊*\n\n";
        $later_text .= "Take your time - personal growth can't be rushed! When you're ready to begin your confidence journey, just type /start and I'll be here waiting for you.\n\n";
        $later_text .= "Remember, the best time to plant a tree was 20 years ago. The second best time is now... whenever your 'now' feels right! 🌱\n\n";
        $later_text .= "I'm excited to be part of your transformation when you're ready! ✨";
        
        sendMessage($chat_id, $later_text);
        
        saveUser($user_id, array_merge($user, [
            'step' => 'postponed'
        ]));
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
    // Handle view day buttons
    elseif (strpos($data, 'view_day_') === 0) {
        $day = intval(str_replace('view_day_', '', $data));
        $completed_days = $user['completed_days'] ?? [];
        $challenge = getChallenge($day);
        
        if (!$challenge) {
            file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id'] . "&text=Challenge not found!");
            return;
        }
        
        $challenge_title = $challenge['title'];
        $is_completed = isset($completed_days[$day]) && $completed_days[$day]['completed'];
        
        if ($is_completed) {
            $current_response = $completed_days[$day]['response'];
            $completed_at = $completed_days[$day]['completed_at'];
            
            $view_message = "*📝 Day {$day}: {$challenge_title}*\n\n";
            $view_message .= "*Status:* ✅ Completed\n";
            $view_message .= "*Completed on:* " . date('M j, Y', strtotime($completed_at)) . "\n\n";
            $view_message .= "*Your Response:*\n";
            $view_message .= "_{$current_response}_\n\n";
            $view_message .= "Would you like to edit your response?";
            
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => '✏️ Edit Response', 'callback_data' => "edit_day_{$day}"],
                        ['text' => '🔙 Back to All Days', 'callback_data' => 'all_days']
                    ]
                ]
            ];
            
            sendMessage($chat_id, $view_message, $keyboard);
        } else {
            // Show challenge and let user complete it
            $challenge_message = formatChallengeMessage($day, $challenge, $user['name']);
            
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '🔙 Back to All Days', 'callback_data' => 'all_days']]
                ]
            ];
            
            sendMessage($chat_id, $challenge_message, $keyboard);
            
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
            $challenge = getChallenge($day);
            $challenge_title = $challenge ? $challenge['title'] : "Day {$day}";
            
            $edit_message = "*✏️ Edit Your Response - Day {$day}*\n";
            $edit_message .= "*Challenge:* {$challenge_title}\n\n";
            $edit_message .= "*Your current response:*\n";
            $edit_message .= "_{$current_response}_\n\n";
            $edit_message .= "Please type your new response:";
            
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '❌ Cancel Edit', 'callback_data' => "view_day_{$day}"]]
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
        list($message, $keyboard) = generateAllDaysView($user);
        sendMessage($chat_id, $message, $keyboard);
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
    // Handle locked day
    elseif (strpos($data, 'locked_day_') === 0) {
        $day = intval(str_replace('locked_day_', '', $data));
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id'] . "&text=Day {$day} is not available yet! Complete previous days first.");
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
            $welcome_back = "*Welcome back, {$user['name']}! 🌟*\n\n";
            $welcome_back .= "You're already on your confidence journey! Use the menu below to navigate:";
            sendMessage($chat_id, $welcome_back, getMainKeyboard());
        } else {
            $welcome_text = "*🌟 Welcome to the 30-Day Self-Confidence Challenge Bot! 🌟*\n\n";
            $welcome_text .= "I'm here to guide you through a scientifically-backed journey to boost your self-confidence over the next 30 days.\n\n";
            $welcome_text .= "This challenge is based on proven psychological principles and small daily actions that can make a big difference in how you see yourself.\n\n";
            $welcome_text .= "To get started, please tell me your name:";
            
            sendMessage($chat_id, $welcome_text);
            
            saveUser($user_id, [
                'chat_id' => $chat_id,
                'first_name' => $first_name,
                'step' => 'waiting_for_name',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    // Handle keyboard menu buttons
    elseif ($user && isset($user['start_date'])) {
        switch ($text) {
            case '📊 My Progress':
                $report = generateProgressReport($user);
                sendMessage($chat_id, $report, getMainKeyboard());
                break;
                
            case '📅 All Days':
                list($message, $keyboard) = generateAllDaysView($user);
                sendMessage($chat_id, $message, $keyboard);
                break;
                
            case '🎯 Today\'s Challenge':
                $current_day = $user['current_day'] ?? 1;
                $completed_days = $user['completed_days'] ?? [];
                
                if ($current_day > 30) {
                    sendMessage($chat_id, "🎉 You've completed all 30 days! Congratulations! Use '📅 All Days' to review your journey.", getMainKeyboard());
                } elseif (isset($completed_days[$current_day]) && $completed_days[$current_day]['completed']) {
                    sendMessage($chat_id, "✅ You've already completed Day {$current_day}! Great job! Use '📅 All Days' to see other days.", getMainKeyboard());
                } else {
                    $challenge = getChallenge($current_day);
                    if ($challenge) {
                        $message = formatChallengeMessage($current_day, $challenge, $user['name']);
                        sendMessage($chat_id, $message, getMainKeyboard());
                        
                        saveUser($user_id, array_merge($user, [
                            'step' => "day_{$current_day}_active",
                            'last_activity' => date('Y-m-d H:i:s')
                        ]));
                    }
                }
                break;
                
            case '❓ Help':
                $help_text = "*🆘 How to Use This Bot*\n\n";
                $help_text .= "*📊 My Progress* - View your journey overview, points, and completion percentage\n\n";
                $help_text .= "*📅 All Days* - See all 30 days with status indicators. Tap any day to view or edit your response\n\n";
                $help_text .= "*🎯 Today's Challenge* - Get your current day's challenge\n\n";
                $help_text .= "*Day Status Indicators:*\n";
                $help_text .= "✅ = Completed\n";
                $help_text .= "⭕ = Available to complete\n";
                $help_text .= "🔒 = Locked (complete previous days first)\n\n";
                $help_text .= "*Privacy:* All your responses are encrypted and stored securely. Only you can see them!\n\n";
                $help_text .= "Need more help? Contact the bot creator! 😊";
                
                sendMessage($chat_id, $help_text, getMainKeyboard());
                break;
                
            default:
                // Handle challenge responses
                if (preg_match('/^day_(\d+)_active$/', $user['step'], $matches)) {
                    $day = intval($matches[1]);
                    handleChallengeResponse($user_id, $user, $day, $text);
                } elseif (preg_match('/^edit_day_(\d+)$/', $user['step'], $matches)) {
                    $day = intval($matches[1]);
                    $new_response = trim($text);
                    
                    if (strlen($new_response) >= 3) {
                        $completed_days = $user['completed_days'] ?? [];
                        $completed_days[$day]['response'] = $new_response;
                        $completed_days[$day]['edited_at'] = date('Y-m-d H:i:s');
                        
                        $edit_success = "*✅ Response Updated Successfully!*\n\n";
                        $edit_success .= "*Day {$day} - New Response:*\n";
                        $edit_success .= "_{$new_response}_\n\n";
                        $edit_success .= "Your response has been saved! Keep up the amazing work! 🌟";
                        
                        sendMessage($chat_id, $edit_success, getMainKeyboard());
                        
                        // Return user to normal state
                        saveUser($user_id, array_merge($user, [
                            'step' => 'waiting_for_next_day',
                            'completed_days' => $completed_days,
                            'last_activity' => date('Y-m-d H:i:s')
                        ]));
                    } else {
                        sendMessage($chat_id, "Please provide a response with at least 3 characters. 😊");
                    }
                } else {
                    sendMessage($chat_id, "Hi {$user['name']}! 😊 Use the menu below to navigate:", getMainKeyboard());
                }
                break;
        }
    }
    // Handle name input
    elseif ($user && $user['step'] == 'waiting_for_name') {
        $name = trim($text);
        
        if (strlen($name) > 2 && strlen($name) < 50) {
            $intro_text = "*Great to meet you, {$name}! 🎉*\n\n";
            $intro_text .= "*🧠 Why This Challenge Works:*\n\n";
            $intro_text .= "Research in neuroplasticity shows that our brains can form new neural pathways through consistent practice. This challenge uses:\n\n";
            $intro_text .= "• *Behavioral activation* - Small daily actions create positive momentum\n";
            $intro_text .= "• *Cognitive restructuring* - Shifting negative self-talk to positive affirmations\n";
            $intro_text .= "• *Exposure therapy principles* - Gradually stepping outside your comfort zone\n";
            $intro_text .= "• *Self-efficacy theory* - Building confidence through mastery experiences\n\n";
            $intro_text .= "🔒 *Your Privacy Matters:*\nI want you to feel completely safe sharing with me. All your responses and progress are encrypted and stored securely. Nobody has access to your personal information - not even the bot creator. This is your private space to grow and reflect. 🤗\n\n";
            $intro_text .= "Are you ready to start your transformation journey right now? 🚀";
            
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => '✨ Yes, let\'s start now!', 'callback_data' => 'start_now'],
                        ['text' => '⏰ I\'ll start later', 'callback_data' => 'start_later']
                    ]
                ]
            ];
            
            sendMessage($chat_id, $intro_text, $keyboard);
            
            saveUser($user_id, [
                'chat_id' => $chat_id,
                'first_name' => $first_name,
                'name' => $name,
                'step' => 'waiting_for_start',
                'created_at' => $user['created_at'] ?? date('Y-m-d H:i:s')
            ]);
        } else {
            sendMessage($chat_id, "Please enter a valid name (2-50 characters):");
        }
    }
    // Handle users who postponed and want to restart
    elseif ($user && $user['step'] == 'postponed' && $text == '/start') {
        $restart_text = "*Welcome back, {$user['name']}! 🌟*\n\n";
        $restart_text .= "I'm so glad you're ready to begin your confidence journey! Are you ready to start with Day 1?";
        
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '✨ Yes, let\'s start now!', 'callback_data' => 'start_now'],
                    ['text' => '⏰ Maybe later', 'callback_data' => 'start_later']
                ]
            ]
        ];
        
        sendMessage($chat_id, $restart_text, $keyboard);
        
        saveUser($user_id, array_merge($user, [
            'step' => 'waiting_for_start'
        ]));
    }
    // Default response for new users
    else {
        if ($user) {
            sendMessage($chat_id, "Hi {$user['name']}! 😊 Use the menu below to navigate:", getMainKeyboard());
        } else {
            sendMessage($chat_id, "Please start by typing /start 🌟");
        }
    }
}
?>
