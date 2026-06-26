<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
session_start();
}

if(!isset($_SESSION['admin_id'])){
header("Location: ../index.php");
exit;
}

$id=(int)($_GET['id'] ?? 0);

$stmt=$pdo->prepare("
SELECT *
FROM reporters
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$reporter=$stmt->fetch();

if(!$reporter){
die('Reporter Not Found');
}

$states=$pdo->query("
SELECT *
FROM states
WHERE status='active'
ORDER BY state_name
")->fetchAll();

$designations=$pdo->query("
SELECT *
FROM designations
WHERE status='active'
ORDER BY designation_level
")->fetchAll();

if($_SERVER['REQUEST_METHOD']=='POST')
{

$update=$pdo->prepare("
UPDATE reporters
SET
name=?,
mobile=?,
email=?,
state_id=?,
designation_id=?,
status=?
WHERE id=?
");

$update->execute([

$_POST['name'],
$_POST['mobile'],
$_POST['email'],
$_POST['state_id'],
$_POST['designation_id'],
$_POST['status'],
$id

]);

header("Location:index.php");
exit;

}

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Edit Reporter

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Name</label>

<input
type="text"
name="name"
class="form-control"
value="<?= htmlspecialchars($reporter['name']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Mobile</label>

<input
type="text"
name="mobile"
class="form-control"
value="<?= htmlspecialchars($reporter['mobile']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($reporter['email']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>State</label>

<select
name="state_id"
class="form-control">

<?php foreach($states as $state): ?>

<option
value="<?= $state['id']; ?>"
<?= $reporter['state_id']==$state['id']?'selected':''; ?>>

<?= htmlspecialchars($state['state_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Designation</label>

<select
name="designation_id"
class="form-control">

<?php foreach($designations as $des): ?>

<option
value="<?= $des['id']; ?>"
<?= $reporter['designation_id']==$des['id']?'selected':''; ?>>

<?= htmlspecialchars($des['designation_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="active">Active</option>
<option value="inactive">Inactive</option>

</select>

</div>

</div>

<button
class="btn btn-success">

Update Reporter

</button>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>