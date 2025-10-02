<?php
// app/Views/admin/dashboard.php

if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . 'http://localhost/radiotaxi_mvc/public/?controller=Admin&action=login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrador</title>
</head>
<body>
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['admin_nombre']) ?></h1>
    <p>Este es el panel de control del administrador.</p>
    <a href="?controller=Admin&action=logout">Cerrar sesión</a>
</body>
</html>
