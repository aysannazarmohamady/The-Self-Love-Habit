<?php
// config.php
define('BOT_TOKEN', '');
define('DATA_FILE', 'users.json');

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
    return file_put_contents(DATA_FILE, json_encode($users, JSON_PRETTY_PRINT));
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

// Main webhook handler
$input = file_get_contents('php://input');
$update = json_decode($input, true);

if (isset($update['message'])) {
    $message = $update['message'];
    $chat_id = $message['chat']['id'];
    $user_id = $message['from']['id'];
    $text = $message['text'] ?? '';
    $first_name = $message['from']['first_name'] ?? '';
    
    $user = getUser($user_id);
    
    // Handle /start command
    if ($text == '/start') {
        $welcome_text = "*🌟 Welcome to the 30-Day Self-Confidence Challenge Bot! 🌟*\n\n";
        $welcome_text .= "I'm here to guide you through a scientifically-backed journey to boost your self-confidence over the next 30 days.\n\n";
        $welcome_text .= "This challenge is based on proven psychological principles and small daily actions that can make a big difference in how you see yourself.\n\n";
        $welcome_text .= "To get started, please tell me your name:";
        
        sendMessage($chat_id, $welcome_text);
        
        // Save user with step 'waiting_for_name'
        saveUser($user_id, [
            'chat_id' => $chat_id,
            'first_name' => $first_name,
            'step' => 'waiting_for_name',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    // Handle name input
    elseif ($user && $user['step'] == 'waiting_for_name') {
        $name = trim($text);
        
        if (strlen($name) > 2 && strlen($name) < 50) {
            $congrats_text = "*Great to meet you, {$name}! 🎉*\n\n";
            $congrats_text .= "Your 30-day self-confidence challenge has officially started! 🚀\n\n";
            $congrats_text .= "*🧠 Why This Challenge Works:*\n\n";
            $congrats_text .= "Research in neuroplasticity shows that our brains can form new neural pathways through consistent practice. This challenge uses:\n\n";
            $congrats_text .= "• *Behavioral activation* - Small daily actions create positive momentum\n";
            $congrats_text .= "• *Cognitive restructuring* - Shifting negative self-talk to positive affirmations\n";
            $congrats_text .= "• *Exposure therapy principles* - Gradually stepping outside your comfort zone\n";
            $congrats_text .= "• *Self-efficacy theory* - Building confidence through mastery experiences\n\n";
            $congrats_text .= "Each day, you'll receive a specific task designed to strengthen your self-confidence muscle. Just like physical exercise, consistency is key! 💪\n\n";
            $congrats_text .= "Your first challenge will arrive tomorrow morning. Get ready to discover just how amazing you really are! ✨";
            
            sendMessage($chat_id, $congrats_text);
            
            // Update user with name and mark as started
            saveUser($user_id, [
                'chat_id' => $chat_id,
                'first_name' => $first_name,
                'name' => $name,
                'step' => 'challenge_started',
                'start_date' => date('Y-m-d'),
                'current_day' => 1,
                'completed_days' => [],
                'created_at' => $user['created_at']
            ]);
        } else {
            sendMessage($chat_id, "Please enter a valid name (2-50 characters):");
        }
    }
    // Handle other messages when user is in different steps
    else {
        sendMessage($chat_id, "Please start by typing /start");
    }
}
?>
