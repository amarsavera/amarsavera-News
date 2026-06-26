<?php

require_once '../includes/config.php';

header('Content-Type: application/json');

try {

    $stmt = $pdo->query("
        SELECT
            id,
            title,
            message,
            created_at
        FROM notifications
        ORDER BY id DESC
        LIMIT 50
    ");

    $notifications = $stmt->fetchAll();

    echo json_encode([
        'status' => true,
        'count'  => count($notifications),
        'data'   => $notifications
    ]);

} catch(Exception $e){

    echo json_encode([
        'status' => false,
        'message' => $e->getMessage()
    ]);

}