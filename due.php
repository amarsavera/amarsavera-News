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

$overdueClients = $pdo->query("
SELECT

ab.id,
ab.booking_number,
ab.client_name,
ab.executive_code,
ab.total_amount,
ab.paid_amount,
ab.balance_amount,
ab.publication_date,
DATEDIFF(CURDATE(),ab.publication_date) overdue_days

FROM advertisement_bookings ab

WHERE ab.balance_amount > 0

ORDER BY overdue_days DESC

")->fetchAll();

$totalDue = $pdo->query("
SELECT IFNULL(SUM(balance_amount),0)
FROM advertisement_bookings
WHERE balance_amount > 0
")->fetchColumn();

$totalClients = $pdo->query("
SELECT COUNT(DISTINCT client_name)
FROM advertisement_bookings
WHERE balance_amount > 0
")->fetchColumn();

$totalOverdue = $pdo->query("
SELECT COUNT(*)
FROM advertisement_bookings
WHERE balance_amount > 0
AND publication_date < CURDATE()
")->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Outstanding Dues Dashboard

</h3>

<div class="row">

<div class="col-md-4">

<div class="card border-danger">

<div class="card-body text-center">

<h3>

₹<?= number_format($totalDue,2); ?>

</h3>

<p>

Total Outstanding Due

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-warning">

<div class="card-body text-center">

<h3>

<?= $totalClients; ?>

</h3>

<p>

Clients Pending

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-primary">

<div class="card-body text-center">

<h3>

<?= $totalOverdue; ?>

</h3>

<p>

Overdue Bookings

</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-danger text-white">

Recovery Tracking

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Booking No</th>
<th>Client</th>
<th>Executive</th>
<th>Total</th>
<th>Paid</th>
<th>Balance</th>
<th>Overdue</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($overdueClients as $row): ?>

<tr>

<td>

<?= htmlspecialchars(
$row['booking_number']
); ?>

</td>

<td>

<?= htmlspecialchars(
$row['client_name']
); ?>

</td>

<td>

<?= htmlspecialchars(
$row['executive_code']
); ?>

</td>

<td>

₹<?= number_format(
$row['total_amount'],
2
); ?>

</td>

<td>

₹<?= number_format(
$row['paid_amount'],
2
); ?>

</td>

<td>

<span class="text-danger">

₹<?= number_format(
$row['balance_amount'],
2
); ?>

</span>

</td>

<td>

<?= $row['overdue_days']; ?>

Days

</td>

<td>

<a
href="payments.php?booking_id=<?= $row['id']; ?>"
class="btn btn-success btn-sm">

Collect

</a>

<a
href="followup.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

Follow Up

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Collection Executive Ranking

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>Executive</th>
<th>Total Collection</th>
<th>Pending Due</th>

</tr>

</thead>

<tbody>

<?php

$ranking = $pdo->query("
SELECT

executive_code,

SUM(paid_amount) total_collection,

SUM(balance_amount) total_due

FROM advertisement_bookings

GROUP BY executive_code

ORDER BY total_collection DESC

LIMIT 20

")->fetchAll();

foreach($ranking as $rank):

?>

<tr>

<td>

<?= htmlspecialchars(
$rank['executive_code']
); ?>

</td>

<td>

₹<?= number_format(
$rank['total_collection'],
2
); ?>

</td>

<td>

₹<?= number_format(
$rank['total_due'],
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

<?php include '../layout/footer.php'; ?>