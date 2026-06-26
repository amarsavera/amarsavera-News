<?php

require_once '../../includes/config.php';

$data = $pdo->query("
SELECT *
FROM seo_settings
LIMIT 1
")->fetch();

include '../layout/header.php';
?>

<h3 class="mb-4">
SEO नियंत्रण केंद्र
</h3>

<form method="POST">

<div class="card">

<div class="card-body">

<input
type="text"
class="form-control mb-3"
placeholder="साइट शीर्षक">

<textarea
class="form-control mb-3"
rows="4"
placeholder="Meta Description">
</textarea>

<textarea
class="form-control mb-3"
rows="4"
placeholder="Meta Keywords">
</textarea>

<button
class="btn btn-success">

सहेजें

</button>

</div>

</div>

</form>

<?php include '../layout/footer.php'; ?>