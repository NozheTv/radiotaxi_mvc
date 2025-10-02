<?php
// app/Controllers/TaxisController.php
class TaxisController {
    private $taxiModel;
    private $baseUrl = 'http://localhost/radiotaxi_mvc/public/';

    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/Taxi.php';
        $this->taxiModel = new Taxi($pdo);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . $this->baseUrl . '?controller=Admin&action=login');
            exit();
        }
        $taxis = $this->taxiModel->obtenerTaxis();
        require __DIR__ . '/../Views/admin/taxis.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'placa' => $_POST['placa'],
                'modelo' => $_POST['modelo'],
                'estado' => $_POST['estado'] ?? 'disponible',
                'id_conductor' => $_POST['id_conductor'] ?? null
            ];
            $this->taxiModel->crearTaxi($data);
            header('Location: ' . $this->baseUrl . '?controller=Taxis&action=index');
            exit();
        }
        // Obtener conductores activos para seleccionar
        require_once __DIR__ . '/../Models/Usuario.php';
        $usuarioModel = new Usuario($this->taxiModel->pdo); // accediendo a pdo desde taxiModel
        $conductores = $usuarioModel->obtenerConductoresActivos();
        require __DIR__ . '/../Views/admin/crear_taxi.php';
    }

    public function cambiarEstado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $estado = $_POST['estado'];
            $this->taxiModel->actualizarEstadoTaxi($id, $estado);
            header('Location: ' . $this->baseUrl . '?controller=Taxis&action=index');
            exit();
        }
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->taxiModel->eliminarTaxi($id);
            header('Location: ' . $this->baseUrl . '?controller=Taxis&action=index');
            exit();
        }
    }
}
