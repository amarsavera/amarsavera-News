<?php

require_once '../includes/config.php';

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];

$stmt = $pdo->prepare("
SELECT *
FROM users
WHERE id=?
LIMIT 1
");

$stmt->execute([$userId]);

$user = $stmt->fetch();

?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid mt-4">

<div class="row">

<div class="col-lg-3">

<div class="card shadow">

<div class="card-body text-center">

<h4>

<?= htmlspecialchars($user['name']); ?>

</h4>

<hr>

<p>

<?= htmlspecialchars($user['email']); ?>

</p>

</div>

</div>

</div>

<div class="col-lg-9">

<div class="row">

<div class="col-md-3 mb-4">

<div class="card shadow border-0">

<div class="card-body text-center">

<h2>

<?= $pdo->query("
SELECT COUNT(*)
FROM news
")->fetchColumn(); ?>

</h2>

<p>Total News</p>

</div>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="card shadow border-0">

<div class="card-body text-center">

<h2>

<?= $pdo->query("
SELECT COUNT(*)
FROM reporters
")->fetchColumn(); ?>

</h2>

<p>Reporters</p>

</div>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="card shadow border-0">

<div class="card-body text-center">

<h2>

<?= $pdo->query("
SELECT COUNT(*)
FROM advertisements
")->fetchColumn(); ?>

</h2>

<p>Advertisements</p>

</div>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="card shadow border-0">

<div class="card-body text-center">

<h2>

<?= $pdo->query("
SELECT COUNT(*)
FROM users
")->fetchColumn(); ?>

</h2>

<p>Users</p>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-danger text-white">

Admin Dashboard

</div>

<div class="card-body">

Welcome to Amar Savera Admin Panel

</div>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>