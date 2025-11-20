<?php
// daily_affirmations_cron.php - Daily self-love affirmations sender
define('BOT_TOKEN', '');
define('DATA_FILE', '');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 30 short self-love affirmations
function getRandomAffirmation() {
    $affirmations = [
        "You are enough. 💚",
        "You matter. ✨",
        "Be kind to yourself. 🌸",
        "You are worthy of love. 💝",
        "Your feelings are valid. 🌈",
        "You deserve happiness. ☀️",
        "Trust yourself. 🦋",
        "You are doing great. 🌟",
        "Embrace your journey. 🛤️",
        "You are valuable. 💎",
        "Love yourself first. 💗",
        "You are strong. 💪",
        "Believe in yourself. ⭐",
        "You are beautiful. 🌺",
        "Your best is enough. 🌻",
        "You deserve rest. 🌙",
        "You are growing. 🌱",
        "Be proud of yourself. 🏆",
        "You are unique. 🎨",
        "Accept yourself fully. 🤗",
        "You are capable. 🚀",
        "Celebrate small wins. 🎉",
        "You are loved. 💞",
        "Honor your needs. 🕊️",
        "You are resilient. 🌊",
        "Speak kindly to yourself. 🗣️",
        "You are worthy. 👑",
        "Trust your path. 🧭",
        "You are amazing. ✨",
        "Embrace who you are. 🦋"
    ];
    
    return $affirmations[array_rand($affirmations)];
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

// Send affirmations to all active users
function sendAffirmations() {
    $users = loadUsers();
    $sent_count = 0;
    $failed_count = 0;
    $skipped_count = 0;
    
    if (empty($users)) {
        echo "No users found!\n";
        return ['sent' => 0, 'failed' => 0, 'skipped' => 0];
    }
    
    echo "\n=== Processing Users for Daily Affirmations ===\n";
    
    // Get one random affirmation for all users
    $affirmation = "*💭 Daily Self-Love Reminder*\n\n" . getRandomAffirmation();
    
    foreach ($users as $user_id => $user) {
        echo "\n--- User ID: $user_id ---\n";
        echo "Name: " . ($user['name'] ?? 'N/A') . "\n";
        echo "Step: " . ($user['step'] ?? 'N/A') . "\n";
        echo "Start Date: " . ($user['start_date'] ?? 'N/A') . "\n";
        echo "Chat ID: " . ($user['chat_id'] ?? 'N/A') . "\n";
        
        // Check conditions for sending affirmation
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
        
        echo "SENDING: Affirmation message\n";
        
        if (sendMessage($user['chat_id'], $affirmation)) {
            $sent_count++;
        } else {
            $failed_count++;
        }
        
        // Small delay to avoid rate limiting
        usleep(200000); // 0.2 seconds
    }
    
    echo "\n=== Summary ===\n";
    echo "Total users: " . count($users) . "\n";
    echo "Affirmations sent: $sent_count\n";
    echo "Messages failed: $failed_count\n";
    echo "Users skipped: $skipped_count\n";
    
    // Log the operation
    $log_message = date('Y-m-d H:i:s') . " - Affirmations: {$sent_count} sent, {$failed_count} failed, {$skipped_count} skipped\n";
    file_put_contents('/home/jetncpan/public_html/selflove/affirmation_log.txt', $log_message, FILE_APPEND);
    
    return ['sent' => $sent_count, 'failed' => $failed_count, 'skipped' => $skipped_count];
}

// Main execution
echo "=== DAILY AFFIRMATIONS CRON JOB STARTED ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "Hour: " . date('H') . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Working Directory: " . getcwd() . "\n";

$result = sendAffirmations();

echo "\n=== CRON JOB COMPLETED ===\n";
echo "Final Results: {$result['sent']} sent, {$result['failed']} failed, {$result['skipped']} skipped\n";

// Log execution
$logMessage = "[" . date('Y-m-d H:i:s') . "] Affirmations Cron executed - Sent: {$result['sent']}, Failed: {$result['failed']}, Skipped: {$result['skipped']}\n";
file_put_contents('/home/jetncpan/public_html/selflove/cron_log.txt', $logMessage, FILE_APPEND);

echo "=== END ===\n";
?>
