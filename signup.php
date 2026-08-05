<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullName'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Using password_hash column name
    $stmt = $conn->prepare("INSERT INTO users (full_name, username, email, password_hash) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Username or email already exists.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>