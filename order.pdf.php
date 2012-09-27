<?php


require_once('common.php');
require_once('class.Store.php');

require_once('class.Invoice.php');

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$order=new Order($id);
if (!$order->id) {
    exit;
}
//print_r($order);
$store=new Store($order->data['Order Store Key']);


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
	$sql=sprintf("select * from `Store Dimension` where `Store Key`=%d", $this->get_footer_var());

$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);

$address='';
$store_address=explode(",",$row['Store Address']);
foreach($store_address as $ad)
	$address.=$ad.",<br/>";


   $response= array('state'=>200);
$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="left" WIDTH="100%">
	<tr >
		<th WIDTH="20%">Company Address</th> 
		<th WIDTH="12%">Company Number</th>
		<th WIDTH="12%">VAT Number</th>
		<th WIDTH="16%">Telephone</th>
		<th WIDTH="20%">Email</th>
		<th WIDTH="16%">Web</th>
	</tr>
	<tr >
		<td>{$row['Store Address']}</td>	
		<td>{$row['Store Company Number']}</td>
		<td>{$row['Store VAT Number']}</td>
		<td>{$row['Store Telephone']}</td>
		<td>{$row['Store Email']}</td>
		<td>{$row['Store URL']}</td>
		
	</tr>

</table>
EOD;


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
$pdf->SetTitle($order->data['Order Key']);
$pdf->SetSubject(_('Order Picking Aid'));

//print_r($invoice);
$header_text=$store->data['Store Name'];

$pdf->SetHeaderData(false, 0, $header_text, 'Invoice ');
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



$net_amount='';
if($order->get('Order Out of Stock Net Amount')!=0){
$net_amount="<tr><td>Out of Stock (N)</td><td width=100 >-{$order->get('Out of Stock Net Amount')}</td></tr>";
}

$charged_amount='';
if ($order->get('Order Invoiced Charges Amount')!=0){
$charged_amount="<tr><td >Charges (N)</td><td width=100 >{$order->get('Invoiced Charges Amount')}</td></tr>";
}

$refund_amount='';
if ($order->get('Order Invoiced Refund Net Amount')!=0){
$refund_amount="<tr><td   ><i>Other Order Refunds</i></td><td width=100 >{$order->get('Invoiced Refund Net Amount')}</td></tr>";
}


$net_adjustment='';
if ($order->get('Order Invoiced Total Net Adjust Amount')!=0){
$net_adjustment="<tr ><td   >Adjusts (N)</td><td width=100 >{$order->get('Invoiced Total Net Adjust Amount')}</td></tr>";
}



$net_refund='';
if ($order->get('Order Net Refund Invoiced Amount')!=0){
$net_refund="<tr><td >Net</td><td width=100 >{$order->get('Net Refund Amount')}</td></tr>";
}



$total_tax='';
if ($order->get('Order Invoiced Total Tax Adjust Amount')!=0){
$total_tax=" <tr style=\"color:red\"><td   >Tax Adjusts</td><td width=100 >{$order->get('Invoiced Total Tax Adjust Amount')}</td></tr>";
}


$html = <<<EOD
<div style="clear:both"></div>
<h3>{$order->data['Order Customer Name']} ({$order->data['Order Public ID']})</h3>
{$order->data['Order XHTML Ship Tos']}

<table>
<tr><td>
<div>
<h2></h2>
<div >

	 <table>
	   <tr><td>Order Date:</td><td>{$order->get('Date')}</td></tr>
	   <tr><td>Invoices:</td><td>{$order->get('Order XHTML Invoices')}</td></tr>
	   <tr><td>Delivery Notes:</td><td>{$order->get('Order XHTML Delivery Notes')}</td></tr>
	 </table>


</div>
</div>
</td><td>
<div>
<h2></h2>
<div >
	 <table>
	  
	   <tr><td   >Total Ordered (N)</td><td width=100 >{$order->get('Total Net Amount')}</td></tr>
	    {$net_amount}
	   
	   <tr><td colspan=2 >Invoiced Amounts</td></tr>
	   
	  
	   <tr><td   >Items (N)</td><td width=100 >{$order->get('Invoiced Items Amount')}</td></tr>
	   
	   <tr><td   >Shipping (N)</td><td width=100 >{$order->get('Invoiced Shipping Amount')}</td></tr>
	   {$charged_amount}
	   {$refund_amount}
	   {$net_adjustment}
           {$net_refund}
	   
	   
	   
	   
	   <tr>
	     
	     <td   >Total (N)</td><td width=100 >{$order->get('Invoiced Total Net Amount')}</td>
	   </tr>
	   <tr><td   >Tax</td><td width=100 >{$order->get('Invoiced Total Tax Amount')}</td></tr>
	    {$total_tax}
	   <tr><td   >Total</td><td width=100 ><b>{$order->get('Invoiced Total Amount')}</b></td></tr>
	   
	 </table>


</div>
</div>
</td></tr>



<div style="clear: both;"></div>
      

EOD;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');




// Set some content to print
$pdf->ln(3);
$columns=array(
             array('w'=>20,'txt'=>_('Code'),'border'=>'TB','align'=>'L'),
             array('w'=>50,'txt'=>_('Description'),'border'=>'TB','align'=>'L'),
             array('w'=>60,'txt'=>_('Ordered'),'border'=>'TB','align'=>'R'),
             array('w'=>20,'txt'=>_('Dispatched'),'border'=>'TB','align'=>'R'),
             array('w'=>20,'txt'=>_('Amount'),'border'=>'TB','align'=>'R')



         );

$pdf->MultiRow($columns);




$sql=sprintf("select O.`Order Transaction Fact Key`,`Deal Info`,`Operation`,`Quantity`,`Order Currency Code`,`Order Quantity`,`Order Bonus Quantity`,`No Shipped Due Out of Stock`,P.`Product ID` ,P.`Product Code`,`Product XHTML Short Description`,`Shipped Quantity`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as amount
         from `Order Transaction Fact` O left join `Product Dimension` P on (P.`Product ID`=O.`Product ID`)
         left join `Order Post Transaction Dimension` POT on (O.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`)
         left join `Order Transaction Deal Bridge` DB on (DB.`Order Transaction Fact Key`=O.`Order Transaction Fact Key`) where O.`Order Key`=%d ", $order->id);
//print $sql;exit;
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



    //$sku=sprintf('SKU%05d',$row['Part SKU']);
    $columns=array(
                 array('w'=>20,'txt'=>$row['Order Transaction Fact Key'],'border'=>'T','align'=>'L'),
                 array('w'=>55,'txt'=>strip_tags($row['Product XHTML Short Description']) ,'border'=>'T','align'=>'L'),
                 array('w'=>55,'txt'=>$row['Order Quantity'] ,'border'=>'T','align'=>'R'),
                 array('w'=>20,'txt'=>$row['Shipped Quantity'],'border'=>'T','align'=>'R'),
                 array('w'=>20,'txt'=>'£'.$row['amount'],'border'=>'T','align'=>'R')
                 
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
		<th colspan="5" ><font size="15">Thank you for your business</font></th>
		<th></th>
		<th></th
		><th></th>
		<th></th>
	</tr>
	<tr nobr="true">
		<td colspan="5">Please notify us immediately of any discrepancies of breakages</td>
	</tr>

</table>
EOD;


$pdf->writeHTML($tbl, true, false, false, false, '');

// set JPEG quality
$pdf->setJPEGQuality(75);

// Image method signature:
// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
$str = 'This is an encoded string';

// Example of Image from data stream ('PHP rules')


// The '@' character is used to indicate that follows an image data stream and not an image file name
//$pdf->Image('@'.$imgdata);

// Print text using writeHTMLCell()
//$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($order->data['Order File As'], 'I');



?>