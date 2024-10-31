<?php
@session_start();
// config.php
$departamentos=['00'=>'SIGA','01'=>'Técnico','02'=>'CAU','03'=>'Jurídico','04'=>'Administración',
'05'=>'Comercial','06'=>'Marketing y comunicación','07'=>'Patentes y Marcas','08'=>'Dirección',
'09'=>'Consejeros','10'=>'Almacén','11'=>'Sala Juntas','12'=>'Sala reuniones'];

asort($departamentos);

$tipo_bienes=["AL"=>'alfombra',"AR" =>'armario',"BA"=>'bandeja',"BU"=>'buck',"CI"=>'cizalla',"DE"=>'destructora',
 "AI"=>'equipo aire',"EC"=>'escalera',"ES"=>'estantería',"EX"=>'extintor',"FU"=>'funda',"IM"=>'impresora',
 "IP"=>'ipad',"LA"=>'lámpara',"ME"=>'mesa',"MO"=>'monitor',"PE"=>'perchero',"VC"=>'policom',"RE"=>'reposapiés',
 "OR"=>'ordenador',"RU"=>'roll-up',"SC"=>'scanner',"SI"=>'silla',"SP"=>'soporte pc',"PI"=>'pizarra',"PU"=>'puntero',
 "TV"=>'televisión',"WC"=>'webcam'];
 
 asort($tipo_bienes);       

 $centros=['1'=>'Pontevedra','2'=>'Madrid'];

 asort($centros);

define('ROOT_PATH',  "/proyects/inventario/"); // Ruta relativa
define('RAIZ_PATH', $_SERVER['DOCUMENT_ROOT'].'/proyects/inventario/'); // Ruta absoluta
define('USR_PATH', ROOT_PATH  . 'views/usuarios/');
define('PROV_PATH', ROOT_PATH  . 'views/proveedores/');
define('ENT_PATH', ROOT_PATH  . 'views/entradas/');
define('BIEN_PATH', ROOT_PATH  . 'views/bienes/');
define('TEMP_PATH', RAIZ_PATH  . 'public/temp/');
define('CONTROLLER_PATH', ROOT_PATH  . 'controllers/');
define('MAIL_PATH', 'http://localhost/proyects/inventario/views/usuarios');
define('FPDF_PATH', RAIZ_PATH . 'libs/fpdf/');
define('FPDF_FONTPATH', RAIZ_PATH.'libs/fpdf/font/');
$base_url = 'http://192.168.200.21/proyects/inventario/';
?>