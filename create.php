<?php

require_once '../../includes/config.php';

session_start();

if($_SERVER['REQUEST_METHOD']=='POST')
{

$stmt=$pdo->prepare("
INSERT INTO advertisement_rate_cards
(
title,
price
)
VALUES
(
?,?
)
");

$stmt->execute([

$_POST['title'],
$_POST['price']

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
class="form-control mb-3"
placeholder="Title">

<input
type="number"
name="price"
class="form-control mb-3"
placeholder="Price">

<button
class="btn btn-danger">

Save

</button>

</form>

<?php include '../layout/footer.php'; ?>