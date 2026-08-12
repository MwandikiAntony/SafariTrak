<?php

function st_load_env(string $filePath): void {
    if (!is_readable($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (trim($line) === '' || str_starts_with(trim($line), '#')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2) + [1 => '']);
        if ($key === '' || getenv($key) !== false) {
            continue;
        }

        $value = trim($value, "\"'");
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

st_load_env(__DIR__ . '/../../.env');
