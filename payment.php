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
| Save Payment
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_payment']))
{

    $bookingId =
    (int)$_POST['booking_id'];

    $amount =
    (float)$_POST['payment_amount'];

    $method =
    $_POST['payment_method'];

    $txnId =
    trim($_POST['transaction_id']);

    $booking = $pdo->prepare("
    SELECT *
    FROM advertisement_bookings
    WHERE id=?
    LIMIT 1
    ");

    $booking->execute([
    $bookingId
    ]);

    $bookingData =
    $booking->fetch();

    if($bookingData)
    {

        $payment = $pdo->prepare("
        INSERT INTO payment_transactions
        (

        booking_id,
        executive_code,

        payment_amount,
        payment_method,
        transaction_id,

        payment_date,
        created_by,
        created_at

        )

        VALUES
        (

        ?,?,
        ?,?,
        ?,
        CURDATE(),
        ?,
        NOW()

        )
        ");

        $payment->execute([

            $bookingId,

            $bookingData['executive_code'],

            $amount,

            $method,

            $txnId,

            $_SESSION['admin_id']

        ]);

        /*
        Update Booking
        */

        $totalPaid = $pdo->prepare("
        SELECT
        IFNULL(SUM(payment_amount),0)
        FROM payment_transactions
        WHERE booking_id=?
        ");

        $totalPaid->execute([
        $bookingId
        ]);

        $paid =
        $totalPaid->fetchColumn();

        $balance =
        $bookingData['total_amount']
        -
        $paid;

        $status =
        ($balance<=0)
        ?
        'paid'
        :
        'partial';

        $update = $pdo->prepare("
        UPDATE advertisement_bookings
        SET

        paid_amount=?,
        balance_amount=?,
        payment_status=?

        WHERE id=?

        ");

        $update->execute([

            $paid,

            max(0,$balance),

            $status,

            $bookingId

        ]);

        $message =
        'Payment Recorded Successfully';

    }

}

$bookings = $pdo->query("
SELECT

id,
booking_number,
total_amount

FROM advertisement_bookings

ORDER BY id DESC

")->fetchAll();

$payments = $pdo->query("
SELECT

pt.*,

ab.booking_number

FROM payment_transactions pt

LEFT JOIN advertisement_bookings ab
ON ab.id=pt.booking_id

ORDER BY pt.id DESC

LIMIT 200

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Payments

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-success text-white">

Record Payment

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

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

<option
value="<?= $booking['id']; ?>">

<?= $booking['booking_number']; ?>

-

₹<?= number_format(
$booking['total_amount']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-4 mb-3">

<label>

Payment Amount

</label>

<input
type="number"
step="0.01"
name="payment_amount"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label>

Payment Method

</label>

<select
name="payment_method"
class="form-control">

<option>Cash</option>
<option>UPI</option>
<option>Bank Transfer</option>
<option>Cheque</option>
<option>Razorpay</option>

</select>

</div>

<div class="col-md-12 mb-3">

<label>

Transaction ID

</label>

<input
type="text"
name="transaction_id"
class="form-control">

</div>

</div>

<button
type="submit"
name="save_payment"
class="btn btn-success">

Save Payment

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Payment History

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Date</th>
<th>Booking</th>
<th>Amount</th>
<th>Method</th>
<th>Transaction ID</th>

</tr>

</thead>

<tbody>

<?php foreach($payments as $row): ?>

<tr>

<td>

<?= $row['payment_date']; ?>

</td>

<td>

<?= htmlspecialchars(
$row['booking_number']
); ?>

</td>

<td>

₹<?= number_format(
$row['payment_amount'],
2
); ?>

</td>

<td>

<?= htmlspecialchars(
$row['payment_method']
); ?>

</td>

<td>

<?= htmlspecialchars(
$row['transaction_id']
); ?>

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