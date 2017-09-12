<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2017 at 00:42:19 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 2.0
*/
chdir('../');

require_once('common.php');
require_once('class.Store.php');
require_once('class.Customer.php');

require_once('class.Invoice.php');
require_once('class.DeliveryNote.php');


$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit("no id");
}
$delivery_note = new DeliveryNote($id);
if (!$delivery_note->id) {
    exit("no dn");
}
$store    = new Store($delivery_note->data['Delivery Note Store Key']);
$customer = new Customer($delivery_note->data['Delivery Note Customer Key']);


$parcels     = $delivery_note->get_formatted_parcels();
$weight      = $delivery_note->data['Delivery Note Weight'];
$consignment = $delivery_note->data['Delivery Note Shipper Consignment'];

$smarty->assign('parcels', $parcels);
$smarty->assign('weight', ($weight ? $delivery_note->get('Weight') : ''));
$smarty->assign(
    'consignment', ($consignment ? $delivery_note->get('Consignment') : '')
);


$shipper_data = array();

$sql    = sprintf(
    "SELECT `Shipper Key`,`Shipper Code`,`Shipper Name` FROM `Shipper Dimension` WHERE `Shipper Active`='Yes' ORDER BY `Shipper Name` "
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


putenv('LC_ALL='.$store->data['Store Locale'].'.UTF-8');
setlocale(LC_ALL, $store->data['Store Locale'].'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");


include("external_libs/mpdf/mpdf.php");

$mpdf = new mPDF('win-1252', 'A4', '', '', 20, 15, 38, 25, 10, 10);

$mpdf->useOnlyCoreFonts = true;    // false is default
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
    "SELECT `Part SKO Barcode`,`Order Quantity`,`Order Bonus Quantity`,`Part Reference`,`Part Package Description`,`Product Units Per Case`,`Product Name`,`Product RRP`,`Product XHTML Short Description`,`Inventory Transaction Quantity`,`Out of Stock`,`Not Found`,`No Picked Other`,`Inventory Transaction Type`,`Required`,OTF.`Product Code`,`Part Unit Description`,`Part XHTML Currently Used In`,ITF.`Part SKU` FROM `Inventory Transaction Fact` AS ITF LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=ITF.`Part SKU`) LEFT JOIN `Order Transaction Fact` OTF  ON (`Order Transaction Fact Key`=`Map To Order Transaction Fact Key`) 
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
            $row['RRP']        = money(
                $row['Product RRP'], $store->data['Store Currency Code']
            );
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


$mpdf->Output();

?> 