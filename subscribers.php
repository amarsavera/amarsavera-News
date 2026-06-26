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
    'SUB-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO whatsapp_subscribers
    (

    subscriber_code,

    full_name,

    mobile_number,

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

        $_POST['mobile_number'],

        $_POST['district'],

        $_POST['source'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Subscriber Added Successfully';

}

$subscribers = $pdo->query("
SELECT *
FROM whatsapp_subscribers
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Subscriber Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-success text-white">

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

<label>Mobile Number</label>

<input
type="text"
name="mobile_number"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>District</label>

<input
type="text"
name="district"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Subscription Source</label>

<select
name="source"
class="form-control">

<option value="website">
Website
</option>

<option value="whatsapp_channel">
WhatsApp Channel
</option>

<option value="facebook">
Facebook
</option>

<option value="youtube">
YouTube
</option>

<option value="instagram">
Instagram
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
class="btn btn-success">

Add Subscriber

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Subscriber Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Name</th>
<th>Mobile</th>
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

<td><?= htmlspecialchars($subscriber['mobile_number']); ?></td>

<td><?= htmlspecialchars($subscriber['district']); ?></td>

<td><?= ucfirst($subscriber['source']); ?></td>

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
Engagement Tracking
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

<li>Subscriber Management</li>

<li>WhatsApp Channel Subscribers</li>

<li>District Wise Subscribers</li>

<li>Active/Inactive Subscribers</li>

<li>Subscription Source Tracking</li>

<li>Import Subscribers</li>

<li>Export Subscribers</li>

<li>Growth Tracking</li>

<li>Subscriber Analytics</li>

<li>Engagement Reports</li>

<li>Audience Database</li>

<li>Subscriber Reports</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>