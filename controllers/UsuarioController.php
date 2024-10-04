<?php

namespace controllers;
@session_start();
use \models\Usuario;

class UsuarioController {

    private $usuarioModel;
    

    public function listarUsuarios() {

        $usuario = new Usuario();
        $usuarios = $usuario->obtenerTodos();
        $_SESSION['usuarios'] = $usuarios; // Guarda los datos en la sesión
        }

    public function editarusuario($id) {
        $usuario = new Usuario();
        $usuario = $usuario->obtenerUno($id);
        $_SESSION['usuario'] = $usuario;
        }
    public function actualizarusuario() {
        $id=$_GET['usuario'];
        $usuario = new Usuario();
        $nombre=$_POST['nombre'];
        $contrasena = $_POST['contrasena'];
        $tipo_usuario=$_POST['tipo_usuario'];
        $usuario->editarUsuario($id,$nombre, $contrasena, $tipo_usuario);
        }

    public function crearUsuario() {
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];
            $tipo_usuario = $_POST['tipo_usuario'];
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
