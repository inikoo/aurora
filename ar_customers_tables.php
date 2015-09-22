<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:27 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('customers')) {
	echo json_encode(array('state'=>405,'resp'=>'Forbidden'));
	exit;
}


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'customers':
	$data=prepare_values($_REQUEST,array(
			'parameters'=>array('type'=>'json array'),
			'nr'=>array('type'=>'number'),
			'page'=>array('type'=>'number'),
			'o'=>array('type'=>'string','optional'=>true),
			'od'=>array('type'=>'string','optional'=>true),
			'f_value'=>array('type'=>'string','optional'=>true),

		));
	customers($data);
	break;
case 'lists':
	$data=prepare_values($_REQUEST,array(
			'parameters'=>array('type'=>'json array'),
			'nr'=>array('type'=>'number'),
			'page'=>array('type'=>'number'),
			'o'=>array('type'=>'string','optional'=>true),
			'od'=>array('type'=>'string','optional'=>true),
			'f_value'=>array('type'=>'string','optional'=>true),

		));
	lists($data);
	break;
default:
	$response=array('state'=>405,'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}

function customers($_data) {

	global $user;


	$parent=$_data['parameters']['parent'];
	$parent_key=$_data['parameters']['parent_key'];
	$number_results=$_data['nr'];
	$start_from=($_data['page']-1)*$number_results;
	$order=(isset($_data['o'])  ?$_data['o']:'id');
	$order_direction=((isset($_data['od']) and  preg_match('/desc/i',$_data['od']) ) ?'desc':'');
	$awhere=$_data['parameters']['awhere'];
	$f_field=$_data['parameters']['f_field'];
	if (isset($_data['f_value']) and $_data['f_value']!='') {
		$f_value=$_data['f_value'];
	}else {
		$f_value='';
	}



	$elements_type=$_data['parameters']['elements_type'];

	$_SESSION['table_state'][$_data['parameters']['tab']]['o']=$order;
	$_SESSION['table_state'][$_data['parameters']['tab']]['od']=($order_direction==''?-1:1);
	$_SESSION['table_state'][$_data['parameters']['tab']]['nr']=$number_results;
	$_SESSION['table_state'][$_data['parameters']['tab']]['f_field']=$f_field;
	$_SESSION['table_state'][$_data['parameters']['tab']]['f_value']=$f_value;
	$_SESSION['table_state'][$_data['parameters']['tab']]['elements_type']=$elements_type;
	$_SESSION['table_state'][$_data['parameters']['tab']]['awhere']=$awhere;

	$rtext_label='customer';

	include_once 'prepare_table/'.$_data['parameters']['tab'].'.ptble.php';
	list($rtext,$total)=get_table_totals($sql_totals,$wheref,$rtext_label);

	$sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  $where_type  $group_by order by $order $order_direction limit $start_from,$number_results";


	$adata=array();

	$result=mysql_query($sql);

	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


		if ($parent=='category') {
			$category_other_value=$data['Other Note'];
		}else {
			$category_other_value='x';
		}






		if ($data['Customer Orders']==0)
			$last_order_date='';
		else
			$last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

		if ($data['Customer Orders Invoiced']==0 or $data['Customer Last Invoiced Order Date']=='')
			$last_invoice_date='';
		else
			$last_invoice_date=strftime("%e %b %y", strtotime($data['Customer Last Invoiced Order Date']." +00:00"));




		$contact_since=strftime("%e %b %y", strtotime($data['Customer First Contacted Date']." +00:00"));


		if ($data['Customer Billing Address Link']=='Contact')
			$billing_address='<i>'._('Same as Contact').'</i>';
		else
			$billing_address=$data['Customer XHTML Billing Address'];

		if ($data['Customer Delivery Address Link']=='Contact')
			$delivery_address='<i>'._('Same as Contact').'</i>';
		elseif ($data['Customer Delivery Address Link']=='Billing')
			$delivery_address='<i>'._('Same as Billing').'</i>';
		else
			$delivery_address=$data['Customer XHTML Main Delivery Address'];

		switch ($data['Customer Type by Activity']) {
		case 'Inactive':
			$activity=_('Lost');
			break;
		case 'Active':
			$activity=_('Active');
			break;
		case 'Prospect':
			$activity=_('Prospect');
			break;
		default:
			$activity=$data['Customer Type by Activity'];
			break;
		}

		$adata[]=array(
			'id'=>(integer) $data['Customer Key'],
			'store_key'=>$data['Customer Store Key'],
			'formated_id'=>sprintf("%06d",$data['Customer Key']),
			'name'=>$data['Customer Name'],
			'company_name'=>$data['Customer Company Name'],
			'contact_name'=>$data['Customer Main Contact Name'],

			'location'=>$data['Customer Main Location'],

			'invoices'=>(integer) $data['Customer Orders Invoiced'],
			'email'=>$data['Customer Main Plain Email'],
			'telephone'=>$data['Customer Main XHTML Telephone'],
			'mobile'=>$data['Customer Main XHTML Mobile'],
			'orders'=>number($data['Customer Orders']),

			'last_order'=>$last_order_date,
			'last_invoice'=>$last_invoice_date,
			'contact_since'=>$contact_since,

			'other_value'=>$category_other_value,

			'total_payments'=>money($data['Customer Net Payments'],$currency),
			'net_balance'=>money($data['Customer Net Balance'],$currency),
			'total_refunds'=>money($data['Customer Net Refunds'],$currency),
			'total_profit'=>money($data['Customer Profit'],$currency),
			'balance'=>money($data['Customer Outstanding Net Balance'],$currency),
			'account_balance'=>money($data['Customer Account Balance'],$currency),


			'top_orders'=>percentage($data['Customer Orders Top Percentage'],1,2),
			'top_invoices'=>percentage($data['Customer Invoices Top Percentage'],1,2),
			'top_balance'=>percentage($data['Customer Balance Top Percentage'],1,2),
			'top_profits'=>percentage($data['Customer Profits Top Percentage'],1,2),
			'address'=>$data['Customer Main XHTML Address'],
			'billing_address'=>$billing_address,
			'delivery_address'=>$delivery_address,

			'activity'=>$activity,
			'logins'=>number($data['Customer Number Web Logins']),
			'failed_logins'=>number($data['Customer Number Web Failed Logins']),
			'requests'=>number($data['Customer Number Web Requests']),


		);
	}

	mysql_free_result($result);

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}

function lists($_data) {

	global $user;

	$parent=$_data['parameters']['parent'];
	$parent_key=$_data['parameters']['parent_key'];
	$number_results=$_data['nr'];
	$start_from=($_data['page']-1)*$number_results;
	$order=(isset($_data['o'])  ?$_data['o']:'id');
	$order_direction=((isset($_data['od']) and  preg_match('/desc/i',$_data['od']) ) ?'desc':'');
	$f_field=$_data['parameters']['f_field'];

	if (isset($_data['f_value']) and $_data['f_value']!='') {
		$f_value=$_data['f_value'];
	}else {
		$f_value='';
	}



	$_SESSION['table_state'][$_data['parameters']['tab']]['o']=$order;
	$_SESSION['table_state'][$_data['parameters']['tab']]['od']=($order_direction==''?-1:1);
	$_SESSION['table_state'][$_data['parameters']['tab']]['nr']=$number_results;
	$_SESSION['table_state'][$_data['parameters']['tab']]['f_field']=$f_field;
	$_SESSION['table_state'][$_data['parameters']['tab']]['f_value']=$f_value;


	$rtext_label='customer';

	include_once 'prepare_table/'.$_data['parameters']['tab'].'.ptble.php';
	list($rtext,$total)=get_table_totals($sql_totals,$wheref,$rtext_label);




	$sql="select $fields from `List Dimension` CLD $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





		switch ($data['List Type']) {
		case 'Static':
			$customer_list_type=_('Static');
			$items=number($data['List Number Items']);
			break;
		default:
			$customer_list_type=_('Dynamic');
			$items='~'.number($data['List Number Items']);
			break;

		}

		$adata[]=array(

			'id'=>(integer) $data['List key'],
			'type'=>$customer_list_type,
			'name'=>$data['List Name'],

			'creation_date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['List Creation Date']." +00:00")),
			//'add_to_email_campaign_action'=>'<div class="buttons small"><button class="positive" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add Emails').'</button></div>',
			'items'=>$items,
			'delete'=>'<img src="/art/icons/cross.png"/>'


		);

	}
	mysql_free_result($result);


	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}


?>
