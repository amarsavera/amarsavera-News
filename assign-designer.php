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

$designers = $pdo->query("
SELECT
u.id,
u.name
FROM users u
INNER JOIN designations d
ON d.id=u.designation_id
WHERE d.designation_name LIKE '%Designer%'
AND u.status='active'
ORDER BY u.name
")->fetchAll();

if($_SERVER['REQUEST_METHOD']=='POST')
{

    $assign = $pdo->prepare("
    INSERT INTO designer_approvals
    (
        advertisement_id,
        designer_id,
        status,
        remarks,
        assigned_by,
        created_at
    )
    VALUES
    (
        ?,?,
        'assigned',
        ?,
        ?,
        NOW()
    )
    ");

    $assign->execute([

        $id,
        $_POST['designer_id'],
        $_POST['remarks'],
        $_SESSION['admin_id']

    ]);

    $update = $pdo->prepare("
    UPDATE advertisements
    SET
    status='designer_assigned'
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

Assign Designer

</div>

<div class="card-body">

<form method="post">

<div class="mb-3">

<label>Select Designer</label>

<select
name="designer_id"
class="form-control"
required>

<option value="">

Select Designer

</option>

<?php foreach($designers as $designer): ?>

<option
value="<?= $designer['id']; ?>">

<?= htmlspecialchars($designer['name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="mb-3">

<label>Assignment Remark</label>

<textarea
name="remarks"
class="form-control"
rows="4"></textarea>

</div>

<button
type="submit"
class="btn btn-success">

Assign Designer

</button>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>