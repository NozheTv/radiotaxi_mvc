<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class TaxiModel extends Model
{
    public function obtenerTaxiDisponible()
    {
        // Obtener taxi cuyo estado permita estar disponible y conductor activo asignado
        $sql = 'SELECT rt.* FROM radiotaxis rt
                JOIN estados_taxi et ON rt.id_estado_taxi = et.id
                JOIN usuarios u ON rt.id_conductor = u.id
                WHERE et.descripcion = "disponible"
                AND u.estado = "activo"
                LIMIT 1';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarEstadoTaxi($id_taxi, $id_estado)
    {
        $stmt = $this->pdo->prepare('UPDATE radiotaxis SET id_estado_taxi = :id_estado WHERE id = :id_taxi');
        return $stmt->execute(['id_estado' => $id_estado, 'id_taxi' => $id_taxi]);
    }

    public function asignarConductorTaxi($id_taxi, $id_conductor)
    {
        $stmt = $this->pdo->prepare('UPDATE radiotaxis SET id_conductor = :id_conductor WHERE id = :id_taxi');
        return $stmt->execute(['id_conductor' => $id_conductor, 'id_taxi' => $id_taxi]);
    }

    public function obtenerTaxiPorId($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM radiotaxis WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
