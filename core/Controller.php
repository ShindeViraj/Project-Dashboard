<?php
namespace Core;

class Controller {
    protected function view($viewName, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';
        
        if (file_exists($viewPath)) {
            ob_start();
            require $viewPath;
            $content = ob_get_clean();
            
            if (strpos($viewName, 'auth/') === 0 || strpos($viewName, 'report') !== false) {
                echo $content;
            } else {
                require __DIR__ . '/../views/layouts/app.php';
            }
        } else {
            die("View $viewName not found");
        }
    }

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    protected function input($key, $default = null) {
        $val = $_POST[$key] ?? $_GET[$key] ?? $default;
        return Session::sanitize($val);
    }
    
    protected function requireCsrf() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            $sessionToken = Session::get('csrf_token');
            if (empty($token) || empty($sessionToken) || !hash_equals($sessionToken, $token)) {
                Session::flash('error', 'Invalid CSRF token. Please try again.');
                $this->redirect($_SERVER['HTTP_REFERER'] ?? '/dashboard');
            }
        }
    }
}
