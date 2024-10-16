<?php 
include "../../menu.php";
require_once "../../config/auth.php";
include_once "../../csrf.php";
@session_start();
if (isset($_SESSION['entradas'])) {
    $entradas = $_SESSION['entradas'];
} else {
    $entradas = []; // Manejar si no hay entradas en la sesión
}
   $entrada=$_GET["entrada"];
?>
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
<h1 class="text-center">Nuevo bien</h1>
<?php include_once('../error.php');?>

<div class="container">
<form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bienes&opcion=crear" method="POST" class="d-flex flex-column align-items-center">
<input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
        <div class="mb-3 w-50">
            <label for="descripcion" class="form-label">Descripción:</label>
            <input type="text" name="descripcion" class="form-control"  required>
        </div>
        <div class="mb-3 w-50">
            <label for="precio" class="form-label">Precio:</label>
            <input type="number" name="precio" step="0.01" class="form-control"  required>
        </div>
        <div class="mb-3 w-50">
        <label for="centro" class="form-label">Centro:</label>
        <select name="centro" required>
            <option value="1" >Pontevedra</option>
            <option value="2" >Madrid</option>
        </select>
        </div>
        <div class="mb-3 w-50">
            <label for="departamento" class="form-label">Departamento:</label>
            <select name="departamento" required>
                <option value="00">SIGA</option>
                <option value="01">Técnico</option>
                <option value="02">CAU</option>
                <option value="03">Jurídico</option>
                <option value="04">Administración</option>
                <option value="05">Comercial</option>
                <option value="06">Marketing y comunicación</option>
                <option value="07">Patentes y Marcas</option>
                <option value="08">Dirección</option>
                <option value="09">Consejeros</option>
                <option value="10">Almacén</option>
                <option value="11">Sala Juntas</option>
                <option value="12">Sala Reuniones</option>
            </select>
        </div>
        <div class="mb-3 w-50">
            <label for="tipo_bien" class="form-label">Tipo de Bien:</label>
            <select name="tipo_bien" required>
                <option value="AL">alfombra</option>
                <option value="AR">armario</option>    
                <option value="BA">bandeja</option>
                <option value="BU">buck</option>
                <option value="CI">cizalla</option>
                <option value="DE">destructora</option>
                <option value="AI">equipo aire</option>
                <option value="EC">escalera</option>
                <option value="ES">estantería</option>
                <option value="EX">extintor</option>
                <option value="FU">funda</option>
                <option value="IM">impresora</option>
                <option value="IP">ipad</option>
                <option value="LA">lámpara</option>
                <option value="ME">mesa</option>
                <option value="MO">monitor</option>
                <option value="PE">perchero</option>
                <option value="VC">policom</option>
                <option value="RE">reposapiés</option>
                <option value="OR">ordenador</option>
                <option value="RU">roll-up</option>
                <option value="SC">scanner</option>
                <option value="SI">silla</option>
                <option value="SP">soporte pc</option>
                <option value="PI">pizarra</option>
                <option value="PU">puntero</option>
                <option value="TV">televisión</option>
                <option value="WC">webcam</option>
            </select>
        </div>
        <div class="mb-3 w-50">
            <label for="codigo" class="form-label">Codigo:</label>
            <input type="text" name="codigo" class="form-control"  required>
        </div>
        <div class="mb-3 w-50">
        <label for="entrada" class="form-label">Entrada:</label>
        <?php foreach ($entradas as $entrada): ?>
            <?php if ($entrada['id'] == $_GET['entrada']): ?>
                <input type="text" name="entrada" id="entrada" class="form-control" value="<?php echo $entrada['descripcion']; ?>" readonly>
                <input type="text" name="entrada_bien_id" id="entrada_bien_id" class="form-control" value="<?php echo $entrada['id']; ?>" hidden>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    <div class="d-flex justify-content-center mt-5">
        <div class="button-container mt-5">
            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn">Crear</button> 
            </div>

            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo ENT_PATH; ?>lista.php';">
                    Volver
                </button>
            </div>
        </div>
    </div>
</form>
</div>
</div>