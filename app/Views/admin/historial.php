<h2>Historial de Rutas</h2>
<table border="1" width="100%">
    <tr>
        <th>ID Historial</th>
        <th>ID Pedido</th>
        <th>Origen (Lat,Long)</th>
        <th>Destino (Lat,Long)</th>
        <th>Evaluación Cliente</th>
        <th>Evaluación Conductor</th>
        <th>Acciones</th>
    </tr>
    <?php if (!empty($historiales) && is_array($historiales)): ?>
        <?php foreach ($historiales as $historial): ?>
            <tr>
                <td><?= $historial['id'] ?></td>
                <td><?= $historial['id_pedido'] ?></td>
                <td>(<?= $historial['origen_latitud'] ?>, <?= $historial['origen_longitud'] ?>)</td>
                <td>(<?= $historial['destino_latitud'] ?>, <?= $historial['destino_longitud'] ?>)</td>
                <td><?= htmlspecialchars($historial['evaluacion_cliente'] ?? 'Sin evaluar') ?></td>
                <td><?= htmlspecialchars($historial['evaluacion_conductor'] ?? 'Sin evaluar') ?></td>
                <td>
                    <a href="?controller=Historial&action=ver&id=<?= $historial['id_pedido'] ?>">Ver Detalles</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="7">No hay historial de rutas.</td></tr>
    <?php endif; ?>
</table>
