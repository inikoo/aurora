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
	$f_value=$_data['parameters']['f_value'];

	$elements_type=$_data['parameters']['elements_type'];

	$_SESSION['table_state'][$_data['parameters']['tab']]['o']=$order;
	$_SESSION['table_state'][$_data['parameters']['tab']]['od']=($order_direction==''?-1:1);
	$_SESSION['table_state'][$_data['parameters']['tab']]['nr']=$number_results;





	include_once 'prepare_table/customers.ptble.php';



	$sql="select count(Distinct C.`Customer Key`) as total from $table   $where $wheref $where_type";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(Distinct C.`Customer Key`) as total_without_filters from $table  $where  $where_type";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('customer','customers',$total_records);




	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('customer name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
			break;
		case('postcode'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with postcode like")." <b>$f_value</b> ";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer based in").$find_data;
			break;
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with ID like")." <b>$f_value</b> ";
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."> <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."< <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."< <b>".money($f_value,$currency)."</b> ";
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."> <b>".money($f_value,$currency)."</b> ";
			break;


		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('customer name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with name like')." <b>*".$f_value."*</b>";
			break;
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with ID  like')." <b>".$f_value."*</b>";
			break;
		case('postcode'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with postcode like')." <b>".$f_value."*</b>";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('based in').$find_data;
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."> ".number($f_value)."  ".ngettext('day','days',$f_value);
			break;
		case('last_less'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."< ".number($f_value)."  ".ngettext('day','days',$f_value);
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."< ".money($f_value,$currency);
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."> ".money($f_value,$currency);
			break;
		}
	}
	else
		$filter_msg='';








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
			'filter_msg'=>$filter_msg,
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

	include_once 'prepare_table/customers.lists.ptble.php';


	$sql="select count(*) as total from `List Dimension` $where $wheref ";
	// print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(distinct `List Key`) as total_without_filters from `List Dimension`  $where  ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);

    if($filtered==0){
	    	$rtext=sprintf (ngettext ("%s list", "%s lists", $total_records), number($total_records));

    }else{
        
	    	$rtext=sprintf (ngettext ("%s list of %s", "%s lists of %s", $total), number($total),number($total_records));

    }

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom;height:14px" src="art/icons/exclamation.png"/>'._("There isn't any list named like")." <b>$f_value*</b> ";
			break;


		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="xvertical-align:bottom;height:14px" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext('list','lists',$total)." "._('with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else {
		$filter_msg="";
	}





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
			'filter_msg'=>$filter_msg,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}


?>
