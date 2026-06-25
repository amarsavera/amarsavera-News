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

$today = date('Y-m-d');

$outstandingInvoices = $pdo->query("
SELECT

i.*,

DATEDIFF(CURDATE(),DATE(i.created_at)) as due_days

FROM finance_invoices i

WHERE i.invoice_status='unpaid'

ORDER BY i.created_at ASC

")->fetchAll();

$totalOutstanding = $pdo->query("
SELECT IFNULL(SUM(total_amount),0)
FROM finance_invoices
WHERE invoice_status='unpaid'
")->fetchColumn();

$totalCollected = $pdo->query("
SELECT IFNULL(SUM(amount),0)
FROM finance_payments
WHERE payment_status='received'
")->fetchColumn();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Collection & Recovery Management

</h3>

<div class="row">

<div class="col-md-4">

<div class="card border-danger">

<div class="card-body text-center">

<h4>

₹<?= number_format(
$totalOutstanding,
2
); ?>

</h4>

<p>

Outstanding Amount

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-success">

<div class="card-body text-center">

<h4>

₹<?= number_format(
$totalCollected,
2
); ?>

</h4>

<p>

Collected Revenue

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-warning">

<div class="card-body text-center">

<h4>

<?= count(
$outstandingInvoices
); ?>

</h4>

<p>

Pending Invoices

</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Outstanding Invoice Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Invoice No</th>
<th>Customer</th>
<th>Amount</th>
<th>Due Days</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($outstandingInvoices as $invoice): ?>

<tr>

<td>

<?= $invoice['invoice_no']; ?>

</td>

<td>

<?= htmlspecialchars(
$invoice['customer_name']
); ?>

</td>

<td>

₹<?= number_format(
$invoice['total_amount'],
2
); ?>

</td>

<td>

<?= $invoice['due_days']; ?>

Days

</td>

<td>

<span class="badge bg-danger">

Outstanding

</span>

</td>

<td>

<a
href="send-reminder.php?id=<?= $invoice['id']; ?>"
class="btn btn-warning btn-sm">

Send Reminder

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Collection Performance

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Total Outstanding</th>
<td>
₹<?= number_format(
$totalOutstanding,
2
); ?>
</td>
</tr>

<tr>
<th>Total Recovery</th>
<td>
₹<?= number_format(
$totalCollected,
2
); ?>
</td>
</tr>

<tr>
<th>Collection Rate</th>
<td>

<?php

if($totalOutstanding>0)
{
echo round(
($totalCollected/
($totalCollected+$totalOutstanding))*100,
2
);
}
else
{
echo '100';
}

?>

%

</td>
</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Recovery Workflow

</div>

<div class="card-body">

<pre>
Invoice Generated
        ↓
Outstanding Created
        ↓
SMS Reminder
        ↓
WhatsApp Reminder
        ↓
Collection Follow-up
        ↓
Payment Received
        ↓
Receipt Generated
        ↓
Collection Closed
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Collection Features

</div>

<div class="card-body">

<ul>

<li>Outstanding Tracking</li>

<li>Automatic Due Calculation</li>

<li>SMS Reminder Integration</li>

<li>WhatsApp Reminder Integration</li>

<li>Collection Executive Tracking</li>

<li>Recovery Reports</li>

<li>Collection Incentive Calculation</li>

<li>Finance ERP Integration</li>

<li>Real-Time Recovery Dashboard</li>

<li>Client Follow-up History</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>