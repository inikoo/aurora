<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2017 at 00:42:19 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 2.0
*/
chdir('../');
require_once __DIR__.'/../vendor/autoload.php';


if(isset($_REQUEST['sak'])){

    include 'keyring/key.php';
    include_once 'utils/general_functions.php';

    $key = md5('82$je&4WN1g2B^{|bRbcEdx!Nz$OAZDI3ZkNs[cm9Q1)8buaLN'.SKEY);
    $auth_data = json_decode(safeDecrypt(urldecode($_REQUEST['sak']), $key),true);

    if( !(isset($auth_data['auth_token']['logged_in']) and  $auth_data['auth_token']['logged_in']) ){
        unset($auth_data);

    }
}



require_once('common.php');
require_once 'utils/object_functions.php';






$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit("no id");
}
$delivery_note = get_object('DeliveryNote',$id);
if (!$delivery_note->id) {
    exit("no dn");
}

$store    = get_object('Store', $delivery_note->get('Delivery Note Store Key'));
$customer = get_object('Customer', $delivery_note->get('Delivery Note Customer Key'));
$order = get_object('Order', $delivery_note->get('Delivery Note Order Key'));


$parcels     = $delivery_note->get_formatted_parcels();
$weight      = $delivery_note->data['Delivery Note Weight'];
$consignment = $delivery_note->data['Delivery Note Shipper Consignment'];

$smarty->assign('parcels', $parcels);
$smarty->assign('weight', ($weight ? $delivery_note->get('Weight') : ''));
$smarty->assign('consignment', ($consignment ? $delivery_note->get('Consignment') : ''));
$smarty->assign('order', $order);


$dangerous_goods=array();
$sql=sprintf(' select `Part UN Number` AS un_number ,`Part Packing Group`  AS part_packing_group,group_concat(`Part Reference`) as parts from  `Inventory Transaction Fact` ITF   LEFT JOIN `Part Dimension` Part ON  (Part.`Part SKU`=ITF.`Part SKU`) WHERE `Delivery Note Key`=?  and (`Part UN Number`!="" or `Part Packing Group`!="Noe" ) group by `Part UN Number`,`Part Packing Group`  ');

$stmt = $db->prepare($sql);
$stmt->execute(
    array($delivery_note->id)
);
while ($row = $stmt->fetch()) {
    if($row['un_number']>1 or $row['part_packing_group']!='None')
        $dangerous_goods[]=$row;
}
$smarty->assign('dangerous_goods', $dangerous_goods);



$shipper_data = array();

$sql    = sprintf(
    "SELECT `Shipper Key`,`Shipper Code`,`Shipper Name` FROM `Shipper Dimension` WHERE `Shipper Status`='Active' ORDER BY `Shipper Name` "
);

if ($result=$db->query($sql)) {
		foreach ($result as $row) {
            $shipper_data[$row['Shipper Key']] = array(
                'shipper_key' => $row['Shipper Key'],
                'code'        => $row['Shipper Code'],
                'name'        => $row['Shipper Name'],
                'selected'    => ($delivery_note->data['Delivery Note Shipper Code'] == $row['Shipper Code'] ? 1 : 0)
            );
		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}


$smarty->assign('shipper_data', $shipper_data);




if(!empty($_REQUEST['locale'])){
    $_locale=$_REQUEST['locale'];
}else{
    $_locale=$store->get('Store Locale');

}




putenv('LC_ALL='.$_locale.'.UTF-8');
setlocale(LC_ALL, $_locale.'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");



$mpdf = new \Mpdf\Mpdf(
    [
        'tempDir'       => __DIR__.'/../server_files/pdf_tmp',
        'mode'          => 'utf-8',
        'margin_left'   => 10,
        'margin_right'  => 10,
        'margin_top'    => 38,
        'margin_bottom' => 25,
        'margin_header' => 10,
        'margin_footer' => 10
    ]
);

$mpdf->SetTitle(
    _('Delivery Note').' '.$delivery_note->data['Delivery Note ID']
);
$mpdf->SetAuthor($store->data['Store Name']);


//$mpdf->SetDisplayMode('fullpage');
//$mpdf->SetJS('this.print();');    // set when we want to print....

$smarty->assign('store', $store);

$smarty->assign('delivery_note', $delivery_note);


$transactions = array();

$sql = sprintf(
    "SELECT `Part SKO Barcode`,`Order Quantity`,`Order Bonus Quantity`,`Part Reference`,`Part Package Description`,`Product Units Per Case`,`Product Name`,`Product RRP`,`Inventory Transaction Quantity`,`Out of Stock`,`Not Found`,`No Picked Other`,`Inventory Transaction Type`,`Required`,OTF.`Product Code`,ITF.`Part SKU` ,
       `Part UN Number` AS un_number,
`Part Packing Group` AS part_packing_group
       
       FROM `Inventory Transaction Fact` AS ITF LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=ITF.`Part SKU`) LEFT JOIN `Order Transaction Fact` OTF  ON (`Order Transaction Fact Key`=`Map To Order Transaction Fact Key`) 
LEFT JOIN  `Product Dimension` PD ON (OTF.`Product ID`=PD.`Product ID`)  WHERE ITF.`Delivery Note Key`=%d AND `Inventory Transaction Type`!='Adjust' ORDER BY `Part Reference`  ",
    $delivery_note->id
);

if ($result=$db->query($sql)) {
		foreach ($result as $row) {
            $notes = '';

            if ($row['Out of Stock'] != 0) {
                $notes .= _('Out of Stock').': '.number($row['Out of Stock']).' ';
            }
            if ($row['Not Found'] != 0) {
                $notes .= _('Not Found').': '.number($row['Not Found']).' ';
            }
            if ($row['No Picked Other'] != 0) {
                $notes .= _('Not picked (other)').': '.number($row['No Picked Other']).' ';
            }
            $row['Ordered']      = $row['Order Quantity']+$row['Order Bonus Quantity'];
            $row['notes']      = $notes;
            $row['RRP']        = money($row['Product RRP'], $store->data['Store Currency Code']);
            $row['Product Description']=$row['Product Units Per Case'].'x '.$row['Product Name'];



            $row['dispatched'] = number(-1 * $row['Inventory Transaction Quantity']);
            $transactions[]    = $row;
		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}




$smarty->assign('transactions', $transactions);


$html = $smarty->fetch('dn.pdf.tpl');


$mpdf->WriteHTML($html);

$mpdf->Output($delivery_note->get('Delivery Note ID').'_delivery_.pdf', 'I');


