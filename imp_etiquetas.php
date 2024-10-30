<?php
@session_start();

$bienes = $_SESSION["bienes"];
require 'config/config.php';
require_once 'libs/fpdf/fpdf.php';
require_once 'libs/phpqrcode/qrlib.php';
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:/xampp/htdocs/proyects/inventario/libs/fpdf/font');
// 1.1 cm de margen superior y lateral. 
// Cada etiqueta de 7 cm de largo y 2.5 cm de ancho
// 3 etiquetas por fila de un total de 11 filas

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Tabla generada con FPDF', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }

    function agregarTextoPlantilla($textoPlantilla)
    {
        $this->SetFont('Arial', '', 12);
        foreach ($textoPlantilla as $linea) {
            $this->Cell(0, 10, ($linea), 0, 1);
        }
        $this->Ln(10); // Espacio antes de la tabla
    }

    function crearTabla($header, $data)
    {
        // Ancho de las columnas
        $w = array(40, 40, 40);

        // Cabecera
        $this->SetFont('Arial', 'B', 12);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1);
        $this->Ln();

        // Datos
        $this->SetFont('Arial', '', 12);
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell($w[0], 6, $col, 1);
            }
            $this->Ln();
        }
    }
}

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);
$n_bien = 0;

$columnas = 3;
$ancho = 70;
$alto = 25;



foreach ($_SESSION["bienes"] as $bien) {

    //Dar formato al codigo 
    $contador = intval($bien["id"]);
    if ($contador > 9999) $contador = $contador - 9999;

    $codigo = str_pad($contador, 4, '0', STR_PAD_LEFT); // Añadir ceros a la izquierda para completar 4 dígitos

    if (isset($_SESSION["bienes"])) $nombre = $bien['centro'] . $bien['departamento'] .$bien['tipo_bien']. $codigo;
    else $nombre = "test";



    //Información que será visible en el QR
    
    $tipo_bien = '';
   
    foreach ($tipo_bienes as $key => $value) {
        if($bien["tipo_bien"] == $key) $tipo_bien=$value;
    }

    $departamento = "";

    foreach ($departamentos as $key => $value) {
        if($bien["departamento"] == $key) $departamento=$value;
    }        

    $centro = "";

    foreach ($centros as $key => $value) {
        if($bien["centro"] == $key) $centro=$value;
    }        

    $fechaCompra = $bien["fecha_compra"];

    // Formateo de fechas
    $date = DateTime::createFromFormat('Y-m-d', $fechaCompra);
    if ($date) {
        $fechaFormateada = $date->format('d/m/Y');
    } else {
        $fechaFormateada = 'Fecha no válida'; 
    }

    $contenido = "";
    $contenido .= $bien["nombre"] . "\n";
    $contenido .= $bien["descripcion"] . "\n";
    $contenido .= $bien["cuenta_contable"] . "\n";
    $contenido .= $bien['centro'] . ' ' . $bien['departamento'] . ' ' .$bien['tipo_bien'].' '. $codigo . "\n";
    $contenido .= $fechaFormateada. "\n";
    // $contenido .= "Fecha de inicio amortizacion: " . $bien["fecha_inicio_amortizacion"]."\n" ;
    // $contenido .= "Precio: " . $bien["precio"]."\n" ;
    // $contenido .= "Porcentaje de amortizacion: " . $bien["porcentaje_amortizacion"]."\n" ;
    // $contenido .= "Centro: " . $centro."\n" ;
    // $contenido .= "Tipo de bien: " . $tipo_bien."\n" ;
    // $contenido .= "Departamento : " . $departamento."\n" ;
    // $contenido .= "Factua: " . $bien["numero_factura"]."\n" ;
    // $contenido .= "Fecha de baja: " . $bien["fecha_baja"]."\n" ;
    // $contenido .= "Causa de baja: " . $bien["causa_baja"]."\n" ;


    // // Obtener la posición desde la variable de sesión (número secuencial)
    $posicion_inicial = intval($_SESSION["posicion"]); // Posición inicial
    $posicion_actual = $posicion_inicial + $n_bien; //posicion actual
    $n_bien = $n_bien + 1;



    // Calcular fila y columna a partir de la posición
    $fila = floor(($posicion_actual - 1) / $columnas) + 1;
    $columna = (($posicion_actual - 1) % $columnas) + 1;

    // // Calcular coordenadas X e Y para la etiqueta

    $x = (($columna - 1) + 0.1) * $ancho;
    $y = ($fila - 1) * $alto;
    //llamada a la función que genera el codigo QR en el pdf

    //Comprobacion si existe la carpeta temporal para guardar las imagenes QR
    if (!file_exists(TEMP_PATH)) mkdir(TEMP_PATH);
    //Generamos el archivo
    $filename = TEMP_PATH . $nombre . '.png';
    $tamanho = 2; // tamaño de la imagen
    $level = 'H'; // tipo de precision (baja l, media M, alta Q ,maxima H)
    $framesize = 3; //marco del qr en blanco
    QRcode::png($contenido, $filename, $level, $tamanho, $framesize);

    //posicion del sigalogo
    $pdf->SetXY($x + 2, $y + 13.5);
    $pdf->Image(RAIZ_PATH . 'public/images/sigalogo.png', $pdf->GetX(), $pdf->GetY(), 15);
    //posicion del texto
    $pdf->SetY($pdf->GetY() + 8); // Ajusta la distancia entre el logo y el texto
    $pdf->setX($x);
    //echo $codigo."<br>";
    $pdf->Cell(25, 5, $bien['centro'] . ' ' . $bien['departamento'] . ' ' . $bien['tipo_bien'] . ' ' . $codigo, 0, 1, 'L');

    // posicion del codigo QR
    $y = $pdf->GetY();
    $pdf->SetY($y - 15); //$pdf->SetY($y - 15);


    $pdf->SetX($x + 30);

    // Posicion del codigo QR a la derecha del texto
    $pdf->Image($filename, $pdf->GetX(), $pdf->GetY(), 15); // Tamaño del QR code
    // Nueva línea para el siguiente bien
    if ($posicion_actual > 32) {
        $n_bien = 0;
        $_SESSION["posicion"] = 1; // Reiniciar la posición para la nueva página
        $pdf->AddPage();
    }
}



header('Content-Type: text/html; charset=utf-8');
header('Content-Disposition: inline; filename="documento.pdf"');
$pdf->Output('I');
