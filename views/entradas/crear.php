<?php 
include "../../menu.php";
@session_start();

if(isset($_SESSION["login"])){
    if($_SESSION["login"]==="Invitado"){
        header("Location: ".ROOT_PATH)."inicio.php";
    }
}
?>
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
<h1 class="text-center">Crear nueva entrada</h1>

<form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=entradas&opcion=crear" method="POST">
    <div class="mb-3">
        <label for="descripcion"class="form-label">Descripción:</label>
        <input type="text" name="descripcion" class="form-control" required>
    </div>    
    <div class="mb-3">
        <label for="numero_factura"class="form-label">Número de Factura:</label>
        <input type="text" name="numero_factura" class="form-control" required>
    </div>    
    <div class="mb-3">
        <label for="nombre_proveedor"class="form-label">Nombre proveedor:</label>
        <select name="proveedor_id" name="nombre_proveedor" required>
        <?php foreach ($_SESSION['proveedores'] as $proveedor): ?>
            <option value="<?php  echo $proveedor['id']; ?>" >
                <?php echo $proveedor['nombre'] ?>
            </option>
        <?php endforeach; ?>
    </select>
    </div>    
    <div class="row">
            <div class="mb-3 col-6">
                <label for="fecha_compra"class="form-label">Fecha de Compra:</label>
                <input type="date" name="fecha_compra" id="fecha_compra" oninput="actualizafecha()" class="form-control" required>
            </div>
            <div class="mb-3 col-6">
                <label for="fecha_inicio_amortizacion"class="form-label">Fecha de Inicio de Amortización:</label>
                <input type="date" name="fecha_inicio_amortizacion" id="fecha_inicio_amortizacion"  class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-6">
                <label for="porcentaje_amortizacion"class="form-label">Porcentaje de Amortización:</label>
                <input type="number" name="porcentaje_amortizacion"class="form-control" step="0.01" required>
            </div>
            <div class="mb-3 col-6">
                <label for="precio"class="form-label">Precio:</label>
                <input type="number" name="precio" step="0.01" class="form-control" required>
            </div>
        </div>
    <div class="mb-3">
        <label for="cuenta_contable"class="form-label">Número de Cuenta Contable:</label>
        <input type="text" name="cuenta_contable" class="form-control" required>
    </div>   
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
</form>

<script>
        //Se actualiza en fecha_inicio_amortizacion el valor de la fecha_compra
        function actualizafecha() {
			let fecha = document.getElementById("fecha_compra").value;
			
			document.getElementById("fecha_inicio_amortizacion").value = fecha;
		}
    </script>