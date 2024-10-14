<?php
namespace controllers;
@session_start();
use \models\Proveedor;

class ProveedorController {
    private $proveedor;

    public function listarProveedores() {
        $proveedor = new Proveedor();
        $proveedores = $proveedor->obtenerTodos();
        $_SESSION['proveedores'] = $proveedores; // Guarda los datos en la sesión
        }

    public function editarProveedor($id) {
        $Proveedor = new Proveedor();
        $Proveedor = $Proveedor->obtenerUno($id);
        $_SESSION['proveedor'] = $Proveedor;
        }
    public function actualizarProveedor() {
        $id=$_GET['proveedor'];
        $Proveedor = new Proveedor();
        $nombre = $_POST["nombre"];
        if ($nombre != strip_tags($nombre)) {
            // Si contiene etiquetas HTML, lanzar un error
            $_SESSION["error"] = "Formato incorrecto";
            header("Location: ".PROV_PATH."editar.php");
            die;
        }   
        $Proveedor->editarProveedor($id, $nombre);
        }

    public function crearProveedor() {      
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        }

    public function eliminarProveedor($id) {
        $Proveedor = new Proveedor();
        $Proveedor->eliminarProveedor($id);
        }
}
?>
