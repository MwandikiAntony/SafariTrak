<?php

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helpers.php';

st_start_session();

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$db = safaritrak_db();

$stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$currentUser = $stmt->fetch();

if (!$currentUser || (int) $currentUser['is_suspended'] === 1) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

$userName = $currentUser['full_name'];

$unreadStmt = $db->prepare(
    'SELECT COUNT(DISTINCT sender_id) AS c FROM messages WHERE receiver_id = ? AND read_at IS NULL'
);
$unreadStmt->execute([$currentUser['id']]);
$unreadConversationCount = (int) ($unreadStmt->fetch()['c'] ?? 0);