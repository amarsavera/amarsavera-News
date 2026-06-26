<?php

require_once '../../includes/config.php';

session_start();

$id=(int)($_GET['id'] ?? 0);

$status=$_GET['status'] ?? 'inactive';

$stmt=$pdo->prepare("
UPDATE users
SET status=?
WHERE id=?
");

$stmt->execute([
$status,
$id
]);

header("Location:index.php");
exit;