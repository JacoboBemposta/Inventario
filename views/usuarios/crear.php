<?php
include "../../menu.php";
require_once "../../config/auth.php";
include_once "../../csrf.php";
@session_start();

?>
<!-- Formulario para crear un nuevo usuario -->
<div class="container d-flex flex-column align-items-center mt-5" style="height: 50vh;">
    <?php include_once('../error.php'); ?>
    <h1 class="text-center">Crear nuevo usuario</h1>

    <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=usuarios&opcion=crear" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario:</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña:</label>
            <input type="password" name="contrasena" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tipo_usuario" class="form-label">Tipo de Usuario:</label><br>
            <select style="width:100%;height:50%;" name="tipo_usuario" class="form-select" required>
                <option value="ADMIN">ADMIN</option>
                <option value="EMPLEADO">EMPLEADO</option>
            </select>
        </div>

        <div class="button-container mt-5">
            <!-- Botón crear -->
            <div class="wrap-login-form-btn">
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn">Crear</button>
            </div>
            <!-- Botón volver -->
            <div class="wrap-login-form-btn">
                <div class="login-form-bgbtn"></div>
                <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo USR_PATH; ?>lista.php';">
                    Volver
                </button>
            </div>
        </div>
    </form>
</div>