<?php
session_start();

if(!isset($_SESSION['admin_id']))
{
    header("Location: ../index.php");
    exit;
}
?>

require_once '../../includes/config.php';

/*
|--------------------------------------------------------------------------
| Advertisement Rotation Engine
|--------------------------------------------------------------------------
|
| Usage:
|
| include 'admin/advertisement/ad-rotation.php';
|
| echo getAdvertisement('homepage_top');
|
*/

function getAdvertisement($positionCode,$device='desktop')
{
    global $pdo;

    /*
    |--------------------------------------------------------------------------
    | Active Campaign Query
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
    SELECT

    a.id AS campaign_id,
    a.priority,
    a.target_impressions,
    a.target_clicks,

    p.position_code,

    b.id AS banner_id,
    b.banner_file,
    b.banner_type,

    bk.booking_number

    FROM advertisements a

    INNER JOIN advertisement_positions p
    ON p.id=a.position_id

    INNER JOIN advertisement_banners b
    ON b.campaign_id=a.id

    INNER JOIN advertisement_bookings bk
    ON bk.id=a.booking_id

    WHERE

    a.status='active'

    AND b.status='active'

    AND p.position_code=?

    AND b.banner_type=?

    AND CURDATE()
    BETWEEN a.start_date
    AND a.end_date

    ORDER BY

    a.priority ASC,
    RAND()

    LIMIT 20

    ");

    $stmt->execute([

        $positionCode,
        $device

    ]);

    $ads =
    $stmt->fetchAll();

    if(empty($ads))
    {
        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | Smart Rotation
    |--------------------------------------------------------------------------
    */

    $selected =
    $ads[array_rand($ads)];

    /*
    |--------------------------------------------------------------------------
    | Save Impression
    |--------------------------------------------------------------------------
    */

    $impression = $pdo->prepare("
    INSERT INTO advertisement_impressions
    (

    campaign_id,
    banner_id,

    ip_address,
    user_agent,

    viewed_at

    )

    VALUES
    (

    ?,
    ?,

    ?,
    ?,

    NOW()

    )

    ");

    $impression->execute([

        $selected['campaign_id'],

        $selected['banner_id'],

        $_SERVER['REMOTE_ADDR']
        ?? '',

        $_SERVER['HTTP_USER_AGENT']
        ?? ''

    ]);

    /*
    |--------------------------------------------------------------------------
    | Update Counter
    |--------------------------------------------------------------------------
    */

    $pdo->prepare("
    UPDATE advertisements
    SET impressions=impressions+1
    WHERE id=?
    ")->execute([

        $selected['campaign_id']

    ]);

    $bannerUrl =
    SITE_URL.
    '/uploads/advertisements/'.
    $selected['banner_file'];

    $clickUrl =
    SITE_URL.
    '/advertisement/click.php?id='.
    $selected['banner_id'];

    return '

    <div class="amar-savera-ad">

        <a
        href="'.$clickUrl.'"
        target="_blank">

            <img
            src="'.$bannerUrl.'"

            class="img-fluid"

            loading="lazy"

            alt="Advertisement">

        </a>

    </div>

    ';

}

/*
|--------------------------------------------------------------------------
| Mobile Detection
|--------------------------------------------------------------------------
*/

function advertisementDevice()
{

    $agent =
    strtolower(
    $_SERVER['HTTP_USER_AGENT']
    ?? ''
    );

    if(
        strpos($agent,'android')!==false
        ||
        strpos($agent,'iphone')!==false
        ||
        strpos($agent,'mobile')!==false
    )
    {
        return 'mobile';
    }

    return 'desktop';

}