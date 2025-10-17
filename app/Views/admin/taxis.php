<?php
define('BASE_URL', 'http://localhost/radiotaxi_mvc/public/');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=radiotaxi_mvc;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // JOIN para mostrar el nombre del estado y del conductor
    $sql = "SELECT r.id, r.placa, r.modelo,
                   r.id_estado_taxi, e.descripcion AS estado,
                   r.id_conductor, u.nombre_completo AS conductor
            FROM radiotaxis r
            LEFT JOIN estados_taxi e ON e.id = r.id_estado_taxi
            LEFT JOIN usuarios u ON u.id = r.id_conductor
            ORDER BY r.id DESC";
    $taxis = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión o consulta: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Taxis</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/admin.css">
</head>
<body>

<h2>Lista de Taxis</h2>
<a href="<?= BASE_URL ?>index.php?controller=Taxis&action=crear" class="btn-crear">Agregar Taxi</a>

<table border="1" width="100%">
    <tr>
        <th>Placa</th>
        <th>Modelo</th>
        <th>Estado</th>
        <th>Conductor</th>
        <th>Acciones</th>
    </tr>
    <?php if (!empty($taxis)): ?>
        <?php foreach ($taxis as $taxi): ?>
            <tr>
                <td><?= htmlspecialchars($taxi['placa']) ?></td>
                <td><?= htmlspecialchars($taxi['modelo']) ?></td>
                <td><?= htmlspecialchars($taxi['estado'] ?? ('ID: '.$taxi['id_estado_taxi'])) ?></td>
                <td><?= htmlspecialchars($taxi['conductor'] ?? ('ID: '.$taxi['id_conductor'])) ?></td>
                <td><!-- botones aquí --></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">No hay taxis registrados.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
