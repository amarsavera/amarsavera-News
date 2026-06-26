<?php

require_once '../../includes/config.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    foreach($_POST as $key => $value){

        $stmt = $pdo->prepare("
        UPDATE settings
        SET setting_value=?
        WHERE setting_key=?
        ");

        $stmt->execute([
            trim($value),
            $key
        ]);
    }

    $message = "सेटिंग्स सफलतापूर्वक अपडेट की गईं";
}

$settings = [];

$stmt = $pdo->query("
SELECT *
FROM settings
");

while($row = $stmt->fetch()){

    $settings[$row['setting_key']]
    =
    $row['setting_value'];

}

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">
वेबसाइट सेटिंग्स
</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<form method="post">

<div class="card mb-4">

<div class="card-header">
सामान्य सेटिंग्स
</div>

<div class="card-body">

<div class="mb-3">

<label>
वेबसाइट नाम
</label>

<input
type="text"
name="site_name"
class="form-control"
value="<?= $settings['site_name'] ?? ''; ?>"
>

</div>

<div class="mb-3">

<label>
टैगलाइन
</label>

<input
type="text"
name="site_tagline"
class="form-control"
value="<?= $settings['site_tagline'] ?? ''; ?>"
>

</div>

<div class="mb-3">

<label>
वेबसाइट URL
</label>

<input
type="text"
name="site_url"
class="form-control"
value="<?= $settings['site_url'] ?? ''; ?>"
>

</div>

</div>

</div>

<div class="card mb-4">

<div class="card-header">
संपर्क जानकारी
</div>

<div class="card-body">

<div class="mb-3">

<label>
ईमेल
</label>

<input
type="email"
name="contact_email"
class="form-control"
value="<?= $settings['contact_email'] ?? ''; ?>"
>

</div>

<div class="mb-3">

<label>
मोबाइल
</label>

<input
type="text"
name="contact_mobile"
class="form-control"
value="<?= $settings['contact_mobile'] ?? ''; ?>"
>

</div>

</div>

</div>

<div class="card mb-4">

<div class="card-header">
सोशल मीडिया
</div>

<div class="card-body">

<div class="mb-3">

<label>
Facebook URL
</label>

<input
type="text"
name="facebook_url"
class="form-control"
value="<?= $settings['facebook_url'] ?? ''; ?>"
>

</div>

<div class="mb-3">

<label>
YouTube URL
</label>

<input
type="text"
name="youtube_url"
class="form-control"
value="<?= $settings['youtube_url'] ?? ''; ?>"
>

</div>

<div class="mb-3">

<label>
Instagram URL
</label>

<input
type="text"
name="instagram_url"
class="form-control"
value="<?= $settings['instagram_url'] ?? ''; ?>"
>

</div>

</div>

</div>

<div class="card mb-4">

<div class="card-header">
विशेष सुविधाएँ
</div>

<div class="card-body">

<div class="mb-3">

<label>
Breaking News
</label>

<select
name="breaking_news"
class="form-control">

<option value="1"
<?= (($settings['breaking_news'] ?? '1')=='1')?'selected':''; ?>>
Enabled
</option>

<option value="0"
<?= (($settings['breaking_news'] ?? '1')=='0')?'selected':''; ?>>
Disabled
</option>

</select>

</div>

<div class="mb-3">

<label>
Live TV
</label>

<select
name="live_tv"
class="form-control">

<option value="1"
<?= (($settings['live_tv'] ?? '1')=='1')?'selected':''; ?>>
Enabled
</option>

<option value="0"
<?= (($settings['live_tv'] ?? '1')=='0')?'selected':''; ?>>
Disabled
</option>

</select>

</div>

</div>

</div>

<button
type="submit"
class="btn btn-success">

सेटिंग्स सेव करें

</button>

</form>

</div>

<?php include '../layout/footer.php'; ?>