<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}

$employeeCode =
$_SESSION['employee_code'] ?? '';

/*
|--------------------------------------------------------------------------
| Wallet Summary
|--------------------------------------------------------------------------
*/

$wallet = $pdo->prepare("
SELECT

IFNULL(SUM(credit_amount),0) total_credit,

IFNULL(SUM(debit_amount),0) total_debit

FROM reporter_wallet

WHERE employee_code=?

");

$wallet->execute([
$employeeCode
]);

$walletData =
$wallet->fetch();

$totalCredit =
$walletData['total_credit'];

$totalDebit =
$walletData['total_debit'];

$balance =
$totalCredit - $totalDebit;

/*
|--------------------------------------------------------------------------
| Withdrawal Request
|--------------------------------------------------------------------------
*/

$message='';

if(isset($_POST['withdraw']))
{

    $amount =
    (float)$_POST['amount'];

    if($amount<=0)
    {
        $message =
        'Invalid Amount';
    }
    elseif($amount>$balance)
    {
        $message =
        'Insufficient Balance';
    }
    else
    {

        $request = $pdo->prepare("
        INSERT INTO withdrawal_requests
        (

        employee_code,
        amount,
        status,
        created_at

        )

        VALUES
        (

        ?,
        ?,
        'pending',
        NOW()

        )
        ");

        $request->execute([

            $employeeCode,
            $amount

        ]);

        $message =
        'Withdrawal Request Submitted';

    }

}

/*
|--------------------------------------------------------------------------
| Transactions
|--------------------------------------------------------------------------
*/

$transactions = $pdo->prepare("
SELECT *
FROM reporter_wallet
WHERE employee_code=?
ORDER BY id DESC
LIMIT 100
");

$transactions->execute([
$employeeCode
]);

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Reporter Wallet

</h3>

<?php if($message): ?>

<div class="alert alert-info">

<?= htmlspecialchars($message); ?>

</div>

<?php endif; ?>

<div class="row">

<div class="col-md-4">

<div class="card border-success">

<div class="card-body text-center">

<h2>

₹<?= number_format($totalCredit,2); ?>

</h2>

<p>

Total Earnings

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-danger">

<div class="card-body text-center">

<h2>

₹<?= number_format($totalDebit,2); ?>

</h2>

<p>

Total Withdrawals

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-primary">

<div class="card-body text-center">

<h2>

₹<?= number_format($balance,2); ?>

</h2>

<p>

Available Balance

</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Request Withdrawal

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>

Amount

</label>

<input
type="number"
step="0.01"
name="amount"
class="form-control"
required>

</div>

<button
type="submit"
name="withdraw"
class="btn btn-success">

Submit Request

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Wallet Transactions

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Date</th>
<th>Description</th>
<th>Credit</th>
<th>Debit</th>
<th>Balance Impact</th>

</tr>

</thead>

<tbody>

<?php foreach($transactions as $txn): ?>

<tr>

<td>

<?= $txn['created_at']; ?>

</td>

<td>

<?= htmlspecialchars(
$txn['description']
); ?>

</td>

<td>

₹<?= number_format(
$txn['credit_amount'],
2
); ?>

</td>

<td>

₹<?= number_format(
$txn['debit_amount'],
2
); ?>

</td>

<td>

<?=
number_format(
$txn['credit_amount']
-
$txn['debit_amount'],
2
);
?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>