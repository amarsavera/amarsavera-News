<?php

require_once '../../includes/config.php';

session_start();

$list=$pdo->query("
SELECT *
FROM advertisement_rate_cards
ORDER BY id DESC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card">

<div class="card-header bg-danger text-white">

Advertisement Rate Card

</div>

<div class="card-body">

<a
href="create.php"
class="btn btn-danger mb-3">

Add Rate

</a>

<table class="table table-bordered">

<tr>

<th>Title</th>
<th>Price</th>
<th>Action</th>

</tr>

<?php foreach($list as $row): ?>

<tr>

<td><?= htmlspecialchars($row['title']); ?></td>

<td>₹<?= $row['price']; ?></td>

<td>

<a
href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>