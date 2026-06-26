<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$subscribers = $pdo->query("
SELECT
s.*,
sp.plan_name

FROM subscribers s

LEFT JOIN subscription_plans sp
ON sp.id=s.plan_id

ORDER BY s.id DESC
")->fetchAll();

include '../layout/header.php';
?><div class="container-fluid"><div class="d-flex justify-content-between mb-3"><h3>Subscriber Management</h3><a href="create.php"
class="btn btn-success">

<i class="fa fa-plus"></i>
Add Subscriber

</a></div><div class="card shadow-sm"><div class="card-body"><table class="table table-bordered"><thead class="table-dark"><tr><th>ID</th>
<th>UID</th>
<th>Name</th>
<th>Mobile</th>
<th>Plan</th>
<th>Status</th>
<th>Action</th></tr></thead><tbody><?php foreach($subscribers as $row): ?><tr><td><?= $row['id']; ?></td><td><?= htmlspecialchars($row['uid']); ?></td><td><?= htmlspecialchars($row['name']); ?></td><td><?= htmlspecialchars($row['mobile']); ?></td><td><?= htmlspecialchars($row['plan_name']); ?></td><td><?= htmlspecialchars($row['status']); ?></td><td><a href="view.php?id=<?= $row['id']; ?>"
class="btn btn-info btn-sm">

View

</a><a href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

Edit

</a></td></tr><?php endforeach; ?></tbody></table></div></div></div><?php include '../layout/footer.php'; ?>