<?php
namespace Core;

class Middleware {
    public static function authRequired() {
        
        if (!Session::has('user_id')) {
            header("Location: /login");
            exit;
        }
    }

    public static function adminRequired() {
        
        if (Session::get('user_role') !== 'admin') {
            header("Location: /dashboard");
            exit;
        }
    }

    public static function guestOnly() {
        
        if (Session::has('user_id')) {
            header("Location: /dashboard");
            exit;
        }
    }
}
