<?php
class GeocercaTarifa {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodas() {
        $stmt = $this->pdo->query("SELECT * FROM geocercas_tarifa");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $poligono_geojson, $tarifa) {
        $sql = "INSERT INTO geocercas_tarifa (nombre_zona, poligono_geojson, tarifa_fija) 
                VALUES (:nombre, :poligono_geojson, :tarifa)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre' => $nombre,
            'poligono_geojson' => $poligono_geojson,
            'tarifa' => $tarifa
        ]);
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM geocercas_tarifa WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM geocercas_tarifa WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
