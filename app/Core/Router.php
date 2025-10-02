<?php
namespace App\Core;

class Router {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function dispatch($url) {
        $url = trim($url, '/');
        $urlArr = explode('/', $url);

        // Determinar controller, método y parámetros
        if (isset($urlArr[0]) && $urlArr[0] != '') {
            $this->controller = ucfirst($urlArr[0]) . 'Controller';
            unset($urlArr[0]);
        }
        if (isset($urlArr[1])) {
            $this->method = $urlArr[1];
            unset($urlArr[1]);
        }

        $this->params = $urlArr ? array_values($urlArr) : [];

        // Ruta completa del controlador
        $controllerFile = __DIR__ . '/../Controllers/' . $this->controller . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controllerFull = "App\\Controllers\\" . $this->controller;
            $controllerObj = new $controllerFull();

            if (method_exists($controllerObj, $this->method)) {
                call_user_func_array([$controllerObj, $this->method], $this->params);
            } else {
                http_response_code(404);
                echo "Método {$this->method} no encontrado.";
            }
        } else {
            http_response_code(404);
            echo "Controlador {$this->controller} no encontrado.";
        }
    }
}
