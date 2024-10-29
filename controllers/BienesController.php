<?php

namespace controllers;

@session_start();

use \models\Bienes;


class BienesController
{
    private $bienModel;


    // Muestra el listado de bienes de una entrada específica
    public function lista($entrada_bien_id)
    {
        $bienModel = new Bienes();
        $bienModel->obtenerPorEntradaId($entrada_bien_id);
    }
    //Muestra todos los bienes
    public function listarBienes()
    {
        $bienModel = new Bienes();
        $_SESSION["bienes"] = $bienModel->obtenerBienes();
    }
    // Devuelve un bien buscando por su id
    public function listarBienesporID($id)
    {
        $bienModel = new Bienes();
        return $bienModel->obtenerPorId($id);
    }

    public function buscarfiltro()
    {
        $bienModel = new Bienes();
        $centro = $_POST["search-centro"];
        if ($centro != strip_tags($centro)) {
            $_SESSION["error"] = "Formato incorrecto";
            header("Location: " . BIEN_PATH . "crear.php");
            die;
        }
        $departamento = $_POST["search-departamento"];
        if ($departamento != strip_tags($departamento)) {
            $_SESSION["error"] = "Formato incorrecto";
            header("Location: " . BIEN_PATH . "crear.php");
            die;
        }
        $tipo_bien = $_POST["search-tipo-bien"];
        if ($tipo_bien != strip_tags($tipo_bien)) {
            $_SESSION["error"] = "Formato incorrecto";
            header("Location: " . BIEN_PATH . "crear.php");
            die;
        }
        $estado = $_POST["search-estado"];
        if ($estado != strip_tags($estado)) {
            $_SESSION["error"] = "Formato incorrecto";
            header("Location: " . BIEN_PATH . "crear.php");
            die;
        }
        $fecha_inicio = $_POST["search-fecha-inicio"];
        if ($fecha_inicio != strip_tags($fecha_inicio)) {
            $_SESSION["error"] = "Formato incorrecto";
            header("Location: " . BIEN_PATH . "crear.php");
            die;
        }
        $fecha_fin = $_POST["search-fecha-fin"];
        if ($fecha_fin != strip_tags($fecha_fin)) {
            $_SESSION["error"] = "Formato incorrecto";
            header("Location: " . BIEN_PATH . "crear.php");
            die;
        }
        $descripcion = $_POST["search-descripcion"];
        if ($descripcion != strip_tags($descripcion)) {

            $_SESSION["error"] = "Formato incorrecto";
            header("Location: " . BIEN_PATH . "crear.php");
            die;
        }
        $cuenta = $_POST["search-cuenta"];
        if ($cuenta != strip_tags($cuenta)) {

            $_SESSION["error"] = "Formato incorrecto";
            header("Location: " . BIEN_PATH . "crear.php");
            die;
        }
        //echo $centro.$departamento.$tipo_bien.$estado;die;   
        //echo $fecha_inicio.$fecha_fin.$descripcion.$cuenta;die;                                                    
        return $bienModel->buscarfiltro($centro, $departamento, $tipo_bien, $estado, $fecha_inicio, $fecha_fin, $descripcion, $cuenta);
    }


    // Maneja la creación de un nuevo bien
    public function crear()
    {
        $bienModel = new Bienes();

    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                $_SESSION["error"] = "Error en el envio del formulario";
                header("Location: " . ROOT_PATH . "error.php");
                die;
            }
            destruirTokenCSRF();
            // Capturar los datos del formulario
            // Si contiene etiquetas HTML, lanzar un error
            $descripcion = $_POST["descripcion"];
            if ($descripcion != strip_tags($descripcion)) {

                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "crear.php");
                die;
            }
            $precio = $_POST["precio"];
            if ($precio != strip_tags($precio)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "crear.php");
                die;
            }
            $centro = $_POST["centro"];
            if ($centro != strip_tags($centro)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "crear.php");
                die;
            }
            $departamento = $_POST["departamento"];
            if ($departamento != strip_tags($departamento)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "crear.php");
                die;
            }
            $tipo_bien = $_POST["tipo_bien"];
            if ($tipo_bien != strip_tags($tipo_bien)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "crear.php");
                die;
            }


            $entrada_bien_id = htmlspecialchars($_POST["entrada_bien_id"]);
            // Guardar el bien en la base de datos
            $bienModel->agregarBien($descripcion, $precio, $centro, $departamento, $tipo_bien, $entrada_bien_id);
        }
    }

    // Muestra el formulario de edición de un bien existente
    public function editar($id)
    {
        $bienModel = new Bienes();
        $bienModel = $bienModel->obtenerPorId($id); // Obtener los datos del bien
        $_SESSION['bien'] = $bienModel;
    }

    // Maneja la actualización de un bien existente
    // Si contiene etiquetas HTML, lanzar un error
    public function actualizar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                $_SESSION["error"] = "Error en el envio del formulario";
                header("Location: " . ROOT_PATH . "error.php");
                die;
            }
            $bienModel = new Bienes();
            $descripcion = $_POST["descripcion"];
            if ($descripcion != strip_tags($descripcion)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "editar.php");
                die;
            }
            $precio = $_POST["precio"];
            if ($precio != strip_tags($precio)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "editar.php");
                die;
            }
            $centro = $_POST["centro"];
            if ($centro != strip_tags($centro)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "editar.php");
                die;
            }
            $departamento = $_POST["departamento"];
            if ($departamento != strip_tags($departamento)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "editar.php");
                die;
            }
            $tipo_bien = $_POST["tipo_bien"];
            if ($tipo_bien != strip_tags($tipo_bien)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "editar.php");
                die;
            }
            $causa_baja = $_POST["causa_baja"];
            if ($causa_baja != strip_tags($causa_baja)) {
                $_SESSION["error"] = "Formato incorrecto";
                header("Location: " . BIEN_PATH . "editar.php");
                die;
            }
            // Actualizar el bien en la base de datos
            $bienModel->editarBien($id, $descripcion, $precio, $centro, $departamento, $tipo_bien, $causa_baja);
            //eliminar si se ha seleccionado una causa de baja
            if ($causa_baja != "NULL") {
                $bienModel->desactivarbien($causa_baja, $id);
            }
        }
    }

    // Elimina (lógicamente) un bien de la base de datos
    public function eliminar($id)
    {
        $bienModel = new Bienes();
        return $bienModel->eliminarBien($id);
    }

    public function actualizaEstado($motivo, $nuevoEstado,$bienId)
    {
        $bienModel = new Bienes();
        $resultado = $bienModel->actualizaEstado($motivo, $nuevoEstado,$bienId);
        return $resultado;
    }
}
