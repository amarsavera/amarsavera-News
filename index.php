<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$tehsils = $pdo->query("
SELECT
t.*,
s.state_name,
dv.division_name,
d.district_name

FROM tehsils t

LEFT JOIN states s
ON s.id=t.state_id

LEFT JOIN divisions dv
ON dv.id=t.division_id

LEFT JOIN districts d
ON d.id=t.district_id

ORDER BY t.tehsil_name ASC
")->fetchAll();

include '../layout/header.php';
?>

<div class="container-fluid">

<div class="d-flex justify-content-between mb-3">

<h3>Tehsil Management</h3>

<a href="create.php" class="btn btn-success">
Add Tehsil
</a>

</div>

<div class="card shadow-sm">

<div class="card-body">

<table class="table table-bordered">

<thead class="table-dark">

<tr>
<th>ID</th>
<th>State</th>
<th>Division</th>
<th>District</th>
<th>Tehsil</th>
<th>Status</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php foreach($tehsils as $row): ?>

<tr>

<td><?= $row['id']; ?></td>
<td><?= htmlspecialchars($row['state_name']); ?></td>
<td><?= htmlspecialchars($row['division_name']); ?></td>
<td><?= htmlspecialchars($row['district_name']); ?></td>
<td><?= htmlspecialchars($row['tehsil_name']); ?></td>

<td>

<?= $row['status']=='active'
? '<span class="badge bg-success">Active</span>'
: '<span class="badge bg-danger">Inactive</span>'; ?>

</td>

<td>

<a href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

Edit

</a>

<a href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete Tehsil?');">

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

<?php include '../layout/footer.php'; ?>