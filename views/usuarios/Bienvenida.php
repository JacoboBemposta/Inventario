<?php 
include "../../menu.php";
@session_start();
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
} else {
    $login = "Invitado"; // Manejar si no hay proveedores en la sesión
}
?>

<div class="container d-flex flex-column oficina" style="min-height: 70vh;">
    <h1>Bienvenido <?php echo $login?></h1>
    <h2>Inventario Oficinas</h2>
</div>