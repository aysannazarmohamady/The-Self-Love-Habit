<?php
// lang_en.php - English translations

function getLangEn() {
    return [
        // Welcome messages
        'welcome_title' => '🌟 Welcome to the 30-Day Self-Confidence Challenge Bot! 🌟',
        'welcome_description' => "I'm here to guide you through a scientifically-backed journey to boost your self-confidence over the next 30 days.\n\nThis challenge is based on proven psychological principles and small daily actions that can make a big difference in how you see yourself.\n\nTo get started, please tell me your name:",
        'welcome_back' => "Welcome back, {name}! 🌟\n\nYou're already on your confidence journey! Use the menu below to navigate:",
        
        // Language selection
        'choose_language' => "🌐 *Choose Your Language / زبان خود را انتخاب کنید*\n\nPlease select your preferred language:",
        'language_set' => "✅ Language set to English! Let's continue your journey! 🚀",
        
        // Menu buttons
        'btn_progress' => '📊 My Progress',
        'btn_all_days' => '📅 All Days',
        'btn_today_challenge' => "🎯 Today's Challenge",
        'btn_gratitude' => '🙏 Daily Gratitude',
        'btn_help' => '❓ Help',
        'btn_change_language' => '🌐 Change Language',
        
        // Intro messages
        'intro_text' => "*Great to meet you, {name}! 🎉*\n\n*🧠 Why This Challenge Works:*\n\nResearch in neuroplasticity shows that our brains can form new neural pathways through consistent practice. This challenge uses:\n\n• *Behavioral activation* - Small daily actions create positive momentum\n• *Cognitive restructuring* - Shifting negative self-talk to positive affirmations\n• *Exposure therapy principles* - Gradually stepping outside your comfort zone\n• *Self-efficacy theory* - Building confidence through mastery experiences\n\n🔒 *Your Privacy Matters:*\nI want you to feel completely safe sharing with me. All your responses and progress are encrypted and stored securely. Nobody has access to your personal information - not even the bot creator. This is your private space to grow and reflect. 🤗\n\nAre you ready to start your transformation journey right now? 🚀",
        
        'start_now' => "🎉 Wonderful, {name}! Your journey begins now!*\n\nRemember, I'm here as your trusted companion throughout this journey. Think of me as your personal confidence coach who's always here to listen, encourage, and celebrate your wins - big and small! 🤗\n\nLet's dive into your first challenge...",
        
        'start_later' => "*No problem at all, {name}! 😊*\n\nTake your time - personal growth can't be rushed! When you're ready to begin your confidence journey, just type /start and I'll be here waiting for you.\n\nRemember, the best time to plant a tree was 20 years ago. The second best time is now... whenever your 'now' feels right! 🌱\n\nI'm excited to be part of your transformation when you're ready! ✨",
        
        // Challenge messages
        'challenge_intro' => "*🎉 Dear {name}! Ready for today's adventure?*\n\n━━━━━━━━━━━━━━━━━━━━\n*📅 DAY {day}: {title}*\n━━━━━━━━━━━━━━━━━━━━\n\n{description}\n\n💡 *Why this works:* {why_it_works}\n\n{prompt}",
        
        'challenge_completed' => "*🎉 Day {day} Complete!*\n\n🏆 *+10 Points! Total: {points} points*",
        
        'all_days_completed' => "🎉 You've completed all 30 days! Congratulations! Use '📅 All Days' to review your journey.",
        
        'day_already_completed' => "✅ You've already completed Day {day}! Great job! Use '📅 All Days' to see other days.",
        
        'final_message' => "\n\n🎊 *INCREDIBLE! You've completed the entire 30-Day Challenge!* 🎊\n\nYou can still view and edit your responses anytime using '📅 All Days'!",
        
        // Gratitude
        'gratitude_prompt' => "*🙏 Take a moment for gratitude*\n\nWhat's ONE thing you're grateful for right now?\n\nIt can be big or small - your health, a person, a moment, anything that brings warmth to your heart.\n\n_Share it with me:_",
        
        'gratitude_response' => "*{response}*\n\n💚 Gratitude is a powerful practice - thank you for sharing this moment with me!",
        
        'gratitude_short' => "Please provide a response with at least 3 characters. 😊",
        
        // Progress
        'progress_report' => "*🌟 {name}'s Confidence Journey 🌟*\n\n📊 *Progress:* {completed}/30 days ({percentage}%)\n🏆 *Total Points:* {points}\n📅 *Started:* {start_date}\n\n",
        
        'progress_bar' => "Progress: {bar}\n\n",
        
        'progress_keep_going' => "Keep up the amazing work! 🚀\n",
        'progress_ready_start' => "Ready to start your journey? 💪\n",
        'progress_footer' => "\nUse '📅 All Days' to see and edit your responses!",
        
        // All days view
        'all_days_title' => "*📅 Your 30-Day Challenge Overview*\n\n",
        'all_days_legend' => "✅ = Completed | ⭕ = Available | 🔒 = Locked\n\nTap any available day to view or edit your response!",
        
        // Day view
        'day_view_completed' => "*📖 Day {day}: {title}*\n\n*Status:* ✅ Completed\n*Completed on:* {date}\n\n*Your Response:*\n_{response}_\n\nWould you like to edit your response?",
        
        'day_view_not_completed' => "_Type your response below or use the Persian translation button above._",
        
        // Edit
        'edit_prompt' => "*✏️ Edit Your Response - Day {day}*\n*Challenge:* {title}\n\n*Your current response:*\n_{response}_\n\nPlease type your new response:",
        
        'edit_success' => "*✅ Response Updated Successfully!*\n\n*Day {day} - New Response:*\n_{response}_\n\nYour response has been saved! Keep up the amazing work! 🌟",
        
        'edit_short' => "Please provide a response with at least 3 characters. 😊",
        
        // Help
        'help_text' => "*🆘 How to Use This Bot*\n\n*📊 My Progress* - View your journey overview, points, and completion percentage\n\n*📅 All Days* - See all 30 days with status indicators. Tap any day to view or edit your response\n\n*🎯 Today's Challenge* - Get your current day's challenge\n\n*🙏 Daily Gratitude* - Practice gratitude anytime you want\n\n*🌐 Change Language* - Switch between English and Persian\n\n*Day Status Indicators:*\n✅ = Completed\n⭕ = Available to complete\n🔒 = Locked (complete previous days first)\n\n*Privacy:* All your responses are encrypted and stored securely. Only you can see them!\n\nNeed more help? Contact the bot creator! 😊",
        
        // Errors
        'invalid_name' => "Please enter a valid name (2-50 characters):",
        'challenge_not_found' => "Challenge not found!",
        'day_locked' => "Day {day} is not available yet! Complete previous days first.",
        'use_menu' => "Hi {name}! 😊 Use the menu below to navigate:",
        'use_start' => "Please start by typing /start 🌟",
        
        // Buttons
        'btn_start_now' => '✨ Yes, let\'s start now!',
        'btn_start_later' => '⏰ I\'ll start later',
        'btn_translate_fa' => 'توضیح به فارسی',
        'btn_translate_en' => '🔙 Back to English',
        'btn_edit' => '✏️ Edit Response',
        'btn_back_all_days' => '🔙 Back to All Days',
        'btn_cancel_edit' => '❌ Cancel Edit',
        'btn_english' => '🇬🇧 English',
        'btn_persian' => '🇮🇷 فارسی',
    ];
}
?>
