<?php

class Router {
    private $routes = [];

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

public function dispatch($uri, $method) {
    $uri = parse_url($uri, PHP_URL_PATH);

    // 🔥 QUITA TODO HASTA /public
    $parts = explode('/public', $uri, 2);
    $uri = isset($parts[1]) ? $parts[1] : '/';

    // Normalizar
    $uri = '/' . ltrim($uri, '/');
    $uri = rtrim($uri, '/');

    if ($uri === '') {
        $uri = '/';
    }

    if (isset($this->routes[$method][$uri])) {
        $action = $this->routes[$method][$uri];

        list($controller, $method) = explode('@', $action);

        require_once __DIR__ . "/../controllers/$controller.php";

        $controller = new $controller();
        $controller->$method();
    } else {
        echo "404 - Página no encontrada";
    }
}
}


?>