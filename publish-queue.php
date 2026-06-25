<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}

$list = $pdo->query("
SELECT
a.*,
p.publish_date,
p.publish_time,
pos.position_name

FROM advertisements a

LEFT JOIN ad_target_locations p
ON p.advertisement_id=a.id

LEFT JOIN ad_positions pos
ON pos.id=p.position_id

WHERE a.status='placement_done'

ORDER BY p.publish_date ASC,
p.publish_time ASC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Advertisement Publish Queue

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>ID</th>
<th>Advertisement</th>
<th>Position</th>
<th>Publish Date</th>
<th>Publish Time</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($list as $row): ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= htmlspecialchars($row['title']); ?>

</td>

<td>

<?= htmlspecialchars($row['position_name']); ?>

</td>

<td>

<?= $row['publish_date']; ?>

</td>

<td>

<?= $row['publish_time']; ?>

</td>

<td>

<a
href="publish.php?id=<?= $row['id']; ?>"
class="btn btn-success btn-sm">

Publish

</a>

<a
href="history.php?id=<?= $row['id']; ?>"
class="btn btn-dark btn-sm">

History

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>