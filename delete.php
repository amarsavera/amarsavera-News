<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$id=(int)($_GET['id']??0);

$stmt=$pdo->prepare("
UPDATE tehsils
SET status='inactive'
WHERE id=?
");

$stmt->execute([$id]);

header("Location:index.php");
exit;
?>