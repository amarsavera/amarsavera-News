<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once '../includes/config.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(isset($_SESSION['admin_id'])){
    header("Location: dashboard.php");
    exit;
}

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if(empty($email) || empty($password)){

        $error = "ईमेल और पासवर्ड दर्ज करें";

    }else{

        $stmt = $pdo->prepare("
            SELECT *
            FROM users
            WHERE email = ?
            AND status = 'active'
            LIMIT 1
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){

    if($password === $user['password']){

        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        $_SESSION['role_id'] = $user['role_id'];

        header("Location: dashboard.php");
        exit;

    }else{

        $error = "गलत पासवर्ड";

    }

}else{

    $error = "यूजर नहीं मिला";

}

    }

}

?>
<!DOCTYPE html>
<html lang="hi">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Login - Amar Savera</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    margin:0;
    padding:0;
    background:#f4f4f4;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    font-family:'Nirmala UI',sans-serif;
}

.login-box{
    width:100%;
    max-width:450px;
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 20px rgba(0,0,0,.15);
}

.logo{
    text-align:center;
    font-size:40px;
    font-weight:800;
    color:#d50000;
}

.tagline{
    text-align:center;
    margin-bottom:25px;
    color:#555;
}

.btn-login{
    background:#d50000;
    color:#fff;
    border:none;
}

.btn-login:hover{
    background:#b30000;
    color:#fff;
}

</style>

</head>

<body>

<div class="login-box">

<div class="logo">
अमर सवेरा
</div>

<div class="tagline">
सत्य के साथ, जनता की आवाज
</div>

<?php if(!empty($error)): ?>
<div class="alert alert-danger">
<?= htmlspecialchars($error); ?>
</div>
<?php endif; ?>

<form method="post">

<div class="mb-3">
<label class="form-label">ईमेल</label>
<input
type="email"
name="email"
class="form-control"
required>
</div>

<div class="mb-3">
<label class="form-label">पासवर्ड</label>
<input
type="password"
name="password"
class="form-control"
required>
</div>

<button
type="submit"
class="btn btn-login w-100">
लॉगिन करें
</button>

</form>

</div>

</body>
</html>