<?php 
include "../../menu.php";
require_once "../../config/auth.php";
@session_start();
?>
<div class="container d-flex flex-column  align-items-center mt-5" style="min-height: 50vh;">
    <h1 class="text-center">Agregar proveedor</h1>
    <?php include_once('../error.php');?>

    <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=proveedores&opcion=crear" method="POST" >
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del proveedor:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>    

        <div class="button-container mt-5">
            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn">Crear</button> 
            </div>

            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo PROV_PATH; ?>lista.php';">
                    Volver
                </button>
            </div>
        </div>
    </form>
</div>
