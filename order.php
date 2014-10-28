<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo

 Version 2.1
*/


include_once 'common.php';
include_once 'order_common_functions.php';
include_once 'class.CurrencyExchange.php';
include_once 'class.CompanyArea.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';
include_once 'class.Payment_Service_Provider.php';
include_once 'class.Part.php';
include_once 'class.Store.php';
include_once 'class.Order.php';
if (!$user->can_view('orders')) {
	header('Location: index.php');
	exit;
}

$modify=$user->can_edit('orders');


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/order.css',

	'theme.css.php'
);


$js_files=array(

	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-debug.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js'
);


$corporation=new Account();

if (isset($_REQUEST['new']) ) {
	date_default_timezone_set('UTC');
	if (isset($_REQUEST['customer_key']) and is_numeric($_REQUEST['customer_key']) ) {
		$customer=new Customer($_REQUEST['customer_key']);
		if (!$customer->id)
			$customer=new Customer('create anonymous');
	} else
		$customer=new Customer('create anonymous');
	$editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Alias'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id
	);

	$order_data=array(

		'Customer Key'=>$customer->id,
		'Order Original Data MIME Type'=>'application/inikoo',
		'Order Type'=>'Order',
		'editor'=>$editor

	);
	/*
	$ship_to=$customer->get_ship_to();
print_r($ship_to);
exit;
*/

	$order=new Order('new',$order_data);


	//$order->update_no_normal_totals();

	if ($order->error)
		exit('error');


	$ship_to=$customer->get_ship_to();
	$order->update_ship_to($ship_to->id);

	$billing_to=$customer->get_billing_to();
	$order->update_billing_to($billing_to->id);
	//exit;
	header('Location: order.php?id='.$order->id);
	exit;



}



if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])) {
	header('Location: orders_server.php?msg=wrong_id');
	exit;
}

$general_options_list=array();
$order_id=$_REQUEST['id'];
$_SESSION['state']['order']['id']=$order_id;
$order=new Order($order_id);
$store=new Store($order->data['Order Store Key']);



//$order->update_xhtml_delivery_notes();//exit;
//$order->update_no_normal_totals();

//$order->update_no_normal_totals();

//exit;

if (!$order->id) {
	header('Location: orders_server.php?msg=order_not_found');
	exit;

}
if (!($user->can_view('stores') and in_array($order->data['Order Store Key'],$user->stores)   ) ) {
	header('Location: orders_server.php');
	exit;
}

if (isset($_REQUEST['referral'])) {
	$referral=$_REQUEST['referral'];
}else {
	$referral='';
}
$smarty->assign('referral',$referral);



if ($referral) {

	if ($referral=='spo' or $referral=='po') {

		if ($referral=='spo') {


			$conf=$_SESSION['state']['customers']['pending_orders'];
			$where=sprintf("and `Order Store Key`=%d",$order->data['Order Store Key']);
			$parent_title=_('Pending Orders').' ('.$store->data['Store Code'].')';

		}else {
			$conf=$_SESSION['state']['stores']['pending_orders'];
			$where='';
			$parent_title=_('Pending Orders');
		}



		$elements=$conf['elements'];
		$_elements='';
		$elements_count=0;
		foreach ($elements as $_key=>$_value) {
			if ($_value) {
				$elements_count++;

				if ($_key=='InWarehouse') {
					$_key="'Ready to Pick','Picking & Packing','Packed','Packing'";
				}if ($_key=='SubmittedbyCustomer') {
					$_key="'Submitted by Customer','In Process'";
				}if ($_key=='ReadytoShip') {
					$_key="'Ready to Ship'";
				}if ($_key=='InProcessbyCustomer') {
					$_key="'In Process by Customer'";
				}if ($_key=='WaitingforPaymentConfirmation') {
					$_key="'Waiting for Payment Confirmation'";
				}if ($_key=='PackedDone') {
					$_key="'Packed Done'";
				}

				$_elements.=','.$_key;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($elements_count==0) {
			$where.=' and false' ;
		} elseif ($elements_count<6) {
			$where.=' and `Order Current Dispatch State` in ('.$_elements.')' ;
		}else {
			$where.=' and `Order Current Dispatch State` not in ("Dispatched","Unknown","Packing","Cancelled","Suspended","" )';

		}



		$list_order=$conf['order'];
		$order_label=$list_order;


		if ($list_order=='customer') {
			$list_order='`Order Customer Name`';
		}elseif ($list_order=='store') {
			$list_order='`Order Store Code`';
		}elseif ($list_order=='public_id') {
			$list_order='`Order File As`';
		}elseif ($list_order=='dispatch_state') {
			$list_order='O.`Order Current Dispatch State`';
		}elseif ($list_order=='payment_state') {
			$list_order='O.`Order Current Payment State`';

		}elseif ($list_order=='total_amount') {
			if ($referral=='spo') {
				$list_order='(O.`Order Total Amount`)';

			}else {
				$list_order='(O.`Order Total Amount`*`Order Currency Exchange`)';
			}

		}else {
			$list_order='`Order Date`';
		}

		$wheref='';



		$f_field=$conf['f_field'];


		$f_value=$conf['f_value'];

		$wheref='';

		if ($f_field=='max' and is_numeric($f_value) )
			$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Order Date Created`))<=".$f_value."    ";
		elseif ($f_field=='min' and is_numeric($f_value) )
			$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Order Date Created`))>=".$f_value."    ";
		elseif ($f_field=='customer_name' and $f_value!='')
			$wheref.=" and  `Order Customer Name` like '".addslashes($f_value)."%'";
		elseif ($f_field=='public_id' and $f_value!='')
			$wheref.=" and  `Order Public ID` like '".addslashes($f_value)."%'";






		$_order=preg_replace('/`/','',$list_order);
		$sql=sprintf("select `Order Key` as id , `Order Public ID` as title from `Order Dimension`   where `Order Key`!=%d %s  and %s <= %s $wheref  order by %s desc  limit 1",
			$order->id,
			$where,$list_order,prepare_mysql($order->get($_order)),$list_order);

		//print $sql;
		$result=mysql_query($sql);
		if ($prev=mysql_fetch_assoc($result)) {
			$prev['to_end']=false;
			$prev['link']=sprintf("order.php?referral=%s&id=%d",$referral,$prev['id']);

		}else {
			$prev=array('id'=>0,'title'=>'','link'=>'','to_end'>false);
		}
			
			
		mysql_free_result($result);

		$sql=sprintf("select `Order Key` as id , `Order Public ID` as title from `Order Dimension`     where `Order Key`!=%d %s and  %s>=%s  $wheref order by %s   limit 1 ",
			$order->id,
			$where,$list_order,prepare_mysql($order->get($_order)),$list_order);
		//print $sql;
		$result=mysql_query($sql);
		if ($next=mysql_fetch_assoc($result)) {
			$next['to_end']=false;
			$next['link']=sprintf("order.php?referral=%s&id=%d",$referral,$next['id']);

		}else {
			$next=array('id'=>0,'title'=>'','link'=>'','to_end'>false);
		}




		mysql_free_result($result);
		$smarty->assign('parent_info',"referral=spo&");


		if ($conf['order_dir']=='desc') {
			$smarty->assign('order_next',$prev);
			$smarty->assign('order_prev',$next);
		}else {
			$smarty->assign('order_prev',$prev);
			$smarty->assign('order_next',$next);
		}



		$smarty->assign('parent_url','store_pending_orders.php?id='.$store->id);

		$smarty->assign('parent_title',$parent_title);

	}
	elseif ($referral=='o') {

		$parent='store';
		$parent_key=$store->id;

		$awhere='';
		$conf=$_SESSION['state']['orders']['orders'];

		$from=$_SESSION['state']['orders']['from'];
		$to=$_SESSION['state']['orders']['to'];




		$elements_type=$conf['elements_type'];
		$elements=$conf['elements'];
		$f_field=$conf['f_field'];
		$f_value=$conf['f_value'];
		$where=sprintf("and `Order Store Key`=%d",$order->data['Order Store Key']);
		$parent_title=_('Orders').' ('.$store->data['Store Code'].')';


		include_once 'splinters/orders_prepare_list.php';




		$list_order=$conf['order'];
		$order_label=$list_order;


		if ($list_order=='id')
			$list_order='`Order File As`';
		elseif ($list_order=='last_date' or $list_order=='date')
			$list_order='O.`Order Date`';
		elseif ($list_order=='customer')
			$list_order='O.`Order Customer Name`';
		elseif ($list_order=='dispatch_state')
			$list_order='O.`Order Current Dispatch State`';
		elseif ($list_order=='payment_state')
			$list_order='O.`Order Current Payment State`';
		elseif ($list_order=='total_amount')
			$list_order='O.`Order Total Amount`';
		else
			$list_order='`Order File As`';










		$_order=preg_replace('/O\./','',$list_order);
		$_order=preg_replace('/`/','',$_order);



		$sql=sprintf("select `Order Key` as id , `Order Public ID` as title from `Order Dimension` O   %s and  `Order Key`!=%d and %s <= %s $wheref  order by %s desc  limit 1",

			$where,
			$order->id,
			$list_order,prepare_mysql($order->get($_order)),$list_order);


		$result=mysql_query($sql);
		if ($prev=mysql_fetch_assoc($result)) {
			$prev['to_end']=false;
			$prev['link']=sprintf("order.php?referral=o&id=%d",$prev['id']);

		}else {
			$prev=array('id'=>0,'title'=>'','link'=>'','to_end'>false);
		}


		mysql_free_result($result);

		$sql=sprintf("select `Order Key` as id , `Order Public ID` as title from `Order Dimension` O   %s and `Order Key`!=%d and  %s>=%s  $wheref order by %s   limit 1 ",

			$where,
			$order->id,
			$list_order,prepare_mysql($order->get($_order)),$list_order);
		//print $sql;
		$result=mysql_query($sql);
		if ($next=mysql_fetch_assoc($result)) {
			$next['to_end']=false;
			$next['link']=sprintf("order.php?referral=o&id=%d",$prev['id']);

		}else {
			$next=array('id'=>0,'title'=>'','link'=>'','to_end'>false);
		}

		mysql_free_result($result);
		$smarty->assign('parent_info',"referral=o&");


		if ($conf['order_dir']=='desc') {
			$smarty->assign('order_next',$prev);
			$smarty->assign('order_prev',$next);
		}else {
			$smarty->assign('order_prev',$prev);
			$smarty->assign('order_next',$next);
		}



		$smarty->assign('parent_url','orders.php?id='.$store->id);

		$smarty->assign('parent_title',$parent_title);

	}

}
else {
$smarty->assign('order_prev',array('id'=>0));
$smarty->assign('order_next',array('id'=>0));

}






$customer=new Customer($order->get('order customer key'));

//$order->update_no_normal_totals();
//$order->update_totals_from_order_transactions();

//print_r($store->get_payment_accounts_data());
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

if (isset($_REQUEST['pick_aid'])) {
	$js_files[]='order_pick_aid.js.php';
	$template='order_pick_aid.tpl';
}
else {


	$tax_categories=array();
	$sql=sprintf("select * from `Tax Category Dimension` where `Tax Category Active`='Yes' and `Tax Category Country Code`=%s ",
		prepare_mysql($store->data['Store Tax Country Code'])
	);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$tax_categories[]=array('rate'=>$row['Tax Category Rate'],'label'=>$row['Tax Category Name'],'code'=>$row['Tax Category Code'],'selected'=>($order->data['Order Tax Code']==$row['Tax Category Code']?true:false));
	}
	$smarty->assign('tax_categories',$tax_categories);

	$credit=array('net'=>'','tax_code'=>'','description'=>'','transaction_key'=>'');
	$sql=sprintf("select * from `Order No Product Transaction Fact` where `Transaction Type`='Credit' and `Order Key`=%d",$order->id);
	$res=mysql_query($sql);
	$has_credit=0;
	if ($row=mysql_fetch_assoc($res)) {
		$credit=array('transaction_key'=>$row['Order No Product Transaction Fact Key'],'net'=>$row['Transaction Net Amount'],'tax_code'=>$row['Tax Category Code'],'description'=>$row['Transaction Description']);
		$has_credit=1;
	}
	$smarty->assign('credit',$credit);
	$smarty->assign('has_credit',$has_credit);

	//$order->update_no_normal_totals();
	//print $order->data['Order Balance Net Amount'].' '.$order->data['Order Balance Tax Amount'].' '.$order->data['Order Balance Total Amount'];






	if (isset($_REQUEST['r'])) {
		$referer=$_REQUEST['r'];
		include_once 'order_navigation.php';;
	}

	$dns_data=array();
	foreach ($order->get_delivery_notes_objects() as $dn) {
		$current_delivery_note_key=$dn->id;

		$missing_dn_data=false;
		$missing_dn_str='';
		$dn_data='';
		if ($dn->data['Delivery Note Weight']) {
			$dn_data=$dn->get('Weight');
		}else {
			$missing_dn_data=true;
			$missing_dn_str=_('weight');

		}

		if ($dn->data['Delivery Note Number Parcels']!='') {
			$dn_data.=', '.$dn->get_formated_parcels();
		}else {
			$missing_dn_data=true;
			$missing_dn_str.=', '._('parcels');
		}
		$missing_dn_str=preg_replace('/^,/','',$missing_dn_str);


		if ($dn->data['Delivery Note Shipper Consignment']!='') {
			$dn_data.=', '. $dn->get('Consignment');
		}else {
			$missing_dn_data=true;
			$missing_dn_str.=', '._('consignment');
		}
		$missing_dn_str=preg_replace('/^,/','',$missing_dn_str);
		$dn_data=preg_replace('/^,/','',$dn_data);

		//'In Process by Customer','Waiting for Payment Confirmation','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Ready to Ship','Dispatched','Packing','Packed','Packed Done','Cancelled','Suspended','Cancelled by Customer'
		if ($missing_dn_data  and in_array($order->data['Order Current Dispatch State'],array('Packed Done','Packed')) ) {
			$dn_data.=' <img src="art/icons/exclamation.png" style="height:14px;vertical-align:-3px"> <span style="font-style:italic;color:#ea6c59">'._('Missing').': '.$missing_dn_str.'</span> <img onClick="show_dialog_set_dn_data_from_order('.$dn->id.')" style="cursor:pointer;display:none" src="art/icons/edit.gif"> ';
		}

		$dns_data[]=array(
			'key'=>$dn->id,
			'number'=>$dn->data['Delivery Note ID'],
			'state'=>$dn->data['Delivery Note XHTML State'],
			'dispatch_state'=>$dn->data['Delivery Note State'],
			'data'=>$dn_data,
			'operations'=>$dn->get_operations($user,'order',$order->id),
		);

		//print_r($dns_data);

	}
	$number_dns=count($dns_data);
	if ($number_dns!=1) {
		$current_delivery_note_key='';
	}
	$smarty->assign('current_delivery_note_key',$current_delivery_note_key);
	$smarty->assign('number_dns',$number_dns);
	$smarty->assign('dns_data',$dns_data);



	$invoices_data=array();
	foreach ($order->get_invoices_objects() as $invoice) {
		$current_invoice_key=$invoice->id;

		//print_r($invoice);

		$invoices_data[]=array(
			'key'=>$invoice->id,
			'operations'=>$invoice->get_operations($user,'order',$order->id),
			'number'=>$invoice->data['Invoice Public ID'],
			'state'=>$invoice->get_xhtml_payment_state(),
			'data'=>'',

		);
	}
	$number_invoices=count($invoices_data);
	if ($number_invoices!=1) {
		$current_invoice_key='';
	}
	$smarty->assign('current_invoice_key',$current_invoice_key);
	$smarty->assign('number_invoices',$number_invoices);
	$smarty->assign('invoices_data',$invoices_data);


	$order_current_dispatch_state=$order->get('Order Current Dispatch State');

	if (isset($_REQUEST['modify']) and $_REQUEST['modify']==1 and $order_current_dispatch_state=='In Process by Customer')
		$order_current_dispatch_state='In Process';

	switch ($order_current_dispatch_state) {

	case('In Process'):
	case('Submitted by Customer'):
	case('Waiting for Payment Confirmation'):
		include 'order_in_process_splinter.php';
		break;
	case('Ready to Pick'):
	case('Picking & Packing'):
	case('Packed Done'):
	case('Ready to Ship'):
		include 'order_in_warehouse_splinter.php';
		break;
	case('Dispatched'):
		include 'order_dispatched_splinter.php';
		break;
	case('Cancelled'):
	case('Cancelled by Customer'):
		include 'order_cancelled_splinter.php';
		break;
	case('Suspended'):
		include 'order_suspended_splinter.php';
		break;
	case 'In Process by Customer':
		include 'order_in_process_by_customer_splinter.php';
		break;
	default:
		exit('todo ->'.$order->get('Order Current Dispatch State').'<-');
		break;
	}
}

$smarty->assign('order',$order);
$smarty->assign('customer',$customer);


$order->update_payment_state();

$smarty->assign('parent','orders');
$smarty->assign('title',_('Order').' '.$order->get('Order Public ID') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display($template);

?>
