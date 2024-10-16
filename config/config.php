<?php
@session_start();
// config.php


define('ROOT_PATH',  "/proyects/inventario/"); // Ruta relativa
define('RAIZ_PATH', $_SERVER['DOCUMENT_ROOT'].'/proyects/inventario/'); // Ruta absoluta

define('USR_PATH', ROOT_PATH  . 'views/usuarios/');
define('PROV_PATH', ROOT_PATH  . 'views/proveedores/');
define('ENT_PATH', ROOT_PATH  . 'views/entradas/');
define('BIEN_PATH', ROOT_PATH  . 'views/bienes/');
define('TEMP_PATH', RAIZ_PATH  . 'public/temp/');
define('CONTROLLER_PATH', ROOT_PATH  . 'controllers/');

define('FPDF_PATH', RAIZ_PATH . 'libs/fpdf/');
define('FPDF_FONTPATH', RAIZ_PATH.'libs/fpdf/font/');
$base_url = 'http://192.168.200.21/proyects/inventario/';
?>