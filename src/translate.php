<?php
// translate.php - Translation helper functions

require_once 'lang_en.php';
require_once 'lang_fa.php';

// Get translation for a key
function t($key, $lang = 'en', $replacements = []) {
    // Load language data
    $translations = ($lang === 'fa') ? getLangFa() : getLangEn();
    
    // Get translation or return key if not found
    $text = isset($translations[$key]) ? $translations[$key] : $key;
    
    // Replace placeholders
    foreach ($replacements as $placeholder => $value) {
        $text = str_replace('{' . $placeholder . '}', $value, $text);
    }
    
    return $text;
}

// Get user's language with fallback
function getUserLanguage($user) {
    // Check if user has language set
    if (isset($user['language']) && in_array($user['language'], ['en', 'fa'])) {
        return $user['language'];
    }
    
    // For existing users without language, detect from their responses
    if (isset($user['completed_days']) && !empty($user['completed_days'])) {
        return detectUserLanguageFromResponses($user['completed_days']);
    }
    
    // Default to English for new users
    return 'en';
}

// Detect language from user's past responses
function detectUserLanguageFromResponses($completed_days) {
    $persian_count = 0;
    $english_count = 0;
    
    foreach ($completed_days as $day => $data) {
        if (isset($data['response']) && !empty($data['response'])) {
            $lang = detectLanguage($data['response']);
            if ($lang === 'fa') {
                $persian_count++;
            } else {
                $english_count++;
            }
        }
    }
    
    // Return language with most responses
    return ($persian_count > $english_count) ? 'fa' : 'en';
}

// Get main keyboard with language
function getMainKeyboard($lang = 'en') {
    return [
        'keyboard' => [
            [
                ['text' => t('btn_progress', $lang)], 
                ['text' => t('btn_all_days', $lang)]
            ],
            [
                ['text' => t('btn_today_challenge', $lang)], 
                ['text' => t('btn_gratitude', $lang)]
            ],
            [
                ['text' => t('btn_help', $lang)],
                ['text' => t('btn_change_language', $lang)]
            ]
        ],
        'resize_keyboard' => true,
        'persistent' => true
    ];
}

// Get language selection keyboard
function getLanguageSelectionKeyboard() {
    return [
        'inline_keyboard' => [
            [
                ['text' => '🇬🇧 English', 'callback_data' => 'set_lang_en'],
                ['text' => '🇮🇷 فارسی', 'callback_data' => 'set_lang_fa']
            ]
        ]
    ];
}

// Get challenge data with proper language
function getChallengeText($day, $lang = 'en') {
    if ($lang === 'fa') {
        $challenge = getFaChallenge($day);
        if (!$challenge) {
            // Fallback to English if Persian not available
            $challenge = getChallenge($day);
            $lang = 'en';
        }
    } else {
        $challenge = getChallenge($day);
    }
    
    return ['challenge' => $challenge, 'lang' => $lang];
}

// Format challenge message with proper language
function formatChallengeMessageMultilang($day, $challenge, $user_name, $chat_id, $lang = 'en') {
    $replacements = [
        'name' => $user_name,
        'day' => $day,
        'title' => $challenge['title'],
        'description' => $challenge['description'],
        'why_it_works' => $challenge['why_it_works'] ?? '',
        'prompt' => $challenge['prompt']
    ];
    
    $message = t('challenge_intro', $lang, $replacements);
    
    // Add translation button
    $other_lang = ($lang === 'en') ? 'fa' : 'en';
    $keyboard = [
        'inline_keyboard' => [
            [['text' => t('btn_translate_' . $other_lang, $lang), 'callback_data' => "translate_{$other_lang}_{$day}"]]
        ]
    ];
    
    sendMessage($chat_id, $message, $keyboard);
}

// Generate AI coaching response with language detection
function generateCoachingResponseMultilang($challenge_title, $user_response, $user_lang = 'en') {
    // Detect response language (user might respond in different language)
    $response_language = detectLanguage($user_response);
    
    // Use detected language for AI response
    return generateCoachingResponse($challenge_title, $user_response, $response_language);
}

// Generate progress report with language
function generateProgressReportMultilang($user, $lang = 'en') {
    $progress = getUserProgress($user);
    $points = calculatePoints($user);
    
    $replacements = [
        'name' => $user['name'],
        'completed' => $progress['completed'],
        'percentage' => $progress['percentage'],
        'points' => $points,
        'start_date' => $user['start_date'] ?? t('use_start', $lang)
    ];
    
    $message = t('progress_report', $lang, $replacements);
    
    // Progress bar
    $completed = $progress['completed'];
    $bar_length = 20;
    $filled = round(($completed / 30) * $bar_length);
    $empty = $bar_length - $filled;
    $progress_bar = str_repeat('🟩', $filled) . str_repeat('⬜', $empty);
    
    $message .= t('progress_bar', $lang, ['bar' => $progress_bar]);
    
    if ($completed > 0) {
        $message .= t('progress_keep_going', $lang);
    } else {
        $message .= t('progress_ready_start', $lang);
    }
    
    $message .= t('progress_footer', $lang);
    
    return $message;
}

// Generate all days view with language
function generateAllDaysViewMultilang($user, $lang = 'en') {
    $completed_days = $user['completed_days'] ?? [];
    $current_day = $user['current_day'] ?? 1;
    
    $message = t('all_days_title', $lang);
    
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
        
        $day_text = ($lang === 'fa') ? "روز {$day}" : "Day {$day}";
        $button_text = "{$day_text} {$status}";
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
    
    $message .= t('all_days_legend', $lang);
    
    return [$message, $keyboard];
}
?>
