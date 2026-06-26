<?php

require_once '../includes/config.php';

header('Content-Type:application/json');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("
SELECT *
FROM users
WHERE email=?
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
){

$token = bin2hex(
random_bytes(32)
);

$pdo->prepare("
INSERT INTO api_tokens
(
user_id,
api_token
)

VALUES
(
?,
?
)
")->execute([

$user['id'],
$token

]);

echo json_encode([

'status'=>true,
'token'=>$token

]);

}else{

echo json_encode([

'status'=>false,
'message'=>'अमान्य लॉगिन'

]);

}