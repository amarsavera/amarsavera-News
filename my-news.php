<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}

$employeeCode =
$_SESSION['employee_code'] ?? '';

$status =
$_GET['status'] ?? '';

$sql = "
SELECT
id,
title,
status,
views,
created_at,
published_at
FROM news
WHERE reporter_code=?
";

$params = [$employeeCode];

if(!empty($status))
{
    $sql .= " AND status=?";
    $params[] = $status;
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$newsList = $stmt->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<div class="d-flex justify-content-between mb-3">

<h3>

My News

</h3>

<div>

<a href="?status=draft"
class="btn btn-secondary">

Draft

</a>

<a href="?status=pending"
class="btn btn-warning">

Pending

</a>

<a href="?status=published"
class="btn btn-success">

Published

</a>

<a href="?status=rejected"
class="btn btn-danger">

Rejected

</a>

<a href="my-news.php"
class="btn btn-dark">

All

</a>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Reporter News List

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Title</th>
<th>Status</th>
<th>Views</th>
<th>Date</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($newsList as $news): ?>

<tr>

<td>

<?= $news['id']; ?>

</td>

<td>

<?= htmlspecialchars($news['title']); ?>

</td>

<td>

<?php

switch($news['status'])
{
    case 'published':
        echo '<span class="badge bg-success">Published</span>';
    break;

    case 'pending':
        echo '<span class="badge bg-warning">Pending</span>';
    break;

    case 'rejected':
        echo '<span class="badge bg-danger">Rejected</span>';
    break;

    default:
        echo '<span class="badge bg-secondary">Draft</span>';
}

?>

</td>

<td>

<?= number_format($news['views']); ?>

</td>

<td>

<?= date(
'd-m-Y',
strtotime($news['created_at'])
); ?>

</td>

<td>

<a
href="../news/view.php?id=<?= $news['id']; ?>"
class="btn btn-primary btn-sm">

View

</a>

<?php if(
$news['status']!='published'
): ?>

<a
href="../news/edit.php?id=<?= $news['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

<?php if(empty($newsList)): ?>

<tr>

<td colspan="6"
class="text-center">

No News Found

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

<?php

$totalNews = $pdo->prepare("
SELECT COUNT(*)
FROM news
WHERE reporter_code=?
");

$totalNews->execute([
$employeeCode
]);

$publishedNews = $pdo->prepare("
SELECT COUNT(*)
FROM news
WHERE reporter_code=?
AND status='published'
");

$publishedNews->execute([
$employeeCode
]);

$totalViews = $pdo->prepare("
SELECT IFNULL(SUM(views),0)
FROM news
WHERE reporter_code=?
");

$totalViews->execute([
$employeeCode
]);

?>

<div class="card shadow mt-4">

<div class="card