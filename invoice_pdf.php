<?php
require './config.php';
require ROOT_PATH.'/TCPDF/tcpdf.php';

$item = false;

if (isset($_GET['id'])) {
    $db = new DB('invoices i');
    $item = $db->join('orders o', 'i.order_id=o.id')
        ->join('users u', 'o.user_id = u.id')
        ->join('technicians t', 'o.technician_id = t.id')
        ->join('devices d', 'o.device_id = d.id')
        ->select("i.*,o.description,d.name,d.location,
        CONCAT(u.first_name, ' ',u.last_name) as full_name,
        CONCAT(t.first_name, ' ',t.last_name) as tech_name,t.address")
        ->where("i.id", $_GET['id'])
        ->getOne();
    $admin_name = (new DB('admin'))
        ->select("CONCAT(first_name, ' ',last_name) as full_name")
        ->getOne()['full_name'];
    $db->update(['is_read' => '1'], $_GET['id']);
}

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

//$headers = $_POST['headers'];
//$data = $_POST['data'];

$pdf->setHeaderFont(array('aefurat', '', 10));
$pdf->setFooterFont(array('dejavusans', '', PDF_FONT_SIZE_DATA));

//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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
//$pdf->ColoredTable($headers, $data);

$htmlRight=' <h2>الفاتورة</h2><span># INV-001001</span>';
$htmlRight.='<p>المبلغ المستحق : '.$item['amount'].' ريال سعودي</p>';
$htmlRight.='<p>تاريخ الفاتورة : '.date('d/m/Y', strtotime($item['created_at'])).' </p>';
$htmlRight.='<p>الشروط : '.'مستحقة عند الاستلام'.' </p>';
$htmlRight.='<p>تاريخ الاستحقاق : '.date('d/m/Y', strtotime($item['due_date'])).' </p>';

$pdf->writeHTMLCell(110,50,'','',$htmlRight);

$htmlLeft='<dl> <dt> <h3>مـن: </h3> </dt>';
$htmlLeft.='<dd>'.$item['tech_name'].' </dd>';
$htmlLeft.='<dd>'.$item['address'].' </dd></dl>';

$htmlLeft.=' <dl><dt><h3> لـ: </h3></dt>';
$htmlLeft.='<dd>'.$admin_name.' </dd></dl>';

$pdf->writeHTMLCell('', 50,'','',$htmlLeft);
$pdf->Ln();
$pdf->setTopMargin(120);

$pdf->SetDrawColor(222, 226, 230);
$pdf->Cell(25,10,'الطلب',border: 'B');
$pdf->Cell('',10,'# '.$item['order_id'],border: 'B');

$pdf->Ln();
$pdf->Cell(25,10,'المستخدم',border: 'B');
$pdf->Cell('',10,$item['full_name'],border: 'B');

$pdf->Ln();
$pdf->Cell(25,10,'الجهاز',border: 'B');
$pdf->Cell('',10,$item['name'],border: 'B');


$pdf->Ln();
$pdf->Cell(25,10,'الموقع',border: 'B');
$pdf->Cell('',10,$item['location'],border: 'B');

$pdf->Ln();
$pdf->Cell(25,10,'المشكلة',border: 'B');
$pdf->Cell('',10,$item['description'],border: 'B');


header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="report.pdf"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$pdf->Output('report.pdf', 'I'); // 'D' تعني تحميل الملف

?>
