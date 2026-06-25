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

$currentMonth = date('m');
$currentYear  = date('Y');

/*
|--------------------------------------------------------------------------
| Approve Commission
|--------------------------------------------------------------------------
*/

if(isset($_GET['approve']))
{

    $id=(int)$_GET['approve'];

    $approve=$pdo->prepare("
    UPDATE executive_commissions
    SET

    status='approved',
    approved_by=?,
    approved_at=NOW()

    WHERE id=?
    ");

    $approve->execute([

        $_SESSION['admin_id'],
        $id

    ]);

    $message='Commission Approved';

}

/*
|--------------------------------------------------------------------------
| Generate Commission
|--------------------------------------------------------------------------
*/

if(isset($_GET['generate']))
{

    $executives=$pdo->query("
    SELECT

    executive_code,

    SUM(total_amount) revenue,

    SUM(paid_amount) collection

    FROM advertisement_bookings

    GROUP BY executive_code

    ")->fetchAll();

    foreach($executives as $exec)
    {

        $adCommission =
        ($exec['revenue'] * 5)/100;

        $collectionCommission =
        ($exec['collection'] * 2)/100;

        $totalCommission =
        $adCommission +
        $collectionCommission;

        $check=$pdo->prepare("
        SELECT id
        FROM executive_commissions
        WHERE employee_code=?
        AND commission_month=?
        AND commission_year=?
        ");

        $check->execute([

            $exec['executive_code'],
            $currentMonth,
            $currentYear

        ]);

        if(!$check->fetch())
        {

            $insert=$pdo->prepare("
            INSERT INTO executive_commissions
            (

            employee_code,

            commission_month,
            commission_year,

            advertisement_commission,
            collection_commission,

            total_commission,

            status,

            created_at

            )

            VALUES
            (

            ?,
            ?,
            ?,

            ?,
            ?,

            ?,

            'pending',

            NOW()

            )
            ");

            $insert->execute([

                $exec['executive_code'],

                $currentMonth,
                $currentYear,

                $adCommission,
                $collectionCommission,

                $totalCommission

            ]);

        }

    }

    $message='Commission Generated Successfully';

}

$commissions=$pdo->query("
SELECT *
FROM executive_commissions
ORDER BY id DESC
")->fetchAll();

include '../layout/header.php';

?>

<div class="container-fluid">

<h3 class="mb-4">

Executive Commission Management

</h3>

<?php if($message): ?>

<div class="alert alert-success">

<?= $message; ?>

</div>

<?php endif; ?>

<div class="mb-3">

<a
href="?generate=1"
class="btn btn-success">

Generate Commission

</a>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">

Commission Ledger

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Executive</th>
<th>Month</th>
<th>Ad Commission</th>
<th>Collection Commission</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($commissions as $row): ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= htmlspecialchars(
$row['employee_code']
); ?>

</td>

<td>

<?= $row['commission_month']; ?>

/
<?= $row['commission_year']; ?>

</td>

<td>

₹<?= number_format(
$row['advertisement_commission'],
2
); ?>

</td>

<td>

₹<?= number_format(
$row['collection_commission'],
2
); ?>

</td>

<td>

<strong>

₹<?= number_format(
$row['total_commission'],
2
); ?>

</strong>

</td>

<td>

<?php

if($row['status']=='approved')
{
echo '<span class="badge bg-success">
Approved
</span>';
}
elseif($row['status']=='paid')
{
echo '<span class="badge bg-primary">
Paid
</span>';
}
else
{
echo '<span class="badge bg-warning">
Pending
</span>';
}

?>

</td>

<td>

<?php if(
$row['status']=='pending'
): ?>

<a
href="?approve=<?= $row['id']; ?>"
class="btn btn-success btn-sm">

Approve

</a>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-success text-white">

Payroll Sync Status

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th width="250">

Approved Commission

</th>

<td>

₹<?= number_format(

$pdo->query("
SELECT
IFNULL(
SUM(total_commission),
0
)
FROM executive_commissions
WHERE status='approved'
")->fetchColumn(),

2

); ?>

</td>

</tr>

<tr>

<th>

Paid Commission

</th>

<td>

₹<?= number_format(

$pdo->query("
SELECT
IFNULL(
SUM(total_commission),
0
)
FROM executive_commissions
WHERE status='paid'
")->fetchColumn(),

2

); ?>

</td>

</tr>

<tr>

<th>

Pending Commission

</th>

<td>

₹<?= number_format(

$pdo->query("
SELECT
IFNULL(
SUM(total_commission),
0
)
FROM executive_commissions
WHERE status='pending'
")->fetchColumn(),

2

); ?>

</td>

</tr>

</table>

</div>

</div>

</div>

<?php include '../layout/footer.php'; ?>