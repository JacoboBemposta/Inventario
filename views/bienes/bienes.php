<?php 
include "../../menu.php";
require_once "../../config/auth.php";
@session_start();

if (isset($_SESSION['bienes'])) {
    $entradas = $_SESSION['bienes'];
} else {
    $entradas = []; // Manejar si no hay proveedores en la sesión
}
var_dump($bienes); die();
?>


<!-- Vista de la página-->
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
    <!-- Título centrado -->
    <h1 class="text-center">Lista de Bienes</h1>
    <!-- Tabla proveedores-->

    <table class="display" id="entradas-table" style="width:60vw" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: center;">Cuenta facturación</th>
                <th style="text-align: center;">Descripción</th>
                <th style="text-align: center;">Fecha</th>
                <th style="text-align: center;">Codigo</th>
                <th style="text-align: center;">Estado</th>
                <th style="text-align: center;">Selección</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entradas as $entrada){ ?>
            <tr>
            <td style="text-align: center;"><?php echo $entrada['cuenta_contable']; ?></td>
                <td style="font-weight: bold; color: #2c3e50;text-align: center;"><?php echo $entrada['descripcion']; ?></td>
                <td style="text-align: center;"><?php echo $entrada['fecha_compra']; ?></td>
                <td style="text-align: center;"><?php echo $entrada['precio']; ?></td>
                <td style="text-align: center;"><?php echo sizeof($_SESSION['bienesPorEntrada'][$entrada["id"]]);?></td>
                <td style="text-align: center;" class="d-flex align-items-center">
                <!-- Botón editar entrada-->
                <button 
                    onclick="window.location.href='<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=entradas&opcion=editar&entrada=<?php echo $entrada['id']; ?>'">
                    <img src="<?php echo ROOT_PATH; ?>public/images/editar.webp" alt="Editar" class="iconoItem">
                </button>
                <!-- Botón eliminar bien -->
                <button onclick="if(confirm('¿Estás seguro de eliminar esta entrada y sus bienes asociados?')) { window.location.href='<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=entradas&opcion=eliminar&entrada=<?php echo $entrada['id']; ?>'; }">
                    <img src="<?php echo ROOT_PATH; ?>public/images/eliminar.jpg" alt="Eliminar" class="iconoItem">
                </button>
                    <!-- Botón  desplegar/ocultar la lista de bienes -->
                <button onclick="showBienesModal(<?php echo $entrada['id']; ?>)">
                        <img src="<?php echo ROOT_PATH; ?>public/images/play.jpg" alt="Ver Bienes" class="iconoItem">
                    </button>
                </td>
            </tr>
            
            <?php } ?> 
        </tbody>
    </table>

    <!-- Botón Agregar entrada -->
    <div class="d-flex justify-content-center">
        <div class="wrap-login-form-btn"> 
            <div class="login-form-bgbtn"></div>
              <form action="<?php echo ENT_PATH ?>crear.php" method="post">
                  <button type="submit" class="login-form-btn">Crear nueva entrada</button> 
              </form>
        </div> 
    </div>

    <!-- Botón subir CSV -->
    <div class="d-flex justify-content-center mt-5 ">
  
              <div class="login-form d-flex justify-content-center mt-5">
                <form action="<?php echo ENT_PATH ?>importar.php" method="post">
                    <button type="imput" class="login-form">
                      <img src="<?php echo ROOT_PATH; ?>public/images/subircsv.jpg" alt="Editar" class="iconoItem">
                      Subir CSV
                    </button> 
                </form>
                <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=entradas&opcion=exportar" method="post">
                    <button type="imput" class="login-form">
                      <img src="<?php echo ROOT_PATH; ?>public/images/exportar.jpg" alt="Editar" class="iconoItem">
                      Exportar PDF
                    </button> 
                </form>
              </div>

    </div>    
</div>



