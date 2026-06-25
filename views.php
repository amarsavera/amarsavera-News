<?php

require_once '../../includes/config.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(empty($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$type = $_GET['type'] ?? 'news';

$title = '';
$data = [];

switch($type){

    case 'news':

        $title = "Pending News";

        $stmt = $pdo->query("
            SELECT
            id,
            title,
            status,
            created_at
            FROM news
            WHERE status='pending'
            ORDER BY id DESC
        ");

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    break;

    case 'epaper':

        $title = "Pending Epaper";

        $stmt = $pdo->query("
            SELECT
            id,
            title,
            status,
            created_at
            FROM epapers
            WHERE status=0
            ORDER BY id DESC
        ");

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    break;

    case 'ads':

        $title = "Pending Advertisements";

        $stmt = $pdo->query("
            SELECT
            id,
            company_name,
            status,
            created_at
            FROM advertisement_bookings
            WHERE status='pending'
            ORDER BY id DESC
        ");

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    break;

    case 'referral':

        $title = "Pending Referral Commission";

        $stmt = $pdo->query("
            SELECT
            id,
            commission_amount,
            status,
            created_at
            FROM referral_commissions
            WHERE status='pending'
            ORDER BY id DESC
        ");

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    break;

    case 'reward':

        $title = "Pending Rewards";

        $stmt = $pdo->query("
            SELECT
            id,
            reward_value,
            status,
            created_at
            FROM user_rewards
            WHERE status='pending'
            ORDER BY id DESC
        ");

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    break;
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title><?= htmlspecialchars($title) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container-fluid mt-4">

<h3>
<?= htmlspecialchars($title) ?>
</h3>

<a href="index.php"
class="btn btn-secondary mb-3">
Back
</a>

<div class="card">

<div class="card-body">

<table class="table table-bordered table-striped">

<thead>

<tr>

<th>ID</th>

<th>Details</th>

<th>Status</th>

<th>Date</th>

<th width="220">
Action
</th>

</tr>

</thead>

<tbody>

<?php foreach($data as $row): ?>

<tr>

<td>
<?= $row['id'] ?>
</td>

<td>

<?php

echo htmlspecialchars(
$row['title']
?? $row['company_name']
?? $row['commission_amount']
?? $row['reward_value']
?? ''
);

?>

</td>

<td>

<?= htmlspecialchars(
$row['status'] ?? 'Pending'
) ?>

</td>

<td>

<?= htmlspecialchars(
$row['created_at']
) ?>

</td>

<td>

<a
href="action.php?action=approve&type=<?= $type ?>&id=<?= $row['id'] ?>"
class="btn btn-success btn-sm">

Approve

</a>

<a
href="action.php?action=reject&type=<?= $type ?>&id=<?= $row['id'] ?>"
class="btn btn-danger btn-sm">

Reject

</a>

<a
href="action.php?action=return&type=<?= $type ?>&id=<?= $row['id'] ?>"
class="btn btn-warning btn-sm">

Return

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</body>

</html>