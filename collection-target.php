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

$currentMonth = date('m');
$currentYear  = date('Y');

/*
|--------------------------------------------------------------------------
| Target Data
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT *
FROM hrms_targets
WHERE employee_code=?
AND target_month=?
AND target_year=?
LIMIT 1
");

$stmt->execute([

$employeeCode,
$currentMonth,
$currentYear

]);

$target = $stmt->fetch();

/*
|--------------------------------------------------------------------------
| Collection Summary
|--------------------------------------------------------------------------
*/

$collection = $pdo->prepare("
SELECT

COUNT(*) total_payments,

IFNULL(SUM(payment_amount),0) total_collection

FROM payment_transactions

WHERE executive_code=?
AND MONTH(payment_date)=?
AND YEAR(payment_date)=?

");

$collection->execute([

$employeeCode,
$currentMonth,
$currentYear

]);

$collectionData = $collection->fetch();

/*
|--------------------------------------------------------------------------
| Pending Recovery
|--------------------------------------------------------------------------
*/

$pending = $pdo->prepare("
SELECT
IFNULL(SUM(balance_amount),0)
FROM advertisement_bookings
WHERE executive_code=?
AND payment_status!='paid'
");

$pending->execute([
$employeeCode
]);

$pendingAmount =
$pending->fetchColumn();

$collectionTarget =
$target['collection_target'] ?? 0;

$collectionAchieved =
$target['collection_achieved'] ?? 0;

$achievement = 0;

if($collectionTarget>0)
{
    $achievement =
    round(
    ($collectionAchieved/$collectionTarget)*100,
    2
    );
}

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Collection Target Dashboard

</h3>

<div class="row">

<div class="col-md-3">

<div class="card border-primary">

<div class="card-body text-center">

<h2>

₹<?= number_format($collectionTarget); ?>

</h2>

<p>

Collection Target

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h2>

₹<?= number_format($collectionAchieved); ?>

</h2>

<p>

Recovered Amount

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h2>

₹<?= number_format($pendingAmount); ?>

</h2>

<p>

Pending Recovery

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-danger">

<div class="card-body text-center">

<h2>

<?= $achievement; ?>%

</h2>

<p>

Achievement %

</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Recovery Progress

</div>

<div class="card-body">

<div class="progress mb-3" style="height:30px;">

<div
class="progress-bar bg-success"
style="width:<?= min($achievement,100); ?>%;">

<?= $achievement; ?>%

</div>

</div>

<table class="table table-bordered">

<tr>

<th width="250">

Target Amount

</th>

<td>

₹<?= number_format($collectionTarget); ?>

</td>

</tr>

<tr>

<th>

Recovered Amount

</th>

<td>

₹<?= number_format($collectionAchieved); ?>

</td>

</tr>

<tr>

<th>

Pending Amount

</th>

<td>

₹<?= number_format(
max(
0,
$collectionTarget-$collectionAchieved
)
); ?>

</td>

</tr>

<tr>

<th>

Recovery Entries

</th>

<td>

<?= $collectionData['total_payments']; ?>

</td>

</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Client Wise Collection

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>Client</th>
<th>Booked</th>
<th>Collected</th>
<th>Balance</th>

</tr>

</thead>

<tbody>

<?php

$clients = $pdo->prepare("
SELECT

client_name,

SUM(total_amount) booked,

SUM(paid_amount) collected,

SUM(balance_amount) balance_due

FROM advertisement_bookings

WHERE executive_code=?

GROUP BY client_name

ORDER BY balance_due DESC

LIMIT 50
");

$clients->execute([
$employeeCode
]);

foreach($clients as $client):

?>

<tr>

<td>

<?= htmlspecialchars(
$client['client_name']
); ?>

</td>

<td>

₹<?= number_format(
$client['booked']
); ?>

</td>

<td>

₹<?= number_format(
$client['collected']
); ?>

</td>

<td>

₹<?= number_format(
$client['balance_due']
); ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-dark text-white">

Collection Ranking

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>Rank</th>
<th>Employee</th>
<th>Achievement %</th>

</tr>

</thead>

<tbody>

<?php

$ranking = $pdo->query("
SELECT
employee_name,
collection_achieved,
collection_target,
achievement_percentage
FROM hrms_targets
ORDER BY collection_achieved DESC
LIMIT 10
")->fetchAll();

$rank = 1;

foreach($ranking as $row):

?>

<tr>

<td><?= $rank++; ?></td>

<td><?= htmlspecialchars($row['employee_name']); ?></td>

<td><?= $row['achievement_percentage']; ?>%</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>