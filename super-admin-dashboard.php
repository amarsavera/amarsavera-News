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

/*
|--------------------------------------------------------------------------
| Revenue Summary
|--------------------------------------------------------------------------
*/

$totalRevenue = $pdo->query("
SELECT
IFNULL(SUM(total_amount),0)
FROM advertisement_bookings
WHERE payment_status='paid'
")->fetchColumn();

$totalOutstanding = $pdo->query("
SELECT
IFNULL(SUM(total_amount),0)
FROM advertisement_bookings
WHERE payment_status!='paid'
OR payment_status IS NULL
")->fetchColumn();

$totalGST = $pdo->query("
SELECT
IFNULL(SUM(gst_amount),0)
FROM advertisement_bookings
WHERE payment_status='paid'
")->fetchColumn();

$pendingApprovals = $pdo->query("
SELECT COUNT(*)
FROM advertisement_bookings
WHERE status='pending'
")->fetchColumn();

$approvedBookings = $pdo->query("
SELECT COUNT(*)
FROM advertisement_bookings
WHERE status='approved'
")->fetchColumn();

$rejectedBookings = $pdo->query("
SELECT COUNT(*)
FROM advertisement_bookings
WHERE status='rejected'
")->fetchColumn();

/*
|--------------------------------------------------------------------------
| Top Advertisers
|--------------------------------------------------------------------------
*/

$topAdvertisers = $pdo->query("
SELECT

a.company_name,

SUM(ab.total_amount) AS revenue

FROM advertisement_bookings ab

LEFT JOIN advertisers a
ON a.id=ab.advertiser_id

WHERE ab.payment_status='paid'

GROUP BY ab.advertiser_id

ORDER BY revenue DESC

LIMIT 5
")->fetchAll();

/*
|--------------------------------------------------------------------------
| Latest Activity
|--------------------------------------------------------------------------
*/

$activities = $pdo->query("
SELECT *
FROM activity_logs
ORDER BY id DESC
LIMIT 10
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h2 class="mb-4">

Super Admin Revenue Dashboard

</h2>

<div class="row">

<div class="col-md-3 mb-3">

<div class="card border-success">

<div class="card-body text-center">

<h3>

₹<?= number_format($totalRevenue,2); ?>

</h3>

<p>

Total Revenue

</p>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card border-danger">

<div class="card-body text-center">

<h3>

₹<?= number_format($totalOutstanding,2); ?>

</h3>

<p>

Outstanding Amount

</p>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card border-primary">

<div class="card-body text-center">

<h3>

₹<?= number_format($totalGST,2); ?>

</h3>

<p>

GST Collection

</p>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card border-warning">

<div class="card-body text-center">

<h3>

<?= $pendingApprovals; ?>

</h3>

<p>

Pending Approvals

</p>

</div>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-success text-white">

Top Advertisers

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>Company</th>

<th>Revenue</th>

</tr>

<?php foreach($topAdvertisers as $row): ?>

<tr>

<td>

<?= htmlspecialchars($row['company_name']); ?>

</td>

<td>

₹<?= number_format($row['revenue'],2); ?>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-dark text-white">

Booking Status

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>Approved</th>

<td><?= $approvedBookings; ?></td>

</tr>

<tr>

<th>Rejected</th>

<td><?= $rejectedBookings; ?></td>

</tr>

<tr>

<th>Pending</th>

<td><?= $pendingApprovals; ?></td>

</tr>

</table>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Latest Activity Logs

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<tr>

<th>ID</th>

<th>Module</th>

<th>Action</th>

<th>Remarks</th>

<th>Date</th>

</tr>

<?php foreach($activities as $log): ?>

<tr>

<td><?= $log['id']; ?></td>

<td><?= htmlspecialchars($log['module_name']); ?></td>

<td><?= htmlspecialchars($log['action_name']); ?></td>

<td><?= htmlspecialchars($log['remarks']); ?></td>

<td><?= $log['created_at']; ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-danger text-white">

Super Admin Quick Controls

</div>

<div class="card-body">

<a href="../advertisement-bookings/index.php"
class="btn btn-primary">

Bookings

</a>

<a href="../advertisements/index.php"
class="btn btn-success">

Advertisements

</a>

<a href="../advertisers/index.php"
class="btn btn-warning">

Advertisers

</a>

<a href="../approval-logs/index.php"
class="btn btn-dark">

Approval Logs

</a>

<a href="../super-admin/override.php"
class="btn btn-danger">

Override Panel

</a>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>