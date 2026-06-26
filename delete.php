<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
session_start();
}

if(!isset($_SESSION['admin_id'])){
header("Location: ../index.php");
exit;
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
UPDATE subscription_plans
SET status=0
WHERE id=?
");

$stmt->execute([$id]);

header("Location:index.php");
exit;
?>