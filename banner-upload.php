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
| Upload Banner
|--------------------------------------------------------------------------
*/

if(isset($_POST['upload_banner']))
{

    $campaignId =
    (int)$_POST['campaign_id'];

    $bannerType =
    $_POST['banner_type'];

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

    if(!empty($_FILES['banner']['name']))
    {

        $ext =
        strtolower(
        pathinfo(
        $_FILES['banner']['name'],
        PATHINFO_EXTENSION
        )
        );

        $allowed =
        ['jpg','jpeg','png','webp'];

        if(in_array($ext,$allowed))
        {

            $fileName =
            time().'_'.
            uniqid().
            '.'.$ext;

            move_uploaded_file(

                $_FILES['banner']['tmp_name'],

                $uploadDir.$fileName

            );

            $stmt=$pdo->prepare("
            INSERT INTO advertisement_banners
            (

            campaign_id,
            banner_type,

            banner_file,

            status,

            created_by,
            created_at

            )

            VALUES
            (

            ?,
            ?,

            ?,

            'active',

            ?,
            NOW()

            )

            ");

            $stmt->execute([

                $campaignId,

                $bannerType,

                $fileName,

                $_SESSION['admin_id']

            ]);

            $message =
            'Banner Uploaded Successfully';

        }
        else
        {

            $message =
            'Only JPG, PNG and WEBP Allowed';

        }

    }

}

$campaigns=$pdo->query("
SELECT
id,
campaign_name
FROM advertisements
ORDER BY campaign_name
")->fetchAll();

$banners=$pdo->query("
SELECT

b.*,

a.campaign_name

FROM advertisement_banners b

LEFT JOIN advertisements a
ON a.id=b.campaign_id

ORDER BY b.id DESC

")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Banner Upload Manager

</h3>

<?php if($message): ?>

<div class="alert alert-info">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Upload Campaign Banner

</div>

<div class="card-body">

<form
method="POST"
enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label>

Campaign

</label>

<select
name="campaign_id"
class="form-control"
required>

<option value="">

Select Campaign

</option>

<?php foreach($campaigns as $campaign): ?>

<option
value="<?= $campaign['id']; ?>">

<?= htmlspecialchars(
$campaign['campaign_name']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>

Banner Type

</label>

<select
name="banner_type"
class="form-control">

<option value="desktop">

Desktop Banner

</option>

<option value="mobile">

Mobile Banner

</option>

</select>

</div>

<div class="col-md-12 mb-3">

<label>

Banner Image

</label>

<input
type="file"
name="banner"
class="form-control"
accept=".jpg,.jpeg,.png,.webp"
required>

</div>

</div>

<button
type="submit"
name="upload_banner"
class="btn btn-success">

Upload Banner

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Uploaded Banners

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Preview</th>
<th>Campaign</th>
<th>Type</th>
<th>Status</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($banners as $banner): ?>

<tr>

<td>

<?= $banner['id']; ?>

</td>

<td>

<img
src="../../uploads/advertisements/<?= $banner['banner_file']; ?>"
style="max-width:150px;max-height:80px;">

</td>

<td>

<?= htmlspecialchars(
$banner['campaign_name']
); ?>

</td>

<td>

<?= ucfirst(
$banner['banner_type']
); ?>

</td>

<td>

<span class="badge bg-success">

<?= ucfirst(
$banner['status']
); ?>

</span>

</td>

<td>

<?= $banner['created_at']; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>