<?php

require_once '../includes/config.php';

header('Content-Type: application/json');

$response = [
    'status' => false,
    'message' => 'Unauthorized'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === '' || $password === '') {

    $response['message'] = 'Email and Password required';

    echo json_encode($response);
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

if (!$user) {

    $response['message'] = 'User not found';

    echo json_encode($response);
    exit;
}

if (!password_verify($password,$user['password'])) {

    $response['message'] = 'Invalid password';

    echo json_encode($response);
    exit;
}

$response = [
    'status' => true,
    'message' => 'Login Success',
    'user' => [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role_id' => $user['role_id']
    ]
];

echo json_encode($response);