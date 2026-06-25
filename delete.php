<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT *
FROM advertisement_bookings
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$booking = $stmt->fetch();

if(!$booking)
{
    die('Booking Not Found');
}

/*
|--------------------------------------------------------------------------
| Super Admin Check
|--------------------------------------------------------------------------
*/

$isSuperAdmin = false;

$check = $pdo->prepare("
SELECT role
FROM admins
WHERE id=?
LIMIT 1
");

$check->execute([
$_SESSION['admin_id']
]);

$admin = $check->fetch();

if(
$admin &&
$admin['role']=='super_admin'
)
{
$isSuperAdmin = true;
}

/*
|--------------------------------------------------------------------------
| Soft Delete Only
|--------------------------------------------------------------------------
*/

$update = $pdo->prepare("
UPDATE advertisement_bookings
SET
status='deleted',
deleted_at=NOW(),
deleted_by=?
WHERE id=?
");

$update->execute([

$_SESSION['admin_id'],

$id

]);

/*
|--------------------------------------------------------------------------
| Activity Log
|--------------------------------------------------------------------------
*/

$log = $pdo->prepare("
INSERT INTO activity_logs
(
user_type,
user_id,
module_name,
action_name,
record_id,
remarks,
ip_address,
created_at
)
VALUES
(
?,?,?,?,?,?,?,NOW()
)
");

$log->execute([

'admin',

$_SESSION['admin_id'],

'Advertisement Booking',

'Delete Booking',

$id,

'Soft Delete Applied',

$_SERVER['REMOTE_ADDR'] ?? ''

]);

/*
|--------------------------------------------------------------------------
| Override Log
|--------------------------------------------------------------------------
*/

if($isSuperAdmin)
{

$override = $pdo->prepare("
INSERT INTO admin_permissions_override
(
admin_id,
module_name,
record_id,
action_name,
remarks,
created_at
)
VALUES
(
?,?,?,?,?,
NOW()
)
");

$override->execute([

$_SESSION['admin_id'],

'Advertisement Booking',

$id,

'Soft Delete',

'Super Admin Override Delete'

]);

}

header("Location:index.php");
exit;