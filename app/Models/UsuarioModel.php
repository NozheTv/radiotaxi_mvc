<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class UsuarioModel extends Model
{
    public function obtenerPorCorreo($correo)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE correo = :correo LIMIT 1');
        $stmt->execute(['correo' => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verificarLogin($correo, $password)
    {
        $usuario = $this->obtenerPorCorreo($correo);
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    public function obtenerPorId($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearUsuario($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nombre_completo, correo, password, telefono, direccion, rol, estado, plataforma_acceso, licencia) VALUES (:nombre_completo, :correo, :password, :telefono, :direccion, :rol, :estado, :plataforma_acceso, :licencia)');
        $stmt->execute([
            'nombre_completo' => $data['nombre_completo'],
            'correo' => $data['correo'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'telefono' => $data['telefono'] ?? null,
            'direccion' => $data['direccion'] ?? null,
            'rol' => $data['rol'],
            'estado' => $data['estado'] ?? 'activo',
            'plataforma_acceso' => $data['plataforma_acceso'],
            'licencia' => $data['licencia'] ?? null,
        ]);
        return $this->pdo->lastInsertId();
    }
   
    public function obtenerHistorialDeViajesCliente($id_cliente)
    {
        $stmt = $this->pdo->prepare('
            SELECT p.*, e.descripcion as estado_pedido 
            FROM pedidos p 
            JOIN estados_pedido e ON p.id_estado_pedido = e.id 
            WHERE p.id_cliente = :id_cliente 
            ORDER BY p.fecha_hora_solicitud DESC
        ');
        $stmt->execute(['id_cliente' => $id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
