<?php

require_once 'includes/config.php';

$live=$pdo->query("
SELECT *
FROM live_tv
WHERE status=1
LIMIT 1
")->fetch();

include 'includes/header.php';

?>

<div class="container mt-4">

<h2 class="mb-3">

Live TV

</h2>

<div class="ratio ratio-16x9">

<iframe
src="<?= htmlspecialchars($live['youtube_url'] ?? ''); ?>"
allowfullscreen>
</iframe>

</div>

</div>

<?php include 'includes/footer.php'; ?>