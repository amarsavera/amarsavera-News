<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    header("Location: /admin/index.php");
    exit;
}

$totalRevenue=$pdo->query("
SELECT SUM(amount)
FROM payment_transactions
WHERE status='success'
")->fetchColumn();

?>

<?php include '../layout/header.php'; ?>

<div class="container-fluid">

<div class="card">

<div class="card-header bg-success text-white">

Revenue Dashboard

</div>

<div class="card-body">

<h2>

₹<?= number_format($totalRevenue,2); ?>

</h2>

<p>

Total Revenue

</p>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>