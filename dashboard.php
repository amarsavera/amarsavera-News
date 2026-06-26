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
| Recruitment Dashboard Statistics
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

$totalSelected = $pdo->query("
SELECT COUNT(*)
FROM recruitment_applications
WHERE application_status='selected'
")->fetchColumn();

$totalTraining = $pdo->query("
SELECT COUNT(*)
FROM recruitment_training
")->fetchColumn();

$totalJoined = $pdo->query("
SELECT COUNT(*)
FROM employees
")->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Recruitment Management Dashboard

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

<h4><?= number_format($totalSelected); ?></h4>

<p>Selected</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-dark">

<div class="card-body text-center">

<h4><?= number_format($totalTraining); ?></h4>

<p>Training</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-danger">

<div class="card-body text-center">

<h4><?= number_format($totalJoined); ?></h4>

<p>Joined</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Recruitment Control Center

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 mb-2">

<a href="vacancies.php"
class="btn btn-primary w-100">

Vacancies

</a>

</div>

<div class="col-md-3 mb-2">

<a href="applications.php"
class="btn btn-warning w-100">

Applications

</a>

</div>

<div class="col-md-3 mb-2">

<a href="interviews.php"
class="btn btn-info w-100">

Interviews

</a>

</div>

<div class="col-md-3 mb-2">

<a href="joining.php"
class="btn btn-success w-100">

Joining

</a>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Recruitment Workflow

</div>

<div class="card-body">

<pre>
Application Received
         ↓
Screening
         ↓
Interview
         ↓
Selection
         ↓
7 Days Training
         ↓
ID Card & Authority Letter
         ↓
Joining
         ↓
Active Reporter
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Recruitment Features

</div>

<div class="card-body">

<ul>

<li>Journalist Recruitment</li>

<li>District Reporter Recruitment</li>

<li>Bureau Chief Recruitment</li>

<li>Photographer Recruitment</li>

<li>Interview Scheduling</li>

<li>Training Management</li>

<li>ID Card Generation</li>

<li>Authority Letter Generation</li>

<li>Official Email Creation</li>

<li>HRMS Integration</li>

<li>Employee ID Generation</li>

<li>Performance Tracking</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>