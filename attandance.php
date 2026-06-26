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

$attendance = $pdo->prepare("
SELECT *
FROM hrms_attendance
WHERE employee_code=?
AND MONTH(attendance_date)=?
AND YEAR(attendance_date)=?
ORDER BY attendance_date DESC
");

$attendance->execute([

$employeeCode,
$currentMonth,
$currentYear

]);

$records =
$attendance->fetchAll();

$presentDays = 0;
$absentDays  = 0;
$lateMarks   = 0;

foreach($records as $row)
{

    if(
    strtolower($row['status'])=='present'
    )
    {
        $presentDays++;
    }

    if(
    strtolower($row['status'])=='absent'
    )
    {
        $absentDays++;
    }

    if(
    strtolower($row['late_mark'])=='yes'
    )
    {
        $lateMarks++;
    }

}

$totalDays =
$presentDays + $absentDays;

$attendancePercent = 0;

if($totalDays>0)
{
    $attendancePercent =
    round(
    ($presentDays/$totalDays)*100,
    2
    );
}

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Attendance Dashboard

</h3>

<div class="row">

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h2>

<?= $presentDays; ?>

</h2>

<p>

Present Days

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-danger">

<div class="card-body text-center">

<h2>

<?= $absentDays; ?>

</h2>

<p>

Absent Days

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h2>

<?= $lateMarks; ?>

</h2>

<p>

Late Marks

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-primary">

<div class="card-body text-center">

<h2>

<?= $attendancePercent; ?>%

</h2>

<p>

Attendance %

</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Attendance Performance

</div>

<div class="card-body">

<div
class="progress mb-3"
style="height:30px;">

<div
class="progress-bar bg-success"
style="width:<?= $attendancePercent; ?>%;">

<?= $attendancePercent; ?>%

</div>

</div>

<table class="table table-bordered">

<tr>

<th>

Present Days

</th>

<td>

<?= $presentDays; ?>

</td>

</tr>

<tr>

<th>

Absent Days

</th>

<td>

<?= $absentDays; ?>

</td>

</tr>

<tr>

<th>

Late Marks

</th>

<td>

<?= $lateMarks; ?>

</td>

</tr>

<tr>

<th>

Attendance Score

</th>

<td>

<?= $attendancePercent; ?>%

</td>

</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Monthly Attendance Log

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Date</th>
<th>Status</th>
<th>Check In</th>
<th>Check Out</th>
<th>Hours</th>
<th>Late</th>

</tr>

</thead>

<tbody>

<?php foreach($records as $row): ?>

<tr>

<td>

<?= date(
'd-m-Y',
strtotime(
$row['attendance_date']
)
); ?>

</td>

<td>

<?php if(
strtolower($row['status'])=='present'
): ?>

<span class="badge bg-success">

Present

</span>

<?php else: ?>

<span class="badge bg-danger">

Absent

</span>

<?php endif; ?>

</td>

<td>

<?= $row['check_in']; ?>

</td>

<td>

<?= $row['check_out']; ?>

</td>

<td>

<?= $row['working_hours']; ?>

</td>

<td>

<?= $row['late_mark']; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>