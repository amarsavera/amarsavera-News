<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id']))
{
    exit('Unauthorized Access');
}

$bookingId =
(int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
SELECT

ab.*,

ac.client_name,
ac.contact_person,
ac.mobile,
ac.email,
ac.gst_number,
ac.address,
ac.city,
ac.state

FROM advertisement_bookings ab

LEFT JOIN advertisement_clients ac
ON ac.id=ab.client_id

WHERE ab.id=?
LIMIT 1
");

$stmt->execute([
$bookingId
]);

$invoice =
$stmt->fetch();

if(!$invoice)
{
    die('Invoice Not Found');
}

$invoiceNo =
'INV-'.
date('Y').
'-'.
str_pad(
$invoice['id'],
6,
'0',
STR_PAD_LEFT
);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>

GST Invoice

</title>

<style>

body{
font-family:Arial,sans-serif;
margin:20px;
}

.header{
text-align:center;
border-bottom:3px solid #000;
padding-bottom:10px;
margin-bottom:20px;
}

.company{
font-size:28px;
font-weight:bold;
}

table{
width:100%;
border-collapse:collapse;
}

table th,
table td{
border:1px solid #000;
padding:8px;
}

.footer{
margin-top:40px;
}

</style>

</head>

<body>

<div class="header">

<div class="company">

AMAR SAVERA

</div>

<div>

सत्य के साथ, जनता की आवाज

</div>

<div>

Website:
https://amar-savera.saragone.in

</div>

</div>

<h2 align="center">

GST TAX INVOICE

</h2>

<table>

<tr>

<th width="200">

Invoice Number

</th>

<td>

<?= $invoiceNo; ?>

</td>

</tr>

<tr>

<th>

Invoice Date

</th>

<td>

<?= date('d-m-Y'); ?>

</td>

</tr>

<tr>

<th>

Booking Number

</th>

<td>

<?= htmlspecialchars(
$invoice['booking_number']
); ?>

</td>

</tr>

</table>

<br>

<h3>

Client Details

</h3>

<table>

<tr>
<th>Name</th>
<td><?= htmlspecialchars($invoice['client_name']); ?></td>
</tr>

<tr>
<th>Contact Person</th>
<td><?= htmlspecialchars($invoice['contact_person']); ?></td>
</tr>

<tr>
<th>Mobile</th>
<td><?= htmlspecialchars($invoice['mobile']); ?></td>
</tr>

<tr>
<th>Email</th>
<td><?= htmlspecialchars($invoice['email']); ?></td>
</tr>

<tr>
<th>GST Number</th>
<td><?= htmlspecialchars($invoice['gst_number']); ?></td>
</tr>

<tr>
<th>Address</th>
<td><?= htmlspecialchars($invoice['address']); ?></td>
</tr>

</table>

<br>

<h3>

Advertisement Details

</h3>

<table>

<tr>

<th>Advertisement Title</th>

<th>Type</th>

<th>Banner Size</th>

<th>Publication Date</th>

</tr>

<tr>

<td>

<?= htmlspecialchars(
$invoice['ad_title']
); ?>

</td>

<td>

<?= htmlspecialchars(
$invoice['ad_type']
); ?>

</td>

<td>

<?= htmlspecialchars(
$invoice['banner_size']
); ?>

</td>

<td>

<?= date(
'd-m-Y',
strtotime(
$invoice['publication_date']
)
); ?>

</td>

</tr>

</table>

<br>

<h3>

Billing Summary

</h3>

<table>

<tr>

<th>

Amount

</th>

<td>

₹<?= number_format(
$invoice['amount'],
2
); ?>

</td>

</tr>

<tr>

<th>

GST (18%)

</th>

<td>

₹<?= number_format(
$invoice['gst_amount'],
2
); ?>

</td>

</tr>

<tr>

<th>

Grand Total

</th>

<td>

<strong>

₹<?= number_format(
$invoice['total_amount'],
2
); ?>

</strong>

</td>

</tr>

</table>

<div class="footer">

<table>

<tr>

<td width="50%">

<b>Payment Status:</b>

<?= strtoupper(
$invoice['payment_status']
); ?>

</td>

<td width="50%" align="right">

For Amar Savera

<br><br><br>

Authorized Signatory

</td>

</tr>

</table>

</div>

<br>

<div align="center">

<a
href="payment-link.php?id=<?= $invoice['id']; ?>"
style="
padding:10px 20px;
background:#28a745;
color:#fff;
text-decoration:none;">

Pay Now

</a>

&nbsp;

<button onclick="window.print();">

Print Invoice

</button>

</div>

</body>

</html>