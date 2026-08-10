<?php

require_once __DIR__ . '/auth-guard.php';

$orgStmt = safaritrak_db()->prepare(
    'SELECT o.id, o.name, oa.role
     FROM organization_admins oa
     JOIN organizations o ON o.id = oa.organization_id
     WHERE oa.user_id = ?
     LIMIT 1'
);
$orgStmt->execute([$currentUser['id']]);
$myOrg = $orgStmt->fetch() ?: null;
