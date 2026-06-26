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

if(isset($_POST['save_stream']))
{

    $streamCode =
    'YTLS-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO youtube_live_streams
    (

    stream_code,

    stream_title,

    stream_type,

    youtube_live_url,

    scheduled_date,

    concurrent_viewers,

    stream_status,

    revenue_generated,

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

    0,

    'scheduled',

    0,

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $streamCode,

        $_POST['stream_title'],

        $_POST['stream_type'],

        $_POST['youtube_live_url'],

        $_POST['scheduled_date'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Live Stream Scheduled Successfully';

}

$streams = $pdo->query("
SELECT *
FROM youtube_live_streams
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

YouTube Live Streams Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-danger text-white">

Schedule Live Stream

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Stream Title</label>

<input
type="text"
name="stream_title"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Stream Type</label>

<select
name="stream_type"
class="form-control">

<option value="breaking_news">
Breaking News
</option>

<option value="election_live">
Election Live
</option>

<option value="event_coverage">
Event Coverage
</option>

<option value="press_conference">
Press Conference
</option>

<option value="special_program">
Special Program
</option>

</select>

</div>

<div class="col-md-5 mb-3">

<label>YouTube Live URL</label>

<input
type="url"
name="youtube_live_url"
class="form-control">

</div>

<div class="col-md-4 mb-3">

<label>Scheduled Date & Time</label>

<input
type="datetime-local"
name="scheduled_date"
class="form-control">

</div>

</div>

<button
type="submit"
name="save_stream"
class="btn btn-danger">

Schedule Stream

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Live Stream Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Title</th>
<th>Type</th>
<th>Viewers</th>
<th>Revenue</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($streams as $stream): ?>

<tr>

<td><?= $stream['stream_code']; ?></td>

<td><?= htmlspecialchars($stream['stream_title']); ?></td>

<td><?= ucwords(str_replace('_',' ',$stream['stream_type'])); ?></td>

<td><?= number_format($stream['concurrent_viewers']); ?></td>

<td>₹<?= number_format($stream['revenue_generated']); ?></td>

<td>

<span class="badge bg-warning">

<?= ucfirst($stream['stream_status']); ?>

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

Live Stream Workflow

</div>

<div class="card-body">

<pre>
Ground Coverage
      ↓
Live Stream Setup
      ↓
YouTube Live
      ↓
Viewers Join
      ↓
Engagement
      ↓
Revenue & Analytics
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Live Stream Features

</div>

<div class="card-body">

<ul>

<li>Live News Streaming</li>

<li>Breaking News Live</li>

<li>Election Live Coverage</li>

<li>Event Live Coverage</li>

<li>Stream Scheduling</li>

<li>Stream Analytics</li>

<li>Concurrent Viewers</li>

<li>Live Chat Monitoring</li>

<li>Stream Revenue</li>

<li>Live Reports</li>

<li>Real-Time Viewer Tracking</li>

<li>Live Production Management</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>