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
| Save Candidate Application
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_application']))
{

    $applicationCode =
    'APP-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO recruitment_applications
    (

    application_code,

    candidate_name,

    mobile,

    email,

    qualification,

    vacancy_id,

    resume_file,

    application_status,

    remarks,

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

    'pending',

    ?,

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $applicationCode,

        $_POST['candidate_name'],

        $_POST['mobile'],

        $_POST['email'],

        $_POST['qualification'],

        $_POST['vacancy_id'],

        $_POST['resume_file'],

        $_POST['remarks'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Candidate Application Added Successfully';

}

$applications = $pdo->query("
SELECT
a.*,
v.vacancy_title

FROM recruitment_applications a

LEFT JOIN recruitment_vacancies v
ON v.id=a.vacancy_id

ORDER BY a.id DESC

LIMIT 500
")->fetchAll();

$vacancies = $pdo->query("
SELECT id,vacancy_title
FROM recruitment_vacancies
WHERE status='active'
ORDER BY vacancy_title
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Candidate Application Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Add Candidate Application

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-3 mb-3">

<label>Candidate Name</label>

<input
type="text"
name="candidate_name"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Mobile Number</label>

<input
type="text"
name="mobile"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Qualification</label>

<input
type="text"
name="qualification"
class="form-control">

</div>

<div class="col-md-4 mb-3">

<label>Apply For Vacancy</label>

<select
name="vacancy_id"
class="form-control">

<?php foreach($vacancies as $vacancy): ?>

<option value="<?= $vacancy['id']; ?>">

<?= htmlspecialchars(
$vacancy['vacancy_title']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-4 mb-3">

<label>Resume File</label>

<input
type="text"
name="resume_file"
class="form-control"
placeholder="resume.pdf">

</div>

<div class="col-md-4 mb-3">

<label>Status Note</label>

<input
type="text"
name="remarks"
class="form-control">

</div>

</div>

<button
type="submit"
name="save_application"
class="btn btn-success">

Save Application

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Application Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Application Code</th>
<th>Candidate</th>
<th>Vacancy</th>
<th>Qualification</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($applications as $application): ?>

<tr>

<td>

<?= $application['application_code']; ?>

</td>

<td>

<?= htmlspecialchars(
$application['candidate_name']
); ?>

</td>

<td>

<?= htmlspecialchars(
$application['vacancy_title']
); ?>

</td>

<td>

<?= htmlspecialchars(
$application['qualification']
); ?>

</td>

<td>

<span class="badge bg-warning">

<?= ucfirst(
$application['application_status']
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

<div class="card-header bg-info text-white">

Application Features

</div>

<div class="card-body">

<ul>

<li>Candidate Application Management</li>

<li>Resume Upload Tracking</li>

<li>Document Verification</li>

<li>Qualification Verification</li>

<li>Application Screening</li>

<li>Shortlisting System</li>

<li>Interview Selection</li>

<li>Status Tracking</li>

<li>Candidate Notes</li>

<li>Recruitment Reports</li>

<li>HRMS Integration</li>

<li>Joining Pipeline</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>