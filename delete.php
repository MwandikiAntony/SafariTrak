<?php
header('Content-Type: application/json');

require_once __DIR__ . '/backend/includes/helpers.php';
require_once __DIR__ . '/backend/includes/session.php';

$currentUserId = st_current_user_id();

if (!$currentUserId) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please log in.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$messageId = isset($input['message_id']) ? (int)$input['message_id'] : 0;

if (!$messageId) {
    echo json_encode(['success' => false, 'message' => 'Invalid message specified.']);
    exit;
}

try {
    $pdo = safaritrak_db();
    
    $stmt = $pdo->prepare("
        DELETE FROM messages 
        WHERE id = ? 
          AND (sender_id = ? OR receiver_id = ?)
    ");
    
    $stmt->execute([
        $messageId,
        $currentUserId,
        $currentUserId
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Message deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Message not found or permission denied.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'SQL Error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}