<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(
!isset($_SESSION['admin_id'])
||
$_SESSION['role']!='super_admin'
)
{
    die('Access Denied');
}

$message='';

/*
|--------------------------------------------------------------------------
| Send Notification
|--------------------------------------------------------------------------
*/

if(isset($_POST['send_notification']))
{

    $stmt = $pdo->prepare("
    INSERT INTO notifications
    (

    notification_type,

    title,

    message,

    target_group,

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

    'sent',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $_POST['notification_type'],

        $_POST['title'],

        $_POST['message'],

        $_POST['target_group'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Notification Sent Successfully';

}

$notifications = $pdo->query("
SELECT *
FROM notifications
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Notification Center

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Send Notification

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-3 mb-3">

<label>Type</label>

<select
name="notification_type"
class="form-control">

<option value="system">
System
</option>

<option value="email">
Email
</option>

<option value="whatsapp">
WhatsApp
</option>

<option value="push">
Push Notification
</option>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Target Group</label>

<select
name="target_group"
class="form-control">

<option value="all">
All Users
</option>

<option value="reporters">
Reporters
</option>

<option value="district_heads">
District Heads
</option>

<option value="division_heads">
Division Heads
</option>

<option value="state_heads">
State Heads
</option>

<option value="advertisement">
Advertisement Team
</option>

<option value="hrms">
HR Team
</option>

<option value="finance">
Finance Team
</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Title</label>

<input
type="text"
name="title"
class="form-control"
required>

</div>

<div class="col-md-12 mb-3">

<label>Message</label>

<textarea
name="message"
rows="5"
class="form-control"
required></textarea>

</div>

</div>

<button
type="submit"
name="send_notification"
class="btn btn-success">

Send Notification

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Notification History

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Type</th>
<th>Title</th>
<th>Target</th>
<th>Status</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($notifications as $notification): ?>

<tr>

<td>

<?= $notification['id']; ?>

</td>

<td>

<?= ucfirst(
$notification['notification_type']
); ?>

</td>

<td>

<?= htmlspecialchars(
$notification['title']
); ?>

</td>

<td>

<?= ucfirst(
str_replace(
'_',
' ',
$notification['target_group']
)
); ?>

</td>

<td>

<span class="badge bg-success">

<?= ucfirst(
$notification['status']
); ?>

</span>

</td>

<td>

<?= $notification['created_at']; ?>

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

Auto Notification Triggers

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>News Submitted</th>
<td>District Head Alert</td>
</tr>

<tr>
<th>News Approved</th>
<td>Division Head Alert</td>
</tr>

<tr>
<th>News Published</th>
<td>Reporter Alert</td>
</tr>

<tr>
<th>Advertisement Booking</th>
<td>Sales Team Alert</td>
</tr>

<tr>
<th>Payroll Generated</th>
<td>Employee Alert</td>
</tr>

<tr>
<th>Leave Request</th>
<td>HR Manager Alert</td>
</tr>

<tr>
<th>Target Assigned</th>
<td>Employee Alert</td>
</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Notification Channels

</div>

<div class="card-body">

<ul>

<li>Dashboard Notifications</li>

<li>Email Notifications</li>

<li>WhatsApp API Notifications</li>

<li>Push Notifications</li>

<li>SMS Gateway Ready</li>

<li>Broadcast Messaging</li>

<li>Department Wise Alerts</li>

<li>Emergency Announcements</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>