<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$departments = $pdo->query("
SELECT *
FROM departments
WHERE status='active'
ORDER BY department_name
")->fetchAll();

if($_SERVER['REQUEST_METHOD']=='POST'){

    $stmt = $pdo->prepare("
    INSERT INTO roles
    (
        role_name,
        department_id
    )
    VALUES
    (
        ?,?
    )
    ");

    $stmt->execute([

        $_POST['role_name'],
        $_POST['department_id']

    ]);

    header("Location:index.php");
    exit;
}

include '../layout/header.php';
?>