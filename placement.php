<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT *
FROM advertisements
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$advertisement = $stmt->fetch();

if(!$advertisement)
{
    die('Advertisement Not Found');
}

$positions = $pdo->query("
SELECT *
FROM ad_positions
WHERE status='active'
ORDER BY position_name
")->fetchAll();

if($_SERVER['REQUEST_METHOD']=='POST')
{

    $save = $pdo->prepare("
    INSERT INTO ad_target_locations
    (
        advertisement_id,
        position_id,
        publish_date,
        publish_time,
        created_at
    )
    VALUES
    (
        ?,?,?,?,NOW()
    )
    ");

    $save->execute([

        $id,
        $_POST['position_id'],
        $_POST['publish_date'],
        $_POST['publish_time']

    ]);

    $update = $pdo->prepare("
    UPDATE advertisements
    SET status='placement_done'
    WHERE id=?
    ");

    $update->execute([$id]);

    header("Location:view.php?id=".$id);
    exit;
}

include '../layout/header.php';
?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Advertisement Placement

</div>

<div class="card-body">

<form method="post">

<div class="mb-3">

<label>Position</label>

<select
name="position_id"
class="form-control"
required>

<?php foreach($positions as $position): ?>

<option
value="<?= $position['id']; ?>">

<?= htmlspecialchars($position['position_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="mb-3">

<label>Publish Date</label>

<input
type="date"
name="publish_date"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Publish Time</label>

<input
type="time"
name="publish_time"
class="form-control"
required>

</div>

<button
type="submit"
class="btn btn-success">

Save Placement

</button>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>