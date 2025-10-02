<?php
// app/Controllers/ConductorController.php
class ConductorController {
    private $usuarioModel;
    private $taxiModel;
    private $baseUrl = 'http://localhost/radiotaxi_mvc/public/';

    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/Usuario.php';
        require_once __DIR__ . '/../Models/Taxi.php';

        $this->usuarioModel = new Usuario($pdo);
        $this->taxiModel = new Taxi($pdo);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->usuarioModel->loginConductor($correo, $password);

            if ($user) {
                session_regenerate_id(true);
                $_SESSION['conductor_id'] = $user['id'];
                $_SESSION['conductor_nombre'] = $user['nombre_completo'];
                header('Location: ' . $this->baseUrl . '?controller=Conductor&action=dashboard');
                exit();
            } else {
                $error = "Correo o contraseña incorrectos.";
                require __DIR__ . '/../Views/conductor/login.php';
            }
        } else {
            require __DIR__ . '/../Views/conductor/login.php';
        }
    }

    public function dashboard() {
        if (!isset($_SESSION['conductor_id'])) {
            header('Location: ' . $this->baseUrl . '?controller=Conductor&action=login');
            exit();
        }
        require __DIR__ . '/../Views/conductor/dashboard.php'; 
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . $this->baseUrl . '?controller=Conductor&action=login');
        exit();
    }
}
