<?php
class Router {
    private $routes;
    
    public function __construct() {
        global $routes;
        $this->routes = $routes;
    }
    
    public function dispatch() {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Remove base path if exists
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/') {
            $uri = str_replace($basePath, '', $uri);
            $uri = trim($uri, '/');
        }
        
        // Fallback: se não há URI, verificar parâmetro GET 'route'
        if (empty($uri) && isset($_GET['route'])) {
            $uri = $_GET['route'];
        }
        // 1) Try exact match first (supports nested routes like rental/confirmation)
        $path = $uri;
        if ($path !== '' && isset($this->routes[$path])) {
            $controllerName = $this->routes[$path]['controller'];
            $actionName = $this->routes[$path]['action'];
            $controller = new $controllerName();
            call_user_func([$controller, $actionName]);
            return;
        }

        // 2) Fallback: find the longest route prefix that matches the path
        $matchedRouteKey = null;
        $matchedRemainder = '';

        // Sort route keys by length (desc) to prefer the longest match
        $routeKeys = array_keys($this->routes);
        usort($routeKeys, function($a, $b) {
            return strlen($b) <=> strlen($a);
        });

        foreach ($routeKeys as $key) {
            if ($key === '') continue; // skip empty root here
            if ($path === $key) {
                $matchedRouteKey = $key;
                $matchedRemainder = '';
                break;
            }
            // match prefix "key/" at the start of $path
            if (strpos($path, $key . '/') === 0) {
                $matchedRouteKey = $key;
                $matchedRemainder = substr($path, strlen($key) + 1); // after the slash
                break;
            }
        }

        if ($matchedRouteKey !== null) {
            $controllerName = $this->routes[$matchedRouteKey]['controller'];
            $actionName = $this->routes[$matchedRouteKey]['action'];
            $controller = new $controllerName();
            $params = $matchedRemainder === '' ? [] : explode('/', $matchedRemainder);
            call_user_func_array([$controller, $actionName], $params);
            return;
        }

        // 3) As a last resort, try first segment routing (legacy behavior)
        $segments = $path === '' ? [''] : explode('/', $path);
        $route = $segments[0];
        if (isset($this->routes[$route])) {
            $controllerName = $this->routes[$route]['controller'];
            $actionName = $this->routes[$route]['action'];
            $controller = new $controllerName();
            $params = array_slice($segments, 1);
            call_user_func_array([$controller, $actionName], $params);
            return;
        }

        // 404 Not Found
        http_response_code(404);
        include 'views/errors/404.php';
    }
}
?>
