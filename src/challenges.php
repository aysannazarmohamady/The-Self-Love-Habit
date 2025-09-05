<?php
// challenges.php - All challenge data

function getChallenges() {
    return [
        1 => [
            'title' => 'Write Down Your Strengths',
            'description' => "Let's start by acknowledging your awesomeness! ✨\n\nTake a moment to write down your strengths. Think about what you're naturally good at - it could be listening to friends, cooking delicious meals, organizing your space, problem-solving, being creative, or anything else that comes to mind.",
            'why_it_works' => 'Focusing on your strengths helps rewire your brain to see yourself in a positive light. It\'s like giving your confidence a warm, encouraging hug!',
            'prompt' => 'When you\'re ready, share a few of your strengths with me. I promise to keep them safe and celebrate them with you! 🌟\n\nWhat are some things you\'re genuinely good at?',
            'encouragement' => [
                'I can see you\'re thinking about this! 😊\n\nDon\'t worry if it feels challenging to list your strengths - that\'s actually pretty normal. Sometimes we\'re our own toughest critics!',
                'Try thinking about:\n• What do friends or family often compliment you on?\n• What tasks do you find easier than others seem to?\n• What would your best friend say you\'re good at?\n\nTake your time and share whatever comes to mind - even small things count! 🌟'
            ],
            'completion_message' => 'this is absolutely beautiful! 🌟\n\nI can feel the authenticity in what you\'ve shared. These strengths you\'ve identified - they\'re not just words, they\'re the foundation of who you are! 💪\n\nTake a moment to really let this sink in. You\'ve just acknowledged some incredible qualities about yourself, and that takes courage. 🤗'
        ],
        
        2 => [
            'title' => 'Set a Small, Achievable Goal for Today',
            'description' => 'Setting and achieving goals can be a massive confidence booster! 🎯\n\nToday, I want you to set a small, achievable goal that you can complete today. It could be something simple like finishing a book chapter, going for a 15-minute walk, organizing your desk, or calling a friend you haven\'t spoken to in a while.',
            'why_it_works' => 'Achieving goals, no matter how small, releases dopamine in your brain - the same chemical that makes you feel accomplished and motivated. Each small win builds momentum for bigger achievements!',
            'prompt' => 'What\'s one small goal you\'d like to achieve today? Make it specific and realistic - something that will make you feel proud when you complete it! 🌟',
            'encouragement' => [
                'Remember, the goal is to set yourself up for success! 😊',
                'Think of something that:\n• Can be completed in 30 minutes to 2 hours\n• Is within your control\n• Will give you a sense of accomplishment\n\nEven something as simple as "make my bed" or "drink 6 glasses of water today" counts! What matters is following through. 💪'
            ],
            'completion_message' => 'I love this goal! 🎉\n\nYou\'ve chosen something that\'s both meaningful and achievable. That\'s the perfect recipe for building confidence! When you complete this goal today, take a moment to really celebrate it - you deserve to feel proud of every step forward you take. 🌟'
        ],
        
        3 => [
            'title' => 'Compliment Yourself Out Loud',
            'description' => 'We often forget to appreciate ourselves, but today we\'re changing that! 🪞✨\n\nI want you to look in the mirror and give yourself a genuine compliment out loud. Yes, OUT LOUD! It might feel a bit weird at first, but hearing positive affirmations in your own voice is incredibly powerful.',
            'why_it_works' => 'When you speak positive affirmations aloud, you\'re engaging multiple senses and creating stronger neural pathways. Your brain starts to believe what it hears repeatedly, especially when it comes from you!',
            'prompt' => 'After you\'ve given yourself that compliment in the mirror, come back and tell me what you said! I\'d love to celebrate this moment with you. 🌟\n\nWhat compliment did you give yourself?',
            'encouragement' => [
                'I know it can feel silly or uncomfortable at first! 😊',
                'Try starting with something simple like:\n• "I am kind and compassionate"\n• "I am capable and strong"\n• "I have beautiful eyes"\n• "I am working hard to grow"\n\nThe key is to mean it when you say it. Look yourself in the eyes and speak with conviction! 💪'
            ],
            'completion_message' => 'YES! 🎉 You just did something incredibly brave and powerful!\n\nSpeaking kindly to yourself out loud is a skill that many adults never develop. You\'re literally rewiring your brain to be more self-compassionate. How did it feel to hear those words in your own voice? 🌟'
        ]
    ];
}

function getChallenge($day) {
    $challenges = getChallenges();
    return isset($challenges[$day]) ? $challenges[$day] : null;
}

function formatChallengeMessage($day, $challenge, $user_name) {
    $message = "*🎉 Good morning, {$user_name}! Ready for today's adventure?*\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "*📅 DAY {$day}: {$challenge['title']}*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= $challenge['description'] . "\n\n";
    $message .= "💡 *Why this works:* " . $challenge['why_it_works'] . "\n\n";
    $message .= $challenge['prompt'];
    
    return $message;
}

function getEncouragementMessage($day) {
    $challenge = getChallenge($day);
    if (!$challenge || !isset($challenge['encouragement'])) {
        return "Take your time and share whatever comes to mind! 🌟";
    }
    
    return implode("\n\n", $challenge['encouragement']);
}

function getCompletionMessage($day, $user_name) {
    $challenge = getChallenge($day);
    if (!$challenge) {
        return "*Great job completing Day {$day}!* 🎉";
    }
    
    $message = "*{$user_name}, " . $challenge['completion_message'] . "*\n\n";
    $message .= "*🎉 Day {$day} Complete!*\n\n";
    
    if ($day < 30) {
        $message .= "Your confidence journey continues to unfold beautifully! Tomorrow, I'll be back with Day " . ($day + 1) . " of our challenge.\n\n";
        $message .= "For now, carry this accomplishment with you. You're building something incredible, one day at a time! ✨\n\n";
        $message .= "Sweet dreams, and get ready for another step forward tomorrow! 😊";
    } else {
        $message .= "*🏆 CONGRATULATIONS! You've completed the entire 30-Day Self-Confidence Challenge!* 🎊\n\n";
        $message .= "Look at how far you've come! You've shown up for yourself every day, faced challenges, and grown in ways you might not have imagined possible.\n\n";
        $message .= "This isn't the end - it's the beginning of a more confident, self-assured you! 🌟";
    }
    
    return $message;
}
?>
