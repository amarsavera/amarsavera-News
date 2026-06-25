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

$clients = $pdo->query("
SELECT
id,
client_name
FROM advertisement_clients
WHERE status='active'
ORDER BY client_name
")->fetchAll();

if(isset($_POST['save_booking']))
{

    $bookingNo =
    'ASAD'.
    date('Ymd').
    rand(1000,9999);

    $amount =
    (float)$_POST['amount'];

    $gst =
    round(
    ($amount*18)/100,
    2
    );

    $grandTotal =
    $amount + $gst;

    $stmt = $pdo->prepare("
    INSERT INTO advertisement_bookings
    (

    booking_number,
    client_id,

    ad_title,
    ad_type,
    banner_size,

    publication_date,

    amount,
    gst_amount,
    total_amount,

    executive_code,

    payment_status,
    booking_status,

    created_at

    )

    VALUES
    (

    ?,?,
    ?,?,
    ?,
    ?,
    ?,?,
    ?,
    ?,
    'pending',
    'booked',
    NOW()

    )
    ");

    $stmt->execute([

        $bookingNo,

        $_POST['client_id'],

        $_POST['ad_title'],

        $_POST['ad_type'],

        $_POST['banner_size'],

        $_POST['publication_date'],

        $amount,

        $gst,

        $grandTotal,

        $_POST['executive_code']

    ]);

    $bookingId =
    $pdo->lastInsertId();

    $message =
    'Advertisement Booked Successfully';

}

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Create Advertisement Booking

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Advertisement Booking Form

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>

Client

</label>

<select
name="client_id"
class="form-control"
required>

<option value="">

Select Client

</option>

<?php foreach($clients as $client): ?>

<option
value="<?= $client['id']; ?>">

<?= htmlspecialchars(
$client['client_name']
); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>

Executive Code

</label>

<input
type="text"
name="executive_code"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>

Advertisement Title

</label>

<input
type="text"
name="ad_title"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>

Advertisement Type

</label>

<select
name="ad_type"
class="form-control">

<option>Website Banner</option>
<option>Breaking News Banner</option>
<option>Homepage Banner</option>
<option>Category Banner</option>
<option>Sponsored News</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>

Banner Size

</label>

<select
name="banner_size"
class="form-control">

<option>728x90</option>
<option>300x250</option>
<option>970x250</option>
<option>1200x300</option>
<option>Custom</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>

Publication Date

</label>

<input
type="date"
name="publication_date"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>

Amount (Without GST)

</label>

<input
type="number"
step="0.01"
name="amount"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>

Duration (Days)

</label>

<input
type="number"
name="duration_days"
class="form-control"
value="30">

</div>

</div>

<button
type="submit"
name="save_booking"
class="btn btn-success">

Save Booking

</button>

</form>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>