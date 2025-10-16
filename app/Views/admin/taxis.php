<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=radiotaxi_mvc;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id, placa, modelo, id_estado_taxi, id_conductor FROM radiotaxis";

    $stmt = $pdo->query($sql);
    $taxis = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión o consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Taxis</title>
    <link rel="stylesheet" href="css/admin.css"> 
</head>
<body>

<h2>Lista de Taxis</h2>
<a href="?controller=Taxis&action=crear" class="btn-crear">Agregar Taxi</a>

<table border="1" width="100%">
    <tr>
        <th>Placa</th>
        <th>Modelo</th>
        <th>Estado (ID)</th>
        <th>ID Conductor</th>
        <th>Acciones</th>
    </tr>
    <?php if (!empty($taxis) && is_array($taxis)): ?>
        <?php foreach ($taxis as $taxi): ?>
            <tr>
                <td><?= htmlspecialchars($taxi['placa']) ?></td>
                <td><?= htmlspecialchars($taxi['modelo']) ?></td>
                <td><?= htmlspecialchars($taxi['id_estado_taxi']) ?></td>
                <td><?= htmlspecialchars($taxi['id_conductor']) ?></td>
                <td>
                    <!-- Aquí los botones para cambiar estado o eliminar -->
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">No hay taxis registrados.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
