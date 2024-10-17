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
        $sql = "SELECT * FROM bienes WHERE entrada_bien_id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$entrada_bien_id]); 
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

    // Obtener un bien específico
    public function obtenerBien($id){
        $sql = "SELECT * FROM bienes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]); 
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
    //busqueda de bienes con filtro
    public function buscarfiltro($centro, $departamento, $tipo_bien, $estado, $fecha_inicio, $fecha_fin, $descripcion, $cuenta) {

   
        $query = "SELECT 
            b.*,
            eb.cuenta_contable as cuenta_contable
            FROM bienes AS b 
            JOIN entradas_bienes AS eb ON b.entrada_bien_id = eb.id
            WHERE 1=1"; // Se inicia con 1=1 para añadir condiciones dinámicas
    
        $params = [];
        
        // Añadir los filtros solo si no son NULL
        if ($centro!=="") {
            $query .= " AND b.centro = ?";
            $params[] = $centro;
        }
        if ($departamento!==""){
            $query .= " AND b.departamento = ?";
            $params[] = $departamento;
        }
        if ($tipo_bien!==""){
            $query .= " AND b.tipo_bien = ?";
            $params[] = $tipo_bien;
        }
        if ($estado!=="") {
            $query .= " AND b.estado = ?";
            $params[] = $estado;
        }
        if ($fecha_inicio!=="" && $fecha_fin!=="") {
            $query .= " AND b.fecha_alta BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
        }
        if ($descripcion !== "") {
            // Usar LIKE en lugar de = para búsquedas parciales
            $query .= " AND b.descripcion LIKE ?";
            $params[] = "%" . $descripcion . "%";  // Agregar los comodines % para búsquedas parciales
        }
        if ($cuenta!=="") {
            $query .= " AND eb.cuenta_contable = ?";
            $params[] = $cuenta;
        }
        
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        // Depuración: Mostrar el resultado de la ejecución
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // O el método adecuado para obtener los resultados
    }
    
    // Obtener todos los bienes
    public function obtenerBienes() {
        $sql = "SELECT * FROM bienes WHERE activo = 1";
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
        $stmt->execute([$motivo,$id]);
        }


    public function actualizaEstado($nuevoEstado,$bienId){

        $sql = "UPDATE bienes SET estado = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $resultado=$stmt->execute([$nuevoEstado,$bienId]);

        if ($resultado) {
            // Asegúrate de que no haya salida antes de esta línea
            echo json_encode(['success' => true]);
        } else {
            error_log("Error en la consulta SQL: " . $this->db->error);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la base de datos']);
            }  
        }        
    }
?>
