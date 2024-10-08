<?php 
@session_start();

require '../../menu.php';

// Guardar los botones seleccionados en la sesión
if (isset($_POST['botones_seleccionados'])) {
    $_SESSION['botones_seleccionados'] = $_POST['botones_seleccionados'];
}

?>

<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style=" display: grid; grid-gap: 2px;">
    <h1>Selecciona las etiquetas que vas a usar</h1>
    <!-- Formulario para recoger los slots en la impresora -->
    <form action="<?php echo ROOT_PATH; ?>controllers/indexController.php?ctrl=bienes&opcion=generarEtiquetas" method="POST">
        <input type="hidden" name="botones_seleccionados" id="botones_seleccionados" value="<?php echo isset($_SESSION['botones_seleccionados']) ? implode(',', $_SESSION['botones_seleccionados']) : ''; ?>">
        <table id="tabla-etiquetas">
            <?php 
            $i = 0;
            for ($i = 0; $i < 11; $i++) { 
                ?>
                <tr>
                    <td class="p-1">
                        <button type="button" class="btn-select" id="<?php echo $i.'1'; ?>">Selecciona</button>
                    </td>
                    <td class="p-1">
                        <button type="button" class="btn-select" id="<?php echo $i.'2'; ?>">Selecciona</button>
                    </td>
                    <td class="p-1">
                        <button type="button" class="btn-select" id="<?php echo $i.'3'; ?>">Selecciona</button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <div class="d-flex justify-content-center mt-5">
            <div class="wrap-login-form-btn"> 
                <div class="login-form-bgbtn"></div>
                    <button type="imput" name="btnEntrar" class="login-form-btn">Imprimir</button> 
                </div> 
            </div>
        </div>
    </form>
</div>

<!-- CSS -->
<style>
    table {
        border-collapse: collapse;
    }

    td {
        padding: 10px;
    }

    button.btn-select {
        padding: 10px 20px;
        border-radius: 12px;
        background-color: #007bff; /* Color original del botón */
        color: white;
        border: none;
        cursor: pointer;
        margin: 5px; /* Espacio entre botones */
        transition: background-color 0.3s ease;
    }

    button.btn-select.selected {
        background-color: green; /* Cambiar a verde cuando se selecciona */
    }
</style>

<!-- JavaScript -->
<script>
    // Obtener los botones seleccionados de la sesión
    let selectedButtons = [];

    <?php if (isset($_SESSION['botones_seleccionados'])): ?>
    selectedButtons = "<?php echo implode(',', $_SESSION['botones_seleccionados']); ?>".split(',');
    <?php endif; ?>

    document.addEventListener('DOMContentLoaded', function () {
        // Seleccionar todos los botones
        const buttons = document.querySelectorAll('.btn-select');

        // Marcar los botones que ya fueron seleccionados en sesiones anteriores
        buttons.forEach(button => {
            if (selectedButtons.includes(button.id)) {
                button.classList.add('selected');
            }

            // Evento click para cada botón
            button.addEventListener('click', function () {
                // Si el botón ya está seleccionado, lo deseleccionamos
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    selectedButtons = selectedButtons.filter(id => id !== this.id); // Eliminar del array
                } else {
                    this.classList.add('selected');
                    selectedButtons.push(this.id); // Agregar al array
                }

                // Actualizar el campo oculto con los botones seleccionados
                document.getElementById('botones_seleccionados').value = selectedButtons.join(',');
            });
        });
    });
</script>

