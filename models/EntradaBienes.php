<?php

namespace models;

use Exception;

@session_start();
require_once __DIR__ . '/../config/db.php';


class EntradaBienes
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
    
    // Obtener todas las entradas de bienes
    public function obtenerTodas(){
        $sql = "SELECT eb.*, p.nombre AS proveedor_nombre FROM entradas_bienes eb 
                LEFT JOIN proveedores p ON eb.proveedor_id = p.id 
                WHERE eb.activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Obtener una entrada de bienes por su ID
    public function obtenerUno($id){
        $sql = "SELECT * FROM entradas_bienes WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Crear una nueva entrada de bienes
    public function agregarEntrada($descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable){
        // Validaciones de entrada
        if (empty($descripcion) || empty($numero_factura) || empty($proveedor_id) || empty($fecha_compra) || empty($fecha_inicio_amortizacion) || 
            $porcentaje_amortizacion < 0 || $precio < 0 || empty($cuenta_contable)) {
            return false; // No se permite la inserción
        }
    
        $sql = "INSERT INTO entradas_bienes (descripcion, numero_factura, proveedor_id, fecha_compra, fecha_inicio_amortizacion, porcentaje_amortizacion, precio, cuenta_contable) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable]);
    }

    // Editar una entrada de bienes
    public function editarEntrada($id, $descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable){
        // Validaciones de entrada
        if (empty($descripcion) || empty($numero_factura) || empty($proveedor_id) || empty($fecha_compra) || 
            empty($fecha_inicio_amortizacion) || $porcentaje_amortizacion < 0 || $precio < 0 || empty($cuenta_contable)) {
            return false; // No se permite la inserción
        }        
        $sql = "UPDATE entradas_bienes 
                SET descripcion = ?, numero_factura = ?, proveedor_id = ?, fecha_compra = ?, fecha_inicio_amortizacion = ?, porcentaje_amortizacion = ?, precio = ?, cuenta_contable = ?
                WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable, $id]);
        return $stmt->rowCount() > 0;
    }

    // Eliminación lógica de una entrada de bienes y sus bienes asociados (marcar como inactivo)
    public function eliminarEntrada($id){
        try {
            // Inicia la transacción
            $this->db->beginTransaction();
    
            // Primera consulta
            $sql = "UPDATE entradas_bienes SET activo = 0 WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
    
            // Verifica si se actualizó alguna fila
            if ($stmt->rowCount() === 0) {
                // No se encontró la entrada, se cancela la transacción
                $this->db->rollBack();
                return false;
            }
    
            // Segunda consulta para actualizar bienes relacionados
            $sql = "UPDATE bienes SET activo = 0 WHERE entrada_bien_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
    
            // Si todo va bien, confirma la transacción
            return $this->db->commit();
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            $this->db->rollBack();
            return false;
        }
    }
    
}
