<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function enviarCorreoRecuperacion($email, $asunto, $mensaje) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'jacobo.bemposta@gmail.com';
        $mail->Password = 'mcnj hlfq bvqw xjrg';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->SMTPDebug = 3;
        $mail->Debugoutput = 'html';
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        // Configuración del correo
        $mail->setFrom('no-reply@siga.com', 'No Reply');
        $mail->addAddress($email);
        
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        $mail->send();
        
        return true;
    } catch (Exception $e) {
        echo($mail->ErrorInfo);die;
        return false;
    }
}