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

$totalOutstanding = $pdo->query("
SELECT
IFNULL(SUM(total_amount),0)
FROM advertisement_bookings
WHERE payment_status!='paid'
OR payment_status IS NULL
")->fetchColumn();

$dueToday = $pdo->query("
SELECT COUNT(*)
FROM booking_followups
WHERE next_followup_date=CURDATE()
")->fetchColumn();

$dueWeek = $pdo->query("
SELECT COUNT(*)
FROM booking_followups
WHERE next_followup_date
BETWEEN CURDATE()
AND DATE_ADD(CURDATE(),INTERVAL 7 DAY)
")->fetchColumn();

$dueMonth = $pdo->query("
SELECT COUNT(*)
FROM booking_followups
WHERE MONTH(next_followup_date)=MONTH(CURDATE())
AND YEAR(next_followup_date)=YEAR(CURDATE())
")->fetchColumn();

$topDefaulters = $pdo->query("
SELECT

a.company_name,

SUM(ab.total_amount) AS outstanding

FROM advertisement_bookings ab

LEFT JOIN advertisers a
ON a.id=ab.advertiser_id

WHERE ab.payment_status!='paid'
OR ab.payment_status IS NULL

GROUP BY ab.advertiser_id

ORDER BY outstanding DESC

LIMIT 10
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Recovery Dashboard

</h3>

<div class="row">

<div class="col-md-3 mb-3">

<div class="card border-danger">

<div class="card-body text-center">

<h3>

₹<?= number_format(
$totalOutstanding,
2
); ?>

</h3>

<p>

Total Outstanding

</p>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card border-warning">

<div class="card-body text-center">

<h3>

<?= $dueToday; ?>

</h3>

<p>

Due Today

</p>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card border-info">

<div class="card-body text-center">

<h3>

<?= $dueWeek; ?>

</h3>

<p>

Due This Week

</p>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card border-success">

<div class="card-body text-center">

<h3>

<?= $dueMonth; ?>

</h3>

<p>

Due This Month

</p>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-danger text-white">

Top Defaulters

</div>

<div class="card-body">

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>Rank</th>

<th>Company</th>

<th>Outstanding Amount</th>

</tr>

</thead>

<tbody>

<?php

$rank=1;

foreach($topDefaulters as $row):

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

<?php include '../layout/footer.php'; ?>