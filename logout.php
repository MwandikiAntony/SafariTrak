<?php
require __DIR__ . '/backend/includes/session.php';

st_logout();

header('Location: login.html');
exit;
