<?php
include "../../menu.php";
require_once "../../config/auth.php";
@session_start();
if ($_SESSION["tipo_usuario"] != "ADMIN") header("Location: " . ROOT_PATH) . "inicio.php";
if (isset($_SESSION['proveedores'])) {
    $proveedores = $_SESSION['proveedores'];
} else {
    $proveedores = []; // Manejar si no hay proveedores en la sesión
}
?>
<!-- Vista de la lista de proveedores -->
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
    <!-- Título centrado -->
    <h1 class="text-center">Lista de Proveedores</h1>
    <!-- Tabla proveedores-->
    <table id="tablaproveedores" class="display" style="min-width:60vw" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: center;">Nombre</th>
                <th style="text-align: center;">Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($proveedores as $proveedor): ?>
                <tr>
                    <td style="text-align: center;"><?php echo $proveedor['nombre']; ?></td>
                    <td style="min-width: 15vw; text-align: center;">
                        <button
                            onclick="window.location.href='<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=proveedores&opcion=editar&proveedor=<?php echo $proveedor['id']; ?>>'"
                            class="editarItem">
                            <img src="<?php echo ROOT_PATH; ?>public/images/editar.webp" alt="Editar" class="iconoItem">
                        </button>
                        <button
                            onclick="confirmarEliminacion('<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=proveedores&opcion=eliminar&proveedor=<?php echo $proveedor['id']; ?>')"
                            class="editarItem">
                            <img src="<?php echo ROOT_PATH; ?>public/images/eliminar.jpg" alt="Eliminar" class="iconoItem">
                        </button>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        <div class="wrap-login-form-btn">
            <div class="login-form-bgbtn"></div>
            <form action="<?php echo PROV_PATH ?>crear.php" method="post">
                <button type="submit" class="login-form-btn">Agregar proveedor</button>
            </form>
        </div>
    </div>
</div>
</div>

<script>
    function confirmarEliminacion(url) {
        // Muestra un mensaje de confirmación
        if (confirm("¿Estás seguro de que deseas eliminar este proveedor?")) {
            // Si el usuario confirma, redirige a la URL de eliminación
            window.location.href = url;
        }
        // Si el usuario cancela, no pasa nada
    }

    new DataTable('#tablaproveedores', {
        language: {
            info: 'Mostrando página _PAGE_ de _PAGES_',
            infoEmpty: 'No hay registros disponibles',
            infoFiltered: '(filtrado de _MAX_ registros totales)',
            lengthMenu: 'Mostrar _MENU_ registros por página',
            zeroRecords: 'No se encontraron registros',
        }
    });
</script>