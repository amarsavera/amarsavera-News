<?php

require_once '../../includes/config.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

/* ==========================
   DROPDOWNS
========================== */

$departments = $pdo->query("
SELECT id,department_name
FROM departments
WHERE status='active'
ORDER BY department_name ASC
")->fetchAll();

$roles = $pdo->query("
SELECT id,role_name
FROM roles
ORDER BY role_name ASC
")->fetchAll();

$designations = $pdo->query("
SELECT id,designation_name
FROM designations
WHERE status='active'
ORDER BY designation_name ASC
")->fetchAll();

$languages = $pdo->query("
SELECT language_code,language_name
FROM languages
WHERE status=1
ORDER BY language_name ASC
")->fetchAll();

$managers = $pdo->query("
SELECT id,name
FROM users
WHERE status='active'
ORDER BY name ASC
")->fetchAll();

$message = '';

/* ==========================
   SAVE USER
========================== */

if($_SERVER['REQUEST_METHOD']=='POST'){

    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);

    $role_id = (int)$_POST['role_id'];
    $department_id = (int)$_POST['department_id'];
    $designation_id = (int)$_POST['designation_id'];

    $reporting_to = !empty($_POST['reporting_to'])
        ? (int)$_POST['reporting_to']
        : null;

    $preferred_language = $_POST['preferred_language'];
    $status = $_POST['status'];

    $password = password_hash(
        $_POST['password'],
        PASSWORD_DEFAULT
    );

    /* UID */

    $nextId = $pdo->query("
    SELECT IFNULL(MAX(id),0)+1
    FROM users
    ")->fetchColumn();

    $uid = 'SGN'.str_pad(
        $nextId,
        7,
        '0',
        STR_PAD_LEFT
    );

    /* Employee Code */

    $employee_code =
    'AS-'.date('Y').'-'.
    str_pad(
        $nextId,
        5,
        '0',
        STR_PAD_LEFT
    );

    $stmt = $pdo->prepare("
    INSERT INTO users
    (
        uid,
        source_system,
        name,
        mobile,
        email,
        password,
        role_id,
        department_id,
        designation_id,
        employee_code,
        reporting_to,
        preferred_language,
        status
    )
    VALUES
    (
        ?,?,?,?,?,?,?,?,?,?,?,?,?
    )
    ");

    $stmt->execute([
        $uid,
        'AMAR_SAVERA',
        $name,
        $mobile,
        $email,
        $password,
        $role_id,
        $department_id,
        $designation_id,
        $employee_code,
        $reporting_to,
        $preferred_language,
        $status
    ]);

    $recordId = $pdo->lastInsertId();

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
        'Create User',
        $recordId,
        'New User Created',
        $_SERVER['REMOTE_ADDR']
    ]);

    header("Location: index.php");
    exit;
}

include '../layout/header.php';
?><div class="container-fluid"><div class="card shadow-sm"><div class="card-header bg-dark text-white">
नया उपयोगकर्ता
</div><div class="card-body"><form method="post"><div class="row"><div class="col-md-6 mb-3">
<label>नाम</label>
<input type="text"
name="name"
class="form-control"
required>
</div><div class="col-md-6 mb-3">
<label>मोबाइल</label>
<input type="text"
name="mobile"
class="form-control"
required>
</div><div class="col-md-6 mb-3">
<label>ईमेल</label>
<input type="email"
name="email"
class="form-control"
required>
</div><div class="col-md-6 mb-3">
<label>पासवर्ड</label>
<input type="password"
name="password"
class="form-control"
required>
</div></div><button
type="submit"
class="btn btn-success">

सहेजें

</button></form></div></div></div><?php include '../layout/footer.php'; ?>