<?php
include "../../menu.php";
require_once "../../config/auth.php";
include_once "../../csrf.php";
@session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    $usuario = []; // Manejar si no hay usuarios en la sesión
}
?>
<!-- Vista para editar un usuario existente -->
<div class="container d-flex flex-column align-items-center mt-5" style="min-height: 50vh;">
    <?php include_once('../error.php'); ?>
    <h1 class="text-center">Editar Usuario</h1>

    <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=usuarios&opcion=actualizar&usuario=<?php echo $usuario[0]['id'] ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $usuario[0]['nombre']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña:</label>
            <input type="password" name="contrasena" class="form-control" value="">
        </div>

        <div class="mb-3">
            <label for="tipo_usuario" class="form-label">Tipo de Usuario:</label>
            <select name="tipo_usuario" class="form-select" required>
                <option value="ADMIN" <?php if ($usuario[0]['tipo_usuario'] == 'ADMIN') echo 'selected'; ?>>ADMIN</option>
                <option value="EMPLEADO" <?php if ($usuario[0]['tipo_usuario'] == 'EMPLEADO') echo 'selected'; ?>>EMPLEADO</option>
            </select>
        </div>

        <div class="button-container mt-5">
            <!-- Botón actualizar -->
            <div class="wrap-login-form-btn">
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn">Actualizar</button>
            </div>
            <!-- Botón crear -->
            <div class="wrap-login-form-btn">
                <div class="login-form-bgbtn"></div>
                <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo USR_PATH; ?>lista.php';">
                    Volver
                </button>
            </div>
        </div>
    </form>

</div>