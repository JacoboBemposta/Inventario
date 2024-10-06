<?php 
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

$pdf=new FPDF();
$pdf->AddPage();
$pdf->AddFont('Times', '', 'libs/fpdf/font/times.php');
$pdf->SetFont('Times','',12,);
$pdf->Cell(40,10,'�Hola, Mundo!');
$pdf->Image('public/images/sigalogo.png');


$pdf->Output();


?>
