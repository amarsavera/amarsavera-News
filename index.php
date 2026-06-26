<?php

require_once '../../includes/config.php';

session_start();

$list=$pdo->query("
SELECT *
FROM subscription_plans
ORDER BY id DESC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card">

<div class="card-header bg-danger text-white">

Subscription Plans

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>Name</th>
<th>Price</th>
<th>Duration</th>

</tr>

<?php foreach($list as $row): ?>

<tr>

<td><?= htmlspecialchars($row['plan_name']); ?></td>

<td>₹<?= $row['price']; ?></td>

<td><?= $row['duration']; ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>