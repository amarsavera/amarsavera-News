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

if(isset($_POST['save_channel']))
{

    $channelCode =
    'YTCH-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO youtube_channels
    (

    channel_code,

    channel_name,

    district,

    channel_url,

    subscriber_count,

    monetization_status,

    verification_status,

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

    0,

    'pending',

    'pending',

    'active',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $channelCode,

        $_POST['channel_name'],

        $_POST['district'],

        $_POST['channel_url'],

        $_SESSION['admin_id']

    ]);

    $message =
    'YouTube Channel Added Successfully';

}

$channels = $pdo->query("
SELECT *
FROM youtube_channels
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

YouTube Channel Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-danger text-white">

Add YouTube Channel

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Channel Name</label>

<input
type="text"
name="channel_name"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label>District</label>

<input
type="text"
name="district"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Channel URL</label>

<input
type="url"
name="channel_url"
class="form-control"
placeholder="https://youtube.com/@channel">

</div>

</div>

<button
type="submit"
name="save_channel"
class="btn btn-danger">

Add Channel

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

YouTube Channel Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Channel</th>
<th>District</th>
<th>Subscribers</th>
<th>Monetization</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($channels as $channel): ?>

<tr>

<td><?= $channel['channel_code']; ?></td>

<td><?= htmlspecialchars($channel['channel_name']); ?></td>

<td><?= htmlspecialchars($channel['district']); ?></td>

<td><?= number_format($channel['subscriber_count']); ?></td>

<td>

<span class="badge bg-warning">

<?= ucfirst($channel['monetization_status']); ?>

</span>

</td>

<td>

<span class="badge bg-success">

<?= ucfirst($channel['status']); ?>

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

Channel Workflow

</div>

<div class="card-body">

<pre>
Channel Created
      ↓
Videos Published
      ↓
Subscribers Grow
      ↓
Watch Time
      ↓
Monetization
      ↓
Revenue
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Channel Features

</div>

<div class="card-body">

<ul>

<li>YouTube Channel Management</li>

<li>Multiple Channel Support</li>

<li>District Wise Channels</li>

<li>News Channels</li>

<li>Subscriber Tracking</li>

<li>Monetization Status</li>

<li>Channel Verification</li>

<li>Growth Analytics</li>

<li>Revenue Tracking</li>

<li>Channel Reports</li>

<li>Watch Time Monitoring</li>

<li>Brand Channel Management</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>