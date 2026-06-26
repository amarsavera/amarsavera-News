<?php

require_once 'includes/config.php';

$newHash = password_hash('Admin@123', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
UPDATE users
SET password=?
WHERE email='admin@saragone.in'
");

$stmt->execute([$newHash]);

echo "Password Reset Success";