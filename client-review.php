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

$version = $pdo->prepare("
SELECT *
FROM advertisement_versions
WHERE advertisement_id=?
ORDER BY version_no DESC
LIMIT 1
");

$version->execute([$id]);

$latestVersion = $version->fetch();

if(isset($_POST['approve']))
{

    $pdo->prepare("
    UPDATE advertisements
    SET status='approved'
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
        'approval',
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
        'revision',
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
    "Location:history.php?id=".$id
    );

    exit;
}

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Client Review

</div>

<div class="card-body">

<h5>

Advertisement :
<?= htmlspecialchars($advertisement['title']); ?>

</h5>

<hr>

<?php if($latestVersion): ?>

<p>

Version :
<?= $latestVersion['version_no']; ?>

</p>

<p>

File :

<a
target="_blank"
href="../../uploads/advertisements/<?= $latestVersion['design_file']; ?>">

View Design

</a>

</p>

<?php endif; ?>

<form method="post">

<div class="mb-3">

<label>

Client Remark

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

Approve Design

</button>

<button
type="submit"
name="revision"
class="btn btn-danger">

Send Revision

</button>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>