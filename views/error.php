<?php
@session_start();
if (isset($_SESSION["error"])) {
    $mensaje = $_SESSION["error"];
    unset($_SESSION["error"]);
}
if (isset($mensaje)) {
?>
    <!-- mensaje de error en la cabecera de la página -->
    <div class="container d-flex flex-column align-items-center justify-content-center bg-danger align-test-center" style="max-width:20vw ;height: 10vh;">

        <h1><?php echo $mensaje ?></h1>
    </div>
<?php
} ?>