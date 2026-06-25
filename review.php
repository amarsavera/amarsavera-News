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

$stmt=$pdo->prepare("
SELECT *
FROM news
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$news=$stmt->fetch();

if(!$news){
die('News Not Found');
}

if(isset($_POST['approve']))
{

$pdo->prepare("
UPDATE news
SET status='approved'
WHERE id=?
")->execute([$id]);

header("Location:approved.php");
exit;

}

if(isset($_POST['reject']))
{

$pdo->prepare("
UPDATE news
SET status='rejected'
WHERE id=?
")->execute([$id]);

header("Location:rejected.php");
exit;

}

include '../../layout/header.php';

?>

<div class="container-fluid">

<div class="card">

<div class="card-header bg-primary text-white">

News Review

</div>

<div class="card-body">

<h3>

<?= htmlspecialchars($news['title']); ?>

</h3>

<hr>

<?= $news['full_description']; ?>

<form method="POST" class="mt-4">

<button
name="approve"
class="btn btn-success">

Approve

</button>

<button
name="reject"
class="btn btn-danger">

Reject

</button>

</form>

</div>

</div>

</div>

<?php include '../../layout/footer.php'; ?>