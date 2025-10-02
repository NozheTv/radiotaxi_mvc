<h2>Usuarios</h2>
<a href="?controller=Usuarios&action=crear">Crear nuevo usuario</a>
<table border="1" width="100%">
    <tr>
        <th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th>
    </tr>
    <?php if (!empty($usuarios) && is_array($usuarios)): ?>
        <table border="1" width="100%">
            <tr>
                <th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($usuario['correo']) ?></td>
                    <td><?= htmlspecialchars($usuario['rol']) ?></td>
                    <td><?= htmlspecialchars($usuario['estado']) ?></td>
                    <td>
                        <!-- Formularios acciones aquí -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p>No hay usuarios registrados.</p>
    <?php endif; ?>

</table>
