<?php

require_once '../../includes/config.php';

session_start();

if(!isset($_SESSION['admin_id']))
{
exit;
}

$fileName =
"outstanding-report-".
date('YmdHis').
".csv";

header('Content-Type:text/csv');
header('Content-Disposition:attachment; filename='.$fileName);

$output =
fopen('php://output','w');

fputcsv($output,[

'Booking Code',
'Advertiser',
'Total Amount',
'Payment Status',
'Booking Status'

]);

$sql=$pdo->query("
SELECT

ab.*,

a.company_name

FROM advertisement_bookings ab

LEFT JOIN advertisers a
ON a.id=ab.advertiser_id

WHERE ab.payment_status!='paid'
OR ab.payment_status IS NULL

ORDER BY ab.id DESC
");

while($row=$sql->fetch(PDO::FETCH_ASSOC))
{

fputcsv($output,[

$row['booking_code'],

$row['company_name'],

$row['total_amount'],

$row['payment_status'],

$row['status']

]);

}

fclose($output);
exit;