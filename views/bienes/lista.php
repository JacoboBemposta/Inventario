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
    <?php 
    if(isset($_SESSION["erroretiquta"])){ 
    ?>
        <div class="container d-flex flex-column align-items-center justify-content-center bg-danger align-test-center" style="max-width:50vw ;height: 10vh;" >
            <h1><?php echo $_SESSION["erroretiquta"]?></h1>
        </div>    
        <?php
    }unset($_SESSION["erroretiquta"]);
    ?>
    <!-- Formulario para la generación de etiquetas -->
    <form action="<?php echo ROOT_PATH; ?>controllers/indexController.php?ctrl=bienes&opcion=generarEtiquetas"
    method="post" id="form-generar-etiquetas"
    <?php echo (!isset($_SESSION["erroretiquta"])) ? '' : 'target="_blank"'; 
    unset($_SESSION["erroretiquta"])?>>
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
                            <input type="checkbox" name="bienes[]" value="<?php echo $bien['id']; ?>" onclick="activarBlank(this);";> <!-- Checkbox para seleccionar el bien -->
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

                                //Si existe el bien le asigna un nombre para la ruta
                                if(isset($bien)) $nombre=$bien["codigo"].$bien["departamento"].$bien["tipo_bien"].$bien["fecha_alta"];
                                else {
                                    echo "datos incorrectos";die;
                                }
                                // Si no existe la ruta, la crea
                                if(!file_exists(TEMP_PATH)) mkdir(TEMP_PATH);
                                
                                $filename = TEMP_PATH.$nombre.'.png';// Crea el archivo .png en la ruta indicada
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
        <div class="container d-flex flex-column align-items-center" style="height: 50vh;" >
        <div class="d-flex justify-content-center">
                <label for="posicion" class="m-5">Posicion de la etiqueta
                <input type="number" name="posicion" id="posicion" placeholder="Indique la posicion" style="margin-left: 2vw";>
                </label>
        </div>
        <!-- Botón para generar etiquetas -->
        <div class="container d-flex flex-column align-items-center" style="height: 50vh;" >
        <div class="d-flex justify-content-center">
            <div class="wrap-login-form-btn">
                <div class="login-form-bgbtn"></div>
                <button type="submit" class="login-form-btn" id="generar-pdf">Comprobar posiciones</button>
                    
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


function activarBlank(checkbox) {
    // Obtener el formulario por su ID
    var formulario = document.getElementById('form-generar-etiquetas');

    // Obtener el valor del input con id="posicion"
    var posicion = document.getElementById('posicion').value;

    // Convertir el valor de la posición en un número
    var posicionNumero = parseInt(posicion);

    // Verificar si al menos un checkbox está seleccionado
    var checkboxSeleccionado = document.querySelector('input[name="bienes[]"]:checked') !== null;

    // Verificar si el campo 'posicion' no está vacío y su valor está entre 1 y 33
    var posicionValida = posicion !== '' && posicionNumero >= 1 && posicionNumero <= 33;

    // Si ambos criterios se cumplen, agregar el atributo target="_blank"
    if (checkboxSeleccionado) {
        formulario.setAttribute('target', '_blank');
    } else {
        // Si no se cumplen, remover el atributo target
        formulario.removeAttribute('target');
    }
}



</script>
