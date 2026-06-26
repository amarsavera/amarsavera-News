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
SELECT id,department_name
FROM departments
WHERE status='active'
ORDER BY department_name
")->fetchAll();

$roles = $pdo->query("
SELECT id,role_name
FROM roles
ORDER BY role_name
")->fetchAll();

$designations = $pdo->query("
SELECT id,designation_name
FROM designations
WHERE status='active'
ORDER BY designation_name
")->fetchAll();

$languages = $pdo->query("
SELECT language_code,language_name
FROM languages
WHERE status=1
ORDER BY language_name
")->fetchAll();

$stmt = $pdo->prepare("
SELECT *
FROM users
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$user = $stmt->fetch();

if(!$user){
    die('User Not Found');
}

if($_SERVER['REQUEST_METHOD']=='POST'){

    $stmt = $pdo->prepare("
    UPDATE users
    SET
    name=?,
    mobile=?,
    email=?,
    role_id=?,
    department_id=?,
    designation_id=?,
    preferred_language=?,
    status=?
    WHERE id=?
    ");

    $stmt->execute([

        $_POST['name'],
        $_POST['mobile'],
        $_POST['email'],
        $_POST['role_id'],
        $_POST['department_id'],
        $_POST['designation_id'],
        $_POST['preferred_language'],
        $_POST['status'],
        $id

    ]);

    $log = $pdo->prepare("
    INSERT INTO activity_logs
    (
        user_type,
        user_id,
        module_name,
        action_name,
        record_id,
        remarks,
        ip_address
    )
    VALUES
    (
        ?,?,?,?,?,?,?
    )
    ");

    $log->execute([
        'admin',
        $_SESSION['admin_id'],
        'Users',
        'Update User',
        $id,
        'User Updated',
        $_SERVER['REMOTE_ADDR']
    ]);

    header("Location: view.php?id=".$id);
    exit;
}

include '../layout/header.php';
?><div class="container-fluid"><div class="card shadow-sm"><div class="card-header bg-warning">उपयोगकर्ता संपादन

</div><div class="card-body"><form method="post"><div class="row"><div class="col-md-6 mb-3">
<label>नाम</label>
<input type="text"
name="name"
value="<?= htmlspecialchars($user['name']); ?>"
class="form-control">
</div><div class="col-md-6 mb-3">
<label>मोबाइल</label>
<input type="text"
name="mobile"
value="<?= htmlspecialchars($user['mobile']); ?>"
class="form-control">
</div><div class="col-md-6 mb-3">
<label>ईमेल</label>
<input type="email"
name="email"
value="<?= htmlspecialchars($user['email']); ?>"
class="form-control">
</div><div class="col-md-6 mb-3">
<label>स्थिति</label><select name="status"
class="form-control">

<option value="active"
<?= $user['status']=='active'?'selected':''; ?>>
Active
</option><option value="inactive"
<?= $user['status']=='inactive'?'selected':''; ?>>
Inactive
</option></select></div></div><button
type="submit"
class="btn btn-success">

Update User

</button></form></div></div></div><?php include '../layout/footer.php'; ?>