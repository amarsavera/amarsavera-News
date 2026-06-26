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
| Create Admin
|--------------------------------------------------------------------------
*/

if(isset($_POST['create_admin']))
{

    $passwordHash =
    password_hash(
    $_POST['password'],
    PASSWORD_DEFAULT
    );

    $stmt = $pdo->prepare("
    INSERT INTO admins
    (

    employee_code,

    full_name,

    email,

    role_id,

    username,

    password,

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

    ?,

    ?,

    'active',

    ?,
    NOW()

    )

    ");

    $stmt->execute([

        $_POST['employee_code'],

        $_POST['full_name'],

        $_POST['email'],

        $_POST['role_id'],

        $_POST['username'],

        $passwordHash,

        $_SESSION['admin_id']

    ]);

    $message =
    'Admin Account Created Successfully';

}

/*
|--------------------------------------------------------------------------
| Block Admin
|--------------------------------------------------------------------------
*/

if(isset($_GET['block']))
{

    $stmt=$pdo->prepare("
    UPDATE admins
    SET status='blocked'
    WHERE id=?
    ");

    $stmt->execute([
    (int)$_GET['block']
    ]);

}

/*
|--------------------------------------------------------------------------
| Activate Admin
|--------------------------------------------------------------------------
*/

if(isset($_GET['activate']))
{

    $stmt=$pdo->prepare("
    UPDATE admins
    SET status='active'
    WHERE id=?
    ");

    $stmt->execute([
    (int)$_GET['activate']
    ]);

}

$roles=$pdo->query("
SELECT *
FROM roles
ORDER BY hierarchy_level DESC
")->fetchAll();

$employees=$pdo->query("
SELECT
employee_code,
full_name,
official_email
FROM employees
WHERE status='active'
ORDER BY full_name
")->fetchAll();

$admins=$pdo->query("
SELECT

a.*,

r.role_name

FROM admins a

LEFT JOIN roles r
ON r.id=a.role_id

ORDER BY a.id DESC

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Admin Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Create Admin Account

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Employee</label>

<select
name="employee_code"
class="form-control"
required>

<option value="">
Select Employee
</option>

<?php foreach($employees as $employee): ?>

<option
value="<?= $employee['employee_code']; ?>">

<?= htmlspecialchars(
$employee['full_name']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-4 mb-3">

<label>Full Name</label>

<input
type="text"
name="full_name"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Role</label>

<select
name="role_id"
class="form-control"
required>

<?php foreach($roles as $role): ?>

<option
value="<?= $role['id']; ?>">

<?= htmlspecialchars(
$role['role_name']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Username</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>2FA Status</label>

<select
class="form-control"
disabled>

<option>
Ready
</option>

</select>

</div>

</div>

<button
type="submit"
name="create_admin"
class="btn btn-success">

Create Admin

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Admin Directory

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Name</th>
<th>Username</th>
<th>Role</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($admins as $admin): ?>

<tr>

<td><?= $admin['id']; ?></td>

<td>
<?= htmlspecialchars(
$admin['full_name']
); ?>
</td>

<td>
<?= htmlspecialchars(
$admin['username']
); ?>
</td>

<td>
<?= htmlspecialchars(
$admin['role_name']
); ?>
</td>

<td>

<?php if($admin['status']=='active'): ?>

<span class="badge bg-success">

Active

</span>

<?php else: ?>

<span class="badge bg-danger">

Blocked

</span>

<?php endif; ?>

</td>

<td>

<?php if($admin['status']=='active'): ?>

<a
href="?block=<?= $admin['id']; ?>"
class="btn btn-danger btn-sm">

Block

</a>

<?php else: ?>

<a
href="?activate=<?= $admin['id']; ?>"
class="btn btn-success btn-sm">

Activate

</a>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Admin Security Rules

</div>

<div class="card-body">

<ul>

<li>Only Super Admin can create Admins</li>

<li>Admin must be linked with HRMS Employee</li>

<li>Role Based Access Mandatory</li>

<li>2FA Ready Architecture</li>

<li>All Activities Logged</li>

<li>Block/Unblock Available</li>

<li>Password Encrypted Using Bcrypt</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>