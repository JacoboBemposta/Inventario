<?php
require 'config/config.php';
require './vendor/autoload.php'; // Cargar autoload de Composer

if (isset($_SESSION["bienes"])) $bienes = $_SESSION["bienes"];

// Iniciar el manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Dompdf\Dompdf;

// Crear una nueva instancia de Dompdf


$dompdf = new Dompdf();

$path1 = "public/images/sigafooter.png";
$data1 = file_get_contents($path1);
$type1 = pathinfo($path1, PATHINFO_EXTENSION);
$base64 = 'data:image/' . $type1 . ';base64,' . base64_encode($data1);

$path2 = "public/images/sigalogo.png";
$data2 = file_get_contents($path2);
$type2 = pathinfo($path2, PATHINFO_EXTENSION);
$base65 = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);

// Verificar que la variable $bienes no esté vacía
if (empty($bienes)) {
    $html = '<h1>No hay bienes disponibles.</h1>';
} else {

    // Construir el HTML
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Lista de Bienes</title>      
        <style>
            body { font-family: Arial, sans-serif; }
            
            /* Estilos del header */
            .header {
                background-color: #861636; 
                color: white; 
                font-weight: bold;
                padding: 15px;
                display: flex;
                justify-content: space-between;
                align-items: center; 
                position: relative;
            }

            .header img {
                width: 100px; /* Ajustar el tamaño del logo */
                height: auto;
                margin-right: 15px; 
                margin-top: 15px; 
            }

            .header h1 {
                flex-grow: 1;
                text-align: center; /* Centrar el texto del header */
                margin-top: 5px;
                vertical-align: middle;
                position: absolute;
                margin-left: 20%;
                padding-bottom: 5%;
            }

            /* Estilos de la tabla */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top : 50px;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            /* Footer siempre en la parte inferior */
            footer {
                background-color: #454545;
                color: #ffffff;
                font-size: 12px;
                padding: 10px;
                position: fixed;
                bottom: 0;
                width: 100%;
            }

            /* Estilos para el contenido del footer */
            .footer-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            footer img {
                width: 50px;
                height: auto;
                position: absolute;
                margin-top:15px;
                margin-left:15px;
            }

            .footer-text {
                flex-grow: 1;
                text-align: center; 
            }

            footer p {
                margin: 5px 0;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="' . $base65 . '" alt="Logo Siga Header">
            <h1>Lista de Bienes</h1>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Cuenta</th>
                    <th>Tipo de bien</th>
                    <th>Código de la etiqueta</th>
                    <th>Fecha de compra</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($bienes as $bien) {

        $contador = intval($bien["id"]);
        if ($contador > 9999) $contador = $contador - 9999;
    
        $codigo = str_pad($contador, 4, '0', STR_PAD_LEFT); // Añadir ceros a la izquierda para completar 4 dígitos
    
        if (isset($_SESSION["bienes"])) $nombre = $bien['centro'] .' '. $bien['departamento'] .' '.$bien['tipo_bien'].' '. $codigo;
        else $nombre = "test";
        
        
        $tipo_bien = '';
        switch ($bien["tipo_bien"]) {
            case 'ME':
                $tipo_bien = "Mesa";
                break;
            case 'SI':
                $tipo_bien = "Silla";
                break;
            case 'AR':
                $tipo_bien = "Armario";
                break;
            case 'ES':
                $tipo_bien = "Estantería";
                break;
            case 'BU':
                $tipo_bien = "Buck";
                break;
            case 'PI':
                $tipo_bien = "Pizarra";
                break;
            case 'IM':
                $tipo_bien = "Impresora";
                break;
            case 'SP':
                $tipo_bien = "Soporte PC";
                break;
            case 'BA':
                $tipo_bien = "Bandeja";
                break;
            case 'PE':
                $tipo_bien = "Perchero";
                break;
            case 'PA':
                $tipo_bien = "Papelera";
                break;
            case 'RE':
                $tipo_bien = "Reposapiés";
                break;
            case 'EX':
                $tipo_bien = "Extintor";
                break;
            case 'DE':
                $tipo_bien = "Destructora";
                break;
            case 'CI':
                $tipo_bien = "Cizalla";
                break;
            case 'AI':
                $tipo_bien = "Equipo Aire";
                break;
            case 'RU':
                $tipo_bien = "Roll up";
                break;
            case 'LA':
                $tipo_bien = "Lámpara";
                break;
            case 'EC':
                $tipo_bien = "Escalera";
                break;
            case 'AL':
                $tipo_bien = "Alfombra";
                break;
            case 'TV':
                $tipo_bien = "Televisión";
                break;
            case 'WC':
                $tipo_bien = "Webcam";
                break;
            case 'OR':
                $tipo_bien = "Ordenador";
                break;
            case 'MO':
                $tipo_bien = "Monitor";
                break;
            case 'VC':
                $tipo_bien = "Policom";
                break;
            case 'SC':
                $tipo_bien = "Scanner";
                break;
            case 'IP':
                $tipo_bien = "Ipad";
                break;
            case 'PU':
                $tipo_bien = "Puntero";
                break;
            case 'FU':
                $tipo_bien = "Funda";
                break;
            default:
                $tipo_bien = "Bien sin identificar";
                break;
        }

        $fechaAlta = $bien["fecha_alta"];

        // Formateo de fechas
        $date = DateTime::createFromFormat('Y-m-d', $fechaAlta);
        if ($date) {
            $fechaFormateada = $date->format('d/m/Y');
        } else {
            $fechaFormateada = 'Fecha no válida'; 
        }
        

        $html .= '<tr>
            <td>' . $bien['cuenta_contable'] . '</td>
            <td>' . $tipo_bien . '</td>
            <td>' . $nombre . '</td>
            <td>' . $fechaFormateada . '</td>
        </tr>';
    }

    $html .= '</tbody></table>
    <footer>
        <div class="footer-content">
            <div><img src="' . $base64 . '" alt="Logo Siga Footer"></div>
            <div class="footer-text">
                <p>atencioncliente@gestores.net</p>
                <p>986 866 171</p>
                <p>Calle Pedro Sarmiento de Gamboa, nº 12 - bajo, 36003 Pontevedra</p>
            </div>
        </div>
    </footer>
    </body>
    </html>';
}

$dompdf->loadHtml($html);

// (Opcional) Configurar el tamaño del papel y la orientación
$dompdf->setPaper('A4', 'portrait');

// Renderizar el HTML como PDF
$dompdf->render();

// Salida del PDF al navegador
$dompdf->stream("lista de bienes", array("Attachment" => true));
