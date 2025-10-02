<?php
// Configuración general y conexión a BD por PDO

define('DB_HOST', 'localhost');
define('DB_NAME', 'radiotaxi_mvc');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Error con la base de datos: ' . $e->getMessage());
}
