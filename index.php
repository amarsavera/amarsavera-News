<?php

require_once '../../includes/config.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$users = $pdo->query("
SELECT
id,
name,
mobile,
email,
employee_code,
status,
preferred_language,
created_at
FROM users
ORDER BY id DESC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-3">

<h3>उपयोगकर्ता प्रबंधन</h3>

<a href="add.php" class="btn btn-success">
<i class="fa fa-plus"></i>
नया उपयोगकर्ता
</a>

</div>

<div class="card shadow-sm">

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<th>ID</th>
<th>Name</th>
<th>Mobile</th>
<th>Email</th>
<th>Department</th>
<th>Role</th>
<th>Designation</th>
<th>Status</th>
<th>Language</th>
<th>Action</th>>

</thead>

<tbody>

<?php foreach($users as $user): ?>

<tr>

<td><?= $user['id']; ?></td>

<td><?= htmlspecialchars($user['name']); ?></td>

<td><?= htmlspecialchars($user['mobile']); ?></td>

<td><?= htmlspecialchars($user['email']); ?></td>

<td><?= htmlspecialchars($user['employee_code']); ?></td>

<td><?= htmlspecialchars($user['preferred_language']); ?></td>
<td><?= htmlspecialchars($user['department_name'] ?? '-'); ?></td>

<td><?= htmlspecialchars($user['role_name'] ?? '-'); ?></td>

<td><?= htmlspecialchars($user['designation_name'] ?? '-'); ?></td>
<td>

<?php if($user['status']=='active'): ?>

<span class="badge bg-success">
सक्रिय
</span>

<?php else: ?>

<span class="badge bg-danger">
निष्क्रिय
</span>

<?php endif; ?>

</td>

<td>

<a href="view.php?id=<?= $user['id']; ?>"
class="btn btn-info btn-sm">
देखें
</a>

<a href="edit.php?id=<?= $user['id']; ?>"
class="btn btn-primary btn-sm">
संपादित
</a>

<a href="delete.php?id=<?= $user['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete User?');">
हटाएँ
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>