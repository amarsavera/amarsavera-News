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
| Upload Creative
|--------------------------------------------------------------------------
*/

if(isset($_POST['upload_creative']))
{

    $fileName='';

    if(
    isset($_FILES['creative_file'])
    &&
    $_FILES['creative_file']['error']==0
    )
    {

        $uploadDir =
        '../../uploads/advertisements/';

        if(!is_dir($uploadDir))
        {
            mkdir(
            $uploadDir,
            0777,
            true
            );
        }

        $fileName =
        time().'_'.
        basename(
        $_FILES['creative_file']['name']
        );

        move_uploaded_file(

        $_FILES['creative_file']['tmp_name'],

        $uploadDir.$fileName

        );

    }

    $stmt = $pdo->prepare("
    INSERT INTO advertisement_creatives
    (

    creative_title,

    creative_type,

    file_name,

    file_size,

    approval_status,

    uploaded_by,

    created_at

    )

    VALUES
    (

    ?,

    ?,

    ?,

    ?,

    'pending',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $_POST['creative_title'],

        $_POST['creative_type'],

        $fileName,

        $_FILES['creative_file']['size']
        ?? 0,

        $_SESSION['admin_id']

    ]);

    $message =
    'Creative Uploaded Successfully';

}

/*
|--------------------------------------------------------------------------
| Approve Creative
|--------------------------------------------------------------------------
*/

if(isset($_GET['approve']))
{

    $stmt=$pdo->prepare("
    UPDATE advertisement_creatives
    SET approval_status='approved'
    WHERE id=?
    ");

    $stmt->execute([
    (int)$_GET['approve']
    ]);

}

$creatives = $pdo->query("
SELECT *
FROM advertisement_creatives
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Creative Asset Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Upload Creative

</div>

<div class="card-body">

<form
method="POST"
enctype="multipart/form-data">

<div class="row">

<div class="col-md-4 mb-3">

<label>Creative Title</label>

<input
type="text"
name="creative_title"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Creative Type</label>

<select
name="creative_type"
class="form-control">

<option value="banner">
Banner
</option>

<option value="video">
Video
</option>

<option value="sponsored">
Sponsored Content
</option>

<option value="popup">
Popup Ad
</option>

</select>

</div>

<div class="col-md-5 mb-3">

<label>Select File</label>

<input
type="file"
name="creative_file"
class="form-control"
required>

</div>

</div>

<button
type="submit"
name="upload_creative"
class="btn btn-success">

Upload Creative

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Creative Library

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Title</th>
<th>Type</th>
<th>File</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($creatives as $creative): ?>

<tr>

<td>

<?= $creative['id']; ?>

</td>

<td>

<?= htmlspecialchars(
$creative['creative_title']
); ?>

</td>

<td>

<?= ucfirst(
$creative['creative_type']
); ?>

</td>

<td>

<a
href="../../uploads/advertisements/<?= $creative['file_name']; ?>"
target="_blank">

Download

</a>

</td>

<td>

<?php if(
$creative['approval_status']
=='approved'
): ?>

<span class="badge bg-success">

Approved

</span>

<?php else: ?>

<span class="badge bg-warning">

Pending

</span>

<?php endif; ?>

</td>

<td>

<?php if(
$creative['approval_status']
!='approved'
): ?>

<a
href="?approve=<?= $creative['id']; ?>"
class="btn btn-success btn-sm">

Approve

</a>

<?php endif; ?>

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

Supported Creative Formats

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Banner Ads</th>
<td>JPG, PNG, WEBP</td>
</tr>

<tr>
<th>Video Ads</th>
<td>MP4, WEBM</td>
</tr>

<tr>
<th>Sponsored Content</th>
<td>HTML, PDF, DOCX</td>
</tr>

<tr>
<th>Popup Ads</th>
<td>PNG, GIF</td>
</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Creative Workflow

</div>

<div class="card-body">

<pre>
Creative Upload
       ↓
Quality Check
       ↓
Approval Process
       ↓
Campaign Linking
       ↓
Advertisement Booking
       ↓
Publishing
       ↓
Archive Storage
</pre>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>