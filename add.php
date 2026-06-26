<?php
session_start();

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}
?>

require_once '../../includes/config.php';

if(isset($_POST['save'])){

$stmt = $pdo->prepare("
INSERT INTO reporters
(
name,
mobile,
district,
email,
password,
status
)

VALUES
(
?,
?,
?,
?,
?,
1
)
");

$stmt->execute([

$_POST['name'],
$_POST['mobile'],
$_POST['district'],
$_POST['email'],
password_hash(
$_POST['password'],
PASSWORD_DEFAULT
)

]);

header("Location:index.php");
exit;

}

include '../layout/header.php';
?>

<h3 class="mb-4">
नया रिपोर्टर जोड़ें
</h3>

<form method="POST">

<div class="card">

<div class="card-body">

<input
type="text"
name="name"
class="form-control mb-3"
placeholder="रिपोर्टर नाम"
required>

<input
type="text"
name="mobile"
class="form-control mb-3"
placeholder="मोबाइल नंबर"
required>

<input
type="text"
name="district"
class="form-control mb-3"
placeholder="जिला"
required>

<input
type="email"
name="email"
class="form-control mb-3"
placeholder="ईमेल">

<input
type="password"
name="password"
class="form-control mb-3"
placeholder="पासवर्ड"
required>

<button
type="submit"
name="save"
class="btn btn-success">

रिपोर्टर सहेजें

</button>

</div>

</div>

</form>

<?php include '../layout/footer.php'; ?>