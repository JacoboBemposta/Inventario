<?php
include "menu.php";
@session_start();
include_once 'csrf.php';
?>
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
	<div class="container d-flex flex-column justify-content-center align-items-center">

		<div class="container-c">
			<div class="wrap-login">
			<div class="container">
					<h3 style="white-space: nowrap; font-size:1.3em; text-align: center; margin-bottom: 20px;">
                        <?php if (isset($_SESSION["success"])) echo $_SESSION["success"]; unset($_SESSION["success"])  ?>
                    </h3>
                </div>
				<form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=usuarios&opcion=login" method="post">
					<input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
					<!-- LOGO -->
					<span class="login-form-title">Iniciar Sesión</span>
					<img class="avatar" src="https://www.gestores.net/assets/images/logo-siga.png" alt="logo" style="width:10vw;" >
					<!-- USUARIO -->
					<div class="wrap-input100">
						<input class="input100" type="text" name="usuario" placeholder="Usuario">
						<span class="focus-efecto"></span>
					</div>
					<!-- CONTRASEÑA -->
					<div class="wrap-input100">
						<input class="input100" type="password" name="password" placeholder="Contraseña" autocomplete="new-password" autocorrect="off" autocapitalize="off" spellcheck="false">
						<span class="focus-efecto"></span>
					</div>
					<!-- BOTÓN -->
					<div class="container-login-form-btn">
						<div class="wrap-login-form-btn col-6">
							<div class="login-form-bgbtn"></div>
							<button type="submit" name="btnEntrar" class="login-form-btn">ENTRAR</button>
						</div>
					</div>					
				</form>
				<div class="container-login-form-btn mt-5">
					<div class="wrap-login-form-btn d-flex flex-column justify-content-center align-items-center">
						<a href="<?php echo USR_PATH ?>recuperar.php">Recuperar contraseña</a>
					</div>
				</div>		
			</div>
		</div>
	</div>
</body>

</html>