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

$employees = $pdo->query("
SELECT
employee_id,
full_name,
designation,
district,
official_email
FROM employees
WHERE status='active'
ORDER BY full_name
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

ID Card & Authority Letter System

</h3>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Generate ID Card & Authority Letter

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Select Employee</label>

<select
name="employee_id"
class="form-control">

<?php foreach($employees as $employee): ?>

<option value="<?= $employee['employee_id']; ?>">

<?= htmlspecialchars(
$employee['full_name']
); ?>

-

<?= htmlspecialchars(
$employee['designation']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-3 mb-3">

<label>ID Card</label>

<button
type="submit"
name="generate_id_card"
class="btn btn-success w-100">

Generate ID Card

</button>

</div>

<div class="col-md-3 mb-3">

<label>Authority Letter</label>

<button
type="submit"
name="generate_authority"
class="btn btn-primary w-100">

Generate Letter

</button>

</div>

</div>

</form>

</div>

</div>

<?php

if(isset($_POST['generate_id_card']))
{

$employeeId =
$_POST['employee_id'];

$stmt = $pdo->prepare("
SELECT *
FROM employees
WHERE employee_id=?
");

$stmt->execute([$employeeId]);

$employee =
$stmt->fetch();

?>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Digital ID Card Preview

</div>

<div class="card-body">

<div
style="
width:350px;
border:2px solid #000;
padding:15px;
">

<h4 class="text-center">

AMAR SAVERA

</h4>

<hr>

<p>

<b>Employee ID:</b>

<?= $employee['employee_id']; ?>

</p>

<p>

<b>Name:</b>

<?= $employee['full_name']; ?>

</p>

<p>

<b>Designation:</b>

<?= $employee['designation']; ?>

</p>

<p>

<b>District:</b>

<?= $employee['district']; ?>

</p>

<p>

<b>Email:</b>

<?= $employee['official_email']; ?>

</p>

<p>

<b>Status:</b> Active

</p>

<hr>

<p class="text-center">

QR Verification Enabled

</p>

</div>

</div>

</div>

<?php

}

if(isset($_POST['generate_authority']))
{

$employeeId =
$_POST['employee_id'];

$stmt = $pdo->prepare("
SELECT *
FROM employees
WHERE employee_id=?
");

$stmt->execute([$employeeId]);

$employee =
$stmt->fetch();

?>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Authority Letter Preview

</div>

<div class="card-body">

<p>

<b>To Whom It May Concern</b>

</p>

<p>

This is to certify that

<b>

<?= $employee['full_name']; ?>

</b>

(Employee ID:

<?= $employee['employee_id']; ?>

)

is officially appointed as

<b>

<?= $employee['designation']; ?>

</b>

for Amar Savera.

</p>

<p>

The above employee is authorized to collect news, conduct interviews, attend press conferences and represent Amar Savera within assigned jurisdiction.

</p>

<p>

District:

<b>

<?= $employee['district']; ?>

</b>

</p>

<p>

Official Email:

<b>

<?= $employee['official_email']; ?>

</b>

</p>

<br>

<p>

Authorized Signatory

</p>

<p>

Editor-in-Chief

</p>

<p>

Amar Savera

</p>

</div>

</div>

<?php

}

?>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

ID Card Workflow

</div>

<div class="card-body">

<pre>
Joining Approved
       ↓
Employee Created
       ↓
ID Card Generated
       ↓
Authority Letter Generated
       ↓
QR Verification
       ↓
Download PDF
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

System Features

</div>

<div class="card-body">

<ul>

<li>Digital ID Card Generation</li>

<li>Authority Letter Generation</li>

<li>QR Code Verification</li>

<li>Employee Auto Fetch</li>

<li>Designation Templates</li>

<li>District Wise Templates</li>

<li>Digital Signature</li>

<li>PDF Download</li>

<li>Validity Management</li>

<li>Verification Portal</li>

<li>HRMS Integration</li>

<li>Reporter Authentication</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>