<?php
// app/Controllers/AdminController.php

class AdminController {
    private $usuarioModel;
    private $baseUrl = 'http://localhost/radiotaxi_mvc/public/';

    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/Usuario.php';
        $this->usuarioModel = new Usuario($pdo);
        // Iniciar sesión solo si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->usuarioModel->login($correo, $password);

            if ($user) {
                // Regenerar ID de sesión para seguridad
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_nombre'] = $user['nombre_completo'];
                header('Location: ' . $this->baseUrl . '?controller=Admin&action=dashboard');
                exit();
            } else {
                $error = "Correo o contraseña incorrectos.";
                require __DIR__ . '/../Views/admin/login.php';
            }
        } else {
            require __DIR__ . '/../Views/admin/login.php';
        }
    }

    public function dashboard() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . $this->baseUrl . '?controller=Admin&action=login');
            exit();
        }
        require __DIR__ . '/../Views/admin/dashboard.php'; 
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Limpiar variables y destruir sesión
        $_SESSION = [];
        session_destroy();
        header('Location: ' . $this->baseUrl . '?controller=Admin&action=login');
        exit();
    }
}
