<?php

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/helpers.php';

$safaritrakUserId = st_current_user_id();

if ($safaritrakUserId === null) {
    header('Location: login.html');
    exit;
}

$stmt = safaritrak_db()->prepare('SELECT id, full_name, username, email, phone, avatar_path FROM users WHERE id = ?');
$stmt->execute([$safaritrakUserId]);
$currentUser = $stmt->fetch();

if (!$currentUser) {
    st_logout();
    header('Location: login.html');
    exit;
}

$userName = $currentUser['full_name'];
