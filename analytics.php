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
| Overall Analytics
|--------------------------------------------------------------------------
*/

$summary = $pdo->prepare("
SELECT

COUNT(*) campaigns,

IFNULL(SUM(impressions),0) impressions,

IFNULL(SUM(clicks),0) clicks,

IFNULL(SUM(budget),0) revenue

FROM advertisements

WHERE DATE(created_at)
BETWEEN ? AND ?

");

$summary->execute([
$fromDate,
$toDate
]);

$data = $summary->fetch();

$ctr = 0;

if($data['impressions']>0)
{
    $ctr = round(
    ($data['clicks']
    /
    $data['impressions'])
    *100,
    2
    );
}

$rpm = 0;

if($data['impressions']>0)
{
    $rpm = round(
    (
    $data['revenue']
    /
    $data['impressions']
    )*1000,
    2
    );
}

$cpc = 0;

if($data['clicks']>0)
{
    $cpc = round(
    $data['revenue']
    /
    $data['clicks'],
    2
    );
}

/*
|--------------------------------------------------------------------------
| Top Campaigns
|--------------------------------------------------------------------------
*/

$campaigns = $pdo->query("
SELECT

campaign_name,
impressions,
clicks,
budget

FROM advertisements

ORDER BY impressions DESC

LIMIT 20
")->fetchAll();

/*
|--------------------------------------------------------------------------
| Position Analytics
|--------------------------------------------------------------------------
*/

$positions = $pdo->query("
SELECT

p.position_name,

COUNT(a.id) campaigns,

SUM(a.impressions) impressions,

SUM(a.clicks) clicks

FROM advertisements a

LEFT JOIN advertisement_positions p
ON p.id=a.position_id

GROUP BY p.id

ORDER BY impressions DESC

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Analytics

</h3>

<div class="row">

<div class="col-md-3">

<div class="card border-primary">

<div class="card-body text-center">

<h3>

<?= number_format(
$data['impressions']
); ?>

</h3>

<p>

Impressions

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h3>

<?= number_format(
$data['clicks']
); ?>

</h3>

<p>

Clicks

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h3>

<?= $ctr; ?>%

</h3>

<p>

CTR

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-danger">

<div class="card-body text-center">

<h3>

₹<?= number_format(
$data['revenue']
);
?>

</h3>

<p>

Revenue

</p>

</div>

</div>

</div>

</div>

<div class="row mt-4">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-success text-white">

Revenue Metrics

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>

RPM

</th>

<td>

₹<?= $rpm; ?>

</td>

</tr>

<tr>

<th>

CPC

</th>

<td>

₹<?= $cpc; ?>

</td>

</tr>

<tr>

<th>

Campaigns

</th>

<td>

<?= $data['campaigns']; ?>

</td>

</tr>

</table>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Top Ad Positions

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

<?php foreach($positions as $row): ?>

<tr>

<td>

<?= htmlspecialchars(
$row['position_name']
); ?>

</td>

<td>

<?= number_format(
$row['impressions']
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

<div class="card shadow mt-4">

<div class="card-header bg-dark text-white">

Top Campaign Performance

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Campaign</th>
<th>Impressions</th>
<th>Clicks</th>
<th>CTR</th>
<th>Budget</th>

</tr>

</thead>

<tbody>

<?php foreach($campaigns as $campaign):

$campaignCTR=0;

if($campaign['impressions']>0)
{
    $campaignCTR=
    round(
    (
    $campaign['clicks']
    /
    $campaign['impressions']
    )*100,
    2
    );
}

?>

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

<td>

<?= number_format(
$campaign['clicks']
); ?>

</td>

<td>

<?= $campaignCTR; ?>%

</td>

<td>

₹<?= number_format(
$campaign['budget']
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

<?php include '../layout/footer.php'; ?>