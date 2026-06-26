<?php

require_once '../includes/config.php';

header('Content-Type:application/json');

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT *
FROM news
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

echo json_encode(
$stmt->fetch()
);