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
"revenue-report-".
date('YmdHis').
".csv";

header('Content-Type:text/csv');
header('Content-Disposition:attachment; filename='.$fileName);

$output =
fopen('php://output','w');

fputcsv($output,[

'Booking Code',
'Advertiser',
'Amount',
'GST',
'Total Amount',
'Payment Status',
'Booking Status',
'Payment Date'

]);

$sql = $pdo->query("
SELECT

ab.*,

a.company_name

FROM advertisement_bookings ab

LEFT JOIN advertisers a
ON a.id=ab.advertiser_id

ORDER BY ab.id DESC
");

$totalAmount = 0;
$totalGST = 0;
$totalRevenue = 0;

while($row=$sql->fetch(PDO::FETCH_ASSOC))
{

$totalAmount +=
(float)$row['amount'];

$totalGST +=
(float)$row['gst_amount'];

$totalRevenue +=
(float)$row['total_amount'];

fputcsv($output,[

$row['booking_code'],

$row['company_name'],

$row['amount'],

$row['gst_amount'],

$row['total_amount'],

$row['payment_status'],

$row['status'],

$row['updated_at']
?? $row['created_at']

]);

}

fputcsv($output,['']);
fputcsv($output,['Revenue Summary']);
fputcsv($output,['Total Amount',$totalAmount]);
fputcsv($output,['Total GST',$totalGST]);
fputcsv($output,['Grand Total',$totalRevenue]);

fclose($output);
exit;