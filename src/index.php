<?php
// config.php
define('BOT_TOKEN', 'YOUR_BOT_TOKEN_HERE');
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

// Calculate user points
function calculatePoints($user) {
    $points = 0;
    $completed_days = $user['completed_days'] ?? [];
    
    foreach ($completed_days as $day => $data) {
        if ($data['completed']) {
            $points += 10; // 10 points per completed day
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
    $completed_days = $user['completed_days'] ?? [];
    
    $message = "*🌟 {$user['name']}'s Confidence Journey 🌟*\n\n";
    $message .= "📊 *Progress:* {$progress['completed']}/30 days ({$progress['percentage']}%)\n";
    $message .= "🏆 *Total Points:* {$points}\n";
    $message .= "📅 *Started:* " . ($user['start_date'] ?? 'Not started') . "\n\n";
    
    // Show completed days
    if ($progress['completed'] > 0) {
        $message .= "*✅ Completed Days:*\n";
        foreach ($completed_days as $day => $data) {
            if ($data['completed']) {
                $challenge = getChallenge($day);
                $title = $challenge ? $challenge['title'] : "Day {$day}";
                $message .= "Day {$day}: {$title}\n";
            }
        }
        $message .= "\n";
    }
    
    // Show pending days
    $pending_days = [];
    for ($day = 1; $day <= 30; $day++) {
        if (!isset($completed_days[$day]) || !$completed_days[$day]['completed']) {
            $pending_days[] = $day;
        }
    }
    
    if (!empty($pending_days)) {
        $message .= "*⏳ Pending Days:*\n";
        $shown = 0;
        foreach ($pending_days as $day) {
            if ($shown >= 5) {
                $remaining = count($pending_days) - 5;
                $message .= "... and {$remaining} more days\n";
                break;
            }
            $challenge = getChallenge($day);
            $title = $challenge ? $challenge['title'] : "Day {$day}";
            $message .= "Day {$day}: {$title}\n";
            $shown++;
        }
    }
    
    return $message;
}

// Handle active challenge response
function handleChallengeResponse($user_id, $user, $day, $text) {
    $response = trim($text);
    
    if (strlen($response) > 10) {
        // Mark as completed and award points
        $completed_days = $user['completed_days'] ?? [];
        $completed_days[$day] = [
            'completed' => true,
            'completed_at' => date('Y-m-d H:i:s'),
            'response' => $response
        ];
        
        $completion_message = getCompletionMessage($day, $user['name']);
        $points = calculatePoints(array_merge($user, ['completed_days' => $completed_days]));
        
        $completion_message .= "\n\n🏆 *+10 Points! Total: {$points} points*";
        
        sendMessage($user['chat_id'], $completion_message);
        
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
            $final_message .= "Use /profile to see your amazing journey summary!";
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
    return $interval->days + 1; // +1 because day 1 is the start date
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
                $missed_text .= "Use /profile to see which days you can still complete. Remember, every step forward counts! 🌟";
                
                sendMessage($user['chat_id'], $missed_text);
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
                    
                    sendMessage($user['chat_id'], $full_message);
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
                $final_text .= "Use /profile to see your complete journey summary. You should be incredibly proud of yourself! 🌟\n\n";
                $final_text .= "Remember, this isn't the end - it's the beginning of a more confident you! 💪";
                
                sendMessage($user['chat_id'], $final_text);
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
    // Handle retry challenge buttons
    elseif (strpos($data, 'retry_day_') === 0) {
        $day = intval(str_replace('retry_day_', '', $data));
        $challenge = getChallenge($day);
        
        if ($challenge) {
            $message = formatChallengeMessage($day, $challenge, $user['name']);
            sendMessage($chat_id, $message);
            
            // Set user to active for this day
            saveUser($user_id, array_merge($user, [
                'step' => "day_{$day}_active",
                'last_activity' => date('Y-m-d H:i:s')
            ]));
        }
        
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
            $welcome_back = "*Welcome back, {$user['name']}! 🌟*\n\n";
            $welcome_back .= "You're already on your confidence journey! Use /profile to see your progress or /today to see today's challenge.";
            sendMessage($chat_id, $welcome_back);
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
    // Handle /profile command
    elseif ($text == '/profile') {
        if ($user && isset($user['start_date'])) {
            $report = generateProgressReport($user);
            
            // Add buttons for incomplete days
            $completed_days = $user['completed_days'] ?? [];
            $incomplete_days = [];
            
            for ($day = 1; $day <= min($user['current_day'] ?? 1, 30); $day++) {
                if (!isset($completed_days[$day]) || !$completed_days[$day]['completed']) {
                    $incomplete_days[] = $day;
                }
            }
            
            $keyboard = null;
            if (!empty($incomplete_days)) {
                $buttons = [];
                $row = [];
                foreach (array_slice($incomplete_days, 0, 6) as $day) {
                    $row[] = ['text' => "Day {$day}", 'callback_data' => "retry_day_{$day}"];
                    if (count($row) == 3) {
                        $buttons[] = $row;
                        $row = [];
                    }
                }
                if (!empty($row)) {
                    $buttons[] = $row;
                }
                
                $keyboard = ['inline_keyboard' => $buttons];
                $report .= "\n💡 *Tap any pending day below to complete it:*";
            }
            
            sendMessage($chat_id, $report, $keyboard);
        } else {
            sendMessage($chat_id, "You haven't started the challenge yet! Type /start to begin your journey! 🌟");
        }
    }
    // Handle /today command
    elseif ($text == '/today') {
        if ($user && isset($user['start_date'])) {
            $current_day = $user['current_day'] ?? 1;
            $completed_days = $user['completed_days'] ?? [];
            
            if ($current_day > 30) {
                sendMessage($chat_id, "🎉 You've completed all 30 days! Congratulations! Use /profile to see your amazing journey!");
            } elseif (isset($completed_days[$current_day]) && $completed_days[$current_day]['completed']) {
                sendMessage($chat_id, "✅ You've already completed Day {$current_day}! Great job! Use /profile to see other pending days.");
            } else {
                $challenge = getChallenge($current_day);
                if ($challenge) {
                    $message = formatChallengeMessage($current_day, $challenge, $user['name']);
                    sendMessage($chat_id, $message);
                    
                    saveUser($user_id, array_merge($user, [
                        'step' => "day_{$current_day}_active",
                        'last_activity' => date('Y-m-d H:i:s')
                    ]));
                }
            }
        } else {
            sendMessage($chat_id, "You haven't started the challenge yet! Type /start to begin! 🌟");
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
                'created_at' => $user['created_at']
            ]);
        } else {
            sendMessage($chat_id, "Please enter a valid name (2-50 characters):");
        }
    }
    // Handle challenge responses for any day
    elseif ($user && preg_match('/^day_(\d+)_active$/', $user['step'], $matches)) {
        $day = intval($matches[1]);
        handleChallengeResponse($user_id, $user, $day, $text);
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
    // Default response
    else {
        if ($user) {
            sendMessage($chat_id, "Hi {$user['name']}! 😊\n\nUse /today for today's challenge, /profile to see your progress, or /start if you want to restart!");
        } else {
            sendMessage($chat_id, "Please start by typing /start 🌟");
        }
    }
}
?>
