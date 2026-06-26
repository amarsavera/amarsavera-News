<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$roles = $pdo->query("
SELECT
r.*,
d.department_name

FROM roles r

LEFT JOIN departments d
ON d.id=r.department_id

ORDER BY r.role_name ASC
")->fetchAll();

include '../layout/header.php';
?><div class="container-fluid"><div class="d-flex justify-content-between mb-3"><h3>रोल प्रबंधन</h3><a href="create.php"
class="btn btn-success">

<i class="fa fa-plus"></i>
नया रोल

</a></div><div class="card shadow-sm"><div class="card-body"><table class="table table-bordered"><thead class="table-dark"><tr><th>ID</th>
<th>Role Name</th>
<th>Department</th>
<th>Action</th></tr></thead><tbody><?php foreach($roles as $role): ?><tr><td><?= $role['id']; ?></td><td><?= htmlspecialchars($role['role_name']); ?></td><td><?= htmlspecialchars($role['department_name']); ?></td><td><a href="edit.php?id=<?= $role['id']; ?>"
class="btn btn-primary btn-sm">

Edit

</a><a href="permissions.php?id=<?= $role['id']; ?>"
class="btn btn-warning btn-sm">

Permissions

</a></td></tr><?php endforeach; ?></tbody></table></div></div></div><?php include '../layout/footer.php'; ?>