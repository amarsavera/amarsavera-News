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

$plans = $pdo->query("
SELECT *
FROM subscription_plans
WHERE status=1
")->fetchAll();

$stmt = $pdo->prepare("
SELECT *
FROM subscribers
WHERE id=?
");

$stmt->execute([$id]);

$subscriber = $stmt->fetch();

if(!$subscriber){
die('Subscriber Not Found');
}

if($_SERVER['REQUEST_METHOD']=='POST'){

$update = $pdo->prepare("
UPDATE subscribers
SET
name=?,
mobile=?,
email=?,
plan_id=?,
status=?
WHERE id=?
");

$update->execute([

$_POST['name'],
$_POST['mobile'],
$_POST['email'],
$_POST['plan_id'],
$_POST['status'],
$id

]);

header("Location:view.php?id=".$id);
exit;
}

include '../layout/header.php';
?>