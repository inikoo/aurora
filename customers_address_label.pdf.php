<?php
require_once('common.php');
require_once('class.Customer.php');

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'list';
$label = isset($_REQUEST['label']) ? $_REQUEST['label'] : 'l7159';



if (!$id) {
    exit;
}


require_once('external_libs/pdf/config/lang/eng.php');
require_once('external_libs/pdf/tcpdf.php');



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
                             'ROWS'=>8,
                             'PAGE_HEIGHT'=>297,
                             'PAGE_WIDTH'=>   210 ,
                             'PDF_PAGE_ORIENTATION'=>'P'
                         ),
                 '99012'=>array(
                             'PDF_MARGIN_TOP'=>0,
                             'PDF_MARGIN_LEFT'=>0,
                             'PDF_MARGIN_RIGHT'=>0,
                             'PDF_MARGIN_BOTTOM'=>0,
                             'CELL_MARGIN_RIGHT'=>0,
                             'CELL_MARGIN_LEFT'=>0,
                             'CELL_MARGIN_TOP'=>0,
                             'CELL_MARGIN_BOTTOM'=>0,
                             'CELL_WIDTH'=>89,
                             'CELL_HEIGHT'=>36,
                             'COLUMNS'=>1,
                             'ROWS'=>1,

                             'PAGE_HEIGHT'=>36,
                             'PAGE_WIDTH'=> 89,
                             'PDF_PAGE_ORIENTATION'=>'L'
                         )
             );

if (!array_key_exists($label,$labels_data)) {
    $label='l7159';
}


$label_data=$labels_data[$label];
//print_r($label_data);


// create new PDF document
$pdf = new TCPDF($label_data['PDF_PAGE_ORIENTATION'], PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$resolution= array($label_data['PAGE_WIDTH'],$label_data['PAGE_HEIGHT'] );




// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Inikoo');
$pdf->SetTitle('Customer List Adresses');
$pdf->SetSubject('Address Labels');



$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);




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
$pdf->AddPage($label_data['PDF_PAGE_ORIENTATION'], $resolution);

// set cell padding
$pdf->setCellPaddings(2, 4, 1, 2);

// set cell margins
$pdf->setCellMargins($label_data['CELL_MARGIN_LEFT'], $label_data['CELL_MARGIN_TOP'], $label_data['CELL_MARGIN_RIGHT'], $label_data['CELL_MARGIN_BOTTOM']);

// set color for background
$pdf->SetFillColor(255, 255, 255);

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)



if ($type=='list') {

    $sql=sprintf("select * from `List Dimension` where `List Key`=%d",$id);
    $res2=mysql_query($sql);
    if ($row2=mysql_fetch_assoc($res2)) {



        if ($row2['List Type']=='Static') {

            $sql=sprintf("select `Customer Key` from `List Customer Bridge` where `List Key`=%d",$id);

            $res=mysql_query($sql);
            $counter=0;
            $labels_per_page=$label_data['COLUMNS']*$label_data['ROWS'];
            while ($row=mysql_fetch_assoc($res)) {
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



            }



        } else { //dinamic
            
            
            $tmp=preg_replace('/\\\"/','"',$row2['List Metadata']);
                $tmp=preg_replace('/\\\\\"/','"',$tmp);
                $tmp=preg_replace('/\'/',"\'",$tmp);

                $raw_data=json_decode($tmp, true);

                $raw_data['store_key']=$row2['List Parent Key'];
                include_once('list_functions_customer.php');
                list($where,$table)=customers_awhere($raw_data);
            
                    $sql=sprintf("select `Customer Key` from $table $where");

            $res=mysql_query($sql);
            $counter=0;
            $labels_per_page=$label_data['COLUMNS']*$label_data['ROWS'];
            while ($row=mysql_fetch_assoc($res)) {
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



            }
            
            
            
        }
    }
}
elseif($type=='customer') {
    $customer=new Customer($id);
    $counter=1;
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
}
elseif ($type=='send_post') {
$conf=$_SESSION['state']['store']['pending_post'];
 if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];

$wheref='';
  if (($f_field=='customer name'     )  and $f_value!='') {
        $wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
    }
    elseif(($f_field=='postcode'     )  and $f_value!='') {
        $wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
    }
    elseif ($f_field=='id'  )
        $wheref.=" and  `Customer Key` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
    elseif ($f_field=='last_more' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
    elseif ($f_field=='last_less' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
    elseif ($f_field=='max' and is_numeric($f_value) )
        $wheref.=" and  `Customer Orders`<=".$f_value."    ";
    elseif ($f_field=='min' and is_numeric($f_value) )
        $wheref.=" and  `Customer Orders`>=".$f_value."    ";
    else if ($f_field=='maxvalue' and is_numeric($f_value) )
        $wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
    else if ($f_field=='minvalue' and is_numeric($f_value) )
        $wheref.=" and  `Customer Net Balance`>=".$f_value."    ";
    else if ($f_field=='country' and  $f_value!='') {
        if ($f_value=='UNK') {
            $wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
            $find_data=' '._('a unknown country');
        } else {

            $f_value=Address::parse_country($f_value);
            if ($f_value!='UNK') {
                $wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
                $country=new Country('code',$f_value);
                $find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
            }

        }
    }

  $where=sprintf('where `Customer Store Key`=%d ',$id);
    $table='`Customer Send Post` CSP left join  `Customer Dimension` C  on (CSP.`Customer Key`=C.`Customer Key`) ';

    $sql="select CSP.`Customer Key`  from  $table   $where $wheref ";
$res=mysql_query($sql);
 $counter=0;
 $labels_per_page=$label_data['COLUMNS']*$label_data['ROWS'];
            while ($row=mysql_fetch_assoc($res)) {
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



            }

}







//$pdf->Ln(4);


// move pointer to last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_005.pdf', 'I');





?>
