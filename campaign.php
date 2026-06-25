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

/*
|--------------------------------------------------------------------------
| Create Campaign
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_campaign']))
{

    $stmt=$pdo->prepare("
    INSERT INTO advertisements
    (

    booking_id,
    campaign_name,

    position_id,

    start_date,
    end_date,

    target_impressions,
    target_clicks,

    budget,

    priority,

    status,

    created_by,
    created_at

    )

    VALUES
    (

    ?,?,
    ?,
    ?,?,
    ?,?,
    ?,
    ?,
    'active',
    ?,
    NOW()

    )
    ");

    $stmt->execute([

        $_POST['booking_id'],
        $_POST['campaign_name'],

        $_POST['position_id'],

        $_POST['start_date'],
        $_POST['end_date'],

        $_POST['target_impressions'],
        $_POST['target_clicks'],

        $_POST['budget'],

        $_POST['priority'],

        $_SESSION['admin_id']

    ]);

    $message='Campaign Created Successfully';

}

$bookings=$pdo->query("
SELECT
id,
booking_number,
ad_title
FROM advertisement_bookings
ORDER BY id DESC
")->fetchAll();

$positions=$pdo->query("
SELECT
id,
position_name
FROM advertisement_positions
WHERE status='active'
ORDER BY priority
")->fetchAll();

$campaigns=$pdo->query("
SELECT

a.*,

ab.booking_number,

ap.position_name

FROM advertisements a

LEFT JOIN advertisement_bookings ab
ON ab.id=a.booking_id

LEFT JOIN advertisement_positions ap
ON ap.id=a.position_id

ORDER BY a.id DESC

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

<div class="card-header bg-primary text-white">

Create Campaign

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>

Booking

</label>

<select
name="booking_id"
class="form-control"
required>

<option value="">

Select Booking

</option>

<?php foreach($bookings as $booking): ?>

<option value="<?= $booking['id']; ?>">

<?= htmlspecialchars(
$booking['booking_number']
); ?>

-
<?= htmlspecialchars(
$booking['ad_title']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>

Campaign Name

</label>

<input
type="text"
name="campaign_name"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>

Ad Position

</label>

<select
name="position_id"
class="form-control"
required>

<?php foreach($positions as $position): ?>

<option value="<?= $position['id']; ?>">

<?= htmlspecialchars(
$position['position_name']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-3 mb-3">

<label>

Start Date

</label>

<input
type="date"
name="start_date"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>

End Date

</label>

<input
type="date"
name="end_date"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>

Target Impressions

</label>

<input
type="number"
name="target_impressions"
value="10000"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>

Target Clicks

</label>

<input
type="number"
name="target_clicks"
value="500"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>

Budget

</label>

<input
type="number"
step="0.01"
name="budget"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>

Priority

</label>

<input
type="number"
name="priority"
value="1"
class="form-control">

</div>

</div>

<button
type="submit"
name="save_campaign"
class="btn btn-success">

Create Campaign

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Campaign List

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Campaign</th>
<th>Booking</th>
<th>Position</th>
<th>Duration</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($campaigns as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['campaign_name']); ?></td>

<td><?= htmlspecialchars($row['booking_number']); ?></td>

<td><?= htmlspecialchars($row['position_name']); ?></td>

<td>

<?= $row['start_date']; ?>

to

<?= $row['end_date']; ?>

</td>

<td>

<span class="badge bg-success">

<?= ucfirst($row['status']); ?>

</span>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>