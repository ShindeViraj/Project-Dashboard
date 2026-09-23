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
            
            // Standalone views that don't use the master layout
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
}
