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

if(isset($_POST['save_automation']))
{

    $automationCode =
    'TAUTO-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO telegram_automation
    (

    automation_code,

    automation_name,

    automation_type,

    trigger_event,

    target_destination,

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

    'active',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $automationCode,

        $_POST['automation_name'],

        $_POST['automation_type'],

        $_POST['trigger_event'],

        $_POST['target_destination'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Telegram Automation Created Successfully';

}

$automations = $pdo->query("
SELECT *
FROM telegram_automation
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Telegram Automation Center

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Create Automation

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Automation Name</label>

<input
type="text"
name="automation_name"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Automation Type</label>

<select
name="automation_type"
class="form-control">

<option value="auto_news">
Auto News Sharing
</option>

<option value="breaking_alert">
Breaking News Alert
</option>

<option value="scheduled_post">
Scheduled Post
</option>

<option value="welcome_message">
Welcome Message
</option>

<option value="traffic_campaign">
Traffic Campaign
</option>

</select>

</div>

<div class="col-md-2 mb-3">

<label>Trigger Event</label>

<select
name="trigger_event"
class="form-control">

<option value="news_publish">
News Publish
</option>

<option value="breaking_news">
Breaking News
</option>

<option value="new_subscriber">
New Subscriber
</option>

<option value="scheduled_time">
Scheduled Time
</option>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Target</label>

<select
name="target_destination"
class="form-control">

<option value="channel">
Telegram Channel
</option>

<option value="group">
Telegram Group
</option>

<option value="all">
All Destinations
</option>

</select>

</div>

</div>

<button
type="submit"
name="save_automation"
class="btn btn-primary">

Create Automation

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Automation Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Name</th>
<th>Type</th>
<th>Trigger</th>
<th>Target</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($automations as $automation): ?>

<tr>

<td><?= $automation['automation_code']; ?></td>

<td><?= htmlspecialchars($automation['automation_name']); ?></td>

<td><?= ucwords(str_replace('_',' ',$automation['automation_type'])); ?></td>

<td><?= ucwords(str_replace('_',' ',$automation['trigger_event'])); ?></td>

<td><?= ucfirst($automation['target_destination']); ?></td>

<td>

<span class="badge bg-success">

<?= ucfirst($automation['status']); ?>

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

Automation Workflow

</div>

<div class="card-body">

<pre>
News Published
      ↓
Automation Trigger
      ↓
Telegram Channel
      ↓
Telegram Groups
      ↓
Subscribers
      ↓
Traffic Generated
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Automation Features

</div>

<div class="card-body">

<ul>

<li>Auto News Sharing</li>

<li>Telegram Bot Integration</li>

<li>Auto Channel Posting</li>

<li>Auto Group Posting</li>

<li>Breaking News Alerts</li>

<li>Scheduled Posts</li>

<li>Auto Welcome Messages</li>

<li>Auto Traffic Campaigns</li>

<li>Automation Logs</li>

<li>Workflow Management</li>

<li>Real Time Distribution</li>

<li>Multi Channel Publishing</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>