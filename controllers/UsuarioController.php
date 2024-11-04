<?php

namespace controllers;

@session_start();

use \models\Usuario;

class UsuarioController
{

    private $usuarioModel;

    // lista todos los usuarios 
    public function listarUsuarios(){

        $usuario = new Usuario();
        $usuarios = $usuario->obtenerTodos();
        $_SESSION['usuarios'] = $usuarios; // Guarda los datos en la sesión
    }

    //Recoge en una variable de sesion el usuario que se va a editar
    public function editarusuario($id){
        $usuario = new Usuario();
        $usuario = $usuario->obtenerUno($id);
        $_SESSION['usuario'] = $usuario;
    }

    // Maneja la actualización de un usuario existente
    public function actualizarusuario(){
        $id = $_GET['usuario'];
        $usuario = new Usuario();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                $_SESSION["error"] = "Error en el envio del formulario";
                header("Location: " . ROOT_PATH . "error.php");
                die;
            }
            destruirTokenCSRF();
            $nombre = $_POST["nombre"];
            if ($nombre != strip_tags($nombre)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . USR_PATH . "editar.php");
                die;
            }
            $email = $_POST["email"];
            if ($email != strip_tags($email)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . USR_PATH . "crear.php");
                die;
            }                   
            $contrasena = $_POST["contrasena"];
            if ($contrasena != strip_tags($contrasena)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . USR_PATH . "editar.php");
                die;
            }
            $tipo_usuario = htmlspecialchars($_POST["tipo_usuario"]);
            $usuario->editarUsuario($id, $nombre, $contrasena, $tipo_usuario,$email);
        }
    }

    //Maneja la creación de un nuevo usuario
    public function crearUsuario(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                $_SESSION["error"] = "Error en el envio del formulario";
                header("Location: " . ROOT_PATH . "error.php");
                die;
            }
            destruirTokenCSRF();
            $nombre = $_POST["nombre"];
            if ($nombre != strip_tags($nombre)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . USR_PATH . "crear.php");
                die;
            }
            $email = $_POST["email"];
            if ($email != strip_tags($email)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . USR_PATH . "crear.php");
                die;
            }       

            $usuario = $_POST["usuario"];
            if ($usuario != strip_tags($usuario)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . USR_PATH . "crear.php");
                die;
            }
            $contrasena = $_POST["contrasena"];
            if ($contrasena != strip_tags($contrasena)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . USR_PATH . "crear.php");
                die;
            }
            $tipo_usuario = htmlspecialchars($_POST["tipo_usuario"]);
            $user = new Usuario();
            $user -> comprobarmail($email);
            if($user!=null){
                $_SESSION["error"] = "El correo ya existe";
                header("Location: " . USR_PATH . "crear.php");
                die;
            }
            $user->agregarUsuario($nombre, $usuario, $contrasena, $tipo_usuario,$email);
        }
    }

    // Elimina (lógicamente) un usuario de la base de datos
    public function eliminarUsuario($id){
        $usuario = new Usuario();
        $usuario->eliminarUsuario($id);
    }

    //Obtiene el usuario (si existe de la base de datos)
    public function login($usuario){
        $objeto = new Usuario();
        return $objeto->obtenerUSuario($usuario);
    }

    public function recuperarpass(){
        
        $email = $_POST['email'];
        $usuario = new Usuario();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                $_SESSION["error"] = "Error en el envio del formulario";
                header("Location: " . ROOT_PATH . "error.php");
                die;
            }
            destruirTokenCSRF();

            $email = $_POST["email"];
            if ($email != strip_tags($email)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . USR_PATH . "crear.php");
                die;
            }      
        $comrpobarmail=$usuario->comprobarmail($email);
        if($comrpobarmail == null){
            $_SESSION["error"] = "El email no existe";
            header("Location: " . USR_PATH . "recuperar.php");
            die;
        }
        // Generar un token único de recuperación
        $token = bin2hex(random_bytes(50)); // 50 bytes para un token seguro
        $expiracion = date("Y-m-d H:i:s", strtotime("+1 hour")); // Expira en 1 hora

        // Guardar el token y la expiración en la base de datos
        $usuario->guardarTokenRecuperacion($email, $token, $expiracion); 
        require_once("../config/config.php");
  
        // Crear el enlace de recuperación de contraseña
        $linkRecuperacion = MAIL_PATH . "/cambiarpass.php?mail=" . $email  . "&token=" . $token;


        // Enviar el correo de recuperación (se requiere configuración de correo)
        $asunto = "Link cambio de Contraseña";
        $mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña: 
                    <a href='" . $linkRecuperacion . "'>Restablecer contraseña</a>";
        $cabeceras = "From: no-reply@siga.com\r\n";
        $cabeceras .= "Content-Type: text/plain; charset=UTF-8";

        require_once ("../config/phpmailer.php");        
        
        if (enviarCorreoRecuperacion($email, $asunto, $mensaje)) {
            $_SESSION["success"] = "Hemos enviado un link al correo electrónico para cambiar la contraseña.";

            header("Location: " . ROOT_PATH . "error.php");
        } else {
            $_SESSION["error"] = "Error al enviar el correo. Inténtalo de nuevo.";
            header("Location: " . ROOT_PATH . "error.php");
        }
  
        }
    }

    public function comprobartoken($mail,$token){
        $token = $_GET["token"];
        $mail = $_GET["mail"];
        $usuario = new Usuario();
        $comprobado = $usuario->compruebatoken($mail,$token);
        return $comprobado;
    }

    public function comprobarmail($mail){
        $usuario = new Usuario();
        $comprobado = $usuario->comprobarmail($mail);
        return $comprobado;
    }

    public function editarpass($pass,$mail){
        $usuario=new Usuario();
        $comprobado =$usuario->editarpass($pass,$mail);
        return $comprobado;
    }
}
