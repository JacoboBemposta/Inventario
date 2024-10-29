<?php
include "../../menu.php";
require_once "../../config/auth.php";
require_once '../../libs/phpqrcode/qrlib.php';
include_once "../../csrf.php";
@session_start();



if (isset($_SESSION['bienestotal'])) {
    $bienes = $_SESSION['bienestotal'];
} else {
    $bienes = []; // Manejar si no hay entradas en la sesión
}

?>
<!-- Incluir Moment.js para formatear la fecha en datatables -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- Incluir el plugin datetime-moment.js para DataTables -->
<script src="https://cdn.datatables.net/plug-ins/1.10.24/sorting/datetime-moment.js"></script>
<!-- Vista de la lista de bienes -->
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
    <h1 class="text-center">Lista de Bienes</h1>
    <div class="container">
        <!-- Formulario de búsqueda -->
        <form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bienes&opcion=buscar" method="POST" class="p-3 rounded border border-8 border border-black" style="color: white;">
            <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
            <div class="row g-3">
                <!-- Campo de búsqueda por Centro -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="search-centro">Centro:</label>
                    <select id="search-centro" name="search-centro" class="form-control">
                        <option value="">Centro</option>
                        <option value="Pontevedra">Pontevedra</option>
                        <option value="Madrid">Madrid</option>
                    </select>
                </div>

                <!-- Campo de búsqueda por Departamento -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="search-departamento">Departamento:</label>
                    <select id="search-departamento" name="search-departamento" class="form-control">
                        <option value="">Departamento</option>
                        <option value="Administración">Administración</option>
                        <option value="Almacén">Almacén</option>
                        <option value="CAU">CAU</option>
                        <option value="Comercial">Comercial</option>
                        <option value="Consejeros">Consejeros</option>
                        <option value="">Departamento</option>
                        <option value="Dirección">Dirección</option>
                        <option value="Jurídico">Jurídico</option>
                        <option value="Marketing y comunicación">Marketing y comunicación</option>
                        <option value="Patentes y Marcas">Patentes y Marcas</option>
                        <option value="Sala Juntas">Sala Juntas</option>
                        <option value="Sala Reuniones">Sala Reuniones</option>
                        <option value="SIGA">SIGA</option>
                        <option value="Técnico">Técnico</option>                        
                    </select>
                </div>

                <!-- Campo de búsqueda por Tipo de bien -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="search-tipo-bien">Tipo de bien:</label>
                    <select id="search-tipo-bien" name="search-tipo-bien" class="form-control">
                        <option value="">Tipo de bien</option>
                        <option value="Alfombra">Alfombra</option>
                        <option value="Armario">Armario</option>
                        <option value="Bandeja">Bandeja</option>
                        <option value="Buck">Buck</option>
                        <option value="Cizalla">Cizalla</option>
                        <option value="Destructora">Destructora</option>
                        <option value="Equipo aire">Equipo aire</option>
                        <option value="Escalera">Escalera</option>
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
                        <option value="Webcam">Webcam</option>
                    </select>
                </div>

                <!-- Campo de búsqueda por Estado -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="search-estado">Estado:</label>
                    <select id="search-estado" name="search-estado" class="form-control">
                        <option value="">Estado</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>

                <!-- Campo de rango de fechas -->
                <div class="col-12 col-md-12 col-lg-6">
                    <label for="search-fechas">Rango de fechas:</label>
                    <div class="d-flex flex-column flex-md-row">
                        <input type="date" id="search-fecha-inicio" name="search-fecha-inicio" class="form-control mr-md-4 mb-2 mb-md-0" placeholder="Fecha inicio">
                        <input type="date" id="search-fecha-fin" name="search-fecha-fin" class="form-control mr-ml-5 mb-2 mb-md-0" placeholder="Fecha fin">
                    </div>
                </div>

                <!-- Campo de búsqueda por Descripción -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="search-descripcion">Descripción:</label>
                    <input type="text" id="search-descripcion" name="search-descripcion" class="form-control" placeholder="Descripción">
                </div>

                <!-- Campo de búsqueda por Cuenta -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="search-cuenta">Cuenta de facturación:</label>
                    <input type="text" id="search-cuenta" name="search-cuenta" class="form-control" placeholder="Cuenta facturación">
                </div>
            </div>
        </form>
    </div>


    <!-- Formulario para la generación de etiquetas -->
    <div class="container d-flex flex-column justify-content-center align-items-center mt-5 " style="min-height: 50vh;">
    <form action="" method="post" id="form-generar-etiquetas" style="width: 100%;">
        <input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
        <table class="display" id="bienes-table" style="width: 100%; box-sizing: border-box;" cellpadding="5" cellspacing="0">
            <thead>
                    <tr>
                        <th style="text-align: center;min-width:3vw;">
                            <input type="checkbox" id="seleccionarTodos"> <!-- Checkbox para seleccionar todos -->
                        </th>
                        <th style="text-align: center; padding: 10px;min-height:7vh;min-width:7vw">Cuenta</th>
                        <th style="text-align: center; padding: 10px;min-height:7vh;min-width:23vw">Descripción</th>
                        <th style="text-align: center; padding: 10px;min-height:7vh;min-width:8vw">Fecha alta</th>
                        <th style="text-align: center; padding: 10px;min-height:7vh;min-width:8vw">Codigo</th>
                        <th style="text-align: center; padding: 10px;min-height:7vh;min-width:8vw">Estado</th>
                        <th style="text-align: center; padding: 10px;min-height:7vh;min-width:6vw" hidden>Precio</th>
                        <th style="text-align: center; padding: 10px;min-height:7vh;min-width:5vw" hidden>Centro</th>
                        <th style="text-align: center; padding: 10px;min-height:7vh;min-width:9vw " hidden>Departamento</th>
                        <th style="text-align: center; padding: 10px;min-height:7vh;min-width:7vw" hidden>Tipo bien</th>
                        <th style="text-align: center; min-height:7vh;min-width:6vw">QR</th>
                    </tr>
                </thead>
            <tbody>
                <?php foreach ($bienes as $bien) { 
                        //Dar formato al codigo 
                        $contador = intval($bien["id"]);
                        if ($contador > 9999) $contador = $contador - 9999;

                        $codigo = str_pad($contador, 4, '0', STR_PAD_LEFT);

                        $tipo_bien = '';
                        switch ($bien["tipo_bien"]) {
                            case 'ME':
                                $tipo_bien = "Mesa";
                                break;
                            case 'SI':
                                $tipo_bien = "Silla";
                                break;
                            case 'AR':
                                $tipo_bien = "Armario";
                                break;
                            case 'ES':
                                $tipo_bien = "Estantería";
                                break;
                            case 'FU':
                                $tipo_bien = "Funda";
                                break;
                            case 'BU':
                                $tipo_bien = "Buck";
                                break;
                            case 'PI':
                                $tipo_bien = "Pizarra";
                                break;
                            case 'IM':
                                $tipo_bien = "Impresora";
                                break;
                            case 'SP':
                                $tipo_bien = "Soporte PC";
                                break;
                            case 'BA':
                                $tipo_bien = "Bandeja";
                                break;
                            case 'PE':
                                $tipo_bien = "Perchero";
                                break;
                            case 'PA':
                                $tipo_bien = "Papelera";
                                break;
                            case 'RE':
                                $tipo_bien = "Reposapiés";
                                break;
                            case 'EX':
                                $tipo_bien = "Extintor";
                                break;
                            case 'DE':
                                $tipo_bien = "Destructora";
                                break;
                            case 'CI':
                                $tipo_bien = "Cizalla";
                                break;
                            case 'AI':
                                $tipo_bien = "Equipo Aire";
                                break;
                            case 'RU':
                                $tipo_bien = "Roll up";
                                break;
                            case 'LA':
                                $tipo_bien = "Lámpara";
                                break;
                            case 'EC':
                                $tipo_bien = "Escalera";
                                break;
                            case 'AL':
                                $tipo_bien = "Alfombra";
                                break;
                            case 'TV':
                                $tipo_bien = "Televisión";
                                break;
                            case 'WC':
                                $tipo_bien = "Webcam";
                                break;
                            case 'OR':
                                $tipo_bien = "Ordenador";
                                break;
                            case 'MO':
                                $tipo_bien = "Monitor";
                                break;
                            case 'VC':
                                $tipo_bien = "Policom";
                                break;
                            case 'SC':
                                $tipo_bien = "Scanner";
                                break;
                            case 'IP':
                                $tipo_bien = "Ipad";
                                break;
                            case 'PU':
                                $tipo_bien = "Puntero";
                                break;
                            case 'FU':
                                $tipo_bien_ = "Funda";
                                break;
                            default:
                                $tipo_bien = "Bien sin identificar";
                                break;
                        }
                        $departamento = "";
                        switch ($bien["departamento"]) {
                            case '00':
                                $departamento = "SIGA";
                                break;
                            case '01':
                                $departamento = "Técnico";
                                break;
                            case '02':
                                $departamento = "Jurídico";
                                break;
                            case '04':
                                $departamento = "Administración";
                                break;
                            case '05':
                                $departamento = "Comercial";
                                break;
                            case '06':
                                $departamento = "Márketing y Comunicación";
                                break;
                            case '07':
                                $departamento = "Patentes y Marcas";
                                break;
                            case '08':
                                $departamento = "Dirección";
                                break;
                            case '00':
                                $departamento = "Consejeros";
                                break;
                            case '10':
                                $departamento = "almacén";
                                break;
                            case '11':
                                $departamento = "Sala Juntas";
                                break;
                            case '12':
                                $departamento = "Sala reuniones";
                                break;
                            default:
                                # code...
                                break;
                        }
                        $centro = "";
                        switch ($bien["centro"]) {
                            case '1':
                                $centro = "Pontevedra";
                                break;
                            case '2':
                                $centro = "Madrid";
                                break;
                            default:
                                break;
                        }
                        $estado = "";
                        switch ($bien["estado"]) {
                            case '0':
                                $estado = "Inactivo";
                                break;
                            case '1':
                                $estado = "Activo";
                                break;
                            default:
                                break;
                        }
                        ?>
                        
                    <tr>
                        <td style="text-align: center; padding: 10px;min-height:7vh;">
                            <input type="checkbox" name="bienes[]" value="<?php echo $bien['id']; ?>"> <!-- Checkbox para seleccionar el bien -->
                        </td>
                        <td style="text-align: center; padding: 10px;min-height:7vh;"><?php echo $bien['cuenta_contable']; ?></td>
                        <td style="text-align: center; padding: 10px;min-height:7vh;"><?php echo $bien['descripcion']; ?></td>
                        <td style="text-align: center; padding: 10px;"><?php echo $bien["fecha_alta"]; ?></td>
                        <td style="text-align: center; padding: 10px;min-height:7vh;"><?php echo $bien['centro'] . ' ' . $bien['departamento'] . ' ' .$bien['tipo_bien'].' '. $codigo; ?></td>
                        <td style="text-align: center; padding: 10px;min-height:7vh;"><?php echo $estado; ?></td>
                        <td style="text-align: center; padding: 10px;"hidden><?php echo $bien['precio']; ?></td>
                        <td style="text-align: center; padding: 10px;" hidden><?php echo $centro; ?></td>
                        <td style="text-align: center; padding: 10px;" hidden><?php echo $departamento; ?></td>
                        <td style="text-align: center; padding: 10px;" hidden><?php echo $tipo_bien; ?></td>
                        
                        <td style="text-align: center; padding: 10px;" class="d-flex justify-content-center align-items-center">
                            <?php
                                $fechaCompra = $bien["fecha_compra"];

                                // Formateo de fechas
                                $date = DateTime::createFromFormat('Y-m-d', $fechaCompra);
                                if ($date) {
                                    $fechaFormateada = $date->format('d/m/Y');
                                } else {
                                    $fechaFormateada = 'Fecha no válida'; 
                                }
                            
                            $contenido = "";
                            $contenido .= $bien["nombre"] . "\n";
                            $contenido .= $bien["descripcion"] . "\n";
                            $contenido .= $bien["cuenta_contable"] . "\n";
                            $contenido .= $bien['centro'] . ' ' . $bien['departamento'] . ' ' .$bien['tipo_bien'].' '. $codigo . "\n";
                            $contenido .= $fechaFormateada. "\n";
                            
                            // Si no existe la ruta, la crea
                            if (!file_exists(TEMP_PATH)) mkdir(TEMP_PATH);

                            $filename = TEMP_PATH . html_entity_decode($bien['centro'] . $bien['departamento'] .$bien['tipo_bien']. $codigo) . '.png'; // Crea el archivo .png en la ruta indicada
                            $tamanho = 1; // tamaño de la imagen
                            $level = 'H'; // tipo de precision
                            $framesize = 3; // marco del qr en blanco
                            QRcode::png($contenido, $filename, $level, $tamanho, $framesize);

                            echo '<img style="width: 100%; height: 100%" src="' . ROOT_PATH . 'public/temp/' . html_entity_decode($bien['centro'] . $bien['departamento'] .$bien['tipo_bien']. $codigo) . '.png">';
                            ?>
                        </td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- Input posición de la primera etiqueta -->
        <div class="container d-flex flex-column align-items-center">
            <div class="d-flex justify-content-center">
                <input type="number" name="posicion" id="posicion" class="form-control" placeholder="Indique la posición"
                    style="margin-left: 2vw;color: white; background-color: #861636; padding-left: 5px; opacity: 1; font-size: 15px; font-family: 'Verdana', cursive;" min="0">
            </div>
        </div>
        <!-- Botones para generar PDF -->
        <div class="container d-flex flex-column align-items-center mt-5">
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
</div>

<script>


    //Script para inicializar DataTables 
    $(document).ready(function() {
        var table = $('#bienes-table').DataTable({
            pageLength: 10, // Limitar a 5 registros por página
            language: {
                info: 'Mostrando página _PAGE_ de _PAGES_',
                infoEmpty: 'No hay registros disponibles',
                infoFiltered: '(filtrado de _MAX_ registros totales)',
                lengthMenu: 'Mostrar _MENU_ registros por página',
                zeroRecords: 'No se encontraron registros',
            },
            layout: {
                topEnd: null
            },
            // Definimos la columna que contiene fechas para que aplique el formato de orden y visualización
            columnDefs:[{targets:3, render:function(data){
            return moment(data).format('DD/MM/YYYY');
            }}]            
        });
        // Evento keyup para el codigo
        $('#search-descripcion').on('keyup', function() {
            var descripcion = this.value.trim(); // Evitar espacios extraños
            if (descripcion !== "") {
                table.column(2).search(descripcion, true, false).draw(); // Añadimos expresión regular para que la búsqueda sea exacta
            } else {
                table.column(2).search("").draw(); // Si no hay valor, reseteamos el filtro
            }
        });
        // Evento change para el centro
        $('#search-centro').on('change', function() {
            var centro = this.value.trim(); // Evitar espacios extraños
            if (centro !== "") {
                table.column(7).search(centro, true, false).draw(); // Añadimos expresión regular para que la búsqueda sea exacta
            } else {
                table.column(7).search("").draw(); // Si no hay valor, reseteamos el filtro
            }
        });
        // Evento change para el departamento
        $('#search-departamento').on('change', function() {
            var departamento = this.value.trim(); // Evitar espacios extraños
            if (departamento !== "") {
                table.column(8).search(departamento, true, false).draw(); // Añadimos expresión regular para que la búsqueda sea exacta
            } else {
                table.column(8).search("").draw(); // Si no hay valor, reseteamos el filtro
            }
        });
        // Filtro por tipo-bien
        $('#search-tipo-bien').on('change', function() {
            var tipo_bien = this.value.trim(); // Evitar espacios extraños
            if (tipo_bien !== "") {
                table.column(9).search(tipo_bien, true, false).draw(); // Añadimos expresión regular para que la búsqueda sea exacta
            } else {
                table.column(9).search("").draw(); // Si no hay valor, reseteamos el filtro
            }
        });

        var fechaInicio = null;
        var fechaFin = null;

        //funcion para visualizar texto en el placeholder de input tipo date
        window.addEventListener('load', function() {
            document.getElementById('search-fecha-inicio').type = 'text';
            document.getElementById('search-fecha-inicio').addEventListener('blur', function() {
                this.type = 'text';
            });
            document.getElementById('search-fecha-inicio').addEventListener('focus', function() {
                this.type = 'date';
            });

            document.getElementById('search-fecha-fin').type = 'text';
            document.getElementById('search-fecha-fin').addEventListener('blur', function() {
                this.type = 'text';
            });
            document.getElementById('search-fecha-fin').addEventListener('focus', function() {
                this.type = 'date';
            });
        });








        // Configuración del filtro personalizado en DataTables
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var fechaAlta = moment(data[3], 'DD-MM-YYYY'); 
            if (!fechaAlta.isValid()) {
                return true; // Si no hay una fecha válida, mostrar la fila
            }

            // Comparar con fechas de inicio y fin
            var fechaInicioDate = fechaInicio ? moment(fechaInicio, 'YYYY-MM-DD') : null;
            var fechaFinDate = fechaFin ? moment(fechaFin, 'YYYY-MM-DD') : null;

            if (
                (!fechaInicioDate || fechaAlta.isSameOrAfter(fechaInicioDate)) && // Comparar fechas de inicio
                (!fechaFinDate || fechaAlta.isSameOrBefore(fechaFinDate)) // Comparar fechas de fin
            ) {
                return true; // La fecha de alta está dentro del rango
            }

            return false; // Fuera del rango
        });







        // Evento 'change' para la fecha de inicio
        $('#search-fecha-inicio').on('change', function() {
            fechaInicio = this.value.trim(); // Guardamos la fecha de inicio
            table.draw(); // Redibujamos la tabla con el nuevo filtro aplicado
        });

        // Evento 'change' para la fecha fin
        $('#search-fecha-fin').on('change', function() {
            fechaFin = this.value.trim(); // Guardamos la fecha de fin
            table.draw(); // Redibujamos la tabla con el nuevo filtro aplicado
        });


        // Evento change para el estado
        $('#search-estado').on('change', function() {
            var estado = this.value.trim(); // Evitar espacios extraños
            if (estado !== "") {
                table.column(5).search('^' + estado + '$', true, false).draw(); // Añadimos expresión regular para que la búsqueda sea exacta
            } else {
                table.column(5).search("").draw(); // Si no hay valor, reseteamos el filtro
            }
        });
        // Evento keyup para la cuenta               
        $('#search-cuenta').on('keyup', function() {
            var cuenta = this.value.trim();
            if (cuenta !== "") {
                table.column(1).search(cuenta, true, false).draw();
            } else {
                table.column(1).search("").draw();
            }
        });
        // Seleccionar/Deseleccionar todos los checkboxes
        $('#seleccionarTodos').on('click', function() {
            var rows = table.rows({
                'search': 'applied'
            }).nodes();
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