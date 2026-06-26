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
| Create Vacancy
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_vacancy']))
{

    $vacancyCode =
    'VAC-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO recruitment_vacancies
    (

    vacancy_code,

    vacancy_title,

    department,

    designation,

    district,

    vacancies,

    application_deadline,

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

    'active',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $vacancyCode,

        $_POST['vacancy_title'],

        $_POST['department'],

        $_POST['designation'],

        $_POST['district'],

        $_POST['vacancies'],

        $_POST['application_deadline'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Vacancy Created Successfully';

}

$vacancies = $pdo->query("
SELECT *
FROM recruitment_vacancies
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Vacancy Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Create Vacancy

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-3 mb-3">

<label>Vacancy Title</label>

<input
type="text"
name="vacancy_title"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Department</label>

<select
name="department"
class="form-control">

<option value="Editorial">Editorial</option>
<option value="Digital Media">Digital Media</option>
<option value="Video Production">Video Production</option>
<option value="Marketing">Marketing</option>
<option value="Administration">Administration</option>

</select>

</div>

<div class="col-md-3 mb-3">

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

<div class="col-md-3 mb-3">

<label>District</label>

<input
type="text"
name="district"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Total Vacancies</label>

<input
type="number"
name="vacancies"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Application Deadline</label>

<input
type="date"
name="application_deadline"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>&nbsp;</label>

<button
type="submit"
name="save_vacancy"
class="btn btn-success w-100">

Create Vacancy

</button>

</div>

</div>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Vacancy Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Title</th>
<th>Designation</th>
<th>District</th>
<th>Vacancies</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($vacancies as $vacancy): ?>

<tr>

<td><?= $vacancy['vacancy_code']; ?></td>

<td><?= htmlspecialchars($vacancy['vacancy_title']); ?></td>

<td><?= htmlspecialchars($vacancy['designation']); ?></td>

<td><?= htmlspecialchars($vacancy['district']); ?></td>

<td><?= $vacancy['vacancies']; ?></td>

<td>

<span class="badge bg-success">

<?= ucfirst($vacancy['status']); ?>

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

Vacancy Types

</div>

<div class="card-body">

<ul>

<li>District Reporter Vacancy</li>

<li>Bureau Chief Vacancy</li>

<li>State Head Vacancy</li>

<li>Photographer Vacancy</li>

<li>Video Editor Vacancy</li>

<li>Marketing Executive Vacancy</li>

<li>Digital Media Executive Vacancy</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>