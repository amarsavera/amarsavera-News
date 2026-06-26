<?php

require_once '../../../includes/config.php';
require_once '../../includes/auth.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    header("Location: /admin/index.php");
    exit;
}
$id=(int)($_GET['id'] ?? 0);

$pdo->prepare("
UPDATE advertisements
SET status='published'
WHERE id=?
")->execute([$id]);

header("Location:approved.php");
exit;