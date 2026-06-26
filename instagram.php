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

if(isset($_POST['publish_instagram']))
{

    $postCode =
    'IG-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO social_instagram_posts
    (

    post_code,

    post_title,

    post_type,

    image_url,

    caption,

    hashtags,

    publish_status,

    created_by,

    created_at

    )

    VALUES
    (

    ?,

    ?,

    ?,

    ?,

    ?,

    ?,

    'published',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $postCode,

        $_POST['post_title'],

        $_POST['post_type'],

        $_POST['image_url'],

        $_POST['caption'],

        $_POST['hashtags'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Instagram Content Published Successfully';

}

$posts = $pdo->query("
SELECT *
FROM social_instagram_posts
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Instagram Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-warning text-dark">

Publish Instagram Content

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

<label>Content Type</label>

<select
name="post_type"
class="form-control">

<option value="feed">
Feed Post
</option>

<option value="reel">
Reel
</option>

<option value="story">
Story
</option>

<option value="breaking">
Breaking News Card
</option>

</select>

</div>

<div class="col-md-5 mb-3">

<label>Image / Reel URL</label>

<input
type="text"
name="image_url"
class="form-control">

</div>

<div class="col-md-12 mb-3">

<label>Caption</label>

<textarea
name="caption"
rows="4"
class="form-control"></textarea>

</div>

<div class="col-md-12 mb-3">

<label>Hashtags</label>

<input
type="text"
name="hashtags"
class="form-control"
placeholder="#AmarSavera #News #UPNews">

</div>

</div>

<button
type="submit"
name="publish_instagram"
class="btn btn-warning">

Publish to Instagram

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Instagram Content Register

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

Instagram Workflow

</div>

<div class="card-body">

<pre>
News / Reel Created
        ↓
Instagram Publish
        ↓
Reach & Engagement
        ↓
Follower Growth
        ↓
Analytics
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Instagram Features

</div>

<div class="card-body">

<ul>

<li>Instagram Integration</li>

<li>Reels Publishing</li>

<li>Feed Post Publishing</li>

<li>Story Publishing</li>

<li>Breaking News Graphics</li>

<li>Hashtag Management</li>

<li>Engagement Tracking</li>

<li>Follower Analytics</li>

<li>Auto News Sharing</li>

<li>Campaign Integration</li>

<li>Instagram Reports</li>

<li>Growth Monitoring</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>