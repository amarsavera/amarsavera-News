<?php

require_once '../includes/config.php';

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../admin/index.php");
    exit;
}

$message='';

/* Add Advertiser */

if(isset($_POST['save']))
{
    $name   = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $email  = trim($_POST['email']);
    $company = trim($_POST['company']);

    $stmt = $pdo->prepare("
    INSERT INTO advertisers
    (
        name,
        mobile,
        email,
        company_name,
        created_at
    )
    VALUES
    (
        ?,?,?,?,NOW()
    )
    ");

    $stmt->execute([
        $name,
        $mobile,
        $email,
        $company
    ]);

    $message = "Advertiser सफलतापूर्वक जोड़ा गया";
}

/* List */

$advertisers = [];

try{

$advertisers = $pdo->query("
SELECT *
FROM advertisers
ORDER BY id DESC
")->fetchAll();

}catch(Exception $e){
}

?>

<!DOCTYPE html>
<html lang="hi">

<head>

<meta charset="utf-8">

<title>Advertiser Management</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<h2 class="mb-4">
Advertiser Management
</h2>

<?php if($message): ?>

<div class="alert alert-success">
<?= $message; ?>
</div>

<?php endif; ?>

<div class="card mb-4">

<div class="card-header">
नया Advertiser जोड़ें
</div>

<div class="card-body">

<form method="post">

<div class="row">

<div class="col-md-6 mb-3">

<label>नाम</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>मोबाइल</label>

<input
type="text"
name="mobile"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Company Name</label>

<input
type="text"
name="company"
class="form-control">

</div>

</div>

<button
type="submit"
name="save"
class="btn btn-success">

Advertiser Save

</button>

</form>

</div>

</div>

<div class="card">

<div class="card-header">
सभी Advertisers
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>ID</th>
<th>Name</th>
<th>Mobile</th>
<th>Email</th>
<th>Company</th>

</tr>

<?php foreach($advertisers as $row): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['name']); ?></td>

<td><?= htmlspecialchars($row['mobile']); ?></td>

<td><?= htmlspecialchars($row['email']); ?></td>

<td><?= htmlspecialchars($row['company_name']); ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

</body>
</html>