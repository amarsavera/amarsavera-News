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
    'WCH-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO whatsapp_channels
    (

    channel_code,

    channel_name,

    district,

    category,

    channel_link,

    subscribers,

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

    0,

    'active',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $channelCode,

        $_POST['channel_name'],

        $_POST['district'],

        $_POST['category'],

        $_POST['channel_link'],

        $_SESSION['admin_id']

    ]);

    $message =
    'WhatsApp Channel Created Successfully';

}

$channels = $pdo->query("
SELECT *
FROM whatsapp_channels
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

WhatsApp Channel Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-success text-white">

Create WhatsApp Channel

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

<div class="col-md-3 mb-3">

<label>Category</label>

<select
name="category"
class="form-control">

<option value="news">
News
</option>

<option value="breaking">
Breaking News
</option>

<option value="district">
District News
</option>

<option value="sports">
Sports
</option>

<option value="special">
Special Coverage
</option>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Status</label>

<input
type="text"
class="form-control"
value="Active"
readonly>

</div>

<div class="col-md-12 mb-3">

<label>Channel Link</label>

<input
type="text"
name="channel_link"
class="form-control"
placeholder="https://whatsapp.com/channel/...">

</div>

</div>

<button
type="submit"
name="save_channel"
class="btn btn-success">

Create Channel

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Channel Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Channel Name</th>
<th>District</th>
<th>Category</th>
<th>Subscribers</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($channels as $channel): ?>

<tr>

<td><?= $channel['channel_code']; ?></td>

<td><?= htmlspecialchars($channel['channel_name']); ?></td>

<td><?= htmlspecialchars($channel['district']); ?></td>

<td><?= ucfirst($channel['category']); ?></td>

<td><?= number_format($channel['subscribers']); ?></td>

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
News Published
      ↓
WhatsApp Channel
      ↓
Subscriber Reach
      ↓
Website Traffic
      ↓
Analytics
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Channel Features

</div>

<div class="card-body">

<ul>

<li>WhatsApp Channel Management</li>

<li>District Wise Channels</li>

<li>News Distribution</li>

<li>Breaking News Channels</li>

<li>Auto Posting</li>

<li>Subscriber Tracking</li>

<li>Growth Analytics</li>

<li>Performance Reports</li>

<li>Traffic Monitoring</li>

<li>Channel Categories</li>

<li>Engagement Reports</li>

<li>Channel Archive</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>