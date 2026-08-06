<?php

function st_avatar_inner(array $user): string {
    if (!empty($user['avatar_path'])) {
        return '<img src="' . htmlspecialchars($user['avatar_path']) . '" class="avatar-img" alt="">';
    }
    $initial = strtoupper(substr($user['full_name'] ?? 'T', 0, 1));
    return htmlspecialchars($initial);
}

function st_greeting(): string {
    $hour = (int) date('G');

    if ($hour < 12) {
        return 'Good morning';
    }
    if ($hour < 17) {
        return 'Good afternoon';
    }
    return 'Good evening';
}
