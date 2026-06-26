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

if(isset($_POST['save_schedule']))
{

    $scheduleCode =
    'SCH-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO social_scheduler
    (

    schedule_code,

    post_title,

    platform,

    content_type,

    scheduled_at,

    status,

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

    'scheduled',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $scheduleCode,

        $_POST['post_title'],

        $_POST['platform'],

        $_POST['content_type'],

        $_POST['scheduled_at'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Content Scheduled Successfully';

}

$schedules = $pdo->query("
SELECT *
FROM social_scheduler
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Social Media Scheduler

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-success text-white">

Schedule Content

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Post Title</label>

<input
type="text"
name="post_title"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label>Platform</label>

<select
name="platform"
class="form-control">

<option value="facebook">Facebook</option>
<option value="youtube">YouTube</option>
<option value="instagram">Instagram</option>
<option value="twitter">X/Twitter</option>
<option value="all">All Platforms</option>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Content Type</label>

<select
name="content_type"
class="form-control">

<option value="news">News</option>
<option value="breaking">Breaking</option>
<option value="video">Video</option>
<option value="live">Live Update</option>
<option value="campaign">Campaign</option>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Schedule Date Time</label>

<input
type="datetime-local"
name="scheduled_at"
class="form-control"
required>

</div>

</div>

<button
type="submit"
name="save_schedule"
class="btn btn-success">

Schedule Post

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Scheduled Queue

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Schedule Code</th>
<th>Title</th>
<th>Platform</th>
<th>Type</th>
<th>Scheduled Time</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($schedules as $schedule): ?>

<tr>

<td><?= $schedule['schedule_code']; ?></td>

<td><?= htmlspecialchars($schedule['post_title']); ?></td>

<td><?= ucfirst($schedule['platform']); ?></td>

<td><?= ucfirst($schedule['content_type']); ?></td>

<td><?= $schedule['scheduled_at']; ?></td>

<td>

<span class="badge bg-warning">

<?= ucfirst($schedule['status']); ?>

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

Scheduler Workflow

</div>

<div class="card-body">

<pre>
News Created
      ↓
Schedule Time Set
      ↓
Queue Added
      ↓
Auto Publish
      ↓
Analytics Updated
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Scheduler Features

</div>

<div class="card-body">

<ul>

<li>Multi Platform Scheduling</li>

<li>Facebook Scheduler</li>

<li>YouTube Scheduler</li>

<li>Instagram Scheduler</li>

<li>X/Twitter Scheduler</li>

<li>Bulk Scheduling</li>

<li>Auto Publishing</li>

<li>Campaign Scheduling</li>

<li>Queue Management</li>

<li>Publishing Calendar</li>

<li>Time Zone Support</li>

<li>Auto Analytics Sync</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>