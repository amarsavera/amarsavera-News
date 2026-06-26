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
| Advertisement Revenue
|--------------------------------------------------------------------------
*/

$ads = $pdo->prepare("
SELECT

COUNT(*) total_ads,

IFNULL(SUM(total_amount),0) revenue

FROM advertisement_bookings

WHERE executive_code=?
AND MONTH(created_at)=?
AND YEAR(created_at)=?

");

$ads->execute([

$employeeCode,
$currentMonth,
$currentYear

]);

$adsData = $ads->fetch();

/*
|--------------------------------------------------------------------------
| Collection Revenue
|--------------------------------------------------------------------------
*/

$collection = $pdo->prepare("
SELECT
IFNULL(SUM(payment_amount),0)
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

$totalCollection =
$collection->fetchColumn();

$adTarget =
$target['advertisement_target'] ?? 0;

$adAchieved =
$target['advertisement_achieved'] ?? 0;

$achievement = 0;

if($adTarget>0)
{
    $achievement =
    round(
    ($adAchieved/$adTarget)*100,
    2
    );
}

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Target Dashboard

</h3>

<div class="row">

<div class="col-md-3">

<div class="card border-primary">

<div class="card-body text-center">

<h2>

<?= $adTarget; ?>

</h2>

<p>

Monthly Target

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h2>

<?= $adAchieved; ?>

</h2>

<p>

Achieved

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h2>

₹<?= number_format(
$adsData['revenue']
); ?>

</h2>

<p>

Revenue Generated

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

Target Progress

</div>

<div class="card-body">

<div
class="progress"
style="height:30px;">

<div
class="progress-bar bg-success"
style="width:<?= min($achievement,100); ?>%;">

<?= $achievement; ?>%

</div>

</div>

<br>

<table class="table table-bordered">

<tr>

<th width="250">

Target

</th>

<td>

<?= $adTarget; ?>

</td>

</tr>

<tr>

<th>

Achieved

</th>

<td>

<?= $adAchieved; ?>

</td>

</tr>

<tr>

<th>

Remaining

</th>

<td>

<?= max(
0,
$adTarget-$adAchieved
); ?>

</td>

</tr>

<tr>

<th>

Advertisements Booked

</th>

<td>

<?= $adsData['total_ads']; ?>

</td>

</tr>

<tr>

<th>

Collection Received

</th>

<td>

₹<?= number_format(
$totalCollection
); ?>

</td>

</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Top Advertisement Performers

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
achievement_percentage

FROM hrms_targets

ORDER BY achievement_percentage DESC

LIMIT 10
")->fetchAll();

$rank = 1;

foreach($ranking as $row):

?>

<tr>

<td>

<?= $rank++; ?>

</td>

<td>

<?= htmlspecialchars(
$row['employee_name']
); ?>

</td>

<td>

<?= $row['achievement_percentage']; ?>%

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Incentive Eligibility

</div>

<div class="card-body">

<?php

if($achievement>=100)
{
echo '<div class="alert alert-success">
Eligible For Full Incentive
</div>';
}
elseif($achievement>=75)
{
echo '<div class="alert alert-warning">
Eligible For Partial Incentive
</div>';
}
else
{
echo '<div class="alert alert-danger">
Not Eligible Yet
</div>';
}

?>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>