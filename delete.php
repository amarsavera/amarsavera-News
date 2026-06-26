<?php

require_once '../includes/config.php';

$id=(int)($_GET['id'] ?? 0);

if($id)
{
    $stmt=$pdo->prepare("
    DELETE FROM advertisements
    WHERE id=?
    ");

    $stmt->execute([$id]);
}

header("Location:index.php");
exit;