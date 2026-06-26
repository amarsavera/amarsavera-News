<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

$departments = $pdo->query("
SELECT *
FROM departments
WHERE status='active'
ORDER BY department_name ASC
")->fetchAll();

$stmt = $pdo->prepare("
SELECT *
FROM roles
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$role = $stmt->fetch();

if(!$role){
    die('Role Not Found');
}

if($_SERVER['REQUEST_METHOD']=='POST'){

    $stmt = $pdo->prepare("
    UPDATE roles
    SET
    role_name=?,
    department_id=?
    WHERE id=?
    ");

    $stmt->execute([

        $_POST['role_name'],
        $_POST['department_id'],
        $id

    ]);

    header("Location:index.php");
    exit;
}

include '../layout/header.php';
?><div class="container-fluid"><div class="card shadow-sm"><div class="card-header bg-primary text-white">Edit Role

</div><div class="card-body"><form method="post"><div class="mb-3"><label>Role Name</label>

<input
type="text"
name="role_name"
value="<?= htmlspecialchars($role['role_name']); ?>"
class="form-control"
required>

</div><div class="mb-3"><label>Department</label>

<select
name="department_id"
class="form-control">

<?php foreach($departments as $dept): ?><option
value="<?= $dept['id']; ?>"
<?= $role['department_id']==$dept['id']?'selected':''; ?>><?= htmlspecialchars($dept['department_name']); ?></option><?php endforeach; ?></select></div><button
type="submit"
class="btn btn-success">

Update Role

</button></form></div></div></div><?php include '../layout/footer.php'; ?>