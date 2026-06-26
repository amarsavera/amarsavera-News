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
FROM youtube_channels
")->fetchColumn();

$totalVideos = $pdo->query("
SELECT COUNT(*)
FROM youtube_videos
")->fetchColumn();

$totalSubscribers = $pdo->query("
SELECT COUNT(*)
FROM youtube_subscribers
")->fetchColumn();

$totalLiveStreams = $pdo->query("
SELECT COUNT(*)
FROM youtube_live_streams
")->fetchColumn();

$totalViews = $pdo->query("
SELECT COALESCE(SUM(views),0)
FROM youtube_videos
")->fetchColumn();

$totalWatchTime = $pdo->query("
SELECT COALESCE(SUM(watch_time),0)
FROM youtube_videos
")->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

YouTube Analytics Dashboard

</h3>

<div class="row">

<div class="col-md-2">

<div class="card border-danger">

<div class="card-body text-center">

<h4><?= number_format($totalChannels); ?></h4>

<p>Channels</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-primary">

<div class="card-body text-center">

<h4><?= number_format($totalVideos); ?></h4>

<p>Videos</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-success">

<div class="card-body text-center">

<h4><?= number_format($totalSubscribers); ?></h4>

<p>Subscribers</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h4><?= number_format($totalViews); ?></h4>

<p>Total Views</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-info">

<div class="card-body text-center">

<h4><?= number_format($totalWatchTime); ?></h4>

<p>Watch Time (Min)</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-danger text-white">

Analytics Summary

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Total Channels</th>
<td><?= number_format($totalChannels); ?></td>
</tr>

<tr>
<th>Total Videos</th>
<td><?= number_format($totalVideos); ?></td>
</tr>

<tr>
<th>Total Subscribers</th>
<td><?= number_format($totalSubscribers); ?></td>
</tr>

<tr>
<th>Total Live Streams</th>
<td><?= number_format($totalLiveStreams); ?></td>
</tr>

<tr>
<th>Total Views</th>
<td><?= number_format($totalViews); ?></td>
</tr>

<tr>
<th>Total Watch Time</th>
<td><?= number_format($totalWatchTime); ?> Minutes</td>
</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Analytics Modules

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 mb-2">

<a href="channel-analytics.php"
class="btn btn-danger w-100">

Channel Analytics

</a>

</div>

<div class="col-md-3 mb-2">

<a href="video-analytics.php"
class="btn btn-primary w-100">

Video Analytics

</a>

</div>

<div class="col-md-3 mb-2">

<a href="subscriber-analytics.php"
class="btn btn-success w-100">

Subscriber Analytics

</a>

</div>

<div class="col-md-3 mb-2">

<a href="revenue-analytics.php"
class="btn btn-warning w-100">

Revenue Analytics

</a>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Analytics Workflow

</div>

<div class="card-body">

<pre>
Videos Published
      ↓
Views Generated
      ↓
Watch Time
      ↓
Subscribers Growth
      ↓
Revenue
      ↓
Analytics Dashboard
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Analytics Features

</div>

<div class="card-body">

<ul>

<li>Channel Analytics</li>

<li>Video Analytics</li>

<li>Watch Time Reports</li>

<li>Revenue Analytics</li>

<li>Subscriber Growth Analytics</li>

<li>CTR Tracking</li>

<li>Audience Retention</li>

<li>Top Performing Videos</li>

<li>Monetization Reports</li>

<li>YouTube KPI Dashboard</li>

<li>Growth Forecasting</li>

<li>Performance Monitoring</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>