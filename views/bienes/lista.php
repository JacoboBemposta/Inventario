<?php
include "../../menu.php";
require QR_PATH.'qrlib.php';
@session_start();

if (!isset($_SESSION["login"]) || ($_SESSION["login"] == "Invitado")) {
    header("Location: " . ROOT_PATH . "inicio.php");
}
$bienes = isset($_SESSION['bienes']) ? $_SESSION['bienes'] : [];
?>

<!-- Vista de la página -->
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
    <h1 class="text-center">Lista de Bienes</h1>

    <!-- Formulario para la generación de etiquetas -->
    <form action="<?php echo ROOT_PATH; ?>controllers/indexController.php?ctrl=bienes&opcion=generarEtiquetas" method="post" id="form-generar-etiquetas"target="_blank">
        <table class="display" id="bienes-table" style="width: 100%;" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th style="text-align: center; padding: 10px;">Seleccionar</th> <!-- Nueva columna para checkbox -->
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
                                if(isset($bien)) $nombre=$bien["codigo"].$bien["departamento"].$bien["tipo_bien"].$bien["fecha_alta"];
                                else {
                                    echo "no nombre";die;
                                }
                                if(!file_exists(TEMP_PATH)) mkdir(TEMP_PATH);

                                $filename = TEMP_PATH.$nombre.'.png';
                                //Echo $filename;die;
                                $tamanho=2; // tamaño de la imagen
                                $level='H'; // tipo de precision (baja l, media M, alta Q ,maxima H)
                                $framesize=3; //marco del qr en blanco
                                $contenido=$nombre;

                                QRcode::png($contenido,$filename,$level,$tamanho,$framesize);

                                echo '<img src="'.ROOT_PATH.'public/temp/'.$nombre.'.png">'; 
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>


        <!-- Botón para generar etiquetas -->
        <div class="container d-flex flex-column align-items-center" style="height: 50vh;" >
        <div class="d-flex justify-content-center">
            <div class="wrap-login-form-btn">
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn" id="generar-pdf">Generar etiquetas</button>
                    
            </div>
        </div>
        </div>
    </form>
</div>


<!-- Script para inicializar DataTables -->
<script>
$(document).ready(function() {
    $('#bienes-table').DataTable({
        language: {
            info: 'Mostrando página _PAGE_ de _PAGES_',
            infoEmpty: 'No hay registros disponibles',
            infoFiltered: '(filtrado de _MAX_ registros totales)',
            lengthMenu: 'Mostrar _MENU_ registros por página',
            zeroRecords: 'No se encontraron registros',
        }
    });
});



</script>
