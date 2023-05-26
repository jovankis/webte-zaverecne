<?php
require_once('tcpdf/tcpdf.php');

// Get the content of the tutorial page from the URL
$content = file_get_contents('https://site119.webte.fei.stuba.sk/zaverecnezadanie/teacher/tutorial.php');

$content = preg_replace('/<nav class="navbar navbar-expand-lg navbar-dark bg-dark">(.*?)<\/nav>/s', '', $content);
$content = preg_replace('/<a id="download-tutorial-button" class="btn btn-primary"(.*?)<\/a>/s', '', $content);

// Create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

// Set document information
$pdf->SetCreator('Your Website');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Tutorial - Math Website');
$pdf->SetSubject('Tutorial');

// Add a page
$pdf->AddPage();

// Set the font and size
$pdf->SetFont('helvetica', '', 12);

// Write the content to the PDF
$pdf->writeHTML($content);

// Output the PDF as a file
$pdf->Output('tutorial.pdf', 'D');
?>




