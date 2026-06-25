<?php

require_once '../../../includes/config.php';

session_start();

$id=(int)($_GET['id'] ?? 0);

$pdo->prepare("
UPDATE news
SET status='published'
WHERE id=?
")->execute([$id]);

header("Location:approved.php");
exit;