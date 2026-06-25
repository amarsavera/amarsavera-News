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

$versions = $pdo->prepare("
SELECT
av.*,
u.name

FROM advertisement_versions av

LEFT JOIN users u
ON u.id=av.uploaded_by

WHERE av.advertisement_id=?

ORDER BY av.version_no DESC
");

$versions->execute([$id]);

$records = $versions->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-dark text-white">

Version History

</div>

<div class="card-body">

<h5>

<?= htmlspecialchars(
$advertisement['title']
); ?>

</h5>

<hr>

<table class="table table-bordered">

<thead>

<tr>

<th>Version</th>
<th>File</th>
<th>Designer</th>
<th>Remark</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($records as $row): ?>

<tr>

<td>

V<?= $row['version_no']; ?>

</td>

<td>

<a
target="_blank"
href="../../uploads/advertisements/<?= $row['design_file']; ?>">

View File

</a>

</td>

<td>

<?= htmlspecialchars(
$row['name']
); ?>

</td>

<td>

<?= nl2br(
htmlspecialchars(
$row['remarks']
)
); ?>

</td>

<td>

<?= $row['created_at']; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>