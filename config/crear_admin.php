<?php
require_once __DIR__ . '/../config/config.php';

// Datos primer administrador
$nombre = "Admin Principal";
$correo = "admin@gmail.com";
$password = "12345678";
$telefono = null;
$direccion = null;
$rol = "administrador";
$estado = "activo";
$plataforma = "web";

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO usuarios (nombre_completo, correo, password, telefono, direccion, rol, estado, plataforma_acceso) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
try {
    $stmt->execute([$nombre, $correo, $passwordHash, $telefono, $direccion, $rol, $estado, $plataforma]);
    echo "Administrador creado exitosamente.";
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo "Error: El correo ya está registrado.";
    } else {
        echo "Error desconocido: " . $e->getMessage();
    }
}
