<?php
include "../../menu.php";
require_once "../../config/auth.php";
include_once "../../csrf.php";
@session_start();

if (isset($_SESSION['entrada'])) {
    $entrada = $_SESSION['entrada'];
} else {
    $entrada = []; // Manejar si no hay proveedores en la sesión
}

?>
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
    <?php include_once('../error.php'); ?>
    <h1 class="text-center mb-4">Editar Entrada</h1>
    <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=entradas&opcion=actualizar&entrada=<?php echo $entrada['id'] ?>" method="POST" style="width: 45%;">
        <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <input type="text" name="descripcion" class="form-control" value="<?php echo $entrada['descripcion']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="numero_factura" class="form-label">Número de Factura:</label>
            <input type="text" name="numero_factura" class="form-control" value="<?php echo $entrada['numero_factura']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="proveedor_id" class="form-label">Proveedor:</label>
            <select name="proveedor_id" required>
                <?php foreach ($_SESSION['proveedores'] as $proveedor): ?>
                    <option value="<?php echo $proveedor['id']; ?>" <?php if ($proveedor['id'] == $entrada['proveedor_id']) echo 'selected'; ?>>
                        <?php echo $proveedor['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row">
            <div class="mb-3 col-6">
                <label for="fecha_compra" class="form-label">Fecha de Compra:</label>
                <input type="date" name="fecha_compra" class="form-control" value="<?php echo $entrada['fecha_compra']; ?>" required>
            </div>
            <div class="mb-3 col-6">
                <label for="fecha_inicio_amortizacion" class="form-label">Fecha de Inicio de Amortización:</label>
                <input type="date" name="fecha_inicio_amortizacion" class="form-control" value="<?php echo $entrada['fecha_inicio_amortizacion']; ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-6">
                <label for="porcentaje_amortizacion" class="form-label">Porcentaje de Amortización:</label>
                <input type="number" name="porcentaje_amortizacion" class="form-control" step="0.01" value="<?php echo $entrada['porcentaje_amortizacion']; ?>" required>
            </div>
            <div class="mb-3 col-6">
                <label for="precio" class="form-label">Precio:</label>
                <input type="number" name="precio" step="0.01" class="form-control" value="<?php echo $entrada['precio']; ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="cuenta_contable" class="form-label">Número de Cuenta Contable:</label>
            <input type="text" name="cuenta_contable" class="form-control" value="<?php echo $entrada['cuenta_contable']; ?>" required>
        </div>

        <div class="button-container mt-5">
            <!-- Botón actualizar -->
            <div class="wrap-login-form-btn">
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn">Actualizar</button>
            </div>
            <!-- Botón volver -->
            <div class="wrap-login-form-btn">
                <div class="login-form-bgbtn"></div>
                <button type="button" class="login-form-btn" onclick="window.location.href='<?php echo ENT_PATH; ?>lista.php';">
                    Volver
                </button>
            </div>
        </div>
    </form>
</div>