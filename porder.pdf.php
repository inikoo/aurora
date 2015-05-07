<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 May 2015 09:07:43 CEST Cala de Mijas, Spain

 Copyright (c) 2015, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Supplier.php';
include_once 'class.PurchaseOrder.php';
include_once 'class.Warehouse.php';




if (isset($_REQUEST['id'])) {
	$po=new PurchaseOrder($_REQUEST['id']);
	if (!$po->id)
		exit("Error po can no be found");
} else {
	exit("error");

}

include "external_libs/mpdf/mpdf.php";

$mpdf=new mPDF('win-1252','A4','','',20,15,38,25,10,10);

$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetTitle(_('Proforma Invoice').' '.$order->data['Order Public ID']);
$mpdf->SetAuthor($store->data['Store Name']);



if (isset($_REQUEST['print'])) {
	$mpdf->SetJS('this.print();');
}

	$supplier=new Supplier('id',$po->data['Purchase Order Supplier Key']);
	$warehouse=new Warehouse(1);


$smarty->assign('po',$po);
$smarty->assign('supplier',$supplier);
$smarty->assign('warehouse',$warehouse);




$transactions=array();

$smarty->assign('transactions',$transactions);



$html=$smarty->fetch('porder.pdf.tpl');
$mpdf->WriteHTML($html);
$mpdf->Output();
?>
