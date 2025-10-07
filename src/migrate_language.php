<?php
// migrate_language.php - One-time script to set language for existing users
// ⚠️ Run this ONCE after deploying the multilingual system
// Usage: php migrate_language.php

define('DATA_FILE', 'users.json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== LANGUAGE MIGRATION STARTED ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "Data File: " . DATA_FILE . "\n\n";

// Load users
function loadUsers() {
    if (!file_exists(DATA_FILE)) {
        echo "ERROR: Users file not found at: " . DATA_FILE . "\n";
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
        echo "JSON Error: " . json_last_error_msg() . "\n";
        return [];
    }
    
    return $users;
}

// Save users
function saveUsers($users) {
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === FALSE) {
        echo "ERROR: Could not encode JSON!\n";
        echo "JSON Error: " . json_last_error_msg() . "\n";
        return false;
    }
    
    $result = file_put_contents(DATA_FILE, $json);
    if ($result === FALSE) {
        echo "ERROR: Could not write to users file!\n";
        return false;
    }
    
    return true;
}

// Detect language from text
function detectLanguage($text) {
    // Persian Unicode range
    $persian_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
    return preg_match($persian_pattern, $text) ? 'fa' : 'en';
}

// Detect user's language from their responses
function detectUserLanguage($user) {
    // If already has language set, skip
    if (isset($user['language']) && in_array($user['language'], ['en', 'fa'])) {
        return [
            'language' => $user['language'], 
            'source' => 'already_set',
            'persian_count' => 0,
            'english_count' => 0
        ];
    }
    
    // Check if user has completed any days
    if (!isset($user['completed_days']) || empty($user['completed_days'])) {
        return [
            'language' => 'en', 
            'source' => 'no_data_default',
            'persian_count' => 0,
            'english_count' => 0
        ];
    }
    
    $persian_count = 0;
    $english_count = 0;
    
    foreach ($user['completed_days'] as $day => $data) {
        if (isset($data['response']) && !empty($data['response'])) {
            $lang = detectLanguage($data['response']);
            if ($lang === 'fa') {
                $persian_count++;
            } else {
                $english_count++;
            }
        }
    }
    
    // If no responses found
    if ($persian_count === 0 && $english_count === 0) {
        return [
            'language' => 'en', 
            'source' => 'no_responses_default',
            'persian_count' => 0,
            'english_count' => 0
        ];
    }
    
    // Return language with most responses
    $detected_lang = ($persian_count > $english_count) ? 'fa' : 'en';
    return [
        'language' => $detected_lang, 
        'source' => 'detected',
        'persian_count' => $persian_count,
        'english_count' => $english_count
    ];
}

// Main migration
$users = loadUsers();

if (empty($users)) {
    echo "No users found or error loading users file.\n";
    echo "=== MIGRATION ABORTED ===\n";
    exit(1);
}

$total_users = count($users);
$updated_count = 0;
$skipped_count = 0;
$stats = [
    'en' => 0,
    'fa' => 0,
    'already_set' => 0,
    'no_data' => 0
];

echo "Total users found: {$total_users}\n";
echo "Starting detection and update...\n\n";

foreach ($users as $user_id => &$user) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "User ID: {$user_id}\n";
    echo "Name: " . ($user['name'] ?? 'N/A') . "\n";
    echo "Step: " . ($user['step'] ?? 'N/A') . "\n";
    echo "Start Date: " . ($user['start_date'] ?? 'N/A') . "\n";
    
    $result = detectUserLanguage($user);
    
    echo "Detection Source: {$result['source']}\n";
    echo "Detected Language: {$result['language']}\n";
    
    if ($result['source'] === 'detected') {
        echo "Persian Responses: {$result['persian_count']}\n";
        echo "English Responses: {$result['english_count']}\n";
    }
    
    if ($result['source'] === 'already_set') {
        echo "Status: ⏭️  SKIPPED (already has language set)\n";
        $skipped_count++;
        $stats['already_set']++;
    } else {
        $user['language'] = $result['language'];
        echo "Status: ✅ UPDATED to {$result['language']}\n";
        $updated_count++;
        
        if ($result['source'] === 'no_data_default' || $result['source'] === 'no_responses_default') {
            $stats['no_data']++;
        } else {
            $stats[$result['language']]++;
        }
    }
    
    echo "\n";
}
unset($user); // Break reference

// Save updated users
if ($updated_count > 0) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Saving changes to users.json...\n";
    
    if (saveUsers($users)) {
        echo "✅ Successfully saved!\n\n";
        
        echo "=== MIGRATION COMPLETED SUCCESSFULLY ===\n";
        echo "Total users: {$total_users}\n";
        echo "Updated: {$updated_count}\n";
        echo "Skipped (already set): {$skipped_count}\n\n";
        
        echo "Language Statistics:\n";
        echo "  - English (detected): {$stats['en']}\n";
        echo "  - Persian (detected): {$stats['fa']}\n";
        echo "  - No data (defaulted to EN): {$stats['no_data']}\n";
        echo "  - Already had language: {$stats['already_set']}\n";
        
        // Log the migration
        $log_message = date('Y-m-d H:i:s') . " - Language migration completed\n";
        $log_message .= "  Total: {$total_users}, Updated: {$updated_count}, Skipped: {$skipped_count}\n";
        $log_message .= "  EN: {$stats['en']}, FA: {$stats['fa']}, No data: {$stats['no_data']}, Already set: {$stats['already_set']}\n";
        $log_message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        file_put_contents('migration_log.txt', $log_message, FILE_APPEND);
        echo "\n✅ Migration logged to migration_log.txt\n";
        
    } else {
        echo "❌ ERROR: Failed to save users file!\n";
        echo "=== MIGRATION FAILED ===\n";
        exit(1);
    }
} else {
    echo "=== NO UPDATES NEEDED ===\n";
    echo "All {$total_users} users already have language set.\n";
    
    // Still log it
    $log_message = date('Y-m-d H:i:s') . " - Migration run: No updates needed (all users already have language)\n";
    file_put_contents('migration_log.txt', $log_message, FILE_APPEND);
}

echo "\n=== MIGRATION COMPLETE ===\n";
echo "You can now deploy the updated bot files.\n";
echo "Check migration_log.txt for detailed log.\n";
?>
