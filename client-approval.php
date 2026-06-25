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

if(isset($_POST['approve']))
{

    $pdo->prepare("
    UPDATE advertisements
    SET status='placement_pending'
    WHERE id=?
    ")->execute([$id]);

    $pdo->prepare("
    INSERT INTO advertisement_comments
    (
        advertisement_id,
        comment_type,
        comment_text,
        created_by,
        created_at
    )
    VALUES
    (
        ?,
        'client_approved',
        ?,
        ?,
        NOW()
    )
    ")->execute([

        $id,
        $_POST['remarks'],
        $_SESSION['admin_id']

    ]);

    header(
    "Location:placement.php?id=".$id
    );

    exit;
}

if(isset($_POST['reject']))
{

    $pdo->prepare("
    UPDATE advertisements
    SET status='rejected'
    WHERE id=?
    ")->execute([$id]);

    $pdo->prepare("
    INSERT INTO advertisement_comments
    (
        advertisement_id,
        comment_type,
        comment_text,
        created_by,
        created_at
    )
    VALUES
    (
        ?,
        'client_rejected',
        ?,
        ?,
        NOW()
    )
    ")->execute([

        $id,
        $_POST['remarks'],
        $_SESSION['admin_id']

    ]);

    header(
    "Location:view.php?id=".$id
    );

    exit;
}

if(isset($_POST['revision']))
{

    $pdo->prepare("
    UPDATE advertisements
    SET status='revision_required'
    WHERE id=?
    ")->execute([$id]);

    $pdo->prepare("
    INSERT INTO advertisement_comments
    (
        advertisement_id,
        comment_type,
        comment_text,
        created_by,
        created_at
    )
    VALUES
    (
        ?,
        'revision_required',
        ?,
        ?,
        NOW()
    )
    ")->execute([

        $id,
        $_POST['remarks'],
        $_SESSION['admin_id']

    ]);

    header(
    "Location:design-upload.php?id=".$id
    );

    exit;
}

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-success text-white">

Client Final Approval

</div>

<div class="card-body">

<h4>

<?= htmlspecialchars(
$advertisement['title']
); ?>

</h4>

<hr>

<form method="post">

<div class="mb-3">

<label>

Client Remarks

</label>

<textarea
name="remarks"
class="form-control"
rows="5"
required></textarea>

</div>

<button
type="submit"
name="approve"
class="btn btn-success">

Approve

</button>

<button
type="submit"
name="revision"
class="btn btn-warning">

Revision Required

</button>

<button
type="submit"
name="reject"
class="btn btn-danger">

Reject

</button>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>