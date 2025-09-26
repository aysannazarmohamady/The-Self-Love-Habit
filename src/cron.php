<?php
// cron.php - Simple reminder system with debugging
define('BOT_TOKEN', '');
define('DATA_FILE', '/home/jetncpan/public_html/selflove/users.json');


// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Multiple message variations
function getRandomMessage() {
    $current_hour = (int)date('H');
    
    if ($current_hour >= 6 && $current_hour <= 12) {
        // Morning messages (6 AM to 12 PM)
        $morning_messages = [
            "*Good morning superstar!* ☀️\n\nReady to conquer today's confidence challenge? You've got this! 💪\n\nRemember: Every brave step makes you stronger! ✨",
            
            "*Rise and shine!* 🌅\n\nYour confidence journey continues today! What amazing thing will you do? 🚀\n\nSmall actions = Big transformations! 💫",
            
            "*Hey champion!* 🏆\n\nTime for your daily dose of courage! Your future self will thank you 💝\n\nToday's challenge is waiting for you! 🎯",
            
            "*Morning motivation coming your way!* ⚡\n\nAnother day, another chance to grow stronger! 🌱\n\nYour confidence challenge is ready when you are! 💎",
            
            "*Hello beautiful soul!* 🌸\n\nDon't forget your confidence boost today! You deserve to feel amazing 👑\n\nEvery step forward counts! 🦋"
        ];
        
        return $morning_messages[array_rand($morning_messages)];
        
    } else {
        // Evening messages (rest of the day)
        $evening_messages = [
            "*Hey there!* 🌙\n\nHow's your confidence challenge going today? 🤔\n\nEven tiny steps create powerful changes! Keep going! 💪",
            
            "*Gentle reminder!* 🔔\n\nHave you tackled today's challenge yet? 🎯\n\nIt's never too late to do something brave! ✨",
            
            "*Check-in time!* ⏰\n\nYour confidence is calling! Have you answered? 📞\n\nConsistency builds unstoppable confidence! 🚀",
            
            "*Sweet reminder!* 🍯\n\nToday's challenge is still waiting for you! 😊\n\nProgress over perfection - always! 🌟",
            
            "*Friendly nudge!* 👋\n\nRemember your confidence goal today? 🎪\n\nEvery moment is a new chance to grow! 🌱",
            
            "*Evening check!* 🌆\n\nDid you show up for yourself today? 💖\n\nThere's still time to make it happen! ⭐"
        ];
        
        return $evening_messages[array_rand($evening_messages)];
    }
}

// Send message function (unchanged)
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

// Load users (unchanged)
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

// Send reminder to all users with random messages
function sendReminders() {
    $users = loadUsers();
    $sent_count = 0;
    $failed_count = 0;
    $skipped_count = 0;
    $current_hour = (int)date('H');
    
    if (empty($users)) {
        echo "No users found!\n";
        return ['sent' => 0, 'failed' => 0, 'skipped' => 0];
    }
    
    $reminder_type = ($current_hour >= 6 && $current_hour <= 12) ? "Morning" : "Evening";
    echo "\n=== Processing Users for $reminder_type Reminders ===\n";
    
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
        
        // Get a random message for this user
        $random_message = getRandomMessage();
        
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
    $log_message = date('Y-m-d H:i:s') . " - {$reminder_type} reminders: {$sent_count} sent, {$failed_count} failed, {$skipped_count} skipped\n";
    file_put_contents('/home/jetncpan/public_html/selflove/reminder_log.txt', $log_message, FILE_APPEND);
    
    return ['sent' => $sent_count, 'failed' => $failed_count, 'skipped' => $skipped_count];
}

// Main execution (unchanged)
echo "=== CRON JOB STARTED ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "Hour: " . date('H') . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Working Directory: " . getcwd() . "\n";

$result = sendReminders();

echo "\n=== CRON JOB COMPLETED ===\n";
echo "Final Results: {$result['sent']} sent, {$result['failed']} failed, {$result['skipped']} skipped\n";

// Log execution
$logMessage = "[" . date('Y-m-d H:i:s') . "] Cron executed - Sent: {$result['sent']}, Failed: {$result['failed']}, Skipped: {$result['skipped']}\n";
file_put_contents('/home/jetncpan/public_html/selflove/cron_log.txt', $logMessage, FILE_APPEND);

echo "=== END ===\n";
?>
