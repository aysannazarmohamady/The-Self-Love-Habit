<?php
// cron.php - Smart reminder system with activity tracking
define('BOT_TOKEN', '');
define('DATA_FILE', '');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get random message based on time and user activity
function getRandomMessage($hour, $user_last_activity = null) {
    // Determine message type based on hour
    // Morning (6-12): Challenge messages
    // Evening (15-21): Reminder messages
    // Other times: Gratitude messages
    
    if ($hour >= 6 && $hour < 12) {
        $message_type = 'morning_challenge';
    } elseif ($hour >= 15 && $hour < 22) {
        // Check if user was active in last 12 hours
        if ($user_last_activity) {
            $last_activity_time = strtotime($user_last_activity);
            $hours_since_activity = (time() - $last_activity_time) / 3600;
            
            if ($hours_since_activity < 12) {
                // User completed challenge recently, send thank you message
                return getThankYouMessage();
            }
        }
        $message_type = 'evening_reminder';
    } else {
        $message_type = 'gratitude';
    }
    
    // Morning Challenge Messages - 15 variations with prefix
    if ($message_type === 'morning_challenge') {
        $messages = [
            "*🎯 Challenge Time!*\n\n*Good morning superstar!* ☀️\n\nReady to conquer today's confidence challenge? You've got this! 💪\n\nRemember: Every brave step makes you stronger! ✨",
            
            "*🎯 Challenge Time!*\n\n*Rise and shine!* 🌅\n\nYour confidence journey continues today! What amazing thing will you do? 🚀\n\nSmall actions = Big transformations! 💫",
            
            "*🎯 Challenge Time!*\n\n*Hey champion!* 🏆\n\nTime for your daily dose of courage! Your future self will thank you 💝\n\nToday's challenge is waiting for you! 🎯",
            
            "*🎯 Challenge Time!*\n\n*Morning motivation coming your way!* ⚡\n\nAnother day, another chance to grow stronger! 🌱\n\nYour confidence challenge is ready when you are! 💎",
            
            "*🎯 Challenge Time!*\n\n*Hello beautiful soul!* 🌸\n\nDon't forget your confidence boost today! You deserve to feel amazing 👑\n\nEvery step forward counts! 🦋",
            
            "*🎯 Challenge Time!*\n\n*Wakey wakey!* 🌞\n\nToday's the perfect day to be brave! What's one thing you'll do for YOU? 💗\n\nYour confidence muscles need their morning workout! 💯",
            
            "*🎯 Challenge Time!*\n\n*Good morning warrior!* ⚔️\n\nReady to slay today's self-doubt? I know you are! 🔥\n\nConfidence isn't built in a day, but today IS a building day! 🏗️",
            
            "*🎯 Challenge Time!*\n\n*Sunrise reminder!* 🌄\n\nYour journey to unstoppable confidence continues NOW! ⏰\n\nWhat brave action will you take today? Make it count! 🎪",
            
            "*🎯 Challenge Time!*\n\n*Morning sparkle!* ✨\n\nTime to show the world (and yourself) what you're made of! 🌟\n\nToday's challenge = Tomorrow's confidence! Let's go! 🚀",
            
            "*🎯 Challenge Time!*\n\n*Hey there rockstar!* 🎸\n\nAnother opportunity to become the person you're meant to be! 🦸\n\nDon't let this day pass without doing something BRAVE! 💥",
            
            "*🎯 Challenge Time!*\n\n*Coffee's ready, so is your challenge!* ☕\n\nStart your day with courage, not just caffeine! 😉\n\nSmall daily wins = Massive confidence gains! 📈",
            
            "*🎯 Challenge Time!*\n\n*Good morning legend!* 🌟\n\nLegends aren't born, they're built - one challenge at a time! 🏗️\n\nWhat's your power move today? 💪",
            
            "*🎯 Challenge Time!*\n\n*Rise up!* 🌇\n\nToday is your canvas - paint it with courage! 🎨\n\nYour confidence challenge is the first brushstroke! ✨",
            
            "*🎯 Challenge Time!*\n\n*Morning vibes!* 🎵\n\nFeeling it or not, show up for yourself today! 💖\n\nConsistency beats motivation every single time! 🔄",
            
            "*🎯 Challenge Time!*\n\n*New day, new you!* 🆕\n\nEvery sunrise brings a fresh chance to level up! 📊\n\nYour confidence quest continues - ready player one? 🎮"
        ];
        
        return $messages[array_rand($messages)];
    }
    
    // Evening Reminder Messages - 15 variations with prefix
    if ($message_type === 'evening_reminder') {
        $messages = [
            "*⏰ Reminder!*\n\n*Hey there!* 🌙\n\nHow's your confidence challenge going today? 🤔\n\nEven tiny steps create powerful changes! Keep going! 💪",
            
            "*⏰ Reminder!*\n\n*Gentle reminder!* 🔔\n\nHave you tackled today's challenge yet? 🎯\n\nIt's never too late to do something brave! ✨",
            
            "*⏰ Reminder!*\n\n*Check-in time!* ⏰\n\nYour confidence is calling! Have you answered? 📞\n\nConsistency builds unstoppable confidence! 🚀",
            
            "*⏰ Reminder!*\n\n*Sweet reminder!* 🍯\n\nToday's challenge is still waiting for you! 😊\n\nProgress over perfection - always! 🌟",
            
            "*⏰ Reminder!*\n\n*Friendly nudge!* 👋\n\nRemember your confidence goal today? 🎪\n\nEvery moment is a new chance to grow! 🌱",
            
            "*⏰ Reminder!*\n\n*Evening check!* 🌆\n\nDid you show up for yourself today? 💖\n\nThere's still time to make it happen! ⭐",
            
            "*⏰ Reminder!*\n\n*Quick question!* 🤷\n\nHave you done today's confidence challenge? 🎭\n\nEven 5 minutes of bravery counts! The clock is ticking! ⏳",
            
            "*⏰ Reminder!*\n\n*Afternoon accountability!* 📝\n\nJust checking in on your awesome self! How's it going? 😊\n\nRemember: You promised YOURSELF you'd do this! 💪",
            
            "*⏰ Reminder!*\n\n*Sunset reminder!* 🌅\n\nBefore the day ends, have you challenged yourself? 🤔\n\nDon't go to bed without at least trying! Your future self is watching! 👀",
            
            "*⏰ Reminder!*\n\n*Psst... hey you!* 🗣️\n\nYour confidence challenge isn't going to complete itself! 😅\n\nWhat are you waiting for? Permission? Consider this it! ✅",
            
            "*⏰ Reminder!*\n\n*Reality check!* 💭\n\nDid you do something brave today or just think about it? 🧐\n\nThinking is great, but DOING is where the magic happens! ✨",
            
            "*⏰ Reminder!*\n\n*Time flies reminder!* 🕐\n\nAnother day is slipping away... caught your challenge yet? 🎣\n\nNo judgment, just motivation! You've got this! 🎯",
            
            "*⏰ Reminder!*\n\n*Honest question:* 🙋\n\nWhat's stopping you from your challenge today? 🚧\n\nWhatever it is, it's smaller than your potential! Break through! 💥",
            
            "*⏰ Reminder!*\n\n*Mid-day motivation!* 🌤️\n\nStill time to turn today into a WIN! 🏆\n\nYour confidence challenge is waiting - don't leave it hanging! 🤝",
            
            "*⏰ Reminder!*\n\n*Let's be real:* 💯\n\nYou know you'll feel amazing after completing today's challenge! 😌\n\nSo why wait? Present you = gift to future you! 🎁"
        ];
        
        return $messages[array_rand($messages)];
    }
    
    // Gratitude Messages (can be sent anytime) - 15 variations
    if ($message_type === 'gratitude') {
        $messages = [
            "*Gratitude moment!* 🙏\n\nPause for a second: What's ONE thing you're thankful for right now? 💭\n\nGratitude is the secret ingredient to confidence! ✨",
            
            "*Quick gratitude check!* 💝\n\nName 3 things that made you smile recently! Ready, go! 😊\n\n1. ___ 2. ___ 3. ___\n\nAppreciating the good multiplies it! 🌟",
            
            "*Reflection time!* 🌸\n\nWhat's going RIGHT in your life today? Think about it! 🤔\n\nFocusing on wins creates more wins! 🏆",
            
            "*Grateful heart check!* 💖\n\nWho's one person that makes your life better? Send them good vibes! 🌈\n\nAppreciation changes everything! ✨",
            
            "*Blessing radar activated!* 📡\n\nLook around: What comfort are you taking for granted? 👀\n\nEven small blessings deserve recognition! 🙌",
            
            "*Body appreciation time!* 💪\n\nWhat's one thing your BODY did for you today? 🏃‍♀️\n\nYour body is always working for you - thank it! 💓",
            
            "*Joy finder mission!* 🔍\n\nWhat's the BEST thing that happened this week? Replay it! 🎬\n\nReliving good moments doubles the happiness! 😄",
            
            "*Gratitude practice!* 📝\n\nWhat skill or ability do you have that you're grateful for? 🎯\n\nYour talents are gifts - acknowledge them! 🎁",
            
            "*Thankful thinking!* 💭\n\nWhat made life easier for you recently? Think! 🤷\n\nSomeone or something helped - recognize it! 🌟",
            
            "*Appreciation alert!* 🚨\n\nWhat's something about TODAY you're excited about? 🎉\n\nPositive focus = Positive outcomes! ⚡",
            
            "*Gratitude boost!* 🚀\n\nWhat challenge did you overcome recently? Celebrate it! 🎊\n\nYou're stronger than you realize! 💪",
            
            "*Blessing count!* 🧮\n\nWhat part of your life is actually going pretty well? 🌈\n\nWe often forget to notice what's working! ✅",
            
            "*Heart check!* 💗\n\nWhat made you laugh or smile today? Remember it! 😊\n\nJoy is everywhere if we look for it! 🦋",
            
            "*Gratitude reminder!* 🌟\n\nWhat's one thing you have now that you once wished for? 💫\n\nSometimes dreams come true quietly! 🌙",
            
            "*Thankful moment!* 🙏\n\nWhat's the best part of today if you had to pick ONE thing? 🎯\n\nEnding on gratitude = Good vibes! ✨"
        ];
        
        return $messages[array_rand($messages)];
    }
}

// Get thank you message for users who already completed today's challenge
function getThankYouMessage() {
    $messages = [
        "*🌟 Amazing! You've already completed today's challenge!*\n\nYou're on fire! 🔥 Keep that momentum going!\n\nSee you tomorrow for the next adventure! ✨",
        
        "*✨ Look at you go!*\n\nYou've already crushed today's challenge! 💪\n\nYour consistency is building something incredible! 🚀",
        
        "*🎉 You're ahead of the game!*\n\nToday's challenge? Already done! ✅\n\nThis is what commitment looks like! Keep shining! 🌟",
        
        "*💚 Already completed!*\n\nYou showed up for yourself today - that's beautiful! 🌸\n\nYour future self is so proud right now! ✨",
        
        "*🏆 Champion move!*\n\nYou've already tackled today's challenge like a boss!\n\nConsistency = Results. You're proving it! 💯"
    ];
    
    return $messages[array_rand($messages)];
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

// Send reminder to all users with smart activity tracking
function sendReminders() {
    $users = loadUsers();
    $sent_count = 0;
    $failed_count = 0;
    $skipped_count = 0;
    $thanked_count = 0;
    
    if (empty($users)) {
        echo "No users found!\n";
        return ['sent' => 0, 'failed' => 0, 'skipped' => 0, 'thanked' => 0];
    }
    
    $current_hour = intval(date('H'));
    echo "\n=== Processing Users for Reminders ===\n";
    echo "Current Hour: {$current_hour}\n";
    
    foreach ($users as $user_id => $user) {
        echo "\n--- User ID: $user_id ---\n";
        echo "Name: " . ($user['name'] ?? 'N/A') . "\n";
        echo "Step: " . ($user['step'] ?? 'N/A') . "\n";
        echo "Start Date: " . ($user['start_date'] ?? 'N/A') . "\n";
        echo "Chat ID: " . ($user['chat_id'] ?? 'N/A') . "\n";
        echo "Last Activity: " . ($user['last_activity'] ?? 'N/A') . "\n";
        
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
        
        echo "PROCESSING: Getting message for user\n";
        
        // Get message based on time and user activity
        $last_activity = $user['last_activity'] ?? null;
        $random_message = getRandomMessage($current_hour, $last_activity);
        
        // Check if it's a thank you message
        $is_thank_you = strpos($random_message, '🌟 Amazing! You\'ve already completed') !== false ||
                       strpos($random_message, '✨ Look at you go!') !== false ||
                       strpos($random_message, '🎉 You\'re ahead of the game!') !== false ||
                       strpos($random_message, '💚 Already completed!') !== false ||
                       strpos($random_message, '🏆 Champion move!') !== false;
        
        if (sendMessage($user['chat_id'], $random_message)) {
            if ($is_thank_you) {
                $thanked_count++;
                echo "THANKED: Sent appreciation message\n";
            } else {
                $sent_count++;
                echo "SENT: Reminder sent\n";
            }
        } else {
            $failed_count++;
            echo "FAILED: Could not send message\n";
        }
        
        // Small delay to avoid rate limiting
        usleep(200000); // 0.2 seconds
    }
    
    echo "\n=== Summary ===\n";
    echo "Total users: " . count($users) . "\n";
    echo "Reminder messages sent: $sent_count\n";
    echo "Thank you messages sent: $thanked_count\n";
    echo "Messages failed: $failed_count\n";
    echo "Users skipped: $skipped_count\n";
    
    // Log the operation
    $log_message = date('Y-m-d H:i:s') . " (Hour: {$current_hour}) - Reminders: {$sent_count} sent, {$thanked_count} thanked, {$failed_count} failed, {$skipped_count} skipped\n";
    file_put_contents('/home/jetncpan/public_html/selflove/reminder_log.txt', $log_message, FILE_APPEND);
    
    return ['sent' => $sent_count, 'failed' => $failed_count, 'skipped' => $skipped_count, 'thanked' => $thanked_count];
}

// Main execution
echo "=== CRON JOB STARTED ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "Hour: " . date('H') . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Working Directory: " . getcwd() . "\n";

$result = sendReminders();

echo "\n=== CRON JOB COMPLETED ===\n";
echo "Final Results: {$result['sent']} reminders sent, {$result['thanked']} users thanked, {$result['failed']} failed, {$result['skipped']} skipped\n";

// Log execution
$logMessage = "[" . date('Y-m-d H:i:s') . "] Cron executed - Sent: {$result['sent']}, Thanked: {$result['thanked']}, Failed: {$result['failed']}, Skipped: {$result['skipped']}\n";
file_put_contents('/home/jetncpan/public_html/selflove/cron_log.txt', $logMessage, FILE_APPEND);

echo "=== END ===\n";
?>
