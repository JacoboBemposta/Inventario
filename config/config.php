<?php
@session_start();
// config.php

// Definir la constante BASE_PATH para la ruta base del proyecto
define('ROOT_PATH',  "http://192.168.200.21/projects/inventario/"); // Esto asume que config.php está en una subcarpeta


define('USR_PATH', ROOT_PATH  . 'views/usuarios/');
define('PROV_PATH', ROOT_PATH  . 'views/proveedores/');
define('ENT_PATH', ROOT_PATH  . 'views/entradas/');
define('BIEN_PATH', ROOT_PATH  . 'views/bienes/');
//define('FPDF_FONTPATH', ROOT_PATH.'fpdf/font/');
$base_url = 'http://192.168.200.21/projects/inventario/';
?>