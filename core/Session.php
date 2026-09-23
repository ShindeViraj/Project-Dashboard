<?php
namespace Core;

class Session {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
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
        if ($this->has($key)) {
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
}
