<?php
// config.php
define('BOT_TOKEN', '');
define('GEMINI_API_KEY', '');
define('DATA_FILE', 'users.json');

// Include challenges data
require_once 'challenges.php';
require_once 'challenges_fa.php';

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
    // Simple Persian detection based on Persian characters
    $persian_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
    return preg_match($persian_pattern, $text) ? 'fa' : 'en';
}

// Get Persian challenge content
function getPersianChallenge($day) {
    $fa_challenge = getFaChallenge($day);
    if (!$fa_challenge) {
        return "متأسفانه این چالش به فارسی موجود نیست.";
    }
    
    $message = "*{$fa_challenge['title']}*\n\n";
    $message .= $fa_challenge['description'] . "\n\n";
    $message .= "💭 *سؤال:* " . $fa_challenge['prompt'];
    
    return $message;
}

// Get Persian gratitude prompt
function getPersianGratitudePrompt() {
    $message = "*🙏 لحظه‌ای برای شکرگزاری*\n\n";
    $message .= "یک چیزی که الان ازش ممنونی چیه?\n\n";
    $message .= "می‌تونه بزرگ یا کوچیک باشه - سلامتیت، یه نفر، یه لحظه، هر چیزی که گرما به قلبت می‌ده.\n\n";
    $message .= "_با من به اشتراک بذار:_";
    
    return $message;
}

// Get final reflection prompt in appropriate language
function getFinalReflectionPrompt($language = 'en') {
    if ($language == 'fa') {
        $message = "*🌟 لحظه‌ای برای تأمل نهایی 🌟*\n\n";
        $message .= "تبریک! شما این سفر 30 روزه شگفت‌انگیز را تکمیل کردید! 🎉\n\n";
        $message .= "قبل از اینکه به پایان برسیم، می‌خواهم از شما بپرسم:\n\n";
        $message .= "💭 *این سفر چطور برایتان بود؟ چه تجربه‌ای داشتید؟*\n\n";
        $message .= "لطفاً به صورت صادقانه در چند خط تجربه و احساستان را از این 30 روز با من در میان بگذارید. این بازخورد شما برای من بسیار ارزشمند است.\n\n";
        $message .= "_منتظر شنیدن تجربه واقعی شما هستم..._";
    } else {
        $message = "*🌟 A Moment for Final Reflection 🌟*\n\n";
        $message .= "Congratulations! You've completed this amazing 30-day journey! 🎉\n\n";
        $message .= "Before we conclude, I want to ask you:\n\n";
        $message .= "💭 *How was this journey for you? What was your experience?*\n\n";
        $message .= "Please share honestly in a few lines your experience and feelings from these 30 days. Your feedback is incredibly valuable to me.\n\n";
        $message .= "_I'm waiting to hear about your real experience..._";
    }
    
    return $message;
}

// Generate comprehensive final feedback using all responses
function generateFinalFeedback($user, $final_reflection, $language = 'en') {
    $completed_days = $user['completed_days'] ?? [];
    
    // Collect all responses
    $all_responses = "";
    for ($day = 1; $day <= 30; $day++) {
        if (isset($completed_days[$day]) && $completed_days[$day]['completed']) {
            $all_responses .= "Day {$day}: " . $completed_days[$day]['response'] . "\n\n";
        }
    }
    
    if ($language == 'fa') {
        $prompt = "شما یک مربی اعتماد به نفس حرفه‌ای و همدل هستید که 30 روز با این شخص همراه بوده‌اید.

این تمام پاسخ‌های او به چالش‌های 30 روزه است:
{$all_responses}

و این بازخورد نهایی او درباره کل سفر است:
\"{$final_reflection}\"

CRITICAL: بر اساس تمام پاسخ‌ها و این بازخورد نهایی، یک پیام شخصی‌سازی شده و عمیق به او بنویسید.

پیام شما باید شامل این موارد باشد:

بخش 1 - قدردانی از سفر مشترک (2-3 جمله):
- از همراهی او در این 30 روز تشکر کنید
- به رشد و تلاش‌های او اشاره کنید
- احساس واقعی خود را از دیدن پیشرفتش بیان کنید

بخش 2 - تحلیل عمیق شخصیت بر اساس تمام پاسخ‌ها (4-5 جمله):
- الگوها و نقاط قوت مشخصی که در پاسخ‌هایش دیدید
- تغییرات و رشدی که از روز 1 تا 30 مشاهده کردید
- ویژگی‌های منحصر به فرد شخصیتی که برجسته شدند
- چالش‌هایی که با شجاعت پشت سر گذاشت
- این تحلیل باید بسیار شخصی و مبتنی بر پاسخ‌های واقعی او باشد

بخش 3 - راهنمایی برای آینده (3-4 جمله):
- توصیه‌های عملی بر اساس نقاط قوت و چالش‌هایی که شناختید
- یادآوری اینکه این سفر تازه شروع شده و ادامه دارد
- انگیزه و امیدواری برای ادامه مسیر
- پیشنهاد گام‌های بعدی متناسب با شخصیت او

نکات مهم:
- از کلمات و مفاهیمی که خود کاربر در پاسخ‌هایش استفاده کرده، اشاره کنید
- بسیار شخصی، صمیمی و واقعی بنویسید
- نشان دهید که واقعاً تمام سفرش را دیده‌اید
- لحن گرم، حمایتگر و الهام‌بخش داشته باشید
- از کلیشه‌های تکراری پرهیز کنید

فرمت: یک پیام یکپارچه و روان در 9-12 جمله، بدون عنوان یا شماره‌گذاری بخش‌ها، فقط متن پیوسته و قلبی.";
    } else {
        $prompt = "You are a professional, empathetic confidence coach who has accompanied this person for 30 days.

These are all their responses to the 30-day challenges:
{$all_responses}

And this is their final reflection on the whole journey:
\"{$final_reflection}\"

CRITICAL: Based on all responses and this final reflection, write a personalized and deep message to them.

Your message should include:

Part 1 - Gratitude for the Shared Journey (2-3 sentences):
- Thank them for their companionship during these 30 days
- Reference their growth and efforts
- Express your genuine feelings about witnessing their progress

Part 2 - Deep Personality Analysis Based on All Responses (4-5 sentences):
- Specific patterns and strengths you saw in their responses
- Changes and growth you observed from day 1 to 30
- Unique personality traits that emerged
- Challenges they courageously overcame
- This analysis must be very personal and based on their actual responses

Part 3 - Guidance for the Future (3-4 sentences):
- Practical recommendations based on the strengths and challenges you identified
- Reminder that this journey has just begun and continues
- Motivation and hope for continuing the path
- Suggestions for next steps suited to their personality

Important Notes:
- Reference words and concepts the user themselves used in their responses
- Write very personally, intimately, and authentically
- Show that you truly saw their entire journey
- Keep tone warm, supportive, and inspiring
- Avoid repetitive clichés

Format: One cohesive, flowing message in 9-12 sentences, without titles or section numbering, just continuous heartfelt text.";
    }

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.9,
            'topK' => 40,
            'topP' => 0.95,
            'maxOutputTokens' => 800,
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
            ? "تبریک می‌گویم! شما این سفر 30 روزه را با موفقیت به پایان رساندید. این تازه شروع سفر واقعی شماست. با اعتماد به نفسی که ساخته‌اید، به پیش بروید! 🌟"
            : "Congratulations! You have successfully completed this 30-day journey. This is just the beginning of your real journey. Move forward with the confidence you've built! 🌟";
    }
    
    $result = json_decode($response, true);
    
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return trim($result['candidates'][0]['content']['parts'][0]['text']);
    }
    
    return $language == 'fa' 
        ? "تبریک می‌گویم! شما این سفر 30 روزه را با موفقیت به پایان رساندید. این تازه شروع سفر واقعی شماست. با اعتماد به نفسی که ساخته‌اید، به پیش بروید! 🌟"
        : "Congratulations! You have successfully completed this 30-day journey. This is just the beginning of your real journey. Move forward with the confidence you've built! 🌟";
}

// Generate AI coaching response using Gemini API
function generateCoachingResponse($challenge_title, $user_response, $language = 'en', $custom_prompt = null) {
    if ($custom_prompt) {
        $prompt = $custom_prompt;
    } elseif ($language == 'fa') {
        $prompt = "شما یک مربی اعتماد به نفس حرفه‌ای و همدل هستید که به شخصی پاسخ می‌دهید که تازه چالش روزانه اعتماد به نفس را تکمیل کرده است.

چالش: \"{$challenge_title}\"
پاسخ کاربر: \"{$user_response}\"

CRITICAL: Do NOT write your analysis or thought process. Write ONLY the final 3-sentence response to the user.

در ذهن خود تحلیل کنید (اما ننویسید):
- احساس و انرژی پشت کلمات
- نقاط قوت و شجاعت
- چالش‌ها یا تردیدها
- فرصت‌های رشد

فقط پاسخ نهایی را در 3 جمله بنویسید:

جمله 1 - بازتاب احساسی:
- از کلمات و مفاهیم خود کاربر استفاده کنید، اما با عبارات متنوع بیان کنید
- به جای تکرار دقیق کلمات، از هم‌معنی‌ها و بیان‌های مشابه استفاده کنید
- مثال: اگر گفت احساس غرور کردم شما بگویید این حس دستاورد واقعاً چشمگیر است

جمله 2 - شناخت و تأیید:
- یک جنبه خاص از تلاش یا بینش آن‌ها را برجسته کنید
- نشان دهید که واقعاً پاسخشان را خوانده و درک کرده‌اید

جمله 3 - توصیه عملی برای رشد (الزامی):
این بخش الزامی است - همیشه یک پیشنهاد مرتبط برای گام بعدی:

اگر پاسخ مثبت و پرانرژی بود:
- چطور این انرژی رو به چالش بعدی منتقل کنید؟
- یک قدم کوچک دیگه برای تقویت این احساس

اگر پاسخ با تردید یا چالش همراه بود:
- یک تمرین ساده برای غلبه بر این احساس
- یک دیدگاه جایگزین یا چارچوب فکری
- یک قدم کوچک و قابل دستیابی

اگر پاسخ خنثی، کوتاه، یا سوالی بود:
- یک سؤال تأملی برای عمیق‌تر شدن
- یک راه برای تمرین بیشتر در زندگی روزمره
- یک بینش کوچک درباره اهمیت این چالش

نکات مهم:
- هرگز جملات کاربر را عیناً تکرار نکنید
- از واژگان غنی و متنوع استفاده کنید
- لحن را با احساس کاربر هماهنگ کنید
- توصیه باید کوتاه، عملی و مرتبط با همین چالش باشد
- از کلیشه‌های تکراری خودداری کنید
- هیچ توضیح یا تحلیل اضافی ننویسید

مثال برای پاسخ کوتاه یا سوالی:
کاربر: مثلا چی؟!
پاسخ خوب: انتخاب یک هدف کوچک می‌تونه گیج‌کننده باشه، اما همین سوال نشون می‌ده آماده‌ای شروع کنی! یک هدف ساده می‌تونه باشه: امروز به یک نفر لبخند بزنم، یا 10 دقیقه قدم بزنم. فردا، سعی کن یک کار کوچک انجام بدی که بهت حس موفقیت می‌ده، حتی اگر خیلی ساده باشه.

فرمت نهایی: فقط 3 جمله مستقیم به کاربر، بدون عنوان، بدون شماره‌گذاری، بدون emoji اضافی، بدون توضیح تحلیل.";
    } else {
        $prompt = "You are a professional, empathetic confidence coach responding to someone who just completed a daily self-confidence challenge.

Challenge: \"{$challenge_title}\"
User's response: \"{$user_response}\"

CRITICAL: Do NOT write your analysis or thought process. Write ONLY the final 3-sentence response to the user.

Analyze mentally (but do not write):
- Emotion and energy behind their words
- Their strengths and courage
- Any struggles or doubts
- Growth opportunities

Write ONLY the final response in 3 sentences:

Sentence 1 - Emotional Reflection:
- Use concepts from THEIR words but express with variety
- Instead of repeating exact phrases, use synonyms and alternative expressions
- Example: If they said I felt proud you say That sense of accomplishment is truly powerful

Sentence 2 - Recognition and Validation:
- Highlight a specific aspect of their effort or insight
- Show you truly read and understood their response

Sentence 3 - Actionable Growth Suggestion (MANDATORY):
This part is MANDATORY - always provide a relevant suggestion for their next step:

If response was positive and energetic:
- How can you carry this momentum forward?
- One small way to amplify this feeling

If response showed doubt or struggle:
- One simple practice to overcome this feeling
- An alternative perspective or reframing
- One tiny achievable step forward

If response was neutral, brief, or a question:
- A reflective question to go deeper
- A way to practice this more in daily life
- A small insight about why this challenge matters

Critical Rules:
- NEVER repeat the user's exact phrases word-for-word
- Use rich varied vocabulary with synonyms
- Match your tone to their emotional state
- The suggestion must be short practical and related to THIS challenge
- Avoid repetitive cliches
- Do NOT write any explanations or analysis

Example for brief or questioning response:
User: Like what?!
Good response: Choosing a small goal can feel confusing, but asking shows you're ready to start! A simple goal could be: smile at one person today, or take a 10-minute walk. Tomorrow, try picking one tiny action that gives you a sense of accomplishment, even if it seems small.

Final format: Just 3 direct sentences to the user, no headers, no numbering, no extra emojis, no analysis explanation.";
    }

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.8,
            'topK' => 40,
            'topP' => 0.95,
            'maxOutputTokens' => 150,
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
            ? "قدم‌گذاشتن در این مسیر نشان از شجاعت واقعی شماست. هر پاسخی که می‌نویسید، یک سرمایه‌گذاری در نسخه بهتر خودتان است. فردا، سعی کنید یک لحظه اضافی برای تأمل درباره پیشرفت‌تان اختصاص دهید."
            : "Taking this step shows genuine courage. Every response you share is an investment in a better version of yourself. Tomorrow, try setting aside one extra moment to reflect on your progress.";
    }
    
    $result = json_decode($response, true);
    
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return trim($result['candidates'][0]['content']['parts'][0]['text']);
    }
    
    return $language == 'fa' 
        ? "قدم‌گذاشتن در این مسیر نشان از شجاعت واقعی شماست. هر پاسخی که می‌نویسید، یک سرمایه‌گذاری در نسخه بهتر خودتان است. فردا، سعی کنید یک لحظه اضافی برای تأمل درباره پیشرفت‌تان اختصاص دهید."
        : "Taking this step shows genuine courage. Every response you share is an investment in a better version of yourself. Tomorrow, try setting aside one extra moment to reflect on your progress.";
}

// Get main keyboard menu
function getMainKeyboard() {
    return [
        'keyboard' => [
            [['text' => '📊 My Progress'], ['text' => '📅 All Days']],
            [['text' => '🎯 Today\'s Challenge'], ['text' => '🙏 Daily Gratitude']],
            [['text' => '❓ Help']]
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
        $challenge = getChallenge($day);
        $challenge_title = $challenge ? $challenge['title'] : "Day {$day} Challenge";
        
        // Generate AI coaching response in appropriate language
        $ai_response = generateCoachingResponse($challenge_title, $response, $response_language);
        
        $points = calculatePoints(array_merge($user, ['completed_days' => $completed_days]));
        
        if ($response_language == 'fa') {
            $completion_message = "*{$user['name']}، {$ai_response}*\n\n";
            $completion_message .= "*🎉 روز {$day} تکمیل شد!*\n\n";
            $completion_message .= "🏆 *+10 امتیاز! مجموع: {$points} امتیاز*";
        } else {
            $completion_message = "*{$user['name']}, {$ai_response}*\n\n";
            $completion_message .= "*🎉 Day {$day} Complete!*\n\n";
            $completion_message .= "🏆 *+10 Points! Total: {$points} points*";
        }
        
        sendMessage($user['chat_id'], $completion_message, getMainKeyboard());
        
        // Update user data
        $next_day = $day + 1;
        
        // If it's day 30, ask for final reflection
        if ($day == 30) {
            $final_prompt = getFinalReflectionPrompt($response_language);
            sendMessage($user['chat_id'], $final_prompt);
            
            saveUser($user_id, array_merge($user, [
                'step' => 'waiting_final_reflection',
                'completed_days' => $completed_days,
                'current_day' => 30,
                'last_activity' => date('Y-m-d H:i:s'),
                'day_30_language' => $response_language
            ]));
        } else {
            saveUser($user_id, array_merge($user, [
                'step' => $next_day <= 30 ? 'waiting_for_next_day' : 'challenge_completed','completed_days' => $completed_days,
                'current_day' => min($next_day, 30),
                'last_activity' => date('Y-m-d H:i:s')
            ]));
        }
        
        return true;
    } else {
        $response_language = detectLanguage($response);
        if ($response_language == 'fa') {
            sendMessage($user['chat_id'], "لطفاً یک پاسخ با حداقل 3 کاراکتر بنویسید. 😊");
        } else {
            sendMessage($user['chat_id'], "Please provide a response with at least 3 characters. 😊");
        }
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

// Format challenge message with translation button
function formatChallengeMessage($day, $challenge, $user_name, $chat_id) {
    $message = "*🎉 Dear {$user_name}! Ready for today's adventure?*\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "*📅 DAY {$day}: {$challenge['title']}*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= $challenge['description'] . "\n\n";
    $message .= "💡 *Why this works:* " . $challenge['why_it_works'] . "\n\n";
    $message .= $challenge['prompt'];
    
    // Add inline keyboard with Persian translation option
    $keyboard = [
        'inline_keyboard' => [
            [['text' => 'Persian translation', 'callback_data' => "translate_fa_{$day}"]]
        ]
    ];
    
    // Send message with keyboard
    sendMessage($chat_id, $message, $keyboard);
    return $message;
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
        formatChallengeMessage(1, $challenge, $user['name'], $chat_id);
        
        sendMessage($chat_id, $start_text);
        
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
    // Handle translate to Persian for challenges
    elseif (strpos($data, 'translate_fa_') === 0) {
        $day = intval(str_replace('translate_fa_', '', $data));
        
        $persian_content = getPersianChallenge($day);
        
        $message = "*🔍 توضیح به فارسی - روز {$day}*\n\n";
        $message .= $persian_content . "\n\n";
        $message .= "_برای پاسخ دادن، متن خود را تایپ کنید. می‌توانید به فارسی یا انگلیسی پاسخ دهید._";
        
        $keyboard = [
            'inline_keyboard' => [
                [['text' => '🔙 Back to English', 'callback_data' => "view_day_{$day}"]]
            ]
        ];
        
        sendMessage($chat_id, $message, $keyboard);
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
    // Handle translate to Persian for gratitude
    elseif ($data == 'gratitude_fa') {
        $persian_gratitude = getPersianGratitudePrompt();
        
        $keyboard = [
            'inline_keyboard' => [
                [['text' => '🔙 Back to English', 'callback_data' => 'gratitude_en']]
            ]
        ];
        
        sendMessage($chat_id, $persian_gratitude, $keyboard);
        
        saveUser($user_id, array_merge($user, [
            'step' => 'gratitude_active',
            'last_activity' => date('Y-m-d H:i:s')
        ]));
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback['id']);
    }
    // Handle back to English for gratitude
    elseif ($data == 'gratitude_en') {
        $gratitude_prompt = "*🙏 Take a moment for gratitude*\n\n";
        $gratitude_prompt .= "What's ONE thing you're grateful for right now?\n\n";
        $gratitude_prompt .= "It can be big or small - your health, a person, a moment, anything that brings warmth to your heart.\n\n";
        $gratitude_prompt .= "_Share it with me:_";
        
        $keyboard = [
            'inline_keyboard' => [
                [['text' => 'Persian translation', 'callback_data' => 'gratitude_fa']]
            ]
        ];
        
        sendMessage($chat_id, $gratitude_prompt, $keyboard);
        
        saveUser($user_id, array_merge($user, [
            'step' => 'gratitude_active',
            'last_activity' => date('Y-m-d H:i:s')
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
            
            $view_message = "*🔍 Day {$day}: {$challenge_title}*\n\n";
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
            formatChallengeMessage($day, $challenge, $user['name'], $chat_id);
            
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '🔙 Back to All Days', 'callback_data' => 'all_days']]
                ]
            ];
            
            // Send message with only back button since formatChallengeMessage already sends the main message
            sendMessage($chat_id, "_Type your response below or use the Persian translation button above._", $keyboard);
            
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
            $welcome_text = "*Hi! I'm so glad you're here. 🌱*\n\n";
            $welcome_text .= "I'm here to help you build self-love and confidence with simple daily practices. Think of me as your supportive friend who's always in your corner.\n\n";
            $welcome_text .= "What should I call you?";
            
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
                        formatChallengeMessage($current_day, $challenge, $user['name'], $chat_id);
                        
                        saveUser($user_id, array_merge($user, [
                            'step' => "day_{$current_day}_active",
                            'last_activity' => date('Y-m-d H:i:s')
                        ]));
                    }
                }
                break;
                
            case '🙏 Daily Gratitude':
                $gratitude_prompt = "*🙏 Take a moment for gratitude*\n\n";
                $gratitude_prompt .= "What's ONE thing you're grateful for right now?\n\n";
                $gratitude_prompt .= "It can be big or small - your health, a person, a moment, anything that brings warmth to your heart.\n\n";
                $gratitude_prompt .= "_Share it with me:_";
                
                $keyboard = [
                    'inline_keyboard' => [
                        [['text' => 'Persian translation', 'callback_data' => 'gratitude_fa']]
                    ]
                ];
                
                sendMessage($chat_id, $gratitude_prompt, $keyboard);
                
                saveUser($user_id, array_merge($user, [
                    'step' => 'gratitude_active',
                    'last_activity' => date('Y-m-d H:i:s')
                ]));
                break;
                
            case '❓ Help':
                $help_text = "*🆘 How to Use This Bot*\n\n";
                $help_text .= "*📊 My Progress* - View your journey overview, points, and completion percentage\n\n";
                $help_text .= "*📅 All Days* - See all 30 days with status indicators. Tap any day to view or edit your response\n\n";
                $help_text .= "*🎯 Today's Challenge* - Get your current day's challenge\n\n";
                $help_text .= "*🙏 Daily Gratitude* - Practice gratitude anytime you want\n\n";
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
                }
                // Handle final reflection after day 30
                elseif ($user['step'] == 'waiting_final_reflection') {
                    $final_reflection = trim($text);
                    
                    if (strlen($final_reflection) >= 10) {
                        $reflection_language = $user['day_30_language'] ?? 'en';
                        
                        // Generate comprehensive final feedback
                        $final_feedback = generateFinalFeedback($user, $final_reflection, $reflection_language);
                        
                        if ($reflection_language == 'fa') {
                            $thank_message = "*🌟 پایان یک سفر، شروع مسیری جدید 🌟*\n\n";
                            $thank_message .= $final_feedback . "\n\n";
                            $thank_message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
                            $thank_message .= "این سفر تازه شروع شده است. با اعتماد به نفسی که ساخته‌اید، به جلو حرکت کنید! 🚀\n\n";
                            $thank_message .= "شما همیشه می‌توانید به پاسخ‌های گذشته خود از طریق '📅 All Days' مراجعه کنید و آن‌ها را مرور کنید.";
                        } else {
                            $thank_message = "*🌟 The End of One Journey, The Beginning of Another 🌟*\n\n";
                            $thank_message .= $final_feedback . "\n\n";
                            $thank_message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
                            $thank_message .= "This journey has just begun. Move forward with the confidence you've built! 🚀\n\n";
                            $thank_message .= "You can always revisit and review your past responses through '📅 All Days'.";
                        }
                        
                        sendMessage($chat_id, $thank_message, getMainKeyboard());
                        
                        // Save final reflection and mark journey as complete
                        saveUser($user_id, array_merge($user, [
                            'step' => 'journey_complete',
                            'final_reflection' => $final_reflection,
                            'final_reflection_date' => date('Y-m-d H:i:s'),
                            'last_activity' => date('Y-m-d H:i:s')
                        ]));
                    } else {
                        $reflection_language = $user['day_30_language'] ?? 'en';
                        if ($reflection_language == 'fa') {
                            sendMessage($chat_id, "لطفاً تجربه خود را با حداقل 10 کاراکتر به اشتراک بگذارید تا بتوانم بازخورد مناسبی به شما بدهم. 😊");
                        } else {
                            sendMessage($chat_id, "Please share your experience with at least 10 characters so I can give you proper feedback. 😊");
                        }
                    }
                }
                // Handle gratitude responses
                elseif ($user['step'] == 'gratitude_active') {
                    $gratitude_text = trim($text);
                    
                    if (strlen($gratitude_text) >= 3) {
                        $response_language = detectLanguage($gratitude_text);
                        
                        // Generate AI response for gratitude with custom prompt
                        if ($response_language == 'fa') {
                            $gratitude_prompt = "شما یک مربی شکرگزاری و ذهن‌آگاهی هستید که به شخصی پاسخ می‌دهید که تازه یک لحظه شکرگزاری را با شما به اشتراک گذاشته است.

پاسخ کاربر: \"{$gratitude_text}\"

ابتدا به ژرفای پاسخ آن‌ها توجه کنید:
- چه چیزی برایشان ارزشمند است؟
- چه احساسی پشت این قدردانی وجود دارد؟
- این شکرگزاری چقدر بزرگ یا کوچک است؟

یک پاسخ گرم و صمیمی در 2-3 جمله بنویسید که شامل این باشد:

جمله 1 - بازتاب معنادار:
- به چیز خاصی که آن‌ها گفتند اشاره کنید (نه عمومی!)
- از کلمات مشابه استفاده کنید، نه تکرار دقیق
- عمق یا زیبایی انتخابشان را نشان دهید

جمله 2 - ارتباط احساسی:
- یک بینش کوچک درباره چرایی اهمیت این چیز
- یا یک نکته درباره قدرت شکرگزاری
- یا تأیید احساسات آن‌ها

جمله 3 (اختیاری) - دعوت به عمل:
- یک پیشنهاد ساده برای گسترش این شکرگزاری
- یک سؤال تأملی برای تعمیق
- یک راه برای ماندگار کردن این لحظه

نکات مهم:
- از تکرار کلمات کاربر خودداری کنید
- لحن گرم، صمیمی و انسانی باشد
- از کلیشه‌های تکراری مثل چه عالی یا ممنون که گفتید پرهیز کنید
- شکرگزاری‌های کوچک را به اندازه بزرگ‌ها ارج بگذارید

مثال:
کاربر: ممنونم بابت سلامتیم
پاسخ ضعیف: چه عالی که از سلامتیت ممنونی! این خیلی مهمه.
پاسخ قوی: داشتن بدنی که هر روز همراهت هست، واقعاً یک هدیه گرانبهاست. این آگاهی ساده می‌تونه نحوه مراقبت از خودت رو تغییر بده. امروز یه لحظه، نفس عمیق بکش و به بدنت تشکر کن.

فرمت: 2-3 جمله کوتاه، بدون emoji اضافی، بدون عنوان.";
                        } else {
                            $gratitude_prompt = "You are a mindfulness and gratitude coach responding to someone who just shared a moment of gratitude with you.

User's response: \"{$gratitude_text}\"

First, notice the depth of their response:
- What do they value?
- What emotion is behind this appreciation?
- How big or small is this gratitude?

Write a warm, authentic response in 2-3 sentences that includes:

Sentence 1 - Meaningful Reflection:
- Reference the specific thing they mentioned (not generic!)
- Use similar words, not exact repetition
- Show the depth or beauty of their choice

Sentence 2 - Emotional Connection:
- A small insight about why this thing matters
- Or a note about the power of gratitude
- Or validation of their feelings

Sentence 3 (optional) - Invitation to Action:
- A simple suggestion to expand this gratitude
- A reflective question to go deeper
- A way to make this moment last

Critical Rules:
- Avoid repeating the user's exact words
- Keep tone warm, intimate, and human
- Avoid repetitive cliches like That's great or Thanks for sharing
- Honor small gratitudes as much as big ones

Example:
User: I'm grateful for my health
Weak response: That's great that you're grateful for your health! It's very important.
Strong response: Having a body that shows up for you every day is truly a precious gift. This simple awareness can transform how you care for yourself. Today, take one deep breath and thank your body for all it does.

Format: 2-3 short sentences, no extra emojis, no headers.";
                        }
                        
                        $ai_gratitude = generateCoachingResponse("Daily Gratitude Practice", $gratitude_text, $response_language, $gratitude_prompt);
                        
                        if ($response_language == 'fa') {
                            $thank_message = "*{$ai_gratitude}*\n\n";
                            $thank_message .= "💚 شکرگزاری یک تمرین قدرتمند است - ممنون که این لحظه رو با من به اشتراک گذاشتی!";
                        } else {
                            $thank_message = "*{$ai_gratitude}*\n\n";
                            $thank_message .= "💚 Gratitude is a powerful practice - thank you for sharing this moment with me!";
                        }
                        
                        sendMessage($chat_id, $thank_message, getMainKeyboard());
                        
                        saveUser($user_id, array_merge($user, [
                            'step' => 'waiting_for_next_day',
                            'last_activity' => date('Y-m-d H:i:s')
                        ]));
                    } else {
                        $response_language = detectLanguage($gratitude_text);
                        if ($response_language == 'fa') {
                            sendMessage($chat_id, "لطفاً یک پاسخ با حداقل 3 کاراکتر بنویسید. 😊");
                        } else {
                            sendMessage($chat_id, "Please provide a response with at least 3 characters. 😊");
                        }
                    }
                }
                elseif (preg_match('/^edit_day_(\d+)$/', $user['step'], $matches)) {
                    $day = intval($matches[1]);
                    $new_response = trim($text);
                    
                    if (strlen($new_response) >= 3) {
                        $response_language = detectLanguage($new_response);
                        $completed_days = $user['completed_days'] ?? [];
                        $completed_days[$day]['response'] = $new_response;
                        $completed_days[$day]['edited_at'] = date('Y-m-d H:i:s');
                        $completed_days[$day]['language'] = $response_language;
                        
                        if ($response_language == 'fa') {
                            $edit_success = "*✅ پاسخ با موفقیت به‌روزرسانی شد!*\n\n";
                            $edit_success .= "*روز {$day} - پاسخ جدید:*\n";
                            $edit_success .= "_{$new_response}_\n\n";
                            $edit_success .= "پاسخ شما ذخیره شد! به کار فوق‌العاده‌تان ادامه دهید! 🌟";
                        } else {
                            $edit_success = "*✅ Response Updated Successfully!*\n\n";
                            $edit_success .= "*Day {$day} - New Response:*\n";
                            $edit_success .= "_{$new_response}_\n\n";
                            $edit_success .= "Your response has been saved! Keep up the amazing work! 🌟";
                        }
                        
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
