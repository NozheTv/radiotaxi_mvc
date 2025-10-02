<?php
// Punto de entrada, carga config y Router para procesar URL

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Router.php';

// Instanciar router y cargar controladores según URL

$router = new App\Core\Router();
$router->dispatch($_SERVER['REQUEST_URI']);
