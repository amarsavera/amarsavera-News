<?php

require_once '../includes/config.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
SELECT *
FROM epapers
WHERE status=1
ORDER BY epaper_date DESC
");

$epapers = $stmt->fetchAll();

echo json_encode([
    'status'=>true,
    'total'=>count($epapers),
    'data'=>$epapers
]);