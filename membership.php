<?php

require_once 'includes/config.php';

$plans=$pdo->query("
SELECT *
FROM membership_plans
ORDER BY price ASC
")->fetchAll();

include 'includes/header.php';

?>

<div class="container mt-4">

<h2>

Membership Plans

</h2>

<div class="row">

<?php foreach($plans as $plan): ?>

<div class="col-md-4 mb-4">

<div class="card shadow">

<div class="card-body text-center">

<h4>

<?= htmlspecialchars($plan['plan_name']); ?>

</h4>

<h2>

₹<?= $plan['price']; ?>

</h2>

<p>

<?= htmlspecialchars($plan['description']); ?>

</p>

</div>

</div>

</div>

<?php endforeach; ?>

</div>

</div>

<?php include 'includes/footer.php'; ?>