<?php

require_once '../includes/config.php';

$ads = $pdo->query("
SELECT *
FROM advertisements
ORDER BY id DESC
")->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Advertisements</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

<h3>Advertisement Management</h3>

<a href="edit.php" class="btn btn-success mb-3">
Add Advertisement
</a>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Image</th>
<th>Title</th>
<th>Position</th>
<th>Action</th>
</tr>

<?php foreach($ads as $ad): ?>

<tr>

<td><?= $ad['id']; ?></td>

<td>

<?php if(!empty($ad['image'])): ?>

<img
src="../<?= $ad['image']; ?>"
width="120">

<?php endif; ?>

</td>

<td><?= htmlspecialchars($ad['title']); ?></td>

<td><?= htmlspecialchars($ad['position']); ?></td>

<td>

<a
href="edit.php?id=<?= $ad['id']; ?>"
class="btn btn-primary btn-sm">

Edit

</a>

<a
href="delete.php?id=<?= $ad['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete?')">

Delete

</a>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</body>
</html>