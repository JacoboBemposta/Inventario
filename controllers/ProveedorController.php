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
        $nombre=htmlspecialchars($_POST['nombre']);
        $Proveedor->editarProveedor($id, $nombre);
        }

    public function crearProveedor() {      
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = htmlspecialchars($_POST['nombre']);
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
