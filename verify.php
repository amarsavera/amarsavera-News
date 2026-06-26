<?php

require_once '../includes/config.php';

$code = trim($_GET['code'] ?? '');

$stmt = $pdo->prepare("
SELECT
r.*,
u.name,
u.mobile,
u.email
FROM reporters r
LEFT JOIN users u
ON u.id = r.user_id
WHERE r.id=?
LIMIT 1
");

$stmt->execute([$id]);

$reporter = $stmt->fetch();

include '../includes/header.php';
include '../includes/navbar.php';

?>

<div class="container my-5">

<?php if($data): ?>

<div class="card">

<div class="card-body">

<h3>

प्राधिकरण पत्र सत्यापन

</h3>

<hr>

<p>

पत्र संख्या :
<?= $data['letter_no']; ?>

</p>

<p>

नाम :
<?= htmlspecialchars($data['name']); ?>

</p>

<p>

पद :
<?= htmlspecialchars($data['designation']); ?>

</p>

<p>

जिला :
<?= htmlspecialchars($data['district']); ?>

</p>

<p>

स्थिति :

<?= $data['status']
? 'वैध'
: 'अमान्य'; ?>

</p>

</div>

</div>

<?php else: ?>

<div class="alert alert-danger">

रिकॉर्ड उपलब्ध नहीं है।

</div>

<?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>