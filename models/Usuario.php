<?php
namespace models;
@session_start();
require_once __DIR__ . '/../config/db.php'; 

class Usuario {
    
    private $db;

    public function __construct() {
        $this->db = \DB::connect();
    }

    // Obtener todos los usuarios
    public function obtenerTodos() {
        
        $sql = "SELECT * FROM usuarios WHERE activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $usuarios;
    }

    // Obtener un usuario por su id
    public function obtenerUno($id) {
        
        $sql = "SELECT * FROM usuarios WHERE id = $id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $usuario = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $usuario;
    }

    // Añadir un usuario
    public function agregarUsuario($nombre, $usuario, $contrasena, $tipo_usuario) {
        
        $sql = "INSERT INTO usuarios (nombre, usuario, contrasena, tipo_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt->execute([$nombre, $usuario, $hashed_password, $tipo_usuario]);
    }

    // Editar un usuario
    public function editarUsuario($id, $nombre, $contrasena, $tipo_usuario) {
        $sql = "UPDATE usuarios SET nombre = ?, tipo_usuario = ?";
        $params = [$nombre, $tipo_usuario];
        
        if (!empty($contrasena)) { // Solo actualiza la contraseña si se proporciona
            $sql .= ", contrasena = ?";
            $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);
            $params[] = $hashed_password;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
    
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    //Eliminación lógica un usuario por su id (marca como inactivo)
    public function eliminarUsuario($id) {
        $sql = "UPDATE usuarios SET activo = 0 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Obtener un usuario por el nombre de usuario
    public function obtenerUSuario($usuario){
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>
