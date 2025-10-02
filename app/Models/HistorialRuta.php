<?php
// app/Models/HistorialRuta.php
class HistorialRuta {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function guardarHistorial($id_pedido, $detalles_ruta, $evaluacion_cliente = null, $evaluacion_conductor = null) {
        $sql = "INSERT INTO historial_rutas (id_pedido, detalles_ruta, evaluacion_cliente, evaluacion_conductor, created_at)
                VALUES (:id_pedido, :detalles_ruta, :evaluacion_cliente, :evaluacion_conductor, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_pedido' => $id_pedido,
            'detalles_ruta' => json_encode($detalles_ruta),
            'evaluacion_cliente' => $evaluacion_cliente,
            'evaluacion_conductor' => $evaluacion_conductor
        ]);
    }

    public function obtenerHistorialPorPedido($id_pedido) {
        $sql = "SELECT * FROM historial_rutas WHERE id_pedido = :id_pedido";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_pedido' => $id_pedido]);
        return $stmt->fetch();
    }

    public function obtenerTodosHistoriales() {
        $sql = "SELECT h.*, p.origen_latitud, p.origen_longitud, p.destino_latitud, p.destino_longitud
                FROM historial_rutas h
                JOIN pedidos p ON h.id_pedido = p.id
                ORDER BY h.created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
