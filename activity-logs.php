<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(
!isset($_SESSION['admin_id'])
||
$_SESSION['role']!='super_admin'
)
{
    die('Access Denied');
}

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

$module =
$_GET['module']
?? '';

$action =
$_GET['action']
?? '';

$query = "

SELECT

l.*,

a.full_name

FROM activity_logs l

LEFT JOIN admins a
ON a.id=l.admin_id

WHERE 1=1

";

$params=[];

if($module!='')
{
    $query .= "
    AND l.module_name=?
    ";

    $params[]=$module;
}

if($action!='')
{
    $query .= "
    AND l.action_type=?
    ";

    $params[]=$action;
}

$query .= "
ORDER BY l.id DESC
LIMIT 1000
";

$stmt =
$pdo->prepare($query);

$stmt->execute($params);

$logs =
$stmt->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Activity Logs & Audit Trail

</h3>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Filter Logs

</div>

<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-4">

<label>

Module

</label>

<select
name="module"
class="form-control">

<option value="">

All Modules

</option>

<option value="news">

News

</option>

<option value="advertisement">

Advertisement

</option>

<option value="hrms">

HRMS

</option>

<option value="finance">

Finance

</option>

<option value="super-admin">

Super Admin

</option>

</select>

</div>

<div class="col-md-4">

<label>

Action

</label>

<select
name="action"
class="form-control">

<option value="">

All Actions

</option>

<option value="create">
Create
</option>

<option value="update">
Update
</option>

<option value="delete">
Delete
</option>

<option value="approve">
Approve
</option>

<option value="login">
Login
</option>

<option value="logout">
Logout
</option>

</select>

</div>

<div class="col-md-4">

<label>&nbsp;</label>

<button
class="btn btn-success w-100">

Search Logs

</button>

</div>

</div>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

System Activity Logs

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>User</th>

<th>Module</th>

<th>Action</th>

<th>Description</th>

<th>IP Address</th>

<th>Date Time</th>

</tr>

</thead>

<tbody>

<?php foreach($logs as $log): ?>

<tr>

<td>

<?= $log['id']; ?>

</td>

<td>

<?= htmlspecialchars(
$log['full_name']
?? 'System'
); ?>

</td>

<td>

<?= ucfirst(
$log['module_name']
); ?>

</td>

<td>

<span class="badge bg-primary">

<?= ucfirst(
$log['action_type']
); ?>

</span>

</td>

<td>

<?= htmlspecialchars(
$log['description']
); ?>

</td>

<td>

<?= htmlspecialchars(
$log['ip_address']
); ?>

</td>

<td>

<?= $log['created_at']; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="row mt-4">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-warning text-dark">

Security Monitoring

</div>

<div class="card-body">

<ul>

<li>Admin Login Tracking</li>

<li>Failed Login Attempts</li>

<li>Password Change Logs</li>

<li>Permission Change Logs</li>

<li>Role Change Logs</li>

<li>API Access Logs</li>

<li>IP Address Tracking</li>

<li>Device Fingerprint Ready</li>

</ul>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-danger text-white">

Audit Trail Coverage

</div>

<div class="card-body">

<ul>

<li>News Publish Logs</li>

<li>Advertisement Changes</li>

<li>Payroll Updates</li>

<li>Attendance Changes</li>

<li>Employee Modifications</li>

<li>Finance Transactions</li>

<li>System Settings Changes</li>

<li>Backup Activity Logs</li>

</ul>

</div>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>