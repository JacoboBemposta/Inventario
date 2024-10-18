<?php

namespace controllers;

@session_start();

use \models\EntradaBienes;
use \models\Bienes;
use \fpdf;
use models\Proveedor;

class EntradaBienesController
{
    private $entrada;
    private $bienes;

    // Listar todas las entradas de bienes
    public function listarEntradas()
    {
        $entrada = new EntradaBienes();
        $entradas = $entrada->obtenerTodas();
        $_SESSION['entradas'] = $entradas;
        $bienesPorEntrada = [];
        foreach ($entradas as $entrada) {
            $bienes = new Bienes();
            $bienes = $bienes->obtenerPorEntradaId($entrada['id']);
            $bienesPorEntrada[$entrada['id']] = $bienes;
            $_SESSION['bienesPorEntrada'] = $bienesPorEntrada;
        }
    }
    // Muestra el formulario de edición de una entrada existente
    public function editarEntrada($id)
    {
        $entrada = new EntradaBienes();
        $entradas = $entrada->obtenerUno($id);
        $_SESSION['entrada'] = $entradas;
    }
    // Maneja la actualización de una entrada existente
    // Si contiene etiquetas HTML, lanzar un error    
    public function actualizarentrada()
    {
        $id = $_GET['entrada'];
        $entrada = new EntradaBienes();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                $_SESSION["error"] = "Error en el envio del formulario";
                // $enlace_actual = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                // $_SESSION["redirect"]=$enlace_actual;
                header("Location: " . ROOT_PATH . "error.php");
                die;
            }
            destruirTokenCSRF();
            $descripcion = $_POST["descripcion"];
            if ($descripcion != strip_tags($descripcion)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "editar.php");
                die;
            }
            $numero_factura = $_POST["numero_factura"];
            if ($numero_factura != strip_tags($numero_factura)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "editar.php");
                die;
            }
            $proveedor_id = $_POST["proveedor_id"];
            if ($proveedor_id != strip_tags($proveedor_id)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "editar.php");
                die;
            }
            $fecha_compra = $_POST["fecha_compra"];
            if ($fecha_compra != strip_tags($fecha_compra)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "editar.php");
                die;
            }
            $fecha_inicio_amortizacion = $_POST["fecha_inicio_amortizacion"];
            if ($fecha_inicio_amortizacion != strip_tags($fecha_inicio_amortizacion)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "editar.php");
                die;
            }
            $porcentaje_amortizacion = $_POST["porcentaje_amortizacion"];
            if ($porcentaje_amortizacion != strip_tags($porcentaje_amortizacion)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "editar.php");
                die;
            }
            $precio = $_POST["precio"];
            if ($precio != strip_tags($precio)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "editar.php");
                die;
            }
            $cuenta_contable = $_POST["cuenta_contable"];
            if ($cuenta_contable != strip_tags($cuenta_contable)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "editar.php");
                die;
            }

            $entrada->editarEntrada($id, $descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable);
        }
    }
    //Maneja la creacion de una nueva entrada de bienes
    public function crearEntrada()
    {
        $entrada = new EntradaBienes();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                $_SESSION["error"] = "Error en el envio del formulario";
                header("Location: " . ROOT_PATH . "error.php");
                die;
            }
            destruirTokenCSRF();
            $descripcion = $_POST["descripcion"];
            if ($descripcion != strip_tags($descripcion)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "crear.php");
                die;
            }
            $numero_factura = $_POST["numero_factura"];
            if ($numero_factura != strip_tags($numero_factura)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "crear.php");
                die;
            }
            $proveedor_id = $_POST["proveedor_id"];
            if ($proveedor_id != strip_tags($proveedor_id)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "crear.php");
                die;
            }
            $fecha_compra = $_POST["fecha_compra"];
            if ($fecha_compra != strip_tags($fecha_compra)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "crear.php");
                die;
            }
            $fecha_inicio_amortizacion = $_POST["fecha_inicio_amortizacion"];
            if ($fecha_inicio_amortizacion != strip_tags($fecha_inicio_amortizacion)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "crear.php");
                die;
            }
            $porcentaje_amortizacion = $_POST["porcentaje_amortizacion"];
            if ($porcentaje_amortizacion != strip_tags($porcentaje_amortizacion)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "crear.php");
                die;
            }
            $precio = $_POST["precio"];
            if ($precio != strip_tags($precio)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "crear.php");
                die;
            }
            $cuenta_contable = $_POST["cuenta_contable"];
            if ($cuenta_contable != strip_tags($cuenta_contable)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . ENT_PATH . "crear.php");
                die;
            }

            $entrada->agregarEntrada($descripcion, $numero_factura, $proveedor_id, $fecha_compra, $fecha_inicio_amortizacion, $porcentaje_amortizacion, $precio, $cuenta_contable);
        }
    }
    //Elimina lógicamente una entrada de la base de datos
    public function eliminarEntrada($id)
    {
        $entrada = new EntradaBienes();
        return $entrada->eliminarEntrada($id);
    }

    // Controlador para importar CSV
    public function importarCSV($archivo)
    {

        //Recorrer el fichero
        $lectura = fopen($archivo["tmp_name"], "r");
        while (($data = fgetcsv($lectura, 1000, ";"))) {
            //Buscar las lineas que empiezan con el campo Descripción
            $mystring = $data[0];
            $findme   = 'Descripc';
            $encontrado = strpos($mystring, $findme);
            if ($encontrado !== false) {
                // Dar formato a la fecha
                $date = $data[10];
                $date = str_replace('/', '-', $date);
                $fecha = date('Y-m-d', strtotime($date));
                //Elminiar el string "Descripción: " del primer elemento del array
                $descripcion = substr($data[0], 16);
                $cuenta = $data[9];
                $fecha_compra = $fecha;
                $fecha_inicio_amortizacion = $fecha;
                $precio = $data[12];
                $amortizacion = $data[13];
                $objeto = new EntradaBienes();
                $objeto->agregarEntrada($descripcion, $numero_factura = NULL, $proveedor_id = NULL, $fecha_compra, $fecha_inicio_amortizacion, $amortizacion, $precio, $cuenta);
            }
        }
    }
}
