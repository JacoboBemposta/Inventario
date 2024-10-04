<?php 
include "config/config.php";
ob_start();
@session_start();
if(!isset($_SESSION["login"])){
    $_SESSION["login"]="Invitado";
    }
if($_SESSION["login"]!="Invitado"){?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicación Inventario</title>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>estilos.css">
    <!-- Bootstrap CSS y datatables -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css">

    <!-- Scripts de Bootstrap y datatables-->
    <script src="https://code.jquery.com/jquery-3.6.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>  
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>  
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
    <!-- Menú de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a href="https://www.gestores.net/">               
                <img src="https://www.gestores.net/assets/images/logo-siga.png" alt="Imagen de navegación" style="width: 5vw; height: 5vh;">
            </a>

           
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav me-auto">
                <?php 
                        if(isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"]=="ADMIN") { ?>
                <li class="nav-item dropdown">
                        <a class="nav-link" href="<?php echo ROOT_PATH ?>index.php" id="usuariosDropdown">
                            Inicio
                        </a>
                    </li>
                    <!-- Usuarios -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=usuarios&opcion=ver" id="usuariosDropdown">
                            Usuarios
                        </a>
                    </li>
                    <!-- Proveedores -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=proveedores&opcion=ver" id="proveedoresDropdown">
                            Proveedores
                        </a>
                    </li>
                    <?php } ?>
                    <!-- Entrada Bienes -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=entradas&opcion=ver" id="proveedoresDropdown">
                            Entrada Bienes
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bienes&opcion=ver" id="proveedoresDropdown">
                            Etiquetas
                        </a>
                    </li>
                </ul>
            </div>
                <!-- Botón login/Cerrar Sesión alineado a la derecha -->
                <ul class="navbar-nav ms-auto justify-content-center align-items-center">
                    <li class="nav-item">
                        <!-- Nombre de usuario -->
                        
                            <?php echo $_SESSION["login"] ?>
                        
                        <!-- Botón de Desconectar -->
                        <?php if ($_SESSION["login"]!== "Invitado") { ?>
                            <a href="<?php echo ROOT_PATH; ?>controllers/indexController.php?ctrl=usuarios&opcion=logout" class="btn-link" style="padding: 0;">
                                <img src="<?php echo ROOT_PATH; ?>public/images/logout.png" alt="Desconectar" style="width: 1.5vw; height: 1.5vh;">
                            </a>
                        <?php } ?>
                    </li>
                </ul>
        </div>
    </nav>
</div>

    

    </div>
</body>
</html>


<?php  }?>