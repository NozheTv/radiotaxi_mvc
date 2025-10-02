<?php
// app/Controllers/PedidosController.php
class PedidosController {
    private $pedidoModel;
    private $baseUrl = 'http://localhost/radiotaxi_mvc/public/';

    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/Pedido.php';
        $this->pedidoModel = new Pedido($pdo);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . $this->baseUrl . '?controller=Admin&action=login');
            exit();
        }
        $pedidos = $this->pedidoModel->obtenerTodosPedidos();
        require __DIR__ . '/../Views/admin/pedidos.php';
    }

    public function asignarTaxi() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pedido = $_POST['id_pedido'];
            $id_taxi = $_POST['id_taxi'];
            $this->pedidoModel->asignarTaxi($id_pedido, $id_taxi);
            header('Location: ' . $this->baseUrl . '?controller=Pedidos&action=index');
            exit();
        }
    }

    public function actualizarEstado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pedido = $_POST['id_pedido'];
            $estado = $_POST['estado'];
            $this->pedidoModel->actualizarEstado($id_pedido, $estado);
            header('Location: ' . $this->baseUrl . '?controller=Pedidos&action=index');
            exit();
        }
    }
}
