<?php

require_once '../../includes/config.php';

session_start();

$id=(int)$_GET['id'];

$stmt=$pdo->prepare("
SELECT *
FROM advertisement_rate_cards
WHERE id=?
");

$stmt->execute([$id]);

$row=$stmt->fetch();

if($_SERVER['REQUEST_METHOD']=='POST')
{

$stmt=$pdo->prepare("
UPDATE advertisement_rate_cards
SET
title=?,
price=?
WHERE id=?
");

$stmt->execute([

$_POST['title'],
$_POST['price'],
$id

]);

header("Location:index.php");
exit;

}

include '../layout/header.php';

?>

<form method="POST">

<input
type="text"
name="title"
value="<?= htmlspecialchars($row['title']); ?>"
class="form-control mb-3">

<input
type="number"
name="price"
value="<?= $row['price']; ?>"
class="form-control mb-3">

<button
class="btn btn-danger">

Update

</button>

</form>

<?php include '../layout/footer.php'; ?>