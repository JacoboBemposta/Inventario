<?php 
include "menu.php";
@session_start();
if(!isset($_SESSION["login"])){
    $_SESSION["login"]="Invitado";
    }
if(isset($_SESSION["error"])) $mensaje=$_SESSION["error"];
?>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>estilos.css">
    <!-- Bootstrap CSS y datatables -->



<body> 
<div class="container d-flex flex-column justify-content-center align-items-center">
		<div class="container-c"> 
			<div class="wrap-error"> 
                <h1 style="text-align: center;">ERROR</h1>
                <h2 style="text-align: center;"><?php echo $mensaje ?></h2><br>
				<div class="container-volver">
					<a href="<?php echo ROOT_PATH; ?>inicio.php" >
						<img src="<?php echo ROOT_PATH; ?>public/images/reintentar.PNG" class="iconovolver" alt="" srcset="">
					</a>
				</div>
			</div> 
		</div> 
</div>	
</body> 