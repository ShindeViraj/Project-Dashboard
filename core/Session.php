<?php
namespace Core;

class Session {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', 3600);
            session_set_cookie_params([
                'lifetime' => 3600,
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            session_start();
        }

        // 1 Hour Session Timeout
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
            session_unset();
            session_destroy();
            session_start();
        }
        $_SESSION['LAST_ACTIVITY'] = time();
        
        // Generate CSRF Token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function set($key, $val) {
        $_SESSION[$key] = $val;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public static function flash($key, $val) {
        $_SESSION['_flash'][$key] = $val;
    }

    public static function getFlash($key) {
        if (isset($_SESSION['_flash'][$key])) {
            $val = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $val;
        }
        return null;
    }
    
    public static function clearFlash() {
        if (isset($_SESSION['_flash'])) {
            unset($_SESSION['_flash']);
        }
    }
    
    public static function sanitize($input) {
        if (is_array($input)) {
            foreach ($input as $k => $v) {
                $input[$k] = self::sanitize($v);
            }
            return $input;
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
