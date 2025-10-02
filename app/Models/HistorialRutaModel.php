<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class HistorialRutaModel extends Model
{
    public function obtenerPorPedido($id_pedido)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM historial_rutas WHERE id_pedido = :id_pedido');
        $stmt->execute(['id_pedido' => $id_pedido]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarRuta($data)
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO historial_rutas (id_pedido, detalles_ruta, evaluacion_cliente, evaluacion_conductor) 
            VALUES (:id_pedido, :detalles_ruta, :evaluacion_cliente, :evaluacion_conductor)
            ON DUPLICATE KEY UPDATE
            detalles_ruta = VALUES(detalles_ruta),
            evaluacion_cliente = VALUES(evaluacion_cliente),
            evaluacion_conductor = VALUES(evaluacion_conductor),
            updated_at = CURRENT_TIMESTAMP
        ');
        return $stmt->execute([
            'id_pedido' => $data['id_pedido'],
            'detalles_ruta' => json_encode($data['detalles_ruta']),
            'evaluacion_cliente' => $data['evaluacion_cliente'] ?? null,
            'evaluacion_conductor' => $data['evaluacion_conductor'] ?? null,
        ]);
    }
}
