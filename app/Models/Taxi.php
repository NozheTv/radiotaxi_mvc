<?php
// app/Models/Taxi.php
class Taxi {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Retornar conductores activos disponibles para asignar a pedido
    public function getConductoresActivos() {
        $sql = "SELECT u.* FROM usuarios u WHERE u.rol = 'conductor' AND u.estado = 'activo'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getTaxiByConductor($id_conductor) {
        $sql = "SELECT * FROM radiotaxis WHERE id_conductor = :id_conductor";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_conductor' => $id_conductor]);
        return $stmt->fetch();
    }
}
