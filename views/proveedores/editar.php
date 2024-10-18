<?php
include "../../menu.php";
require_once "../../config/auth.php";
include_once "../../csrf.php";
@session_start();
if (isset($_SESSION['proveedor'])) {
    $proveedor = $_SESSION['proveedor'];
} else {
    $proveedor = []; 
}
?>
<!-- Vista editar un proveedor existente -->
<div class="container d-flex flex-column align-items-center mt-5" style="height: 50vh;">
    <?php include_once('../error.php'); ?>
    <h1 class="text-center mb-4">Editar proveedor</h1>
    <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=proveedores&opcion=actualizar&proveedor=<?php echo $proveedor['id'] ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Proveedor:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $proveedor['nombre']; ?>" required>
        </div>
        <div class="button-container mt-5">
            <!-- Botón actualizar -->
            <div class="wrap-login-form-btn">
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn">Actualizar</button>
            </div>
            <!-- Botón volver -->
            <div class="wrap-login-form-btn">
                <div class="login-form-bgbtn"></div>
                <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo PROV_PATH; ?>lista.php';">
                    Volver
                </button>
            </div>
    </form>
</div>

</div>