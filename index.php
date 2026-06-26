<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id']))
{
exit;
}

include '../layout/header.php';
?>

<div class="container-fluid">

<div class="row">

<div class="col-md-3">

<div class="card">

<div class="card-body">

<h5>Users</h5>

<a href="../users/index.php">
Manage
</a>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card">

<div class="card-body">

<h5>Rights</h5>

<a href="../users/rights/index.php">
Manage
</a>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card">

<div class="card-body">

<h5>Roles</h5>

<a href="../rbac/roles/index.php">
Manage
</a>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card">

<div class="card-body">

<h5>Activity Logs</h5>

<a href="../activity-logs/index.php">
View
</a>

</div>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>