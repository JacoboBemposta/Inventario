<?php
namespace models;
@session_start();
require_once __DIR__ . '/../config/db.php'; 

class Bienes {
    private $db;

    public function __construct() {
        $this->db = \DB::connect();
    }

    // Obtener todos los bienes de una entrada específica
    public function obtenerPorEntradaId($entrada_bien_id) {
        $sql = "SELECT * FROM bienes WHERE entrada_bien_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$entrada_bien_id]); 
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Obtener un bien por su ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM bienes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    // Obtener todos los bienes
    public function obtenerBienes() {
        $sql = "SELECT * FROM bienes";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    // Agregar un nuevo bien a una entrada
    public function agregarBien($descripcion, $precio, $centro, $departamento, $tipo_bien, $codigo, $entrada_bien_id) {
        $sql = "INSERT INTO bienes (descripcion, precio, centro, departamento, tipo_bien, codigo, fecha_alta, entrada_bien_id) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$descripcion, $precio, $centro, $departamento, $tipo_bien, $codigo, $entrada_bien_id]);
    }
    
    

    // Editar un bien
    public function editarBien($id, $descripcion, $precio, $centro, $departamento, $tipo_bien, $causa_baja) {
        $sql = "UPDATE bienes 
                SET descripcion = ?, precio = ?, centro = ?, departamento = ?, tipo_bien = ?, causa_baja = ?
                WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$descripcion, $precio, $centro, $departamento, $tipo_bien, $causa_baja, $id]);
    }

    // Eliminación lógica de un bien (marcar como inactivo)
    public function eliminarBien($id) {
        $sql = "UPDATE bienes SET activo = 0, fecha_baja = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
