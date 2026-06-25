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
SELECT *
FROM advertisement_bookings
WHERE id=?
LIMIT 1
");

$stmt->execute([$bookingId]);

$booking = $stmt->fetch();

if(!$booking)
{
    die('Booking Not Found');
}

$payments = $pdo->prepare("
SELECT *
FROM payment_transactions
WHERE booking_id=?
ORDER BY id DESC
");

$payments->execute([$bookingId]);

$list = $payments->fetchAll();

$totalPaid = $pdo->prepare("
SELECT
IFNULL(SUM(payment_amount),0)
FROM payment_transactions
WHERE booking_id=?
AND payment_status='paid'
");

$totalPaid->execute([$bookingId]);

$totalReceived =
$totalPaid->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Payment History

</div>

<div class="card-body">

<div class="row mb-4">

<div class="col-md-4">

<h5>

Booking Code

</h5>

<p>

<?= htmlspecialchars(
$booking['booking_code']
); ?>

</p>

</div>

<div class="col-md-4">

<h5>

Booking Amount

</h5>

<p>

₹<?= number_format(
$booking['total_amount'],
2
); ?>

</p>

</div>

<div class="col-md-4">

<h5>

Total Received

</h5>

<p>

₹<?= number_format(
$totalReceived,
2
); ?>

</p>

</div>

</div>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Transaction ID</th>

<th>Mode</th>

<th>Amount</th>

<th>Status</th>

<th>Date</th>

<th>Created By</th>

</tr>

</thead>

<tbody>

<?php foreach($list as $row): ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= htmlspecialchars(
$row['transaction_id']
);
?>

</td>

<td>

<?= htmlspecialchars(
$row['payment_mode']
);
?>

</td>

<td>

₹<?= number_format(
$row['payment_amount'],
2
);
?>

</td>

<td>

<?php

if($row['payment_status']=='paid')
{
echo '<span class="badge bg-success">Paid</span>';
}
elseif($row['payment_status']=='partial')
{
echo '<span class="badge bg-warning">Partial</span>';
}
else
{
echo '<span class="badge bg-danger">Pending</span>';
}

?>

</td>

<td>

<?= date(
'd-m-Y',
strtotime(
$row['payment_date']
)
); ?>

</td>

<td>

<?= $row['created_by']; ?>

</td>

</tr>

<?php endforeach; ?>

<?php if(empty($list)): ?>

<tr>

<td colspan="7" class="text-center">

No Payment History Found

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<div class="mt-3">

<a
href="payment.php?id=<?= $bookingId; ?>"
class="btn btn-success">

Add Payment

</a>

<a
href="view.php?id=<?= $bookingId; ?>"
class="btn btn-secondary">

Back

</a>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>