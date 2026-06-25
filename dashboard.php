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
| Advertisement Dashboard Stats
|--------------------------------------------------------------------------
*/

$totalClients = $pdo->query("
SELECT COUNT(*)
FROM advertisement_clients
")->fetchColumn();

$totalBookings = $pdo->query("
SELECT COUNT(*)
FROM advertisement_bookings
")->fetchColumn();

$totalRevenue = $pdo->query("
SELECT IFNULL(SUM(total_amount),0)
FROM advertisement_bookings
WHERE status='approved'
")->fetchColumn();

$pendingAds = $pdo->query("
SELECT COUNT(*)
FROM advertisement_bookings
WHERE status='pending'
")->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Dashboard

</h3>

<div class="row">

<div class="col-md-3">

<div class="card border-primary">

<div class="card-body text-center">

<h4>

<?= $totalClients; ?>

</h4>

<p>Total Clients</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h4>

<?= $totalBookings; ?>

</h4>

<p>Total Bookings</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

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

<div class="card border-danger">

<div class="card-body text-center">

<h4>

<?= $pendingAds; ?>

</h4>

<p>Pending Approval</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Advertisement Control Center

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 mb-2">
<a href="clients.php" class="btn btn-success w-100">
Clients
</a>
</div>

<div class="col-md-3 mb-2">
<a href="bookings.php" class="btn btn-primary w-100">
Bookings
</a>
</div>

<div class="col-md-3 mb-2">
<a href="billing.php" class="btn btn-warning w-100">
Billing
</a>
</div>

<div class="col-md-3 mb-2">
<a href="reports.php" class="btn btn-danger w-100">
Reports
</a>
</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Revenue Flow

</div>

<div class="card-body">

<pre>
Client
   ↓
Booking
   ↓
Approval
   ↓
Invoice
   ↓
Payment
   ↓
Collection
   ↓
Finance Ledger
</pre>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>