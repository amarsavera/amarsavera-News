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

$month = $_GET['month'] ?? date('m');
$year  = $_GET['year'] ?? date('Y');

$executives = $pdo->prepare("
SELECT

u.id,
u.name,

COUNT(pt.id) AS total_collections,

IFNULL(SUM(pt.payment_amount),0) AS total_amount

FROM payment_transactions pt

LEFT JOIN users u
ON u.id=pt.created_by

WHERE MONTH(pt.payment_date)=?
AND YEAR(pt.payment_date)=?

GROUP BY pt.created_by

ORDER BY total_amount DESC
");

$executives->execute([
$month,
$year
]);

$list = $executives->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="d-flex justify-content-between mb-3">

<h3>

Collection Performance Report

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

<div class="card shadow">

<div class="card-header bg-success text-white">

Executive Wise Collection

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Rank</th>

<th>Executive</th>

<th>Total Collections</th>

<th>Total Amount</th>

<th>Performance</th>

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
$row['name']
?? 'Unknown'
); ?>

</td>

<td>

<?= $row['total_collections']; ?>

</td>

<td>

₹<?= number_format(
$row['total_amount'],
2
); ?>

</td>

<td>

<?php

if($row['total_amount']>=100000)
{
echo '<span class="badge bg-success">Excellent</span>';
}
elseif($row['total_amount']>=50000)
{
echo '<span class="badge bg-primary">Good</span>';
}
elseif($row['total_amount']>=10000)
{
echo '<span class="badge bg-warning">Average</span>';
}
else
{
echo '<span class="badge bg-danger">Low</span>';
}

?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>