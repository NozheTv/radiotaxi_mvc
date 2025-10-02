<?php
// app/Controllers/HistorialController.php
class HistorialController {
    private $historialModel;
    private $baseUrl = 'http://localhost/radiotaxi_mvc/public/';

    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/HistorialRuta.php';
        $this->historialModel = new HistorialRuta($pdo);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . $this->baseUrl . '?controller=Admin&action=login');
            exit();
        }
        $historiales = $this->historialModel->obtenerTodosHistoriales();
        require __DIR__ . '/../Views/admin/historial.php';
    }

    public function ver($id_pedido) {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . $this->baseUrl . '?controller=Admin&action=login');
            exit();
        }
        $historial = $this->historialModel->obtenerHistorialPorPedido($id_pedido);
        require __DIR__ . '/../Views/admin/ver_historial.php';
    }
}
