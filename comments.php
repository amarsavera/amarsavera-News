<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT *
FROM advertisements
WHERE id=?
LIMIT 1
");

$stmt->execute([$id]);

$advertisement = $stmt->fetch();

if(!$advertisement)
{
    die('Advertisement Not Found');
}

if($_SERVER['REQUEST_METHOD']=='POST')
{

    $save = $pdo->prepare("
    INSERT INTO advertisement_comments
    (
        advertisement_id,
        comment_type,
        comment_text,
        created_by,
        created_at
    )
    VALUES
    (
        ?,?,?,?,NOW()
    )
    ");

    $save->execute([

        $id,
        $_POST['comment_type'],
        $_POST['comment_text'],
        $_SESSION['admin_id']

    ]);

    header("Location: comments.php?id=".$id);
    exit;
}

$comments = $pdo->prepare("
SELECT *
FROM advertisement_comments
WHERE advertisement_id=?
ORDER BY id DESC
");

$comments->execute([$id]);

$commentsList = $comments->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="row">

<div class="col-lg-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Add Remark

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Remark Type</label>

<select
name="comment_type"
class="form-control">

<option value="designer">
Designer Remark
</option>

<option value="client">
Client Remark
</option>

<option value="editorial">
Editorial Remark
</option>

<option value="revision">
Revision Remark
</option>

<option value="approval">
Approval Remark
</option>

</select>

</div>

<div class="mb-3">

<label>Remark</label>

<textarea
name="comment_text"
class="form-control"
rows="5"
required></textarea>

</div>

<button
type="submit"
class="btn btn-success">

Save Remark

</button>

</form>

</div>

</div>

</div>

<div class="col-lg-7">

<div class="card shadow">

<div class="card-header bg-dark text-white">

Remark History

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>ID</th>
<th>Type</th>
<th>Remark</th>
<th>User</th>
<th>Date</th>

</tr>

<?php foreach($commentsList as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['comment_type']); ?></td>

<td><?= nl2br(htmlspecialchars($row['comment_text'])); ?></td>

<td><?= $row['created_by']; ?></td>

<td><?= $row['created_at']; ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>