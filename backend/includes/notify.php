<?php

require_once __DIR__ . '/../config/database.php';

function st_notify(int $userId, string $type, string $title, ?string $body = null, ?int $relatedJourneyId = null, ?int $relatedUserId = null): void {
    $db = safaritrak_db();
    $stmt = $db->prepare(
        'INSERT INTO notifications (user_id, type, title, body, related_journey_id, related_user_id)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$userId, $type, $title, $body, $relatedJourneyId, $relatedUserId]);
}
