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
| Add Advertisement Category
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_category']))
{

    $stmt = $pdo->prepare("
    INSERT INTO advertisement_categories
    (

    category_name,

    position_code,

    base_price,

    status,

    created_by,

    created_at

    )

    VALUES
    (

    ?,

    ?,

    ?,

    'active',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $_POST['category_name'],

        $_POST['position_code'],

        $_POST['base_price'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Advertisement Category Created Successfully';

}

$categories = $pdo->query("
SELECT *
FROM advertisement_categories
ORDER BY id DESC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Categories

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Add Advertisement Position

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Category Name</label>

<input
type="text"
name="category_name"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label>Position Code</label>

<input
type="text"
name="position_code"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label>Base Price (₹)</label>

<input
type="number"
step="0.01"
name="base_price"
class="form-control"
required>

</div>

</div>

<button
type="submit"
name="save_category"
class="btn btn-success">

Save Category

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Advertisement Inventory

</div>

<div class="card-body">

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Category</th>
<th>Position Code</th>
<th>Base Price</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($categories as $category): ?>

<tr>

<td><?= $category['id']; ?></td>

<td>
<?= htmlspecialchars(
$category['category_name']
); ?>
</td>

<td>
<?= htmlspecialchars(
$category['position_code']
); ?>
</td>

<td>
₹<?= number_format(
$category['base_price'],
2
); ?>
</td>

<td>
<span class="badge bg-success">
Active
</span>
</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Amar Savera Advertisement Inventory

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Homepage Top Banner</th>
<td>Premium Placement</td>
</tr>

<tr>
<th>Homepage Middle Banner</th>
<td>High Visibility</td>
</tr>

<tr>
<th>Homepage Footer Banner</th>
<td>Standard Placement</td>
</tr>

<tr>
<th>News Detail Top Banner</th>
<td>Article Premium</td>
</tr>

<tr>
<th>News Detail Sidebar Banner</th>
<td>Sidebar Placement</td>
</tr>

<tr>
<th>Breaking News Banner</th>
<td>Breaking News Area</td>
</tr>

<tr>
<th>Video Advertisement</th>
<td>Video Pre-Roll</td>
</tr>

<tr>
<th>Sponsored Article</th>
<td>Native Advertisement</td>
</tr>

<tr>
<th>District Page Banner</th>
<td>District Targeting</td>
</tr>

<tr>
<th>State Page Banner</th>
<td>State Targeting</td>
</tr>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>