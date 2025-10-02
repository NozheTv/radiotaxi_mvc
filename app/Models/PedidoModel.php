<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class PedidoModel extends Model
{
    public function crearPedido($data)
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO pedidos
            (id_cliente, id_taxi, origen_latitud, origen_longitud, destino_latitud, destino_longitud, tarifa, id_estado_pedido, prioridad, fecha_hora_solicitud)
            VALUES
            (:id_cliente, :id_taxi, :origen_latitud, :origen_longitud, :destino_latitud, :destino_longitud, :tarifa, :id_estado_pedido, :prioridad, :fecha_hora_solicitud)
        ');
        return $stmt->execute([
            'id_cliente' => $data['id_cliente'],
            'id_taxi' => $data['id_taxi'],
            'origen_latitud' => $data['origen_latitud'],
            'origen_longitud' => $data['origen_longitud'],
            'destino_latitud' => $data['destino_latitud'],
            'destino_longitud' => $data['destino_longitud'],
            'tarifa' => $data['tarifa'],
            'id_estado_pedido' => $data['id_estado_pedido'],
            'prioridad' => $data['prioridad'] ?? false,
            'fecha_hora_solicitud' => $data['fecha_hora_solicitud'],
        ]);
    }

    public function obtenerPedidosPorCliente($id_cliente)
    {
        $sql = 'SELECT * FROM pedidos WHERE id_cliente = :id_cliente ORDER BY fecha_hora_solicitud DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_cliente' => $id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstadoPedido($id_pedido, $id_estado)
    {
        $stmt = $this->pdo->prepare('UPDATE pedidos SET id_estado_pedido = :id_estado WHERE id = :id_pedido');
        return $stmt->execute(['id_estado' => $id_estado, 'id_pedido' => $id_pedido]);
    }

    public function obtenerPedidoPorId($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pedidos WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
