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

if(isset($_POST['save_campaign']))
{

    $campaignCode =
    'CMP-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO social_campaigns
    (

    campaign_code,

    campaign_name,

    campaign_type,

    platforms,

    budget,

    start_date,

    end_date,

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

    ?,

    'active',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $campaignCode,

        $_POST['campaign_name'],

        $_POST['campaign_type'],

        $_POST['platforms'],

        $_POST['budget'],

        $_POST['start_date'],

        $_POST['end_date'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Campaign Created Successfully';

}

$campaigns = $pdo->query("
SELECT *
FROM social_campaigns
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Campaign Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-info text-white">

Create Campaign

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Campaign Name</label>

<input
type="text"
name="campaign_name"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Campaign Type</label>

<select
name="campaign_type"
class="form-control">

<option value="brand">
Brand Awareness
</option>

<option value="election">
Election Campaign
</option>

<option value="advertisement">
Advertisement Campaign
</option>

<option value="hashtag">
Hashtag Campaign
</option>

<option value="promotion">
Promotion Campaign
</option>

</select>

</div>

<div class="col-md-5 mb-3">

<label>Platforms</label>

<select
name="platforms"
class="form-control">

<option value="facebook">
Facebook
</option>

<option value="youtube">
YouTube
</option>

<option value="instagram">
Instagram
</option>

<option value="twitter">
X/Twitter
</option>

<option value="all">
All Platforms
</option>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Budget (₹)</label>

<input
type="number"
name="budget"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Start Date</label>

<input
type="date"
name="start_date"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>End Date</label>

<input
type="date"
name="end_date"
class="form-control">

</div>

</div>

<button
type="submit"
name="save_campaign"
class="btn btn-info">

Create Campaign

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Campaign Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Name</th>
<th>Type</th>
<th>Platform</th>
<th>Budget</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($campaigns as $campaign): ?>

<tr>

<td><?= $campaign['campaign_code']; ?></td>

<td><?= htmlspecialchars($campaign['campaign_name']); ?></td>

<td><?= ucfirst($campaign['campaign_type']); ?></td>

<td><?= ucfirst($campaign['platforms']); ?></td>

<td>₹<?= number_format($campaign['budget']); ?></td>

<td>

<span class="badge bg-success">

<?= ucfirst($campaign['status']); ?>

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

Campaign Workflow

</div>

<div class="card-body">

<pre>
Campaign Created
        ↓
Platforms Selected
        ↓
Content Published
        ↓
Audience Engagement
        ↓
Performance Analysis
        ↓
ROI Report
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Campaign Features

</div>

<div class="card-body">

<ul>

<li>Social Media Campaign Management</li>

<li>Election Campaign Tracking</li>

<li>Brand Awareness Campaign</li>

<li>Advertisement Campaign</li>

<li>Hashtag Campaign</li>

<li>Multi Platform Campaign</li>

<li>Budget Tracking</li>

<li>Reach Tracking</li>

<li>Engagement Tracking</li>

<li>Campaign ROI Reports</li>

<li>Lead Generation Tracking</li>

<li>Performance Analytics</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>