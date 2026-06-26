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
    'WAUTO-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO whatsapp_automation
    (

    automation_code,

    automation_name,

    automation_type,

    trigger_event,

    target_channel,

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

        $_POST['target_channel'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Automation Created Successfully';

}

$automations = $pdo->query("
SELECT *
FROM whatsapp_automation
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

WhatsApp Automation Center

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-success text-white">

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

<option value="breaking_news">
Breaking News Automation
</option>

<option value="welcome_message">
Welcome Message
</option>

<option value="scheduled_broadcast">
Scheduled Broadcast
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

<option value="new_subscriber">
New Subscriber
</option>

<option value="breaking_alert">
Breaking Alert
</option>

<option value="schedule_time">
Schedule Time
</option>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Target</label>

<select
name="target_channel"
class="form-control">

<option value="channel">
WhatsApp Channel
</option>

<option value="groups">
Groups
</option>

<option value="broadcast">
Broadcast List
</option>

<option value="all">
All Targets
</option>

</select>

</div>

</div>

<button
type="submit"
name="save_automation"
class="btn btn-success">

Create Automation

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

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

<td><?= ucfirst($automation['target_channel']); ?></td>

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
WhatsApp Channel
      ↓
Groups
      ↓
Broadcast Lists
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

<li>Scheduled Broadcasts</li>

<li>Breaking News Automation</li>

<li>Channel Auto Posting</li>

<li>Group Auto Posting</li>

<li>Auto Subscriber Welcome Message</li>

<li>Auto Traffic Campaigns</li>

<li>WhatsApp Bot Integration</li>

<li>Workflow Automation</li>

<li>Automation Logs</li>

<li>Multi Channel Distribution</li>

<li>Real Time Notifications</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>