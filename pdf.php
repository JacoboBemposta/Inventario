<?php
@session_start();
include 'config/config.php';
//require('fpdf/fpdf.php');
//require('fpdf/html2pdf.php');
//require('fpdf/pdf.php');

//require('WriteHTML.php');
if(!isset($_SESSION["login"]) || ($_SESSION["login"]=="Invitado")){
    header("Location: ".ROOT_PATH)."inicio.php";
}
// Obtiene las entradas desde la sesión o define un arreglo vacío si no existen
$entradas = isset($_SESSION['entradas']) ? $_SESSION['entradas'] : [];

// function extraerContenidoODT($archivoODT) {

//     $zip = new ZipArchive;

//     if ($zip->open($archivoODT) === TRUE) {
//         // Leemos el archivo content.xml dentro del .odt
//         $contenidoXML = $zip->getFromName('content.xml');
//         $zip->close();
//         return $contenidoXML;
//     } else {
//         return false;
//     }
// }

// function parsearContenidoODT($contenidoXML) {
//     // Convertimos el XML en un objeto SimpleXML
//     $xml = simplexml_load_string($contenidoXML);
//     $texto = [];

//     // Navegamos en el XML para extraer los párrafos de texto
//     foreach ($xml->xpath('//text:p') as $parrafo) {
//         $texto[] = (string) $parrafo;
//     }

//     return $texto;
// }
// ?????????????????????????????????????????????????????



// Ejemplo de uso
// $contenido = extraerContenidoODT(ROOT_PATH.'entradas.odt');
// if ($contenido) {
//     $textoPlantilla = parsearContenidoODT($contenido);
//     print_r($textoPlantilla);  // Ver el texto extraído
// } else {
//     echo "Error al abrir el archivo ODT.";
// }
// $pdf = new PDF();
// $pdf->AddPage();

// // Agregar el texto de la plantilla extraída
// if ($contenido) {
//     $textoPlantilla = parsearContenidoODT($contenido);
//     $pdf->agregarTextoPlantilla($textoPlantilla);
// } else {
//     echo "No se pudo extraer contenido de la plantilla.";
// }

// // Crear la tabla
// $header = array('Columna 1', 'Columna 2', 'Columna 3');
// $data = array(
//     array('1', '2', '3'),
//     array('4', '5', '6'),
//     array('7', '8', '9')
// );
// $pdf->crearTabla($header, $data);

// // Guardar el archivo PDF
// $pdf->Output('F', ROOT_PATH.'tabla_generada.pdf');

//?????????????????????????????????????????



// $pdf = new PDF_HTML();
// //$pdf->Image('/public/images/siga.png',null, null, 180);
// //$pdf->Image('/public/images/siga.png' , 1 ,1, 5 , 5,'png');
// $pdf->AliasNbPages();
// $pdf->SetAutoPageBreak(true, 15);
// $pdf->AddPage();

// // Cabecera del PDF
// $pdf->SetFont('Arial','B',15);
// $pdf->WriteHTML('<h1>Inventario Siga</h1></para><br><br>');

// // Cabecera de la tabla
// $pdf->SetFont('Arial','B',8); 
// $htmlTable = '<table border="1" cellpadding="5">
//     <thead>
//         <tr>
//             <th>Cuenta facturación</th>
//             <th>Descripción</th>
//             <th>Fecha de Compra</th>
//             <th>Precio</th>
//             <th>Número de bienes</th>
//         </tr>
//     </thead>
//     <tbody>';

// // Itera sobre las entradas y genera las filas de la tabla
// foreach($entradas as $entrada) {
//     $numBienes = isset($_SESSION['bienesPorEntrada'][$entrada["id"]]) ? sizeof($_SESSION['bienesPorEntrada'][$entrada["id"]]) : 0;
//     $htmlTable .= '<tr>
//         <td>' . $entrada["cuenta_contable"] . '</td>
//         <td>' . $entrada['descripcion'] . '</td>
//         <td>' . $entrada['fecha_compra'] . '</td>
//         <td>' . $entrada['precio'] . '</td>
//         <td>' . $numBienes . '</td>
//     </tr>';
// }

// $htmlTable .= '</tbody></table>';

// // Escribe la tabla HTML en el PDF
// $pdf->WriteHTML($htmlTable);
// $pdf->Cell(1,11,'Titulo',1,true);
// // Salida del archivo PDF
// $pdf->Output();

require('fpdf/fpdf.php');

$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'¡Mi primera página pdf con FPDF!');
$pdf->Output();
?>



