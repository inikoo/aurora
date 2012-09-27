<?php


require_once('common.php');
require_once('class.Store.php');

require_once('class.DeliveryNote.php');

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$dn=new DeliveryNote($id);
if (!$dn->id) {
    exit;
}

$store=new Store($dn->data['Delivery Note Store Key']);

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
	
	$store=$this->get_footer_var();
	
	

$address='';
$store_address=explode(",",$store->data['Store Address']);
foreach($store_address as $ad)
	$address.=$ad.",<br/>";


   $response= array('state'=>200);
$tbl ='
<table border="0" cellpadding="2" cellspacing="2" align="left" WIDTH="100%">
	<tr >
		<th WIDTH="20%">'._('Company Address').'</th> 
		<th WIDTH="12%">'._('Company Number').'</th>
		<th WIDTH="12%">'._('VAT Number').'</th>
		<th WIDTH="16%">'._('Telephone').'</th>
		<th WIDTH="20%">'._('Email').'</th>
		<th WIDTH="16%">'._('Web').'</th>
	</tr>
	<tr >
		<td>'.$store->data['Store Address'].'</td>	
		<td>'.$store->data['Store Company Number'].'</td>	
		<td>'.$store->data['Store VAT Number'].'</td>	
		<td>'.$store->data['Store Telephone'].'</td>	
		<td>'.$store->data['Store Email'].'</td>	
		<td>'.$store->data['Store URL'].'</td>	
		
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
$pdf->SetTitle($dn->data['Delivery Note ID']);
$pdf->SetSubject(_('Delivery Note'));


$pdf->SetHeaderData(false, 0,  _('Delivery Note').' '.$dn->data['Delivery Note ID'],$store->data['Store Name']);

$pdf->set_footer_var($store);
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



$html ='<table width="820px" style="padding:20px 0px 0px 0px">
<tr>
<td>
<h3>'.$dn->data['Delivery Note Customer Name'].' ('.$dn->data['Delivery Note Customer Key'].')</h3>
'.$dn->data['Delivery Note XHTML Ship To'].'

</td>
<td >
	 <table>
	   
	<tr><td style="text-align:right;padding-right:10px">'._('Date').':</td><td  width="50px" style="text-align:right;padding-right:10px;width:150px">'.$dn->get('Date').'</td></tr>
	<tr><td style="text-align:right;padding-right:10px">'._('Weight').':</td><td  width="50px" style="text-align:right;padding-right:10px;;width:150px" >'.$dn->get('Weight').'</td></tr>

	 </table>
</td>
</tr>
</table>
';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');




// Set some content to print
$pdf->ln(3);
$columns=array(
             array('w'=>20,'txt'=>_('Part SKU'),'border'=>'TB','align'=>'L'),
             array('w'=>30,'txt'=>_('Code'),'border'=>'TB','align'=>'L'),
             array('w'=>70,'txt'=>_('Description'),'border'=>'TB','align'=>'L'),
             array('w'=>20,'txt'=>_('Ordered'),'border'=>'TB','align'=>'R'),
                          array('w'=>20,'txt'=>_('Dispatched'),'border'=>'TB','align'=>'R'),
                          array('w'=>30,'txt'=>_('Notes'),'border'=>'TB','align'=>'R')

         );

$pdf->MultiRow($columns);




$sql=sprintf("select `Inventory Transaction Quantity`,`Out of Stock`,`Not Found`,`No Picked Other`,`Inventory Transaction Type`,`Required`,`Product Code`,`Part Unit Description`,`Part XHTML Currently Used In`,ITF.`Part SKU` from `Inventory Transaction Fact` as ITF left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`) left join `Order Transaction Fact`  on (`Order Transaction Fact Key`=`Map To Order Transaction Fact Key`)  where ITF.`Delivery Note Key`=%d and `Inventory Transaction Type`!='Adjust'", $dn->id);
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

$notes='';

		if ($row['Out of Stock']!=0) {
			$notes.=_('Out of Stock').' '.number($row['Out of Stock']).' ';
		}
		if ($row['Not Found']!=0) {
			$notes.=_('Not Found').' '.number($row['Not Found']).' ';
		}
		if ($row['No Picked Other']!=0) {
			$notes.=_('Not picked (other)').' '.number($row['No Picked Other']).' ';
		}


    //$sku=sprintf('SKU%05d',$row['Part SKU']);
    $columns=array(
                 array('w'=>20,'txt'=>'SKU'.$row['Part SKU'],'border'=>'T','align'=>'L'),
                 array('w'=>30,'txt'=>strip_tags($row['Product Code']) ,'border'=>'T','align'=>'L'),
                 array('w'=>70,'txt'=>strip_tags($row['Part Unit Description']) ,'border'=>'T','align'=>'L'),
                 array('w'=>20,'txt'=>$row['Required'],'border'=>'T','align'=>'R'),
                 array('w'=>20,'txt'=>number(-1*$row['Inventory Transaction Quantity']),'border'=>'T','align'=>'R'),
                 array('w'=>30,'txt'=>$notes,'border'=>'T','align'=>'R')


             );
    $pdf->MultiRow($columns);





}

//$columns=array(array('w'=>0,'txt'=>'','border'=>'T','align'=>'L'));
 //$pdf->MultiRow($columns);

/*
$sql=sprintf("select * from `Store Dimension` where `Store Key`=%d", $store->id);
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);

$address='';
$store_address=explode(",",$row['Store Address']);
foreach($store_address as $ad)
	$address.=$ad.",<br/>";
*/
  

$message=sprintf("<div>%s</div>",$dn->data['Delivery Note XHTML Public Message']);

$message.=sprintf("<div>%s</div>",$store->data['Store Delivery Note XHTML Message']);

$pdf->writeHTML($message, true, false, false, false, '');
// Print text using writeHTMLCell()
//$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($dn->data['Delivery Note ID'], 'I');



?>