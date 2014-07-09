<?php

require_once 'common.php';
require_once 'class.Store.php';

require_once 'class.Invoice.php';
require_once 'class.DeliveryNote.php';





$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
	exit("no id");
}
$delivery_note=new DeliveryNote($id);
if (!$delivery_note->id) {
	exit("no dn");
}
$store=new Store($delivery_note->data['Delivery Note Store Key']);
$customer=new Customer($delivery_note->data['Delivery Note Customer Key']);


include "external_libs/mpdf/mpdf.php";

$mpdf=new mPDF('win-1252','A4','','',15,15,22,25,10,10);
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetTitle(_('Picking Aid').' '.$delivery_note->data['Delivery Note ID']);
$mpdf->SetAuthor($store->data['Store Name']);



//$mpdf->SetDisplayMode('fullpage');
//$mpdf->SetJS('this.print();');    // set when we want to print....

$smarty->assign('store',$store);
$smarty->assign('delivery_note',$delivery_note);



$transactions=array();





$sql=sprintf("select  `Map To Order Transaction Fact Parts Multiplicity` as part_multiplicity,`Map To Order Transaction Fact XHTML Info` as multiple_parts_info,Part.`Part Current On Hand Stock` as total_stock, PLD.`Quantity On Hand` as stock_in_picking,`Part Current Stock`,`Part Reference` as reference,`Picking Note` as notes,ITF.`Part SKU`,`Part Unit Description` as description,
(`Required`+`Given`) as qty,`Location Code` as location from 
`Inventory Transaction Fact` ITF   left join  
`Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`) left join  
`Location Dimension` L on  (L.`Location Key`=ITF.`Location Key`)  left join 
`Part Location Dimension` PLD on (ITF.`Location Key`=PLD.`Location Key` and ITF.`Part SKU`=PLD.`Part SKU`) 
where `Delivery Note Key`=%d order by `Location Code`,`Part Reference` ",
	$delivery_note->id
);
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$stock_in_picking=$row['stock_in_picking'];
	$total_stock=$row['total_stock'];

	$row['stock']=sprintf("[<b>%d</b>,%d]", $stock_in_picking,$total_stock);

	//$row['location']=$row['Location Code'];
	$transactions[]=$row;

}



$smarty->assign('transactions',$transactions);

$number_of_items=0;
$number_of_picks=0;
$sql=sprintf("select count(*) as items,sum(`Required`+`Given`) as picks from `Inventory Transaction Fact`  where `Delivery Note Key`=%d ",
$delivery_note->id
);
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
$number_of_items=$row['items'];
$number_of_picks=$row['picks'];
}

//print $sql;

$formated_number_of_items='<b>'.number($number_of_items).'</b> '.ngettext('item', 'items', $number_of_items);
$formated_number_of_picks='<b>'.number($number_of_picks).'</b> '.ngettext('pick', 'picks', $number_of_picks);

$smarty->assign('formated_number_of_items',$formated_number_of_items);
$smarty->assign('formated_number_of_picks',$formated_number_of_picks);


$html=$smarty->fetch('order_pick_aid.pdf.tpl');

$mpdf->WriteHTML($html);



$mpdf->Output();

?>
