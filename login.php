<?php

require_once '../includes/config.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD']!='POST')
{
    echo json_encode([
        'status'=>false,
        'message'=>'Invalid Request'
    ]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if(empty($email) || empty($password))
{
    echo json_encode([
        'status'=>false,
        'message'=>'Email and Password Required'
    ]);
    exit;
}

$stmt = $pdo->prepare("
SELECT *
FROM users
WHERE email=?
LIMIT 1
");

$stmt->execute([$email]);

$user = $stmt->fetch();

if(!$user)
{
    echo json_encode([
        'status'=>false,
        'message'=>'User Not Found'
    ]);
    exit;
}

if(!password_verify($password,$user['password']))
{
    echo json_encode([
        'status'=>false,
        'message'=>'Invalid Password'
    ]);
    exit;
}

echo json_encode([
    'status'=>true,
    'message'=>'Login Successful',
    'user'=>[
        'id'=>$user['id'],
        'name'=>$user['name'],
        'email'=>$user['email'],
        'mobile'=>$user['mobile'],
        'role_id'=>$user['role_id']
    ]
]);