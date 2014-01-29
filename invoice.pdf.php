<?php
require_once('common.php');
require_once('class.Store.php');

require_once('class.Invoice.php');





$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$invoice=new Invoice($id);
if (!$invoice->id) {
    exit;
}
//print_r($invoice);
$store=new Store($invoice->data['Invoice Store Key']);
$customer=new Customer($invoice->data['Invoice Customer Key']);


putenv('LC_ALL='.$store->data['Store Locale'].'.UTF-8');
setlocale(LC_ALL,$store->data['Store Locale'].'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");


require_once('external_libs/pdf/config/lang/eng.php');
require_once('external_libs/pdf/tcpdf.php');


class MYPDF extends TCPDF {

    function Header() {
        if ($this->header_xobjid < 0) {
            // start a new XObject Template
            $this->header_xobjid = $this->startTemplate($this->w, $this->tMargin);
            $headerfont = $this->getHeaderFont();
            $headerdata = $this->getHeaderData();
            $this->y = $this->header_margin;
            if ($this->rtl) {
                $this->x = $this->w - $this->original_rMargin;
            } else {
                $this->x = $this->original_lMargin;
            }
            if (($headerdata['logo']) AND ($headerdata['logo'] != K_BLANK_IMAGE)) {
                $imgtype = $this->getImageFileType(K_PATH_IMAGES.$headerdata['logo']);
                if (($imgtype == 'eps') OR ($imgtype == 'ai')) {
                    $this->ImageEps(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
                }
                elseif ($imgtype == 'svg') {
                    $this->ImageSVG(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
                }
                else {
                    $this->Image(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
                }
                $imgy = $this->getImageRBY();
            } else {
                $imgy = $this->y+50;
            }
            $cell_height = round(($this->cell_height_ratio * $headerfont[2]) / $this->k, 2);
            // set starting margin for text data cell
            if ($this->getRTL()) {
                $header_x = $this->original_rMargin + ($headerdata['logo_width'] * 1.1);
            } else {
                $header_x = $this->original_lMargin + ($headerdata['logo_width'] * 1.1);
            }
            $cw = $this->w - $this->original_lMargin - $this->original_rMargin - ($headerdata['logo_width'] * 1.1) -20;
            $this->SetTextColor(0, 0, 0);
            // header title
            $this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);
            $this->SetX($header_x);
            $this->Cell($cw, $cell_height, $headerdata['title'], 0, 1, '', 0, '', 0);
            // header string
            $this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
            $this->SetX($header_x);

            $this->MultiCell($cw, $cell_height, $headerdata['string'], 0, '', 0, 1, '', '', true, 0, false, true, 0, 'T', false);

            $style = array(
                         'border' => false,
                         'vpadding' => 'auto',
                         'hpadding' => 'auto',
                         'fgcolor' => array(0,0,0),
                         'bgcolor' => false, //array(255,255,255)
                         'module_width' => 1, // width of a single module in points
                         'module_height' => 1 // height of a single module in points
                     );
            //$this->write2DBarcode('aw.inikoo.com/invoice.pdf.php?id=215167', 'QRCODE,Q', $cw, 0, 50, 50, $style, 'N');


            // print an ending header line
            $this->SetLineStyle(array('width' => 0.85 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
            $this->SetY((2.835 / $this->k) + max($imgy, $this->y));
            if ($this->rtl) {
                $this->SetX($this->original_rMargin);
            } else {
                $this->SetX($this->original_lMargin);
            }
            $this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
            $this->endTemplate();
        }
        // print header template
        $x = 0;
        $dx = 0;
        if ($this->booklet AND (($this->page % 2) == 0)) {
            // adjust margins for booklet mode
            $dx = ($this->original_lMargin - $this->original_rMargin);
        }
        if ($this->rtl) {
            $x = $this->w + $dx;
        } else {
            $x = 0 + $dx;
        }
        $this->printTemplate($this->header_xobjid, $x, 0, 0, 0, '', '', false);
        if ($this->header_xobj_autoreset) {
            // reset header xobject template at each page
            $this->header_xobjid = -1;
        }
    }
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-30);
        // Set font
        $this->SetFont('helvetica', 'I', 6);
        // Page number


$address='';

global $store;

$store_address=explode(",",$store->data['Store Address']);

foreach($store_address as $ad){
	$address.=$ad.",<br/>";
}

 
$tbl ='<table border="0" cellpadding="2" cellspacing="2" align="left" WIDTH="100%">
	<tr><th COLSPAN=6>'.$store->data['Store Company Name'].'</th></tr>

	<tr >
		<td WIDTH="20%">'.$store->data['Store Address'].'</td>	
		<td WIDTH="12%">'.$store->data['Store Company Number'].'</td>
		<td WIDTH="12%">'.$store->data['Store VAT Number'].'</td>
		<td WIDTH="16%">'.$store->data['Store Telephone'].'</td>
		<td WIDTH="20%">'.$store->data['Store Email'].'</td>
		<td WIDTH="16%">'.$store->data['Store URL'].'</td>
		
	</tr>

</table>';


$this->writeHTML($tbl, true, false, false, false, '');


        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function MultiRow($columns) {
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)

        $page_start = $this->getPage();
        $y_start = $this->GetY();

        $number_columns=count($columns);
        $i=1;
        foreach($columns as $column) {
            $this->MultiCell($column['w'], 0, $column['txt'],$column['border'] , $column['align'], 0, ($i==$number_columns?1:2), ($i==1?'':$this->GetX()), ($i==1?'':$y_start), true, 0);
            $page_end[]=$this->getPage();
            $y_end[]= $this->GetY();

            $this->setPage($page_start);
            $i++;
        }


        /*
        // see http://www.tcpdf.org/examples/example_020.phps
         // set the new row position by case
        if (max($page_end_1,$page_end_2) == $page_start) {
            $ynew = max($y_end_1, $y_end_2);
        } elseif ($page_end_1 == $page_end_2) {
            $ynew = max($y_end_1, $y_end_2);
        } elseif ($page_end_1 > $page_end_2) {
            $ynew = $y_end_1;
        } else {
            $ynew = $y_end_2;
        }
        
        
        */


        $this->setPage(max($page_end));
        $this->SetXY($this->GetX(),max($y_end));
    }



}



// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($store->data['Store Name']);
$pdf->SetTitle($invoice->data['Invoice Public ID']);
$pdf->SetSubject(_('Invoice'));

//print_r($invoice);
$header_text=_('Invoice').' '.$invoice->data['Invoice Public ID'];



//$image="../../image"
//$pdf->SetHeaderData("../../../image.php\?id=1", 100, $header_text, 'Invoice ');





$pdf->SetHeaderData(false, 0, $header_text,$store->data['Store Name']);
$pdf->set_footer_var($store->id);
//print $invoice->data['Invoice Customer Name'].$invoice->data['Invoice Public ID'];
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);


$pdf->SetFont('helvetica', '', 6, '', true);


$pdf->setCellPaddings(1,0.5,1,0.5);
// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

if ($invoice->get('Invoice Items Discount Amount')!=0)
	$discount='<tr><td width="120px"   align="right" >'._('Discounts').' :</td><td width="10px"></td><td   width="70px" align="right">'.$invoice->get('Items Discount Amount')."</td></tr>";
else
	$discount='';

if($invoice->get('Invoice Refund Net Amount')!=0){
	$credit='<tr><td width="120px"  align="right"  >'._('Credits').' :</td><td width="10px"></td><td   width="70px" align="right">'.$invoice->get('Refund Net Amount').'</td></tr>';
}
else
	$credit='';

if ($invoice->get('Invoice Charges Net Amount')!=0){
		$charges='<tr><td width="120px"  align="right"  >'._('Charges').' :</td><td width="10px"></td><td   width="70px" align="right">'.$invoice->get('Charges Net Amount').'</td></tr>';

}
else
	$charges='';

if ($invoice->get('Invoice Total Net Adjust Amount')!=0)
		$total_net='<tr><td width="120px"  align="right"  >'._('Adjust Net').' :</td><td width="10px"></td><td   width="70px" align="right">'.$invoice->get('Total Net Adjust Amount').'</td></tr>';

else
	$total_net='';

if ($invoice->get('Invoice Shipping Net Amount')!=0){
	
	$shipping_net='<tr><td width="120px"   align="right"  >'._('Shipping').' :</td><td width="10px"></td><td   width="70px" align="right">'.$invoice->get('Shipping Net Amount').'</td></tr>';

}
else
	$shipping_net='';


if ($invoice->get('Invoice Total Tax Adjust Amount')!=0){
		$total_tax='<tr><td width="120px"  align="right"  >'._('Adjust Tax').' :</td><td width="10px"></td><td   width="70px" align="right">'.$invoice->get('Total Tax Adjust Amount').'</td></tr>';

}
else
	$total_tax='';

$tax_data=array();
$sql=sprintf("select `Tax Category Name`,`Tax Category Rate`,`Tax Amount` from  `Invoice Tax Bridge` B  left join `Tax Category Dimension` T on (T.`Tax Category Code`=B.`Tax Code`)  where B.`Invoice Key`=%d ",$invoice->id);

$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
    $tax_data[]=array('name'=>$row['Tax Category Name'],'amount'=>money($row['Tax Amount'],$invoice->data['Invoice Currency']));
}
$tax_info='';
foreach($tax_data as $tax){
	$tax_info.="<tr ><td align=\"right\">{$tax['name']}</td><td  >{$tax['amount']}</td></tr>";
	$tax_info='<tr><td width="120px"  align="right"  >'._('Tax').' ('.$tax['name'].')  :</td><td width="10px"></td><td   width="70px" align="right">'.$tax['amount'].'</td></tr>';

}

//<tr><td align="right">'._('Payment Method').':</td><td >'.$invoice->data['Invoice Main Payment Method'].'</td></tr>


$invoice_info='<table border="0" width="300px" >
<tr>
<td width="90px" align="right" style="padding-left:10px">'._('Customer ID').':</td>
<td width="10px"></td>
<td width="120px">'.$customer->get_formated_id().'</td>
</tr>
<tr>
<td width="90px" align="right" style="padding-left:10px">'._('Invoice Date').':</td>
<td width="10px"></td>
<td width="120px">'.$invoice->data['Invoice Date'].'</td>
</tr>
<tr><td align="right">'._('Payment State').':</td><td width="10px"></td><td >'.$invoice->get('Payment State').'</td></tr>
</table>';

$invoice_amounts='<table border="0" width="250px">
<tr><td width="120px" align="right">'._('Items Gross').' :</td><td width="10px"></td><td   width="70px" align="right">'.$invoice->get('Items Gross Amount').'</td></tr> 

'.$discount.'
<tr><td width="120px" align="right">'._('Items Net').' :</td><td width="10px"></td> <td width="70px"  align="right">'.$invoice->get('Items Net Amount').'</td></tr>
'.$credit.'
'.$charges.'
'.$total_net.'
'.$shipping_net.'
<tr><td width="120px" align="right">'._('Total Net').' :</td><td width="10px"></td> <td width="70px"  align="right">'.$invoice->get('Total Net Amount').'</td></tr>
'.$tax_info.'
'.$total_tax.'
<tr><td width="120px" align="right"><b>'._('Total').' :</b></td><td width="10px"></td> <td width="70px"  align="right"><b>'.$invoice->get('Total Amount').'</b></td></tr>
</table>';


$html='<div style="clear:both"></div>';


$html.='<table border="0"><tr><td width="240px"><h3>'.$invoice->data['Invoice Customer Name'].'</h3> '.$invoice->data['Invoice XHTML Address'].'</td>
<td width="230px">'.$invoice_info.'</td>
<td width="250px">'.$invoice_amounts.'</td>
</tr></table>';



// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');




// Set some content to print
$pdf->ln(3);
$columns=array(
             array('w'=>20,'txt'=>_('Code'),'border'=>'TB','align'=>'L'),
             array('w'=>65,'txt'=>_('Description'),'border'=>'TB','align'=>'L'),
             array('w'=>15,'txt'=>_('Tariff Code'),'border'=>'TB','align'=>'L'),

             array('w'=>15,'txt'=>_('Qty'),'border'=>'TB','align'=>'R'),
             array('w'=>20,'txt'=>_('Gross'),'border'=>'TB','align'=>'R'),
             array('w'=>20,'txt'=>_('Discounts'),'border'=>'TB','align'=>'R'),
             array('w'=>20,'txt'=>_('Net'),'border'=>'TB','align'=>'R'),
             array('w'=>15,'txt'=>_('Tax'),'border'=>'TB','align'=>'R'),


         );

$pdf->MultiRow($columns);




$sql=sprintf("select `Product Tariff Code`,`Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Item Tax Amount`,`Invoice Quantity`,`Invoice Transaction Tax Refund Amount`,`Invoice Currency Code`,`Invoice Transaction Net Refund Amount`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code` from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) where `Invoice Key`=%d ", $invoice->id);
//print $sql;exit;
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



    //$sku=sprintf('SKU%05d',$row['Part SKU']);
    $columns=array(
                 array('w'=>20,'txt'=>$row['Product Code'],'border'=>'T','align'=>'L'),
                 array('w'=>65,'txt'=>strip_tags($row['Product XHTML Short Description']) ,'border'=>'T','align'=>'L'),
                 array('w'=>15,'txt'=>strip_tags($row['Product Tariff Code']) ,'border'=>'T','align'=>'L'),

                 array('w'=>15,'txt'=>$row['Invoice Quantity'] ,'border'=>'T','align'=>'R'),
                 array('w'=>20,'txt'=>money($row['Invoice Transaction Gross Amount'],$invoice->data['Invoice Currency']) ,'border'=>'T','align'=>'R'),
                 array('w'=>20,'txt'=>($row['Invoice Transaction Total Discount Amount']!=0? money($row['Invoice Transaction Total Discount Amount'],$invoice->data['Invoice Currency']).' ('.percentage($row['Invoice Transaction Total Discount Amount'],$row['Invoice Transaction Gross Amount']).')':''),'border'=>'T','align'=>'R'),
                 array('w'=>20,'txt'=>money(($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount']),$invoice->data['Invoice Currency']),'border'=>'T','align'=>'R'),
                    array('w'=>15,'txt'=>money(($row['Invoice Transaction Item Tax Amount']),$invoice->data['Invoice Currency']),'border'=>'T','align'=>'R')

   );
    $pdf->MultiRow($columns);





}

$columns=array(array('w'=>0,'txt'=>'','border'=>'T','align'=>'L'));
 $pdf->MultiRow($columns);


$sql=sprintf("select * from `Store Dimension` where `Store Key`=%d", $store->id);
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);

$address='';
$store_address=explode(",",$row['Store Address']);
foreach($store_address as $ad)
	$address.=$ad.",<br/>";


   $response= array('state'=>200);
$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="left" WIDTH="100%">
	<tr nobr="true">
		<th colspan="5" ><font size="15">{$store->data['Store Invoice Message Header']}</font></th>
		<th></th>
		<th></th
		><th></th>
		<th></th>
	</tr>
	<tr nobr="true">
		<td colspan="5">{$store->data['Store Invoice Message']}</td>
	</tr>

</table>
EOD;


$pdf->writeHTML($tbl, true, false, false, false, '');
/*
// set JPEG quality
$pdf->setJPEGQuality(75);


$sql="select * from `Image Dimension` where `Image Key`=1";

$res=mysql_query($sql);
$row=mysql_fetch_assoc($res);

$imgdata= ($row['Image Data']);
//print $imgdata;
$imgdata = base64_decode($imgdata);
//print $imgdata;
//$imgdata = base64_decode('iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABlBMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDrEX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==');

// The '@' character is used to indicate that follows an image data stream and not an image file name
$pdf->Image('@'.$imgdata);
// Image method signature:
// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
$str = 'This is an encoded string';

// Example of Image from data stream ('PHP rules')


// The '@' character is used to indicate that follows an image data stream and not an image file name
//$pdf->Image('@'.$imgdata);
*/
// Print text using writeHTMLCell()
//$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($invoice->data['Invoice File As'], 'I');



?>