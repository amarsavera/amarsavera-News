<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT *
FROM states
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$state = $stmt->fetch();

if(!$state){
    die('State Not Found');
}

if($_SERVER['REQUEST_METHOD']=='POST'){

    $update = $pdo->prepare("
    UPDATE states
    SET
    state_name=?,
    state_code=?,
    status=?
    WHERE id=?
    ");

    $update->execute([

        trim($_POST['state_name']),
        trim($_POST['state_code']),
        $_POST['status'],
        $id

    ]);

    header("Location:index.php");
    exit;
}

include '../layout/header.php';
?>

<div class="container-fluid">

<div class="card shadow-sm">

<div class="card-header bg-primary text-white">
Edit State
</div>

<div class="card-body">

<form method="post">

<div class="mb-3">

<label>State Name</label>

<input
type="text"
name="state_name"
class="form-control"
value="<?= htmlspecialchars($state['state_name']); ?>"
required>

</div>

<div class="mb-3">

<label>State Code</label>

<input
type="text"
name="state_code"
class="form-control"
value="<?= htmlspecialchars($state['state_code']); ?>">

</div>

<div class="mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="active"
<?= $state['status']=='active'?'selected':''; ?>>
Active
</option>

<option value="inactive"
<?= $state['status']=='inactive'?'selected':''; ?>>
Inactive
</option>

</select>

</div>

<button
type="submit"
class="btn btn-primary">

Update State

</button>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>