<?php

require_once '../../includes/config.php';

if(session_status()===PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin_id']))
{
    exit('Unauthorized Access');
}

$fileName =
"advertisement-bookings-".
date('YmdHis').
".csv";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename='.$fileName);

$output =
fopen('php://output', 'w');

fputcsv($output,[

'Booking ID',
'Booking Code',
'Advertiser',
'Amount',
'GST',
'Total Amount',
'Payment Status',
'Booking Status',
'Created Date'

]);

$query = $pdo->query("
SELECT

ab.*,

a.company_name

FROM advertisement_bookings ab

LEFT JOIN advertisers a
ON a.id=ab.advertiser_id

ORDER BY ab.id DESC
");

while($row=$query->fetch(PDO::FETCH_ASSOC))
{

fputcsv($output,[

$row['id'],

$row['booking_code'],

$row['company_name'],

$row['amount'],

$row['gst_amount'],

$row['total_amount'],

$row['payment_status'],

$row['status'],

$row['created_at']

]);

}

fclose($output);
exit;