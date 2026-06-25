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

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT *
FROM advertisement_bookings
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$booking = $stmt->fetch();

if(!$booking)
{
    die('Booking Not Found');
}

if($_SERVER['REQUEST_METHOD']=='POST')
{

    $update = $pdo->prepare("
    UPDATE advertisement_bookings
    SET
    status='approved',
    approved_by=?,
    approved_at=NOW()
    WHERE id=?
    ");

    $update->execute([

        $_SESSION['admin_id'],

        $id

    ]);

    $log = $pdo->prepare("
    INSERT INTO approval_logs
    (
        module_name,
        record_id,
        user_id,
        action_type,
        remarks,
        created_at
    )
    VALUES
    (
        'Advertisement Booking',
        ?,
        ?,
        'approved',
        ?,
        NOW()
    )
    ");

    $log->execute([

        $id,

        $_SESSION['admin_id'],

        $_POST['remarks']

    ]);

    $activity = $pdo->prepare("
    INSERT INTO activity_logs
    (
        user_type,
        user_id,
        module_name,
        action_name,
        record_id,
        remarks,
        ip_address,
        created_at
    )
    VALUES
    (
        ?,?,?,?,?,?,?,NOW()
    )
    ");

    $activity->execute([

        'admin',

        $_SESSION['admin_id'],

        'Advertisement Booking',

        'Approve Booking',

        $id,

        $_POST['remarks'],

        $_SERVER['REMOTE_ADDR'] ?? ''

    ]);

    header(
    "Location:view.php?id=".$id
    );

    exit;
}

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-success text-white">

Approve Advertisement Booking

</div>

<div class="card-body">

<h5>

Booking Code :

<?= htmlspecialchars(
$booking['booking_code']
); ?>

</h5>

<hr>

<form method="post">

<div class="mb-3">

<label>

Approval Remarks

</label>

<textarea
name="remarks"
class="form-control"
rows="4"
required></textarea>

</div>

<button
type="submit"
class="btn btn-success">

Approve Booking

</button>

<a
href="view.php?id=<?= $booking['id']; ?>"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>