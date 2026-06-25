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
| Create Booking
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_booking']))
{

    $rateCard = $pdo->prepare("
    SELECT *
    FROM advertisement_rate_card
    WHERE id=?
    LIMIT 1
    ");

    $rateCard->execute([
    $_POST['rate_card_id']
    ]);

    $rate =
    $rateCard->fetch();

    $invoiceNo =
    'ADV-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO advertisement_bookings
    (

    booking_no,

    client_id,

    rate_card_id,

    category_id,

    start_date,

    end_date,

    creative_file,

    amount,

    gst_percent,

    gst_amount,

    total_amount,

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

    ?,

    ?,

    ?,

    ?,

    'pending',

    ?,

    NOW()

    )

    ");

    $baseAmount =
    $rate['base_rate'];

    $gstAmount =
    $rate['base_rate']
    *
    $rate['gst_percent']
    /100;

    $totalAmount =
    $baseAmount
    +
    $gstAmount;

    $stmt->execute([

        $invoiceNo,

        $_POST['client_id'],

        $_POST['rate_card_id'],

        $rate['category_id'],

        $_POST['start_date'],

        $_POST['end_date'],

        $_POST['creative_file'],

        $baseAmount,

        $rate['gst_percent'],

        $gstAmount,

        $totalAmount,

        $_SESSION['admin_id']

    ]);

    $message =
    'Advertisement Booking Created';

}

if(isset($_GET['approve']))
{

    $stmt=$pdo->prepare("
    UPDATE advertisement_bookings
    SET status='approved'
    WHERE id=?
    ");

    $stmt->execute([
    (int)$_GET['approve']
    ]);

}

$clients = $pdo->query("
SELECT *
FROM advertisement_clients
WHERE status='active'
ORDER BY company_name
")->fetchAll();

$rates = $pdo->query("
SELECT

r.*,

c.category_name

FROM advertisement_rate_card r

LEFT JOIN advertisement_categories c
ON c.id=r.category_id

ORDER BY c.category_name
")->fetchAll();

$bookings = $pdo->query("
SELECT

b.*,

c.company_name

FROM advertisement_bookings b

LEFT JOIN advertisement_clients c
ON c.id=b.client_id

ORDER BY b.id DESC

LIMIT 500

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Booking Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Create Advertisement Booking

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-3 mb-3">

<label>Client</label>

<select
name="client_id"
class="form-control"
required>

<?php foreach($clients as $client): ?>

<option
value="<?= $client['id']; ?>">

<?= htmlspecialchars(
$client['company_name']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Rate Card</label>

<select
name="rate_card_id"
class="form-control"
required>

<?php foreach($rates as $rate): ?>

<option
value="<?= $rate['id']; ?>">

<?= htmlspecialchars(
$rate['category_name']
); ?>

-

₹<?= number_format(
$rate['final_rate'],
2
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-2 mb-3">

<label>Start Date</label>

<input
type="date"
name="start_date"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label>End Date</label>

<input
type="date"
name="end_date"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label>Creative</label>

<input
type="text"
name="creative_file"
class="form-control">

</div>

</div>

<button
type="submit"
name="save_booking"
class="btn btn-success">

Create Booking

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Booking Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Booking No</th>
<th>Client</th>
<th>Amount</th>
<th>GST</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($bookings as $booking): ?>

<tr>

<td>

<?= $booking['booking_no']; ?>

</td>

<td>

<?= htmlspecialchars(
$booking['company_name']
); ?>

</td>

<td>

₹<?= number_format(
$booking['amount'],
2
); ?>

</td>

<td>

₹<?= number_format(
$booking['gst_amount'],
2
); ?>

</td>

<td>

₹<?= number_format(
$booking['total_amount'],
2
); ?>

</td>

<td>

<?php if(
$booking['status']
=='approved'
): ?>

<span class="badge bg-success">

Approved

</span>

<?php else: ?>

<span class="badge bg-warning">

Pending

</span>

<?php endif; ?>

</td>

<td>

<?php if(
$booking['status']
!='approved'
): ?>

<a
href="?approve=<?= $booking['id']; ?>"
class="btn btn-success btn-sm">

Approve

</a>

<?php endif; ?>

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

Booking Workflow

</div>

<div class="card-body">

<pre>
Client Selected
        ↓
Rate Card Applied
        ↓
GST Calculated
        ↓
Booking Created
        ↓
Approval Process
        ↓
Invoice Generated
        ↓
Payment Collection
        ↓
Commission Distribution
        ↓
Finance Ledger Entry
</pre>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>