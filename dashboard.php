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

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Telegram Command Center

</h3>

<div class="row">

<div class="col-md-2">

<div class="card border-primary">

<div class="card-body text-center">

<h4><?= $totalChannels; ?></h4>

<p>Channels</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-success">

<div class="card-body text-center">

<h4><?= $totalGroups; ?></h4>

<p>Groups</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h4><?= $totalSubscribers; ?></h4>

<p>Subscribers</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-danger">

<div class="card-body text-center">

<h4><?= $totalBroadcasts; ?></h4>

<p>Broadcasts</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-info">

<div class="card-body text-center">

<h4><?= $totalCampaigns; ?></h4>

<p>Campaigns</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Telegram Control Panel

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 mb-2">

<a href="channels.php"
class="btn btn-primary w-100">

Channels

</a>

</div>

<div class="col-md-3 mb-2">

<a href="groups.php"
class="btn btn-success w-100">

Groups

</a>

</div>

<div class="col-md-3 mb-2">

<a href="broadcasts.php"
class="btn btn-danger w-100">

Broadcasts

</a>

</div>

<div class="col-md-3 mb-2">

<a href="subscribers.php"
class="btn btn-warning w-100">

Subscribers

</a>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Telegram Workflow

</div>

<div class="card-body">

<pre>
News Published
      ↓
Telegram Channel
      ↓
Telegram Groups
      ↓
Subscribers
      ↓
Website Traffic
      ↓
Analytics
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Features

</div>

<div class="card-body">

<ul>

<li>Telegram Channel Management</li>

<li>Telegram Group Management</li>

<li>News Broadcasting</li>

<li>Subscriber Tracking</li>

<li>Auto News Sharing</li>

<li>Traffic Generation</li>

<li>Campaign Management</li>

<li>Automation System</li>

<li>Engagement Analytics</li>

<li>Delivery Reports</li>

<li>Growth Tracking</li>

<li>Multi Channel Distribution</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>