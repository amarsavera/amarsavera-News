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

$fromDate =
$_GET['from_date']
?? date('Y-m-01');

$toDate =
$_GET['to_date']
?? date('Y-m-d');

/*
|--------------------------------------------------------------------------
| Click Summary
|--------------------------------------------------------------------------
*/

$summary = $pdo->prepare("
SELECT

COUNT(*) total_clicks,

COUNT(DISTINCT ip_address) unique_visitors

FROM advertisement_clicks

WHERE DATE(clicked_at)
BETWEEN ? AND ?

");

$summary->execute([
$fromDate,
$toDate
]);

$summaryData =
$summary->fetch();

/*
|--------------------------------------------------------------------------
| Click Logs
|--------------------------------------------------------------------------
*/

$logs = $pdo->prepare("
SELECT

ac.*,

a.campaign_name

FROM advertisement_clicks ac

LEFT JOIN advertisements a
ON a.id=ac.campaign_id

WHERE DATE(ac.clicked_at)
BETWEEN ? AND ?

ORDER BY ac.id DESC

LIMIT 500

");

$logs->execute([
$fromDate,
$toDate
]);

/*
|--------------------------------------------------------------------------
| Fraud Detection
|--------------------------------------------------------------------------
*/

$fraudClicks = $pdo->query("
SELECT

ip_address,

COUNT(*) total_clicks

FROM advertisement_clicks

GROUP BY ip_address

HAVING COUNT(*) > 20

ORDER BY total_clicks DESC

LIMIT 20
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Click Reports

</h3>

<div class="card shadow mb-4">

<div class="card-header bg-primary text-white">

Filter Reports

</div>

<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-5">

<label>From Date</label>

<input
type="date"
name="from_date"
value="<?= $fromDate; ?>"
class="form-control">

</div>

<div class="col-md-5">

<label>To Date</label>

<input
type="date"
name="to_date"
value="<?= $toDate; ?>"
class="form-control">

</div>

<div class="col-md-2">

<label>&nbsp;</label>

<button
class="btn btn-success w-100">

Generate

</button>

</div>

</div>

</form>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="card border-success">

<div class="card-body text-center">

<h3>

<?= number_format(
$summaryData['total_clicks']
); ?>

</h3>

<p>

Total Clicks

</p>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card border-primary">

<div class="card-body text-center">

<h3>

<?= number_format(
$summaryData['unique_visitors']
); ?>

</h3>

<p>

Unique Visitors

</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Click Activity Logs

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Campaign</th>
<th>IP Address</th>
<th>User Agent</th>
<th>Date</th>

</tr>

</thead