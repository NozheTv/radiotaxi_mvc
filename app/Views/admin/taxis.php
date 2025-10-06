<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>lista de taxis</title>
    <link rel="stylesheet" href="css/admin.css"> 
</head>
<h2>Lista de Taxis</h2>
<a href="?controller=Taxis&action=crear" class="btn-crear">Agregar Taxi</a>
<table border="1" width="100%">
    <tr>
        <th>Placa</th>
        <th>Modelo</th>
        <th>Estado</th>
        <th>Conductor</th>
        <th>Acciones</th>
    </tr>
    <?php if (!empty($taxis) && is_array($taxis)): ?>
        <?php foreach ($taxis as $taxi): ?>
            <tr>
                <td><?= htmlspecialchars($taxi['placa']) ?></td>
                <td><?= htmlspecialchars($taxi['modelo']) ?></td>
                <td><?= htmlspecialchars($taxi['estado']) ?></td>
                <td><?= htmlspecialchars($taxi['conductor'] ?? 'Sin asignar') ?></td>
                <td>
                    <form method="POST" action="?controller=Taxis&action=cambiarEstado" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $taxi['id'] ?>">
                        <input type="hidden" name="estado" value="<?= $taxi['estado'] === 'disponible' ? 'no disponible' : 'disponible' ?>">
                        <button type="submit"><?= $taxi['estado'] === 'disponible' ? 'Desactivar' : 'Activar' ?></button>
                    </form>
                    <form method="POST" action="?controller=Taxis&action=eliminar" onsubmit="return confirm('¿Eliminar taxi?');" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $taxi['id'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">No hay taxis registrados.</td></tr>
    <?php endif; ?>
</table>
