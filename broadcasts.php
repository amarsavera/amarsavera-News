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

if(isset($_POST['save_broadcast']))
{

    $broadcastCode =
    'WBC-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO whatsapp_broadcasts
    (

    broadcast_code,

    title,

    message_text,

    broadcast_type,

    target_group,

    website_link,

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

    ?,

    'scheduled',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $broadcastCode,

        $_POST['title'],

        $_POST['message_text'],

        $_POST['broadcast_type'],

        $_POST['target_group'],

        $_POST['website_link'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Broadcast Created Successfully';

}

$broadcasts = $pdo->query("
SELECT *
FROM whatsapp_broadcasts
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

WhatsApp Broadcast Center

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-success text-white">

Create Broadcast

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Broadcast Title</label>

<input
type="text"
name="title"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Broadcast Type</label>

<select
name="broadcast_type"
class="form-control">

<option value="news">
News
</option>

<option value="breaking">
Breaking News
</option>

<option value="advertisement">
Advertisement
</option>

<option value="campaign">
Campaign
</option>

<option value="alert">
Emergency Alert
</option>

</select>

</div>

<div class="col-md-5 mb-3">

<label>Target Audience</label>

<select
name="target_group"
class="form-control">

<option value="all">
All Subscribers
</option>

<option value="state">
State Groups
</option>

<option value="district">
District Groups
</option>

<option value="reporters">
Reporter Groups
</option>

<option value="channels">
Channel Followers
</option>

</select>

</div>

<div class="col-md-12 mb-3">

<label>Message</label>

<textarea
name="message_text"
rows="5"
class="form-control"
required></textarea>

</div>

<div class="col-md-12 mb-3">

<label>Website Link</label>

<input
type="text"
name="website_link"
class="form-control"
placeholder="https://amar-savera.saragone.in">

</div>

</div>

<button
type="submit"
name="save_broadcast"
class="btn btn-success">

Create Broadcast

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Broadcast Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Title</th>
<th>Type</th>
<th>Target</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($broadcasts as $broadcast): ?>

<tr>

<td><?= $broadcast['broadcast_code']; ?></td>

<td><?= htmlspecialchars($broadcast['title']); ?></td>

<td><?= ucfirst($broadcast['broadcast_type']); ?></td>

<td><?= ucfirst($broadcast['target_group']); ?></td>

<td>

<span class="badge bg-warning">

<?= ucfirst($broadcast['status']); ?>

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

Broadcast Workflow

</div>

<div class="card-body">

<pre>
Breaking News
      ↓
Broadcast Created
      ↓
WhatsApp Delivery
      ↓
Subscribers Receive
      ↓
Website Visits
      ↓
Analytics
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Broadcast Features

</div>

<div class="card-body">

<ul>

<li>News Broadcast System</li>

<li>Breaking News Broadcast</li>

<li>District Wise Broadcast</li>

<li>Scheduled Broadcasts</li>

<li>Bulk Message Sending</li>

<li>Website Link Sharing</li>

<li>Media Attachment Support</li>

<li>Delivery Tracking</li>

<li>Read Tracking</li>

<li>Broadcast Reports</li>

<li>Traffic Generation</li>

<li>Campaign Broadcasting</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>