<?php

require_once '../includes/config.php';

header('Content-Type:application/json');

$news = $pdo->query("
SELECT
id,
title,
short_description,
slug,
created_at

FROM news

WHERE status='approved'

ORDER BY id DESC

LIMIT 50
")->fetchAll();

echo json_encode($news);