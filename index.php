<?php

require_once '../../../includes/config.php';

session_start();

$list=$pdo->query("
SELECT *
FROM roles
ORDER BY id DESC
")->fetchAll();

include '../../layout/header.php';

?>

<div class="container-fluid">

<div class="card">

<div class="card-header bg-danger text-white">

Roles Management

</div>

<div class="card-body">

<a
href="create.php"
class="btn btn-danger mb-3">

Create Role

</a>

<table class="table table-bordered">

<tr>

<th>ID</th>
<th>Role Name</th>
<th>Status</th>
<th>Action</th>

</tr>

<?php foreach($list as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['role_name']); ?></td>

<td><?= $row['status']; ?></td>

<td>

<a
href="permissions.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

Permissions

</a>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

<?php include '../../layout/footer.php'; ?>