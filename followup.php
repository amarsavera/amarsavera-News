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

$bookingId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT
ab.*,
a.company_name

FROM advertisement_bookings ab

LEFT JOIN advertisers a
ON a.id=ab.advertiser_id

WHERE ab.id=?
LIMIT 1
");

$stmt->execute([$bookingId]);

$booking = $stmt->fetch();

if(!$booking)
{
    die('Booking Not Found');
}

if($_SERVER['REQUEST_METHOD']=='POST')
{

    $save = $pdo->prepare("
    INSERT INTO booking_followups
    (
        booking_id,
        followup_type,
        followup_status,
        next_followup_date,
        remarks,
        executive_id,
        created_at
    )
    VALUES
    (
        ?,?,?,?,?,?,
        NOW()
    )
    ");

    $save->execute([

        $bookingId,

        $_POST['followup_type'],

        $_POST['followup_status'],

        $_POST['next_followup_date'],

        $_POST['remarks'],

        $_SESSION['admin_id']

    ]);

    header(
    "Location:followup.php?id=".$bookingId
    );

    exit;
}

$history = $pdo->prepare("
SELECT *
FROM booking_followups
WHERE booking_id=?
ORDER BY id DESC
");

$history->execute([$bookingId]);

$list = $history->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Add Follow-up

</div>

<div class="card-body">

<form method="post">

<div class="mb-3">

<label>Follow-up Type</label>

<select
name="followup_type"
class="form-control">

<option value="call">
Phone Call
</option>

<option value="whatsapp">
WhatsApp
</option>

<option value="email">
Email
</option>

<option value="meeting">
Meeting
</option>

</select>

</div>

<div class="mb-3">

<label>Status</label>

<select
name="followup_status"
class="form-control">

<option value="connected">
Connected
</option>

<option value="not_connected">
Not Connected
</option>

<option value="promised">
Payment Promised
</option>

<option value="paid">
Paid
</option>

</select>

</div>

<div class="mb-3">

<label>Next Follow-up Date</label>

<input
type="date"
name="next_followup_date"
class="form-control">

</div>

<div class="mb-3">

<label>Remarks</label>

<textarea
name="remarks"
class="form-control"
rows="4"></textarea>

</div>

<button
type="submit"
class="btn btn-success">

Save Follow-up

</button>

</form>

</div>

</div>

</div>

<div class="col-md-8">

<div class="card shadow">

<div class="card-header bg-warning">

Follow-up History

</div>

<div class="card-body">

<h5>

<?= htmlspecialchars(
$booking['booking_code']
); ?>

-

<?= htmlspecialchars(
$booking['company_name']
); ?>

</h5>

<hr>

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>

<th>Type</th>

<th>Status</th>

<th>Next Date</th>

<th>Remarks</th>

<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($list as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= $row['followup_type']; ?></td>

<td><?= $row['followup_status']; ?></td>

<td><?= $row['next_followup_date']; ?></td>

<td><?= htmlspecialchars($row['remarks']); ?></td>

<td><?= $row['created_at']; ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>