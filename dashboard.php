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

$totalLiveStreams = $pdo->query("
SELECT COUNT(*)
FROM youtube_live_streams
")->fetchColumn();

$totalSubscribers = $pdo->query("
SELECT COUNT(*)
FROM youtube_subscribers
")->fetchColumn();

$totalPlaylists = $pdo->query("
SELECT COUNT(*)
FROM youtube_playlists
")->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

YouTube Command Center

</h3>

<div class="row">

<div class="col-md-2">

<div class="card border-danger">

<div class="card-body text-center">

<h4><?= $totalChannels; ?></h4>

<p>Channels</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-primary">

<div class="card-body text-center">

<h4><?= $totalVideos; ?></h4>

<p>Videos</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h4><?= $totalLiveStreams; ?></h4>

<p>Live Streams</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-warning">

<div class="card-body text-center">

<h4><?= $totalSubscribers; ?></h4>

<p>Subscribers</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-info">

<div class="card-body text-center">

<h4><?= $totalPlaylists; ?></h4>

<p>Playlists</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-danger text-white">

YouTube Control Panel

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 mb-2">

<a href="channels.php"
class="btn btn-danger w-100">

Channels

</a>

</div>

<div class="col-md-3 mb-2">

<a href="videos.php"
class="btn btn-primary w-100">

Videos

</a>

</div>

<div class="col-md-3 mb-2">

<a href="live-streams.php"
class="btn btn-success w-100">

Live Streams

</a>

</div>

<div class="col-md-3 mb-2">

<a href="analytics.php"
class="btn btn-warning w-100">

Analytics

</a>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

YouTube Workflow

</div>

<div class="card-body">

<pre>
News Coverage
      ↓
Video Production
      ↓
YouTube Upload
      ↓
Subscribers
      ↓
Revenue
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

<li>YouTube Channel Management</li>

<li>Video Publishing</li>

<li>Live Streaming</li>

<li>Playlist Management</li>

<li>Subscriber Tracking</li>

<li>Monetization Tracking</li>

<li>Watch Time Analytics</li>

<li>Revenue Analytics</li>

<li>SEO Optimization</li>

<li>Thumbnail Management</li>

<li>Content Scheduling</li>

<li>Performance Reports</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>