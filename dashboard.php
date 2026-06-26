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

$totalFacebookPosts = $pdo->query("
SELECT COUNT(*)
FROM social_facebook_posts
")->fetchColumn();

$totalYoutubePosts = $pdo->query("
SELECT COUNT(*)
FROM social_youtube_posts
")->fetchColumn();

$totalInstagramPosts = $pdo->query("
SELECT COUNT(*)
FROM social_instagram_posts
")->fetchColumn();

$totalTwitterPosts = $pdo->query("
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

Social Media Command Center

</h3>

<div class="row">

<div class="col-md-2">

<div class="card border-primary">

<div class="card-body text-center">

<h4><?= $totalFacebookPosts; ?></h4>

<p>Facebook</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-danger">

<div class="card-body text-center">

<h4><?= $totalYoutubePosts; ?></h4>

<p>YouTube</p>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card border-warning">

<div class="card-body text-center">

<h4><?= $totalInstagramPosts; ?></h4>

<p>Instagram</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-dark">

<div class="card-body text-center">

<h4><?= $totalTwitterPosts; ?></h4>

<p>X / Twitter</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h4><?= $totalCampaigns; ?></h4>

<p>Campaigns</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Social Media Control Panel

</div>

<div class="card-body">

<div class="row">

<div class="col-md-2 mb-2">

<a href="facebook.php"
class="btn btn-primary w-100">

Facebook

</a>

</div>

<div class="col-md-2 mb-2">

<a href="youtube.php"
class="btn btn-danger w-100">

YouTube

</a>

</div>

<div class="col-md-2 mb-2">

<a href="instagram.php"
class="btn btn-warning w-100">

Instagram

</a>

</div>

<div class="col-md-2 mb-2">

<a href="x-twitter.php"
class="btn btn-dark w-100">

X/Twitter

</a>

</div>

<div class="col-md-2 mb-2">

<a href="scheduler.php"
class="btn btn-success w-100">

Scheduler

</a>

</div>

<div class="col-md-2 mb-2">

<a href="campaigns.php"
class="btn btn-info w-100">

Campaigns

</a>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Social Media Workflow

</div>

<div class="card-body">

<pre>
News Published
       ↓
Auto Social Distribution
       ↓
Facebook
Instagram
YouTube
X (Twitter)
       ↓
Audience Reach
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

<li>Facebook Publishing</li>

<li>YouTube Publishing</li>

<li>Instagram Publishing</li>

<li>X (Twitter) Publishing</li>

<li>Post Scheduling</li>

<li>Campaign Management</li>

<li>Auto News Sharing</li>

<li>Social Analytics</li>

<li>Audience Engagement</li>

<li>Hashtag Management</li>

<li>Social Reports</li>

<li>Multi Platform Posting</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>