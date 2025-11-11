<?php
// migration_level2.php
// ONE-TIME SCRIPT: Send Level 2 offer to existing users who completed Level 1

define('BOT_TOKEN', '');
define('DATA_FILE', '');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== LEVEL 2 MIGRATION SCRIPT STARTED ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

// Load users
function loadUsers() {
    if (!file_exists(DATA_FILE)) {
        echo "ERROR: Users file not found!\n";
        return [];
    }
    
    $json = file_get_contents(DATA_FILE);
    if ($json === FALSE) {
        echo "ERROR: Could not read users file!\n";
        return [];
    }
    
    $users = json_decode($json, true);
    if ($users === NULL) {
        echo "ERROR: Invalid JSON in users file!\n";
        return [];
    }
    
    echo "Successfully loaded " . count($users) . " users\n\n";
    return $users;
}

// Save users
function saveUsers($users) {
    return file_put_contents(DATA_FILE, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Send message
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
    $result = file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        return false;
    }
    
    $response = json_decode($result, true);
    return ($response && isset($response['ok']) && $response['ok']);
}

// Detect language
function detectLanguage($text) {
    $persian_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
    return preg_match($persian_pattern, $text) ? 'fa' : 'en';
}

// Get Level 1 completion message
function getLevel1CompletionMessage($language = 'en') {
    if ($language == 'fa') {
        $message = "*🎊 خبر عالی! Level 2 الان آماده‌ست! 🎊*\n\n";
        $message .= "تبریک! تو Level 1: Self-Confidence رو تموم کردی!\n\n";
        $message .= "*✨ دستاوردهای تو:*\n";
        $message .= "• 30 روز متوالی ✅\n";
        $message .= "• از خودشناسی تا اعتماد به نفس پایه\n\n";
        $message .= "*🎯 آماده‌ای برای مرحله بعدی؟*\n\n";
        $message .= "📍 *الان کجایی:* Level 1 ✅ تموم شد\n";
        $message .= "📍 *بعدی کجاست:* Level 2 - Social Confidence\n";
        $message .= "_(تمرکز: مهارت‌های اجتماعی و ارتباطات)_\n\n";
        $message .= "از اعتماد به نفس فردی به اعتماد به نفس اجتماعی! 💪";
    } else {
        $message = "*🎊 Great News! Level 2 is Now Available! 🎊*\n\n";
        $message .= "Congratulations! You completed Level 1: Self-Confidence!\n\n";
        $message .= "*✨ Your Achievements:*\n";
        $message .= "• 30 consecutive days ✅\n";
        $message .= "• From self-awareness to foundational confidence\n\n";
        $message .= "*🎯 Ready for the next stage?*\n\n";
        $message .= "📍 *Where you are:* Level 1 ✅ Complete\n";
        $message .= "📍 *What's next:* Level 2 - Social Confidence\n";
        $message .= "_(Focus: Social skills and communication)_\n\n";
        $message .= "From personal confidence to social confidence! 💪";
    }
    
    return $message;
}

// Main migration function
function migrateUsers() {
    $users = loadUsers();
    
    $sent_count = 0;
    $failed_count = 0;
    $skipped_count = 0;
    $already_migrated = 0;
    
    echo "=== Processing Users ===\n\n";
    
    foreach ($users as $user_id => $user) {
        echo "--- User ID: {$user_id} ---\n";
        echo "Name: " . ($user['name'] ?? 'N/A') . "\n";
        echo "Step: " . ($user['step'] ?? 'N/A') . "\n";
        echo "Current Day: " . ($user['current_day'] ?? 'N/A') . "\n";
        
        // Check if user already migrated
        if (isset($user['level2_offered']) && $user['level2_offered'] === true) {
            echo "SKIP: Already received Level 2 offer\n\n";
            $already_migrated++;
            continue;
        }
        
        // Check if user completed Level 1 (Day 30)
        $completed_days = $user['completed_days'] ?? [];
        $completed_day_30 = isset($completed_days[30]) && $completed_days[30]['completed'];
        
        if (!$completed_day_30) {
            echo "SKIP: Has not completed Day 30\n\n";
            $skipped_count++;
            continue;
        }
        
        // Check if user is already in Level 2
        $current_day = $user['current_day'] ?? 1;
        if ($current_day > 30) {
            echo "SKIP: Already in Level 2 (Day {$current_day})\n\n";
            $already_migrated++;
            continue;
        }
        
        // User completed Level 1 and hasn't been offered Level 2 yet
        echo "ELIGIBLE: Sending Level 2 offer...\n";
        
        // Detect user's language preference from their responses
        $user_language = 'en';
        if (isset($completed_days[30]['language'])) {
            $user_language = $completed_days[30]['language'];
        } elseif (isset($user['day_30_language'])) {
            $user_language = $user['day_30_language'];
        } else {
            // Try to detect from any response
            foreach ($completed_days as $day => $data) {
                if (isset($data['language'])) {
                    $user_language = $data['language'];
                    break;
                }
            }
        }
        
        echo "Detected language: {$user_language}\n";
        
        // Get celebration message
        $celebration_message = getLevel1CompletionMessage($user_language);
        
        // Create keyboard
        $keyboard = [
            'inline_keyboard' => [
                [['text' => '🚀 شروع Level 2 / Start Level 2', 'callback_data' => 'start_level2']],
                [
                    ['text' => '📊 مرور سفرم / Review Journey', 'callback_data' => 'review_journey'],
                    ['text' => '⏸ استراحت / Take a Break', 'callback_data' => 'take_break']
                ]
            ]
        ];
        
        // Send message
        if (sendMessage($user['chat_id'], $celebration_message, $keyboard)) {
            echo "SUCCESS: Message sent\n";
            $sent_count++;
            
            // Update user data with migration flag
            $users[$user_id]['level2_offered'] = true;
            $users[$user_id]['level2_offer_date'] = date('Y-m-d H:i:s');
            $users[$user_id]['step'] = 'level1_completed';
            
        } else {
            echo "FAILED: Could not send message\n";
            $failed_count++;
        }
        
        echo "\n";
        
        // Small delay to avoid rate limiting
        usleep(500000); // 0.5 seconds
    }
    
    // Save updated users data
    if ($sent_count > 0) {
        if (saveUsers($users)) {
            echo "✅ Users data saved successfully\n\n";
        } else {
            echo "❌ ERROR: Could not save users data!\n\n";
        }
    }
    
    echo "=== MIGRATION SUMMARY ===\n";
    echo "Total users: " . count($users) . "\n";
    echo "Messages sent: {$sent_count}\n";
    echo "Messages failed: {$failed_count}\n";
    echo "Users skipped (not eligible): {$skipped_count}\n";
    echo "Already migrated: {$already_migrated}\n";
    
    return [
        'sent' => $sent_count,
        'failed' => $failed_count,
        'skipped' => $skipped_count,
        'already_migrated' => $already_migrated
    ];
}

// Execute migration
$result = migrateUsers();

echo "\n=== MIGRATION COMPLETED ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "Final Results: {$result['sent']} sent, {$result['failed']} failed, {$result['skipped']} skipped, {$result['already_migrated']} already migrated\n";

// Log to file
$log_message = "[" . date('Y-m-d H:i:s') . "] Level 2 Migration - Sent: {$result['sent']}, Failed: {$result['failed']}, Skipped: {$result['skipped']}, Already Migrated: {$result['already_migrated']}\n";
file_put_contents('migration_level2_log.txt', $log_message, FILE_APPEND);

echo "\n✅ Migration script finished. Log saved to migration_level2_log.txt\n";
echo "\n⚠️ IMPORTANT: This script should only be run ONCE!\n";
echo "=== END ===\n";
?>
