<?php
// app/Controllers/UsuariosController.php
class UsuariosController {
    private $usuarioModel;
    private $baseUrl = 'http://localhost/radiotaxi_mvc/public/';

    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/Usuario.php';
        $this->usuarioModel = new Usuario($pdo);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . $this->baseUrl . '?controller=Admin&action=login');
            exit();
        }
        $usuarios = $this->usuarioModel->obtenerUsuarios();
        require __DIR__ . '/../Views/admin/usuarios.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre_completo' => $_POST['nombre_completo'],
                'correo' => $_POST['correo'],
                'password' => $_POST['password'],
                'rol' => $_POST['rol'],
                'estado' => $_POST['estado'] ?? 'activo'
            ];
            $this->usuarioModel->crearUsuario($data);
            header('Location: ' . $this->baseUrl . '?controller=Usuarios&action=index');
            exit();
        }
        require __DIR__ . '/../Views/admin/crear_usuario.php';
    }

    public function cambiarEstado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $estado = $_POST['estado'];
            $this->usuarioModel->actualizarEstado($id, $estado);
            header('Location: ' . $this->baseUrl . '?controller=Usuarios&action=index');
            exit();
        }
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->usuarioModel->eliminarUsuario($id);
            header('Location: ' . $this->baseUrl . '?controller=Usuarios&action=index');
            exit();
        }
    }
}
