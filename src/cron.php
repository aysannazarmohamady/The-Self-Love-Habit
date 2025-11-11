<?php
// cron.php - Simple reminder system with multiple message variations
define('BOT_TOKEN', '');
define('DATA_FILE', '');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Multiple message variations
function getRandomMessage() {
    // Randomly choose message type (40% morning challenge, 40% evening challenge, 20% gratitude)
    $rand = rand(1, 100);
    
    if ($rand <= 40) {
        $message_type = 'morning_challenge';
    } elseif ($rand <= 80) {
        $message_type = 'evening_challenge';
    } else {
        $message_type = 'gratitude';
    }
    
    // Morning Challenge Messages - 15 variations
    if ($message_type === 'morning_challenge') {
        $messages = [
            "*Good morning superstar!* ☀️\n\nReady to conquer today's confidence challenge? You've got this! 💪\n\nRemember: Every brave step makes you stronger! ✨",
            
            "*Rise and shine!* 🌅\n\nYour confidence journey continues today! What amazing thing will you do? 🚀\n\nSmall actions = Big transformations! 💫",
            
            "*Hey champion!* 🏆\n\nTime for your daily dose of courage! Your future self will thank you 💝\n\nToday's challenge is waiting for you! 🎯",
            
            "*Morning motivation coming your way!* ⚡\n\nAnother day, another chance to grow stronger! 🌱\n\nYour confidence challenge is ready when you are! 💎",
            
            "*Hello beautiful soul!* 🌸\n\nDon't forget your confidence boost today! You deserve to feel amazing 👑\n\nEvery step forward counts! 🦋",
            
            "*Wakey wakey!* 🌞\n\nToday's the perfect day to be brave! What's one thing you'll do for YOU? 💗\n\nYour confidence muscles need their morning workout! 💯",
            
            "*Good morning warrior!* ⚔️\n\nReady to slay today's self-doubt? I know you are! 🔥\n\nConfidence isn't built in a day, but today IS a building day! 🏗️",
            
            "*Sunrise reminder!* 🌄\n\nYour journey to unstoppable confidence continues NOW! ⏰\n\nWhat brave action will you take today? Make it count! 🎪",
            
            "*Morning sparkle!* ✨\n\nTime to show the world (and yourself) what you're made of! 🌟\n\nToday's challenge = Tomorrow's confidence! Let's go! 🚀",
            
            "*Hey there rockstar!* 🎸\n\nAnother opportunity to become the person you're meant to be! 🦸\n\nDon't let this day pass without doing something BRAVE! 💥",
            
            "*Coffee's ready, so is your challenge!* ☕\n\nStart your day with courage, not just caffeine! 😉\n\nSmall daily wins = Massive confidence gains! 📈",
            
            "*Good morning legend!* 🌟\n\nLegends aren't born, they're built - one challenge at a time! 🏗️\n\nWhat's your power move today? 💪",
            
            "*Rise up!* 🌇\n\nToday is your canvas - paint it with courage! 🎨\n\nYour confidence challenge is the first brushstroke! ✨",
            
            "*Morning vibes!* 🎵\n\nFeeling it or not, show up for yourself today! 💖\n\nConsistency beats motivation every single time! 🔄",
            
            "*New day, new you!* 🆕\n\nEvery sunrise brings a fresh chance to level up! 📊\n\nYour confidence quest continues - ready player one? 🎮"
        ];
        
        return $messages[array_rand($messages)];
    }
    
    // Evening Challenge Messages - 15 variations
    if ($message_type === 'evening_challenge') {
        $messages = [
            "*Hey there!* 🌙\n\nHow's your confidence challenge going today? 🤔\n\nEven tiny steps create powerful changes! Keep going! 💪",
            
            "*Gentle reminder!* 🔔\n\nHave you tackled today's challenge yet? 🎯\n\nIt's never too late to do something brave! ✨",
            
            "*Check-in time!* ⏰\n\nYour confidence is calling! Have you answered? 📞\n\nConsistency builds unstoppable confidence! 🚀",
            
            "*Sweet reminder!* 🍯\n\nToday's challenge is still waiting for you! 😊\n\nProgress over perfection - always! 🌟",
            
            "*Friendly nudge!* 👋\n\nRemember your confidence goal today? 🎪\n\nEvery moment is a new chance to grow! 🌱",
            
            "*Evening check!* 🌆\n\nDid you show up for yourself today? 💖\n\nThere's still time to make it happen! ⭐",
            
            "*Quick question!* 🤷\n\nHave you done today's confidence challenge? 🎭\n\nEven 5 minutes of bravery counts! The clock is ticking! ⏳",
            
            "*Afternoon accountability!* 📝\n\nJust checking in on your awesome self! How's it going? 😊\n\nRemember: You promised YOURSELF you'd do this! 💪",
            
            "*Sunset reminder!* 🌅\n\nBefore the day ends, have you challenged yourself? 🤔\n\nDon't go to bed without at least trying! Your future self is watching! 👀",
            
            "*Psst... hey you!* 🗣️\n\nYour confidence challenge isn't going to complete itself! 😅\n\nWhat are you waiting for? Permission? Consider this it! ✅",
            
            "*Reality check!* 💭\n\nDid you do something brave today or just think about it? 🧐\n\nThinking is great, but DOING is where the magic happens! ✨",
            
            "*Time flies reminder!* 🕐\n\nAnother day is slipping away... caught your challenge yet? 🎣\n\nNo judgment, just motivation! You've got this! 🎯",
            
            "*Honest question:* 🙋\n\nWhat's stopping you from your challenge today? 🚧\n\nWhatever it is, it's smaller than your potential! Break through! 💥",
            
            "*Mid-day motivation!* 🌤️\n\nStill time to turn today into a WIN! 🏆\n\nYour confidence challenge is waiting - don't leave it hanging! 🤝",
            
            "*Let's be real:* 💯\n\nYou know you'll feel amazing after completing today's challenge! 😌\n\nSo why wait? Present you = gift to future you! 🎁"
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
    
    if (empty($users)) {
        echo "No users found!\n";
        return ['sent' => 0, 'failed' => 0, 'skipped' => 0];
    }
    
    echo "\n=== Processing Users for Reminders ===\n";
    
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
    $log_message = date('Y-m-d H:i:s') . " - Reminders: {$sent_count} sent, {$failed_count} failed, {$skipped_count} skipped\n";
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
