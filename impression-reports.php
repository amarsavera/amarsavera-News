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
| Impression Summary
|--------------------------------------------------------------------------
*/

$summary = $pdo->prepare("
SELECT

COUNT(*) total_impressions,

COUNT(DISTINCT ip_address) unique_visitors

FROM advertisement_impressions

WHERE DATE(viewed_at)
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
| Impression Logs
|--------------------------------------------------------------------------
*/

$logs = $pdo->prepare("
SELECT

ai.*,

a.campaign_name,

p.position_name

FROM advertisement_impressions ai

LEFT JOIN advertisements a
ON a.id=ai.campaign_id

LEFT JOIN advertisement_positions p
ON p.id=a.position_id

WHERE DATE(ai.viewed_at)
BETWEEN ? AND ?

ORDER BY ai.id DESC

LIMIT 500

");

$logs->execute([
$fromDate,
$toDate
]);

/*
|--------------------------------------------------------------------------
| Campaign Wise Impressions
|--------------------------------------------------------------------------
*/

$campaignStats = $pdo->query("
SELECT

a.campaign_name,

COUNT(ai.id) impressions

FROM advertisement_impressions ai

LEFT JOIN advertisements a
ON a.id=ai.campaign_id

GROUP BY ai.campaign_id

ORDER BY impressions DESC

LIMIT 20

")->fetchAll();

/*
|--------------------------------------------------------------------------
| Position Wise Impressions
|--------------------------------------------------------------------------
*/

$positionStats = $pdo->query("
SELECT

p.position_name,

COUNT(ai.id) impressions

FROM advertisement_impressions ai

LEFT JOIN advertisements a
ON a.id=ai.campaign_id

LEFT JOIN advertisement_positions p
ON p.id=a.position_id

GROUP BY p.id

ORDER BY impressions DESC

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Impression Reports

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
$summaryData['total_impressions']
); ?>

</h3>

<p>

Total Impressions

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

Impression Logs

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Campaign</th>
<th>Position</th>
<th>IP Address</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($logs as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td>
<?= htmlspecialchars(
$row['campaign_name']
); ?>
</td>

<td>
<?= htmlspecialchars(
$row['position_name']
); ?>
</td>

<td>
<?= htmlspecialchars(
$row['ip_address']
); ?>
</td>

<td>
<?= $row['viewed_at']; ?>
</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="row mt-4">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-info text-white">

Campaign Wise Impressions

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>Campaign</th>
<th>Impressions</th>

</tr>

</thead>

<tbody>

<?php foreach($campaignStats as $campaign): ?>

<tr>

<td>

<?= htmlspecialchars(
$campaign['campaign_name']
); ?>

</td>

<td>

<?= number_format(
$campaign['impressions']
); ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-warning text-dark">

Position Wise Impressions

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>Position</th>
<th>Impressions</th>

</tr>

</thead>

<tbody>

<?php foreach($positionStats as $position): ?>

<tr>

<td>

<?= htmlspecialchars(
$position['position_name']
); ?>

</td>

<td>

<?= number_format(
$position['impressions']
); ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<div class="mt-4">

<a
href="export-impression-excel.php"
class="btn btn-success">

Export Excel

</a>

<a
href="export-impression-pdf.php"
class="btn btn-danger">

Export PDF

</a>

</div>

</div>

<?php include '../layout/footer.php'; ?>