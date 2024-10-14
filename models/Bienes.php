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
        $query = "SELECT 
        b.*,
        eb.descripcion as nombre,
        eb.id as entradaID,
        eb.cuenta_contable as cuenta_contable, 
        eb.fecha_inicio_amortizacion as fecha_inicio_amortizacion,
        eb.porcentaje_amortizacion as porcentaje_amortizacion,
        eb.numero_factura as numero_factura,
        eb.fecha_compra as fecha_compra,
        p.id as proveedorID
        FROM bienes AS b 
        JOIN entradas_bienes AS eb ON b.entrada_bien_id = eb.id
        JOIN proveedores AS p ON eb.proveedor_id = p.id
        WHERE b.id = ?";
        $stmt = $this->db->prepare($query);
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
    public function eliminarBien($motivo,$id) {

        $sql = "UPDATE bienes SET activo = 0, causa_baja = ? , fecha_baja = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id,$motivo]);
    }
}
?>
