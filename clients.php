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
| Save Client
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_client']))
{

    $stmt = $pdo->prepare("
    INSERT INTO advertisement_clients
    (

    client_type,

    company_name,

    contact_person,

    mobile,

    email,

    gst_number,

    address,

    credit_limit,

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

    ?,

    ?,

    'active',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $_POST['client_type'],

        $_POST['company_name'],

        $_POST['contact_person'],

        $_POST['mobile'],

        $_POST['email'],

        $_POST['gst_number'],

        $_POST['address'],

        $_POST['credit_limit'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Client Added Successfully';

}

$clients = $pdo->query("
SELECT *
FROM advertisement_clients
ORDER BY id DESC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Advertisement Clients

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Add New Client

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-3 mb-3">

<label>Client Type</label>

<select
name="client_type"
class="form-control">

<option value="business">
Business
</option>

<option value="government">
Government
</option>

<option value="political">
Political
</option>

<option value="individual">
Individual
</option>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Company / Client Name</label>

<input
type="text"
name="company_name"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Contact Person</label>

<input
type="text"
name="contact_person"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Mobile</label>

<input
type="text"
name="mobile"
class="form-control">

</div>

<div class="col-md-4 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control">

</div>

<div class="col-md-4 mb-3">

<label>GST Number</label>

<input
type="text"
name="gst_number"
class="form-control">

</div>

<div class="col-md-4 mb-3">

<label>Credit Limit</label>

<input
type="number"
step="0.01"
name="credit_limit"
class="form-control"
value="0">

</div>

<div class="col-md-12 mb-3">

<label>Address</label>

<textarea
name="address"
rows="3"
class="form-control"></textarea>

</div>

</div>

<button
type="submit"
name="save_client"
class="btn btn-success">

Save Client

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Client Directory

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Client</th>
<th>Type</th>
<th>Contact</th>
<th>GST</th>
<th>Credit Limit</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($clients as $client): ?>

<tr>

<td><?= $client['id']; ?></td>

<td>

<?= htmlspecialchars(
$client['company_name']
); ?>

</td>

<td>

<?= ucfirst(
$client['client_type']
); ?>

</td>

<td>

<?= htmlspecialchars(
$client['mobile']
); ?>

</td>

<td>

<?= htmlspecialchars(
$client['gst_number']
); ?>

</td>

<td>

₹<?= number_format(
$client['credit_limit'],
2
); ?>

</td>

<td>

<span class="badge bg-success">

<?= ucfirst(
$client['status']
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

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Client Categories

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Business Clients</th>
<td>Private Companies, Shops, Brands</td>
</tr>

<tr>
<th>Government Clients</th>
<td>Departments, PSU, Government Schemes</td>
</tr>

<tr>
<th>Political Clients</th>
<td>Political Parties & Candidates</td>
</tr>

<tr>
<th>Individual Clients</th>
<td>Personal Advertisements</td>
</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Client Revenue Tracking

</div>

<div class="card-body">

<pre>
Client Registration
        ↓
Advertisement Booking
        ↓
Invoice Generation
        ↓
Payment Collection
        ↓
Revenue Tracking
        ↓
Client History
</pre>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>