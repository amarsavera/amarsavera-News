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

$message='';

if(isset($_POST['publish_tweet']))
{

    $tweetCode =
    'X-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO social_twitter_posts
    (

    tweet_code,

    tweet_title,

    tweet_type,

    tweet_content,

    hashtags,

    publish_status,

    created_by,

    created_at

    )

    VALUES
    (

    ?,

    ?,

    ?,

    ?,

    ?,

    'published',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $tweetCode,

        $_POST['tweet_title'],

        $_POST['tweet_type'],

        $_POST['tweet_content'],

        $_POST['hashtags'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Tweet Published Successfully';

}

$tweets = $pdo->query("
SELECT *
FROM social_twitter_posts
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

X (Twitter) Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-dark text-white">

Publish Tweet

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Tweet Title</label>

<input
type="text"
name="tweet_title"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Tweet Type</label>

<select
name="tweet_type"
class="form-control">

<option value="news">
News
</option>

<option value="breaking">
Breaking News
</option>

<option value="live">
Live Update
</option>

<option value="campaign">
Campaign
</option>

</select>

</div>

<div class="col-md-5 mb-3">

<label>Hashtags</label>

<input
type="text"
name="hashtags"
class="form-control"
placeholder="#AmarSavera #BreakingNews">

</div>

<div class="col-md-12 mb-3">

<label>Tweet Content</label>

<textarea
name="tweet_content"
rows="4"
class="form-control"
required></textarea>

</div>

</div>

<button
type="submit"
name="publish_tweet"
class="btn btn-dark">

Publish Tweet

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Tweet Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>Tweet Code</th>
<th>Title</th>
<th>Type</th>
<th>Status</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($tweets as $tweet): ?>

<tr>

<td><?= $tweet['tweet_code']; ?></td>

<td><?= htmlspecialchars($tweet['tweet_title']); ?></td>

<td><?= ucfirst($tweet['tweet_type']); ?></td>

<td>

<span class="badge bg-success">

<?= ucfirst($tweet['publish_status']); ?>

</span>

</td>

<td><?= $tweet['created_at']; ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Twitter Workflow

</div>

<div class="card-body">

<pre>
Breaking News
      ↓
Tweet Published
      ↓
Retweets & Reach
      ↓
Trending Analysis
      ↓
Analytics
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Twitter Features

</div>

<div class="card-body">

<ul>

<li>X (Twitter) API Integration</li>

<li>News Tweet Publishing</li>

<li>Breaking News Alerts</li>

<li>Live Event Tweeting</li>

<li>Trending Hashtag Tracking</li>

<li>Audience Analytics</li>

<li>Engagement Reports</li>

<li>Tweet Scheduler</li>

<li>Campaign Monitoring</li>

<li>Twitter Reports</li>

<li>Trend Monitoring</li>

<li>Real-Time Updates</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>