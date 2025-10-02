<?php
// app/Models/Pedido.php
class Pedido {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function crearPedido($data) {
        $sql = "INSERT INTO pedidos
            (id_cliente, id_taxi, origen_latitud, origen_longitud, destino_latitud, destino_longitud, tarifa, id_estado_pedido, prioridad, fecha_hora_solicitud)
            VALUES
            (:id_cliente, NULL, :origen_latitud, :origen_longitud, :destino_latitud, :destino_longitud, :tarifa, 1, :prioridad, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_cliente' => $data['id_cliente'],
            'origen_latitud' => $data['origen_latitud'],
            'origen_longitud' => $data['origen_longitud'],
            'destino_latitud' => $data['destino_latitud'],
            'destino_longitud' => $data['destino_longitud'],
            'tarifa' => $data['tarifa'],
            'prioridad' => $data['prioridad'] ?? 0,
        ]);
        return $this->pdo->lastInsertId();
    }

    public function asignarTaxi($id_pedido, $id_taxi) {
        $sql = "UPDATE pedidos SET id_taxi = :id_taxi, id_estado_pedido = 2 WHERE id = :id_pedido";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id_taxi' => $id_taxi, 'id_pedido' => $id_pedido]);
    }

    public function obtenerPedidosPorCliente($id_cliente) {
        $sql = "SELECT * FROM pedidos WHERE id_cliente = :id_cliente ORDER BY fecha_hora_solicitud DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_cliente' => $id_cliente]);
        return $stmt->fetchAll();
    }
}
