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
| Recruitment Reports Dashboard
|--------------------------------------------------------------------------
*/

$totalVacancies = $pdo->query("
SELECT COUNT(*)
FROM recruitment_vacancies
")->fetchColumn();

$totalApplications = $pdo->query("
SELECT COUNT(*)
FROM recruitment_applications
")->fetchColumn();

$totalInterviews = $pdo->query("
SELECT COUNT(*)
FROM recruitment_interviews
")->fetchColumn();

$totalTraining = $pdo->query("
SELECT COUNT(*)
FROM recruitment_training
")->fetchColumn();

$totalEmployees = $pdo->query("
SELECT COUNT(*)
FROM employees
")->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Recruitment Reports & Analytics

</h3>

<div class="row">

<div class="col-md-2">

<div class="card border-primary">

<div class="card-body text-center">

<h4><?= number_format($totalVacancies); ?></h4>

<p>Vacancies</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-warning">

<div class="card-body text-center">

<h4><?= number_format($totalApplications); ?></h4>

<p>Applications</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-info">

<div class="card-body text-center">

<h4><?= number_format($totalInterviews); ?></h4>

<p>Interviews</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-success">

<div class="card-body text-center">

<h4><?= number_format($totalTraining); ?></h4>

<p>Training</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-danger">

<div class="card-body text-center">

<h4><?= number_format($totalEmployees); ?></h4>

<p>Joined</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Recruitment Summary

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Total Vacancies</th>
<td><?= number_format($totalVacancies); ?></td>
</tr>

<tr>
<th>Total Applications</th>
<td><?= number_format($totalApplications); ?></td>
</tr>

<tr>
<th>Total Interviews</th>
<td><?= number_format($totalInterviews); ?></td>
</tr>

<tr>
<th>Total Training Candidates</th>
<td><?= number_format($totalTraining); ?></td>
</tr>

<tr>
<th>Total Active Employees</th>
<td><?= number_format($totalEmployees); ?></td>
</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Available Reports

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 mb-2">

<a href="vacancy-report.php"
class="btn btn-primary w-100">

Vacancy Reports

</a>

</div>

<div class="col-md-3 mb-2">

<a href="application-report.php"
class="btn btn-warning w-100">

Application Reports

</a>

</div>

<div class="col-md-3 mb-2">

<a href="training-report.php"
class="btn btn-success w-100">

Training Reports

</a>

</div>

<div class="col-md-3 mb-2">

<a href="joining-report.php"
class="btn btn-danger w-100">

Joining Reports

</a>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Recruitment Reports Workflow

</div>

<div class="card-body">

<pre>
Vacancies
      ↓
Applications
      ↓
Interviews
      ↓
Training
      ↓
Joining
      ↓
Reports & Analytics
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Report Features

</div>

<div class="card-body">

<ul>

<li>Vacancy Reports</li>

<li>Application Reports</li>

<li>Interview Reports</li>

<li>Training Reports</li>

<li>Joining Reports</li>

<li>Reporter Recruitment Reports</li>

<li>District Wise Recruitment Reports</li>

<li>Monthly Reports</li>

<li>Yearly Reports</li>

<li>PDF Export</li>

<li>Excel Export</li>

<li>Analytics Dashboard</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>