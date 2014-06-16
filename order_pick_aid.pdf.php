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


require_once('external_libs/pdf/config/lang/eng.php');
require_once('external_libs/pdf/tcpdf.php');


class MYPDF extends TCPDF {

    function Header() {
    	global $dn;
    
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
                $header_x = $this->original_lMargin ;
            }
            $cw = $this->w - $this->original_lMargin - $this->original_rMargin - ($headerdata['logo_width'] * 1.1)+20;
            $this->SetTextColor(0, 0, 0);
            // header title
            $this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);
            $this->SetX($header_x);
	    //$this->SetY(15);
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
            $this->write2DBarcode('aw.inikoo.com/order_pick_aid.php?id='.$dn->id, 'QRCODE,Q', $cw, 0, 50, 50, $style, 'N');


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
//$file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array()) {
//$this->Image('./art/scs.jpg', 10, 15, 30.4, 8.6, 'JPG', 'http://www.inikoo.com', '', true, 500, '', false, false, 0, false, false, false);
    }


    public function MultiRow($columns) {
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)

        $page_start = $this->getPage();
        $y_start = $this->GetY();

        $number_columns=count($columns);
        $i=1;
        foreach($columns as $column) {
		$this->MultiCell($column['w'], 0, $column['txt'],$column['border'], $column['align'], 0, ($i==$number_columns?1:2), ($i==1?'':$this->GetX()), ($i==1?'':$y_start), true, 0);
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

    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
//	$this->Cell(0, 10, 'Powered by Inikoo ®', 0, false, 'L', 0, '', 0, false, 'T', 'M');
     
    	$this->Cell(0, 10, _('Document created').' '.date('Y-m-d H:i:s'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
 
     $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

    }

}



// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($store->data['Store Name']);
$pdf->SetTitle($dn->data['Delivery Note ID']);
$pdf->SetSubject(_('Order Picking Aid'));


$header_text=_('Customer').': '.$dn->data['Delivery Note Customer Name'].' (C'.$dn->data['Delivery Note Customer Key'].')';
$header_text.="\n"._('In warehouse since').': '.$dn->data['Delivery Note Date Created'];
$header_text.="\n"._('Picker').': '.$dn->data['Delivery Note Assigned Picker Alias'];
$header_text.="\n"._('Weight').':';
$header_text.="\n"._('Parcels').':';

$pdf->SetHeaderData('', 30.4, _('Order Pick Aid').' '.$dn->data['Delivery Note ID'], $header_text);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(10, 33, 15);
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


$pdf->SetFont('helvetica', '', 9, '', true);


$pdf->setCellPaddings(1,0.5,1,0.5);
// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Set some content to print
$pdf->ln(3);
$columns=array(
		array('w'=>30,'txt'=>_('Location'),'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))),'align'=>'L'),

		array('w'=>30,'txt'=>_('Reference'),'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))),'align'=>'L'),
		array('w'=>55,'txt'=>_('Description'),'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))),'align'=>'L'),
		array('w'=>20,'txt'=>_('Picks'),'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))),'align'=>'R'),
		array('w'=>20,'txt'=>_('No Picked'),'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))),'align'=>'R'),
				array('w'=>20,'txt'=>_('Stock'),'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))),'align'=>'R'),

		
		array('w'=>15,'txt'=>'','border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))),'align'=>'R'),
		//  array('w'=>15,'txt'=>'SKU','border'=>'TB','align'=>'L'),
		// array('w'=>0,'txt'=>_('Observations'),'border'=>'TB','align'=>'L'),
         );

$pdf->MultiRow($columns);


$sql=sprintf("select  `Part Current Stock`,`Part Reference`,`Picking Note`,ITF.`Part SKU`,`Part Unit Description`,`Required`,`Location Code` from `Inventory Transaction Fact` ITF   left join  `Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`) left join  `Location Dimension` L on  (L.`Location Key`=ITF.`Location Key`)  where `Delivery Note Key`=%d order by `Location Code` ",
             $dn->id
            );
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

$stock=$row['Part Current Stock'];

    $sku=sprintf('SKU%05d',$row['Part SKU']);
    $columns=array(
    		array('w'=>30,'txt'=>$row['Location Code'],'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192))),'align'=>'L'),

		array('w'=>30,'txt'=> strip_tags($row['Part Reference']) ,'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192))),'align'=>'L'),
		array('w'=>55,'txt'=>strip_tags($row['Part Unit Description']) ,'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192))),'align'=>'L'),


		array('w'=>20,'txt'=>$row['Required'],'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192))),'align'=>'R'),
		array('w'=>20,'txt'=>'','border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192))),'align'=>'R'),
		array('w'=>20,'txt'=>$stock,'border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192))),'align'=>'R'),


array('w'=>15,'txt'=>'','border'=>array('B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192))),'align'=>'R'),
		//  array('w'=>15,'txt'=>$sku,'border'=>'T','align'=>'L'),
		// array('w'=>0,'txt'=>'','border'=>'T','align'=>'L')


             );
    $pdf->MultiRow($columns);





}

$columns=array(array('w'=>0,'txt'=>'','border'=>'T','align'=>'L'));
 $pdf->MultiRow($columns);




// Print text using writeHTMLCell()
//$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($dn->data['Delivery Note File As'], 'I');


?>