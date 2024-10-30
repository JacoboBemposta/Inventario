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
                    <th style="text-align: center; padding: 10px;min-height:7vh;min-width:7vw">Cuenta</th>
                    <th style="text-align: center; padding: 10px;min-height:7vh;min-width:7vw">Tipo de bien</th>
                    <th style="text-align: center; padding: 10px;min-height:7vh;min-width:7vw">Código</th>
                    <th style="text-align: center; padding: 10px;min-height:7vh;min-width:7vw">Fecha de compra</th>
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
   
        foreach ($tipo_bienes as $key => $value) {
            if($bien["tipo_bien"] == $key) $tipo_bien=$value;
        }

        //posible campo adicional departamento
        $departamento = "";
    
        foreach ($departamentos as $key => $value) {
            if($bien["departamento"] == $key) $departamento=$value;
        }        
        //posible campo adicional centro
        $centro = "";
    
        foreach ($centros as $key => $value) {
            if($bien["centro"] == $key) $centro=$value;
        }        
        
        $estado = $bien["estado"] === '1' ? "Activo" : "Inactivo";

        $fechaAlta = $bien["fecha_alta"];
        $fechaFormateada = date("d/m/Y",strtotime($fechaAlta));
        

        $html .= '<tr>
            <td style="text-align: center; padding: 10px;min-height:7vh;min-width:7vw">' . $bien['cuenta_contable'] . '</td>
            <td style="text-align: center; padding: 10px;min-height:7vh;min-width:7vw">' . $tipo_bien . '</td>
            <td style="text-align: center; padding: 10px;min-height:7vh;min-width:7vw">' . $nombre . '</td>
            <td style="text-align: center; padding: 10px;min-height:7vh;min-width:7vw">' . $fechaFormateada . '</td>
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
