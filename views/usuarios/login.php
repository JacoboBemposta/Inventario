<?php 
include "../../menu.php";
include_once RAIZ_PATH . 'csrf.php';
@session_start();

?>
<script src="<?php echo ROOT_PATH . 'csrf.js'; ?>"></script> 

<!-- Formulario login -->
<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 70vh;">
<form action="<?php echo ROOT_PATH ?>public/index.php?ctrl=usuarios&opcion=login method='POST' class='w-50'">
    <!-- <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> -->
    <div class="mb-3">
    <label for="nombre" class="form-label">Usuario:</label>
    <input type="text" name="nombre" class="form-control" placeholder="Introduce usuario" required>
    </div>
    <div class="mb-3">
    <label for="password" class="form-label">Password:</label>
    <input type="password" name="password" class="form-control" placeholder="" required>
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