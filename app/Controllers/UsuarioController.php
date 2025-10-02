<?php
namespace App\Controllers;
use App\Core\Controller;

class UsuarioController extends Controller
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = $this->model('UsuarioModel');
    }

    public function login()
    {
        // Espera POST con correo y password
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            $usuario = $this->usuarioModel->verificarLogin($correo, $password);

            if ($usuario) {
                // Guardar sesión o token, por simplificar aquí guardamos sesión
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                echo json_encode(['success' => true, 'mensaje' => 'Login exitoso']);
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'mensaje' => 'Correo o contraseña incorrectos']);
            }
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
        }
    }

    public function cerrarSesion()
    {
        session_start();
        session_destroy();
        echo json_encode(['success' => true, 'mensaje' => 'Sesión cerrada']);
    }

    public function historialViajes($id_cliente)
    {
        session_start();
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_id'] != $id_cliente) {
            http_response_code(403);
            echo json_encode(['success' => false, 'mensaje' => 'No autorizado']);
            return;
        }
        $viajes = $this->usuarioModel->obtenerHistorialDeViajesCliente($id_cliente);
        echo json_encode(['success' => true, 'datos' => $viajes]);
    }
}
