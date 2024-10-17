<?php
include "../../menu.php";
require_once "../../config/auth.php";
require_once '../../libs/phpqrcode/qrlib.php';
include_once "../../csrf.php";
@session_start();


$bienes = isset($_SESSION['bienestotal']) ? $_SESSION['bienestotal'] : []; //Manejar si hay bienes en la sesion
unset($_SESSION["bienestotal"]);

?>
<link rel="stylesheet" href="estilos.css">
<div class="container d-flex flex-column align-items-center" style="padding: 2px; border-radius: 10px;">
    <!-- Fila del formulario -->
    <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bienes&opcion=buscar" method="POST" class="d-flex flex-column p-2 rounded border border-8 border border-black" style="max-width: 100vw; color: white;">
    <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
        <div class="row g-3">
            <!-- Campo de búsqueda por Centro -->
            <div class="col-3">
                <label for="search-centro">Centro:</label>
                <select id="search-centro" name="search-centro" class="form-control">
                    <option value="">Selecciona un centro</option>
                    <option value="1">Pontevedra</option>
                    <option value="2">Madrid</option>
                </select>
            </div>

            <!-- Campo de búsqueda por Departamento -->
            <div class="col-3">
                <label for="search-departamento">Departamento:</label>
                <select id="search-departamento" name="search-departamento" class="form-control">
                    <option value="">Selecciona un departamento</option>
                    <option value="SIGA">SIGA</option>
                    <option value="Técnico">Técnico</option>
                    <option value="CAU">CAU</option>
                    <option value="Jurídico">Jurídico</option>
                    <option value="Administración">Administración</option>
                    <option value="Comercial">Comercial</option>
                    <option value="Marketing y comunicación">Marketing y comunicación</option>
                    <option value="Patentes y Marcas">Patentes y Marcas</option>
                    <option value="Dirección">Dirección</option>
                    <option value="Consejeros">Consejeros</option>
                    <option value="Almacén">Almacén</option>
                    <option value="Sala Juntas">Sala Juntas</option>
                    <option value="Sala Reuniones">Sala Reuniones</option>
                </select>
            </div>

            <!-- Campo de búsqueda por Tipo de bien -->
            <div class="col-3">
                <label for="search-tipo-bien">Tipo de bien:</label>
                <select id="search-tipo-bien" name="search-tipo-bien" class="form-control">
                    <option value="">Selecciona un tipo de bien</option>
                    <option value="Alfombra">Alfombra</option>
                    <option value="Armario">Armario</option>    
                    <option value="Bandeja">Bandeja</option>
                    <option value="Buck">Buck</option>
                    <option value="Cizalla">Cizalla</option>
                    <option value="Destructora">Destructora</option>
                    <option value="Equipo aire">Equipo aire</option>
                    <option value="Escalera">escalera</option>
                    <option value="Estantería">Estantería</option>
                    <option value="Funda">Funda</option>
                    <option value="Impresora">Impresora</option>
                    <option value="Ipad">Ipad</option>
                    <option value="Lámpara">Lámpara</option>
                    <option value="Mesa">Mesa</option>
                    <option value="Monitor">Monitor</option>
                    <option value="Perchero">Perchero</option>
                    <option value="Policom">Policom</option>
                    <option value="Reposapiés">Reposapiés</option>
                    <option value="Ordenador">Ordenador</option>
                    <option value="Roll-up">Roll-up</option>
                    <option value="Scanner">Scanner</option>
                    <option value="Silla">Silla</option>
                    <option value="Soporte pc">Soporte pc</option>
                    <option value="Pizarra">Pizarra</option>
                    <option value="Puntero">Puntero</option>
                    <option value="Televisión">Televisión</option>
                    <option value="Webcam">Wbbcam</option>
                </select>
            </div>

            <!-- Campo de búsqueda por Estado -->
            <div class="col-3">
                <label for="search-estado">Estado:</label>
                <select id="search-estado" name="search-estado" class="form-control">
                    <option value="">Selecciona un estado</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>

            <!-- Campo de rango de fechas -->
            <div class="col-6">
                <label for="search-fechas">Rango de fechas:</label>
                <div class="d-flex">
                    <input type="date" id="search-fecha-inicio" name="search-fecha-inicio" class="form-control mr-2" placeholder="Fecha inicio" style="flex: 1;">
                    <input type="date" id="search-fecha-fin" name="search-fecha-fin" class="form-control" placeholder="Fecha fin" style="flex: 1;">
                </div>
            </div>

            <!-- Campo de búsqueda por Descripción -->
            <div class="col-3">
                <label for="search-descripcion">Descripción:</label>
                <input type="text" id="search-descripcion" name="search-descripcion" class="form-control" placeholder="Introduce la descripción">
            </div>

            <!-- Campo de búsqueda por Cuenta -->
            <div class="col-3">
                <label for="search-cuenta">Cuenta de facturación:</label>
                <input type="text" id="search-cuenta" name="search-cuenta" class="form-control" placeholder="Introduce la cuenta">
            </div>



            <div class="container d-flex flex-column align-items-center">
 
            <!-- Botón de búsqueda -->
            <!-- <div class="d-flex justify-content-center">
                <div class="wrap-login-form-btn mt-5">
                    <div class="login-form-bgbtn"></div>
                    <button type="submit" class="login-form-btn" id="exportar-pdf">Buscar</button>
                </div>            
            </div> -->
        </div>
        </div>
    </form>
</div>


<!-- Vista de la página -->
 
<div class=" container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh; min-width:60vw">
    <h1 class="text-center">Lista de Bienes</h1>
    <!-- Formulario para la generación de etiquetas -->
    <form action="" method="post" id="form-generar-etiquetas" style="width: 100%;" >
        <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
        <table class="display" id="bienes-table" style="width: 100%;" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th style="text-align: center; padding: 10px;min-height:7vh;min-width:10vw;">
                        <input type="checkbox" id="seleccionarTodos"> Seleccionar todos<!-- Checkbox para seleccionar todos -->
                    </th>
                    <th style="text-align: center; padding: 10px;min-height:7vh;">Descripción</th>
                    <th style="text-align: center; padding: 10px;min-height:7vh;">Precio</th>
                    <th style="text-align: center; padding: 10px;min-height:7vh;">Centro</th>
                    <th style="text-align: center; padding: 10px;min-height:7vh;">Departamento</th>
                    <th style="text-align: center; padding: 10px;min-height:7vh;">Tipo bien</th>
                    <th style="text-align: center; padding: 10px;min-height:7vh;min-width:10vw">Fecha alta</th>
                    <th style="text-align: center; padding: 10px;min-height:7vh;min-width:10vw">QR</th>
                    <th style="display: none";>codigo</th>
                    <th style="display: none";>factura</th>
                    <th style="display: none";>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bienes as $bien) { ?>
                    <tr>
                        <td style="text-align: center; padding: 10px;min-height:7vh;">
                            <input type="checkbox" name="bienes[]" value="<?php echo $bien['id']; ?>"> <!-- Checkbox para seleccionar el bien -->
                        </td>
                        <td style="text-align: center; padding: 10px;min-height:7vh;">
                            <div style="max-width: 20vw; overflow-x: auto; white-space: nowrap; text-align: center">
                                <?php echo $bien['descripcion']; ?>        
                            </div>
                        </td>
                        <?php 
                                    $tipo_bien = ''; 
                                    switch ($bien["tipo_bien"]) {
                                        case 'ME':
                                            $tipo_bien="Mesa";
                                            break;
                                         case 'SI':
                                            $tipo_bien="Silla";
                                            break;
                                         case 'AR':
                                            $tipo_bien="Armario";
                                            break;
                                         case 'ES':
                                            $tipo_bien="Estantería";
                                            break;
                                        case 'FU':
                                            $tipo_bien="Funda";
                                            break;
                                         case 'BU':
                                            $tipo_bien="Buck";
                                            break;
                                        case 'PI':
                                            $tipo_bien="Pizarra";
                                            break;
                                         case 'IM':
                                            $tipo_bien="Impresora";
                                            break;
                                         case 'SP':
                                            $tipo_bien="Soporte PC";
                                            break;
                                         case 'BA':
                                            $tipo_bien="Bandeja";
                                            break;
                                         case 'PE':
                                            $tipo_bien="Perchero";
                                            break; 
                                        case 'PA':
                                            $tipo_bien="Papelera";
                                            break;
                                         case 'RE':
                                            $tipo_bien="Reposapiés";
                                            break;
                                         case 'EX':
                                            $tipo_bien="Extintor";
                                            break;
                                         case 'DE':
                                            $tipo_bien="Destructora";
                                            break;
                                         case 'CI':
                                            $tipo_bien="Cizalla";
                                            break;            
                                        case 'AI':
                                            $tipo_bien="Equipo Aire";
                                            break;
                                         case 'RU':
                                            $tipo_bien="Roll up";
                                            break;
                                         case 'LA':
                                            $tipo_bien="Lámpara";
                                            break;
                                         case 'EC':
                                            $tipo_bien="Escalera";
                                            break;
                                         case 'AL':
                                            $tipo_bien="Alfombra";
                                            break;                
                                        case 'TV':
                                            $tipo_bien="Televisión";
                                            break;
                                         case 'WC':
                                            $tipo_bien="Webcam";
                                            break;
                                         case 'OR':
                                            $tipo_bien="Ordenador";
                                            break;
                                         case 'MO':
                                            $tipo_bien="Monitor";
                                            break;
                                         case 'VC':
                                            $tipo_bien="Policom";
                                            break;                
                                        case 'SC':
                                            $tipo_bien="Scanner";
                                            break;
                                         case 'IP':
                                            $tipo_bien="Ipad";
                                            break;
                                         case 'PU':
                                            $tipo_bien="Puntero";
                                            break;
                                         case 'FU':
                                            $tipo_bien_="Funda";
                                            break;             
                                        default:
                                            $tipo_bien="Bien sin identificar";
                                            break;
                                    }
                                    $departamento="";
                                    switch ($bien["departamento"]) {
                                        case '00':
                                            $departamento="SIGA";
                                            break;
                                        case '01':
                                            $departamento="Técnico";
                                            break;
                                        case '02':
                                            $departamento="Jurídico";
                                            break;
                                        case '04':
                                            $departamento="Administración";
                                            break;
                                        case '05':
                                            $departamento="Comercial";
                                            break;
                                        case '06':
                                            $departamento="Márketing y Comunicación";
                                            break;
                                        case '07':
                                            $departamento="Patentes y Marcas";
                                            break;
                                        case '08':
                                            $departamento="Dirección";
                                            break;
                                        case '00':
                                            $departamento="Consejeros";
                                            break;
                                        case '10':
                                            $departamento="almacén";
                                            break;
                                        case '11':
                                            $departamento="Sala Juntas";
                                            break;
                                        case '12':
                                            $departamento="Sala reuniones";
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                    $centro="";
                                    switch ($bien["centro"]) {
                                        case '1':
                                            $centro="Pontevedra";
                                            break;
                                        case '2':
                                            $centro="Madrid";
                                            break;
                                        default:
                                        break;
                                    }
                                    $estado="";
                                    switch ($bien["estado"]) {
                                        case '1':
                                            $estado="Activo";
                                            break;
                                        case '0':
                                            $estado="Inactivo";
                                            break;
                                        default:
                                        break;
                                    }
                        ?>
                        <td style="text-align: center; padding: 10px;min-height:7vh;"><?php echo $bien['precio']; ?></td>
                        <td style="text-align: center; padding: 10px;min-height:7vh;"><?php echo $centro; ?></td>
                        <td style="text-align: center; padding: 10px;min-height:7vh;"><?php echo $departamento; ?></td>
                        <td style="text-align: center; padding: 10px;min-height:7vh;"><?php echo $tipo_bien; ?></td>
                        <td style="text-align: center; padding: 10px;min-height:7vh;min-width:10vw"><?php echo $bien["fecha_alta"]; ?></td>                      
                        <td style="text-align: center; padding: 10px; min-height: 7vh; min-width: 10vw;" class="d-flex justify-content-center align-items-center">
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
                        <td style="display: none";><?php echo $bien['numero_factura']; ?></td>
                        <td style="display: none";><?php echo $bien['cuenta_contable']; ?></td> 
                        <td style="display: none";><?php echo $estado; ?></td>                          
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="container d-flex flex-column align-items-center">
        <div class="d-flex justify-content-center">
            <input type="number" name="posicion" id="posicion" class="form-control" placeholder="Indique la posición"
            style="margin-left: 2vw;color: white; background-color: #861636; padding-left: 5px; opacity: 1; font-size: 15px; font-family: 'Verdana', cursive;" min="0">
        </div>
</div>
        <div class="container d-flex flex-column align-items-center mt-5">
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
    // Búsqueda por código
        $('#search-descripcion').on('keyup', function () {
            var descripcion = this.value.trim(); // Evitar espacios extraños
            if(descripcion !== "") {
                table.column(1).search(descripcion, true, false).draw(); // Añadimos expresión regular para que la búsqueda sea exacta
            } else {
                table.column(1).search("").draw(); // Si no hay valor, reseteamos el filtro
            }
        });
        $('#search-centro').on('keyup', function () {
            var centro = this.value.trim(); // Evitar espacios extraños
            if(centro !== "") {
                table.column(3).search(centro, true, false).draw(); // Añadimos expresión regular para que la búsqueda sea exacta
            } else {
                table.column(3).search("").draw(); // Si no hay valor, reseteamos el filtro
            }
        });     
        $('#search-departamento').on('change', function () {
            var departamento = this.value.trim(); // Evitar espacios extraños
            if (departamento !== "") {
                table.column(4).search(departamento, true, false).draw(); // Añadimos expresión regular para que la búsqueda sea exacta
            } else {
                table.column(4).search("").draw(); // Si no hay valor, reseteamos el filtro
            }
        });       
        $('#search-tipo-bien').on('change', function () {
            var tipo_bien = this.value.trim(); // Evitar espacios extraños
            if (tipo_bien !== "") {
                table.column(5).search(tipo_bien, true, false).draw(); // Añadimos expresión regular para que la búsqueda sea exacta
            } else {
                table.column(5).search("").draw(); // Si no hay valor, reseteamos el filtro
            }
        });       

        var fechaInicio = null;
        var fechaFin = null;

        // Configuración del filtro personalizado en DataTables
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var fechaAlta = data[6]; // Obtenemos la fecha de alta de la columna 6
                var fechaAltaDate = new Date(fechaAlta); // Convertimos la fecha de alta a objeto Date

                if (fechaAltaDate == "Invalid Date") {
                    return true; // Si no hay una fecha válida, no aplicar el filtro (mostrar la fila)
                }

                // Convertimos las fechas de inicio y fin a objetos Date si están seleccionadas
                var fechaInicioDate = fechaInicio ? new Date(fechaInicio) : null;
                var fechaFinDate = fechaFin ? new Date(fechaFin) : null;

                // Comparar según los valores de fechaInicio y fechaFin
                if (
                    (!fechaInicioDate || fechaAltaDate >= fechaInicioDate) && // Si no hay fechaInicio o es mayor o igual
                    (!fechaFinDate || fechaAltaDate <= fechaFinDate) // Si no hay fechaFin o es menor o igual
                ) {
                    return true; // La fecha de alta está dentro del rango
                }

                return false; // La fecha de alta está fuera del rango
            }
        );

        // Evento 'change' para la fecha de inicio
        $('#search-fecha-inicio').on('change', function () {
            fechaInicio = this.value.trim(); // Guardamos la fecha de inicio
            table.draw(); // Redibujamos la tabla con el nuevo filtro aplicado
        });

        // Evento 'change' para la fecha fin
        $('#search-fecha-fin').on('change', function () {
            fechaFin = this.value.trim(); // Guardamos la fecha de fin
            table.draw(); // Redibujamos la tabla con el nuevo filtro aplicado
        });



        $('#search-estado').on('change', function () {
            var estado = this.value.trim(); // Evitar espacios extraños
            if (estado !== "") {
                table.column(10).search(estado, true, false).draw(); // Añadimos expresión regular para que la búsqueda sea exacta
            } else {
                table.column(10).search("").draw(); // Si no hay valor, reseteamos el filtro
            }
        });                     
        $('#search-cuenta').on('keyup', function () {
            var cuenta = this.value.trim();
            if(cuenta !== "") {
                table.column(9).search(cuenta, true, false).draw();
            } else {
                table.column(9).search("").draw();
            }
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
