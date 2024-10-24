<?php
include "../../menu.php";
require_once "../../config/auth.php";
?>
<!-- Vista para subir un fichero CSV -->
<div class="container d-flex flex-column align-items-center mt-5" style="min-height: 50vh;">
    <h1 class="text-center">Subir CSV</h1>
    <div class="container">
        <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=entradas&opcion=importar" method="POST" enctype="multipart/form-data" class="d-flex flex-column align-items-center">
            <!-- input CSV -->
            <div class="mb-3 w-50">
            <input type="file" name="archivocsv" class="form-control" accept=".csv" required>
            </div>
            <!-- Botón CSV -->
            <button type="submit" class="login-form">
                <img src="<?php echo ROOT_PATH; ?>public/images/nube.png" alt="Editar" class="iconoItem">
                Subir CSV
            </button>
        </form>
    </div>
    <div class="d-flex justify-content-center mt-5">
        <!-- Botón volver -->
        <div class="wrap-login-form-btn">
            <div class="login-form-bgbtn"></div>
            <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo ENT_PATH; ?>lista.php';">
                Volver
            </button>
        </div>
    </div>