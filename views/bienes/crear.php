<?php 
include "../../menu.php";
@session_start();
if(isset($_SESSION["login"])){
    if($_SESSION["login"]==="Invitado"){
        header("Location: ".ROOT_PATH)."inicio.php";
    }
}
if (isset($_SESSION['entradas'])) {
    $entrada = $_SESSION['entradas'];
} else {
    $entrada = []; // Manejar si no hay entradas en la sesión
}
?>
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
<h1 class="text-center">Nuevo bien</h1>
<div class="container">
<form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bien&opcion=crear" method="POST" class="d-flex flex-column align-items-center">
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
        <label for="codigo" class="form-label">Entrada:</label>
            <select name="entrada_bien_id" required>
                <?php foreach ($_SESSION['entradas'] as $entrada): ?>
                    <option value="<?php  echo $entrada['id']; ?>" <?php if ($entrada['id'] == $_GET['entrada']) echo 'selected'; ?>>
                        <?php echo $entrada['descripcion'] ?>
                    </option>
                <?php endforeach; ?>
            </select>    
        </div>
    <div class="d-flex justify-content-center mt-5">
            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                    <button type="imput" name="btnEntrar" class="login-form-btn">Crear</button> 
                </div> 
            </div>
        </div>
    </form>
</div>
</div>
<div class="container justify-content-center align-items-center mt-5" style="min-height: 70vh; max-width: 20vw;">
        <div class="wrap-login-form-btn">
            <div class="login-form-bgbtn"></div>
            <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo ENT_PATH; ?>lista.php';">
                Volver
            </button>
        </div>
    </div>
