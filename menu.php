<?php
require "config/config.php";

ob_start();
@session_start();
if (!isset($_SESSION["login"])) {
    $_SESSION["login"] = "Invitado";
}
if ($_SESSION["login"] != "Invitado") { ?>
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
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">

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
                    <!-- Logo de la compañía -->
                    <a class="navbar-brand" href="https://www.gestores.net/">
                        <img src="https://www.gestores.net/assets/images/logo-siga.png" alt="Imagen de navegación" style="width: 5vw; height: 5vh;">
                    </a>

                    <!-- Botón para dispositivos móviles -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Enlaces del menú colapsables -->
                    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <?php
                            if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] == "ADMIN") { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo ROOT_PATH ?>index.php">Inicio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=usuarios&opcion=ver">Usuarios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=proveedores&opcion=ver">Proveedores</a>
                                </li>
                            <?php } ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=entradas&opcion=ver">Entrada Bienes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=bienes&opcion=ver">Etiquetas</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Sección de usuario y cierre de sesión alineado a la derecha -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <!-- Mostrar nombre del usuario -->
                            <span class="navbar-text">
                                <?php echo $_SESSION["login"]; ?>
                            </span>
                            <!-- Botón de desconectar -->
                            <?php if ($_SESSION["login"] !== "Invitado") { ?>
                                <a href="<?php echo ROOT_PATH; ?>controllers/indexController.php?ctrl=usuarios&opcion=logout" class="btn btn-link">
                                    <img src="<?php echo ROOT_PATH; ?>public/images/logout.png" alt="Desconectar" style="width: 1.5vw; height: 1.5vh;">
                                </a>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </body>

    </html>
<?php } ?>