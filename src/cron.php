<?php
// cron.php - Simple cron job file
// This file should be called daily by cron job

// Send a special request to the main bot file to trigger daily processing
$bot_url = 'https://yourdomain.com/index.php'; // Replace with your actual bot URL

$data = [
    'cron_job' => true,
    'action' => 'daily_challenges',
    'timestamp' => time()
];

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($bot_url, false, $context);

// Log the operation
$log_message = date('Y-m-d H:i:s') . " - Cron job executed. Result: " . ($result ? 'Success' : 'Failed') . "\n";
file_put_contents('cron_log.txt', $log_message, FILE_APPEND);

echo "Cron job completed at " . date('Y-m-d H:i:s') . "\n";
?>
