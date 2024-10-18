<?php

namespace controllers;

@session_start();

use \models\Usuario;

class UsuarioController
{

    private $usuarioModel;

    // lista todos los usuarios 
    public function listarUsuarios()
    {

        $usuario = new Usuario();
        $usuarios = $usuario->obtenerTodos();
        $_SESSION['usuarios'] = $usuarios; // Guarda los datos en la sesión
    }
    //Recoge en una variable de sesion el usuario que se va a editar
    public function editarusuario($id)
    {
        $usuario = new Usuario();
        $usuario = $usuario->obtenerUno($id);
        $_SESSION['usuario'] = $usuario;
    }
    // Maneja la actualización de un usuario existente
    public function actualizarusuario()
    {
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
            $contrasena = $_POST["contrasena"];
            if ($contrasena != strip_tags($contrasena)) {
                // Si contiene etiquetas HTML, lanzar un error
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . USR_PATH . "editar.php");
                die;
            }
            $tipo_usuario = htmlspecialchars($_POST["tipo_usuario"]);
            $usuario->editarUsuario($id, $nombre, $contrasena, $tipo_usuario);
        }
    }
    //Maneja la creación de un nuevo usuario
    public function crearUsuario()
    {
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
            $user->agregarUsuario($nombre, $usuario, $contrasena, $tipo_usuario);
        }
    }
    // Elimina (lógicamente) un usuario de la base de datos
    public function eliminarUsuario($id)
    {
        $usuario = new Usuario();
        $usuario->eliminarUsuario($id);
    }
    //Obtiene el usuario (si existe de la base de datos)
    public function login($usuario)
    {
        $objeto = new Usuario();
        return $objeto->obtenerUSuario($usuario);
    }
}
