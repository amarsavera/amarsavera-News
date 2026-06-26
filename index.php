<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$states = $pdo->query("
SELECT *
FROM states
ORDER BY state_name ASC
")->fetchAll();

include '../layout/header.php';
?>

<div class="container-fluid">

<div class="d-flex justify-content-between mb-3">

<h3>State Management</h3>

<a href="create.php" class="btn btn-success">
    <i class="fa fa-plus"></i> Add State
</a>

</div>

<div class="card shadow-sm">

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>
    <th>ID</th>
    <th>State Name</th>
    <th>State Code</th>
    <th>Status</th>
    <th>Action</th>
</tr>

</thead>

<tbody>

<?php foreach($states as $state): ?>

<tr>

<td><?= $state['id']; ?></td>

<td><?= htmlspecialchars($state['state_name']); ?></td>

<td><?= htmlspecialchars($state['state_code']); ?></td>

<td>

<?php if($state['status']=='active'): ?>

<span class="badge bg-success">
Active
</span>

<?php else: ?>

<span class="badge bg-danger">
Inactive
</span>

<?php endif; ?>

</td>

<td>

<a href="edit.php?id=<?= $state['id']; ?>"
class="btn btn-primary btn-sm">

Edit

</a>

<a href="delete.php?id=<?= $state['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete State?');">

Delete

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>