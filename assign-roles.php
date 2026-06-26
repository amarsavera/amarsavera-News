<?php

require_once '../../../includes/config.php';

session_start();

$userId=(int)($_GET['user_id'] ?? 0);

$roles=$pdo->query("
SELECT *
FROM roles
WHERE status=1
")->fetchAll();

if($_SERVER['REQUEST_METHOD']=='POST')
{

$stmt=$pdo->prepare("
UPDATE users
SET role_id=?
WHERE id=?
");

$stmt->execute([

$_POST['role_id'],
$userId

]);

header("Location:../users/index.php");
exit;

}

include '../../layout/header.php';

?>

<form method="POST">

<select
name="role_id"
class="form-control mb-3">

<?php foreach($roles as $role): ?>

<option
value="<?= $role['id']; ?>">

<?= htmlspecialchars($role['role_name']); ?>

</option>

<?php endforeach; ?>

</select>

<button
class="btn btn-danger">

Assign Role

</button>

</form>

<?php include '../../layout/footer.php'; ?>