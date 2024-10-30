<?php 
include "../../menu.php";
require_once "../../config/auth.php";
require_once "../../config/config.php";
include_once "../../csrf.php";
@session_start();
if (isset($_SESSION['entradas'])) {
    $entradas = $_SESSION['entradas'];
} else {
    $entradas = []; // Manejar si no hay entradas en la sesión
}
   $entrada=$_GET["entrada"];

?>
<!-- Formulario para crear un nuevo bien-->
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
    <?php include_once('../error.php');?>
    <h1 class="text-center">Nuevo bien</h1>

        <!-- Formulario crear bienes -->
        <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bienes&opcion=crear" method="POST"  style="width: 45%;">
            <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
            <div class="mb-3">
                <label for="entrada" class="form-label">Entrada:</label>
                <!-- Recorre todas las entradas y cubre el valor de los campos descripción e id de la entrada del bien seleccionado -->
                <?php foreach ($entradas as $entrada): ?>
                    <?php if ($entrada['id'] == $_GET['entrada']): ?>
                        <input type="text" name="entrada" id="entrada" class="form-control" value="<?php echo $entrada['descripcion']; ?>" readonly>
                        <input type="text" name="entrada_bien_id" id="entrada_bien_id" class="form-control" value="<?php echo $entrada['id']; ?>" hidden>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" name="descripcion" class="form-control"  required>
            </div>
            <div class="row">
                <div class="mb-3 col-6">
                    <label for="centro" class="form-label">Centro:</label><br>
                    <select style="width:100%;height: 50%;"name="centro" required>
                        <option value="">Centro</option>
                        <?php foreach ($centros as $key => $value) {?>
                            <option value="<?php echo $value?>"><?php echo $value?></option>
                        <?php } ?>   
                    </select>
                </div>
                <div class="mb-3 col-6">
                    <label for="departamento" class="form-label">Departamento:</label>
                    <select style="width:100%;height: 50%;"name="departamento" required>
                        <option value="">Departamento</option>
                        <?php foreach ($departamentos as $key => $value) {?>
                            <option value="<?php echo $key?>"><?php echo $value?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-6">
                    <label for="tipo_bien" class="form-label">Tipo de Bien:</label><br>
                    <select style="width:100%;height: 50%;" name="tipo_bien" required>
                    <option value="">tipo de bien</option>
                        <?php foreach ($tipo_bienes as $key => $value) {?>
                            <option value="<?php echo $key?>"><?php echo $value?></option>
                        <?php } ?>
                    </select>
                </div>


                <div class="mb-3 col-6">
                    <label for="precio" class="form-label">Precio:</label>
                    <input type="number" name="precio" step="0.01" class="form-control"  required>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-5">
            <div class="button-container mt-5">
                <!--Botón crear -->
                <div class="wrap-login-form-btn"> 
                    <div class="login-form-bgbtn"></div>
                    <button type="submit" class="login-form-btn">Crear</button> 
                </div>
                <!-- Botón volver -->
                <div class="wrap-login-form-btn"> 
                    <div class="login-form-bgbtn"></div>
                    <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo ENT_PATH; ?>lista.php';">
                        Volver
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>