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

$totalChannels = $pdo->query("
SELECT COUNT(*)
FROM telegram_channels
")->fetchColumn();

$totalGroups = $pdo->query("
SELECT COUNT(*)
FROM telegram_groups
")->fetchColumn();

$totalSubscribers = $pdo->query("
SELECT COUNT(*)
FROM telegram_subscribers
")->fetchColumn();

$totalBroadcasts = $pdo->query("
SELECT COUNT(*)
FROM telegram_broadcasts
")->fetchColumn();

$totalCampaigns = $pdo->query("
SELECT COUNT(*)
FROM telegram_campaigns
")->fetchColumn();

$totalAutomations = $pdo->query("
SELECT COUNT(*)
FROM telegram_automation
")->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Telegram Reports & Analytics

</h3>

<div class="row">

<div class="col-md-2">

<div class="card border-primary">

<div class="card-body text-center">

<h4><?= number_format($totalChannels); ?></h4>

<p>Channels</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-success">

<div class="card-body text-center">

<h4><?= number_format($totalGroups); ?></h4>

<p>Groups</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-warning">

<div class="card-body text-center">

<h4><?= number_format($totalSubscribers); ?></h4>

<p>Subscribers</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-danger">

<div class="card-body text-center">

<h4><?= number_format($totalBroadcasts); ?></h4>

<p>Broadcasts</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-info">

<div class="card-body text-center">

<h4><?= number_format($totalCampaigns); ?></h4>

<p>Campaigns</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-dark">

<div class="card-body text-center">

<h4><?= number_format($totalAutomations); ?></h4>

<p>Automation</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Telegram Summary Report

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Total Channels</th>
<td><?= number_format($totalChannels); ?></td>
</tr>

<tr>
<th>Total Groups</th>
<td><?= number_format($totalGroups); ?></td>
</tr>

<tr>
<th>Total Subscribers</th>
<td><?= number_format($totalSubscribers); ?></td>
</tr>

<tr>
<th>Total Broadcasts</th>
<td><?= number_format($totalBroadcasts); ?></td>
</tr>

<tr>
<th>Total Campaigns</th>
<td><?= number_format($totalCampaigns); ?></td>
</tr>

<tr>
<th>Total Automations</th>
<td><?= number_format($totalAutomations); ?></td>
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

<div class="col-md-2 mb-2">

<a href="channel-report.php"
class="btn btn-primary w-100">

Channels

</a>

</div>

<div class="col-md-2 mb-2">

<a href="group-report.php"
class="btn btn-success w-100">

Groups

</a>

</div>

<div class="col-md-2 mb-2">

<a href="subscriber-report.php"
class="btn btn-warning w-100">

Subscribers

</a>

</div>

<div class="col-md-2 mb-2">

<a href="broadcast-report.php"
class="btn btn-danger w-100">

Broadcasts

</a>

</div>

<div class="col-md-2 mb-2">

<a href="campaign-report.php"
class="btn btn-info w-100">

Campaigns

</a>

</div>

<div class="col-md-2 mb-2">

<a href="automation-report.php"
class="btn btn-dark w-100">

Automation

</a>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Telegram Reports Workflow

</div>

<div class="card-body">

<pre>
News Distribution
        ↓
Audience Reach
        ↓
Engagement
        ↓
Traffic Analytics
        ↓
Reports
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Reports Features

</div>

<div class="card-body">

<ul>

<li>Channel Reports</li>

<li>Group Reports</li>

<li>Subscriber Reports</li>

<li>Broadcast Reports</li>

<li>Campaign Reports</li>

<li>Automation Reports</li>

<li>Reach Analytics</li>

<li>Traffic Analytics</li>

<li>Growth Reports</li>

<li>Engagement Reports</li>

<li>PDF Export</li>

<li>Excel Export</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>