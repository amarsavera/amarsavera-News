<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$states = $pdo->query("
SELECT *
FROM states
WHERE status='active'
")->fetchAll();

$divisions = $pdo->query("
SELECT *
FROM divisions
WHERE status='active'
")->fetchAll();

$districts = $pdo->query("
SELECT *
FROM districts
WHERE status='active'
")->fetchAll();

if($_SERVER['REQUEST_METHOD']=='POST'){

    $stmt = $pdo->prepare("
    INSERT INTO tehsils
    (
        state_id,
        division_id,
        district_id,
        tehsil_name,
        status
    )
    VALUES
    (
        ?,?,?,?,?
    )
    ");

    $stmt->execute([

        $_POST['state_id'],
        $_POST['division_id'],
        $_POST['district_id'],
        $_POST['tehsil_name'],
        $_POST['status']

    ]);

    header("Location:index.php");
    exit;
}

include '../layout/header.php';
?>