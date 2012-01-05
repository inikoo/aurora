<?php


require_once('common.php');
require_once('class.Store.php');

require_once('class.Invoice.php');

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$dn=new DeliveryNote($id);
if (!$dn->id) {
    exit;
}

$store=new Store($dn->data['Delivery Note Store Key']);


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
$pdf->SetTitle($dn->data['Delivery Note Key']);
$pdf->SetSubject(_('Delivery Note'));


$header_text=$dn->data['Delivery Note Customer Name'];

$pdf->SetHeaderData(false, 0, 'Delivery Note '.$dn->data['Delivery Note Key'], $header_text);

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

if($dn->get('Delivery Note XHTML Invoices')!='')
	$invoices='<tr><td>Invoices:</td><td class="aright">{$dn->get(\'Delivery Note XHTML Invoices\')}</td></tr>';
else
	$invoices='';

$html = <<<EOD
<div style="clear:both"></div>

<div style="padding:0px 20px;float:left">
<h2>Notes</h2>
<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">a</div>
</div>

<div style="padding:0px 20px;float:right">
<h2>Notes</h2>
<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">
	 <table border=0  style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right" >
	   
	   <tr><td>Creation Date:</td><td class="aright">{$dn->get('Date Created')}</td></tr>
	   <tr><td>Orders:</td><td class="aright">{$dn->get('Delivery Note XHTML Orders')}</td></tr>

	{$invoices}

	 </table>

</div>
</div>

<div style="padding:0px 20px;float:right">
<h2>Notes</h2>
<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">
	 <table border=0  style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	 	   <tr><td  class="aright" >Estimated Weight</td><td width=100 class="aright">{$dn->get('Estimated Weight')}</td></tr>

	
	   
	 </table>

</div>
</div>




<div style="clear: both;"></div>
      

EOD;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');




// Set some content to print
$pdf->ln(3);
$columns=array(
             array('w'=>15,'txt'=>_('Part SKU'),'border'=>'TB','align'=>'L'),
             array('w'=>50,'txt'=>_('Used In'),'border'=>'TB','align'=>'L'),
             array('w'=>55,'txt'=>_('Description'),'border'=>'TB','align'=>'R'),
             array('w'=>20,'txt'=>_('Qty'),'border'=>'TB','align'=>'R')
         );

$pdf->MultiRow($columns);




$sql=sprintf("select `Required`,`Part XHTML Description`,`Part XHTML Currently Used In`,ITF.`Part SKU` from `Inventory Transaction Fact` as ITF left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`) where `Delivery Note Key`=%d", $dn->id);
//print $sql;exit;
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



    //$sku=sprintf('SKU%05d',$row['Part SKU']);
    $columns=array(
                 array('w'=>15,'txt'=>$row['Part SKU'],'border'=>'T','align'=>'L'),
                 array('w'=>50,'txt'=>strip_tags($row['Part XHTML Currently Used In']) ,'border'=>'T','align'=>'L'),
                 array('w'=>55,'txt'=>strip_tags($row['Part XHTML Description']) ,'border'=>'T','align'=>'R'),
                 array('w'=>20,'txt'=>$row['Required'],'border'=>'T','align'=>'R')


             );
    $pdf->MultiRow($columns);





}

$columns=array(array('w'=>0,'txt'=>'','border'=>'T','align'=>'L'));
 $pdf->MultiRow($columns);


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
	<tr nobr="true">
		<td colspan="5">Ancient Wisdom Marketing Ltd<br />
		Unit 15, Block B<br />
		Parkwood Business Park<br />
		Parkwood Road<br />
		Sheffield S3 8AL<br />
		United Kingdom
		</td>
	</tr>
	<tr >
		<th WIDTH="20%">Company Number</th>
		<th WIDTH="20%">VAT Number</th>
		<th WIDTH="20%">Telephone</th>
		<th WIDTH="20%">Email</th>
		<th WIDTH="20%">Web</th>
	</tr>
	<tr width=100%>
		<td>4108870</td>
		<td>7642985889</td>
		<td>0114 2729165</td>
		<td>mail@ancinentwisdom.biz</td>
		<td>www.ancientwisdom.biz</td>
	</tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
// Print text using writeHTMLCell()
//$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($dn->data['Delivery Note File As'], 'I');



?>