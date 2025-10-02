<?php
// app/Models/Usuario.php
class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($correo, $password) {
        $sql = "SELECT * FROM usuarios WHERE correo = :correo AND rol = 'administrador' LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['correo' => $correo]);
        $user = $stmt->fetch();
        if ($user) {
            // Verificar contraseña con hash
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }
}
