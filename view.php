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
u.*,
r.role_name,
d.department_name,
dg.designation_name

FROM users u

LEFT JOIN roles r
ON r.id=u.role_id

LEFT JOIN departments d
ON d.id=u.department_id

LEFT JOIN designations dg
ON dg.id=u.designation_id

WHERE u.id=?
LIMIT 1
");

$stmt->execute([$id]);

$user = $stmt->fetch();

if(!$user){
    die('User Not Found');
}

include '../layout/header.php';
?><div class="container-fluid"><div class="card shadow-sm"><div class="card-header bg-primary text-white">उपयोगकर्ता विवरण

</div><div class="card-body"><table class="table table-bordered"><tr>
<th>UID</th>
<td><?= htmlspecialchars($user['uid']); ?></td>
</tr><tr>
<th>Employee Code</th>
<td><?= htmlspecialchars($user['employee_code']); ?></td>
</tr><tr>
<th>नाम</th>
<td><?= htmlspecialchars($user['name']); ?></td>
</tr><tr>
<th>मोबाइल</th>
<td><?= htmlspecialchars($user['mobile']); ?></td>
</tr><tr>
<th>ईमेल</th>
<td><?= htmlspecialchars($user['email']); ?></td>
</tr><tr>
<th>विभाग</th>
<td><?= htmlspecialchars($user['department_name']); ?></td>
</tr><tr>
<th>रोल</th>
<td><?= htmlspecialchars($user['role_name']); ?></td>
</tr><tr>
<th>पदनाम</th>
<td><?= htmlspecialchars($user['designation_name']); ?></td>
</tr><tr>
<th>भाषा</th>
<td><?= htmlspecialchars($user['preferred_language']); ?></td>
</tr><tr>
<th>स्थिति</th>
<td><?= htmlspecialchars($user['status']); ?></td>
</tr><tr>
<th>Source</th>
<td><?= htmlspecialchars($user['source_system']); ?></td>
</tr></table><a href="update.php?id=<?= $user['id']; ?>"
class="btn btn-primary">

Edit User

</a><a href="index.php"
class="btn btn-secondary">

Back

</a></div></div></div><?php include '../layout/footer.php'; ?>