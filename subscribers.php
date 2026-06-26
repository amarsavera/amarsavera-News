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

if(isset($_POST['save_subscriber']))
{

    $subscriberCode =
    'YTSUB-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO youtube_subscribers
    (

    subscriber_code,

    subscriber_name,

    channel_name,

    source,

    district,

    engagement_level,

    status,

    subscribed_at,

    created_by

    )

    VALUES
    (

    ?,

    ?,

    ?,

    ?,

    ?,

    ?,

    'active',

    NOW(),

    ?

    )

    ");

    $stmt->execute([

        $subscriberCode,

        $_POST['subscriber_name'],

        $_POST['channel_name'],

        $_POST['source'],

        $_POST['district'],

        $_POST['engagement_level'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Subscriber Added Successfully';

}

$subscribers = $pdo->query("
SELECT *
FROM youtube_subscribers
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

YouTube Subscriber Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-danger text-white">

Add Subscriber

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-3 mb-3">

<label>Subscriber Name</label>

<input
type="text"
name="subscriber_name"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Channel Name</label>

<input
type="text"
name="channel_name"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label>Source</label>

<select
name="source"
class="form-control">

<option value="youtube_search">
YouTube Search
</option>

<option value="shorts">
Shorts
</option>

<option value="suggested_videos">
Suggested Videos
</option>

<option value="external_link">
External Link
</option>

<option value="social_media">
Social Media
</option>

</select>

</div>

<div class="col-md-2 mb-3">

<label>District</label>

<input
type="text"
name="district"
class="form-control">

</div>

<div class="col-md-2 mb-3">

<label>Engagement</label>

<select
name="engagement_level"
class="form-control">

<option value="high">
High
</option>

<option value="medium">
Medium
</option>

<option value="low">
Low
</option>

</select>

</div>

</div>

<button
type="submit"
name="save_subscriber"
class="btn btn-danger">

Add Subscriber

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Subscriber Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Name</th>
<th>Channel</th>
<th>Source</th>
<th>Engagement</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($subscribers as $subscriber): ?>

<tr>

<td><?= $subscriber['subscriber_code']; ?></td>

<td><?= htmlspecialchars($subscriber['subscriber_name']); ?></td>

<td><?= htmlspecialchars($subscriber['channel_name']); ?></td>

<td><?= ucwords(str_replace('_',' ',$subscriber['source'])); ?></td>

<td>

<span class="badge bg-info">

<?= ucfirst($subscriber['engagement_level']); ?>

</span>

</td>

<td>

<span class="badge bg-success">

<?= ucfirst($subscriber['status']); ?>

</span>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Subscriber Workflow

</div>

<div class="card-body">

<pre>
Video Published
      ↓
Viewer Watches
      ↓
Subscriber Added
      ↓
Notifications Enabled
      ↓
Regular Audience
      ↓
Revenue Growth
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Subscriber Features

</div>

<div class="card-body">

<ul>

<li>Subscriber Management</li>

<li>Channel Wise Subscribers</li>

<li>Growth Tracking</li>

<li>Subscriber Sources</li>

<li>Subscriber Analytics</li>

<li>Engagement Tracking</li>

<li>Subscriber Demographics</li>

<li>Watch Behavior Analysis</li>

<li>Retention Analytics</li>

<li>Subscriber Reports</li>

<li>Audience Segmentation</li>

<li>Growth Monitoring</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>