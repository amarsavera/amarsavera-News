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

if(isset($_POST['save_group']))
{

    $groupCode =
    'TGP-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO telegram_groups
    (

    group_code,

    group_name,

    group_type,

    district,

    group_link,

    total_members,

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

        $groupCode,

        $_POST['group_name'],

        $_POST['group_type'],

        $_POST['district'],

        $_POST['group_link'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Telegram Group Created Successfully';

}

$groups = $pdo->query("
SELECT *
FROM telegram_groups
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Telegram Groups Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-success text-white">

Create Telegram Group

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Group Name</label>

<input
type="text"
name="group_name"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Group Type</label>

<select
name="group_type"
class="form-control">

<option value="state_team">
State Team
</option>

<option value="bureau_team">
Bureau Team
</option>

<option value="district_team">
District Team
</option>

<option value="reporter_team">
Reporter Team
</option>

<option value="editorial_team">
Editorial Team
</option>

<option value="emergency_alert">
Emergency Alert
</option>

</select>

</div>

<div class="col-md-2 mb-3">

<label>District</label>

<input
type="text"
name="district"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Total Members</label>

<input
type="number"
class="form-control"
value="0"
readonly>

</div>

<div class="col-md-12 mb-3">

<label>Telegram Group Link</label>

<input
type="text"
name="group_link"
class="form-control"
placeholder="https://t.me/+groupinvite">

</div>

</div>

<button
type="submit"
name="save_group"
class="btn btn-success">

Create Group

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Group Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Group Name</th>
<th>Type</th>
<th>District</th>
<th>Members</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($groups as $group): ?>

<tr>

<td><?= $group['group_code']; ?></td>

<td><?= htmlspecialchars($group['group_name']); ?></td>

<td><?= ucwords(str_replace('_',' ',$group['group_type'])); ?></td>

<td><?= htmlspecialchars($group['district']); ?></td>

<td><?= number_format($group['total_members']); ?></td>

<td>

<span class="badge bg-success">

<?= ucfirst($group['status']); ?>

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

Telegram Group Workflow

</div>

<div class="card-body">

<pre>
Breaking News
      ↓
State Team
      ↓
District Team
      ↓
Reporter Team
      ↓
Ground Coverage
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Group Features

</div>

<div class="card-body">

<ul>

<li>Telegram Group Management</li>

<li>Reporter Groups</li>

<li>Bureau Groups</li>

<li>Editorial Groups</li>

<li>District Groups</li>

<li>Member Tracking</li>

<li>Internal Communication</li>

<li>News Sharing</li>

<li>Emergency Alerts</li>

<li>Group Reports</li>

<li>Moderation Tools</li>

<li>Coordination Network</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>