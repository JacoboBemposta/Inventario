<?php
require 'vendor/autoload.php'; // Incluye PHPWord
require 'vendor/phpoffice/index.php';
use PhpOffice\PhpWord\IOFactory;


$plantillaDocx = '/public/plantillas/entradas.docx';

// Cargar el documento .docx
$phpWord = IOFactory::load($plantillaDocx);

// Obtener el contenido del documento
$contenido = '';

foreach ($phpWord->getSections() as $section) {
    foreach ($section->getElements() as $element) {
        if (method_exists($element, 'getText')) {
            $contenido .= $element->getText() . "\n";
        }
    }
}

require 'vendor/autoload.php'; // Incluye PHPWord y FPDF
require 'fpdf/fpdf.php'; // Asegúrate de incluir FPDF en tu proyecto



$plantillaDocx = '/public/plantillas/entradas.docx';

// Cargar el documento .docx
$phpWord = IOFactory::load($plantillaDocx);

// Obtener el contenido del documento
$contenido = '';

foreach ($phpWord->getSections() as $section) {
    foreach ($section->getElements() as $element) {
        if (method_exists($element, 'getText')) {
            $contenido .= $element->getText() . "\n";
        }
    }
}

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Agregar el contenido del documento .docx al PDF
$pdf->MultiCell(0, 10, utf8_decode($contenido));

// Dejar un espacio antes de agregar la tabla
$pdf->Ln(10);

// Agregar una tabla después del contenido
$header = array('Columna 1', 'Columna 2', 'Columna 3');
$data = array(
    array('Dato 1', 'Dato 2', 'Dato 3'),
    array('Dato 4', 'Dato 5', 'Dato 6'),
    array('Dato 7', 'Dato 8', 'Dato 9')
);

function crearTabla($pdf, $header, $data)
{
    // Imprimir el header
    foreach ($header as $col) {
        $pdf->Cell(40, 7, $col, 1);
    }
    $pdf->Ln();
    
    // Imprimir las filas
    foreach ($data as $row) {
        foreach ($row as $col) {
            $pdf->Cell(40, 6, $col, 1);
        }
        $pdf->Ln();
    }
}

// Crear la tabla
crearTabla($pdf, $header, $data);

// Guardar el archivo PDF
$pdf->Output('F', 'salida.pdf'); 
?>
