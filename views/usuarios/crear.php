<?php 
include "../../menu.php";
@session_start();
if(isset($_SESSION["login"])){
    if($_SESSION["login"]==="Invitado"){
        header("Location: ".ROOT_PATH)."inicio.php";
    }
}
?>
<div class="container d-flex flex-column align-items-center mt-5" style="height: 50vh;" >
<h1 class="text-center">Crear nuevo usuario</h1>
    <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=usuarios&opcion=crear" method="POST" >
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
            <label for="tipo_usuario" class="form-label">Tipo de Usuario:</label>
            <select name="tipo_usuario" class="form-select" required>
                <option value="ADMIN">ADMIN</option>
                <option value="EMPLEADO">EMPLEADO</option>
            </select>
        </div>

        <div class="button-container mt-5">
            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn">Crear</button> 
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

