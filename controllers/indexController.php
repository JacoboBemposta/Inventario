<?php

@session_start();
require_once __DIR__ . '/../autoload.php';
require __DIR__ . '/../config/config.php';
include_once '../csrf.php';

use BienesController as GlobalBienesController;
use controllers\UsuarioController;
use controllers\ProveedorController;
use controllers\EntradaBienesController;
use controllers\BienesController;
use models\Bienes;
use models\EntradaBienes;
use models\Proveedor;

// Controlador de rutas, recibe los parámetros por formulario y los envía al correspondiente controlador
if (isset($_GET["ctrl"])) $ctrl = $_GET["ctrl"];
else $ctrl = "usuarios";
if (isset($_GET["opcion"])) $opcion = $_GET["opcion"];
else $opcion = "ver";
if ($ctrl == "usuarios") {

    $objeto = new UsuarioController();

    switch ($opcion) {
        case 'ver':
            $objeto->listarUsuarios();
            header("Location: " . USR_PATH . 'lista.php');
            exit();
            break;
        case 'editar':
            $objeto->editarusuario($_GET['usuario']);
            header("Location: " . USR_PATH . 'editar.php');
            break;
        case 'actualizar':
            $objeto->actualizarusuario($_GET['usuario']);
            $objeto->listarUsuarios();
            header("Location: " . USR_PATH . "lista.php");
            break;
        case 'crear':
            $objeto->crearUsuario();
            $objeto->listarUsuarios();
            header("Location: " . USR_PATH . "lista.php");
            break;
        case 'eliminar':
            $objeto->eliminarUsuario($_GET['usuario']);
            $objeto->listarUsuarios();
            header("Location: " . USR_PATH . "lista.php");
            break;
            //comprueba que el usuario y la contraseña son correctos
        case 'login':
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                    $_SESSION["error"] = "Error en el envio del formulario";
                    header("Location: " . ROOT_PATH . "inicio.php");
                }
                destruirTokenCSRF();
                $validar = "";
                $objeto = new UsuarioController();
                $usuario = htmlspecialchars($_POST["usuario"]);
                $pass = htmlspecialchars($_POST["password"]);
                $user = $objeto->login($usuario);
                if ($user != null) {
                    
                    if ($user[0]["usuario"] !== "Invitado") {
                        // Si el usuario y la contraseña son correctos
                        
                        if (strcasecmp($usuario, $user[0]["usuario"]) == 0 && password_verify($pass, $user[0]["contrasena"])) {
                            $_SESSION["login"] = $user[0]["usuario"];
                            $_SESSION["tipo_usuario"] = $user[0]["tipo_usuario"];
                            header("Location: " . USR_PATH . "Bienvenida.php");
                        } else {
                            
                            // Si la contraseña no coincide
                            $_SESSION["login"] = "Invitado";
                            $_SESSION["error"] = "Contraseña incorrecta";
                            header("Location: " . ROOT_PATH . "inicio.php");
                        }
                    }
                } else {
                    // Si el usuario no existe
                    $_SESSION["error"] = "El usuario $usuario no existe";
                    header("Location: " . ROOT_PATH . "inicio.php");
                }
            }
            break;
        case 'logout':
            $_SESSION["login"] = "Invitado";
            header("Location: " . ROOT_PATH . "inicio.php");
            break;
        case 'recuperar':    
            $objeto->recuperarpass();
            break;
        
        case 'comprobartoken':
            $token = $_GET["token"];
            $mail = $_GET["mail"];
            $validado = $objeto->comprobartoken($mail,$token);
            $_SESSION["validado"]=$validado;
            break;

        case 'editarpass':

            $mail = $_GET["mail"];
            $token = $_GET["token"];
            $pass = $_POST["pass"];
            $nuevapass = $_POST["nuevapass"];
            if($pass!=$nuevapass) {
                $_SESSION["error"] = "Las contraseñas no coinciden";
                header('Location: ' . USR_PATH . 'cambiarpass.php?mail=' . urlencode($email) . '&token=' . urlencode($token));
            }else{
                $mailOK = $objeto->comprobarmail($mail);
                if($mailOK){
                    $actualizado = $objeto->editarpass($pass,$mail,);
                    if($actualizado = 1){
                        $_SESSION["success"] = "Contraseña actualizada";
                        header("Location: " . ROOT_PATH . "error.php");
                    }else {
                        $_SESSION["error"] = "No se ha podido actualizar la contraseña";
                        header("Location: " . ROOT_PATH . "error.php");
                    }
                }
                
            }


            $_SESSION["validado"]=$validado;
            break;            
        default:
            # code...
            break;
    }
} else if ($ctrl == "proveedores") {
    $objeto = new ProveedorController();

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
} else if ($ctrl == "entradas") {
    $objeto = new EntradaBienesController();

    switch ($opcion) {
        case 'ver':
            $objeto->listarEntradas();
            $proveedor = new ProveedorController();
            $proveedor->listarProveedores();
            header("Location: " . ENT_PATH . 'lista.php');
            break;
        case 'editar':
            $objeto->editarEntrada($_GET['entrada']);
            $proveedor = new ProveedorController();
            $proveedor->listarProveedores();
            header("Location: " . ENT_PATH . 'editar.php');
            break;
        case 'actualizar':
            $objeto->actualizarentrada();
            $objeto->listarEntradas();
            header("Location: " . ENT_PATH . 'lista.php');
            break;
        case 'crear':
            $entrada = new EntradaBienesController();
            $entrada->listarEntradas();
            $objeto->crearEntrada();
            $objeto->listarEntradas();
            header("Location: " . ENT_PATH . 'lista.php');
            break;
        case 'eliminar':
            $eliminado = $objeto->eliminarEntrada($_GET['entrada']);
            if (!$eliminado) {
                header("Location: " . ENT_PATH . "lista.php");
            } else {
                $objeto->listarEntradas();
                header("Location: " . ENT_PATH . 'lista.php');
            }

            break;
        case 'importar':
            // Recibe un archivo CSV y lo envía al controlador para tratar los datos
            $archivo = $_FILES["archivocsv"];
            $objeto = new EntradaBienesController;
            $objeto->importarCSV($archivo);
            $objeto->listarEntradas();
            header("Location: " . ENT_PATH . 'lista.php');
            break;
        default:
            # code...
            break;
    }
} else if ($ctrl == "bienes") {
    $objeto = new BienesController();
    $entrada = new EntradaBienesController();
    switch ($opcion) {
        case 'ver':
            $objeto->listarBienes();
            $_SESSION["bienestotal"] = [];
            foreach ($_SESSION['bienes'] as $bienSeleccionado) {
                $bien = $objeto->listarBienesporID($bienSeleccionado["id"]);
                if ($bien) {

                    $_SESSION["bienestotal"][] = $bien;
                }
            }
            header("Location: " . BIEN_PATH . 'lista.php');
            break;

        case 'bienporID':
            header('Content-Type: application/json');
            $bien = new EntradaBienesController();
            $resultado=$bien->BienPorEntradaId($_POST['idEntrada']);
            $entrada_bien_id = $_POST['idEntrada'];

            foreach ($resultado as $bien) {
  
                $bien["entrada_bien_id"] = $entrada_bien_id;

            }
            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
            exit;
            break;

        case 'buscar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                    $_SESSION["error"] = "Error en el envio del formulario";
                    header("Location: " . ROOT_PATH . "error.php");
                    die;
                }
                destruirTokenCSRF();
                $_SESSION["bienestotal"] = $objeto->buscarfiltro();

                header("Location: " . BIEN_PATH . 'lista.php');
                break;
            }
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

        case 'eliminarbien':
            header('Content-Type: application/json');

            $resultado=$objeto->eliminar($_POST['bien_id']);
            if ($resultado) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la base de datos']);
            }
            exit;
            $entrada->listarEntradas();

            header("Location: " . ENT_PATH . 'lista.php');
            break;

        case 'generarEtiquetas':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                    $_SESSION["error"] = "Error en el envio del formulario";
                    header("Location: " . ROOT_PATH . "error.php");
                    die;
                }
                destruirTokenCSRF();
                // Manejo de error si no se selecciona ningún bien
                if (empty($_POST["bienes"])) {

                    $_SESSION["erroretiquta"] = "Debes seleccionar al menos una etiqueta";
                    header("Location: " . BIEN_PATH . 'lista.php');
                    die;
                }

                // Manejo de error si no se envia la posicion 
                if (empty(htmlspecialchars($_POST['posicion']))) {

                    $_SESSION["erroretiqueta"] = "Debes indicar una posición correcta";
                    header("Location: " . BIEN_PATH . 'lista.php');
                    die;
                }

                // Manejo de error si no se envia un valor de posición correcto
                if (htmlspecialchars($_POST["posicion"]) <= 0 || htmlspecialchars($_POST["posicion"] > 33)) {
                    $_SESSION["erroretiqueta"] = "Debes indicar una posición correcta";
                    header("Location: " . BIEN_PATH . 'lista.php');
                    die;
                }

                //Asignamos el valor de la posición a una variable de sesión
                $_SESSION["posicion"] = htmlspecialchars($_POST["posicion"]);

                // recibe una lista de bienes por id, recoge los bienes y llama al archivo para generar etiquetas
                if (!empty($_POST['bienes']) && !empty($_POST["posicion"])) {
                    $_SESSION['bienes'] = [];

                    foreach ($_POST['bienes'] as $bienSeleccionado) {

                        $bien = $objeto->listarBienesporID($bienSeleccionado);
                        if ($bien) {

                            $_SESSION["bienes"][] = $bien;
                        }
                    }

                    header("Location: " . ROOT_PATH . 'imp_etiquetas.php');
                }
            }
            break;

        case 'generarPDF':

            // Manejo de error si no se selecciona ningún bien
            if (empty($_POST["bienes"])) {
                $_SESSION["erroretiquta"] = "Debes seleccionar al menos una etiqueta";
                header("Location: " . BIEN_PATH . 'lista.php');
                die;
            } else {
                $_SESSION['bienes'] = [];

                foreach ($_POST['bienes'] as $bienSeleccionado) {

                    $bien = $objeto->listarBienesporID($bienSeleccionado);
                    if ($bien) {

                        $_SESSION["bienes"][] = $bien;
                    }
                }
   
                header("Location: " . ROOT_PATH . 'pdf.php');
            }
            break;

            case 'estadoBien':
                header('Content-Type: application/json'); // Nos aseguramos el tipo de respuesta JSON
            
                if (isset($_POST["motivo"],$_POST['nuevoEstado'], $_POST['bien_id'])) {
                    $nuevoEstado = $_POST['nuevoEstado'];
                    $bienId = $_POST['bien_id'];
                    $motivo = $_POST["motivo"] ?? "No especificado";
            
                    // Llamada al modelo y a la función del controlador
                    $resultado = $objeto->actualizaEstado($motivo, $nuevoEstado, $bienId);
            
                    if ($resultado) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al actualizar la base de datos']);
                    }
                     } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Parámetros faltantes',
                        'debug' => [
                            'POST_data' => $_POST, // Muestra todos los datos recibidos
                        ]
                    ]);
                }
                exit;
            
            break;

        default:
            # code...
            break;
    }
}
