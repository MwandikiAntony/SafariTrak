<?php

function st_avatar_inner(array $user): string {
    if (!empty($user['avatar_path'])) {
        return '<img src="' . htmlspecialchars($user['avatar_path']) . '" class="avatar-img" alt="">';
    }
    $initial = strtoupper(substr($user['full_name'] ?? 'T', 0, 1));
    return htmlspecialchars($initial);
}

function st_initials(string $name): string {
    $parts = preg_split('/\s+/', trim($name));
    $letters = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        $letters .= strtoupper(substr($p, 0, 1));
    }
    return $letters ?: '?';
}

function st_notif_icon(string $type): string {
    $icons = [
        'journey_started' => 'fa-route',
        'journey_completed' => 'fa-flag-checkered',
        'arrival' => 'fa-flag-checkered',
        'route_deviation' => 'fa-triangle-exclamation',
        'new_message' => 'fa-regular fa-message',
        'location_share' => 'fa-location-arrow',
        'sos_alert' => 'fa-triangle-exclamation',
        'contact_request' => 'fa-user-plus',
        'group_invite' => 'fa-user-group',
    ];
    return $icons[$type] ?? 'fa-bell';
}

function st_notif_category(string $type): string {
    if (in_array($type, ['sos_alert', 'route_deviation'], true)) {
        return 'safety';
    }
    if ($type === 'new_message') {
        return 'messages';
    }
    return 'journey';
}

function st_notif_link(string $type, ?int $relatedJourneyId, ?int $relatedUserId): ?string {
    switch ($type) {
        case 'location_share':
        case 'journey_started':
            return $relatedJourneyId ? 'watch-journey.php?id=' . $relatedJourneyId : null;
        case 'journey_completed':
        case 'arrival':
        case 'route_deviation':
            return $relatedJourneyId ? 'watch-journey.php?id=' . $relatedJourneyId : 'my-journeys.php';
        case 'new_message':
            return $relatedUserId ? 'messages.php?to=' . $relatedUserId : 'messages.php';
        case 'contact_request':
            return 'trusted-contacts.php';
        case 'group_invite':
            return 'group-travel.php';
        case 'sos_alert':
            return 'safety.php';
        default:
            return null;
    }
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
