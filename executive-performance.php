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

$currentMonth = date('m');
$currentYear  = date('Y');

$executives = $pdo->query("
SELECT

ab.executive_code,

COUNT(ab.id) total_bookings,

IFNULL(SUM(ab.total_amount),0) total_revenue,

IFNULL(SUM(ab.paid_amount),0) total_collection,

IFNULL(SUM(ab.balance_amount),0) total_due

FROM advertisement_bookings ab

GROUP BY ab.executive_code

ORDER BY total_revenue DESC

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Executive Performance Dashboard

</h3>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Sales Leaderboard

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Rank</th>
<th>Executive</th>
<th>Bookings</th>
<th>Revenue</th>
<th>Collection</th>
<th>Due</th>
<th>Achievement</th>

</tr>

</thead>

<tbody>

<?php

$rank = 1;

foreach($executives as $row):

$achievement = 0;

$target = $pdo->prepare("
SELECT advertisement_target,
advertisement_achieved
FROM hrms_targets
WHERE employee_code=?
AND target_month=?
AND target_year=?
LIMIT 1
");

$target->execute([

$row['executive_code'],
$currentMonth,
$currentYear

]);

$targetData = $target->fetch();

if(
!empty($targetData['advertisement_target'])
)
{
    $achievement =
    round(
    (
    $targetData['advertisement_achieved']
    /
    $targetData['advertisement_target']
    )*100,
    2
    );
}

?>

<tr>

<td>

<?= $rank++; ?>

</td>

<td>

<?= htmlspecialchars(
$row['executive_code']
); ?>

</td>

<td>

<?= $row['total_bookings']; ?>

</td>

<td>

₹<?= number_format(
$row['total_revenue'],
2
); ?>

</td>

<td>

₹<?= number_format(
$row['total_collection'],
2
); ?>

</td>

<td>

₹<?= number_format(
$row['total_due'],
2
); ?>

</td>

<td>

<span class="badge bg-success">

<?= $achievement; ?>%

</span>

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

Top Revenue Performers

</div>

<div class="card-body">

<?php

$topRevenue = array_slice(
$executives,
0,
5
);

?>

<table class="table table-bordered">

<thead>

<tr>

<th>Executive</th>
<th>Revenue</th>

</tr>

</thead>

<tbody>

<?php foreach($topRevenue as $row): ?>

<tr>

<td>

<?= htmlspecialchars(
$row['executive_code']
); ?>

</td>

<td>

₹<?= number_format(
$row['total_revenue'],
2
); ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Commission Summary

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>Executive</th>
<th>Revenue</th>
<th>Commission (5%)</th>

</tr>

</thead>

<tbody>

<?php foreach($executives as $row): ?>

<tr>

<td>

<?= htmlspecialchars(
$row['executive_code']
); ?>

</td>

<td>

₹<?= number_format(
$row['total_revenue'],
2
); ?>

</td>

<td>

₹<?= number_format(
($row['total_revenue']*5)/100,
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