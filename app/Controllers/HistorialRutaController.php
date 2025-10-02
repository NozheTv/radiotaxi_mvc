<?php
namespace App\Controllers;

use App\Core\Controller;

class HistorialRutaController extends Controller
{
    private $historialModel;

    public function __construct()
    {
        $this->historialModel = $this->model('HistorialRutaModel');
    }

    public function mostrarHistorial($id_pedido)
    {
        $historial = $this->historialModel->obtenerPorPedido($id_pedido);
        if ($historial) {
            echo json_encode(['success' => true, 'historial' => $historial]);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'No hay historial para ese pedido']);
        }
    }

    public function registrarRuta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['id_pedido'], $data['detalles_ruta'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
                return;
            }

            $exito = $this->historialModel->registrarRuta($data);
            if ($exito) {
                echo json_encode(['success' => true, 'mensaje' => 'Ruta registrada o actualizada']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'mensaje' => 'Error en el registro']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
        }
    }
}
