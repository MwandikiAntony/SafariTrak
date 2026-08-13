<?php

require_once __DIR__ . '/../config/database.php';

function st_start_session(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function st_current_user_id(): ?int {
    st_start_session();

    if (!empty($_SESSION['user_id'])) {
        return (int) $_SESSION['user_id'];
    }

    st_try_remember_login();

    return !empty($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function st_require_login(): int {
    $userId = st_current_user_id();
    if ($userId === null) {
        st_json_error('You need to log in first.', 401);
    }
    return $userId;
}

function st_require_admin(): void {
    st_start_session();

    $userId = st_current_user_id();
    $role = strtolower(trim($_SESSION['role'] ?? 'user'));

    if ($userId === null || $role !== 'admin') {
        http_response_code(403);
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>SafariTrak | Access Denied</title>
            <style>
                body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #f4f6f8; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; }
                .error-card { background: #ffffff; padding: 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); text-align: center; max-width: 400px; width: 90%; }
                .error-card h2 { color: #d32f2f; margin-top: 0; margin-bottom: 8px; font-size: 20px; }
                .error-card p { color: #555555; font-size: 14px; line-height: 1.5; margin-bottom: 24px; }
                .error-card a { display: inline-block; background: #10b981; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 14px; }
                .error-card a:hover { background: #059669; }
            </style>
        </head>
        <body>
            <div class="error-card">
                <h2>Access Restricted</h2>
                <p>You must be registered as an admin to access this page.</p>
                <a href="index.php">Return to Dashboard</a>
            </div>
        </body>
        </html>';
        exit;
    }
}

function st_login_user(array $user, bool $remember = false): void {
    st_start_session();
    session_regenerate_id(true);

    $_SESSION['user_id']   = (int) $user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['role']      = strtolower(trim($user['role'] ?? 'user'));

    if ($remember) {
        st_issue_remember_token((int) $user['id']);
    }
}

function st_logout(): void {
    st_start_session();

    if (!empty($_COOKIE['safaritrak_remember'])) {
        $parts = explode(':', $_COOKIE['safaritrak_remember'], 2);
        $selector = $parts[0];
        $stmt = safaritrak_db()->prepare('DELETE FROM sessions WHERE id = ?');
        $stmt->execute([$selector]);
    }

    setcookie('safaritrak_remember', '', time() - 3600, '/');

    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function st_issue_remember_token(int $userId): void {
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));
    $validatorHash = hash('sha256', $validator);
    $expiresAt = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));

    $stmt = safaritrak_db()->prepare(
        'INSERT INTO sessions (id, user_id, validator_hash, user_agent, ip_address, expires_at) VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $selector,
        $userId,
        $validatorHash,
        substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
        $_SERVER['REMOTE_ADDR'] ?? null,
        $expiresAt,
    ]);

    setcookie(
        'safaritrak_remember',
        $selector . ':' . $validator,
        time() + (30 * 24 * 60 * 60),
        '/',
        '',
        false,
        true
    );
}

function st_try_remember_login(): void {
    if (empty($_COOKIE['safaritrak_remember'])) {
        return;
    }

    $parts = explode(':', $_COOKIE['safaritrak_remember'], 2);
    if (count($parts) !== 2) {
        return;
    }

    [$selector, $validator] = $parts;

    $stmt = safaritrak_db()->prepare('SELECT * FROM sessions WHERE id = ? AND expires_at > NOW()');
    $stmt->execute([$selector]);
    $row = $stmt->fetch();

    if (!$row) {
        return;
    }

    $computedHash = hash('sha256', $validator);

    if (!hash_equals($row['validator_hash'], $computedHash)) {
        $deleteStmt = safaritrak_db()->prepare('DELETE FROM sessions WHERE id = ?');
        $deleteStmt->execute([$selector]);
        return;
    }

    $userStmt = safaritrak_db()->prepare('SELECT id, full_name, username, role FROM users WHERE id = ?');
    $userStmt->execute([$row['user_id']]);
    $user = $userStmt->fetch();

    if (!$user) {
        return;
    }

    session_regenerate_id(true);
    $_SESSION['user_id']   = (int) $user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['role']      = strtolower(trim($user['role'] ?? 'user'));

    $touchStmt = safaritrak_db()->prepare('UPDATE sessions SET last_active_at = NOW() WHERE id = ?');
    $touchStmt->execute([$selector]);
}