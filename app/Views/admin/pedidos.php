<h2>Gestión de Pedidos</h2>

<table border="1" width="100%">
    <tr>
        <th>ID Pedido</th>
        <th>Cliente</th>
        <th>Taxi asignado</th>
        <th>Origen</th>
        <th>Destino</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php if (!empty($pedidos) && is_array($pedidos)): ?>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= $pedido['id'] ?></td>
                <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                <td><?= htmlspecialchars($pedido['taxi'] ?? 'Sin asignar') ?></td>
                <td>(<?= $pedido['origen_latitud'] ?>, <?= $pedido['origen_longitud'] ?>)</td>
                <td>(<?= $pedido['destino_latitud'] ?>, <?= $pedido['destino_longitud'] ?>)</td>
                <td><?= $pedido['id_estado_pedido'] ?></td>
                <td>
                    <?php if (empty($pedido['taxi'])): ?>
                    <form method="POST" action="?controller=Pedidos&action=asignarTaxi">
                        <input type="hidden" name="id_pedido" value="<?= $pedido['id'] ?>">
                        <input type="text" name="id_taxi" placeholder="ID Taxi para asignar" required>
                        <button type="submit">Asignar Taxi</button>
                    </form>
                    <?php endif; ?>
                    <form method="POST" action="?controller=Pedidos&action=actualizarEstado">
                        <input type="hidden" name="id_pedido" value="<?= $pedido['id'] ?>">
                        <select name="estado">
                            <option value="1" <?= $pedido['id_estado_pedido'] == 1 ? 'selected' : '' ?>>Solicitado</option>
                            <option value="2" <?= $pedido['id_estado_pedido'] == 2 ? 'selected' : '' ?>>Asignado</option>
                            <option value="3" <?= $pedido['id_estado_pedido'] == 3 ? 'selected' : '' ?>>Cancelado</option>
                            <option value="4" <?= $pedido['id_estado_pedido'] == 4 ? 'selected' : '' ?>>Finalizado</option>
                        </select>
                        <button type="submit">Actualizar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="7">No hay pedidos registrados.</td></tr>
    <?php endif; ?>
</table>
