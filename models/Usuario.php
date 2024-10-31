<?php

namespace models;

@session_start();
require_once __DIR__ . '/../config/db.php';

class Usuario
{

    private $db;

    public function __construct()
    {
        $this->db = \DB::connect();
    }
    public function getDbConnection()
    {
        return $this->db;
    }

    // Obtener todos los usuarios
    public function obtenerTodos(){

        $sql = "SELECT * FROM usuarios WHERE activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $usuarios;
    }

    // Obtener un usuario por su id
    public function obtenerUno($id){

        $sql = "SELECT * FROM usuarios WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $usuario;
    }

    // Añadir un usuario
    public function agregarUsuario($nombre, $usuario, $contrasena, $tipo_usuario,$email){
        // Validaciones de entrada
        if (empty($nombre) || empty($usuario) || empty($contrasena) || empty($tipo_usuario)|| empty($email)) {
            return false; // No se permite la inserción
        }        

        $sql = "INSERT INTO usuarios (nombre, usuario, contrasena, tipo_usuario, email) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt->execute([$nombre, $usuario, $hashed_password, $tipo_usuario,$email]);
    }

    // Editar un usuario
    public function editarUsuario($id, $nombre, $contrasena, $tipo_usuario,$emial){
        // Verifica si el usuario existe
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $usuarioExistente = $stmt->fetch(\PDO::FETCH_ASSOC);
    
        if (!$usuarioExistente) {
            return false; // El usuario no existe
        }        
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
    public function eliminarUsuario($id){
        $sql = "UPDATE usuarios SET activo = 0 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    // Obtener un usuario por el nombre de usuario
    public function obtenerUSuario($usuario){
        $sql = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function guardarTokenRecuperacion($email, $token, $expiracion){
        $sql = "INSERT INTO tokens (email, token, expiracion) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([$email, $token, $expiracion]);   
           
    }

    public function compruebatoken($mail,$token){
        $sql = "SELECT * FROM tokens WHERE email = ? AND token = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$mail,$token]);
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $stmt->rowCount() > 0;
    }

    public function comprobarmail($mail){
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$mail]);
        $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $stmt->rowCount() > 0;
    }

    public function editarpass($pass,$mail){
        $sql = "UPDATE usuarios SET contrasena = ? where email = ?";
        $stmt = $this->db->prepare($sql);
        $hashed_password = password_hash($pass, PASSWORD_BCRYPT);
        $stmt->execute([$hashed_password,$mail]);
        $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $stmt->rowCount() > 0;
    }
}
