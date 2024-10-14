<?php 
include "../../menu.php";
require_once "../../config/auth.php";
@session_start();
if (isset($_SESSION['proveedor'])) {
    $proveedor = $_SESSION['proveedor'];
} else {
    $proveedor = []; // Manejar si no hay proveedores en la sesión
}
?>
<div class="container d-flex flex-column align-items-center mt-5" style="height: 50vh;">
    <h1 class="text-center mb-4">Editar proveedor</h1>
    <?php include_once('../error.php');?>
    <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=proveedores&opcion=actualizar&proveedor=<?php echo $proveedor['id'] ?>" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Proveedor:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $proveedor['nombre']; ?>" required>
        </div>
        <div class="button-container mt-5">
            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn">Actualizar</button> 
            </div>

            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo PROV_PATH; ?>lista.php';">
                    Volver
                </button>
            </div>
    </form>
</div>

</div>