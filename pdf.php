<?php 
@session_start();
$bienes=$_SESSION["bienes"];
require 'config/config.php';
require('libs/fpdf/fpdf.php');
require QR_PATH.'qrlib.php';
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:/xampp/htdocs/proyects/inventario/libs/fpdf/font');

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
$pdf->SetFont('Arial', '', 12);
//$pdf->Image('public/images/sigalogo.png');

// Definición de la cabecera de la tabla
//$pdf->SetFillColor(255, 37, 48); // Color de fondo para la cabecera
//$pdt->Cell(ancho, alto, $txt, borde, posicion (0 la derecha,1 debajo,2 sigiuente linea), $align, fondo);
// $pdf->Cell(40, 10, 'Precio', 1, 0, 'C', true);
// $pdf->Cell(40, 10, 'Centro', 1, 0, 'C', true);
// $pdf->Cell(40, 10, 'Departamento', 1, 0, 'C', true);
// $pdf->Cell(40, 10, 'Tipo de Bien', 1, 0, 'C', true);
// $pdf->Cell(40, 10, 'Fecha Alta', 1, 1, 'C', true); // Salto de línea al final

// Configuración de la fuente para el contenido

// Iterar sobre los bienes para imprimir cada fila
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

    $pdf->Cell(100, 15, $bien['codigo'].$bien['tipo_bien'].$bien['fecha_alta'], 0, 1, 'L');

    // Obtener la posición Y actual para que la imagen se alinee con el texto
    $y = $pdf->GetY();
    
    // Volver a la posición Y anterior (para que no se mueva el cursor)
    $pdf->SetY($y - 10); // Ajusta el valor según sea necesario
    
    // Posicionar la imagen a la derecha
    $pdf->SetX(120); // Ajusta el valor de X según el ancho de la página y la posición deseada
    $pdf->Image($filename, $pdf->GetX(), $pdf->GetY(), 20); // Ajusta el ancho y altura según sea necesario
    
    // Nueva línea para el siguiente texto
    $pdf->Ln(10);
    
    // Celda de texto adicional
    $pdf->Cell(100, 15, $bien['centro'], 0, 1, 'L');
    //$pdf->Image($filename);
    $pdf->Ln(10);
}
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="documento.pdf"');

    $pdf->Output('I');
?>
