<?php
// seed_data.php

$host = 'localhost';
$db   = 'radiotaxi_db';  // Cambia según tu DB
$user = 'root';          // Usuario MySQL
$pass = '';              // Contraseña MySQL
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Crear usuario root admin
    $password = password_hash('12345678', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, correo, password, rol, estado) VALUES (?, ?, ?, ?, ?)");

    $stmt->execute(['root', 'root@viacha.local', $password, 'administrador', 'activo']);

    echo "Usuario root creados\n";

    // Crear algunos conductores
    $conductores = [
        ['Juan Pérez', 'juan.perez@viacha.local'],
        ['Carlos Mamani', 'carlos.mamani@viacha.local'],
        ['Luis Quispe', 'luis.quispe@viacha.local'],
    ];

    $stmtConductor = $pdo->prepare("INSERT INTO usuarios (nombre_completo, correo, password, rol, estado) VALUES (?, ?, ?, 'conductor', 'activo')");
    $passConductor = password_hash('conductor123', PASSWORD_DEFAULT);

    foreach ($conductores as $conductor) {
        $stmtConductor->execute([$conductor[0], $conductor[1], $passConductor]);
    }

    echo "Conductores creados\n";

    // Crear algunos taxis asociados a conductores
    $stmtConductorId = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmtTaxi = $pdo->prepare("INSERT INTO taxis (placa, modelo, estado, id_conductor) VALUES (?, ?, 'disponible', ?)");

    $taxis = [
        ['VIACHA-01', 'Toyota Corolla', 'juan.perez@viacha.local'],
        ['VIACHA-02', 'Hyundai Accent', 'carlos.mamani@viacha.local'],
        ['VIACHA-03', 'Suzuki Swift', 'luis.quispe@viacha.local'],
    ];

    foreach ($taxis as $taxi) {
        $stmtConductorId->execute([$taxi[2]]);
        $conductorId = $stmtConductorId->fetchColumn();
        $stmtTaxi->execute([$taxi[0], $taxi[1], $conductorId]);
    }

    echo "Taxis creados\n";

    // Crear algunos clientes
    $clientes = [
        ['Maria Lopez', 'maria.lopez@viacha.local'],
        ['Pedro García', 'pedro.garcia@viacha.local'],
    ];

    $stmtCliente = $pdo->prepare("INSERT INTO usuarios (nombre_completo, correo, password, rol, estado) VALUES (?, ?, ?, 'cliente', 'activo')");
    $passCliente = password_hash('cliente123', PASSWORD_DEFAULT);

    foreach ($clientes as $cliente) {
        $stmtCliente->execute([$cliente[0], $cliente[1], $passCliente]);
    }

    echo "Clientes creados\n";

    // Crear pedidos simulados con ubicaciones cerca Plaza Principal Viacha
    $stmtPedido = $pdo->prepare("INSERT INTO pedidos 
        (id_cliente, id_taxi, origen_latitud, origen_longitud, destino_latitud, destino_longitud, tarifa, id_estado_pedido, prioridad, fecha_hora_solicitud)
        VALUES (?, NULL, ?, ?, ?, ?, ?, 1, 0, NOW())");

    // Coordenadas aproximadas Plaza Principal Viacha
    $plaza_lat = -17.257916;
    $plaza_long = -67.989661;

    // Origen y destino simulados
    $pedidos = [
        ['maria.lopez@viacha.local', $plaza_lat, $plaza_long, $plaza_lat + 0.002, $plaza_long + 0.005, 10.5],
        ['pedro.garcia@viacha.local', $plaza_lat + 0.001, $plaza_long + 0.001, $plaza_lat + 0.003, $plaza_long + 0.006, 12.0],
    ];

    $stmtClienteId = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");

    foreach ($pedidos as $pedido) {
        $stmtClienteId->execute([$pedido[0]]);
        $clienteId = $stmtClienteId->fetchColumn();
        $stmtPedido->execute([$clienteId, $pedido[1], $pedido[2], $pedido[3], $pedido[4], $pedido[5]]);
    }

    echo "Pedidos creados\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
