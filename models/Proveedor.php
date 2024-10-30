<?php

namespace models;

@session_start();
require_once __DIR__ . '/../config/db.php';

class Proveedor
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
    
    // Obtener todos los proveedores
    public function obtenerTodos(){
        $sql = "SELECT * FROM proveedores WHERE activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $proveedores = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $proveedores;
    }

    // Obtener un proveedor por ID
    public function obtenerUno($id){
        $sql = "SELECT * FROM proveedores WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Crear un nuevo proveedor
    public function agregarProveedor($nombre){
        // Validaciones de entrada
        if (empty($nombre)) {
            return false; // No se permite la inserción
        }        
        $sql = "INSERT INTO proveedores (nombre) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre]);
    }

    // Editar un proveedor
    public function editarProveedor($id, $nombre){
        if (empty($nombre)) {
            return false; // No se permite la inserción
        }             
        $sql = "UPDATE proveedores SET nombre = ? WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre, $id]);
        return $stmt->rowCount() > 0;
    }

    // Eliminación lógica de un proveedor (marcar como inactivo)
    public function eliminarProveedor($id){
        $sql = "UPDATE proveedores SET activo = 0 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
