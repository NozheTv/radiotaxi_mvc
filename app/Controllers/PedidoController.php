<?php
namespace App\Controllers;

use App\Core\Controller;

class PedidoController extends Controller
{
    private $pedidoModel;
    private $taxiModel;

    public function __construct()
    {
        $this->pedidoModel = $this->model('PedidoModel');
        $this->taxiModel = $this->model('TaxiModel');
    }

    public function solicitarTaxi()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validaciones básicas
            if (!isset($data['id_cliente'], $data['origen_latitud'], $data['origen_longitud'], $data['destino_latitud'], $data['destino_longitud'], $data['tarifa'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos para solicitud']);
                return;
            }

            // Obtener taxi disponible
            $taxi = $this->taxiModel->obtenerTaxiDisponible();

            if (!$taxi) {
                http_response_code(404);
                echo json_encode(['success' => false, 'mensaje' => 'No hay taxis disponibles']);
                return;
            }

            $pedidoData = [
                'id_cliente' => $data['id_cliente'],
                'id_taxi' => $taxi['id'],
                'origen_latitud' => $data['origen_latitud'],
                'origen_longitud' => $data['origen_longitud'],
                'destino_latitud' => $data['destino_latitud'],
                'destino_longitud' => $data['destino_longitud'],
                'tarifa' => $data['tarifa'],
                'id_estado_pedido' => 1, // Suponiendo 1 es 'Solicitado'
                'prioridad' => $data['prioridad'] ?? false,
                'fecha_hora_solicitud' => date('Y-m-d H:i:s'),
            ];

            $exito = $this->pedidoModel->crearPedido($pedidoData);

            if ($exito) {
                // Actualizar estado de taxi como no disponible (ejemplo id_estado_taxi 2 = ocupado)
                $this->taxiModel->actualizarEstadoTaxi($taxi['id'], 2);
                echo json_encode(['success' => true, 'mensaje' => 'Taxi asignado y pedido creado', 'pedido' => $pedidoData]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'mensaje' => 'Error al crear el pedido']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
        }
    }
}
