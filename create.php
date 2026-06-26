<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

if($_SERVER['REQUEST_METHOD']=='POST'){

    $stmt = $pdo->prepare("
    INSERT INTO subscription_plans
    (
        plan_name,
        amount,
        duration_days,
        status
    )
    VALUES
    (
        ?,?,?,?
    )
    ");

    $stmt->execute([

        $_POST['plan_name'],
        $_POST['amount'],
        $_POST['duration_days'],
        $_POST['status']

    ]);

    $recordId = $pdo->lastInsertId();

    $log = $pdo->prepare("
    INSERT INTO activity_logs
    (
        user_type,
        user_id,
        module_name,
        action_name,
        record_id,
        remarks,
        ip_address
    )
    VALUES
    (
        ?,?,?,?,?,?,?
    )
    ");

    $log->execute([
        'admin',
        $_SESSION['admin_id'],
        'Subscription Plans',
        'Create Plan',
        $recordId,
        'New Subscription Plan Created',
        $_SERVER['REMOTE_ADDR']
    ]);

    header("Location:index.php");
    exit;
}

include '../layout/header.php';
?><div class="container-fluid"><div class="card shadow-sm"><div class="card-header bg-success text-white">Add Subscription Plan

</div><div class="card-body"><form method="post"><div class="mb-3"><label>Plan Name</label>

<input
type="text"
name="plan_name"
class="form-control"
required>

</div><div class="mb-3"><label>Amount</label>

<input
type="number"
step="0.01"
name="amount"
class="form-control"
required>

</div><div class="mb-3"><label>Duration (Days)</label>

<input
type="number"
name="duration_days"
class="form-control"
required>

</div><div class="mb-3"><label>Status</label>

<select
name="status"
class="form-control">

<option value="1">Active</option>
<option value="0">Inactive</option></select></div><button
type="submit"
class="btn btn-success">

Save Plan

</button></form></div></div></div><?php include '../layout/footer.php'; ?>