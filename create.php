<?php

require_once '../../../includes/config.php';
require_once '../../includes/auth.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    header("Location: /admin/index.php");
    exit;
}
if($_SERVER['REQUEST_METHOD']=='POST')
{

$stmt=$pdo->prepare("
INSERT INTO roles
(
role_name,
status
)
VALUES
(
?,1
)
");

$stmt->execute([
$_POST['role_name']
]);

header("Location:index.php");
exit;

}

include '../../layout/header.php';

?>

<div class="container-fluid">

<form method="POST">

<input
type="text"
name="role_name"
class="form-control mb-3"
placeholder="Role Name"
required>

<button
class="btn btn-danger">

Create Role

</button>

</form>

</div>

<?php include '../../layout/footer.php'; ?>