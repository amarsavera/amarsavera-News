<?php

require_once '../../../includes/config.php';
require_once '../../includes/auth.php';

session_start();

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../../index.php");
    exit;
}

$list = $pdo->query("
SELECT
n.*,
u.name AS reporter_name
FROM news n
LEFT JOIN users u
ON u.id=n.created_by
WHERE n.status='pending'
ORDER BY n.id DESC
")->fetchAll();

include '../../layout/header.php';

?>

<div class="container-fluid">

<div class="card">

<div class="card-header bg-warning">

Pending News Approval

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>ID</th>
<th>Title</th>
<th>Reporter</th>
<th>Action</th>

</tr>

<?php foreach($list as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['title']); ?></td>

<td><?= htmlspecialchars($row['reporter_name']); ?></td>

<td>

<a
href="review.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

Review

</a>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

<?php include '../../layout/footer.php'; ?>