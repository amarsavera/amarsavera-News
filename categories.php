<?php

require_once '../includes/config.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
SELECT
id,
category_name,
slug
FROM news_categories
ORDER BY category_name ASC
");

$categories = $stmt->fetchAll();

echo json_encode([
    'status' => true,
    'total' => count($categories),
    'data' => $categories
]);