<?php
// seed_usuarios.php

$host = 'localhost';
$db   = 'radiotaxi_mvc';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Limpiar tabla usuarios
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0;");
    $pdo->exec("TRUNCATE TABLE usuarios;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");

    echo "Tabla usuarios limpiada\n";

    // Insertar usuario root
    $passwordRoot = password_hash('12345678', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios 
        (nombre_completo, correo, password, telefono, direccion, rol, estado, licencia, plataforma_acceso) 
        VALUES (?, ?, ?, NULL, NULL, ?, ?, NULL, 'web')");

    $stmt->execute(['root', 'root@viacha.local', $passwordRoot, 'administrador', 'activo']);
    echo "Usuario root creado\n";

    // Insertar conductores
    $conductores = [
        ['Juan Pérez', 'juan.perez@viacha.local'],
        ['Carlos Mamani', 'carlos.mamani@viacha.local'],
        ['Luis Quispe', 'luis.quispe@viacha.local']
    ];

    $passwordConductor = password_hash('conductor123', PASSWORD_DEFAULT);
    foreach ($conductores as $c) {
        $stmt->execute([$c[0], $c[1], $passwordConductor, 'conductor', 'activo']);
    }
    echo "Conductores creados\n";

    // Insertar clientes
    $clientes = [
        ['Maria Lopez', 'maria.lopez@viacha.local'],
        ['Pedro García', 'pedro.garcia@viacha.local']
    ];

    $passwordCliente = password_hash('cliente123', PASSWORD_DEFAULT);
    foreach ($clientes as $c) {
        $stmt->execute([$c[0], $c[1], $passwordCliente, 'cliente', 'activo']);
    }
    echo "Clientes creados\n";

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
