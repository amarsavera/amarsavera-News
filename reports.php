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

$totalFacebook = $pdo->query("
SELECT COUNT(*)
FROM social_facebook_posts
")->fetchColumn();

$totalYoutube = $pdo->query("
SELECT COUNT(*)
FROM social_youtube_posts
")->fetchColumn();

$totalInstagram = $pdo->query("
SELECT COUNT(*)
FROM social_instagram_posts
")->fetchColumn();

$totalTwitter = $pdo->query("
SELECT COUNT(*)
FROM social_twitter_posts
")->fetchColumn();

$totalCampaigns = $pdo->query("
SELECT COUNT(*)
FROM social_campaigns
")->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Social Media Reports & Analytics

</h3>

<div class="row">

<div class="col-md-2">

<div class="card border-primary">

<div class="card-body text-center">

<h4><?= number_format($totalFacebook); ?></h4>

<p>Facebook Posts</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-danger">

<div class="card-body text-center">

<h4><?= number_format($totalYoutube); ?></h4>

<p>YouTube Content</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-warning">

<div class="card-body text-center">

<h4><?= number_format($totalInstagram); ?></h4>

<p>Instagram Posts</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-dark">

<div class="card-body text-center">

<h4><?= number_format($totalTwitter); ?></h4>

<p>X/Twitter Posts</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h4><?= number_format($totalCampaigns); ?></h4>

<p>Campaigns</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Social Media Summary

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Facebook Posts</th>
<td><?= number_format($totalFacebook); ?></td>
</tr>

<tr>
<th>YouTube Content</th>
<td><?= number_format($totalYoutube); ?></td>
</tr>

<tr>
<th>Instagram Posts</th>
<td><?= number_format($totalInstagram); ?></td>
</tr>

<tr>
<th>X/Twitter Posts</th>
<td><?= number_format($totalTwitter); ?></td>
</tr>

<tr>
<th>Campaigns</th>
<td><?= number_format($totalCampaigns); ?></td>
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

<a href="facebook-report.php"
class="btn btn-primary w-100">

Facebook

</a>

</div>

<div class="col-md-2 mb-2">

<a href="youtube-report.php"
class="btn btn-danger w-100">

YouTube

</a>

</div>

<div class="col-md-2 mb-2">

<a href="instagram-report.php"
class="btn btn-warning w-100">

Instagram

</a>

</div>

<div class="col-md-2 mb-2">

<a href="twitter-report.php"
class="btn btn-dark w-100">

X/Twitter

</a>

</div>

<div class="col-md-2 mb-2">

<a href="campaign-report.php"
class="btn btn-success w-100">

Campaigns

</a>

</div>

<div class="col-md-2 mb-2">

<a href="engagement-report.php"
class="btn btn-info w-100">

Engagement

</a>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Social Media Reports Workflow

</div>

<div class="card-body">

<pre>
Posts Published
      ↓
Audience Reach
      ↓
Engagement
      ↓
Analytics
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

<li>Facebook Reports</li>

<li>YouTube Reports</li>

<li>Instagram Reports</li>

<li>X/Twitter Reports</li>

<li>Campaign Reports</li>

<li>Reach Analytics</li>

<li>Engagement Analytics</li>

<li>Follower Growth Reports</li>

<li>ROI Reports</li>

<li>Monthly Analytics</li>

<li>PDF Export</li>

<li>Excel Export</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>