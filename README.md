# The-Self-Love-Habit

# 30-Day Self-Confidence Challenge Bot

A Telegram bot designed to guide users through a scientifically-backed 30-day journey to boost self-confidence through daily challenges and AI-powered coaching responses.

## Features

### Core Functionality
- **30 Daily Challenges**: Each day presents a unique confidence-building activity
- **Progress Tracking**: Visual progress bars, points system (10 points per challenge)
- **Response Management**: Users can view, edit, and complete challenges at their own pace
- **AI Coaching**: Personalized encouraging responses using Gemini AI

### Multilingual Support
- **Dual Language Interface**: English challenges with Persian translation option
- **Smart Language Detection**: Automatically detects user response language (Persian/English)
- **Contextual Responses**: AI coaching responses match the user's input language
- **Translation Feature**: Each challenge includes a "توضیح به فارسی" button for Persian summary

### User Experience
- **Flexible Navigation**: Users can access any available day, edit previous responses
- **Status Indicators**: ✅ Completed | ⭕ Available | 🔒 Locked
- **Privacy-Focused**: All data encrypted and stored securely
- **Persistent Keyboard**: Easy navigation with main menu always accessible

## Technical Stack

### Backend
- **Language**: PHP
- **Data Storage**: JSON file-based system
- **API Integration**: Gemini AI for coaching responses and translations
- **Platform**: Telegram Bot API

## Installation

1. **Prerequisites**
   - PHP-enabled web server
   - SSL certificate (required for Telegram webhooks)
   - Telegram Bot Token
   - Google Gemini API Key

2. **Setup**
   ```bash
   # Upload files to your web server
   # Update API keys in index.php
   define('BOT_TOKEN', 'your_telegram_bot_token');
   define('GEMINI_API_KEY', 'your_gemini_api_key');
   
   # Set webhook
   https://api.telegram.org/bot[BOT_TOKEN]/setWebhook?url=https://yourserver.com/path/to/config.php
   ```

3. **Security Note**
   - Store API keys in environment variables for production
   - Ensure proper file permissions for users.json
   - Use HTTPS for webhook endpoint

## Usage

### For Users
1. Start with `/start` command
2. Provide your name for personalization
3. Begin Day 1 challenge or explore all days
4. Complete challenges by typing responses
5. Use Persian translation button when needed
6. Track progress through the menu system

### Bot Commands
- `/start` - Initialize or return to main menu
- Main menu buttons:
  - 📊 My Progress - View completion stats and points
  - 📅 All Days - Access any challenge day
  - 🎯 Today's Challenge - Current day's challenge
  - ❓ Help - Usage instructions

## Challenge Structure

Each challenge includes:
- **Title**: Clear, actionable challenge name
- **Description**: Detailed explanation of the activity
- **Why it Works**: Scientific/psychological rationale
- **Prompt**: Specific question or action request
- **Encouragement**: Additional support messages
- **Completion Message**: Personalized celebration response

## Language Features

### Persian Translation
- Challenges automatically translated using AI
- Maintains encouraging tone and cultural context
- Provides concise summaries rather than word-for-word translation

### Smart Response Handling
```php
// Detects language and responds accordingly
$response_language = detectLanguage($user_response);
$ai_response = generateCoachingResponse($challenge_title, $user_response, $response_language);
```

## Data Structure

### User Data
```json
{
  "user_id": {
    "name": "User Name",
    "chat_id": 123456789,
    "step": "day_5_active",
    "start_date": "2024-01-15",
    "current_day": 5,
    "completed_days": {
      "1": {
        "completed": true,
        "response": "User's response",
        "language": "en",
        "completed_at": "2024-01-15 10:30:00"
      }
    }
  }
}
```

## AI Integration

### Gemini API Usage
- **Coaching Responses**: Generates personalized encouragement based on user responses
- **Translation Service**: Converts English challenges to natural Persian summaries
- **Dual Language Support**: Maintains coaching quality in both languages

### Prompt Engineering
- Coaching prompts focus on validation, encouragement, and growth
- Translation prompts prioritize natural language and cultural context
- Both maintain consistent supportive tone

## Contributing

When adding features:
1. Maintain the encouraging, supportive tone
2. Ensure Persian translation compatibility
3. Test language detection accuracy
4. Follow existing code structure patterns
5. Update challenge data carefully to maintain psychological effectiveness



## Support

For technical issues or feature requests, refer to the bot's help system or contact the development team through the bot interface.
