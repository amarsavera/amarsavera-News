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
| Save Permission
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_permission']))
{

    $stmt=$pdo->prepare("
    INSERT INTO role_permissions
    (

    role_id,

    module_name,

    can_view,
    can_create,
    can_edit,
    can_delete,

    can_approve,
    can_publish,

    can_export,

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

    ?,
    ?,

    ?,

    ?,
    NOW()

    )

    ");

    $stmt->execute([

        $_POST['role_id'],

        $_POST['module_name'],

        isset($_POST['can_view']) ? 1 : 0,
        isset($_POST['can_create']) ? 1 : 0,
        isset($_POST['can_edit']) ? 1 : 0,
        isset($_POST['can_delete']) ? 1 : 0,

        isset($_POST['can_approve']) ? 1 : 0,
        isset($_POST['can_publish']) ? 1 : 0,

        isset($_POST['can_export']) ? 1 : 0,

        $_SESSION['admin_id']

    ]);

    $message='Permission Assigned Successfully';

}

$roles=$pdo->query("
SELECT *
FROM roles
ORDER BY hierarchy_level DESC
")->fetchAll();

$permissions=$pdo->query("
SELECT

p.*,

r.role_name

FROM role_permissions p

LEFT JOIN roles r
ON r.id=p.role_id

ORDER BY p.id DESC

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Permission Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Assign Permissions

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

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

<div class="col-md-4 mb-3">

<label>Module</label>

<select
name="module_name"
class="form-control">

<option>news</option>
<option>advertisement</option>
<option>hrms</option>
<option>finance</option>
<option>users</option>
<option>settings</option>
<option>reports</option>
<option>api</option>

</select>

</div>

</div>

<div class="row">

<div class="col-md-2">

<label>

<input
type="checkbox"
name="can_view">

 View

</label>

</div>

<div class="col-md-2">

<label>

<input
type="checkbox"
name="can_create">

 Create

</label>

</div>

<div class="col-md-2">

<label>

<input
type="checkbox"
name="can_edit">

 Edit

</label>

</div>

<div class="col-md-2">

<label>

<input
type="checkbox"
name="can_delete">

 Delete

</label>

</div>

<div class="col-md-2">

<label>

<input
type="checkbox"
name="can_approve">

 Approve

</label>

</div>

<div class="col-md-2">

<label>

<input
type="checkbox"
name="can_publish">

 Publish

</label>

</div>

</div>

<div class="mt-3">

<label>

<input
type="checkbox"
name="can_export">

 Export Reports

</label>

</div>

<button
type="submit"
name="save_permission"
class="btn btn-success mt-3">

Save Permission

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Permission Matrix

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Role</th>
<th>Module</th>
<th>View</th>
<th>Create</th>
<th>Edit</th>
<th>Delete</th>
<th>Approve</th>
<th>Publish</th>
<th>Export</th>

</tr>

</thead>

<tbody>

<?php foreach($permissions as $row): ?>

<tr>

<td><?= htmlspecialchars($row['role_name']); ?></td>

<td><?= ucfirst($row['module_name']); ?></td>

<td><?= $row['can_view'] ? '✓' : '✗'; ?></td>
<td><?= $row['can_create'] ? '✓' : '✗'; ?></td>
<td><?= $row['can_edit'] ? '✓' : '✗'; ?></td>
<td><?= $row['can_delete'] ? '✓' : '✗'; ?></td>
<td><?= $row['can_approve'] ? '✓' : '✗'; ?></td>
<td><?= $row['can_publish'] ? '✓' : '✗'; ?></td>
<td><?= $row['can_export'] ? '✓' : '✗'; ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Amar Savera Permission Rules

</div>

<div class="card-body">

<pre>
Super Admin
→ Full Access

Managing Director
→ All Departments View

Editor In Chief
→ News + Approval Control

State Head
→ News Approval + Reporting

Division Head
→ Division News Control

District Head
→ District News Control

Reporter
→ News Create Only

Advertisement Manager
→ Revenue + Campaigns

HR Manager
→ Employees + Payroll

Finance Manager
→ Finance + Reports
</pre>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>