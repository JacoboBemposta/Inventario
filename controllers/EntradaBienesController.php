<?php
namespace controllers;
@session_start();
use \models\EntradaBienes;
use \models\Bienes;
use \fpdf;

class EntradaBienesController {
    private $entrada;
    private $bienes;

    // Listar todas las entradas de bienes
    public function listarEntradas() {
        $entrada = new EntradaBienes();
        $entradas = $entrada->obtenerTodas();
        $_SESSION['entradas'] = $entradas; // Guarda los datos en la sesión
        
        $bienesPorEntrada = [];
        
        foreach ($entradas as $entrada ) {
            $bienes=new Bienes();
            $bienes = $bienes->obtenerPorEntradaId($entrada['id']);
            $bienesPorEntrada[$entrada['id']] = $bienes;
            
            $_SESSION['bienesPorEntrada'] = $bienesPorEntrada;
        }

        
    }
    public function editarEntrada($id) {
        $entrada = new EntradaBienes();
        $entradas = $entrada->obtenerUno($id);
        $_SESSION['entrada'] = $entradas;
        
    }
    public function actualizarentrada() {
        $id=$_GET['entrada'];
        $entrada = new EntradaBienes();
        $descripcion=htmlspecialchars($_POST['descripcion']);
        $numero_factura=htmlspecialchars($_POST['numero_factura']);
        $proveedor_id=htmlspecialchars($_POST['proveedor_id']);
        $fecha_compra=htmlspecialchars($_POST['fecha_compra']);
        $fecha_inicio_amortizacion=htmlspecialchars($_POST['fecha_inicio_amortizacion']);
        $porcentaje_amortizacion=htmlspecialchars($_POST['porcentaje_amortizacion']);
        $precio=htmlspecialchars($_POST['precio']);
        $cuenta_contable=htmlspecialchars($_POST['cuenta_contable']);
        $entrada->editarEntrada($id, $descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable) ;
    }

    //Crear una nueva entrada de bienes
    public function crearEntrada() {

            $entrada = new EntradaBienes();
            $descripcion = htmlspecialchars($_POST['descripcion']);
            $numero_factura = htmlspecialchars($_POST['numero_factura']);
            $proveedor_id = htmlspecialchars($_POST['proveedor_id']);
            $fecha_compra = htmlspecialchars($_POST['fecha_compra']);
            $fecha_inicio_amortizacion = htmlspecialchars($_POST['fecha_inicio_amortizacion']);
            $porcentaje_amortizacion = htmlspecialchars($_POST['porcentaje_amortizacion']);
            $precio = htmlspecialchars($_POST['precio']);
            $cuenta_contable = htmlspecialchars($_POST['cuenta_contable']);
            
            $entrada->agregarEntrada($descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable) ;

        }
    public function eliminarEntrada($id){
        $entrada = new EntradaBienes();
        return $entrada->eliminarEntrada($id);
    }

    // Controlador para importar CSV
    public function importarCSV($archivo){
        
        //Recorrer el fichero
        $lectura = fopen($archivo["tmp_name"], "r");
        while (($data = fgetcsv($lectura, 1000, ";"))) {
            //Buscar las lineas que empiezan con el campo Descripción
            $mystring = $data[0];
            $findme   = 'Descripc';
            $encontrado = strpos($mystring, $findme);
            if($encontrado !== false){
                // Dar formato a la fecha
                $date = $data[10];
                $date = str_replace('/', '-', $date);
                $fecha = date('Y-m-d', strtotime($date));
                //Elminiar el string "Descripción: " del primer elemento del array
                $descripcion = substr($data[0],16);
                $cuenta = $data[9];         
                $fecha_compra = $fecha;
                $fecha_inicio_amortizacion = $fecha;
                $precio = $data[12];
                $amortizacion = $data[13];
                $objeto = new EntradaBienes();
                $resultado=$objeto -> agregarEntrada($descripcion, $numero_factura=NULL, $proveedor_id=NULL, $fecha_compra, $fecha_inicio_amortizacion, $amortizacion, $precio, $cuenta) ;

            }


        }
    }

}
?>
