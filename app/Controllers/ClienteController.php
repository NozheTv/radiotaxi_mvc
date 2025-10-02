<?php
// app/Controllers/ClienteController.php
class ClienteController {
    private $usuarioModel;
    private $pedidoModel;
    private $baseUrl = 'http://localhost/radiotaxi_mvc/public/';

    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/Usuario.php';
        require_once __DIR__ . '/../Models/Pedido.php';

        $this->usuarioModel = new Usuario($pdo);
        $this->pedidoModel = new Pedido($pdo);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->usuarioModel->loginCliente($correo, $password);

            if ($user) {
                session_regenerate_id(true);
                $_SESSION['cliente_id'] = $user['id'];
                $_SESSION['cliente_nombre'] = $user['nombre_completo'];
                header('Location: ' . $this->baseUrl . '?controller=Cliente&action=dashboard');
                exit();
            } else {
                $error = "Correo o contraseña incorrectos.";
                require __DIR__ . '/../Views/cliente/login.php';
            }
        } else {
            require __DIR__ . '/../Views/cliente/login.php';
        }
    }

    public function dashboard() {
        if (!isset($_SESSION['cliente_id'])) {
            header('Location: ' . $this->baseUrl . '?controller=Cliente&action=login');
            exit();
        }
        require __DIR__ . '/../Views/cliente/dashboard.php'; 
    }

    public function solicitarTaxi() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_cliente' => $_SESSION['cliente_id'],
                'origen_latitud' => $_POST['origen_latitud'],
                'origen_longitud' => $_POST['origen_longitud'],
                'destino_latitud' => $_POST['destino_latitud'],
                'destino_longitud' => $_POST['destino_longitud'],
                'tarifa' => $_POST['tarifa'],
                'prioridad' => $_POST['prioridad'] ?? 0
            ];
            $id_pedido = $this->pedidoModel->crearPedido($data);
            // Lógica para asignar taxi activo disponible podría ir aquí (no implementada aún)
            header('Location: ' . $this->baseUrl . '?controller=Cliente&action=historialViajes');
            exit();
        } else {
            require __DIR__ . '/../Views/cliente/solicitar_taxi.php';
        }
    }

    public function historialViajes() {
        if (!isset($_SESSION['cliente_id'])) {
            header('Location: ' . $this->baseUrl . '?controller=Cliente&action=login');
            exit();
        }
        $viajes = $this->pedidoModel->obtenerPedidosPorCliente($_SESSION['cliente_id']);
        require __DIR__ . '/../Views/cliente/historial_viajes.php';
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . $this->baseUrl . '?controller=Cliente&action=login');
        exit();
    }
}
