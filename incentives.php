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
| Bonus Calculation
|--------------------------------------------------------------------------
*/

$newsBonus = 0;
$adBonus = 0;
$collectionBonus = 0;
$attendanceBonus = 0;

if(($target['news_achieved'] ?? 0) >= ($target['news_target'] ?? 0))
{
    $newsBonus = 1000;
}

if(($target['advertisement_achieved'] ?? 0) >= ($target['advertisement_target'] ?? 0))
{
    $adBonus = 2500;
}

if(($target['collection_achieved'] ?? 0) >= ($target['collection_target'] ?? 0))
{
    $collectionBonus = 2000;
}

$attendance = $pdo->prepare("
SELECT
COUNT(*) total_days,
SUM(
CASE
WHEN status='Present'
THEN 1
ELSE 0
END
) present_days
FROM hrms_attendance
WHERE employee_code=?
AND MONTH(attendance_date)=?
AND YEAR(attendance_date)=?
");

$attendance->execute([
$employeeCode,
$currentMonth,
$currentYear
]);

$attendanceData =
$attendance->fetch();

$attendancePercent = 0;

if($attendanceData['total_days']>0)
{
    $attendancePercent =
    round(
    ($attendanceData['present_days']
    /
    $attendanceData['total_days'])
    *100,
    2
    );
}

if($attendancePercent >= 95)
{
    $attendanceBonus = 1000;
}

$totalIncentive =
$newsBonus +
$adBonus +
$collectionBonus +
$attendanceBonus;

/*
|--------------------------------------------------------------------------
| Incentive History
|--------------------------------------------------------------------------
*/

$history = $pdo->prepare("
SELECT *
FROM reporter_incentives
WHERE employee_code=?
ORDER BY id DESC
LIMIT 100
");

$history->execute([
$employeeCode
]);

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Incentive Dashboard

</h3>

<div class="row">

<div class="col-md-3">

<div class="card border-primary">

<div class="card-body text-center">

<h3>

₹<?= number_format($newsBonus); ?>

</h3>

<p>

News Bonus

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h3>

₹<?= number_format($adBonus); ?>

</h3>

<p>

Advertisement Bonus

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h3>

₹<?= number_format($collectionBonus); ?>

</h3>

<p>

Collection Bonus

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-danger">

<div class="card-body text-center">

<h3>

₹<?= number_format($attendanceBonus); ?>

</h3>

<p>

Attendance Bonus

</p>

</div>

</div>

</div>

</div>

<div class="card