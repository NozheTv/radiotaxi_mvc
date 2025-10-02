<?php
// Archivo config/database.php

// Parámetros para la conexión
define('DB_HOST', 'localhost');
define('DB_NAME', 'radiotaxi_mvc');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8');

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,          // Manejo de errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Fetch tipo asociativo
        PDO::ATTR_EMULATE_PREPARES => false,                  // Usar consultas preparadas nativas
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    die('Error al conectar a la base de datos: ' . $e->getMessage());
}
