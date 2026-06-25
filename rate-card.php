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
| Save Rate Card
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_rate']))
{

    $stmt = $pdo->prepare("
    INSERT INTO advertisement_rate_card
    (

    category_id,

    location_type,

    location_name,

    duration_days,

    base_rate,

    gst_percent,

    final_rate,

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

    NOW()

    )

    ");

    $gstPercent = 18;

    $finalRate =
    $_POST['base_rate']
    +
    (
    $_POST['base_rate']
    *
    $gstPercent
    /100
    );

    $stmt->execute([

        $_POST['category_id'],

        $_POST['location_type'],

        $_POST['location_name'],

        $_POST['duration_days'],

        $_POST['base_rate'],

        $gstPercent,

        $finalRate,

        $_SESSION['admin_id']

    ]);

    $message =
    'Rate Card Saved Successfully';

}

$categories = $pdo->query("
SELECT *
FROM advertisement_categories
ORDER BY category_name
")->fetchAll();

$rates = $pdo->query("
SELECT

r.*,

c.category_name

FROM advertisement_rate_card r

LEFT JOIN advertisement_categories c
ON c.id=r.category_id

ORDER BY r.id DESC

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Rate Card

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Create Rate Card

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-3 mb-3">

<label>Advertisement Category</label>

<select
name="category_id"
class="form-control"
required>

<?php foreach($categories as $category): ?>

<option
value="<?= $category['id']; ?>">

<?= htmlspecialchars(
$category['category_name']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-2 mb-3">

<label>Location Type</label>

<select
name="location_type"
class="form-control">

<option value="national">
National
</option>

<option value="state">
State
</option>

<option value="district">
District
</option>

</select>

</div>

<div class="col-md-2 mb-3">

<label>Location</label>

<input
type="text"
name="location_name"
class="form-control"
placeholder="UP / Bareilly">

</div>

<div class="col-md-2 mb-3">

<label>Days</label>

<input
type="number"
name="duration_days"
value="30"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Base Rate (₹)</label>

<input
type="number"
step="0.01"
name="base_rate"
class="form-control"
required>

</div>

</div>

<button
type="submit"
name="save_rate"
class="btn btn-success">

Save Rate Card

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Rate Card Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>Category</th>
<th>Location</th>
<th>Days</th>
<th>Base Rate</th>
<th>GST</th>
<th>Final Rate</th>

</tr>

</thead>

<tbody>

<?php foreach($rates as $rate): ?>

<tr>

<td>

<?= htmlspecialchars(
$rate['category_name']
); ?>

</td>

<td>

<?= ucfirst(
$rate['location_type']
); ?>

-

<?= htmlspecialchars(
$rate['location_name']
); ?>

</td>

<td>

<?= $rate['duration_days']; ?>

</td>

<td>

₹<?= number_format(
$rate['base_rate'],
2
); ?>

</td>

<td>

<?= $rate['gst_percent']; ?>%

</td>

<td>

<strong>

₹<?= number_format(
$rate['final_rate'],
2
); ?>

</strong>

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

Suggested Amar Savera Rate Card

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Homepage Top Banner</th>
<td>₹25,000 / Month</td>
</tr>

<tr>
<th>Homepage Middle Banner</th>
<td>₹15,000 / Month</td>
</tr>

<tr>
<th>Homepage Footer Banner</th>
<td>₹8,000 / Month</td>
</tr>

<tr>
<th>Breaking News Banner</th>
<td>₹20,000 / Week</td>
</tr>

<tr>
<th>Sponsored Article</th>
<td>₹5,000 / Article</td>
</tr>

<tr>
<th>Video Advertisement</th>
<td>₹10,000 / Campaign</td>
</tr>

<tr>
<th>District Banner</th>
<td>₹3,000 / Month</td>
</tr>

<tr>
<th>State Banner</th>
<td>₹12,000 / Month</td>
</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Revenue Flow

</div>

<div class="card-body">

<pre>
Rate Card
      ↓
Client Booking
      ↓
Invoice Creation
      ↓
GST Calculation
      ↓
Payment Collection
      ↓
Commission Distribution
      ↓
Finance Ledger Posting
</pre>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>