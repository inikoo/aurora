<?php
chdir('../');

require_once('common.php');
require_once('class.Store.php');

require_once('class.Invoice.php');
require_once('class.DeliveryNote.php');





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



$parcels=$delivery_note->get_formatted_parcels();
$weight=$delivery_note->data['Delivery Note Weight'];
$consignment=$delivery_note->data['Delivery Note Shipper Consignment'];


$smarty->assign( 'parcels', $parcels);
$smarty->assign( 'weight', ($weight?$delivery_note->get('Weight'):'') );
$smarty->assign( 'consignment', ($consignment?$delivery_note->get('Consignment'):'') );


$shipper_data=array();

$sql=sprintf("select `Shipper Key`,`Shipper Code`,`Shipper Name` from `Shipper Dimension` where `Shipper Active`='Yes' order by `Shipper Name` ");
$result=mysql_query($sql);
while ($row=mysql_fetch_assoc($result)) {
	$shipper_data[$row['Shipper Key']]=array(
	'shipper_key'=>$row['Shipper Key'],
	'code'=>$row['Shipper Code'],
	'name'=>$row['Shipper Name'],
	'selected'=>($delivery_note->data['Delivery Note Shipper Code']==$row['Shipper Code']?1:0)
	);
	
	
}
$smarty->assign( 'shipper_data', $shipper_data );




putenv('LC_ALL='.$store->data['Store Locale'].'.UTF-8');
setlocale(LC_ALL,$store->data['Store Locale'].'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");





include("external_libs/mpdf/mpdf.php");

$mpdf=new mPDF('win-1252','A4','','',20,15,38,25,10,10);

$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetTitle(_('Delivery Note').' '.$delivery_note->data['Delivery Note ID']);
$mpdf->SetAuthor($store->data['Store Name']);



//$mpdf->SetDisplayMode('fullpage');
//$mpdf->SetJS('this.print();');    // set when we want to print....

$smarty->assign('store',$store);

$smarty->assign('delivery_note',$delivery_note);



$transactions=array();

$sql=sprintf("select `Product RRP`,`Product XHTML Short Description`,`Inventory Transaction Quantity`,`Out of Stock`,`Not Found`,`No Picked Other`,`Inventory Transaction Type`,`Required`,OTF.`Product Code`,`Part Unit Description`,`Part XHTML Currently Used In`,ITF.`Part SKU` from `Inventory Transaction Fact` as ITF left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`) left join `Order Transaction Fact` OTF  on (`Order Transaction Fact Key`=`Map To Order Transaction Fact Key`) left join  `Product Dimension` PD on (OTF.`Product ID`=PD.`Product ID`)  where ITF.`Delivery Note Key`=%d and `Inventory Transaction Type`!='Adjust' order by `Part Reference`  ", 
$delivery_note->id);


$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

$notes='';

		if ($row['Out of Stock']!=0) {
			$notes.=_('Out of Stock').': '.number($row['Out of Stock']).' ';
		}
		if ($row['Not Found']!=0) {
			$notes.=_('Not Found').': '.number($row['Not Found']).' ';
		}
		if ($row['No Picked Other']!=0) {
			$notes.=_('Not picked (other)').': '.number($row['No Picked Other']).' ';
		}

$row['notes']=$notes;
$row['RRP']=money($row['Product RRP'],$store->data['Store Currency Code']);
$row['dispatched']=number(-1*$row['Inventory Transaction Quantity']);
$transactions[]=$row;

}



$smarty->assign('transactions',$transactions);




$html=$smarty->fetch('dn.pdf.tpl');

$mpdf->WriteHTML($html);



$mpdf->Output();

?> 