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
    'TSUB-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO telegram_subscribers
    (

    subscriber_code,

    full_name,

    telegram_username,

    district,

    source,

    status,

    joined_date,

    created_by

    )

    VALUES
    (

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

        $_POST['full_name'],

        $_POST['telegram_username'],

        $_POST['district'],

        $_POST['source'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Telegram Subscriber Added Successfully';

}

$subscribers = $pdo->query("
SELECT *
FROM telegram_subscribers
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Telegram Subscriber Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Add Subscriber

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-3 mb-3">

<label>Full Name</label>

<input
type="text"
name="full_name"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Telegram Username</label>

<input
type="text"
name="telegram_username"
class="form-control"
placeholder="@username">

</div>

<div class="col-md-3 mb-3">

<label>District</label>

<input
type="text"
name="district"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Subscriber Source</label>

<select
name="source"
class="form-control">

<option value="telegram_channel">
Telegram Channel
</option>

<option value="telegram_group">
Telegram Group
</option>

<option value="website">
Website
</option>

<option value="facebook">
Facebook
</option>

<option value="whatsapp">
WhatsApp
</option>

<option value="referral">
Referral
</option>

</select>

</div>

</div>

<button
type="submit"
name="save_subscriber"
class="btn btn-primary">

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
<th>Username</th>
<th>District</th>
<th>Source</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($subscribers as $subscriber): ?>

<tr>

<td><?= $subscriber['subscriber_code']; ?></td>

<td><?= htmlspecialchars($subscriber['full_name']); ?></td>

<td><?= htmlspecialchars($subscriber['telegram_username']); ?></td>

<td><?= htmlspecialchars($subscriber['district']); ?></td>

<td><?= ucfirst(str_replace('_',' ',$subscriber['source'])); ?></td>

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
User Joins Channel
        ↓
Subscriber Added
        ↓
News Delivered
        ↓
Website Visit
        ↓
Engagement
        ↓
Analytics
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Subscriber Features

</div>

<div class="card-body">

<ul>

<li>Telegram Subscriber Management</li>

<li>Channel Subscribers</li>

<li>Group Members</li>

<li>District Wise Subscribers</li>

<li>Active/Inactive Subscribers</li>

<li>Subscriber Analytics</li>

<li>Growth Tracking</li>

<li>Engagement Reports</li>

<li>Import / Export</li>

<li>Audience Database</li>

<li>Traffic Monitoring</li>

<li>Performance Reports</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>