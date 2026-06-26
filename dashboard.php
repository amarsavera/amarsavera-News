<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php');
    exit;
}

$employeeCode =
$_SESSION['employee_code'] ?? '';

$month = date('m');
$year  = date('Y');

/*
|--------------------------------------------------------------------------
| News Target
|--------------------------------------------------------------------------
*/

$target = $pdo->prepare("
SELECT *
FROM hrms_targets
WHERE employee_code=?
AND target_month=?
AND target_year=?
LIMIT 1
");

$target->execute([
$employeeCode,
$month,
$year
]);

$targetData =
$target->fetch();

/*
|--------------------------------------------------------------------------
| News Count
|--------------------------------------------------------------------------
*/

$newsCount = $pdo->prepare("
SELECT COUNT(*)
FROM news
WHERE reporter_code=?
AND MONTH(created_at)=?
AND YEAR(created_at)=?
");

$newsCount->execute([

$employeeCode,
$month,
$year

]);

$totalNews =
$newsCount->fetchColumn();

/*
|--------------------------------------------------------------------------
| Advertisement Revenue
|--------------------------------------------------------------------------
*/

$adsRevenue = $pdo->prepare("
SELECT IFNULL(SUM(total_amount),0)
FROM advertisement_bookings
WHERE executive_code=?
AND MONTH(created_at)=?
AND YEAR(created_at)=?
");

$adsRevenue->execute([

$employeeCode,
$month,
$year

]);

$totalAdsRevenue =
$adsRevenue->fetchColumn();

/*
|--------------------------------------------------------------------------
| Collections
|--------------------------------------------------------------------------
*/

$collection = $pdo->prepare("
SELECT IFNULL(SUM(payment_amount),0)
FROM payment_transactions
WHERE executive_code=?
AND MONTH(payment_date)=?
AND YEAR(payment_date)=?
");

$collection->execute([

$employeeCode,
$month,
$year

]);

$totalCollection =
$collection->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Reporter Dashboard

</h3>

<div class="row">

<div class="col-md-3">

<div class="card border-primary">

<div class="card-body text-center">

<h2>

<?= $totalNews; ?>

</h2>

<p>

News Published

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h2>

₹<?= number_format($totalAdsRevenue); ?>

</h2>

<p>

Advertisement Revenue

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h2>

₹<?= number_format($totalCollection); ?>

</h2>

<p>

Collections

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-danger">

<div class="card-body text-center">

<h2>

<?= $targetData['achievement_percentage'] ?? 0; ?>%

</h2>

<p>

Overall Achievement

</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Current Month Target

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>News Target</th>

<td>

<?= $targetData['news_target'] ?? 0; ?>

</td>

<th>Achieved</th>

<td>

<?= $targetData['news_achieved'] ?? 0; ?>

</td>

</tr>

<tr>

<th>Advertisement Target</th>

<td>

<?= $targetData['advertisement_target'] ?? 0; ?>

</td>

<th>Achieved</th>

<td>

<?= $targetData['advertisement_achieved'] ?? 0; ?>

</td>

</tr>

<tr>

<th>Collection Target</th>

<td>

<?= $targetData['collection_target'] ?? 0; ?>

</td>

<th>Achieved</th>

<td>

<?= $targetData['collection_achieved'] ?? 0; ?>

</td>

</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-dark text-white">

Quick Actions

</div>

<div class="card-body">

<a
href="assignments.php"
class="btn btn-primary">

Assignments

</a>

<a
href="my-news.php"
class="btn btn-success">

My News

</a>

<a
href="performance.php"
class="btn btn-warning">

Performance

</a>

<a
href="wallet.php"
class="btn btn-info">

Wallet

</a>

<a
href="incentives.php"
class="btn btn-dark">

Incentives

</a>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>