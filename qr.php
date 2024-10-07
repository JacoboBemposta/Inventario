<?php 
@session_start();
include "config/config.php";
require_once QR_PATH.'qrlib.php';
if(isset($_SESSION["bien"])) $nombre=$_SESSION["bien"];
else $nombre="test";
if(!file_exists(TEMP_PATH)) mkdir(TEMP_PATH);

$filename = TEMP_PATH.$nombre.'.png';
echo $filename;die;
$tamanho=2; // tamaño de la imagen
$level='H'; // tipo de precision (baja l, media M, alta Q ,maxima H)
$framesize=3; //marco del qr en blanco
$contenido='Hola Mundo';

QRcode::png($contenido,$filename,$level,$tamanho,$framesize);

echo '<img src="'.ROOT_PATH.'public/temp/'.$nombre.'.png">';

?>