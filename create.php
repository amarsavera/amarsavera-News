<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$plans = $pdo->query("
SELECT *
FROM subscription_plans
WHERE status=1
ORDER BY plan_name
")->fetchAll();

if($_SERVER['REQUEST_METHOD']=='POST'){

    $nextId = $pdo->query("
    SELECT IFNULL(MAX(id),0)+1
    FROM subscribers
    ")->fetchColumn();

    $uid = 'SGN'.str_pad(
        $nextId,
        7,
        '0',
        STR_PAD_LEFT
    );

    $stmt = $pdo->prepare("
    INSERT INTO subscribers
    (
        uid,
        source_system,
        name,
        mobile,
        email,
        plan_id,
        status
    )
    VALUES
    (
        ?,?,?,?,?,?,?
    )
    ");

    $stmt->execute([

        $uid,
        'AMAR_SAVERA',
        $_POST['name'],
        $_POST['mobile'],
        $_POST['email'],
        $_POST['plan_id'],
        $_POST['status']

    ]);

    header("Location:index.php");
    exit;
}

include '../layout/header.php';
?><div class="container-fluid"><div class="card shadow-sm"><div class="card-header bg-success text-white">Add Subscriber

</div><div class="card-body"><form method="post"><div class="row"><div class="col-md-6 mb-3"><label>Name</label>

<input
type="text"
name="name"
class="form-control"
required>

</div><div class="col-md-6 mb-3"><label>Mobile</label>

<input
type="text"
name="mobile"
class="form-control"
required>

</div><div class="col-md-6 mb-3"><label>Email</label>

<input
type="email"
name="email"
class="form-control">

</div><div class="col-md-6 mb-3"><label>Plan</label>

<select
name="plan_id"
class="form-control">

<?php foreach($plans as $plan): ?><option value="<?= $plan['id']; ?>"><?= htmlspecialchars($plan['plan_name']); ?></option><?php endforeach; ?></select></div><div class="col-md-6 mb-3"><label>Status</label>

<select
name="status"
class="form-control">

<option value="active">
Active
</option><option value="inactive">
Inactive
</option></select></div></div><button
type="submit"
class="btn btn-success">

Save Subscriber

</button></form></div></div></div><?php include '../layout/footer.php'; ?>