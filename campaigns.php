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
    'TCMP-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO telegram_campaigns
    (

    campaign_code,

    campaign_name,

    campaign_type,

    target_audience,

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

        $_POST['target_audience'],

        $_POST['budget'],

        $_POST['start_date'],

        $_POST['end_date'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Telegram Campaign Created Successfully';

}

$campaigns = $pdo->query("
SELECT *
FROM telegram_campaigns
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Telegram Campaign Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Create Telegram Campaign

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

<option value="news_promotion">
News Promotion
</option>

<option value="advertisement">
Advertisement
</option>

<option value="subscriber_growth">
Subscriber Growth
</option>

<option value="election">
Election Campaign
</option>

<option value="brand_awareness">
Brand Awareness
</option>

</select>

</div>

<div class="col-md-5 mb-3">

<label>Target Audience</label>

<select
name="target_audience"
class="form-control">

<option value="all_subscribers">
All Subscribers
</option>

<option value="channel_followers">
Channel Followers
</option>

<option value="district_users">
District Users
</option>

<option value="premium_members">
Premium Members
</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label>Budget (₹)</label>

<input
type="number"
name="budget"
class="form-control"
value="0">

</div>

<div class="col-md-4 mb-3">

<label>Start Date</label>

<input
type="date"
name="start_date"
class="form-control">

</div>

<div class="col-md-4 mb-3">

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
class="btn btn-primary">

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

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Name</th>
<th>Type</th>
<th>Audience</th>
<th>Budget</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($campaigns as $campaign): ?>

<tr>

<td><?= $campaign['campaign_code']; ?></td>

<td><?= htmlspecialchars($campaign['campaign_name']); ?></td>

<td><?= ucwords(str_replace('_',' ',$campaign['campaign_type'])); ?></td>

<td><?= ucwords(str_replace('_',' ',$campaign['target_audience'])); ?></td>

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
Telegram Distribution
        ↓
Audience Reach
        ↓
Website Traffic
        ↓
Conversions
        ↓
ROI Analysis
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Campaign Features

</div>

<div class="card-body">

<ul>

<li>Telegram Campaign Management</li>

<li>News Promotion Campaigns</li>

<li>Advertisement Campaigns</li>

<li>Subscriber Growth Campaigns</li>

<li>Election Campaigns</li>

<li>Reach Tracking</li>

<li>Click Tracking</li>

<li>Budget Tracking</li>

<li>ROI Analysis</li>

<li>Campaign Reports</li>

<li>Traffic Generation</li>

<li>Performance Analytics</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>