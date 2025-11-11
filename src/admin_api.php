<?php
// admin_api.php - Enhanced API for admin dashboard with Level 1 & 2 support
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

define('BOT_TOKEN', '');
define('DATA_FILE', 'users.json');

// Simple admin authentication (optional - uncomment to enable)
// $admin_password = 'your_secure_password_here';
// $provided_password = $_GET['admin_key'] ?? $_POST['admin_key'] ?? '';
// if ($provided_password !== $admin_password) {
//     echo json_encode(['success' => false, 'message' => 'Unauthorized']);
//     exit;
// }

function loadUsers() {
    if (!file_exists(DATA_FILE)) {
        return [];
    }
    
    $json = file_get_contents(DATA_FILE);
    if ($json === FALSE) {
        return [];
    }
    
    $users = json_decode($json, true);
    return $users ?: [];
}

function sendTelegramMessage($chat_id, $text, $parse_mode = 'Markdown') {
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => $parse_mode
    ];
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
            'timeout' => 10
        ]
    ];
    
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        return false;
    }
    
    $response = json_decode($result, true);
    return ($response && isset($response['ok']) && $response['ok']);
}

function calculateUserStats($user) {
    $completed_days = $user['completed_days'] ?? [];
    $current_day = $user['current_day'] ?? 1;
    $completed_count = 0;
    $points = 0;
    
    // Count completed days
    foreach ($completed_days as $day => $data) {
        if (isset($data['completed']) && $data['completed']) {
            $completed_count++;
            $points += 10;
        }
    }
    
    // Determine total days based on current level
    $total_days = 30; // Default Level 1
    if ($current_day > 30 && $current_day <= 60) {
        $total_days = 60; // Level 2
    } elseif ($current_day > 60) {
        $total_days = 60; // Both levels complete
    }
    
    $progress_percentage = $total_days > 0 ? round(($completed_count / $total_days) * 100, 1) : 0;
    
    return [
        'completed_days' => $completed_count,
        'points' => $points,
        'progress_percentage' => $progress_percentage,
        'total_days' => $total_days
    ];
}

function getUserLevel($user) {
    $current_day = $user['current_day'] ?? 1;
    
    if ($current_day <= 30) {
        return 'Level 1: Self-Confidence';
    } elseif ($current_day <= 60) {
        return 'Level 2: Social Confidence';
    } else {
        return 'Both Levels Complete';
    }
}

function getUserStatus($user) {
    $step = $user['step'] ?? 'unknown';
    
    if (strpos($step, 'active') !== false) {
        return 'active';
    } elseif ($step === 'both_levels_complete' || $step === 'level1_completed' || $step === 'challenge_completed') {
        return 'completed';
    } elseif ($step === 'waiting_for_next_day' || strpos($step, 'waiting') !== false) {
        return 'waiting';
    } elseif ($step === 'postponed') {
        return 'postponed';
    } else {
        return 'new';
    }
}

function processUsersData($users) {
    $processed = [];
    $stats = [
        'total' => count($users),
        'active' => 0,
        'completed' => 0,
        'responses' => 0
    ];
    
    foreach ($users as $user_id => $user) {
        $userStats = calculateUserStats($user);
        $status = getUserStatus($user);
        
        // Count statistics
        if ($status === 'completed') {
            $stats['completed']++;
        } elseif ($status === 'active') {
            $stats['active']++;
        }
        
        $stats['responses'] += $userStats['completed_days'];
        
        $processed[] = [
            'user_id' => $user_id,
            'name' => $user['name'] ?? 'No Name',
            'first_name' => $user['first_name'] ?? '',
            'chat_id' => $user['chat_id'] ?? '',
            'step' => $user['step'] ?? 'unknown',
            'status' => $status,
            'level' => getUserLevel($user),
            'start_date' => $user['start_date'] ?? '',
            'current_day' => $user['current_day'] ?? 1,
            'completed_days' => $userStats['completed_days'],
            'total_days' => $userStats['total_days'],
            'points' => $userStats['points'],
            'progress_percentage' => $userStats['progress_percentage'],
            'last_activity' => $user['last_activity'] ?? '',
            'created_at' => $user['created_at'] ?? ''
        ];
    }
    
    // Sort by last activity (most recent first) - default sorting
    usort($processed, function($a, $b) {
        $aTime = strtotime($a['last_activity'] ?: '2000-01-01');
        $bTime = strtotime($b['last_activity'] ?: '2000-01-01');
        return $bTime - $aTime;
    });
    
    return ['users' => $processed, 'stats' => $stats];
}

// Main API handler
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getUsers':
        try {
            $users = loadUsers();
            $result = processUsersData($users);
            echo json_encode(['success' => true] + $result);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'sendMessage':
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                throw new Exception('Invalid JSON input');
            }
            
            $type = $input['type'] ?? '';
            $message = $input['message'] ?? '';
            $use_markdown = $input['markdown'] ?? true;
            $specific_chat_id = $input['chat_id'] ?? '';
            
            if (empty($message)) {
                throw new Exception('Message cannot be empty');
            }
            
            $users = loadUsers();
            $sent_count = 0;
            $failed_count = 0;
            
            $parse_mode = $use_markdown ? 'Markdown' : null;
            
            switch ($type) {
                case 'all':
                    foreach ($users as $user_id => $user) {
                        if (isset($user['chat_id']) && isset($user['start_date'])) {
                            if (sendTelegramMessage($user['chat_id'], $message, $parse_mode)) {
                                $sent_count++;
                            } else {
                                $failed_count++;
                            }
                            usleep(200000);
                        }
                    }
                    break;
                    
                case 'active':
                    foreach ($users as $user_id => $user) {
                        if (isset($user['chat_id']) && isset($user['step'])) {
                            $status = getUserStatus($user);
                            if ($status === 'active') {
                                if (sendTelegramMessage($user['chat_id'], $message, $parse_mode)) {
                                    $sent_count++;
                                } else {
                                    $failed_count++;
                                }
                                usleep(200000);
                            }
                        }
                    }
                    break;
                    
                case 'completed':
                    foreach ($users as $user_id => $user) {
                        if (isset($user['chat_id']) && isset($user['step'])) {
                            $status = getUserStatus($user);
                            if ($status === 'completed') {
                                if (sendTelegramMessage($user['chat_id'], $message, $parse_mode)) {
                                    $sent_count++;
                                } else {
                                    $failed_count++;
                                }
                                usleep(200000);
                            }
                        }
                    }
                    break;
                    
                case 'specific':
                    if (empty($specific_chat_id)) {
                        throw new Exception('Chat ID is required for specific message');
                    }
                    
                    if (sendTelegramMessage($specific_chat_id, $message, $parse_mode)) {
                        $sent_count = 1;
                    } else {
                        $failed_count = 1;
                    }
                    break;
                    
                default:
                    throw new Exception('Invalid message type');
            }
            
            $log_message = date('Y-m-d H:i:s') . " - Admin broadcast ({$type}): {$sent_count} sent, {$failed_count} failed\n";
            @file_put_contents('admin_log.txt', $log_message, FILE_APPEND);
            
            echo json_encode([
                'success' => true,
                'sent' => $sent_count,
                'failed' => $failed_count,
                'message' => "Message sent successfully to {$sent_count} user(s)"
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'getUserDetail':
        try {
            $user_id = $_GET['user_id'] ?? '';
            if (empty($user_id)) {
                throw new Exception('User ID is required');
            }
            
            $users = loadUsers();
            if (!isset($users[$user_id])) {
                throw new Exception('User not found');
            }
            
            $user = $users[$user_id];
            $stats = calculateUserStats($user);
            
            $detail = [
                'user_id' => $user_id,
                'name' => $user['name'] ?? 'No Name',
                'first_name' => $user['first_name'] ?? '',
                'chat_id' => $user['chat_id'] ?? '',
                'step' => $user['step'] ?? 'unknown',
                'status' => getUserStatus($user),
                'level' => getUserLevel($user),
                'start_date' => $user['start_date'] ?? '',
                'current_day' => $user['current_day'] ?? 1,
                'completed_days' => $stats['completed_days'],
                'total_days' => $stats['total_days'],
                'points' => $stats['points'],
                'progress_percentage' => $stats['progress_percentage'],
                'last_activity' => $user['last_activity'] ?? '',
                'created_at' => $user['created_at'] ?? '',
                'responses' => $user['completed_days'] ?? []
            ];
            
            echo json_encode(['success' => true, 'user' => $detail]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'getStats':
        try {
            $users = loadUsers();
            $result = processUsersData($users);
            
            $detailed_stats = [
                'total_users' => count($users),
                'level1_users' => 0,
                'level2_users' => 0,
                'completed_both' => 0,
                'users_by_status' => [
                    'active' => 0,
                    'completed' => 0,
                    'waiting' => 0,
                    'new' => 0,
                    'postponed' => 0
                ],
                'recent_activity_7days' => 0
            ];
            
            foreach ($users as $user_id => $user) {
                $current_day = $user['current_day'] ?? 1;
                $status = getUserStatus($user);
                
                if ($current_day <= 30) {
                    $detailed_stats['level1_users']++;
                } elseif ($current_day <= 60) {
                    $detailed_stats['level2_users']++;
                } else {
                    $detailed_stats['completed_both']++;
                }
                
                $detailed_stats['users_by_status'][$status]++;
                
                if (isset($user['last_activity'])) {
                    $activity_date = strtotime($user['last_activity']);
                    $days_ago = (time() - $activity_date) / (60 * 60 * 24);
                    if ($days_ago <= 7) {
                        $detailed_stats['recent_activity_7days']++;
                    }
                }
            }
            
            echo json_encode([
                'success' => true,
                'stats' => $result['stats'],
                'detailed_stats' => $detailed_stats
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'testConnection':
        try {
            $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/getMe";
            $result = @file_get_contents($url);
            
            if ($result === FALSE) {
                throw new Exception('Failed to connect to Telegram API');
            }
            
            $response = json_decode($result, true);
            if (!$response || !$response['ok']) {
                throw new Exception('Invalid bot token or API error');
            }
            
            echo json_encode([
                'success' => true,
                'bot_info' => $response['result'],
                'message' => 'Bot connection successful'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
