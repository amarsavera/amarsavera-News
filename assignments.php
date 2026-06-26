<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}

$employeeCode =
$_SESSION['employee_code'] ?? '';

$status =
$_GET['status'] ?? '';

$sql = "
SELECT *
FROM reporter_assignments
WHERE employee_code=?
";

$params = [$employeeCode];

if(!empty($status))
{
    $sql .= " AND assignment_status=?";
    $params[] = $status;
}

$sql .= " ORDER BY deadline ASC,id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$assignments = $stmt->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="d-flex justify-content-between mb-3">

<h3>

My Assignments

</h3>

<div>

<a href="?status=pending"
class="btn btn-warning">

Pending

</a>

<a href="?status=completed"
class="btn btn-success">

Completed

</a>

<a href="assignments.php"
class="btn btn-dark">

All

</a>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Assignment List

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Assignment</th>
<th>Assigned By</th>
<th>Priority</th>
<th>Deadline</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($assignments as $row): ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<strong>

<?= htmlspecialchars(
$row['title']
); ?>

</strong>

<br>

<small>

<?= htmlspecialchars(
substr(
$row['description'],
0,
120
)
); ?>

...

</small>

</td>

<td>

<?= htmlspecialchars(
$row['assigned_by_name']
); ?>

</td>

<td>

<?php

if($row['priority']=='high')
{
echo '<span class="badge bg-danger">High</span>';
}
elseif($row['priority']=='medium')
{
echo '<span class="badge bg-warning">Medium</span>';
}
else
{
echo '<span class="badge bg-success">Normal</span>';
}

?>

</td>

<td>

<?= date(
'd-m-Y',
strtotime(
$row['deadline']
)
); ?>

</td>

<td>

<?php

if($row['assignment_status']=='completed')
{
echo '<span class="badge bg-success">Completed</span>';
}
elseif($row['assignment_status']=='in_progress')
{
echo '<span class="badge bg-primary">In Progress</span>';
}
else
{
echo '<span class="badge bg-warning">Pending</span>';
}

?>

</td>

<td>

<a
href="assignment-view.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

View

</a>

<?php if(
$row['assignment_status']!='completed'
): ?>

<a
href="assignment-complete.php?id=<?= $row['id']; ?>"
class="btn btn-success btn-sm">

Complete

</a>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

<?php if(empty($assignments)): ?>

<tr>

<td colspan="7"
class="text-center">

No Assignments Found

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Assignment Summary

</div>

<div class="card-body">

<?php

$total = $pdo->prepare("
SELECT COUNT(*)
FROM reporter_assignments
WHERE employee_code=?
");

$total->execute([
$employeeCode
]);

$completed = $pdo->prepare("
SELECT COUNT(*)
FROM reporter_assignments
WHERE employee_code=?
AND assignment_status='completed'
");

$completed->execute([
$employeeCode
]);

?>

<table class="table table-bordered">

<tr>

<th>Total Assignments</th>

<td>

<?= $total->fetchColumn(); ?>

</td>

</tr>

<tr>

<th>Completed</th>

<td>

<?= $completed->fetchColumn(); ?>

</td>

</tr>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>