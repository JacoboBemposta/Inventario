<?php
namespace controllers;
@session_start();
use \models\Bienes;


class BienesController {
    private $bienModel;


    // Muestra el listado de bienes de una entrada específica
    public function lista($entrada_bien_id) {
        $bienModel = new Bienes();
        $bienModel->obtenerPorEntradaId($entrada_bien_id); 

        
    }
    public function listarBienes() {
        $bienModel = new Bienes();
        $_SESSION["bienes"] = $bienModel->obtenerBienes(); 

    }
    // Devuelve un bien buscando por su id
    public function listarBienesporID($id) {
        $bienModel = new Bienes();
        return $bienModel->obtenerPorId($id);
    }




    // Maneja la creación de un nuevo bien
    public function crear() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bienModel = new Bienes();
            
            // Capturar los datos del formulario
            $descripcion = $_POST["descripcion"];
            if ($descripcion != strip_tags($descripcion)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."crear.php");
                die;
            }        
            $precio = $_POST["precio"];
            if ($precio != strip_tags($precio)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."crear.php");
                die;
            }     
            $centro = $_POST["centro"];
            if ($centro != strip_tags($centro)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."crear.php");
                die;
            }                 
            $departamento = $_POST["departamento"];
            if ($departamento != strip_tags($departamento)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."crear.php");
                die;
            }         
            $tipo_bien = $_POST["tipo_bien"];
            if ($tipo_bien != strip_tags($tipo_bien)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."crear.php");
                die;
            }              
            $codigo = $_POST["codigo"];
            if ($codigo != strip_tags($codigo)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."crear.php");
                die;
            }          

            $entrada_bien_id = htmlspecialchars($_POST["entrada_bien_id"]);
            // Guardar el bien en la base de datos
            $bienModel->agregarBien($descripcion, $precio, $centro, $departamento, $tipo_bien, $codigo, $entrada_bien_id) ;
        }
    }

    // Muestra el formulario de edición de un bien existente
    public function editar($id) {
        $bienModel = new Bienes();
        $bienModel=$bienModel->obtenerPorId($id); // Obtener los datos del bien
        $_SESSION['bien']=$bienModel;

    }

    // Maneja la actualización de un bien existente
    public function actualizar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bienModel = new Bienes();
            $descripcion = $_POST["descripcion"];
            if ($descripcion != strip_tags($descripcion)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."editar.php");
                die;
            }        
            $precio = $_POST["precio"];
            if ($precio != strip_tags($precio)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."editar.php");
                die;
            }     
            $centro = $_POST["centro"];
            if ($centro != strip_tags($centro)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."editar.php");
                die;
            }          
            $departamento=$_POST["departamento"];
            if ($departamento != strip_tags($departamento)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."editar.php");
                die;
            }         
            $tipo_bien = $_POST["tipo_bien"];
            if ($tipo_bien != strip_tags($tipo_bien)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."editar.php");
                die;
            }         
            $causa_baja = $_POST["causa_baja"];
            if ($causa_baja != strip_tags($causa_baja)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: ".BIEN_PATH."editar.php");
                die;
            }                    


            // Actualizar el bien en la base de datos

            $bienModel->editarBien($id, $descripcion, $precio, $centro, $departamento, $tipo_bien, $causa_baja);
            //eliminar si se ha seleccionado una causa de baja
            if($causa_baja!="NULL") {
                $bienModel->eliminarBien($causa_baja,$id);
            }

        }
    }

    // Elimina (lógicamente) un bien de la base de datos
    public function eliminar($id,$motivo) {
        $bienModel = new Bienes();
        $bienModel->obtenerPorId($id);

        if ($bienModel) {
            // Eliminación lógica del bien
            $bienModel->eliminarBien($id,$motivo);
        }
    }
}
