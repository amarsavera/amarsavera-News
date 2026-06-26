<?php

require_once '../includes/config.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $mobile  = trim($_POST['mobile'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $messageText = trim($_POST['message'] ?? '');

    if(
        $name != '' &&
        $subject != '' &&
        $messageText != ''
    )
    {
        try{

            $stmt = $pdo->prepare("
            INSERT INTO contact_messages
            (
                name,
                email,
                mobile,
                subject,
                message,
                created_at
            )
            VALUES
            (
                ?,?,?,?,?,
                NOW()
            )
            ");

            $stmt->execute([
                $name,
                $email,
                $mobile,
                $subject,
                $messageText
            ]);

            $message =
            '<div class="alert alert-success">
            आपका संदेश सफलतापूर्वक भेज दिया गया है।
            </div>';

        }catch(Exception $e){

            $message =
            '<div class="alert alert-danger">
            संदेश भेजने में समस्या हुई।
            </div>';
        }
    }
}

include '../includes/header.php';
?>

<div class="container mt-4">

<div class="row">

<div class="col-lg-8">

<div class="card shadow-sm">

<div class="card-header bg-danger text-white">
<h4 class="mb-0">संपर्क करें</h4>
</div>

<div class="card-body">

<?= $message; ?>

<form method="post">

<div class="mb-3">
<label>नाम</label>
<input
type="text"
name="name"
class="form-control"
required>
</div>

<div class="mb-3">
<label>ईमेल</label>
<input
type="email"
name="email"
class="form-control">
</div>

<div class="mb-3">
<label>मोबाइल नंबर</label>
<input
type="text"
name="mobile"
class="form-control">
</div>

<div class="mb-3">
<label>विषय</label>
<input
type="text"
name="subject"
class="form-control"
required>
</div>

<div class="mb-3">
<label>संदेश</label>
<textarea
name="message"
rows="6"
class="form-control"
required></textarea>
</div>

<button
type="submit"
class="btn btn-danger">

संदेश भेजें

</button>

</form>

</div>

</div>

</div>

<div class="col-lg-4">

<div class="card shadow-sm">

<div class="card-header bg-dark text-white">
कार्यालय जानकारी
</div>

<div class="card-body">

<p>
<strong><?= SITE_NAME; ?></strong>
</p>

<p>
<?= SITE_TAGLINE; ?>
</p>

<p>
वेबसाइट:
<br>
<a href="<?= SITE_URL; ?>">
<?= SITE_URL; ?>
</a>
</p>

<p>
ईमेल:
<br>
info@amarsavera.in
</p>

<p>
मोबाइल:
<br>
+91 7252039902
</p>

</div>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>