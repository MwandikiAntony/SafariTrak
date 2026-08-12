<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/session.php';

st_require_method('POST');

$input = st_input();

$identifier = trim($input['username'] ?? '');
$password = (string) ($input['password'] ?? '');
$remember = !empty($input['remember']);

if ($identifier === '' || $password === '') {
    st_json_error('Enter your username and password.', 422);
}

$db = safaritrak_db();

$stmt = $db->prepare('SELECT id, full_name, username, password_hash, is_suspended FROM users WHERE username = ? OR email = ? OR phone = ?');
$stmt->execute([$identifier, $identifier, preg_replace('/\D/', '', $identifier)]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    st_json_error('That username or password is not right.', 401);
}

if ((int) $user['is_suspended'] === 1) {
    st_json_error('This account has been suspended. Contact SafariTrak support for help.', 403);
}

st_login_user($user, $remember);

st_json_ok(['redirect' => 'index.php', 'user' => ['full_name' => $user['full_name'], 'username' => $user['username']]]);
