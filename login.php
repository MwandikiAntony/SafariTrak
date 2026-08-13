<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read raw JSON input from JS postJSON()
    $input = json_decode(file_get_contents('php://input'), true);

    $usernameOrEmail = trim($input['username'] ?? '');
    $password        = trim($input['password'] ?? '');

    if (empty($usernameOrEmail) || empty($password)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Please enter both username/email and password.'
        ]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, email, password_hash, role FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password_hash'])) {
            $userRole = strtolower(trim($user['role'] ?? 'user'));

            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $userRole;

            // Root-relative pathing prevents 404s when called from /backend/api/
            $redirectUrl = ($userRole === 'admin') ? '/SafariTrak/admin-dashboard.php' : '/SafariTrak/index.php';

            echo json_encode([
                'success'  => true,
                'redirect' => $redirectUrl,
                'role'     => $userRole
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Invalid password.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'User not found.'
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid request method.'
    ]);
}
?>