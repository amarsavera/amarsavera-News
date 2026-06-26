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

<div class="col-md-3 mb-3">

<div class="card">

<div class="card-body text-center">

<h5>User Rights</h5>

<a
href="../users/rights/index.php"
class="btn btn-danger">

Manage

</a>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card">

<div class="card-body text-center">

<h5>Override Control</h5>

<a
href="override.php"
class="btn btn-primary">

Open

</a>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card">

<div class="card-body text-center">

<h5>Approval Center</h5>

<a
href="../approval-center/index.php"
class="btn btn-success">

Open

</a>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card">

<div class="card-body text-center">

<h5>System Audit</h5>

<a
href="../audit/index.php"
class="btn btn-dark">

Open

</a>

</div>

</div>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>