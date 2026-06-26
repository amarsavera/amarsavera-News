<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(
!isset($_SESSION['admin_id'])
||
$_SESSION['role']!='super_admin'
)
{
    die('Access Denied');
}

$message='';

/*
|--------------------------------------------------------------------------
| Create Backup
|--------------------------------------------------------------------------
*/

if(isset($_POST['create_backup']))
{

    $backupName =
    'backup_'.
    date('Ymd_His').
    '.sql';

    $backupPath =
    '../../backups/'.
    $backupName;

    if(!is_dir('../../backups'))
    {
        mkdir(
        '../../backups',
        0777,
        true
        );
    }

    $command =

    "mysqldump -u ".
    DB_USER.
    " -p'".
    DB_PASS.
    "' ".
    DB_NAME.
    " > ".
    $backupPath;

    exec($command);

    $stmt=$pdo->prepare("
    INSERT INTO backup_logs
    (

    backup_name,

    backup_type,

    created_by,

    created_at

    )

    VALUES
    (

    ?,

    'database',

    ?,

    NOW()

    )
    ");

    $stmt->execute([

        $backupName,

        $_SESSION['admin_id']

    ]);

    $message =
    'Database Backup Created Successfully';

}

$backups=$pdo->query("
SELECT *
FROM backup_logs
ORDER BY id DESC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Backup Manager

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="row">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Create Backup

</div>

<div class="card-body">

<form method="POST">

<button
type="submit"
name="create_backup"
class="btn btn-success btn-lg">

Create Full Database Backup

</button>

</form>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-warning text-dark">

Backup Policy

</div>

<div class="card-body">

<ul>

<li>Daily Database Backup</li>

<li>Weekly Media Backup</li>

<li>Monthly Full Backup</li>

<li>Cloud Backup Support</li>

<li>Restore Point Tracking</li>

<li>Disaster Recovery Ready</li>

</ul>

</div>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Backup History

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Backup Name</th>

<th>Type</th>

<th>Date</th>

<th>Download</th>

</tr>

</thead>

<tbody>

<?php foreach($backups as $backup): ?>

<tr>

<td>

<?= $backup['id']; ?>

</td>

<td>

<?= htmlspecialchars(
$backup['backup_name']
); ?>

</td>

<td>

<?= ucfirst(
$backup['backup_type']
); ?>

</td>

<td>

<?= $backup['created_at']; ?>

</td>

<td>

<a
href="../../backups/<?= $backup['backup_name']; ?>"
class="btn btn-primary btn-sm">

Download

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-danger text-white">

Disaster Recovery Status

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th width="300">Database Backup</th>
<td>
<span class="badge bg-success">
Protected
</span>
</td>
</tr>

<tr>
<th>Media Backup</th>
<td>
<span class="badge bg-success">
Protected
</span>
</td>
</tr>

<tr>
<th>Cloud Replication</th>
<td>
<span class="badge bg-warning">
Pending Setup
</span>
</td>
</tr>

<tr>
<th>Recovery Points</th>
<td>
Available
</td>
</tr>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>