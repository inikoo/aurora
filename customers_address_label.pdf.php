<?php
require_once('common.php');
require_once('class.Customer.php');

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

require_once('external_libs/PDF/config/lang/eng.php');
require_once('external_libs/PDF/tcpdf.php');



// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Inikoo');
$pdf->SetTitle('Customer List Adresses');
$pdf->SetSubject('Address Labels');



$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


$labels_data=array(
'l7159'=>array(
        'PDF_MARGIN_TOP'=>12.9,
         'PDF_MARGIN_LEFT'=>7,
         'PDF_MARGIN_RIGHT'=>7,
           'PDF_MARGIN_BOTTOM'=>12.9,
         'CELL_MARGIN_RIGHT'=>2,
          'CELL_MARGIN_LEFT'=>0,
           'CELL_MARGIN_TOP'=>0,
            'CELL_MARGIN_BOTTOM'=>0,
            'CELL_WIDTH'=>64,
            'CELL_HEIGHT'=>33.9,
            'COLUMNS'=>3,
            'ROWS'=>8
         )
);

$label_data=$labels_data['l7159'];

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins($label_data['PDF_MARGIN_LEFT'], $label_data['PDF_MARGIN_TOP'], $label_data['PDF_MARGIN_RIGHT']);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, $label_data['PDF_MARGIN_BOTTOM']);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 10);

// add a page
$pdf->AddPage();

// set cell padding
$pdf->setCellPaddings(1, 1, 1, 1);

// set cell margins
$pdf->setCellMargins($label_data['CELL_MARGIN_LEFT'], $label_data['CELL_MARGIN_TOP'], $label_data['CELL_MARGIN_RIGHT'], $label_data['CELL_MARGIN_BOTTOM']);

// set color for background
$pdf->SetFillColor(255, 255, 255);

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)



$sql=mysql_fetch_array(mysql_query("select `List Type` from `List Dimension` where `List Key`=$id"));
$list_type=$sql[0];
if($list_type=='Static'){

$sql=sprintf("select `Customer Key` from `List Customer Bridge` where `List Key`=%d",$id);
$res=mysql_query($sql);
$counter=0;
$labels_per_page=$label_data['COLUMNS']*$label_data['ROWS'];
while($row=mysql_fetch_assoc($res)){
$counter++;

$customer=new Customer($row['Customer Key']);
$pdf->MultiCell(
                    $label_data['CELL_WIDTH'], 
                    $label_data['CELL_HEIGHT'], 
                    $customer->display_contact_address('label'),
                    0,
                    'C',
                    
                    0,
                    (fmod($counter,$label_data['COLUMNS'])?0:1),
                    '',
                    '',
                    true,
                    
                    0,
                    false,
                    true,
                    $label_data['CELL_HEIGHT'],
                    'M',
                    
                    true);

//if(!fmod($counter,$labels_per_page)){
//$pdf->ln(10);
//}

}



}else
{

}








//$pdf->Ln(4);


// move pointer to last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_005.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+



/*
//require_once('pdf_main_customer_list.php');

81czwxxmjb


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);



//////$pdf->SetAuthor('Inikoo');
$pdf->SetTitle('Generate Customer Postal Address');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->SetFont('helvetica', '', 8);
$pdf->AddPage();
//$resolution= array(102, 255);
//$pdf->AddPage('P', $resolution);


$pdf->Write(0, '', '', 0, 'L', true, 0, false, false, 0);
$pdf->SetFont('helvetica', '', 8);
ob_clean();
ob_start();

$sql=mysql_fetch_array(mysql_query("select `List Type` from `List Dimension` where `List Key`=$id"));
$list_type=$sql[0];
if($list_type=='Static'){
include('external_libs/PDF/template_customer_list.php');
}else
{
include('external_libs/PDF/template_customer_list_dynamic.php');
}

$page1 = ob_get_contents();
ob_clean();
$page1 = preg_replace("/\s\s+/", '', $page1);
$pdf->writeHTML($page1, true, 0, true, 0);
$pdf_file_name="List Id:".$id.'.pdf';
$pdf->Output($pdf_file_name, 'I');
*/
?>
