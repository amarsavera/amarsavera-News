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
FROM advertisements
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$ad=$stmt->fetch();

if(!$ad){
die('Advertisement Not Found');
}

if(isset($_POST['submit_design']))
{

$pdo->prepare("
UPDATE advertisements
SET
status='client_review',
designer_note=?
WHERE id=?
")->execute([

$_POST['designer_note'],
$id

]);

header("Location:client-approval.php?id=".$id);
exit;

}

include '../../layout/header.php';

?>

<form method="POST">

<div class="card">

<div class="card-header">

Designer Review

</div>

<div class="card-body">

<h4>

<?= htmlspecialchars($ad['title']); ?>

</h4>

<textarea
name="designer_note"
class="form-control"
rows="5"></textarea>

<br>

<button
name="submit_design"
class="btn btn-success">

Send To Client

</button>

</div>

</div>

</form>

<?php include '../../layout/footer.php'; ?>