<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id']))
{
exit;
}

if($_SERVER['REQUEST_METHOD']=='POST')
{

$module=$_POST['module_name'];
$recordId=(int)$_POST['record_id'];
$action=$_POST['action'];

$stmt=$pdo->prepare("
INSERT INTO admin_permissions_override
(
admin_id,
module_name,
record_id,
action_name,
remarks,
created_at
)
VALUES
(
?,?,?,?,?,
NOW()
)
");

$stmt->execute([

$_SESSION['admin_id'],
$module,
$recordId,
$action,
$_POST['remarks']

]);

$log=$pdo->prepare("
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
$module,
$action,
$recordId,
$_POST['remarks'],
$_SERVER['REMOTE_ADDR']

]);

}

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card">

<div class="card-header bg-danger text-white">

Super Admin Override

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Module</label>

<input
type="text"
name="module_name"
class="form-control">

</div>

<div class="mb-3">

<label>Record ID</label>

<input
type="number"
name="record_id"
class="form-control">

</div>

<div class="mb-3">

<label>Action</label>

<select
name="action"
class="form-control">

<option value="force_approve">
Force Approve
</option>

<option value="force_reject">
Force Reject
</option>

<option value="force_publish">
Force Publish
</option>

<option value="grant_access">
Grant Access
</option>

<option value="remove_access">
Remove Access
</option>

</select>

</div>

<div class="mb-3">

<label>Remarks</label>

<textarea
name="remarks"
class="form-control"></textarea>

</div>

<button
class="btn btn-danger">

Execute Override

</button>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>