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

$mpdf=new mPDF('win-1252','A4','','',15,15,38,25,10,10);

$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetTitle($po->data['Purchase Order Public ID']);
$mpdf->SetAuthor($po->data['Purchase Order Main Buyer Name']);



if (isset($_REQUEST['print'])) {
	$mpdf->SetJS('this.print();');
}

$supplier=new Supplier('id',$po->data['Purchase Order Supplier Key']);
$warehouse=new Warehouse(1);


$smarty->assign('po',$po);
$smarty->assign('supplier',$supplier);
$smarty->assign('warehouse',$warehouse);




$transactions=array();

$sql=sprintf("select `Supplier Product Name`,`Note to Supplier`,`Supplier Product Code`,`Supplier Product Units Per Inner`,`SPH Units Per Case`,`SPH Case Cost`,`SPH Units Per Case`,`SPH Currency`,`Purchase Order Quantity`,`Purchase Order Net Amount`,`Currency Code` from `Purchase Order Transaction Fact` POTF
left join `Supplier Product Dimension` SPD on (POTF.`Supplier Product ID`=SPD.`Supplier Product ID`)
left join `Supplier Product History Dimension` SPHD on (POTF.`Supplier Product Key`=SPHD.`SPH Key`)
 where `Purchase Order Key`=%d order by `Supplier Product Store As` ", $po->id);

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

	$description=$row['Supplier Product Name'];
	if($row['Note to Supplier']!=''){
	$description.='<br><span class="note">'._('Note').': '.$row['Note to Supplier'].'<span>';
	}
	

	$data['Code']=$row['Supplier Product Code'];
	$data['Description']=$description;
	$data['Inners']=$row['Supplier Product Units Per Inner'];
	$data['Units']=$row['SPH Units Per Case'];
	$data['Price_Unit']=money($row['SPH Case Cost']/$row['SPH Units Per Case'],$row['SPH Currency']);
	$data['Cartons']=$row['Purchase Order Quantity'];
	$data['Amount']=money($row['Purchase Order Net Amount'],$row['Currency Code']);

	$transactions[]=$data;

}

$smarty->assign('transactions',$transactions);



$html=$smarty->fetch('porder.pdf.tpl');
$mpdf->WriteHTML($html);
$mpdf->Output();
?>
