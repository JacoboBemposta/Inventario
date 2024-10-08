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
$posicion_inicial=round(fmod($_SESSION["posicion"],3));

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
        
        $pdf->Image(RAIZ_PATH.'public/images/sigalogo.png', $pdf->SetX($pdf->GetX()+1), $pdf->SetY($pdf->GetY()+1), 11);
        // $x=$pdf->getX(); para color a la derecha del logo.
        // $pdf->setX(50);
        $pdf->Ln(1);
        $pdf->Cell(30, 9, $bien['codigo'].$bien['tipo_bien'].$bien['fecha_alta'], 0, 1, 'L');
    
        // Obtener la posición Y actual para que la imagen se alinee con el texto
        $y = $pdf->GetY();
        
        // Volver a la posición Y anterior (para que no se mueva el cursor)
        $pdf->SetY($y - 25); // Ajusta el valor según sea necesario
        $x=$pdf->getX();
        // Posicionar la imagen a la derecha
        $pdf->SetX($x+25); // Ajusta el valor de X según el ancho de la página y la posición deseada
        $pdf->Image($filename, $pdf->GetX(), $pdf->GetY(), 15); // Ajusta el ancho y altura según sea necesario
        
        // Nueva línea para el siguiente texto
        $pdf->Ln(40);
    }
    
}
    
  
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="documento.pdf"');
    $pdf->Output('I');
?>
