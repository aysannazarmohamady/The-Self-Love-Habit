<?php
// profile_functions.php
// User Profile Management Functions (English Only)

// Get profile questions
function getProfileQuestions() {
    return [
        1 => [
            'question' => "*👤 Profile Section - Question 1 of 5*\n\n*What is your age?*",
            'type' => 'options',
            'options' => [
                ['text' => '18-24', 'value' => '18-24'],
                ['text' => '25-34', 'value' => '25-34'],
                ['text' => '35-44', 'value' => '35-44'],
                ['text' => '45+', 'value' => '45+']
            ],
            'key' => 'age'
        ],
        2 => [
            'question' => "*👤 Profile Section - Question 2 of 5*\n\n*What is your gender?*",
            'type' => 'options',
            'options' => [
                ['text' => 'Male', 'value' => 'male'],
                ['text' => 'Female', 'value' => 'female'],
                ['text' => 'Prefer not to say', 'value' => 'prefer_not_to_say']
            ],
            'key' => 'gender'
        ],
        3 => [
            'question' => "*👤 Profile Section - Question 3 of 5*\n\n*What is your work/education status?*\n\n_If employed, please also mention your field._",
            'type' => 'options',
            'options' => [
                ['text' => 'Student', 'value' => 'student'],
                ['text' => 'Employed', 'value' => 'employed'],
                ['text' => 'Entrepreneur/Freelancer', 'value' => 'entrepreneur'],
                ['text' => 'Job Seeking', 'value' => 'job_seeking'],
                ['text' => 'Other', 'value' => 'other']
            ],
            'key' => 'occupation_status',
            'follow_up' => 'occupation_field'
        ],
        4 => [
            'question' => "*👤 Profile Section - Question 4 of 5*\n\n*What is your living situation?*",
            'type' => 'options',
            'options' => [
                ['text' => 'Living alone', 'value' => 'alone'],
                ['text' => 'With family', 'value' => 'with_family'],
                ['text' => 'With partner', 'value' => 'with_partner'],
                ['text' => 'With friends/roommates', 'value' => 'with_roommates']
            ],
            'key' => 'living_situation'
        ],
        5 => [
            'question' => "*👤 Profile Section - Question 5 of 5*\n\n*What are one or two of your main hobbies/interests?*\n\n_Please write in a few words (e.g., reading, sports, music, art)_",
            'type' => 'text',
            'key' => 'hobbies'
        ]
    ];
}

// Get user's profile data
function getUserProfile($user) {
    return $user['profile'] ?? null;
}

// Check if profile is complete
function isProfileComplete($user) {
    $profile = getUserProfile($user);
    if (!$profile) return false;
    
    $required_fields = ['age', 'gender', 'occupation_status', 'living_situation', 'hobbies'];
    
    foreach ($required_fields as $field) {
        if (!isset($profile[$field]) || empty($profile[$field])) {
            return false;
        }
    }
    
    return true;
}

// Format profile for display
function formatProfileView($user) {
    $profile = getUserProfile($user);
    
    if (!$profile) {
        return "*👤 Your Profile*\n\n❌ _You haven't completed your profile yet._\n\nTo personalize your coaching experience better, please complete your profile.";
    }
    
    $message = "*👤 Your Profile*\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Age
    if (isset($profile['age'])) {
        $message .= "📅 *Age:* {$profile['age']}\n\n";
    }
    
    // Gender
    if (isset($profile['gender'])) {
        $gender_labels = [
            'male' => 'Male',
            'female' => 'Female',
            'prefer_not_to_say' => 'Prefer not to say'
        ];
        $gender_text = $gender_labels[$profile['gender']] ?? $profile['gender'];
        $message .= "⚧ *Gender:* {$gender_text}\n\n";
    }
    
    // Occupation
    if (isset($profile['occupation_status'])) {
        $occupation_labels = [
            'student' => 'Student',
            'employed' => 'Employed',
            'entrepreneur' => 'Entrepreneur/Freelancer',
            'job_seeking' => 'Job Seeking',
            'other' => 'Other'
        ];
        $occupation_text = $occupation_labels[$profile['occupation_status']] ?? $profile['occupation_status'];
        $message .= "💼 *Work Status:* {$occupation_text}";
        
        if (isset($profile['occupation_field']) && !empty($profile['occupation_field'])) {
            $message .= " ({$profile['occupation_field']})";
        }
        $message .= "\n\n";
    }
    
    // Living situation
    if (isset($profile['living_situation'])) {
        $living_labels = [
            'alone' => 'Living alone',
            'with_family' => 'With family',
            'with_partner' => 'With partner',
            'with_roommates' => 'With friends/roommates'
        ];
        $living_text = $living_labels[$profile['living_situation']] ?? $profile['living_situation'];
        $message .= "🏠 *Living Situation:* {$living_text}\n\n";
    }
    
    // Hobbies
    if (isset($profile['hobbies'])) {
        $message .= "🎨 *Hobbies:* {$profile['hobbies']}\n\n";
    }
    
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    
    if (isset($profile['completed_at'])) {
        $completed_date = date('M j, Y', strtotime($profile['completed_at']));
        $message .= "_Completed on: {$completed_date}_";
    }
    
    return $message;
}

// Handle profile menu
function handleProfileMenu($chat_id, $user) {
    $is_complete = isProfileComplete($user);
    $profile_view = formatProfileView($user);
    
    if ($is_complete) {
        $keyboard = [
            'inline_keyboard' => [
                [['text' => '✏️ Edit Profile', 'callback_data' => 'edit_profile']],
                [['text' => '🔙 Back', 'callback_data' => 'back_to_menu']]
            ]
        ];
    } else {
        $keyboard = [
            'inline_keyboard' => [
                [['text' => '📝 Complete Profile', 'callback_data' => 'start_profile']],
                [['text' => '🔙 Back', 'callback_data' => 'back_to_menu']]
            ]
        ];
    }
    
    sendMessage($chat_id, $profile_view, $keyboard);
}

// Start profile completion flow
function startProfileCompletion($user_id, $user, $chat_id) {
    $questions = getProfileQuestions();
    $first_question = $questions[1];
    
    // Send first question
    sendProfileQuestion($chat_id, $first_question, 1);
    
    // Update user step
    saveUser($user_id, array_merge($user, [
        'step' => 'profile_q1_active',
        'profile_temp' => [],
        'last_activity' => date('Y-m-d H:i:s')
    ]));
}

// Send profile question
function sendProfileQuestion($chat_id, $question, $question_num) {
    $message = $question['question'];
    
    if ($question['type'] == 'options') {
        // Create inline keyboard with options
        $buttons = [];
        foreach ($question['options'] as $option) {
            $buttons[] = [['text' => $option['text'], 'callback_data' => "profile_answer_{$question_num}_{$option['value']}"]];
        }
        
        $keyboard = ['inline_keyboard' => $buttons];
        sendMessage($chat_id, $message, $keyboard);
    } else {
        // Text input
        sendMessage($chat_id, $message);
    }
}

// Handle profile answer (callback)
function handleProfileAnswerCallback($user_id, $user, $question_num, $answer_value, $chat_id, $callback_id) {
    $questions = getProfileQuestions();
    $current_question = $questions[$question_num];
    
    // Save answer temporarily
    $profile_temp = $user['profile_temp'] ?? [];
    $profile_temp[$current_question['key']] = $answer_value;
    
    // Check if this question has a follow-up (like occupation field)
    if (isset($current_question['follow_up']) && ($answer_value == 'employed' || $answer_value == 'entrepreneur')) {
        // Ask for field
        $follow_up_message = "*💼 What field do you work in?*\n\n_Please write your work field (e.g., IT, Education, Healthcare)_";
        
        sendMessage($chat_id, $follow_up_message);
        
        saveUser($user_id, array_merge($user, [
            'step' => "profile_q{$question_num}_followup",
            'profile_temp' => $profile_temp,
            'last_activity' => date('Y-m-d H:i:s')
        ]));
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback_id);
        return;
    }
    
    // Move to next question or complete
    $next_question_num = $question_num + 1;
    
    if ($next_question_num <= count($questions)) {
        // Send next question
        $next_question = $questions[$next_question_num];
        sendProfileQuestion($chat_id, $next_question, $next_question_num);
        
        saveUser($user_id, array_merge($user, [
            'step' => "profile_q{$next_question_num}_active",
            'profile_temp' => $profile_temp,
            'last_activity' => date('Y-m-d H:i:s')
        ]));
    } else {
        // Profile complete
        completeProfile($user_id, $user, $profile_temp, $chat_id);
    }
    
    file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback_id);
}

// Handle profile text answer (for hobbies and follow-ups)
function handleProfileTextAnswer($user_id, $user, $text, $chat_id) {
    $questions = getProfileQuestions();
    
    // Check current step
    if (preg_match('/^profile_q(\d+)_active$/', $user['step'], $matches)) {
        $question_num = intval($matches[1]);
        $current_question = $questions[$question_num];
        
        // Validate text length
        if (strlen(trim($text)) < 2) {
            sendMessage($chat_id, "Please enter at least 2 characters. 😊");
            return;
        }
        
        // Save answer
        $profile_temp = $user['profile_temp'] ?? [];
        $profile_temp[$current_question['key']] = trim($text);
        
        // Move to next or complete
        $next_question_num = $question_num + 1;
        
        if ($next_question_num <= count($questions)) {
            $next_question = $questions[$next_question_num];
            sendProfileQuestion($chat_id, $next_question, $next_question_num);
            
            saveUser($user_id, array_merge($user, [
                'step' => "profile_q{$next_question_num}_active",
                'profile_temp' => $profile_temp,
                'last_activity' => date('Y-m-d H:i:s')
            ]));
        } else {
            completeProfile($user_id, $user, $profile_temp, $chat_id);
        }
    }
    // Handle follow-up (occupation field)
    elseif (preg_match('/^profile_q(\d+)_followup$/', $user['step'], $matches)) {
        $question_num = intval($matches[1]);
        
        if (strlen(trim($text)) < 2) {
            sendMessage($chat_id, "Please enter your work field. 😊");
            return;
        }
        
        // Save field
        $profile_temp = $user['profile_temp'] ?? [];
        $profile_temp['occupation_field'] = trim($text);
        
        // Move to next question
        $next_question_num = $question_num + 1;
        
        if ($next_question_num <= count($questions)) {
            $next_question = $questions[$next_question_num];
            sendProfileQuestion($chat_id, $next_question, $next_question_num);
            
            saveUser($user_id, array_merge($user, [
                'step' => "profile_q{$next_question_num}_active",
                'profile_temp' => $profile_temp,
                'last_activity' => date('Y-m-d H:i:s')
            ]));
        } else {
            completeProfile($user_id, $user, $profile_temp, $chat_id);
        }
    }
}

// Complete profile
function completeProfile($user_id, $user, $profile_data, $chat_id) {
    // Save profile
    $profile_data['completed_at'] = date('Y-m-d H:i:s');
    
    saveUser($user_id, array_merge($user, [
        'profile' => $profile_data,
        'step' => 'waiting_for_next_day',
        'profile_temp' => null,
        'last_activity' => date('Y-m-d H:i:s')
    ]));
    
    // Send confirmation
    $success_message = "*✅ Your profile has been completed successfully!*\n\n";
    $success_message .= "Now I can better personalize your coaching experience! 🎯\n\n";
    $success_message .= "You can view or edit your profile anytime from the '👤 My Profile' menu.";
    
    sendMessage($chat_id, $success_message, getMainKeyboard());
}

// Handle edit profile
function handleEditProfile($user_id, $user, $chat_id) {
    $questions = getProfileQuestions();
    
    $edit_message = "*✏️ Edit Profile*\n\n";
    $edit_message .= "Which section would you like to edit?";
    
    // Create buttons for each field
    $buttons = [];
    
    $field_labels = [
        'age' => '📅 Age',
        'gender' => '⚧ Gender',
        'occupation_status' => '💼 Work Status',
        'living_situation' => '🏠 Living Situation',
        'hobbies' => '🎨 Hobbies'
    ];
    
    foreach ($questions as $num => $question) {
        $field_key = $question['key'];
        $label = $field_labels[$field_key] ?? $field_key;
        $buttons[] = [['text' => $label, 'callback_data' => "edit_profile_field_{$num}"]];
    }
    
    $buttons[] = [['text' => '🔙 Back', 'callback_data' => 'view_profile']];
    
    $keyboard = ['inline_keyboard' => $buttons];
    
    sendMessage($chat_id, $edit_message, $keyboard);
}

// Handle edit specific field
function handleEditProfileField($user_id, $user, $field_num, $chat_id, $callback_id) {
    $questions = getProfileQuestions();
    $question = $questions[$field_num];
    
    // Send question for this field
    sendProfileQuestion($chat_id, $question, $field_num);
    
    // Set user to edit mode for this field
    saveUser($user_id, array_merge($user, [
        'step' => "profile_edit_q{$field_num}_active",
        'last_activity' => date('Y-m-d H:i:s')
    ]));
    
    file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback_id);
}

// Handle edit field answer
function handleEditFieldAnswer($user_id, $user, $field_num, $answer_value, $chat_id, $callback_id) {
    $questions = getProfileQuestions();
    $question = $questions[$field_num];
    
    // Update profile
    $profile = $user['profile'];
    $profile[$question['key']] = $answer_value;
    $profile['updated_at'] = date('Y-m-d H:i:s');
    
    // Check for follow-up
    if (isset($question['follow_up']) && ($answer_value == 'employed' || $answer_value == 'entrepreneur')) {
        $follow_up_message = "*💼 What field do you work in?*\n\n_Please write your work field_";
        
        sendMessage($chat_id, $follow_up_message);
        
        saveUser($user_id, array_merge($user, [
            'step' => "profile_edit_q{$field_num}_followup",
            'profile' => $profile,
            'last_activity' => date('Y-m-d H:i:s')
        ]));
        
        file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback_id);
        return;
    }
    
    saveUser($user_id, array_merge($user, [
        'step' => 'waiting_for_next_day',
        'profile' => $profile,
        'last_activity' => date('Y-m-d H:i:s')
    ]));
    
    // Show success message and updated profile
    $success_message = "*✅ Your profile has been updated!*";
    sendMessage($chat_id, $success_message);
    
    // Show updated profile
    $profile_view = formatProfileView(array_merge($user, ['profile' => $profile]));
    
    $keyboard = [
        'inline_keyboard' => [
            [['text' => '✏️ Edit Again', 'callback_data' => 'edit_profile']],
            [['text' => '🔙 Back', 'callback_data' => 'back_to_menu']]
        ]
    ];
    
    sendMessage($chat_id, $profile_view, $keyboard);
    
    file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/answerCallbackQuery?callback_query_id=" . $callback_id);
}

// Handle edit field text answer (for hobbies and follow-ups)
function handleEditFieldTextAnswer($user_id, $user, $field_num, $text, $chat_id) {
    $questions = getProfileQuestions();
    
    if (strlen(trim($text)) < 2) {
        sendMessage($chat_id, "Please enter at least 2 characters. 😊");
        return;
    }
    
    // Check if it's follow-up or main answer
    if (preg_match('/^profile_edit_q(\d+)_followup$/', $user['step'], $matches)) {
        // It's follow-up (occupation field)
        $profile = $user['profile'];
        $profile['occupation_field'] = trim($text);
        $profile['updated_at'] = date('Y-m-d H:i:s');
    } else {
        // It's main answer (hobbies)
        $question = $questions[$field_num];
        $profile = $user['profile'];
        $profile[$question['key']] = trim($text);
        $profile['updated_at'] = date('Y-m-d H:i:s');
    }
    
    saveUser($user_id, array_merge($user, [
        'step' => 'waiting_for_next_day',
        'profile' => $profile,
        'last_activity' => date('Y-m-d H:i:s')
    ]));
    
    // Show success
    $success_message = "*✅ Your profile has been updated!*";
    sendMessage($chat_id, $success_message);
    
    // Show updated profile
    $profile_view = formatProfileView(array_merge($user, ['profile' => $profile]));
    
    $keyboard = [
        'inline_keyboard' => [
            [['text' => '✏️ Edit Again', 'callback_data' => 'edit_profile']],
            [['text' => '🔙 Back', 'callback_data' => 'back_to_menu']]
        ]
    ];
    
    sendMessage($chat_id, $profile_view, $keyboard);
}

?>
