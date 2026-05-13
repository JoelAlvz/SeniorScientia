<?php

class Router {
    private $routes = [];

    public function get($uri, $action) {
        $this->routes['GET'][$this->convertToRegex($uri)] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$this->convertToRegex($uri)] = $action;
    }

    // Función auxiliar para convertir /tutor/alumno/([0-9]+) en una regex válida
    private function convertToRegex($uri) {
        $uri = '/' . ltrim($uri, '/');
        // Escapamos las barras y preparamos la expresión regular
        return "#^" . $uri . "$#";
    }

    public function dispatch($uri, $method) {
        $uri = parse_url($uri, PHP_URL_PATH);

        // Limpiar la URL del prefijo /public
        $parts = explode('/public', $uri, 2);
        $uri = isset($parts[1]) ? $parts[1] : '/';

        // Normalizar la URI
        $uri = '/' . ltrim($uri, '/');
        if (strlen($uri) > 1) {
            $uri = rtrim($uri, '/');
        }

        // Buscamos coincidencia con las rutas registradas
        foreach ($this->routes[$method] as $routeRegex => $action) {
            if (preg_match($routeRegex, $uri, $matches)) {
                // $matches[0] es la URL completa, los siguientes son los parámetros capturados ()
                array_shift($matches); 

                list($controllerName, $methodName) = explode('@', $action);

                require_once __DIR__ . "/../controllers/$controllerName.php";

                $controller = new $controllerName();
                
                // Llamamos al método pasando los parámetros capturados
                return call_user_func_array([$controller, $methodName], $matches);
            }
        }

        // Si no encuentra nada tras el bucle
        echo "404 - Página no encontrada (Ruta: $uri)";
    }
}