<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(
!isset($_SESSION['admin_id'])
||
$_SESSION['role']!='super_admin'
)
{
    die('Access Denied');
}

$message='';

/*
|--------------------------------------------------------------------------
| Save Settings
|--------------------------------------------------------------------------
*/

if(isset($_POST['save_settings']))
{

    foreach($_POST as $key=>$value)
    {

        if($key=='save_settings')
        {
            continue;
        }

        $stmt=$pdo->prepare("
        REPLACE INTO system_settings
        (
        setting_key,
        setting_value,
        updated_at
        )
        VALUES
        (
        ?,
        ?,
        NOW()
        )
        ");

        $stmt->execute([
        $key,
        $value
        ]);

    }

    $message=
    'System Settings Updated Successfully';

}

function getSetting($key)
{
    global $pdo;

    $stmt=$pdo->prepare("
    SELECT setting_value
    FROM system_settings
    WHERE setting_key=?
    LIMIT 1
    ");

    $stmt->execute([$key]);

    return $stmt->fetchColumn();
}

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

System Settings

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Company Information

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6 mb-3">

<label>Site Name</label>

<input
type="text"
name="site_name"
class="form-control"
value="<?= getSetting('site_name'); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Tagline</label>

<input
type="text"
name="tagline"
class="form-control"
value="<?= getSetting('tagline'); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Website URL</label>

<input
type="text"
name="website_url"
class="form-control"
value="<?= getSetting('website_url'); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Company Email</label>

<input
type="email"
name="company_email"
class="form-control"
value="<?= getSetting('company_email'); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Mobile Number</label>

<input
type="text"
name="mobile_number"
class="form-control"
value="<?= getSetting('mobile_number'); ?>">

</div>

<div class="col-md-6 mb-3">

<label>WhatsApp Number</label>

<input
type="text"
name="whatsapp_number"
class="form-control"
value="<?= getSetting('whatsapp_number'); ?>">

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

SMTP Settings

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6 mb-3">

<label>SMTP Host</label>

<input
type="text"
name="smtp_host"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>SMTP Port</label>

<input
type="text"
name="smtp_port"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label>Encryption</label>

<select
name="smtp_encryption"
class="form-control">

<option>TLS</option>
<option>SSL</option>

</select>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Advertisement Settings

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label>Default GST (%)</label>

<input
type="number"
name="gst_percentage"
class="form-control"
value="<?= getSetting('gst_percentage') ?: 18; ?>">

</div>

<div class="col-md-6">

<label>Currency</label>

<input
type="text"
name="currency"
class="form-control"
value="INR">

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

HRMS Settings

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label>Employee ID Prefix</label>

<input
type="text"
name="employee_prefix"
class="form-control"
value="AS">

</div>

<div class="col-md-6">

<label>Default Email Password</label>

<input
type="text"
name="default_email_password"
class="form-control"
value="Amar@123">

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-danger text-white">

Security Settings

</div>

<div class="card-body">

<div class="row">

<div class="col-md-4">

<label>Session Timeout (Min)</label>

<input
type="number"
name="session_timeout"
class="form-control"
value="30">

</div>

<div class="col-md-4">

<label>Login Attempts</label>

<input
type="number"
name="max_login_attempts"
class="form-control"
value="5">

</div>

<div class="col-md-4">

<label>2FA</label>

<select
name="enable_2fa"
class="form-control">

<option value="0">
Disabled
</option>

<option value="1">
Enabled
</option>

</select>

</div>

</div>

</div>

</div>

<div class="mt-4">

<button
type="submit"
name="save_settings"
class="btn btn-success btn-lg">

Save All Settings

</button>

</div>

</form>

</div>

<?php include '../layout/footer.php'; ?>