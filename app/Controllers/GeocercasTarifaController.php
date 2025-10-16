<?php
class GeocercasTarifaController {
    private $model;
    private $baseUrl = 'http://localhost/radiotaxi_mvc/public/';

    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/GeocercaTarifa.php';
        $this->model = new GeocercaTarifa($pdo);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . $this->baseUrl . '?controller=Admin&action=login');
            exit();
        }

        $geocercas = $this->model->obtenerTodas();
        require __DIR__ . '/../Views/admin/geocercas_tarifa.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $tarifa = $_POST['tarifa'];
            $poligono = $_POST['poligono_geojson'];
            $this->model->crear($nombre, $poligono, $tarifa);
            header('Location: ' . $this->baseUrl . '?controller=GeocercasTarifa&action=index');
            exit();
        }
        require __DIR__ . '/../Views/admin/crear_geocerca_tarifa.php';
    }

    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->model->eliminar($_GET['id']);
        }
        header('Location: ' . $this->baseUrl . '?controller=GeocercasTarifa&action=index');
    }
}
