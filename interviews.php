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
| Schedule Interview
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_interview']))
{

    $interviewCode =
    'INT-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO recruitment_interviews
    (

    interview_code,

    application_id,

    interview_type,

    interview_date,

    interview_panel,

    marks,

    remarks,

    recommendation,

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

    'scheduled',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $interviewCode,

        $_POST['application_id'],

        $_POST['interview_type'],

        $_POST['interview_date'],

        $_POST['interview_panel'],

        $_POST['marks'],

        $_POST['remarks'],

        $_POST['recommendation'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Interview Scheduled Successfully';

}

$applications = $pdo->query("
SELECT
id,
application_code,
candidate_name
FROM recruitment_applications
ORDER BY candidate_name
")->fetchAll();

$interviews = $pdo->query("
SELECT
i.*,
a.candidate_name
FROM recruitment_interviews i
LEFT JOIN recruitment_applications a
ON a.id=i.application_id
ORDER BY i.id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Interview Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Schedule Interview

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Candidate</label>

<select
name="application_id"
class="form-control">

<?php foreach($applications as $app): ?>

<option value="<?= $app['id']; ?>">

<?= htmlspecialchars(
$app['candidate_name']
); ?>

(
<?= $app['application_code']; ?>
)

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-4 mb-3">

<label>Interview Type</label>

<select
name="interview_type"
class="form-control">

<option value="online">
Online Interview
</option>

<option value="physical">
Physical Interview
</option>

<option value="telephonic">
Telephonic Interview
</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label>Interview Date</label>

<input
type="datetime-local"
name="interview_date"
class="form-control">

</div>

<div class="col-md-4 mb-3">

<label>Interview Panel</label>

<input
type="text"
name="interview_panel"
class="form-control">

</div>

<div class="col-md-2 mb-3">

<label>Marks</label>

<input
type="number"
name="marks"
max="100"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Recommendation</label>

<select
name="recommendation"
class="form-control">

<option value="selected">
Selected
</option>

<option value="hold">
Hold
</option>

<option value="rejected">
Rejected
</option>

</select>

</div>

<div class="col-md-3 mb-3">

<label>Status</label>

<input
type="text"
value="Scheduled"
class="form-control"
readonly>

</div>

<div class="col-md-12 mb-3">

<label>Interview Remarks</label>

<textarea
name="remarks"
rows="3"
class="form-control"></textarea>

</div>

</div>

<button
type="submit"
name="save_interview"
class="btn btn-success">

Schedule Interview

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Interview Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Candidate</th>
<th>Type</th>
<th>Date</th>
<th>Marks</th>
<th>Recommendation</th>

</tr>

</thead>

<tbody>

<?php foreach($interviews as $interview): ?>

<tr>

<td>

<?= $interview['interview_code']; ?>

</td>

<td>

<?= htmlspecialchars(
$interview['candidate_name']
); ?>

</td>

<td>

<?= ucfirst(
$interview['interview_type']
); ?>

</td>

<td>

<?= $interview['interview_date']; ?>

</td>

<td>

<?= $interview['marks']; ?>/100

</td>

<td>

<span class="badge bg-primary">

<?= ucfirst(
$interview['recommendation']
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

Interview Workflow

</div>

<div class="card-body">

<pre>
Application Shortlisted
         ↓
Interview Scheduled
         ↓
Candidate Evaluation
         ↓
Marks & Remarks
         ↓
Selection / Rejection
         ↓
Training Batch
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Interview Features

</div>

<div class="card-body">

<ul>

<li>Interview Scheduling</li>

<li>Online Interview</li>

<li>Physical Interview</li>

<li>Telephonic Interview</li>

<li>Interview Panel Management</li>

<li>Candidate Evaluation</li>

<li>Marks System</li>

<li>Selection / Rejection</li>

<li>Interview Remarks</li>

<li>Final Recommendation</li>

<li>Training Batch Transfer</li>

<li>Interview Reports</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>