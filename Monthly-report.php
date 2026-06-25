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

$month =
$_GET['month']
?? date('m');

$year =
$_GET['year']
?? date('Y');

$stmt = $pdo->prepare("
SELECT

COUNT(*) AS total_bookings,

IFNULL(SUM(amount),0) AS total_amount,

IFNULL(SUM(gst_amount),0) AS total_gst,

IFNULL(SUM(total_amount),0) AS grand_total

FROM advertisement_bookings

WHERE MONTH(created_at)=?
AND YEAR(created_at)=?
");

$stmt->execute([

$month,
$year

]);

$summary =
$stmt->fetch();

$list = $pdo->prepare("
SELECT

ab.*,

a.company_name

FROM advertisement_bookings ab

LEFT JOIN advertisers a
ON a.id=ab.advertiser_id

WHERE MONTH(ab.created_at)=?
AND YEAR(ab.created_at)=?

ORDER BY ab.id DESC
");

$list->execute([

$month,
$year

]);

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="d-flex justify-content-between mb-3">

<h3>

Monthly Revenue Report

</h3>

<form method="GET">

<div class="row">

<div class="col">

<input
type="number"
name="month"
value="<?= $month; ?>"
min="1"
max="12"
class="form-control">

</div>

<div class="col">

<input
type="number"
name="year"
value="<?= $year; ?>"
class="form-control">

</div>

<div class="col">

<button
class="btn btn-primary">

Filter

</button>

</div>

</div>

</form>

</div>

<div class="row">

<div class="col-md-3">

<div class="card">

<div class="card-body text-center">

<h3>

<?= $summary['total_bookings']; ?>

</h3>

<p>

Total Bookings

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card">

<div class="card-body text-center">

<h3>

₹<?= number_format(
$summary['total_amount'],
2
); ?>

</h3>

<p>

Booking Amount

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card">

<div class="card-body text-center">

<h3>

₹<?= number_format(
$summary['total_gst'],
2
); ?>

</h3>

<p>

GST Collection

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card">

<div class="card-body text-center">

<h3>

₹<?= number_format(
$summary['grand_total'],
2
); ?>

</h3>

<p>

Total Revenue

</p>

</div>

</div>

</div>

</div>

<div class="card mt-4">

<div class="card-header bg-success text-white">

Monthly Booking Details

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Booking Code</th>
<th>Advertiser</th>
<th>Amount</th>
<th>GST</th>
<th>Total</th>
<th>Payment</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($list as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td>
<?= htmlspecialchars(
$row['booking_code']
); ?>
</td>

<td>
<?= htmlspecialchars(
$row['company_name']
);
?>
</td>

<td>
₹<?= number_format(
$row['amount'],
2
);
?>
</td>

<td>
₹<?= number_format(
$row['gst_amount'],
2
);
?>
</td>

<td>
₹<?= number_format(
$row['total_amount'],
2
);
?>
</td>

<td>
<?= htmlspecialchars(
$row['payment_status']
);
?>
</td>

<td>
<?= date(
'd-m-Y',
strtotime(
$row['created_at']
)
);
?>
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