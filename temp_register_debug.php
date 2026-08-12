<?php
$url = 'http://localhost:8001/backend/api/register.php';
$payload = [
    'full_name' => 'Debug User',
    'username' => 'debuguser123',
    'email' => 'debuguser123@example.com',
    'phone' => '0712345678',
    'password' => 'debugpass1',
    'terms' => true,
];
$options = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => json_encode($payload),
        'ignore_errors' => true,
    ],
];
$ctx = stream_context_create($options);
$result = file_get_contents($url, false, $ctx);
$status = $http_response_header[0] ?? '';
echo "STATUS: {$status}\n";
echo "RESPONSE:\n";
echo $result;
