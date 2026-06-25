<?php
session_start();

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}
?>

require_once '../../includes/config.php';

$id=(int)($_GET['id'] ?? 0);

$stmt=$pdo->prepare("
SELECT *
FROM advertisements
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$ad=$stmt->fetch();

if(!$ad){
die('Advertisement Not Found');
}

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card">

<div class="card-header bg-danger text-white">

Advertisement Details

</div>

<div class="card-body">

<h4>

<?= htmlspecialchars($ad['title']); ?>

</h4>

<hr>

<?php if(!empty($ad['image'])): ?>

<img
src="../../<?= $ad['image']; ?>"
class="img-fluid mb-3">

<?php endif; ?>

<p>

Status:
<strong>

<?= $ad['status']; ?>

</strong>

</p>

<p>

Link:
<?= htmlspecialchars($ad['link_url']); ?>

</p>

<p>

Created:
<?= $ad['created_at']; ?>

</p>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>