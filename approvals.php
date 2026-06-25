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

$message='';

/*
|--------------------------------------------------------------------------
| Approve Advertisement
|--------------------------------------------------------------------------
*/

if(isset($_GET['approve']))
{

    $stmt=$pdo->prepare("
    UPDATE advertisement_bookings
    SET

    status='approved',

    approved_by=?,

    approved_at=NOW()

    WHERE id=?
    ");

    $stmt->execute([

        $_SESSION['admin_id'],

        (int)$_GET['approve']

    ]);

    $message =
    'Advertisement Approved Successfully';

}

/*
|--------------------------------------------------------------------------
| Reject Advertisement
|--------------------------------------------------------------------------
*/

if(isset($_POST['reject_ad']))
{

    $stmt=$pdo->prepare("
    UPDATE advertisement_bookings
    SET

    status='rejected',

    rejection_reason=?,

    approved_by=?,

    approved_at=NOW()

    WHERE id=?
    ");

    $stmt->execute([

        $_POST['rejection_reason'],

        $_SESSION['admin_id'],

        $_POST['booking_id']

    ]);

    $message =
    'Advertisement Rejected';

}

$bookings=$pdo->query("
SELECT

b.*,

c.company_name

FROM advertisement_bookings b

LEFT JOIN advertisement_clients c
ON c.id=b.client_id

ORDER BY b.id DESC

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Approval Center

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Pending & Approved Advertisements

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Booking No</th>
<th>Client</th>
<th>Amount</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($bookings as $booking): ?>

<tr>

<td>

<?= $booking['booking_no']; ?>

</td>

<td>

<?= htmlspecialchars(
$booking['company_name']
); ?>

</td>

<td>

₹<?= number_format(
$booking['total_amount'],
2
); ?>

</td>

<td>

<?php

if($booking['status']=='approved')
{
echo '<span class="badge bg-success">Approved</span>';
}
elseif($booking['status']=='rejected')
{
echo '<span class="badge bg-danger">Rejected</span>';
}
else
{
echo '<span class="badge bg-warning">Pending</span>';
}

?>

</td>

<td>

<?php if($booking['status']=='pending'): ?>

<a
href="?approve=<?= $booking['id']; ?>"
class="btn btn-success btn-sm">

Approve

</a>

<button
class="btn btn-danger btn-sm"
data-bs-toggle="modal"
data-bs-target="#reject<?= $booking['id']; ?>">

Reject

</button>

<?php endif; ?>

</td>

</tr>

<!-- Reject Modal -->

<div
class="modal fade"
id="reject<?= $booking['id']; ?>">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST">

<input
type="hidden"
name="booking_id"
value="<?= $booking['id']; ?>">

<div class="modal-header">

<h5>

Reject Advertisement

</h5>

</div>

<div class="modal-body">

<textarea
name="rejection_reason"
class="form-control"
required></textarea>

</div>

<div class="modal-footer">

<button
type="submit"
name="reject_ad"
class="btn btn-danger">

Reject

</button>

</div>

</form>

</div>

</div>

</div>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Approval Workflow

</div>

<div class="card-body">

<pre>
Advertisement Booking
          ↓
Creative Verification
          ↓
Advertisement Manager
          ↓
Finance Verification
          ↓
State Head Approval
          ↓
Publication Approval
          ↓
Live Advertisement
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Approval Rules

</div>

<div class="card-body">

<ul>

<li>Creative must be approved first</li>

<li>Finance verification mandatory</li>

<li>State Head approval required for premium ads</li>

<li>All approvals recorded in audit logs</li>

<li>Auto notification on approval/rejection</li>

<li>No advertisement can go live without approval</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>