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
WHERE status='rejected'
ORDER BY id DESC
")->fetchAll();

include '../../layout/header.php';

?>

<div class="container-fluid">

<h3>Rejected News</h3>

<table class="table table-bordered">

<tr>

<th>ID</th>
<th>Title</th>

</tr>

<?php foreach($list as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['title']); ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php include '../../layout/footer.php'; ?>