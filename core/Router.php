<?php
namespace Core;

class Router {
    private $routes = [];

    public function get($route, $action) {
        $this->addRoute('GET', $route, $action);
    }

    public function post($route, $action) {
        $this->addRoute('POST', $route, $action);
    }

    private function addRoute($method, $route, $action) {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'action' => $action
        ];
    }

    public function dispatch($uri, $method) {
        // Strip query string if exists
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                // Convert {id} to regex capturing group
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route['route']);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $uri, $matches)) {
                    // Extract named parameters
                    $params = [];
                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $params[$key] = $value;
                        }
                    }

                    if (is_callable($route['action'])) {
                        return call_user_func_array($route['action'], array_values($params));
                    }

                    // Parse action Controller@method
                    list($controller, $actionMethod) = explode('@', $route['action']);
                    $controllerClass = "Controllers\\$controller";

                    if (class_exists($controllerClass)) {
                        $controllerInstance = new $controllerClass();
                        if (method_exists($controllerInstance, $actionMethod)) {
                            return call_user_func_array([$controllerInstance, $actionMethod], array_values($params));
                        }
                    }
                    break;
                }
            }
        }
        
        // 404 fallback
        http_response_code(404);
        echo "404 Not Found";
    }
}
