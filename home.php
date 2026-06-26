<?php

require_once '../includes/config.php';

header('Content-Type:application/json');

$response = [

'breaking_news' => [],

'featured_news' => [],

'latest_news' => []

];

$response['breaking_news']
=
$pdo->query("
SELECT *
FROM news
WHERE breaking_news=1
AND status='approved'
ORDER BY id DESC
LIMIT 10
")->fetchAll();

$response['featured_news']
=
$pdo->query("
SELECT *
FROM news
WHERE featured_news=1
AND status='approved'
ORDER BY id DESC
LIMIT 10
")->fetchAll();

$response['latest_news']
=
$pdo->query("
SELECT *
FROM news
WHERE status='approved'
ORDER BY id DESC
LIMIT 20
")->fetchAll();

echo json_encode($response);