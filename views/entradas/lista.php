<?php 
include "../../menu.php";
@session_start();

if(!isset($_SESSION["login"]) || ($_SESSION["login"]=="Invitado")){
      header("Location: ".ROOT_PATH)."inicio.php";
}
if (isset($_SESSION['entradas'])) {
    $entradas = $_SESSION['entradas'];
} else {
    $entradas = []; // Manejar si no hay proveedores en la sesión
}

?>

<!-- Modal para seleccionar el motivo de eliminación del bien-->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEliminarLabel">Selecciona el motivo de eliminación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEliminar">
          <div class="form-group">
            <label for="motivoSelect">Motivo de eliminación:</label>
            <select class="form-control" id="motivoSelect" required>
              <option value="NULL"></option>
              <option value="Obsolescencia">Obsolescencia</option>
              <option value="Deterioro">Deterioro</option>
              <option value="Venta">Venta</option>
              <option value="Averiado">Averiado</option>
              <option value="Extravío">Extravío</option>
              <option value="Otras causas">Otras causas</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="confirmarEliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para mostrar los bienes -->
<div class="modal fade" id="modalBienes" tabindex="-1" aria-labelledby="modalBienesLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalBienesLabel">Bienes de la Entrada</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="bienesContent">
          <!-- Aquí se insertará dinámicamente la tabla de bienes -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Vista de la página-->
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
    <!-- Título centrado -->
    <h1 class="text-center">Lista de Entradas</h1>
    <!-- Tabla proveedores-->

    <table class="display" id="entradas-table" style="width:60vw" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: center;">Cuenta facturación</th>
                <th style="text-align: center;">Descripción</th>
                <th style="text-align: center;">Fecha de Compra</th>
                <th style="text-align: center;">Precio</th>
                <th style="text-align: center;">Número de bienes</th>
                <th style="text-align: center;">Acciones</th>
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










<!-- JavaScript para dar formato a las tablas y mostrar/ocultar la lista de bienes -->
<script>
    new DataTable('#entradas-table', {
    language: {
        info: 'Mostrando página _PAGE_ de _PAGES_',
        infoEmpty: 'No hay registros disponibles',
        infoFiltered: '(filtrado de _MAX_ registros totales)',
        lengthMenu: 'Mostrar _MENU_ registros por página',
        zeroRecords: 'No se encontraron registros',
    },

});



// Manejar la confirmación de eliminación dentro del modal
document.getElementById('confirmarEliminar').addEventListener('click', function() {
    // Obtener el motivo seleccionado
    var motivo = document.getElementById('motivoSelect').value;

    // Verificar si se seleccionó un motivo válido
    if (motivo === "NULL" || motivo.trim() === "") {
        alert('El motivo de la eliminación es obligatorio.');
        return;
    }

    // Confirmar si desea continuar con la eliminación
    var confirmacion = confirm('Motivo de la eliminación: "' + motivo + '". ¿Estás seguro de que deseas eliminar este bien?');

    // Si el usuario confirma, redirigir a la URL de eliminación con el motivo
    if (confirmacion) {
        window.location.href = '<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bien&opcion=eliminar&bien=' + bienAEliminar + '&motivo=' + encodeURIComponent(motivo);
    }

    // Cerrar el modal
    $('#modalEliminar').modal('hide');
});



//desplegar/ocultar la lista de bienes
function showBienesModal(idEntrada) {
    var bienesContent = '';
    // Botón "Añadir"

    var bienesPorEntrada = <?php echo json_encode($_SESSION['bienesPorEntrada']); 


    ?>;
    if (bienesPorEntrada[idEntrada] && bienesPorEntrada[idEntrada].length > 0) {
        bienesContent += '<table class="bienes-table" id="bienes-table" style="width: 100%; border: 1px solid black;" cellpadding="5" cellspacing="0">';
        bienesContent += '<thead><tr>';
        bienesContent += '<th style="text-align: center;">Descripción</th>';
        bienesContent += '<th style="text-align: center;">Precio</th>';
        bienesContent += '<th style="text-align: center;">Código</th>';
        bienesContent += '<th style="text-align: center;">Activo</th>';
        bienesContent += '<th style="text-align: center;">Acciones</th>';
        bienesContent += '</tr></thead><tbody>';

        // Generar el contenido de la tabla dinámicamente
        bienesPorEntrada[idEntrada].forEach(function(bien) {
            bienesContent += '<tr>';
            bienesContent += '<td style="text-align: center;">' + bien['descripcion'] + '</td>';
            bienesContent += '<td style="text-align: center;">' + bien['precio'] + '</td>';
            bienesContent += '<td style="text-align: center;">' + bien['codigo'] + '</td>';
            bienesContent += '<td style="text-align: center;">' + (bien['activo'] ? 'Activo' : 'Inactivo') + '</td>';
            bienesContent += '<td style="text-align: center;">';
            // Botón "Editar"
            bienesContent += '<button onclick="window.location.href=\'<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bien&opcion=editar&bien=' + bien['id'] + '\'">';
            bienesContent += '<img src="<?php echo ROOT_PATH; ?>public/images/editar.webp" alt="Editar" class="iconoItem"> ';
            bienesContent += '</button> ';
            // Botón "Eliminar"
            bienesContent += '<button onclick="eliminarBien(' + bien['id'] + ')">';
            bienesContent += '<img src="<?php echo ROOT_PATH; ?>public/images/eliminar.jpg" alt="Eliminar" class="iconoItem"> ';
            bienesContent += '</button>';
            bienesContent += '</td>';
            bienesContent += '</tr>';
        });        
        bienesContent += '</tbody></table>';
        bienesContent +='<div class="container mt-5" style="display: flex;justify-content: center; align-items: center; ">'
        bienesContent += '<button onclick="window.location.href=\'<?php echo BIEN_PATH ?>crear.php?entrada=' + idEntrada + '\'">';
        bienesContent += '<img src="<?php echo ROOT_PATH; ?>public/images/add.png" alt="Añadir" class="iconoItem"> Añadir bien';
        bienesContent += '</button>';
        bienesContent += '</div>';
    } else {
        bienesContent = '<p>No hay bienes que mostrar</p>';
        bienesContent += '<button onclick="window.location.href=\'<?php echo BIEN_PATH ?>crear.php?entrada=' + idEntrada + '\'">';
        bienesContent += '<img src="<?php echo ROOT_PATH; ?>public/images/add.png" alt="Añadir" class="iconoItem"> Añadir bien';
        bienesContent += '</button>';
    }

    // Insertar el contenido en el modal
    document.getElementById('bienesContent').innerHTML = bienesContent;

    // Mostrar el modal de Bienes
    $('#modalBienes').modal('show');
    new DataTable('#bienes-table', {
    language: {
        info: 'Mostrando página _PAGE_ de _PAGES_',
        infoEmpty: 'No hay registros disponibles',
        infoFiltered: '(filtrado de _MAX_ registros totales)',
        lengthMenu: 'Mostrar _MENU_ registros por página',
        zeroRecords: 'No se encontraron registros',
    }
});


}


function eliminarBien(idBien) {
    // Ocultar la tabla de bienes
    $('#modalBienes').modal('hide');
    // Guardar el ID del bien que se quiere eliminar
    bienAEliminar = idBien;

    // Mostrar el modal para seleccionar el motivo
    $('#modalEliminar').modal('show');
}

// Variable global para almacenar el ID del bien a eliminar
let bienAEliminar = null;
</script>
