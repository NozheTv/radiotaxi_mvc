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

    public function obtenerUsuarios() {
        $sql = "SELECT id, nombre_completo, correo, rol, estado FROM usuarios";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Crear nuevo usuario (cliente o conductor)
    public function crearUsuario($data) {
        $sql = "INSERT INTO usuarios (nombre_completo, correo, password, rol, estado) VALUES (:nombre, :correo, :password, :rol, :estado)";
        $stmt = $this->pdo->prepare($sql);
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        return $stmt->execute([
            'nombre' => $data['nombre_completo'],
            'correo' => $data['correo'],
            'password' => $hash,
            'rol' => $data['rol'],
            'estado' => $data['estado'] ?? 'activo'
        ]);
    }

    // Actualizar estado de usuario (activo/inactivo)
    public function actualizarEstado($id, $estado) {
        $sql = "UPDATE usuarios SET estado = :estado WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['estado' => $estado, 'id' => $id]);
    }

    // Eliminar usuario
    public function eliminarUsuario($id) {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    public function obtenerConductoresActivos() {
        $sql = "SELECT id, nombre_completo FROM usuarios WHERE rol = 'conductor' AND estado = 'activo'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

}
