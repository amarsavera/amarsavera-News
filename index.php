<?php

require_once '../../includes/config.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(empty($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Dashboard Counts
|--------------------------------------------------------------------------
*/

$pendingNews = 0;
$pendingEpaper = 0;
$pendingAds = 0;
$pendingReferral = 0;
$pendingRewards = 0;

try{

    $stmt = $pdo->query("
        SELECT COUNT(*) total
        FROM news
        WHERE status='pending'
    ");

    $pendingNews = $stmt->fetch()['total'];

}catch(Exception $e){}

try{

    $stmt = $pdo->query("
        SELECT COUNT(*) total
        FROM epapers
        WHERE status=0
    ");

    $pendingEpaper = $stmt->fetch()['total'];

}catch(Exception $e){}

try{

    $stmt = $pdo->query("
        SELECT COUNT(*) total
        FROM advertisement_bookings
        WHERE status='pending'
    ");

    $pendingAds = $stmt->fetch()['total'];

}catch(Exception $e){}

try{

    $stmt = $pdo->query("
        SELECT COUNT(*) total
        FROM referral_commissions
        WHERE status='pending'
    ");

    $pendingReferral = $stmt->fetch()['total'];

}catch(Exception $e){}

try{

    $stmt = $pdo->query("
        SELECT COUNT(*) total
        FROM user_rewards
        WHERE status='pending'
    ");

    $pendingRewards = $stmt->fetch()['total'];

}catch(Exception $e){}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Approval Center</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

.card-box{
    border-radius:12px;
    padding:20px;
    color:#fff;
    margin-bottom:20px;
}

.news{
    background:#dc3545;
}

.epaper{
    background:#0d6efd;
}

.ads{
    background:#198754;
}

.referral{
    background:#6f42c1;
}

.reward{
    background:#fd7e14;
}

.count{
    font-size:32px;
    font-weight:700;
}

</style>

</head>

<body>

<div class="container-fluid mt-4">

<h3 class="mb-4">
Approval Center
</h3>

<div class="row">

<div class="col-md-4">

<div class="card-box news">

<div>Pending News</div>

<div class="count">
<?= $pendingNews ?>
</div>

<a href="view.php?type=news"
class="btn btn-light btn-sm mt-2">
View
</a>

</div>

</div>

<div class="col-md-4">

<div class="card-box epaper">

<div>Pending Epaper</div>

<div class="count">
<?= $pendingEpaper ?>
</div>

<a href="view.php?type=epaper"
class="btn btn-light btn-sm mt-2">
View
</a>

</div>

</div>

<div class="col-md-4">

<div class="card-box ads">

<div>Pending Advertisements</div>

<div class="count">
<?= $pendingAds ?>
</div>

<a href="view.php?type=ads"
class="btn btn-light btn-sm mt-2">
View
</a>

</div>

</div>

<div class="col-md-6">

<div class="card-box referral">

<div>Pending Referral Commission</div>

<div class="count">
<?= $pendingReferral ?>
</div>

<a href="view.php?type=referral"
class="btn btn-light btn-sm mt-2">
View
</a>

</div>

</div>

<div class="col-md-6">

<div class="card-box reward">

<div>Pending Rewards</div>

<div class="count">
<?= $pendingRewards ?>
</div>

<a href="view.php?type=reward"
class="btn btn-light btn-sm mt-2">
View
</a>

</div>

</div>

</div>

</div>

</body>

</html>