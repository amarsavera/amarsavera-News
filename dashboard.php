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

$totalAdmins = $pdo->query("
SELECT COUNT(*)
FROM admins
")->fetchColumn();

$totalEmployees = $pdo->query("
SELECT COUNT(*)
FROM employees
")->fetchColumn();

$totalNews = $pdo->query("
SELECT COUNT(*)
FROM news
")->fetchColumn();

$totalRevenue = $pdo->query("
SELECT IFNULL(SUM(total_amount),0)
FROM advertisement_bookings
")->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Super Admin Dashboard

</h3>

<div class="row">

<div class="col-md-3">

<div class="card border-primary">

<div class="card-body text-center">

<h2><?= $totalAdmins; ?></h2>

<p>Total Admins</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h2><?= $totalEmployees; ?></h2>

<p>Total Employees</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h2><?= $totalNews; ?></h2>

<p>Total News</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-danger">

<div class="card-body text-center">

<h2>

₹<?= number_format($totalRevenue); ?>

</h2>

<p>Total Revenue</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-dark text-white">

Master Control Panel

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 mb-2">

<a
href="roles.php"
class="btn btn-primary w-100">

Roles

</a>

</div>

<div class="col-md-3 mb-2">

<a
href="permissions.php"
class="btn btn-success w-100">

Permissions

</a>

</div>

<div class="col-md-3 mb-2">

<a
href="admins.php"
class="btn btn-warning w-100">

Admins

</a>

</div>

<div class="col-md-3 mb-2">

<a
href="activity-logs.php"
class="btn btn-danger w-100">

Activity Logs

</a>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

System Status

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th width="300">News Portal</th>
<td>
<span class="badge bg-success">
Running
</span>
</td>
</tr>

<tr>
<th>Advertisement System</th>
<td>
<span class="badge bg-success">
Running
</span>
</td>
</tr>

<tr>
<th>HRMS Integration</th>
<td>
<span class="badge bg-success">
Connected
</span>
</td>
</tr>

<tr>
<th>Email Server</th>
<td>
<span class="badge bg-success">
Active
</span>
</td>
</tr>

<tr>
<th>API Gateway</th>
<td>
<span class="badge bg-success">
Healthy
</span>
</td>
</tr>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>