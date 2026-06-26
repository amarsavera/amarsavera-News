<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

if($_SERVER['REQUEST_METHOD']=='POST'){

    $stmt = $pdo->prepare("
    INSERT INTO states
    (
        state_name,
        state_code,
        status
    )
    VALUES
    (
        ?,?,?
    )
    ");

    $stmt->execute([

        trim($_POST['state_name']),
        trim($_POST['state_code']),
        $_POST['status']

    ]);

    header("Location:index.php");
    exit;
}

include '../layout/header.php';
?>

<div class="container-fluid">

<div class="card">

<div class="card-header bg-success text-white">
Add State
</div>

<div class="card-body">

<form method="post">

<div class="mb-3">

<label>State Name</label>

<input
type="text"
name="state_name"
class="form-control"
required>

</div>

<div class="mb-3">

<label>State Code</label>

<input
type="text"
name="state_code"
class="form-control">

</div>

<div class="mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="active">Active</option>
<option value="inactive">Inactive</option>

</select>

</div>

<button
type="submit"
class="btn btn-success">

Save State

</button>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>