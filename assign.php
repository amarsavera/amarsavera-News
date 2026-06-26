<?php

require_once '../../includes/config.php';

session_start();

$userId=(int)($_GET['id'] ?? 0);

$permissions=$pdo->query("
SELECT *
FROM permissions
ORDER BY permission_name ASC
")->fetchAll();

if($_SERVER['REQUEST_METHOD']=='POST')
{

$pdo->prepare("
DELETE FROM user_permissions
WHERE user_id=?
")->execute([$userId]);

if(!empty($_POST['permissions']))
{

foreach($_POST['permissions'] as $permissionId)
{

$stmt=$pdo->prepare("
INSERT INTO user_permissions
(
user_id,
permission_id
)
VALUES
(
?,?
)
");

$stmt->execute([
$userId,
$permissionId
]);

}

}

header("Location:index.php");
exit;

}

include '../layout/header.php';

?>

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

Save Rights

</button>

</form>

<?php include '../layout/footer.php'; ?>