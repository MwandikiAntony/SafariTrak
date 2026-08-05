<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'update_username') {
    $newUsername = trim($_POST['username'] ?? '');

    if (empty($newUsername)) {
        echo json_encode(['success' => false, 'message' => 'Username cannot be empty.']);
        exit();
    }

    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $checkStmt->bind_param("si", $newUsername, $userId);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username is already taken.']);
        exit();
    }

    $updateStmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
    $updateStmt->bind_param("si", $newUsername, $userId);
    if ($updateStmt->execute()) {
        $_SESSION['username'] = $newUsername;
        echo json_encode(['success' => true, 'message' => 'Username updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update username.']);
    }
}

if ($action === 'update_password') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in both password fields.']);
        exit();
    }

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($currentPassword, $user['password_hash'])) {
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $updateStmt->bind_param("si", $newHash, $userId);
        
        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Password updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect current password.']);
    }
}
?>