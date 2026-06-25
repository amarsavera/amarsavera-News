<?php

require_once '../../includes/config.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(empty($_SESSION['admin_id'])){
    header("Location: ../index.php");
    exit;
}

$action = $_GET['action'] ?? '';
$type   = $_GET['type'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

$userId = $_SESSION['admin_id'];

if($id <= 0){

    die('Invalid Request');

}

try{

    $pdo->beginTransaction();

    switch($type){

        /*
        |--------------------------------------------------------------------------
        | NEWS
        |--------------------------------------------------------------------------
        */

        case 'news':

            if($action === 'approve'){

                $stmt = $pdo->prepare("
                    UPDATE news
                    SET status='approved'
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

            elseif($action === 'reject'){

                $stmt = $pdo->prepare("
                    UPDATE news
                    SET status='rejected'
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

            elseif($action === 'return'){

                $stmt = $pdo->prepare("
                    UPDATE news
                    SET status='draft'
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

        break;

        /*
        |--------------------------------------------------------------------------
        | EPAPER
        |--------------------------------------------------------------------------
        */

        case 'epaper':

            if($action === 'approve'){

                $stmt = $pdo->prepare("
                    UPDATE epapers
                    SET status=1
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

            elseif($action === 'reject'){

                $stmt = $pdo->prepare("
                    UPDATE epapers
                    SET status=2
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

        break;

        /*
        |--------------------------------------------------------------------------
        | ADS
        |--------------------------------------------------------------------------
        */

        case 'ads':

            if($action === 'approve'){

                $stmt = $pdo->prepare("
                    UPDATE advertisement_bookings
                    SET status='approved'
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

            elseif($action === 'reject'){

                $stmt = $pdo->prepare("
                    UPDATE advertisement_bookings
                    SET status='rejected'
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

        break;

        /*
        |--------------------------------------------------------------------------
        | REFERRAL
        |--------------------------------------------------------------------------
        */

        case 'referral':

            if($action === 'approve'){

                $stmt = $pdo->prepare("
                    UPDATE referral_commissions
                    SET status='approved'
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

            elseif($action === 'reject'){

                $stmt = $pdo->prepare("
                    UPDATE referral_commissions
                    SET status='rejected'
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

        break;

        /*
        |--------------------------------------------------------------------------
        | REWARD
        |--------------------------------------------------------------------------
        */

        case 'reward':

            if($action === 'approve'){

                $stmt = $pdo->prepare("
                    UPDATE user_rewards
                    SET status='issued'
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

            elseif($action === 'reject'){

                $stmt = $pdo->prepare("
                    UPDATE user_rewards
                    SET status='cancelled'
                    WHERE id=?
                ");

                $stmt->execute([$id]);

            }

        break;
    }

    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    */

    $log = $pdo->prepare("
        INSERT INTO activity_logs
        (
            user_id,
            activity_type,
            activity_description,
            ip_address,
            created_at
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

    $log->execute([
        $userId,
        strtoupper($action),
        strtoupper($type).' '.$action,
        $_SERVER['REMOTE_ADDR'] ?? ''
    ]);

    /*
    |--------------------------------------------------------------------------
    | Workflow Action Log
    |--------------------------------------------------------------------------
    */

    $workflow = $pdo->prepare("
        INSERT INTO workflow_actions
        (
            item_id,
            item_type,
            approved_by,
            action,
            created_at
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

    $workflow->execute([
        $id,
        $type,
        $userId,
        $action
    ]);

    $pdo->commit();

}catch(Exception $e){

    $pdo->rollBack();

    die($e->getMessage());

}

header("Location:view.php?type=".$type);
exit;