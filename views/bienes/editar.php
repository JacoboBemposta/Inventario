<?php 
include "../../menu.php";
require_once "../../config/auth.php";
require_once "../../config/config.php";
include_once "../../csrf.php";
@session_start();
if (isset($_SESSION['bien'])) {
    $bien = $_SESSION['bien'];
} else {
    $bien = []; // Manejar si no hay proveedores en la sesión
}
    //Dar formato al codigo 
    $contador = intval($bien["id"]);
    if ($contador > 9999) $contador = $contador - 9999;

    $codigo = str_pad($contador, 4, '0', STR_PAD_LEFT);

?>
<!-- Formulario para editar un bien existente -->
<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 70vh;">
    <?php include_once('../error.php');?>
    <h1 class="text-center mb-4">Editar bien</h1>
    

    <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bienes&opcion=actualizar&bien=<?php echo $bien['id'] ?>" method="POST" class="w-50" >
        <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
        <div class="mb-3">
          <label for="entrada_id">Entrada : </label>
          <!-- Recorre todas las entradas y cubre el valor de los campos descripción e id de la entrada del bien seleccionado -->
            <?php foreach ($_SESSION["entradas"] as $entrada): ?>
                <?php if ($entrada['id'] == $bien["entrada_bien_id"]): ?>
                    <input type="text" name="entrada" id="entrada" class="form-control" value="<?php echo $entrada['descripcion']; ?>" readonly>
                    <input type="text" name="entrada_bien_id" id="entrada_bien_id" class="form-control" value="<?php echo $entrada['id']; ?>" hidden>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="mb-3">
            <label for="fecha_alta">Fecha de alta:</label>
            <input type="text" name="fecha_alta" class="form-control"value="<?php 
            $fecha = $bien['fecha_alta'];
            $nueva_fecha = date("d-m-Y",strtotime($fecha));
            echo $nueva_fecha; 
            ?>" readonly>
        </div>       
        <div class="mb-3">
            <label for="descripcion">Código:</label>
            <input type="text" name="codigo" class="form-control" value="<?php echo $bien['centro'] . ' ' . $bien['departamento'] . ' ' .$bien['tipo_bien'].' '. $codigo; ?>" readonly>

        </div>    
        <div class="mb-3">
            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" class="form-control"value="<?php echo $bien['descripcion']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="centro">Centro:</label>
            <select name="centro" required>
                <option value="1" <?php if ($bien['centro'] == 1) echo 'selected'; ?>>Pontevedra</option>
                <option value="2" <?php if ($bien['centro'] == 2) echo 'selected'; ?>>Madrid</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="departamento">Departamento:</label>
                <select name="departamento" required>
                    <option value="">Selecciona un departamento</option>
                        <?php foreach ($departamentos as $key => $value) {?>
                            <option value="<?php echo $key?>"<?php if ($bien['departamento'] == $key) echo 'selected'; ?>><?php echo $value?></option>
                        <?php } ?>
                </select>
        </div>        
        <div class="mb-3">
            <label for="tipo_bien">Tipo de Bien:</label>
            <select name="tipo_bien" required>
                <option value="">Selecciona tipo</option>
                    <?php foreach ($tipo_bienes as $key => $value) {?>
                        <option value="<?php echo $key?>"<?php if ($bien['tipo_bien'] == $key) echo 'selected'; ?>><?php echo $value?></option>
                    <?php } ?>
            </select>
        </div>
        <div class="mb-3">
        <label for="precio">Precio:</label>
        <input type="number" name="precio" step="0.01" class="form-control" value="<?php echo $bien['precio']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="tipo_bien">Causa de baja: </label>
            <select name="causa_baja" >
                <option value="NULL" <?php if ($bien['causa_baja'] == NULL) echo 'selected'; ?>></option>
                <option value="Obsolescencia" <?php if ($bien['causa_baja'] == "Obsolescencia") echo 'selected'; ?>>Obsolescencia</option>
                <option value="Deterioro" <?php if ($bien['causa_baja'] == "Deterioro") echo 'selected'; ?>>Deterioro</option>
                <option value="Venta"<?php if ($bien['causa_baja'] == "Venta") echo 'selected'; ?>>Venta</option>
                <option value="Averiado"<?php if ($bien['causa_baja'] == "Averiado") echo 'selected'; ?>>Averiado</option>
                <option value="Extravío"<?php if ($bien['causa_baja'] == "Extravío") echo 'selected'; ?>>Extravío</option>
                <option value="Otras causas"<?php if ($bien['causa_baja'] == "Otras causas") echo 'selected'; ?>>Otras causas</option>
            </select>
        </div>

        
        <div class="button-container mt-5">
                <!-- Boton actualizar -->
                <div class="wrap-login-form-btn"> 
                    <div class="login-form-bgbtn"></div>
                    <button type="submit" class="login-form-btn">Actualizar</button> 
                </div>

                <div class="wrap-login-form-btn"> 
                    <!-- Boton volver -->
                    <div class="login-form-bgbtn"></div>
                    <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo ENT_PATH; ?>lista.php';">
                        Volver
                    </button>
                </div>
            </div>
    </form>
</div>
