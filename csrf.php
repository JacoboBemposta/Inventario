<?php 
@session_start();

// 1. Generar Token CSRF
function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// 2. Validar Token CSRF
function validarTokenCSRF($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// 3. Destruir Token CSRF después de usarlo
function destruirTokenCSRF() {
    unset($_SESSION['csrf_token']);
}

?>