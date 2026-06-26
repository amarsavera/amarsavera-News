<?php

require_once '../../includes/config.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if($id <= 0){
    header("Location: index.php");
    exit;
}

/* ==========================
   USER CHECK
========================== */

$stmt = $pdo->prepare("
SELECT id,name
FROM users
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$user = $stmt->fetch();

if(!$user){
    header("Location: index.php");
    exit;
}

/* ==========================
   SOFT DELETE
========================== */

$stmt = $pdo->prepare("
UPDATE users
SET status='inactive'
WHERE id=?
");

$stmt->execute([$id]);

/* ==========================
   ACTIVITY LOG
========================== */

$log = $pdo->prepare("
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
'Users',
'Delete User',
$id,
'User Deactivated : '.$user['name'],
$_SERVER['REMOTE_ADDR']
]);

$_SESSION['success'] = 'User deactivated successfully.';

header("Location: index.php");
exit;
?>