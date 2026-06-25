<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../index.php");
    exit;
}

$advertisementId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT *
FROM advertisements
WHERE id=?
LIMIT 1
");

$stmt->execute([$advertisementId]);

$advertisement = $stmt->fetch();

if(!$advertisement)
{
    die('Advertisement Not Found');
}

if($_SERVER['REQUEST_METHOD']=='POST')
{

    $version = (int)($_POST['version_no'] ?? 1);

    $fileName = '';

    if(
        isset($_FILES['design_file'])
        &&
        $_FILES['design_file']['error']==0
    )
    {

        $ext = pathinfo(
            $_FILES['design_file']['name'],
            PATHINFO_EXTENSION
        );

        $fileName =
        'ad_'.
        $advertisementId.
        '_v'.
        $version.
        '_'.
        time().
        '.'.
        $ext;

        move_uploaded_file(

            $_FILES['design_file']['tmp_name'],

            '../../uploads/advertisements/'.
            $fileName

        );

    }

    $save = $pdo->prepare("
    INSERT INTO advertisement_versions
    (
        advertisement_id,
        version_no,
        design_file,
        remarks,
        uploaded_by,
        created_at
    )
    VALUES
    (
        ?,?,?,?,?,NOW()
    )
    ");

    $save->execute([

        $advertisementId,
        $version,
        $fileName,
        $_POST['remarks'],
        $_SESSION['user_id']

    ]);

    $update = $pdo->prepare("
    UPDATE advertisements
    SET status='client_review'
    WHERE id=?
    ");

    $update->execute([
        $advertisementId
    ]);

    header(
    "Location:view.php?id=".$advertisementId
    );

    exit;
}

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-success text-white">

Design Upload

</div>

<div class="card-body">

<form
method="post"
enctype="multipart/form-data">

<div class="mb-3">

<label>

Version Number

</label>

<input
type="number"
name="version_no"
value="1"
class="form-control"
required>

</div>

<div class="mb-3">

<label>

Design File

</label>

<input
type="file"
name="design_file"
class="form-control"
required>

</div>

<div class="mb-3">

<label>

Designer Remarks

</label>

<textarea
name="remarks"
class="form-control"
rows="5"></textarea>

</div>

<button
type="submit"
class="btn btn-success">

Upload Design

</button>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>