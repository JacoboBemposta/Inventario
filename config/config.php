<?php
@session_start();
// config.php

// Definir la constante BASE_PATH para la ruta base del proyecto
define('ROOT_PATH',  "http://localhost/proyects/inventario/"); // Esto asume que config.php está en una subcarpeta
define('RAIZ_PATH', 'C:/xampp/htdocs/proyects/inventario/'); // Esto asume que nuestras librerias estan en el servidor web

define('USR_PATH', ROOT_PATH  . 'views/usuarios/');
define('PROV_PATH', ROOT_PATH  . 'views/proveedores/');
define('ENT_PATH', ROOT_PATH  . 'views/entradas/');
define('BIEN_PATH', ROOT_PATH  . 'views/bienes/');
define('TEMP_PATH', RAIZ_PATH  . 'public/temp/');
define('QR_PATH', RAIZ_PATH . 'libs/phpqrcode/');
define('FPDF_PATH', RAIZ_PATH . 'libs/fpdf/');
//define('FPDF_FONTPATH', ROOT_PATH.'/fpdf/fpdf.php');
define('FPDF_FONTPATH', RAIZ_PATH.'libs/fpdf/font/');
$base_url = 'http://localhost/proyects/inventario/';
?>