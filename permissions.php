<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$roleId = (int)($_GET['id'] ?? 0);

$role = $pdo->prepare("
SELECT *
FROM roles
WHERE id=?
");

$role->execute([$roleId]);

$role = $role->fetch();

if(!$role){
    die('Role Not Found');
}

$permissions = $pdo->query("
SELECT *
FROM permissions
ORDER BY module_name, permission_name
")->fetchAll();

if($_SERVER['REQUEST_METHOD']=='POST'){

    $pdo->prepare("
    DELETE FROM role_permissions
    WHERE role_id=?
    ")->execute([$roleId]);

    if(!empty($_POST['permissions'])){

        $insert = $pdo->prepare("
        INSERT INTO role_permissions
        (
            role_id,
            permission_id
        )
        VALUES
        (
            ?,?
        )
        ");

        foreach($_POST['permissions'] as $permissionId){

            $insert->execute([
                $roleId,
                $permissionId
            ]);
        }
    }

    header("Location:index.php");
    exit;
}

$currentPermissions = $pdo->prepare("
SELECT permission_id
FROM role_permissions
WHERE role_id=?
");

$currentPermissions->execute([$roleId]);

$currentPermissions =
$currentPermissions->fetchAll(PDO::FETCH_COLUMN);

include '../layout/header.php';
?><div class="container-fluid"><div class="card shadow-sm"><div class="card-header bg-warning">Role Permissions :

<?= htmlspecialchars($role['role_name']); ?></div><div class="card-body"><form method="post"><div class="row"><?php foreach($permissions as $permission): ?><div class="col-md-4 mb-2"><label><input
type="checkbox"
name="permissions[]"
value="<?= $permission['id']; ?>"

<?= in_array(
$permission['id'],
$currentPermissions
) ? 'checked' : ''; ?>><?= htmlspecialchars($permission['module_name']); ?>- 

<?= htmlspecialchars($permission['permission_name']); ?></label></div><?php endforeach; ?></div><hr><button
type="submit"
class="btn btn-success">

Save Permissions

</button></form></div></div></div><?php include '../layout/footer.php'; ?>