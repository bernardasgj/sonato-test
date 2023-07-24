<?php
class Router {
    protected $routes = [];

    public function get($uri, $controllerAction) {
        $this->routes['GET'][$uri] = $controllerAction;
    }

    public function post($uri, $controllerAction) {
        $this->routes['POST'][$uri] = $controllerAction;
    }

    public function dispatch() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        if (array_key_exists($uri, $this->routes[$method])) {
            $controllerAction = $this->routes[$method][$uri];            
        } else {
            $baseUri = strtok($uri, '?');
            if (array_key_exists($baseUri, $this->routes[$method])) {
                $controllerAction = $this->routes[$method][$baseUri];
            } else {
                header("HTTP/1.0 404 Not Found");
                echo '404 Not Found';
                return;
            }
        }
    
        list($controllerName, $action) = explode('@', $controllerAction);
        $controllerName = 'App\\Controllers\\' . $controllerName;
        $controller = new $controllerName();
        $controller->$action();
    }
}
