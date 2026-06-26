<?php

require_once '../../../includes/config.php';

session_start();

$roleId=(int)($_GET['id'] ?? 0);

$permissions=$pdo->query("
SELECT *
FROM permissions
ORDER BY permission_name ASC
")->fetchAll();

if($_SERVER['REQUEST_METHOD']=='POST')
{

$pdo->prepare("
DELETE FROM role_permissions
WHERE role_id=?
")->execute([$roleId]);

if(!empty($_POST['permissions']))
{

foreach($_POST['permissions'] as $permissionId)
{

$stmt=$pdo->prepare("
INSERT INTO role_permissions
(
role_id,
permission_id
)
VALUES
(
?,?
)
");

$stmt->execute([
$roleId,
$permissionId
]);

}

}

header("Location:index.php");
exit;

}

include '../../layout/header.php';

?>

<div class="container-fluid">

<form method="POST">

<div class="row">

<?php foreach($permissions as $permission): ?>

<div class="col-md-3 mb-2">

<label>

<input
type="checkbox"
name="permissions[]"
value="<?= $permission['id']; ?>">

<?= htmlspecialchars($permission['permission_name']); ?>

</label>

</div>

<?php endforeach; ?>

</div>

<button
class="btn btn-danger">

Save Permissions

</button>

</form>

</div>

<?php include '../../layout/footer.php'; ?>