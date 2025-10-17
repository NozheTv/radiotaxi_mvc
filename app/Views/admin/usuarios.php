<?php
// cargar_usuarios.php

// Incluir configuración de base de datos si la tienes (opcional)
// require_once 'config/database.php';

try {
    // Usar usuario 'root' y password '' (vacío) como definiste antes
    $pdo = new PDO('mysql:host=localhost;dbname=radiotaxi_mvc;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query('SELECT nombre_completo, correo, rol, estado FROM usuarios');
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <link rel="stylesheet" href="css/admin.css"> 
</head>
<body>

<h2>Usuarios</h2>
<a href="?controller=Usuarios&action=crear" class="btn-crear">Crear nuevo usuario</a>

<?php if (!empty($usuarios)): ?>
    <table border="1" width="100%">
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['nombre_completo']) ?></td>
                <td><?= htmlspecialchars($usuario['correo']) ?></td>
                <td><?= htmlspecialchars($usuario['rol']) ?></td>
                <td><?= htmlspecialchars($usuario['estado']) ?></td>
                <td>
                    <!-- Aquí botones para editar, borrar, etc. -->
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No hay usuarios registrados.</p>
<?php endif; ?>

</body>
</html>
