<?php
include "../../menu.php";
require "../../config/auth.php";

if ($_SESSION["tipo_usuario"] != "ADMIN") header("Location: " . ROOT_PATH) . "inicio.php";
@session_start();

if (isset($_SESSION['usuarios'])) {
    $usuarios = $_SESSION['usuarios'];
} else {
    $usuarios = []; // Manejar si no hay usuarios en la sesión
}

?>
<!-- Vista de la lista de usuarios -->
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
    <h1 class="text-center">Lista de Usuarios</h1>
    <table id="tablausuarios" class="display" style="min-width:60vw" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: center;">Nombre</th>
                <th style="text-align: center;">Usuario</th>
                <th style="text-align: center;">Tipo de Usuario</th>
                <th style="text-align: center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td style="text-align: center;"><?php echo $usuario['nombre']; ?></td>
                    <td style="text-align: center;"><?php echo $usuario['usuario']; ?></td>
                    <td style="text-align: center;"><?php echo $usuario['tipo_usuario']; ?></td>
                    <td style="min-width: 15vw;text-align: center;" ;>
                        <!-- Botón editar-->
                        <button
                            onclick="window.location.href='<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=usuarios&opcion=editar&usuario=<?php echo $usuario['id']; ?>'"
                            class="editarItem">
                            <img src="<?php echo ROOT_PATH; ?>public/images/editar.webp" alt="Editar" class="iconoItem">
                        </button>
                        <!-- Botón eliminar -->
                        <button
                            onclick="confirmarEliminacion('<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=usuarios&opcion=eliminar&usuario=<?php echo $usuario['id']; ?>')"
                            class="editarItem">
                            <img src="<?php echo ROOT_PATH; ?>public/images/eliminar.jpg" alt="Eliminar" class="iconoItem">
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Botón agregar -->
    <div class="d-flex justify-content-center">
        <div class="wrap-login-form-btn">
            <div class="login-form-bgbtn"></div>
            <form action="<?php echo USR_PATH ?>crear.php" method="post">
                <button type="submit" class="login-form-btn">Agregar usuarios</button>
            </form>
        </div>
    </div>
</div>





<script>
    function confirmarEliminacion(url) {
        // Muestra un mensaje de confirmación
        if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
            // Si el usuario confirma, redirige a la URL de eliminación
            window.location.href = url;
        }
        // Si el usuario cancela, no pasa nada
    }

    new DataTable('#tablausuarios', {
        language: {
            info: 'Mostrando página _PAGE_ de _PAGES_',
            infoEmpty: 'No hay registros disponibles',
            infoFiltered: '(filtrado de _MAX_ registros totales)',
            lengthMenu: 'Mostrar _MENU_ registros por página',
            zeroRecords: 'No se encontraron registros',
        }
    });
</script>