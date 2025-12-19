<?php
require 'config.php';
require 'TCPDF/tcpdf.php';

class MYPDF extends TCPDF
{

    // Load table data from file
    public function LoadData($file)
    {
        // Read file lines
        $lines = file($file);
        $data = array();
        foreach ($lines as $line) {
            $data[] = explode(';', chop($line));
        }
        return $data;
    }

    //Page header
    public function Header()
    {

        // Logo
        $image_file = './logo.png';
        $this->Image($image_file, x: null, y: 7, w: 15, h: '', type: 'PNG');
        // Set font
        $this->SetFont('aealarabiya', 'B', 20);
        // Title
        $this->setRightMargin(35);
        $this->writeHTMLCell(w: 30, h: 15, x: 35, y: 5, html: '<span color="#0000ff">فيكس برو</span>');
        $this->Ln(8);
        $this->SetFont('aealarabiya', '', 12);
        $this->Cell(w: 30, h: 5, txt: 'لصيانة الأجهزة الإلكترونية', ln: 1, align: 'R', fill: 0, valign: 'T');
        $this->Cell(w: 30, h: 5, txt: 'www.fixpro.com', ln: 1, align: 'R', fill: 0, valign: 'T');
        $this->setMargins(10, 0);
        $this->Ln();
        $this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $this->Cell(w: '', h: '', border: 'T');
        $this->setTopMargin(35);
    }

    // Colored table
    public function ColoredTable($header, $data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);

        // Header
        $w = array(22, 32, 32, 22, 22, 23, 25);
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 10, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach ($data as $row) {
            for ($i = 0; $i < count($row); $i++) {
                $this->Cell($w[$i], 6, $row[$i], 'LR', 0, 'R', $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$headers = $_POST['headers'];
$data = $_POST['data'];

$pdf->setHeaderFont(array('aefurat', '', 10));
$pdf->setFooterFont(array('dejavusans', '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set some language dependent data:
$lg = array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'fa';
$lg['w_page'] = 'page';

// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);
$pdf->SetFont('dejavusans', '', 10, '', true);
$pdf->AddPage();

// print colored table
$pdf->ColoredTable($headers, $data);

$pdf_file = 'Report.pdf';

$pdf->Output(__DIR__ . '/' . $pdf_file, 'F');

$pdf_url = BASE_PATH($pdf_file);
echo $pdf_url;

?>
