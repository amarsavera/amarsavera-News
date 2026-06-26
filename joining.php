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
| Approve Joining
|--------------------------------------------------------------------------
*/

if(isset($_POST['approve_joining']))
{

    $employeeId =
    'AS'.
    date('Y').
    rand(1000,9999);

    $nameParts =
    explode(' ',
    trim($_POST['employee_name']));

    $firstName =
    strtolower($nameParts[0]);

    $districtCode =
    strtolower(
    substr(
    $_POST['district'],
    0,
    3
    ));

    $officialEmail =
    $firstName.
    '-'.
    $districtCode.
    '@amar-savera.saragone.in';

    $defaultPassword =
    'Amar@123';

    $stmt = $pdo->prepare("
    INSERT INTO employees
    (

    employee_id,

    full_name,

    email,

    official_email,

    district,

    department,

    designation,

    joining_date,

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

    CURDATE(),

    'active',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $employeeId,

        $_POST['employee_name'],

        $_POST['personal_email'],

        $officialEmail,

        $_POST['district'],

        $_POST['department'],

        $_POST['designation'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Joining Approved. Employee ID: '.
    $employeeId.
    ' | Email: '.
    $officialEmail;

}

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Joining & Employee Activation

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-success text-white">

Approve Candidate Joining

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Employee Name</label>

<input
type="text"
name="employee_name"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label>Personal Email</label>

<input
type="email"
name="personal_email"
class="form-control">

</div>

<div class="col-md-4 mb-3">

<label>District</label>

<input
type="text"
name="district"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label>Department</label>

<select
name="department"
class="form-control">

<option value="Editorial">
Editorial
</option>

<option value="Digital Media">
Digital Media
</option>

<option value="Marketing">
Marketing
</option>

<option value="Administration">
Administration
</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label>Designation</label>

<select
name="designation"
class="form-control">

<option value="District Reporter">
District Reporter
</option>

<option value="Bureau Chief">
Bureau Chief
</option>

<option value="State Head">
State Head
</option>

<option value="Photographer">
Photographer
</option>

<option value="Video Editor">
Video Editor
</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label>&nbsp;</label>

<button
type="submit"
name="approve_joining"
class="btn btn-success w-100">

Approve Joining

</button>

</div>

</div>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Joining Automation

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>HRMS Employee ID</th>
<td>Auto Generated</td>
</tr>

<tr>
<th>Official Email</th>
<td>Auto Generated</td>
</tr>

<tr>
<th>Default Password</th>
<td>Amar@123</td>
</tr>

<tr>
<th>ID Card</th>
<td>Auto Generated</td>
</tr>

<tr>
<th>Authority Letter</th>
<td>Auto Generated</td>
</tr>

<tr>
<th>Portal Access</th>
<td>Auto Activated</td>
</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Joining Workflow

</div>

<div class="card-body">

<pre>
Interview Selected
        ↓
Training Completed
        ↓
Joining Approved
        ↓
Employee ID Generated
        ↓
Official Email Generated
        ↓
ID Card Generated
        ↓
Authority Letter Generated
        ↓
Reporter Activated
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Integration Features

</div>

<div class="card-body">

<ul>

<li>HRMS Employee Creation</li>

<li>Official Email Creation</li>

<li>Reporter Dashboard Access</li>

<li>ID Card Generation</li>

<li>Authority Letter Generation</li>

<li>Employee Profile Creation</li>

<li>Department Allocation</li>

<li>Designation Allocation</li>

<li>Role Based Access</li>

<li>Target Assignment</li>

<li>Payroll Integration</li>

<li>Performance Tracking</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>