<?php 
@session_start();
$bienes=$_SESSION["bienes"];
require 'config/config.php';
require('libs/fpdf/fpdf.php');
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
$pdf->Image('public/images/sigalogo.png');

// Definición de la cabecera de la tabla
$pdf->SetFillColor(255, 37, 48); // Color de fondo para la cabecera
$pdf->Cell(40, 10, 'Precio', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Centro', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Departamento', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Tipo de Bien', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Fecha Alta', 1, 1, 'C', true); // Salto de línea al final

// Configuración de la fuente para el contenido
$pdf->SetFont('Arial', '', 10);

// Iterar sobre los bienes para imprimir cada fila
foreach ($bienes as $bien) {
    $pdf->Cell(40, 10, $bien['precio'], 1, 0, 'C');
    $pdf->Cell(40, 10, $bien['centro'], 1, 0, 'C');
    $pdf->Cell(40, 10, $bien['departamento'], 1, 0, 'C');
    $pdf->Cell(40, 10, $bien['tipo_bien'], 1, 0, 'C');
    $pdf->Cell(40, 10, $bien['fecha_alta'], 1, 1, 'C'); // Salto de línea
}
$pdf->Output();


?>
