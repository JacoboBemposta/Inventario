<?php 
@session_start();
require_once __DIR__ .'/../autoload.php';
require __DIR__ .'/../menu.php';

use BienesController as GlobalBienesController;
use controllers\UsuarioController;
use controllers\ProveedorController;
use controllers\EntradaBienesController;
use controllers\BienesController;
use models\Bienes;
use models\Proveedor;

// Controlador de rutas, recibe los parámetros por formulario y los envía al correspondiente controlador
if(isset($_GET["ctrl"])) $ctrl= $_GET["ctrl"];
else $ctrl="usuarios";
if(isset($_GET["opcion"])) $opcion= $_GET["opcion"];
else $opcion="ver";
if($ctrl=="usuarios"){

    $objeto=new UsuarioController();
    
    switch ($opcion) {
        case 'ver':
            $objeto->listarUsuarios();
            header("Location: " . USR_PATH . 'lista.php');
            exit();  // Asegúrate de detener la ejecución después de la redirección
            break;
        case 'editar':
            $objeto->editarusuario($_GET['usuario']);
            header("Location: " . USR_PATH . 'editar.php');
            break;
        case 'actualizar':
            $objeto->actualizarusuario($_GET['usuario']);
            $objeto->listarUsuarios();
            header("Location: ".USR_PATH."lista.php");
            break;
        case 'crear':
            $objeto->crearUsuario();
            $objeto->listarUsuarios();
            header("Location: ".USR_PATH."lista.php");
            break;
        case 'eliminar':
            $objeto->eliminarUsuario($_GET['usuario']);
            $objeto->listarUsuarios();
            header("Location: ".USR_PATH."lista.php");
            break;
        //comprueba que el usuario y la contraseña son correctos
        case 'login':
            $validar="";
            $objeto = new UsuarioController();
            $usuario=$_POST["usuario"];
            $pass=$_POST["password"];
            $user = $objeto->login($usuario);
            if($user!=null) {
                if($user[0]["usuario"]!=="Invitado"){
                    // Si el usuario y la contraseña son correctos
                    if (strcasecmp($usuario, $user[0]["usuario"]) == 0 && password_verify($pass, $user[0]["contrasena"])) {
                        $_SESSION["login"]=$user[0]["usuario"];
                        $_SESSION["tipo_usuario"]=$user[0]["tipo_usuario"];
                        header("Location: ".USR_PATH."Bienvenida.php");
                    } else {
                        // Si la contraseña no coincide
                        $_SESSION["login"]="Invitado";
                        $_SESSION["error"]="Contraseña incorrecta";
                        header("Location: ".ROOT_PATH."error.php");
                    }
                }
            }else {
                // Si el usuario no existe
                $_SESSION["error"]="El usuario $usuario no existe";
                header("Location: ".ROOT_PATH."error.php");
            }
            break;
        case 'logout':
            $_SESSION["login"]="Invitado";
            header("Location: ".ROOT_PATH)."inicio.php";
            break;    

        
        default:
            # code...
            break;
    }
}else if($ctrl=="proveedores"){
    $objeto=new ProveedorController();
    
    switch ($opcion) {
        case 'ver':
            $objeto->listarProveedores();
            header("Location: " . PROV_PATH . 'lista.php');
            break;
        case 'editar':
            $objeto->editarProveedor($_GET['proveedor']);
            
            header("Location: " . PROV_PATH . 'editar.php');
            break;
        case 'actualizar':
            $objeto->actualizarProveedor($_GET['proveedor']);
            $objeto->listarProveedores();
            header("Location: " . PROV_PATH . 'lista.php');
            break;
        case 'crear':
            $objeto->crearProveedor();
            $objeto->listarProveedores();
            header("Location: " . PROV_PATH . 'lista.php');
            break;
        case 'eliminar':
            $objeto->eliminarProveedor($_GET['proveedor']);
            $objeto->listarProveedores();
            header("Location: " . PROV_PATH . 'lista.php');
            break;
        default:
            # code...
            break;
    }
}else if($ctrl=="entradas"){
    $objeto=new EntradaBienesController();
    
    switch ($opcion) {
        case 'ver':          
            $objeto->listarEntradas();
            header("Location: " . ENT_PATH . 'lista.php');
            break;
        case 'editar':
            $objeto->editarEntrada($_GET['entrada']);
            $proveedor=new ProveedorController();
            $proveedor->listarProveedores();
            header("Location: " . ENT_PATH . 'editar.php');
            break;
        case 'actualizar':
            $objeto->actualizarentrada();
            $objeto->listarEntradas();
            header("Location: " . ENT_PATH . 'lista.php');
            break;
        case 'crear':
            $entrada=new EntradaBienesController();
            $entrada->listarEntradas();
            $objeto->crearEntrada();
            $objeto->listarEntradas();
            header("Location: " . ENT_PATH . 'lista.php');
            break;
        case 'eliminar':
            $eliminado=$objeto->eliminarEntrada($_GET['entrada']);
            if(!$eliminado) 
            {
                header("Location: ".ROOT_PATH."error.php");

            }else{
                $objeto->listarEntradas();
                header("Location: " . ENT_PATH . 'lista.php');
            }
            
            break;
        case 'importar':
            // Recibe un archivo CSV y lo envía al controlador para tratar los datos
            $archivo=$_FILES["archivocsv"];
            $objeto = new EntradaBienesController;
            $objeto->importarCSV($archivo);
            $objeto->listarEntradas();
            header("Location: " . ENT_PATH . 'lista.php');
            break;  
        case 'exportar':
            // Exportar las entradas en formato pdf
            // $entradas = new EntradaBienesController();
            // $entradas->listarEntradas();
            // header("Location: " . ROOT_PATH . 'pdf.php');
            break;                
        default:
            # code...
            break;
    }
}else if($ctrl=="bienes"){
    $objeto = new BienesController();
    $entrada = new EntradaBienesController();
    switch ($opcion) {
        case 'ver':          
            $objeto->listarBienes();
            
            header("Location: " . BIEN_PATH . 'lista.php');
            break;
        case 'editar':
            $objeto->editar($_GET['bien']);
            header("Location: " . BIEN_PATH . 'editar.php');
            break;
        case 'actualizar':
            $objeto->actualizar($_GET['bien']);
            $entrada->listarEntradas();
            header("Location: " . ENT_PATH . 'lista.php');
            break;
        case 'crear':
            $objeto->crear();
            $entrada->listarEntradas();
            header("Location: " . ENT_PATH . 'lista.php');
            break;
        case 'eliminar':
            $objeto->eliminar($_GET['bien']);
            $entrada->listarEntradas();
            header("Location: " . ENT_PATH . 'lista.php');
            break;
        case 'generarEtiquetas':
            // Manejo de error si no se envia la posicion 
            if (empty($_POST['posicion'])) {
                
                $_SESSION["erroretiquta"]="Debes indicar una posición correcta";
                header("Location: " . BIEN_PATH . 'lista.php');
                die;
            }
            // Manejo de error si no se envia un valor de posición correcto
            if($_POST["posicion"]<=0 || $_POST["posicion"]>33) {
                $_SESSION["erroretiquta"]="Debes indicar una posición correcta";
                header("Location: " . BIEN_PATH . 'lista.php');
                die;                
            }
            //Asignamos el valor de la posición a una variable de sesión
            $_SESSION["posicion"]=$_POST["posicion"];
            // recibe una lista de bienes por id, recoge los bienes y llama al archivo para generar etiquetas
            if (!empty($_POST['bienes']) && !empty($_POST["posicion"])) {
                $_SESSION['bienes']=[];
                foreach ($_POST['bienes'] as $bienSeleccionado) {
                    $bien = $objeto->listarBienesporID($bienSeleccionado);
                    if ($bien) {
                        $_SESSION["bienes"][] = $bien; // Agrega el bien al array
                    }
                }
                header("Location: " . ROOT_PATH . 'pdf.php');
            } else {
                // Manejo de error si no se selecciona ningún bien
                $_SESSION["erroretiquta"]="Debes seleccionar al menos una etiqueta";
                header("Location: " . BIEN_PATH . 'lista.php');
            }
            
            break;            
        default:
            # code...
            break;
    }
}
?>