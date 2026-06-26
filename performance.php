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

$employeeCode =
$_SESSION['employee_code'] ?? '';

$currentMonth = date('m');
$currentYear  = date('Y');

/*
|--------------------------------------------------------------------------
| Target Data
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT *
FROM hrms_targets
WHERE employee_code=?
AND target_month=?
AND target_year=?
LIMIT 1
");

$stmt->execute([
$employeeCode,
$currentMonth,
$currentYear
]);

$target = $stmt->fetch();

/*
|--------------------------------------------------------------------------
| Attendance Score
|--------------------------------------------------------------------------
*/

$attendance = $pdo->prepare("
SELECT
COUNT(*) total_days,
SUM(
CASE
WHEN status='Present'
THEN 1
ELSE 0
END
) present_days
FROM hrms_attendance
WHERE employee_code=?
AND MONTH(attendance_date)=?
AND YEAR(attendance_date)=?
");

$attendance->execute([
$employeeCode,
$currentMonth,
$currentYear
]);

$attendanceData =
$attendance->fetch();

$attendanceScore = 0;

if($attendanceData['total_days']>0)
{
    $attendanceScore =
    round(
    ($attendanceData['present_days']
    /
    $attendanceData['total_days'])
    *100,
    2
    );
}

/*
|--------------------------------------------------------------------------
| News Score
|--------------------------------------------------------------------------
*/

$newsScore =
$target['news_target'] > 0
?
round(
($target['news_achieved']
/
$target['news_target'])*100,
2
)
:
0;

/*
|--------------------------------------------------------------------------
| Advertisement Score
|--------------------------------------------------------------------------
*/

$adScore =
$target['advertisement_target'] > 0
?
round(
($target['advertisement_achieved']
/
$target['advertisement_target'])*100,
2
)
:
0;

/*
|--------------------------------------------------------------------------
| Collection Score
|--------------------------------------------------------------------------
*/

$collectionScore =
$target['collection_target'] > 0
?
round(
($target['collection_achieved']
/
$target['collection_target'])*100,
2
)
:
0;

/*
|--------------------------------------------------------------------------
| Overall Score
|--------------------------------------------------------------------------
*/

$overallScore =
round(
(
$attendanceScore +
$newsScore +
$adScore +
$collectionScore
)/4,
2
);

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Performance Dashboard

</h3>

<div class="row">

<div class="col-md-3">

<div class="card border-primary">

<div class="card-body text-center">

<h2>

<?= $newsScore; ?>%

</h2>

<p>

News Performance

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-success">

<div class="card-body text-center">

<h2>

<?= $attendanceScore; ?>%

</h2>

<p>

Attendance Score

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-warning">

<div class="card-body text-center">

<h2>

<?= $adScore; ?>%

</h2>

<p>

Advertisement Score

</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-danger">

<div class="card-body text-center">

<h2>

<?= $collectionScore; ?>%

</h2>

<p>

Collection Score

</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-dark text-white">

Overall Performance Score

</div>

<div class="card-body">

<div
class="progress"
style="height:35px;">

<div
class="progress-bar bg-success"
style="width:<?= min($overallScore,100); ?>%;">

<?= $overallScore; ?>%

</div>

</div>

<br>

<table class="table table-bordered">

<tr>

<th width="250">

Overall Score

</th>

<td>

<?= $overallScore; ?>%

</td>

</tr>

<tr>

<th>

Performance Grade

</th>

<td>

<?php

if($overallScore>=90)
{
echo "A+";
}
elseif($overallScore>=75)
{
echo "A";
}
elseif($overallScore>=60)
{
echo "B";
}
elseif($overallScore>=40)
{
echo "C";
}
else
{
echo "D";
}

?>

</td>

</tr>

<tr>

<th>

Promotion Eligibility

</th>

<td>

<?php

if($overallScore>=85)
{
echo '<span class="badge bg-success">
Eligible
</span>';
}
else
{
echo '<span class="badge bg-danger">
Not Eligible
</span>';
}

?>

</td>

</tr>

<tr>

<th>

Incentive Eligibility

</th>

<td>

<?php

if($overallScore>=100)
{
echo "Full Incentive";
}
elseif($overallScore>=75)
{
echo "Partial Incentive";
}
else
{
echo "No Incentive";
}

?>

</td>

</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

Performance Breakdown

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>News</th>
<td><?= $newsScore; ?>%</td>
</tr>

<tr>
<th>Attendance</th>
<td><?= $attendanceScore; ?>%</td>
</tr>

<tr>
<th>Advertisement</th>
<td><?= $adScore; ?>%</td>
</tr>

<tr>
<th>Collection</th>
<td><?= $collectionScore; ?>%</td>
</tr>

<tr class="table-success">
<th>Final Score</th>
<td><?= $overallScore; ?>%</td>
</tr>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>