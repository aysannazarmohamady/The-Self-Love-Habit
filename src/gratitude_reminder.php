<?php
// gratitude_reminder.php - Gratitude practice reminder system
define('BOT_TOKEN', '8032308468:AAENEHClufdgLTlZpV828UdHE6Q0DUrqBlI');
define('DATA_FILE', '/home/jetncpan/public_html/selflove/users.json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Gratitude reminder messages
function getRandomGratitudeMessage() {
    $gratitude_messages = [
        "*🙏 Pause for gratitude*\n\nWhat's something good in your life right now?\n\nTap *🙏 Daily Gratitude* in the menu to share one thing you're grateful for today! 💚",
        
        "*✨ Gratitude moment*\n\nEven on tough days, there's always something to appreciate.\n\nUse *🙏 Daily Gratitude* button to practice this powerful habit! 🌟",
        
        "*💝 Quick gratitude check*\n\nTake 30 seconds to think of one thing that made you smile today.\n\nShare it with me using *🙏 Daily Gratitude* in the menu! ☺️",
        
        "*🌟 Gratitude reminder*\n\nScience shows gratitude rewires your brain for happiness!\n\nTry it now - tap *🙏 Daily Gratitude* below! 💫",
        
        "*🦋 A moment to appreciate*\n\nWhat's one small joy from today?\n\nShare it using the *🙏 Daily Gratitude* button in your menu! 💚",
        
        "*💚 Gratitude practice*\n\nFocusing on what's good attracts more good into your life.\n\nTap *🙏 Daily Gratitude* to share what you're thankful for! ✨",
        
        "*🌸 Time for thankfulness*\n\nYour health? A person? A moment? What brings warmth to your heart?\n\nUse *🙏 Daily Gratitude* in the menu to express it! 🙏",
        
        "*⭐ Gratitude break*\n\nEven listing one thing you're grateful for can shift your whole mood!\n\nTry it - tap *🙏 Daily Gratitude* below! 💝"
    ];
    
    return $gratitude_messages[array_rand($gratitude_messages)];
}

// Send message function
function sendMessage($chat_id, $text) {
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'Markdown'
    ];
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    echo "Sending to chat_id $chat_id: ";
    
    if ($result === FALSE) {
        echo "FAILED - Network error\n";
        return false;
    }
    
    $response = json_decode($result, true);
    if ($response && isset($response['ok']) && $response['ok']) {
        echo "SUCCESS\n";
        return true;
    } else {
        $error = $response['description'] ?? 'Unknown error';
        echo "FAILED - API Error: $error\n";
        return false;
    }
}

// Load users
function loadUsers() {
    echo "Loading users from: " . DATA_FILE . "\n";
    
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
    
    echo "Successfully loaded " . count($users) . " users\n";
    return $users;
}

// Send gratitude reminders
function sendGratitudeReminders() {
    $users = loadUsers();
    $sent_count = 0;
    $failed_count = 0;
    $skipped_count = 0;
    
    if (empty($users)) {
        echo "No users found!\n";
        return ['sent' => 0, 'failed' => 0, 'skipped' => 0];
    }
    
    echo "\n=== Processing Users for Gratitude Reminders ===\n";
    
    foreach ($users as $user_id => $user) {
        echo "\n--- User ID: $user_id ---\n";
        echo "Name: " . ($user['name'] ?? 'N/A') . "\n";
        echo "Step: " . ($user['step'] ?? 'N/A') . "\n";
        echo "Start Date: " . ($user['start_date'] ?? 'N/A') . "\n";
        echo "Chat ID: " . ($user['chat_id'] ?? 'N/A') . "\n";
        
        // Check conditions for sending reminder
        if (!isset($user['start_date'])) {
            echo "SKIP: No start_date\n";
            $skipped_count++;
            continue;
        }
        
        if (!isset($user['chat_id'])) {
            echo "SKIP: No chat_id\n";
            $skipped_count++;
            continue;
        }
        
        // Skip users who haven't properly started
        $skip_steps = ['postponed', 'waiting_for_name', 'waiting_for_start'];
        if (isset($user['step']) && in_array($user['step'], $skip_steps)) {
            echo "SKIP: User step is " . $user['step'] . "\n";
            $skipped_count++;
            continue;
        }
        
        echo "SENDING: All conditions met\n";
        
        // Get a random gratitude message
        $random_message = getRandomGratitudeMessage();
        
        if (sendMessage($user['chat_id'], $random_message)) {
            $sent_count++;
        } else {
            $failed_count++;
        }
        
        // Small delay to avoid rate limiting
        usleep(200000); // 0.2 seconds
    }
    
    echo "\n=== Summary ===\n";
    echo "Total users: " . count($users) . "\n";
    echo "Messages sent: $sent_count\n";
    echo "Messages failed: $failed_count\n";
    echo "Users skipped: $skipped_count\n";
    
    // Log the operation
    $log_message = date('Y-m-d H:i:s') . " - Gratitude reminders: {$sent_count} sent, {$failed_count} failed, {$skipped_count} skipped\n";
    file_put_contents('/home/jetncpan/public_html/selflove/gratitude_reminder_log.txt', $log_message, FILE_APPEND);
    
    return ['sent' => $sent_count, 'failed' => $failed_count, 'skipped' => $skipped_count];
}

// Main execution
echo "=== GRATITUDE REMINDER CRON JOB STARTED ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "Hour: " . date('H') . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Working Directory: " . getcwd() . "\n";

$result = sendGratitudeReminders();

echo "\n=== GRATITUDE REMINDER CRON JOB COMPLETED ===\n";
echo "Final Results: {$result['sent']} sent, {$result['failed']} failed, {$result['skipped']} skipped\n";

// Log execution
$logMessage = "[" . date('Y-m-d H:i:s') . "] Gratitude reminder executed - Sent: {$result['sent']}, Failed: {$result['failed']}, Skipped: {$result['skipped']}\n";
file_put_contents('/home/jetncpan/public_html/selflove/gratitude_cron_log.txt', $logMessage, FILE_APPEND);

echo "=== END ===\n";
?>