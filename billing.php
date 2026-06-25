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
| Generate Advertisement Invoice
|--------------------------------------------------------------------------
*/

if(isset($_GET['generate_invoice']))
{

    $bookingId =
    (int)$_GET['generate_invoice'];

    $bookingQuery =
    $pdo->prepare("
    SELECT

    b.*,

    c.company_name,
    c.mobile,
    c.email

    FROM advertisement_bookings b

    LEFT JOIN advertisement_clients c
    ON c.id=b.client_id

    WHERE b.id=?

    LIMIT 1
    ");

    $bookingQuery->execute([
    $bookingId
    ]);

    $booking =
    $bookingQuery->fetch();

    if($booking)
    {

        $invoiceNo =
        'ADVBILL-'.
        date('Ym').
        '-'.
        rand(1000,9999);

        $stmt=$pdo->prepare("
        INSERT INTO finance_invoices
        (

        invoice_no,

        invoice_type,

        customer_name,

        customer_mobile,

        customer_email,

        amount,

        gst_percent,

        gst_amount,

        total_amount,

        invoice_status,

        created_by,

        created_at

        )

        VALUES
        (

        ?,

        'advertisement',

        ?,

        ?,

        ?,

        ?,

        ?,

        ?,

        ?,

        'unpaid',

        ?,

        NOW()

        )

        ");

        $stmt->execute([

            $invoiceNo,

            $booking['company_name'],

            $booking['mobile'],

            $booking['email'],

            $booking['amount'],

            $booking['gst_percent'],

            $booking['gst_amount'],

            $booking['total_amount'],

            $_SESSION['admin_id']

        ]);

        $message =
        'Advertisement Invoice Generated';

    }

}

$bookings=$pdo->query("
SELECT

b.*,

c.company_name

FROM advertisement_bookings b

LEFT JOIN advertisement_clients c
ON c.id=b.client_id

WHERE b.status='approved'

ORDER BY b.id DESC

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Billing

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Approved Advertisement Billing

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

<strong>

₹<?= number_format(
$booking['total_amount'],
2
); ?>

</strong>

</td>

<td>

<a
href="?generate_invoice=<?= $booking['id']; ?>"
class="btn btn-success btn-sm">

Generate Invoice

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Billing Features

</div>

<div class="card-body">

<ul>

<li>Advertisement Invoice Generation</li>

<li>GST Auto Calculation</li>

<li>Credit Billing Support</li>

<li>Cash Billing Support</li>

<li>UPI Collection Support</li>

<li>Razorpay Payment Integration</li>

<li>Finance ERP Integration</li>

<li>Receipt Generation</li>

<li>Outstanding Tracking</li>

<li>Auto Ledger Posting</li>

</ul>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Billing Workflow

</div>

<div class="card-body">

<pre>
Advertisement Approved
          ↓
Invoice Generated
          ↓
GST Applied
          ↓
Payment Collection
          ↓
Receipt Generated
          ↓
Finance Ledger Entry
          ↓
Revenue Dashboard Update
</pre>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>