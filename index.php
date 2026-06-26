<?php

require_once '../../includes/config.php';

session_start();

$list=$pdo->query("
SELECT *
FROM teams
ORDER BY id DESC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="card">

<div class="card-header bg-primary text-white">

Teams

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>ID</th>
<th>Team</th>
<th>Status</th>

</tr>

<?php foreach($list as $team): ?>

<tr>

<td><?= $team['id']; ?></td>

<td><?= htmlspecialchars($team['team_name']); ?></td>

<td><?= $team['status']; ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>