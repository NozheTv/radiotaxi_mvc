<?php
// app/Models/Taxi.php
class Taxi {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTaxis() {
        $sql = "SELECT t.id, t.placa, t.modelo, t.estado, u.nombre_completo AS conductor FROM taxis t LEFT JOIN usuarios u ON t.id_conductor = u.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function crearTaxi($data) {
        $sql = "INSERT INTO taxis (placa, modelo, estado, id_conductor) VALUES (:placa, :modelo, :estado, :id_conductor)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'placa' => $data['placa'],
            'modelo' => $data['modelo'],
            'estado' => $data['estado'] ?? 'disponible',
            'id_conductor' => $data['id_conductor'] ?? null
        ]);
    }

    public function actualizarEstadoTaxi($id, $estado) {
        $sql = "UPDATE taxis SET estado = :estado WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['estado' => $estado, 'id' => $id]);
    }
    
    public function eliminarTaxi($id) {
        $sql = "DELETE FROM taxis WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
