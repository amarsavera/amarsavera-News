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

$year =
$_GET['year']
?? date('Y');

$stmt = $pdo->prepare("
SELECT

a.id,
a.company_name,

COUNT(ab.id) AS total_bookings,

IFNULL(SUM(ab.amount),0) AS booking_amount,

IFNULL(SUM(ab.gst_amount),0) AS gst_amount,

IFNULL(SUM(ab.total_amount),0) AS total_revenue,

SUM(
CASE
WHEN ab.payment_status!='paid'
OR ab.payment_status IS NULL
THEN ab.total_amount
ELSE 0
END
) AS outstanding

FROM advertisers a

LEFT JOIN advertisement_bookings ab
ON ab.advertiser_id=a.id

AND YEAR(ab.created_at)=?

GROUP BY a.id

ORDER BY total_revenue DESC

LIMIT 10
");

$stmt->execute([$year]);

$list = $stmt->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="d-flex justify-content-between mb-3">

<h3>

Top Advertisers Report

</h3>

<form method="GET">

<div class="input-group">

<input
type="number"
name="year"
value="<?= $year; ?>"
class="form-control">

<button
class="btn btn-primary">

Filter

</button>

</div>

</form>

</div>

<div class="card shadow">

<div class="card-header bg-success text-white">

Top 10 Advertisers

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Rank</th>

<th>Company</th>

<th>Total Bookings</th>

<th>Booking Amount</th>

<th>GST</th>

<th>Total Revenue</th>

<th>Outstanding</th>

</tr>

</thead>

<tbody>

<?php

$rank = 1;

foreach($list as $row):

?>

<tr>

<td>

<?= $rank++; ?>

</td>

<td>

<?= htmlspecialchars(
$row['company_name']
); ?>

</td>

<td>

<?= $row['total_bookings']; ?>

</td>

<td>

₹<?= number_format(
$row['booking_amount'],
2
); ?>

</td>

<td>

₹<?= number_format(
$row['gst_amount'],
2
); ?>

</td>

<td>

₹<?= number_format(
$row['total_revenue'],
2
); ?>

</td>

<td>

₹<?= number_format(
$row['outstanding'],
2
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