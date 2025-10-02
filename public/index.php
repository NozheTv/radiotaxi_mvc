<?php
session_start();

// Definir base URL para redirecciones
define('BASE_URL', 'http://localhost/radiotaxi_mvc/');

// Cargar configuración de DB
require_once __DIR__ . '/../config/database.php';

// Cargar controlador solicitado por query param ?controller=Admin&action=login
$controller = $_GET['controller'] ?? 'Admin';  // Por defecto Admin
$action = $_GET['action'] ?? 'login';           // Por defecto login

// Construir ruta y nombre clase controlador
$controllerClass = $controller . 'Controller';
$controllerFile = __DIR__ . '/../app/Controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    // Crear instancia del controlador pasando la conexión PDO
    $controllerInstance = new $controllerClass($pdo);

    if (method_exists($controllerInstance, $action)) {
        // Llamar el método de acción
        $controllerInstance->$action();
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Acción no encontrada.";
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Controlador no encontrado.";
}
