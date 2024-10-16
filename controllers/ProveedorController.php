<?php
namespace controllers;
@session_start();
use \models\Proveedor;

class ProveedorController {
    private $proveedor;
    //Muestra el listado de proveedores
    public function listarProveedores() {
        $proveedor = new Proveedor();
        $proveedores = $proveedor->obtenerTodos();
        $_SESSION['proveedores'] = $proveedores;
        }

    
    // Muestra el formulario de edición de un proveedor existente
    public function editarProveedor($id) {
        $Proveedor = new Proveedor();
        $Proveedor = $Proveedor->obtenerUno($id);
        $_SESSION['proveedor'] = $Proveedor;
        }

    //Maneja la actualizacion de un proveedor existente    
    public function actualizarProveedor() {
        $id=$_GET['proveedor'];
        $Proveedor = new Proveedor();
        if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
            $_SESSION["error"]="Token no válido";
            header("Location: ".ROOT_PATH."error.php");
            die;
        }        
        $nombre = $_POST["nombre"];
        if ($nombre != strip_tags($nombre)) {
            // Si contiene etiquetas HTML, lanzar un error
            $_SESSION["error"] = "Formato incorrecto";
            header("Location: ".PROV_PATH."editar.php");
            die;
        }   
        $Proveedor->editarProveedor($id, $nombre);
        }

    // Maneja la creación de un nuevo proveedor
    public function crearProveedor() {      
        if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
            $_SESSION["error"]="Token no válido";
            header("Location: ".ROOT_PATH."error.php");
            die;
        }
        destruirTokenCSRF(); 
        $nombre = $_POST["nombre"];
        if ($nombre != strip_tags($nombre)) {
            // Si contiene etiquetas HTML, lanzar un error
            $_SESSION["error"] = "Formato incorrecto";
            header("Location: ".PROV_PATH."crear.php");
            die;
        }   
        $user = new Proveedor();
        $user->agregarProveedor($nombre);
        }
        
    // Elimina (lógicamente un proveedor de la base de datos)
    public function eliminarProveedor($id) {
        $Proveedor = new Proveedor();
        $Proveedor->eliminarProveedor($id);
        }
}
?>
