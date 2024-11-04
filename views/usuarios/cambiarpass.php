<?php
include "../../menu.php";
@session_start();
include_once '../../csrf.php';

if (!isset($_GET['token'])) {
    // Si no hay token en sesión, redirigir
    $_SESSION['error'] = "Acceso no autorizado.";
    header("Location:".ROOT_PATH." error.php");
    exit;
}else $token = $_GET["token"];

if (isset($_GET['mail'])) {
    $mail = $_GET['mail'];
   
} else {
    $_SESSION['error'] = "El mail no se ha recibido.";
    header("Location:".ROOT_PATH." error.php");
}

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
<?php
if(isset($token)&&isset($mail)){?>
        <div class="container d-flex flex-column justify-content-center align-items-center">
		<div class="container-c">
			<div class="wrap-login">
                <div class="container">
                    <h3 style="white-space: nowrap; font-size:1.3em">
                        <?php if (isset($_SESSION["error"])) echo $_SESSION["error"]; unset($_SESSION["error"]) ?>
                    </h3>
                </div>
				<form action="<?php echo ROOT_PATH ?>controllers/indexController.php?ctrl=usuarios&opcion=editarpass&mail=<?php echo $mail ?>" method="post">
					<input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>"> <!-- Incluye el token CSRF -->
					<!-- LOGO -->
					<img class="avatar" src="https://www.gestores.net/assets/images/logo-siga.png" alt="logo" style="width:10vw;" >
					<!-- USUARIO -->
					<div class="wrap-input100">
						<input class="input100" type="text" name="pass" placeholder="Nueva contraseña">
						<span class="focus-efecto"></span>
					</div>
					<!-- CONTRASEÑA -->
					<div class="wrap-input100">
						<input class="input100" type="password" name="nuevapass" placeholder="Repita contraseña" autocomplete="new-password" autocorrect="off" autocapitalize="off" spellcheck="false">
						<span class="focus-efecto"></span>
					</div>
					<!-- BOTÓN -->
					<div class="container-login-form-btn">
						<div class="wrap-login-form-btn col-6">
							<div class="login-form-bgbtn"></div>
							<button type="submit" name="btnEntrar" class="login-form-btn">ENVIAR</button>
						</div>
					</div>					
				</form>
			</div>
		</div>
	</div>        
    <?php
    }else {
        $_SESSION["error"]="No se ha podido validar el mail";
        header("Location: " . ROOT_PATH . "error.php");
    }
?>

</body>

</html>