<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: http://localhost/radiotaxi_mvc/public/?controller=Admin&action=login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Administrador</title>
    <link rel="stylesheet" href="http://localhost/radiotaxi_mvc/public/css/admin.css" />
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>RadioTaxi Admin</h2>
            <nav>
                <ul>
                    <li><a href="?controller=Admin&action=dashboard&section=inicio">Inicio</a></li>
                    <li><a href="?controller=Admin&action=dashboard&section=usuarios">Usuarios</a></li>
                    <li><a href="?controller=Admin&action=dashboard&section=taxis">Taxis</a></li>
                    <li><a href="?controller=Admin&action=dashboard&section=pedidos">Pedidos</a></li>
                    <li><a href="?controller=Admin&action=dashboard&section=historial">Historial</a></li>
                    <li><a href="?controller=Admin&action=logout">Cerrar sesión</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <?php
            $section = $_GET['section'] ?? 'inicio';

            // Según sección cargar contenido dinámico
            switch ($section) {
                case 'usuarios':
                    require __DIR__ . '/usuarios.php'; // aquí cargarás la vista de usuarios
                    break;
                case 'taxis':
                    require __DIR__ . '/taxis.php';
                    break;
                case 'pedidos':
                    require __DIR__ . '/pedidos.php';
                    break;
                case 'historial':
                    require __DIR__ . '/historial.php';
                    break;
                case 'inicio':
                default:
                    echo '<h1>Bienvenido al Panel de Administrador</h1>';
                    echo '<p>Seleccione una opción del menú para comenzar.</p>';
                    break;
            }
            ?>
        </main>
    </div>
</body>
</html>
