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
| Advertisement Reports Summary
|--------------------------------------------------------------------------
*/

$totalBookings = $pdo->query("
SELECT COUNT(*)
FROM advertisement_bookings
")->fetchColumn();

$totalRevenue = $pdo->query("
SELECT IFNULL(SUM(total_amount),0)
FROM advertisement_bookings
WHERE status='approved'
")->fetchColumn();

$totalCollections = $pdo->query("
SELECT IFNULL(SUM(amount),0)
FROM finance_payments
")->fetchColumn();

$totalClients = $pdo->query("
SELECT COUNT(*)
FROM advertisement_clients
")->fetchColumn();

$topClients = $pdo->query("
SELECT

c.company_name,

COUNT(b.id) as total_bookings,

IFNULL(SUM(b.total_amount),0) as revenue

FROM advertisement_clients c

LEFT JOIN advertisement_bookings b
ON b.client_id=c.id

GROUP BY c.id

ORDER BY revenue DESC

LIMIT 10

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Reports & Analytics

</h3>

<div class="row">

<div class="col-md-3">

<div class="card border-primary">

<div class="card-body text-center">

<h4><?= $totalBookings; ?></h4>

<p>Total Bookings</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h4>

₹<?= number_format(
$totalRevenue
); ?>

</h4>

<p>Total Revenue</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h4>

₹<?= number_format(
$totalCollections
); ?>

</h4>

<p>Total Collections</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-info">

<div class="card-body text-center">

<h4>

<?= $totalClients; ?>

</h4>

<p>Total Clients</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Top Revenue Clients

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Client Name</th>
<th>Total Bookings</th>
<th>Total Revenue</th>

</tr>

</thead>

<tbody>

<?php foreach($topClients as $client): ?>

<tr>

<td>

<?= htmlspecialchars(
$client['company_name']
); ?>

</td>

<td>

<?= $client['total_bookings']; ?>

</td>

<td>

₹<?= number_format(
$client['revenue'],
2
); ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Available Reports

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 mb-2">

<a
href="revenue-report.php"
class="btn btn-success w-100">

Revenue Report

</a>

</div>

<div class="col-md-3 mb-2">

<a
href="client-report.php"
class="btn btn-primary w-100">

Client Report

</a>

</div>

<div class="col-md-3 mb-2">

<a
href="collection-report.php"
class="btn btn-warning w-100">

Collection Report

</a>

</div>

<div class="col-md-3 mb-2">

<a
href="commission-report.php"
class="btn btn-danger w-100">

Commission Report

</a>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Analytics Workflow

</div>

<div class="card-body">

<pre>
Advertisement Booking
          ↓
Revenue Tracking
          ↓
Collection Tracking
          ↓
Commission Tracking
          ↓
Client Analysis
          ↓
Performance Reports
          ↓
Management Dashboard
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Export Options

</div>

<div class="card-body">

<ul>

<li>PDF Reports</li>

<li>Excel Export</li>

<li>Monthly Revenue Reports</li>

<li>Quarterly Reports</li>

<li>Annual Revenue Reports</li>

<li>District Wise Revenue Analysis</li>

<li>State Wise Revenue Analysis</li>

<li>Executive Performance Reports</li>

<li>Collection Efficiency Reports</li>

<li>Commission Analysis Reports</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>