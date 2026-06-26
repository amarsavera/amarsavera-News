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
| Audit Filters
|--------------------------------------------------------------------------
*/

$module =
$_GET['module']
?? '';

$adminId =
$_GET['admin_id']
?? '';

$query = "

SELECT

a.*,

ad.full_name

FROM audit_trail a

LEFT JOIN admins ad
ON ad.id=a.admin_id

WHERE 1=1

";

$params=[];

if($module!='')
{
    $query .= "
    AND a.module_name=?
    ";

    $params[]=$module;
}

if($adminId!='')
{
    $query .= "
    AND a.admin_id=?
    ";

    $params[]=$adminId;
}

$query .= "
ORDER BY a.id DESC
LIMIT 2000
";

$stmt=$pdo->prepare($query);
$stmt->execute($params);

$audits=$stmt->fetchAll();

$admins=$pdo->query("
SELECT
id,
full_name
FROM admins
ORDER BY full_name
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Enterprise Audit Trail

</h3>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Audit Filters

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

Admin User

</label>

<select
name="admin_id"
class="form-control">

<option value="">

All Admins

</option>

<?php foreach($admins as $admin): ?>

<option
value="<?= $admin['id']; ?>">

<?= htmlspecialchars(
$admin['full_name']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-4">

<label>&nbsp;</label>

<button
class="btn btn-success w-100">

Search Audit

</button>

</div>

</div>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Audit Records

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

<th>Old Value</th>

<th>New Value</th>

<th>IP</th>

<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($audits as $audit): ?>

<tr>

<td>

<?= $audit['id']; ?>

</td>

<td>

<?= htmlspecialchars(
$audit['full_name']
?? 'System'
); ?>

</td>

<td>

<?= ucfirst(
$audit['module_name']
); ?>

</td>

<td>

<span class="badge bg-primary">

<?= ucfirst(
$audit['action_name']
); ?>

</span>

</td>

<td>

<?= htmlspecialchars(
substr(
$audit['old_value'],
0,
80
)
); ?>

</td>

<td>

<?= htmlspecialchars(
substr(
$audit['new_value'],
0,
80
)
); ?>

</td>

<td>

<?= htmlspecialchars(
$audit['ip_address']
); ?>

</td>

<td>

<?= $audit['created_at']; ?>

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

Audit Coverage

</div>

<div class="card-body">

<ul>

<li>News Edit History</li>

<li>News Approval History</li>

<li>Advertisement Updates</li>

<li>Campaign Changes</li>

<li>HRMS Employee Changes</li>

<li>Attendance Modifications</li>

<li>Payroll Revisions</li>

<li>Target Updates</li>

</ul>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-danger text-white">

Compliance Features

</div>

<div class="card-body">

<ul>

<li>Immutable Audit Records</li>

<li>Forensic Investigation Ready</li>

<li>User Accountability Tracking</li>

<li>Role Change Monitoring</li>

<li>Permission Change Tracking</li>

<li>Backup & Restore Audit</li>

<li>API Activity Monitoring</li>

<li>Enterprise Compliance Reports</li>

</ul>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Security Chain

</div>

<div class="card-body">

<pre>
User Action
      ↓
Activity Log
      ↓
Audit Trail
      ↓
Immutable Storage
      ↓
Compliance Report
      ↓
Investigation Ready
</pre>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>