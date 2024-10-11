<?php

namespace controllers;
@session_start();
use \models\Usuario;

class UsuarioController {

    private $usuarioModel;
    
    // lista todos los usuarios y los guarda en una variable de sesion
    public function listarUsuarios() {

        $usuario = new Usuario();
        $usuarios = $usuario->obtenerTodos();
        $_SESSION['usuarios'] = $usuarios; // Guarda los datos en la sesión
        }
    //Recoge en una variable de sesion el usuario que se va a editar
    public function editarusuario($id) {
        $usuario = new Usuario();
        $usuario = $usuario->obtenerUno($id);
        $_SESSION['usuario'] = $usuario;
        }
    // Actualiza los datos del usuario
    public function actualizarusuario() {
        $id=$_GET['usuario'];
        $usuario = new Usuario();
        $nombre=htmlspecialchars($_POST['nombre']);
        $contrasena = htmlspecialchars($_POST['contrasena']);
        $tipo_usuario=htmlspecialchars($_POST['tipo_usuario']);
        $usuario->editarUsuario($id,$nombre, $contrasena, $tipo_usuario);
        }

    public function crearUsuario() {
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = htmlspecialchars($_POST['nombre']);
            $usuario = htmlspecialchars($_POST['usuario']);
            $contrasena = htmlspecialchars($_POST['contrasena']);
            $tipo_usuario = htmlspecialchars($_POST['tipo_usuario']);
            $user = new Usuario();
            $user->agregarUsuario($nombre, $usuario, $contrasena, $tipo_usuario);
                }
        }

    public function eliminarUsuario($id) {
        $usuario = new Usuario();
        $usuario->eliminarUsuario($id);
        }
        
    public function login($usuario) {
        $objeto = new Usuario();
        return $objeto->obtenerUSuario($usuario);

    }
} 
?>
