<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT
s.*,
sp.plan_name

FROM subscribers s

LEFT JOIN subscription_plans sp
ON sp.id=s.plan_id

WHERE s.id=?
LIMIT 1
");

$stmt->execute([$id]);

$subscriber = $stmt->fetch();

if(!$subscriber){
    die('Subscriber Not Found');
}

include '../layout/header.php';
?><div class="container-fluid"><div class="card shadow-sm"><div class="card-header bg-info text-white">Subscriber Details

</div><div class="card-body"><table class="table table-bordered"><tr>
<th>UID</th>
<td><?= htmlspecialchars($subscriber['uid']); ?></td>
</tr><tr>
<th>Name</th>
<td><?= htmlspecialchars($subscriber['name']); ?></td>
</tr><tr>
<th>Mobile</th>
<td><?= htmlspecialchars($subscriber['mobile']); ?></td>
</tr><tr>
<th>Email</th>
<td><?= htmlspecialchars($subscriber['email']); ?></td>
</tr><tr>
<th>Plan</th>
<td><?= htmlspecialchars($subscriber['plan_name']); ?></td>
</tr><tr>
<th>Status</th>
<td><?= htmlspecialchars($subscriber['status']); ?></td>
</tr><tr>
<th>Source System</th>
<td><?= htmlspecialchars($subscriber['source_system']); ?></td>
</tr><tr>
<th>Created</th>
<td><?= htmlspecialchars($subscriber['created_at']); ?></td>
</tr></table><a href="edit.php?id=<?= $subscriber['id']; ?>"
class="btn btn-primary">

Edit Subscriber

</a></div></div></div><?php include '../layout/footer.php'; ?>