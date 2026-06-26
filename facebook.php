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

$message='';

if(isset($_POST['publish_post']))
{

    $postCode =
    'FB-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO social_facebook_posts
    (

    post_code,

    post_title,

    post_content,

    post_type,

    publish_status,

    scheduled_at,

    created_by,

    created_at

    )

    VALUES
    (

    ?,

    ?,

    ?,

    ?,

    'published',

    ?,

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $postCode,

        $_POST['post_title'],

        $_POST['post_content'],

        $_POST['post_type'],

        $_POST['scheduled_at'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Facebook Post Published Successfully';

}

$posts = $pdo->query("
SELECT *
FROM social_facebook_posts
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Facebook Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Publish Facebook Post

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Post Title</label>

<input
type="text"
name="post_title"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Post Type</label>

<select
name="post_type"
class="form-control">

<option value="news">News</option>
<option value="breaking">Breaking News</option>
<option value="live">Live Update</option>
<option value="promotion">Promotion</option>

</select>

</div>

<div class="col-md-5 mb-3">

<label>Schedule Time</label>

<input
type="datetime-local"
name="scheduled_at"
class="form-control">

</div>

<div class="col-md-12 mb-3">

<label>Post Content</label>

<textarea
name="post_content"
rows="5"
class="form-control"
required></textarea>

</div>

</div>

<button
type="submit"
name="publish_post"
class="btn btn-primary">

Publish to Facebook

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Facebook Post Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>Post Code</th>
<th>Title</th>
<th>Type</th>
<th>Status</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($posts as $post): ?>

<tr>

<td><?= $post['post_code']; ?></td>

<td><?= htmlspecialchars($post['post_title']); ?></td>

<td><?= ucfirst($post['post_type']); ?></td>

<td>

<span class="badge bg-success">

<?= ucfirst($post['publish_status']); ?>

</span>

</td>

<td><?= $post['created_at']; ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Facebook Workflow

</div>

<div class="card-body">

<pre>
News Published
      ↓
Facebook Auto Post
      ↓
Audience Reach
      ↓
Likes / Comments
      ↓
Analytics
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Facebook Features

</div>

<div class="card-body">

<ul>

<li>Facebook Page Integration</li>

<li>Auto News Posting</li>

<li>Manual Post Publishing</li>

<li>Breaking News Push</li>

<li>Featured News Sharing</li>

<li>Facebook Live Integration</li>

<li>Comment Monitoring</li>

<li>Engagement Tracking</li>

<li>Audience Analytics</li>

<li>Post Scheduling</li>

<li>Campaign Support</li>

<li>Performance Reports</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>