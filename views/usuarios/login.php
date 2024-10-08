<?php 
include "../../menu.php";
@session_start();
?>
<!-- Formulario login -->
<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 70vh;">
<form action="<?php echo ROOT_PATH ?>public/index.php?ctrl=usuarios&opcion=ver method='POST' class='w-50'">
    <div class="mb-3">
    <label for="nombre" class="form-label">Usuario:</label>
    <input type="text" name="nombre" class="form-control" placeholder="Introduce usuario" required>
    </div>
    <div class="mb-3">
    <label for="nombre" class="form-label">Password:</label>
    <input type="password" name="nombre" class="form-control" placeholder="" required>
    </div>
    <div class="d-flex justify-content-center mt-5">
            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                    <button type="submit"  class="login-form-btn">Entrar</button> 
                </div> 
            </div>
        </div>
</form>
<div class="d-flex justify-content-center">
         <a href="<?php echo PROV_PATH ?>lista.php">Volver</a>
    </div>

</div>