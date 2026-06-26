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

if(isset($_POST['publish_video']))
{

    $videoCode =
    'YT-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO social_youtube_posts
    (

    video_code,

    video_title,

    video_type,

    video_url,

    description,

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

    'published',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $videoCode,

        $_POST['video_title'],

        $_POST['video_type'],

        $_POST['video_url'],

        $_POST['description'],

        $_SESSION['admin_id']

    ]);

    $message =
    'YouTube Content Published Successfully';

}

$videos = $pdo->query("
SELECT *
FROM social_youtube_posts
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

YouTube Channel Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-danger text-white">

Publish YouTube Content

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Video Title</label>

<input
type="text"
name="video_title"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label>Content Type</label>

<select
name="video_type"
class="form-control">

<option value="video">
News Video
</option>

<option value="shorts">
YouTube Shorts
</option>

<option value="live">
Live Stream
</option>

<option value="interview">
Interview
</option>

<option value="podcast">
Podcast
</option>

</select>

</div>

<div class="col-md-5 mb-3">

<label>YouTube URL</label>

<input
type="text"
name="video_url"
class="form-control">

</div>

<div class="col-md-12 mb-3">

<label>Description</label>

<textarea
name="description"
rows="5"
class="form-control"></textarea>

</div>

</div>

<button
type="submit"
name="publish_video"
class="btn btn-danger">

Publish to YouTube

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

YouTube Content Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Video Code</th>
<th>Title</th>
<th>Type</th>
<th>Status</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($videos as $video): ?>

<tr>

<td><?= $video['video_code']; ?></td>

<td><?= htmlspecialchars($video['video_title']); ?></td>

<td><?= ucfirst($video['video_type']); ?></td>

<td>

<span class="badge bg-success">

<?= ucfirst($video['publish_status']); ?>

</span>

</td>

<td><?= $video['created_at']; ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

YouTube Workflow

</div>

<div class="card-body">

<pre>
Video Uploaded
      ↓
YouTube Publish
      ↓
Views & Subscribers
      ↓
Analytics
      ↓
Revenue Tracking
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

YouTube Features

</div>

<div class="card-body">

<ul>

<li>YouTube Channel Integration</li>

<li>Video Publishing</li>

<li>Shorts Publishing</li>

<li>Live Stream Management</li>

<li>Playlist Management</li>

<li>Community Posts</li>

<li>Video Analytics</li>

<li>Subscriber Analytics</li>

<li>Monetization Tracking</li>

<li>YouTube Reports</li>

<li>SEO Optimization</li>

<li>Revenue Dashboard</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>