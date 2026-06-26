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
| Create Training Batch
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_training']))
{

    $trainingCode =
    'TRN-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO recruitment_training
    (

    training_code,

    candidate_name,

    batch_name,

    trainer_name,

    start_date,

    end_date,

    attendance_percent,

    assessment_marks,

    training_status,

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

    ?,

    'ongoing',

    ?,

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $trainingCode,

        $_POST['candidate_name'],

        $_POST['batch_name'],

        $_POST['trainer_name'],

        $_POST['start_date'],

        $_POST['end_date'],

        $_POST['attendance_percent'],

        $_POST['assessment_marks'],

        $_POST['remarks'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Training Batch Assigned Successfully';

}

$trainings = $pdo->query("
SELECT *
FROM recruitment_training
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Training Management System

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Assign Training Batch

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

<label>Batch Name</label>

<input
type="text"
name="batch_name"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Trainer Name</label>

<input
type="text"
name="trainer_name"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Training Duration</label>

<input
type="text"
value="7 Days"
class="form-control"
readonly>

</div>

<div class="col-md-3 mb-3">

<label>Start Date</label>

<input
type="date"
name="start_date"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>End Date</label>

<input
type="date"
name="end_date"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Attendance (%)</label>

<input
type="number"
name="attendance_percent"
class="form-control"
value="0">

</div>

<div class="col-md-3 mb-3">

<label>Assessment Marks</label>

<input
type="number"
name="assessment_marks"
class="form-control"
value="0">

</div>

<div class="col-md-12 mb-3">

<label>Remarks</label>

<textarea
name="remarks"
rows="3"
class="form-control"></textarea>

</div>

</div>

<button
type="submit"
name="save_training"
class="btn btn-success">

Assign Training

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Training Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Training Code</th>
<th>Candidate</th>
<th>Batch</th>
<th>Trainer</th>
<th>Attendance</th>
<th>Marks</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($trainings as $training): ?>

<tr>

<td><?= $training['training_code']; ?></td>

<td><?= htmlspecialchars($training['candidate_name']); ?></td>

<td><?= htmlspecialchars($training['batch_name']); ?></td>

<td><?= htmlspecialchars($training['trainer_name']); ?></td>

<td><?= $training['attendance_percent']; ?>%</td>

<td><?= $training['assessment_marks']; ?>/100</td>

<td>

<span class="badge bg-primary">

<?= ucfirst($training['training_status']); ?>

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

Training Workflow

</div>

<div class="card-body">

<pre>
Candidate Selected
        ↓
Training Batch Assigned
        ↓
7 Days Training
        ↓
Daily Assessment
        ↓
Final Evaluation
        ↓
Certificate Eligible
        ↓
Joining Approval
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Training Features

</div>

<div class="card-body">

<ul>

<li>7 Days Training Batch</li>

<li>Training Attendance</li>

<li>Daily Assessment</li>

<li>Trainer Assignment</li>

<li>Training Progress Tracking</li>

<li>Final Evaluation</li>

<li>Certificate Eligibility</li>

<li>Reporter Evaluation</li>

<li>Training Completion</li>

<li>Training Reports</li>

<li>HRMS Integration</li>

<li>Joining Approval Workflow</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>