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

if(isset($_POST['save_playlist']))
{

    $playlistCode =
    'YTPL-'.
    date('Ym').
    '-'.
    rand(1000,9999);

    $stmt = $pdo->prepare("
    INSERT INTO youtube_playlists
    (

    playlist_code,

    playlist_name,

    playlist_category,

    total_videos,

    watch_time,

    seo_keywords,

    status,

    created_by,

    created_at

    )

    VALUES
    (

    ?,

    ?,

    ?,

    0,

    0,

    ?,

    'active',

    ?,

    NOW()

    )

    ");

    $stmt->execute([

        $playlistCode,

        $_POST['playlist_name'],

        $_POST['playlist_category'],

        $_POST['seo_keywords'],

        $_SESSION['admin_id']

    ]);

    $message =
    'Playlist Created Successfully';

}

$playlists = $pdo->query("
SELECT *
FROM youtube_playlists
ORDER BY id DESC
LIMIT 500
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

YouTube Playlist Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-danger text-white">

Create Playlist

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label>Playlist Name</label>

<input
type="text"
name="playlist_name"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label>Playlist Category</label>

<select
name="playlist_category"
class="form-control">

<option value="news_series">
News Series
</option>

<option value="district_news">
District News
</option>

<option value="election_coverage">
Election Coverage
</option>

<option value="special_program">
Special Program
</option>

<option value="interviews">
Interviews
</option>

<option value="documentary">
Documentary
</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label>SEO Keywords</label>

<input
type="text"
name="seo_keywords"
class="form-control"
placeholder="news, amar savera, up news">

</div>

</div>

<button
type="submit"
name="save_playlist"
class="btn btn-danger">

Create Playlist

</button>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Playlist Register

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Code</th>
<th>Playlist Name</th>
<th>Category</th>
<th>Videos</th>
<th>Watch Time</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($playlists as $playlist): ?>

<tr>

<td><?= $playlist['playlist_code']; ?></td>

<td><?= htmlspecialchars($playlist['playlist_name']); ?></td>

<td><?= ucwords(str_replace('_',' ',$playlist['playlist_category'])); ?></td>

<td><?= number_format($playlist['total_videos']); ?></td>

<td><?= number_format($playlist['watch_time']); ?> Min</td>

<td>

<span class="badge bg-success">

<?= ucfirst($playlist['status']); ?>

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

Playlist Workflow

</div>

<div class="card-body">

<pre>
Videos Published
      ↓
Playlist Created
      ↓
Audience Watches
      ↓
Watch Time Growth
      ↓
Revenue Increase
</pre>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-info text-white">

Playlist Features

</div>

<div class="card-body">

<ul>

<li>Playlist Management</li>

<li>News Series Playlists</li>

<li>District News Playlists</li>

<li>Election Coverage Playlists</li>

<li>Special Programs</li>

<li>Playlist Analytics</li>

<li>Watch Time Tracking</li>

<li>Video Organization</li>

<li>SEO Optimization</li>

<li>Playlist Reports</li>

<li>Content Categorization</li>

<li>Audience Retention Tracking</li>

</ul>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>