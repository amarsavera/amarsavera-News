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
SELECT *
FROM subscription_plans
WHERE id=?
");

$stmt->execute([$id]);

$plan = $stmt->fetch();

if(!$plan){
die('Plan Not Found');
}

if($_SERVER['REQUEST_METHOD']=='POST'){

$update = $pdo->prepare("
UPDATE subscription_plans
SET
plan_name=?,
amount=?,
duration_days=?,
status=?
WHERE id=?
");

$update->execute([

$_POST['plan_name'],
$_POST['amount'],
$_POST['duration_days'],
$_POST['status'],
$id

]);

header("Location:index.php");
exit;
}

include '../layout/header.php';
?>