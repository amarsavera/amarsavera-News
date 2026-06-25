<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT *
FROM advertisements
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$advertisement = $stmt->fetch();

if(!$advertisement)
{
    die('Advertisement Not Found');
}

$history = $pdo->prepare("
SELECT *
FROM designer_approvals
WHERE advertisement_id=?
ORDER BY id DESC
");

$history->execute([$id]);

$records = $history->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-dark text-white">

Advertisement History

</div>

<div class="card-body">

<h5>

Ad Code :
<?= htmlspecialchars($advertisement['ad_code'] ?? $advertisement['id']); ?>

</h5>

<hr>

<div class="table-responsive">

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>
<th>Status</th>
<th>Remarks</th>
<th>Created By</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($records as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['status']); ?></td>

<td><?= nl2br(htmlspecialchars($row['remarks'])); ?></td>

<td><?= htmlspecialchars($row['created_by']); ?></td>

<td><?= $row['created_at']; ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>