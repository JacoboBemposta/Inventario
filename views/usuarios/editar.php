<?php 
include "../../menu.php";
require_once "../../config/auth.php";
@session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    $usuario = []; // Manejar si no hay usuarios en la sesión
}
?>
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
<h1 class="text-center">Editar Usuario</h1>
    <?php include_once('../error.php');?>
    <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=usuarios&opcion=actualizar&usuario=<?php echo $usuario[0]['id'] ?>" method="POST">
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
            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn">Actualizar</button> 
            </div>

            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo USR_PATH; ?>lista.php';">
                    Volver
                </button>
            </div>
        </div>
    </form>

</div>

