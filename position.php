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

$message='';

/*
|--------------------------------------------------------------------------
| Add Position
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_position']))
{

    $stmt=$pdo->prepare("
    INSERT INTO advertisement_positions
    (

    position_name,
    position_code,

    width,
    height,

    priority,

    status,

    created_at

    )

    VALUES
    (

    ?,?,
    ?,?,
    ?,
    'active',
    NOW()

    )
    ");

    $stmt->execute([

        $_POST['position_name'],
        $_POST['position_code'],

        $_POST['width'],
        $_POST['height'],

        $_POST['priority']

    ]);

    $message='Position Created Successfully';

}

$positions=$pdo->query("
SELECT *
FROM advertisement_positions
ORDER BY priority ASC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Positions

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Create Ad Position

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>

Position Name

</label>

<input
type="text"
name="position_name"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label>

Position Code

</label>

<input
type="text"
name="position_code"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label>

Width

</label>

<input
type="number"
name="width"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label>

Height

</label>

<input
type="number"
name="height"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>

Priority

</label>

<input
type="number"
name="priority"
value="1"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>

Recommended Positions

</label>

<select
class="form-control"
disabled>

<option>Homepage Top Banner</option>
<option>Homepage Sidebar</option>
<option>Article Top Banner</option>
<option>Article Middle Banner</option>
<option>Article Bottom Banner</option>
<option>Category Banner</option>
<option>Breaking News Banner</option>
<option>Sticky Footer Banner</option>
<option>Mobile Banner</option>

</select>

</div>

</div>

<button
type="submit"
name="save_position"
class="btn btn-success">

Save Position

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Advertisement Slots

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Position</th>
<th>Code</th>
<th>Size</th>
<th>Priority</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($positions as $row): ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= htmlspecialchars(
$row['position_name']
); ?>

</td>

<td>

<?= htmlspecialchars(
$row['position_code']
); ?>

</td>

<td>

<?= $row['width']; ?>

×

<?= $row['height']; ?>

</td>

<td>

<?= $row['priority']; ?>

</td>

<td>

<?php if($row['status']=='active'): ?>

<span class="badge bg-success">

Active

</span>

<?php else: ?>

<span class="badge bg-danger">

Inactive

</span>

<?php endif; ?>

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