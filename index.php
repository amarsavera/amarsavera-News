<?php

require_once '../../includes/config.php';
require_once '../includes/auth.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    header("Location: /admin/index.php");
    exit;
}
$list=$pdo->query("
SELECT *
FROM wallet_transactions
ORDER BY id DESC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<table class="table table-bordered">

<tr>

<th>ID</th>
<th>User</th>
<th>Amount</th>
<th>Type</th>

</tr>

<?php foreach($list as $row): ?>

<tr>

<td><?= $row['id']; ?></td>
<td><?= $row['user_id']; ?></td>
<td><?= $row['amount']; ?></td>
<td><?= $row['transaction_type']; ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php include '../layout/footer.php'; ?>