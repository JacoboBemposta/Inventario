<?php
namespace models;

use Exception;

@session_start();
require_once __DIR__ . '/../config/db.php'; 


class EntradaBienes {
    private $db;

    public function __construct() {
        $this->db = \DB::connect();
    }

    // Obtener todas las entradas de bienes
    public function obtenerTodas() {
        $sql = "SELECT eb.*, p.nombre AS proveedor_nombre FROM entradas_bienes eb 
                LEFT JOIN proveedores p ON eb.proveedor_id = p.id 
                WHERE eb.activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Obtener una entrada de bienes por su ID
    public function obtenerUno($id) {
        $sql = "SELECT * FROM entradas_bienes WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Crear una nueva entrada de bienes
    public function agregarEntrada($descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable) {
        $sql = "INSERT INTO entradas_bienes (descripcion, numero_factura, proveedor_id, fecha_compra, fecha_inicio_amortizacion, porcentaje_amortizacion, precio, cuenta_contable) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable]);
    }

    // Editar una entrada de bienes
    public function editarEntrada($id, $descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable) {
        $sql = "UPDATE entradas_bienes 
                SET descripcion = ?, numero_factura = ?, proveedor_id = ?, fecha_compra = ?, fecha_inicio_amortizacion = ?, porcentaje_amortizacion = ?, precio = ?, cuenta_contable = ?
                WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable, $id]);
    }

    // Eliminación lógica de una entrada de bienes y sus bienes asociados (marcar como inactivo)
    public function eliminarEntrada($id){
        try{
                        // Inicia la transacción
                        $this->db->beginTransaction();
    
                        // Primera consulta
                        $sql = "UPDATE entradas_bienes SET activo = 0 WHERE id = ?";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([$id]);
                
                        // Segunda consulta
                        $sql = "UPDATE bienes SET activo = 0 WHERE entrada_bien_id = ?";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([$id]);
                
                        // Si todo va bien, confirma la transacción
                        return $this->db->commit();

        }catch(Exception $e){
                $_SESSION["error"]=$e->getMessage();
                $this->db->rollBack();
                return false;
            
        }
            
    }
        // function file_force_contents($dir, $contents){
        //     $parts = explode('/', $dir);
        //     $file = array_pop($parts);
        //     $dir = '';
        //     foreach($parts as $part)
        //         if(!is_dir($dir .= "/$part")) mkdir($dir);
        //     file_put_contents("$dir/$file", $contents);
        // }
    
}
?>