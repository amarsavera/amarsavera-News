<?php
session_start();

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}
?>

require_once '../../includes/config.php';

$reporters = $pdo->query("
SELECT *
FROM reporters
ORDER BY id DESC
")->fetchAll();

include '../layout/header.php';
?>

<h3 class="mb-4">
रिपोर्टर प्रबंधन
</h3>

<a
href="add.php"
class="btn btn-success mb-3">

नया रिपोर्टर जोड़ें

</a>

<div class="card">

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>
<th>नाम</th>
<th>मोबाइल</th>
<th>जिला</th>
<th>स्थिति</th>
<th>कार्य</th>

</tr>

</thead>

<tbody>

<?php foreach($reporters as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['name']); ?></td>

<td><?= htmlspecialchars($row['mobile']); ?></td>

<td><?= htmlspecialchars($row['district']); ?></td>

<td>

<?php if($row['status']==1): ?>

<span class="badge bg-success">
सक्रिय
</span>

<?php else: ?>

<span class="badge bg-danger">
निष्क्रिय
</span>

<?php endif; ?>

</td>

<td>

<a
href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

संपादित करें

</a>

<a
href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm">

हटाएँ

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

<?php include '../layout/footer.php'; ?>