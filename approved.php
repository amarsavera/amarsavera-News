<?php

require_once '../../../includes/config.php';
require_once '../../includes/auth.php';

<?php

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    header("Location: /admin/index.php");
    exit;
}

$list=$pdo->query("
SELECT *
FROM news
WHERE status='approved'
ORDER BY id DESC
")->fetchAll();

include '../../layout/header.php';

?>

<div class="container-fluid">

<h3>Approved News</h3>

<table class="table table-bordered">

<tr>

<th>ID</th>
<th>Title</th>
<th>Date</th>

</tr>

<?php foreach($list as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['title']); ?></td>

<td><?= $row['published_at']; ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php include '../../layout/footer.php'; ?>