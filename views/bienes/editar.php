<?php 
include "../../menu.php";
require_once "../../config/auth.php";
include_once "../../csrf.php";
@session_start();
if (isset($_SESSION['bien'])) {
    $bien = $_SESSION['bien'];
} else {
    $bien = []; // Manejar si no hay proveedores en la sesión
}

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
            <input type="text" name="codigo" class="form-control"value="<?php echo $bien['codigo']; ?>" readonly>
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
                    <option value="00" <?php if ($bien['departamento'] == '00') echo 'selected'; ?>>SIGA</option>
                    <option value="01" <?php if ($bien['departamento'] == '01') echo 'selected'; ?>>Técnico</option>
                    <option value="02" <?php if ($bien['departamento'] == '02') echo 'selected'; ?>>CAU</option>
                    <option value="03" <?php if ($bien['departamento'] == '03') echo 'selected'; ?>>Jurídico</option>
                    <option value="04" <?php if ($bien['departamento'] == '04') echo 'selected'; ?>>Administración</option>
                    <option value="05" <?php if ($bien['departamento'] == '05') echo 'selected'; ?>>Comercial</option>
                    <option value="06" <?php if ($bien['departamento'] == '06') echo 'selected'; ?>>Marketing y comunicación</option>
                    <option value="07" <?php if ($bien['departamento'] == '07') echo 'selected'; ?>>Patentes y Marcas</option>
                    <option value="08" <?php if ($bien['departamento'] == '08') echo 'selected'; ?>>Dirección</option>
                    <option value="09" <?php if ($bien['departamento'] == '09') echo 'selected'; ?>>Consejeros</option>
                    <option value="10" <?php if ($bien['departamento'] == '10') echo 'selected'; ?>>Almacén</option>
                    <option value="11" <?php if ($bien['departamento'] == '11') echo 'selected'; ?>>Sala Juntas</option>
                    <option value="12" <?php if ($bien['departamento'] == '12') echo 'selected'; ?>>Sala Reuniones</option>
                </select>
        </div>        
        <div class="mb-3">
            <label for="tipo_bien">Tipo de Bien:</label>
            <select name="tipo_bien" required>
                <option value="AL" <?php if ($bien['tipo_bien'] == 'AL') echo 'selected'; ?>>alfombra</option>
                <option value="AR" <?php if ($bien['tipo_bien'] == 'AR') echo 'selected'; ?>>armario</option>
                <option value="BA" <?php if ($bien['tipo_bien'] == 'BA') echo 'selected'; ?>>bandeja</option>
                <option value="BU" <?php if ($bien['tipo_bien'] == 'BU') echo 'selected'; ?>>buck</option>
                <option value="CI" <?php if ($bien['tipo_bien'] == 'CI') echo 'selected'; ?>>cizalla</option>
                <option value="DE" <?php if ($bien['tipo_bien'] == 'DE') echo 'selected'; ?>>destructora</option>
                <option value="AI" <?php if ($bien['tipo_bien'] == 'AI') echo 'selected'; ?>>equipo aire</option>
                <option value="EC" <?php if ($bien['tipo_bien'] == 'EC') echo 'selected'; ?>>escalera</option>
                <option value="ES" <?php if ($bien['tipo_bien'] == 'ES') echo 'selected'; ?>>estantería</option>
                <option value="EX" <?php if ($bien['tipo_bien'] == 'EX') echo 'selected'; ?>>extintor</option>
                <option value="FU" <?php if ($bien['tipo_bien'] == 'FU') echo 'selected'; ?>>funda</option>
                <option value="IM" <?php if ($bien['tipo_bien'] == 'IM') echo 'selected'; ?>>impresora</option>
                <option value="IP" <?php if ($bien['tipo_bien'] == 'IP') echo 'selected'; ?>>ipad</option>
                <option value="LA" <?php if ($bien['tipo_bien'] == 'LA') echo 'selected'; ?>>lámpara</option>
                <option value="ME" <?php if ($bien['tipo_bien'] == 'ME') echo 'selected'; ?>>mesa</option>
                <option value="MO" <?php if ($bien['tipo_bien'] == 'MO') echo 'selected'; ?>>monitor</option>
                <option value="PE" <?php if ($bien['tipo_bien'] == 'PE') echo 'selected'; ?>>perchero</option>
                <option value="VC" <?php if ($bien['tipo_bien'] == 'VC') echo 'selected'; ?>>policom</option>
                <option value="RE" <?php if ($bien['tipo_bien'] == 'RE') echo 'selected'; ?>>reposapiés</option>
                <option value="OR" <?php if ($bien['tipo_bien'] == 'OR') echo 'selected'; ?>>ordenador</option>
                <option value="RU" <?php if ($bien['tipo_bien'] == 'RU') echo 'selected'; ?>>roll-up</option>
                <option value="SC" <?php if ($bien['tipo_bien'] == 'SC') echo 'selected'; ?>>scanner</option>
                <option value="SI" <?php if ($bien['tipo_bien'] == 'SI') echo 'selected'; ?>>silla</option>
                <option value="SP" <?php if ($bien['tipo_bien'] == 'SP') echo 'selected'; ?>>soporte pc</option>
                <option value="PI" <?php if ($bien['tipo_bien'] == 'PI') echo 'selected'; ?>>pizarra</option>
                <option value="PU" <?php if ($bien['tipo_bien'] == 'PU') echo 'selected'; ?>>puntero</option>
                <option value="TV" <?php if ($bien['tipo_bien'] == 'TV') echo 'selected'; ?>>televisión</option>
                <option value="WC" <?php if ($bien['tipo_bien'] == 'WC') echo 'selected'; ?>>webcam</option>
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
