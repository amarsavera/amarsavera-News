<?php

require_once '../includes/config.php';

$users=$pdo->query("
SELECT u.*,r.role_name
FROM users u
LEFT JOIN roles r
ON r.id=u.role_id
ORDER BY u.id DESC
")->fetchAll();

?>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Status</th>
</tr>

<?php foreach($users as $user): ?>

<tr>

<td><?= $user['id'] ?></td>
<td><?= $user['name'] ?></td>
<td><?= $user['email'] ?></td>
<td><?= $user['role_name'] ?></td>
<td><?= $user['status'] ?></td>

</tr>

<?php endforeach; ?>

</table>