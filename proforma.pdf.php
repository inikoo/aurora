<?php

require_once 'common.php';
require_once 'class.Store.php';

require_once 'class.Order.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';
include_once 'class.Payment_Service_Provider.php';
require_once 'common_geography_functions.php';



$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
	exit;
}
$order=new Order($id);
if (!$order->id) {
	exit;
}
//print_r($order);
$store=new Store($order->data['Order Store Key']);
$customer=new Customer($order->data['Order Customer Key']);


putenv('LC_ALL='.$store->data['Store Locale'].'.UTF-8');
setlocale(LC_ALL,$store->data['Store Locale'].'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");




include "external_libs/mpdf/mpdf.php";

$mpdf=new mPDF('win-1252','A4','','',20,15,38,25,10,10);

$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetTitle(_('Proforma Invoice').' '.$order->data['Order Public ID']);
$mpdf->SetAuthor($store->data['Store Name']);



if (isset($_REQUEST['print'])) {
	$mpdf->SetJS('this.print();');
}
$smarty->assign('store',$store);

$smarty->assign('order',$order);



$transactions=array();
$sql=sprintf("select * from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) where `Order Key`=%d order by `Product Code File As` ", $order->id);

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

	$no_charge_quantity=0;

	$quantity=number($row['Order Quantity']);
	if ($row['Order Bonus Quantity']!=0) {
		if ($row['Order Quantity']!=0) {
			$quantity.='<br/> +'.number($row['Order Bonus Quantity']).' '._('free');
		}else {
			$quantity=number($row['Order Bonus Quantity']).' '._('free');
		}
	}


	if ($row['No Shipped Due Out of Stock']!=0) {
		$quantity.='<br/><span>('._('Out of Stock').') '.(-1*$row['No Shipped Due Out of Stock']).'</span>';
		$no_charge_quantity+=$row['No Shipped Due Out of Stock'];
	}

	if ($row['No Shipped Due No Authorized']!=0) {
		$quantity.='<br/><span>('._('No Authorized').') '.(-1*$row['No Shipped Due No Authorized ']).'</span>';
		$no_charge_quantity+=$row['No Shipped Due No Authorized'];
	}
	if ($row['No Shipped Due Not Found']!=0) {
		$quantity.='<br/><span>('._('Not Found').') '.(-1*$row['No Shipped Due Not Found']).'</span>';
		$no_charge_quantity+=$row['No Shipped Due Not Found'];
	}
	if ($row['No Shipped Due Other']!=0) {
		$quantity.='<br/><span>('._('Not Due Other').') '.(-1*$row['No Shipped Due Other']).'</span>';
		$no_charge_quantity+=$row['No Shipped Due Other'];
	}

	if ($row['Order Quantity']==0) {
		$charge_quantity_amount=0;
	}else {
		$to_charge=$row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'];
		$no_charge_quantity_amount=$to_charge*$no_charge_quantity/$row['Order Quantity'];
		$charge_quantity_amount=$to_charge-$no_charge_quantity_amount;
	}


	$row['Amount']=money($charge_quantity_amount,$row['Order Currency Code']);
	$row['Discount']=($row['Order Transaction Total Discount Amount']==0?'':percentage($row['Order Transaction Total Discount Amount'],$row['Order Transaction Gross Amount'],0));

	$row['Quantity']=$quantity;


	$transactions[]=$row;

}


$smarty->assign('transactions',$transactions);
$html=$smarty->fetch('proforma.pdf.tpl');
$mpdf->WriteHTML($html);
//$mpdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="1" suppress="off" />');
//$mpdf->WriteHTML($html);
$mpdf->Output();

?>
