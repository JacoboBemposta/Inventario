<?php 
@session_start();
$bienes=$_SESSION["bienes"];
require 'config/config.php';
require('libs/fpdf/fpdf.php');
require QR_PATH.'qrlib.php';
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:/xampp/htdocs/proyects/inventario/libs/fpdf/font');
// 1.1 cm de margen superior y lateral. 
// Cada etiqueta de 7 cm de largo y 2.5 cm de ancho
// 3 etiquetas por fila de un total de 11 filas

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Tabla generada con FPDF', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }

    function agregarTextoPlantilla($textoPlantilla) {
        $this->SetFont('Arial', '', 12);
        foreach ($textoPlantilla as $linea) {
            $this->Cell(0, 10, ($linea), 0, 1);
        }
        $this->Ln(10); // Espacio antes de la tabla
    }

    function crearTabla($header, $data) {
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

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 4);


    // // Obtener la posición desde la variable de sesión (número secuencial)
    // $posicion = $_SESSION["posicion"]; // Número de casilla (por ejemplo, 5)
    // $columnas=3;
    // // Calcular fila y columna a partir de la posición
    // $fila = floor(($posicion - 1) / $columnas) + 1;   // Fila en la cuadrícula
    // $columna = (($posicion - 1) % $columnas) + 1;     // Columna en la cuadrícula

    // // Calcular coordenadas X e Y para la etiqueta
    // $x = ($columna - 1) * ($ancho_etiqueta + $margen_x) + $margen_inicial_x; // Posición X
    // $y = ($fila - 1) * ($alto_etiqueta + $margen_y) + $margen_inicial_y;     // Posición Y

for ($i=1; $i <= 11; $i++) { 
    foreach ($_SESSION["bienes"] as $bien) {

        if(isset($_SESSION["bienes"])) $nombre=$bien["codigo"];
        else $nombre="test";
        if(!file_exists(TEMP_PATH)) mkdir(TEMP_PATH);
        $filename = TEMP_PATH.$nombre.'.png';
        $tamanho=2; // tamaño de la imagen
        $level='H'; // tipo de precision (baja l, media M, alta Q ,maxima H)
        $framesize=3; //marco del qr en blanco
        $contenido=$bien["descripcion"];
        
        QRcode::png($contenido,$filename,$level,$tamanho,$framesize);
        $pdf->setX(1);
        $pdf->SetY($pdf->GetY() + 1);
        $pdf->Image(RAIZ_PATH.'public/images/sigalogo.png', $pdf->GetX(), $pdf->GetY(), 20);
        $pdf->Ln(10); // Ajusta la distancia entre el logo y el texto
        $pdf->Cell(30, 9, $bien['codigo'].' '.$bien['tipo_bien'].' '.$bien['fecha_alta'], 0, 1, 'L');
    
        // Obtener la posición Y actual (después del texto)
        $y = $pdf->GetY();
        
        // Ajustar la posición del QR code debajo del texto, con espacio de 5 unidades
        $pdf->SetY($y - 20);
        $x = $pdf->GetX();
        
        // Posicionar el QR code a la derecha del texto
        $pdf->SetX($x + 30); // Ajustar el valor según sea necesario
        $pdf->Image($filename, $pdf->GetX(), $pdf->GetY(), 15); // Tamaño del QR code
        
        // Nueva línea para el siguiente bien
        $pdf->Ln(20);
    }
    
}
    
  
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="documento.pdf"');
    $pdf->Output('I');
?>
