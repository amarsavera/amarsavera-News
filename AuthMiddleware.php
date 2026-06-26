<?php

class AuthMiddleware
{
    public static function check()
    {
        if(
            empty(
                $_SESSION['admin_id']
            )
        ){
            header(
                'Location:/admin/index.php'
            );
            exit;
        }
    }
}