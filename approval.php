<?php

require_once '../../../includes/config.php';
require_once '../includes/auth.php';

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

if(isset($_POST['approve']))
{

$pdo->prepare("
UPDATE advertisements
SET status='approved'
WHERE id=?
")->execute([$id]);

header("Location:approved.php");
exit;

}

if(isset($_POST['revision']))
{

$pdo->prepare("
UPDATE advertisements
SET
status='designer_revision',
client_note=?
WHERE id=?
")->execute([

$_POST['client_note'],
$id

]);

header("Location:designer.php?id=".$id);
exit;

}

include '../../layout/header.php';

?>

<form method="POST">

<div class="card">

<div class="card-header">

Client Approval

</div>

<div class="card-body">

<textarea
name="client_note"
class="form-control"
rows="5"
placeholder="Revision Remark"></textarea>

<br>

<button
name="approve"
class="btn btn-success">

Approve

</button>

<button
name="revision"
class="btn btn-danger">

Send Revision

</button>

</div>

</div>

</form>

<?php include '../../layout/footer.php'; ?>