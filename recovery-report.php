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

$records = $pdo->query("
SELECT

ab.*,

a.company_name,
a.mobile,
a.contact_person

FROM advertisement_bookings ab

LEFT JOIN advertisers a
ON a.id=ab.advertiser_id

WHERE ab.payment_status!='paid'
OR ab.payment_status IS NULL

ORDER BY ab.created_at ASC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="d-flex justify-content-between mb-3">

<h3>

Recovery Report

</h3>

<a
href="export-outstanding-report.php"
class="btn btn-danger">

Export Report

</a>

</div>

<div class="card shadow">

<div class="card-header bg-warning">

Outstanding Recovery List

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Booking Code</th>

<th>Company</th>

<th>Contact Person</th>

<th>Mobile</th>

<th>Total Amount</th>

<th>Payment Status</th>

<th>Pending Days</th>

<th>Ageing</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($records as $row): ?>

<?php

$days =
floor(

(
time()
-
strtotime(
$row['created_at']
)
)

/
86400

);

$ageing = '';

if($days <= 30)
{
$ageing='0-30 Days';
}
elseif($days <= 60)
{
$ageing='31-60 Days';
}
elseif($days <= 90)
{
$ageing='61-90 Days';
}
else
{
$ageing='90+ Days';
}

?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= htmlspecialchars(
$row['booking_code']
); ?>

</td>

<td>

<?= htmlspecialchars(
$row['company_name']
); ?>

</td>

<td>

<?= htmlspecialchars(
$row['contact_person']
); ?>

</td>

<td>

<?= htmlspecialchars(
$row['mobile']
); ?>

</td>

<td>

₹<?= number_format(
$row['total_amount'],
2
); ?>

</td>

<td>

<?= htmlspecialchars(
$row['payment_status']
?? 'Pending'
); ?>

</td>

<td>

<?= $days; ?>

</