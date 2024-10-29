<?php

namespace models;

@session_start();
require_once __DIR__ . '/../config/db.php';

class Bienes
{
    protected $db;

    public function __construct()
    {
        $this->db = \DB::connect();
    }
    public function getDbConnection()
    {
        return $this->db;
    }
    // Obtener todos los bienes de una entrada específica
    public function obtenerPorEntradaId($entrada_bien_id)
    {
        $sql = "SELECT * FROM bienes WHERE entrada_bien_id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$entrada_bien_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Obtener un bien específico
    public function obtenerBien($id)
    {
        $sql = "SELECT * FROM bienes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    // Obtener un bien por su ID
    public function obtenerPorId($id)
    {
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
    //busqueda de bienes con filtro
public function buscarfiltro($centro, $departamento, $tipo_bien, $estado, $fecha_inicio, $fecha_fin, $descripcion, $cuenta)
{
    $query = "SELECT b.*, eb.cuenta_contable 
              FROM bienes AS b 
              JOIN entradas_bienes AS eb ON b.entrada_bien_id = eb.id 
              WHERE 1=1";

    $params = [];

    if ($centro !== null) {
        $query .= " AND b.centro = ?";
        $params[] = $centro;
    }

    if ($departamento !== null) {
        $query .= " AND b.departamento = ?";
        $params[] = $departamento;
    }

    if ($tipo_bien !== null) {
        $query .= " AND b.tipo_bien = ?";
        $params[] = $tipo_bien;
    }

    if ($estado !== null) {
        $query .= " AND b.estado = ?";
        $params[] = $estado;
    }

    if ($fecha_inicio !== null && $fecha_fin !== null) {
        $query .= " AND b.fecha_alta BETWEEN ? AND ?";
        $params[] = $fecha_inicio;
        $params[] = $fecha_fin;
    }

    if ($descripcion !== null) {
        $query .= " AND b.descripcion LIKE ?";
        $params[] = '%' . $descripcion . '%';
    }

    if ($cuenta !== null) {
        $query .= " AND eb.cuenta_contable = ?";
        $params[] = $cuenta;
    }

    $stmt = $this->db->prepare($query);
    $stmt->execute($params);
    
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

    // Obtener todos los bienes
    public function obtenerBienes()
    {
        $sql = "SELECT * FROM bienes WHERE activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    // Agregar un nuevo bien a una entrada
    public function agregarBien($descripcion, $precio, $centro, $departamento, $tipo_bien, $entrada_bien_id)
    {
        if ($entrada_bien_id === null) {
            throw new \InvalidArgumentException("EL bien debe estar asociado a un ID de entrada correcto");
        }
        $sql = "INSERT INTO bienes (descripcion, precio, centro, departamento, tipo_bien, fecha_alta, entrada_bien_id) 
                VALUES (?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$descripcion, $precio, $centro, $departamento, $tipo_bien, $entrada_bien_id]);
    }



    // Editar un bien
    public function editarBien($id, $descripcion, $precio, $centro, $departamento, $tipo_bien, $causa_baja)
    {

        $sql = "UPDATE bienes 
                SET descripcion = ?, precio = ?, centro = ?, departamento = ?, tipo_bien = ?, causa_baja = ?
                WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([$descripcion, $precio, $centro, $departamento, $tipo_bien, $causa_baja, $id]);
        return $stmt->rowCount() > 0;
    }



    // Eliminación lógica de un bien (marcar como inactivo)
    public function eliminarBien($id)
    {

        $sql = "UPDATE bienes SET activo = 0, fecha_baja = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }


    public function actualizaEstado($motivo, $nuevoEstado,$bienId)
    {
        if ($nuevoEstado === null || !in_array($nuevoEstado, [0, 1])) {
            return false; // No se permite cambiar a null o a un estado no permitido
        }  
        $sql = "UPDATE bienes SET causa_baja = ? , estado = ?  WHERE id = ?";
        $stmt = $this->db->prepare($sql);   
        $stmt->execute([$motivo, $nuevoEstado, $bienId]);
        return $stmt->rowCount() > 0;
 
    }
}
