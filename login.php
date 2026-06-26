<?php

require_once '../includes/config.php';

session_start();

if(isset($_SESSION['user_id']))
{
    header("Location: dashboard.php");
    exit;
}

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
    SELECT *
    FROM users
    WHERE email=?
    AND status='active'
    LIMIT 1
    ");

    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if(
        $user &&
        password_verify(
            $password,
            $user['password']
        )
    )
    {
        $_SESSION['user_id'] = $user['id'];

        header("Location: dashboard.php");
        exit;
    }

    $error = "Invalid Email or Password";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Admin Login - Amar Savera</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center">

<div class="col-md-5 mt-5">

<div class="card shadow">

<div class="card-header bg-danger text-white text-center">

<h4>

Admin Login

</h4>

</div>

<div class="card-body">

<?php if($error): ?>

<div class="alert alert-danger">

<?= $error; ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
type="submit"
class="btn btn-danger w-100">

Login

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>