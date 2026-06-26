<?php

require_once '../../includes/config.php';

session_start();

$id=(int)($_GET['id'] ?? 0);

$newPassword =
password_hash(
'123456',
PASSWORD_DEFAULT
);

$stmt=$pdo->prepare("
UPDATE users
SET password=?
WHERE id=?
");

$stmt->execute([
$newPassword,
$id
]);

header("Location:index.php");
exit;