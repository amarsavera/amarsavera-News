<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
session_start();
}

if(!isset($_SESSION['admin_id'])){
header("Location: ../index.php");
exit;
}

$id=(int)($_GET['id'] ?? 0);

$stmt=$pdo->prepare("
UPDATE reporters
SET status='inactive'
WHERE id=?
");

$stmt->execute([$id]);

$log=$pdo->prepare("
INSERT INTO activity_logs
(
user_type,
user_id,
module_name,
action_name,
record_id,
remarks,
ip_address
)
VALUES
(
?,?,?,?,?,?,?
)
");

$log->execute([

'admin',
$_SESSION['admin_id'],
'Reporter',
'Deactivate Reporter',
$id,
'Reporter Deactivated',
$_SERVER['REMOTE_ADDR']

]);

header("Location:index.php");
exit;