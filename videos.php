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

if(isset($_POST['save_video']))
{

    $videoCode =
    'YTV-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO youtube_videos
    (

    video_code,

    video_title,

    video_category,

    youtube_url,

    thumbnail,

    seo_tags,

    publish_status,

    views,

    watch_time,

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

    'scheduled',

    0,

    0,

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $videoCode,

        $_POST['video_title'],

        $_POST['video_category'],

        $_POST['youtube_url'],

        $_POST['thumbnail'],

        $_POST['seo_tags'],

        $_SESSION['admin_id']

    ]);

    $message =
    'YouTube Video Added Successfully';

}

$videos = $pdo->query("
SELECT *
FROM youtube_videos
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

YouTube Video Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-danger text-white">

Add YouTube Video

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

<label>Video Category</label>

<select
name="video_category"
class="form-control">

<option value="news_report">
News Report
</option>

<option value="breaking_news">
Breaking News
</option>

<option value="interview">
Interview
</option>

<option value="shorts">
Shorts
</option>

<option value="special_program">
Special Program
</option>

<option value="live_clip">
Live Clip
</option>

</select>

</div>

<div class="col-md-5 mb-3">

<label>YouTube URL</label>

<input
type="url"
name="youtube_url"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Thumbnail</label>

<input
type="text"
name="thumbnail"
class="form-control"
placeholder="thumbnail.jpg">

</div>

<div class="col-md-6 mb-3">

<label>SEO Tags</label>

<input
type="text"
name="seo_tags"
class="form-control"
placeholder="news, up news, amar savera">

</div>

</div>

<button
type="submit"
name="save_video"
class="btn btn-danger">

Save Video

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Video Library

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Title</th>
<th>Category</th>
<th>Views</th>
<th>Watch Time</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($videos as $video): ?>

<tr>

<td><?= $video['video_code']; ?></td>

<td><?= htmlspecialchars($video['video_title']); ?></td>

<td><?= ucwords(str_replace('_',' ',$video['video_category'])); ?></td>

<td><?= number_format($video['views']); ?></td>

<td><?= number_format($video['watch_time']); ?> Min</td>

<td>

<span class="badge bg-warning">

<?= ucfirst($video['publish_status']); ?>

</span>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-warning text-dark">

Video Workflow

</div>

<div class="card-body">

<pre>
News Coverage
      ↓
Video Editing
      ↓
Upload Video
      ↓
YouTube Publish
      ↓
Views & Watch Time
      ↓
Revenue
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Video Features

</div>

<div class="card-body">

<ul>

<li>Video Upload Management</li>

<li>News Video Library</li>

<li>Shorts Management</li>

<li>Video Categories</li>

<li>Thumbnail Management</li>

<li>SEO Tags</li>

<li>Scheduled Publishing</li>

<li>View Tracking</li>

<li>Watch Time Analytics</li>

<li>Video Reports</li>

<li>Revenue Tracking</li>

<li>Content Performance Monitoring</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>