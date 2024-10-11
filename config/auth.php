<?php 
if(!isset($_SESSION["login"]) || ($_SESSION["login"]=="Invitado")){
    header("Location: ".ROOT_PATH)."inicio.php";
}
?>