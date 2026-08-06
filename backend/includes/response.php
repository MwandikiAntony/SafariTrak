<?php

function st_json_ok(array $data = [], int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => true], $data));
    exit;
}

function st_json_error(string $message, int $status = 400, array $extra = []): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => false, 'message' => $message], $extra));
    exit;
}

function st_require_method(string $method): void {
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        st_json_error('This endpoint only accepts ' . $method . ' requests.', 405);
    }
}

function st_input(): array {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        return $decoded;
    }
    return $_POST;
}
