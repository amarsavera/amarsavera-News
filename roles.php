<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(
!isset($_SESSION['admin_id'])
||
$_SESSION['role']!='super_admin'
)
{
    die('Access Denied');
}

$message='';

/*
|--------------------------------------------------------------------------
| Create Role
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_role']))
{

    $stmt=$pdo->prepare("
    INSERT INTO roles
    (

    role_name,
    role_code,

    hierarchy_level,

    description,

    status,

    created_by,
    created_at

    )

    VALUES
    (

    ?,
    ?,

    ?,

    ?,

    'active',

    ?,
    NOW()

    )

    ");

    $stmt->execute([

        $_POST['role_name'],

        strtoupper(
        $_POST['role_code']
        ),

        $_POST['hierarchy_level'],

        $_POST['description'],

        $_SESSION['admin_id']

    ]);

    $message='Role Created Successfully';

}

$roles=$pdo->query("
SELECT *
FROM roles
ORDER BY hierarchy_level DESC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Role Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Create New Role

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Role Name</label>

<input
type="text"
name="role_name"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label>Role Code</label>

<input
type="text"
name="role_code"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label>Hierarchy</label>

<input
type="number"
name="hierarchy_level"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label>Description</label>

<input
type="text"
name="description"
class="form-control">

</div>

</div>

<button
type="submit"
name="save_role"
class="btn btn-success">

Create Role

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Recommended Amar Savera Roles

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Level</th>
<th>Role</th>
</tr>

<tr><td>100</td><td>Super Admin</td></tr>
<tr><td>95</td><td>Managing Director</td></tr>
<tr><td>90</td><td>Editor In Chief</td></tr>
<tr><td>85</td><td>State Head</td></tr>
<tr><td>80</td><td>Division Head</td></tr>
<tr><td>75</td><td>District Head</td></tr>
<tr><td>70</td><td>Bureau Chief</td></tr>
<tr><td>60</td><td>Senior Reporter</td></tr>
<tr><td>50</td><td>Reporter</td></tr>
<tr><td>45</td><td>Advertisement Manager</td></tr>
<tr><td>40</td><td>Advertisement Executive</td></tr>
<tr><td>35</td><td>HR Manager</td></tr>
<tr><td>30</td><td>Finance Manager</td></tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Role Directory

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Role</th>
<th>Code</th>
<th>Level</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($roles as $role): ?>

<tr>

<td><?= $role['id']; ?></td>

<td>
<?= htmlspecialchars(
$role['role_name']
); ?>
</td>

<td>
<?= htmlspecialchars(
$role['role_code']
); ?>
</td>

<td>
<?= $role['hierarchy_level']; ?>
</td>

<td>

<span class="badge bg-success">

<?= ucfirst(
$role['status']
); ?>

</span>

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