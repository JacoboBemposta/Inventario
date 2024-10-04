<?php 
include "config/config.php";
@session_start();
if(isset($_SESSION["login"])){
    if(isset($_SESSION["login"])){
        if($_SESSION["login"]!=="Invitado"){
            header("Location: ".USR_PATH."Bienvenida.php");
        }else header("Location: ".ROOT_PATH."inicio.php");
        
    }else header("Location: ".ROOT_PATH."inicio.php");
}else {
    header("Location: ".ROOT_PATH."inicio.php");
}


?>
