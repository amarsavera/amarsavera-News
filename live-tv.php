<?php

require_once '../includes/config.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
SELECT *
FROM live_tv
ORDER BY id DESC
LIMIT 1
");

$tv = $stmt->fetch();

if(!$tv)
{
    echo json_encode([
        'status'=>false,
        'message'=>'No Live TV Found'
    ]);
    exit;
}

echo json_encode([
    'status'=>true,
    'data'=>[
        'id'=>$tv['id'],
        'title'=>$tv['title'],
        'stream_url'=>$tv['stream_url']
    ]
]);