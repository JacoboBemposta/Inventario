<?php
include "../../menu.php";
require_once "../../config/auth.php";
require_once '../../libs/phpqrcode/qrlib.php';
include_once "../../csrf.php";
@session_start();


$bienes = isset($_SESSION['bienestotal']) ? $_SESSION['bienestotal'] : [];
unset($_SESSION["bienestotal"]);
?>

<!-- Vista de la página -->
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
    <h1 class="text-center">Lista de Bienes</h1>
    <!-- Formulario para la generación de etiquetas -->
    <form action="" method="post" id="form-generar-etiquetas">
        <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
        <table class="display" id="bienes-table" style="width: 100%;" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th style="text-align: center; padding: 10px;min-width:10vw;">
                        <input type="checkbox" id="seleccionarTodos"> Seleccionar todos<!-- Checkbox para seleccionar todos -->
                    </th>
                    <th style="text-align: center; padding: 10px;">Descripción</th>
                    <th style="text-align: center; padding: 10px;">Precio</th>
                    <th style="text-align: center; padding: 10px;">Centro</th>
                    <th style="text-align: center; padding: 10px;">Departamento</th>
                    <th style="text-align: center; padding: 10px;">Tipo bien</th>
                    <th style="text-align: center; padding: 10px;">Fecha alta</th>
                    <th style="text-align: center; padding: 10px;">QR</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bienes as $bien) { ?>
                    <tr>
                        <td style="text-align: center; padding: 10px;">
                            <input type="checkbox" name="bienes[]" value="<?php echo $bien['id']; ?>"> <!-- Checkbox para seleccionar el bien -->
                        </td>
                        <td style="text-align: center; padding: 10px;">
                            <div style="max-width: 20vw; overflow-x: auto; white-space: nowrap; text-align: center">
                                <?php echo $bien['descripcion']; ?>        
                            </div>
                        </td>
                        <td style="text-align: center; padding: 10px;"><?php echo $bien['precio']; ?></td>
                        <td style="text-align: center; padding: 10px;"><?php echo $bien['centro']; ?></td>
                        <td style="text-align: center; padding: 10px;"><?php echo $bien['departamento']; ?></td>
                        <td style="text-align: center; padding: 10px;"><?php echo $bien["tipo_bien"]; ?></td>
                        <td style="text-align: center; padding: 10px;"><?php echo $bien["fecha_alta"]; ?></td>
                        <td style="text-align: center; padding: 10px;" class="d-flex align-items-center">
                            <?php 
                                // Generar códigos QR //
                                $contenido = $bien["descripcion"]."\n"; // Contenido del QR
                                
                                // Si no existe la ruta, la crea
                                if(!file_exists(TEMP_PATH)) mkdir(TEMP_PATH);
                                
                                $filename = TEMP_PATH.html_entity_decode($bien["codigo"]).'.png'; // Crea el archivo .png en la ruta indicada
                                $tamanho = 1; // tamaño de la imagen
                                $level = 'H'; // tipo de precision
                                $framesize = 3; // marco del qr en blanco
                                QRcode::png($contenido, $filename, $level, $tamanho, $framesize);

                                echo '<img src="'.ROOT_PATH.'public/temp/'.html_entity_decode($bien["codigo"]).'.png">'; 
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="container d-flex flex-column align-items-center">
            <label for="posicion" class="m-5">Posición de la etiqueta
            <input type="number" name="posicion" id="posicion" placeholder="Indique la posición" style="margin-left: 2vw";>
            </label>
            <!-- Botones para generar etiquetas -->
            <div class="d-flex justify-content-center">
                <div class="wrap-login-form-btn">
                    <div class="login-form-bgbtn"></div>
                    <button type="submit" class="login-form-btn" id="generar-imprimir">Imprimir</button>
                </div>
                <div class="wrap-login-form-btn">
                    <div class="login-form-bgbtn"></div>
                    <button type="submit" class="login-form-btn" id="exportar-pdf">Exportar PDF</button>
                </div>            
            </div>
        </div>
    </form>
</div>

<!-- Script para inicializar DataTables -->
<script>
    $(document).ready(function() {
        var table = $('#bienes-table').DataTable({
            pageLength: 5, // Limitar a 5 registros por página
            language: {
                info: 'Mostrando página _PAGE_ de _PAGES_',
                infoEmpty: 'No hay registros disponibles',
                infoFiltered: '(filtrado de _MAX_ registros totales)',
                lengthMenu: 'Mostrar _MENU_ registros por página',
                zeroRecords: 'No se encontraron registros',
            },
        });

        // Seleccionar/Deseleccionar todos los checkboxes
        $('#seleccionarTodos').on('click', function(){
            var rows = table.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        // Validar formulario al momento de enviarlo
        $('#form-generar-etiquetas').on('submit', function(event) {
            // Obtener el valor del campo de posición
            var posicion = $('#posicion').val();
            var posicionNumero = parseInt(posicion);

            // Verificar si al menos un checkbox está seleccionado
            var checkboxSeleccionado = $('input[name="bienes[]"]:checked').length > 0;

            // Validar el botón "Imprimir"
            if (event.originalEvent.submitter.id === "generar-imprimir") {
                if (!checkboxSeleccionado) {
                    alert('Debes seleccionar al menos un bien.');
                    event.preventDefault(); // Detiene el envío del formulario
                    return;
                }

                if (posicion === '' || posicionNumero < 1 || posicionNumero > 33) {
                    alert('Indica la posición en la que se va a imprimir la primera etiqueta (1-33).');
                    event.preventDefault(); // Detiene el envío del formulario
                    return;
                }

                $(this).attr('action', '<?php echo ROOT_PATH; ?>controllers/indexController.php?ctrl=bienes&opcion=generarEtiquetas');
                $(this).attr('target', '_blank'); // Abre en nueva pestaña
                $(this).submit(); // Enviar el formulario
            }

            // Para el botón "Exportar PDF"
            if (event.originalEvent.submitter.id === "exportar-pdf") {
                console.log("hola");
                if (!checkboxSeleccionado) {
                    alert('Debes seleccionar al menos un bien para exportar.');
                    event.preventDefault(); // Detiene el envío del formulario
                    return;
                }

                // Cambia la acción y envía el formulario
                $(this).attr('action', '<?php echo ROOT_PATH; ?>controllers/indexController.php?ctrl=bienes&opcion=generarPDF');
                $(this).attr('target', '_blank'); // Abre en nueva pestaña
                $(this).submit(); // Enviar el formulario
            }
        });
    });
</script>
