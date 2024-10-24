<?php
require "../../menu.php";
require_once "../../config/auth.php";
@session_start();


if (isset($_SESSION['entradas'])) {
  $entradas = $_SESSION['entradas'];
} else {
  $entradas = []; // Manejar si no hay entradas en la sesión
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




<!-- Vista de la página-->
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 50vh;">
  <!-- Título centrado -->
  <h1 class="text-center">Lista de Entradas</h1>
  <!-- Tabla entradas-->

  <table class="display" id="entradas-table" style="width:60vw" cellpadding="5" cellspacing="0">
    <thead>
      <tr id="encabezado-tabla">
        <th style="text-align: center;">Cuenta facturación</th>
        <th style="text-align: center;">Descripción</th>
        <th style="text-align: center;">Fecha de Compra</th>
        <th style="text-align: center;">Precio</th>
        <th style="text-align: center;">Número de bienes</th>
        <th style="text-align: center;">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($entradas as $key => $entrada) { ?>
        <tr id="entrada-<?php echo $entrada['id']; ?>">
          <td style="text-align: center;"><?php echo $entrada['cuenta_contable']; ?></td>
          <td style="font-weight: bold; color: #2c3e50;text-align: center;"><?php echo $entrada['descripcion']; ?></td>
          <td style="text-align: center;"><?php echo $entrada['fecha_compra']; ?></td>
          <td style="text-align: center;"><?php echo $entrada['precio']; ?></td>
          <td style="text-align: center;"><?php echo $_SESSION['entradas'][$key]['numbienes']; ?></td>
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
            <button onclick="showBienes(<?php echo $entrada['id']; ?>, this)">
              <img src="<?php echo ROOT_PATH; ?>public/images/play.png" alt="Ver Bienes" class="iconoItem">
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
    order: [
      [1, 'asc'],
      [0, 'asc']
    ]
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

      window.location.href = '<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bienes&opcion=eliminar&bien=' + bienAEliminar + '&motivo=' + encodeURIComponent(motivo);
    }

    // Cerrar el modal
    $('#modalEliminar').modal('hide');
  });



  //desplegar/ocultar la lista de bienes
  function showBienes(idEntrada, boton) {
    // Obtener la fila de bienes correspondiente
    const bienesRow = $('#bienesRow-' + idEntrada);


    // Si la fila ya existe y está visible, la ocultamos
    if (bienesRow.length > 0 && bienesRow.is(':visible')) {
      bienesRow.hide(); // Usamos slideUp para un efecto de ocultación más suave
      return; // Terminamos la función si ocultamos la fila
    }

    // Ocultar todas las filas de bienes visibles antes de mostrar la nueva
    $('tr[id^="bienesRow"]').hide(); // Ocultamos las demás filas de bienes con slideUp

    // Si la fila de bienes no existe, la creamos
    if (bienesRow.length === 0) {
      // Crea la nueva fila
      const newRow = `
            <tr id="bienesRow-${idEntrada}" style="width:90%;">
                <td style="display:none"></td>
                <td colspan="6">
                    <div id="bienesContent-${idEntrada}" class="container" style="background-color: #f0f0f0; padding: 10px; width: 90%; margin: 0 auto;">
                        <!-- Aquí se insertará la tabla de bienes -->
                    </div>
                </td>
                <td style="display:none"></td>
                <td style="display:none"></td>
                <td style="display:none"></td>
                <td style="display:none"></td>
            </tr>
        `;

      // Inserta la nueva fila después de la fila de entrada correspondiente
      $('#entrada-' + idEntrada).after(newRow);
    }

    // Si la fila está oculta o acabamos de crearla, muestra la fila
    $(boton).prop('disabled', true); // Desactiva el botón mientras se carga el contenido

    // Realiza la solicitud AJAX para obtener los bienes de la entrada actual
    $.ajax({
      url: '<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bienes&opcion=bienporID',
      type: 'POST',
      data: {
        idEntrada: idEntrada
      },
      dataType: 'json',
      success: function(response) {
        if (typeof response !== 'object') {
          response = JSON.parse(response);
        }

        var bienesContent = '';

        // Si hay bienes, construimos el contenido de la tabla
        if (response.length > 0) {
          bienesContent += '<table class="display" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;" cellpadding="5" cellspacing="0">';
          bienesContent += '<thead><tr>';
          bienesContent += '<th style="text-align: center;width:35%">Descripción</th>';
          bienesContent += '<th style="text-align: center;">Precio</th>';
          bienesContent += '<th style="text-align: center;">Código</th>';
          bienesContent += '<th style="text-align: center;">Estado</th>';
          bienesContent += '<th style="text-align: center;">Acciones</th>';
          bienesContent += '</tr></thead><tbody>';

          response.forEach(function(bien) {
            bienesContent += '<tr>';
            bienesContent += '<td style="text-align: center; width: 35%">' + bien.descripcion + '</td>';
            bienesContent += '<td style="text-align: center;">' + bien.precio + '</td>';
            bienesContent += '<td style="text-align: center;">' + bien.codigo + '</td>';
            bienesContent += '<td style="text-align: center;">';
            let switchChecked = bien.estado == 1 ? 'checked' : '';
            bienesContent += '<div class="form-check form-switch ml-2">';
            bienesContent += '<input class="form-check-input" type="checkbox" role="switch" id="switch' + bien.id + '" ' + switchChecked + ' onchange="cambiarEstado(' + bien.id + ', this.checked)">';
            bienesContent += '<label class="form-check-label" for="switch' + bien.id + '">' + (bien.estado == 1 ? 'Activo' : 'Inactivo') + '</label>';
            bienesContent += '</div></td>';
            bienesContent += '<td style="text-align: center;">';
            bienesContent += '<button onclick="window.location.href=\'../../controllers/IndexController.php?ctrl=bienes&opcion=editar&bien=' + bien.id + '\'">';
            bienesContent += '<img src="../../public/images/editar.webp" alt="Editar" class="iconoItem">';
            bienesContent += '</button>';
            bienesContent += '<button type="button" onclick="eliminarBien(' + bien.id + ')">';
            bienesContent += '<img src="../../public/images/eliminar.jpg" alt="Eliminar" class="iconoItem">';
            bienesContent += '</button>';
            bienesContent += '</td>';
            bienesContent += '</tr>';
          });

          bienesContent += '</tbody></table>';
          bienesContent += '<div class="container mt-5" style="display: flex; justify-content: center; align-items: center;">';
          bienesContent += '<button onclick="window.location.href=\'../../views/bienes/crear.php?entrada=' + idEntrada + '\'">';
          bienesContent += '<img src="../../public/images/add.png" alt="Añadir" class="iconoItem"> Añadir bien';
          bienesContent += '</button></div>';
        } else {
          // Si no hay bienes, mostrar un mensaje
          bienesContent = '<div class="container d-flex flex-column justify-content-center align-items-center"><p>No hay bienes que mostrar</p>';
          bienesContent += '<button onclick="window.location.href=\'../../views/bienes/crear.php?entrada=' + idEntrada + '\'">';
          bienesContent += '<img src="../../public/images/add.png" alt="Añadir" class="iconoItem"> Añadir bien';
          bienesContent += '</button></div>';
        }

        // Actualiza el contenido de bienes
        $('#bienesContent-' + idEntrada).html(bienesContent);

        // Muestra la fila de bienes
        $('#bienesRow-' + idEntrada).show(800);

        // Inicializa DataTable para la tabla de bienes
        $('#bienesRow-' + idEntrada + ' table.display').DataTable();

        // Reactivar el botón
        $(boton).prop('disabled', false);
      },
      error: function() {
        alert('Ocurrió un error al obtener los bienes.');
        $(boton).prop('disabled', false);
      }
    });
  }



  function eliminarBien(idBien) {
    // Guardar el ID del bien que se quiere eliminar
    bienAEliminar = idBien;

    // Mostrar el modal para seleccionar el motivo
    $('#modalEliminar').modal('show');
  }

  // Variable global para almacenar el ID del bien a eliminar
  let bienAEliminar = null;


  function cambiarEstado(bienId, estado) {
    // Convertir el estado booleano a 1 o 0
    var nuevoEstado = estado ? 1 : 0;

    // Llamar al servidor para actualizar el estado en la base de datos
    $.ajax({
      url: '<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bienes&opcion=estadoBien',
      type: 'POST',
      data: {
        ctrl: 'bienes',
        opcion: 'estadoBien',
        bien_id: bienId,
        estado: nuevoEstado,
      },
      dataType: 'json',

      success: function(response) {

        if (typeof response !== 'object') {
          response = JSON.parse(response);
        }
        if (response.success) {
          var label = document.querySelector('label[for="switch' + bienId + '"]');
          label.textContent = (nuevoEstado == 1) ? 'Activo' : 'Inactivo';
        } else {
          alert(response.message || 'Error al actualizar el estado');
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log("Error: ", textStatus, errorThrown);
        console.log("Respuesta del servidor:", jqXHR.responseText); // Captura la respuesta completa del servidor
        alert('Error en la solicitud AJAX: ' + textStatus);
      }
    });
  }
</script>