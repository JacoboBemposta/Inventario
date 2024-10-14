<?php 
@session_start();
    if(isset($_SESSION["error"])) $mensaje=$_SESSION["error"];
    if(isset($_SESSION["error"])){ 
    ?>
        <div class="container d-flex flex-column align-items-center justify-content-center bg-danger align-test-center" style="max-width:20vw ;height: 10vh;" >
            
            <h1><?php echo $_SESSION["error"]?></h1>
        </div>    
        <?php
    }unset($_SESSION["error"]); ?>