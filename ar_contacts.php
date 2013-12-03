<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW

require_once 'common.php';
require_once 'class.Customer.php';
require_once 'class.Timer.php';
require_once 'ar_edit_common.php';

if (!isset($output_type))
	$output_type='ajax';

if (!isset($_REQUEST['tipo'])) {
	if ($output_type=='ajax') {
		$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
		echo json_encode($response);
	}
	return;
}


$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('get_contacts_elements_numbers'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),

		));
	get_contacts_elements_numbers($data);
	break;
case('number_orders_in_process'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key')
		));
	number_orders_in_process($data);
	break;
case('pending_post'):
	pending_post();
	break;
case('check_tax_number'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key')
		));
	check_tax_number($data);
	break;

	break;
case('can_merge_customer'):


	$data=prepare_values($_REQUEST,array(
			'customer_to_merge_id'=>array('type'=>'string'),
			'customer_key'=>array('type'=>'key')));
	can_merge_customer($data);
	break;

case('email_in_other_customer'):
	$data=prepare_values($_REQUEST,array(
			'query'=>array('type'=>'string'),
			'store_key'=>array('type'=>'key'),
			'customer_key'=>array('type'=>'key')
		));
	email_in_other_customer($data) ;
	break;
case('customers_correlation'):

	list_customers_correlations();

	break;
case('show_posible_customer_matches'):
	$data=prepare_values($_REQUEST,array(
			'scope'=>array('type'=>'json array')
			,'values'=>array('type'=>'json array')
		));
	show_posible_customer_matches($data);
	break;

case('customers_lists'):
	list_customers_lists();
	break;

case('marketing_post_lists'):
	marketing_post_lists();
	break;

case('used_email'):
	used_email();

	break;
case('find_customer'):
	require_once 'ar_edit_common.php';
	$data=prepare_values($_REQUEST,array(
			'scope'=>array('type'=>'json array')
			,'values'=>array('type'=>'json array')
		));
	find_customer($data);
	break;
case('find_Company'):
case('find_company'):
	require_once 'ar_edit_common.php';
	$data=prepare_values($_REQUEST,array(
			'scope'=>array('type'=>'json array')

			,'values'=>array('type'=>'json array')
		));
	find_company($data);
	break;
case('find_Contact'):

case('find_contact'):
	require_once 'ar_edit_common.php';


	$data=prepare_values($_REQUEST,array(
			'scope'=>array('type'=>'json array')
			,'values'=>array('type'=>'json array')
		));
	find_contact($data);
	break;

case('is_company_department_code'):
	is_company_department_code();
	break;
case('is_company_department_name'):
	is_company_department_name();
	break;
case('is_department_code'):
	is_department_code();
	break;
case('is_department_name'):
	is_department_name();
	break;
case('is_position_code'):
	is_position_code();
	break;
case('is_position_name'):
	is_position_name();
	break;

case('find_company_area'):
	require_once 'ar_edit_common.php';
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'number')
			,'query'=>array('type'=>'string')
		));
	find_company_area($data);
	break;
case('find_company_department'):
	require_once 'ar_edit_common.php';
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'number')
			,'grandparent_key'=>array('type'=>'number')
			,'query'=>array('type'=>'string')
		));
	find_company_department($data);
	break;
case('contacts'):
	list_contacts();
	break;
case('companies'):
	list_companies();
	break;
case('staff'):
	list_staff();
	break;

case('customers'):
	if (!$user->can_view('customers'))
		exit();
	$results=list_customers();
	break;





case('customers_send_post'):
	if (!$user->can_view('customers'))
		exit();
	list_customers_send_post();
	break;

case('assets_dispatched_to_customer'):

	list_assets_dispatched_to_customer();
	break;
case('assets_in_process_customer'):

	list_assets_in_process_customer();
	break;

case('site_user_view_orders'):

	list_customer_orders();
	break;
case('customer_orders'):
	if (!$user->can_view('orders'))
		exit();
	list_customer_orders();


	break;
case('customer_categories'):
	list_customer_categories();
	break;

case('get_customer_history_elements'):
	$data=prepare_values($_REQUEST,array(
			'customer_key'=>array('type'=>'key')
		));
	get_customer_history_elements($data);
	break;

default:
	$response=array('state'=>404,'resp'=>_('Operation not found'));
	echo json_encode($response);

}


function get_customer_history_elements($data) {
	$customer_key=$data['customer_key'];
	$elements_number=array('Notes'=>0,'Orders'=>0,'Changes'=>0,'Attachments'=>0,'Emails'=>0,'WebLog'=>0);
	$sql=sprintf("select count(*) as num , `Type` from  `Customer History Bridge` where `Customer Key`=%d group by `Type`",$customer_key);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_number[$row['Type']]=$row['num'];
	}

	echo json_encode(array('elements_numbers'=>$elements_number));


}

function list_customer_orders() {
	$conf=$_SESSION['state']['customer']['orders'];


	if (isset( $_REQUEST['customer_key'])) {
		$customer_id=$_REQUEST['customer_key'];
		$_SESSION['state']['customer']['id']=$customer_id;
	} else
		$customer_id=$_SESSION['state']['customer']['id'];




	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$conf['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf['to'];




	if (isset( $_REQUEST['tid']))
		$tableid=$_REQUEST['tid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_SESSION['state']['customer']['id']=$customer_id;
	$_SESSION['state']['customer']['orders']['order']=$order;
	$_SESSION['state']['customer']['orders']['order_dir']=$order_direction;
	$_SESSION['state']['customer']['orders']['nr']=$number_results;
	$_SESSION['state']['customer']['orders']['sf']=$start_from;
	$_SESSION['state']['customer']['orders']['f_field']=$f_field;
	$_SESSION['state']['customer']['orders']['f_value']=$f_value;



	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['customer']['table']['from'],$_SESSION['state']['customer']['table']['to']);
	} else {
		$_SESSION['state']['customer']['orders']['from']=$date_interval['from'];
		$_SESSION['state']['customer']['orders']['to']=$date_interval['to'];
	}




	// $where=sprintf("    where `Current Dispatching State` not in ('Cancelled','Dispatched',) and `Customer Key`=%d  ",$customer_id);
	$where=sprintf(" where true and `Order Customer Key`=%d  ",$customer_id);

	//print "$f_field $f_value  " ;

	$wheref='';
	if ($f_field=='description' and $f_value!='')
		$wheref.=" and ( `Deal Component Terms Description` like '".addslashes($f_value)."%' or `Deal Component Allowance Description` like '".addslashes($f_value)."%'  )   ";
	elseif ($f_field=='code' and $f_value!='') {
		switch ($type) {
		case('Family'):
			$wheref.=" and  `Product Family Code` like '".addslashes($f_value)."%'";
			break;
		case('Department'):
			$wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";

			break;
		default:
			$wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";

		}



	}

	$sql=sprintf("select count(*) as total from `Order Dimension` $where" );
	//print $sql;
	$total=0;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Order Dimension` $where  $wheref ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}

	//print $sql;
	$rtext=$total_records." ".ngettext('Order','Orders',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='public_id') {

		$order='`Order Public ID`';


	}
	elseif ($order=='last_update') {
		$order='`Order Last Updated Date`';
	}
	elseif ($order=='current_state') {
		$order='`Order Current XHTML State`';
	}
	elseif ($order=='order_date') {
		$order='`Order Date`';
	}
	elseif ($order=='total_amount') {
		$order='`Order Invoiced Balance Total Amount`';
	}






	$adata=array();
	//  $sql=sprintf("select  count(distinct `Order Key`) as `Number of Orders`,sum(`Order Quantity`) as `Order Quantity`,`Current Dispatching State` ,`Product Code`,`Product Family Code`,PD.`Product Family Key`,PD.`Product Main Department Key`,D.`Product Department Code` ,`Product Family Name` , `Product XHTML Short Description` ,`Product Department Name` from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`) left join `Product Department Dimension` D on (D.`Product Department Key`=`Product Main Department Key`)   $where " ,$customer_id);

	// $sql.=" $wheref ";
	// $sql.=sprintf("  group by `%s`   order by $order $order_direction limit $start_from,$number_results   ",$group_by);
	//  $sql="select `Order Invoiced Total Tax Adjust Amount`,`Order Invoiced Total Net Adjust Amount`,`Order Adjust Amount`,`Order Out of Stock Amount `,`Order Invoiced Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,`Order Key`,`Order Public ID`,`Order Customer Key`,`Order Customer Name`,`Order Last Updated Date`,`Order Date`,`Order Total Amount` ,`Order Current XHTML State` from `Order Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
	$sql="select `Order Balance Total Amount`,`Order Current Payment State`,`Order Current Dispatch State`,`Order Out of Stock Net Amount`,`Order Invoiced Total Net Adjust Amount`,`Order Invoiced Total Tax Adjust Amount`,FORMAT(`Order Invoiced Total Net Adjust Amount`+`Order Invoiced Total Tax Adjust Amount`,2) as `Order Adjust Amount`,`Order Out of Stock Net Amount`,`Order Out of Stock Tax Amount`,FORMAT(`Order Out of Stock Net Amount`+`Order Out of Stock Tax Amount`,2) as `Order Out of Stock Amount`,`Order Invoiced Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,`Order Key`,`Order Public ID`,`Order Customer Key`,`Order Customer Name`,`Order Last Updated Date`,`Order Date`,`Order Total Amount` ,`Order Current XHTML State` from `Order Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

	$res = mysql_query($sql);
	//print_r($sql);
	$total=mysql_num_rows($res);
	//print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$mark_out_of_stock="<span style='visibility:hidden'>&otimes;</span>";
		$mark_out_of_credits="<span style='visibility:hidden'>&crarr;</span>";
		$mark_out_of_error="<span style='visibility:hidden'>&epsilon;</span>";
		$out_of_stock=false;
		$errors=false;
		$refunded=false;
		if ($row['Order Out of Stock Amount']!=0) {
			$out_of_stock=true;
			$info='';
			if ($row['Order Out of Stock Net Amount']!=0) {
				$info.=_('Net').': '.money($row['Order Out of Stock Net Amount'],$row['Order Currency'])."";
			}
			if ($row['Order Out of Stock Tax Amount']!=0) {
				$info.='; '._('Tax').': '.money($row['Order Out of Stock Tax Amount'],$row['Order Currency']);
			}
			$info=preg_replace('/^\;\s*/','',$info);
			$mark_out_of_stock="<span style='color:brown'  title='$info'  >&otimes;</span>";

		}

		if ($row['Order Adjust Amount']<-0.01 or $row['Order Adjust Amount']>0.01 ) {
			$errors=true;
			$info='';
			if ($row['Order Invoiced Total Net Adjust Amount']!=0) {
				$info.=_('Net').': '.money($row['Order Invoiced Total Net Adjust Amount'],$row['Order Currency'])."";
			}
			if ($row['Order Invoiced Total Tax Adjust Amount']!=0) {
				$info.='; '._('Tax').': '.money($row['Order Invoiced Total Tax Adjust Amount'],$row['Order Currency']);
			}
			$info=_('Errors').' '.preg_replace('/^\;\s*/','',$info);
			if ($row['Order Adjust Amount']<-1 or $row['Order Adjust Amount']>1 ) {
				$mark_out_of_error ="<span style='color:red' title='$info'>&epsilon;</span>";
			} else {
				$mark_out_of_error ="<span style='color:brown'  title='$info'>&epsilon;</span>";
			}
			//$mark_out_of_error.=$row['Order Adjust Amount'];
		}


		if (!$out_of_stock and !$refunded)
			$mark=$mark_out_of_error.$mark_out_of_stock.$mark_out_of_credits;
		elseif (!$refunded and $out_of_stock and $errors)
			$mark=$mark_out_of_stock.$mark_out_of_error.$mark_out_of_credits;
		else
			$mark=$mark_out_of_stock.$mark_out_of_credits.$mark_out_of_error;


		$adata[]=array(
			'public_id'=>sprintf("<a href='order.php?id=%d'>%s</a>",$row['Order Key'],$row['Order Public ID']),
			'last_update'=>strftime("%a %e %b %Y %T", strtotime($row['Order Last Updated Date'].' UTC')) ,
			'current_state'=>$row['Order Current XHTML State'],
			'order_date'=>strftime("%a %e %b %Y", strtotime($row['Order Date'].' UTC')) ,
			'total_amount'=>money($row['Order Balance Total Amount'],$row['Order Currency']).$mark,


		);
	}
	mysql_free_result($res);
	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);

}




function list_assets_dispatched_to_customer() {
	$conf=$_SESSION['state']['customer']['assets'];




	if (isset( $_REQUEST['customer_key'])) {
		$customer_id=$_REQUEST['customer_key'];
		$_SESSION['state']['customer']['id']=$customer_id;
	} else
		$customer_id=$_SESSION['state']['customer']['id'];




	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$conf['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf['to'];



	if (isset( $_REQUEST['type']))
		$type=$_REQUEST['type'];
	else
		$type=$conf['type'];

	if (isset( $_REQUEST['tid']))
		$tableid=$_REQUEST['tid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_SESSION['state']['customer']['id']=$customer_id;
	$_SESSION['state']['customer']['assets']=array('type'=>$type,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'f_field'=>$f_field,'f_value'=>$f_value);
	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['customer']['table']['from'],$_SESSION['state']['customer']['table']['to']);
	} else {
		$_SESSION['state']['customer']['assets']['from']=$date_interval['from'];
		$_SESSION['state']['customer']['assets']['to']=$date_interval['to'];
	}

	switch ($type) {
	case('Family'):
		$group_by='Product Family Key';
		$subject='Product Family Code';
		$description='Product Family Name';
		$subject_label='family';
		$subject_label_plural='families';
		break;
	case('Department'):
		$group_by='Product Department Key';
		$description='Product Department Name';

		$subject='Product Department Code';
		$subject_label='department';
		$subject_label_plural='departments';
		break;
	default:
		$group_by='Product Code';
		$subject='Product Code';
		$description='Product XHTML Short Description';

		$subject_label='product';
		$subject_label_plural='products';
	}



	$where=sprintf("    where `Current Dispatching State` not in ('Cancelled') and `Customer Key`=%d  ",$customer_id);

	//print "$f_field $f_value  " ;

	$wheref='';
	if ($f_field=='description' and $f_value!='')
		$wheref.=" and ( `Deal Component Terms Description` like '".addslashes($f_value)."%' or `Deal Component Allowance Description` like '".addslashes($f_value)."%'  )   ";
	elseif ($f_field=='code' and $f_value!='') {
		switch ($type) {
		case('Family'):
			$wheref.=" and  `Product Family Code` like '".addslashes($f_value)."%'";
			break;
		case('Department'):
			$wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";

			break;
		default:
			$wheref.=" and  OTF.`Product Code` like '".addslashes($f_value)."%'";

		}



	}

	$sql=sprintf("select count(distinct OTF.`%s`)  as total  from `Order Transaction Fact` OTF  left join `Product Dimension` PD on (PD.`Product ID`=OTF.`Product ID`)  $where  ",$group_by);

	$total=0;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(distinct OTF.`$group_by`)  as total   from `Order Transaction Fact` OTF  left join `Product Dimension` PD on (PD.`Product ID`=OTF.`Product ID`)  $where  $wheref ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=$total_records." ".ngettext($subject_label,$subject_label_plural,$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='subject') {
		switch ($type) {
		case('Family'):
			$order='`Product Family Code`';
			break;
		case('Department'):
			$order='`Product Department Code`';
			break;
		default:
			$order='`Product Code`';
		}

	}
	elseif ($order=='description') {
		switch ($type) {
		case('Family'):
			$order='`Product Family Name`';
			break;
		case('Department'):
			$order='`Product Department Name`';
			break;
		default:
			$order='`Product XHTML Short Description`';
		}


	}
	elseif ($order=='dispatched') {
		$order='`Delivery Note Quantity`';
	}
	elseif ($order=='orders') {
		$order='`Number of Orders`';
	}
	elseif ($order=='ordered') {
		$order='`Order Quantity`';
	}

	$adata=array();
	$sql=sprintf("select  count(distinct `Order Key`) as `Number of Orders`,sum(`Order Quantity`) as `Order Quantity`,sum(`Delivery Note Quantity`) as `Delivery Note Quantity` ,OTF.`Product Code`,`Product Family Code`,OTF.`Product Family Key`,OTF.`Product Department Key`,D.`Product Department Code` ,`Product Family Name` , `Product XHTML Short Description` ,`Product Department Name` from `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product ID`=OTF.`Product ID`) left join `Product Department Dimension` D on (D.`Product Department Key`=OTF.`Product Department Key`)   $where " ,$customer_id);
	$sql.=" $wheref ";
	$sql.=sprintf("  group by `%s`   order by $order $order_direction limit $start_from,$number_results   ",$group_by);
	//  print $sql;
	$res = mysql_query($sql);

	$total=mysql_num_rows($res);

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$adata[]=array(
			'subject'=>$row[$subject],
			'description'=>$row[$description],
			'ordered'=>number($row['Order Quantity']),
			'dispatched'=>number($row['Delivery Note Quantity']),
			'orders'=>number($row['Number of Orders']),
		);
	}
	mysql_free_result($res);
	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);

}

function list_assets_in_process_customer() {
	$conf=$_SESSION['state']['customer']['assets'];
	if (isset( $_REQUEST['id']))
		$customer_id=$_REQUEST['id'];
	else
		$customer_id=$_SESSION['state']['customer']['id'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$conf['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf['to'];



	if (isset( $_REQUEST['type']))
		$type=$_REQUEST['type'];
	else
		$type=$conf['type'];

	if (isset( $_REQUEST['tid']))
		$tableid=$_REQUEST['tid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_SESSION['state']['customer']['id']=$customer_id;
	$_SESSION['state']['customer']['assets']=array('type'=>$type,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'f_field'=>$f_field,'f_value'=>$f_value);
	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['customer']['table']['from'],$_SESSION['state']['customer']['table']['to']);
	} else {
		$_SESSION['state']['customer']['assets']['from']=$date_interval['from'];
		$_SESSION['state']['customer']['assets']['to']=$date_interval['to'];
	}

	switch ($type) {
	case('Family'):
		$group_by='Product Family Key';
		$subject='Product Family Code';
		$description='Product Family Name';
		$subject_label='family';
		$subject_label_plural='families';
		break;
	case('Department'):
		$group_by='Product Department Key';
		$description='Product Department Name';

		$subject='Product Department Code';
		$subject_label='department';
		$subject_label_plural='departments';
		break;
	default:
		$group_by='Product Code';
		$subject='Product Code';
		$description='Product XHTML Short Description';

		$subject_label='product';
		$subject_label_plural='products';
	}



	$where=sprintf("    where `Current Dispatching State` not in ('Cancelled','Dispatched',) and `Customer Key`=%d  ",$customer_id);


	//print "$f_field $f_value  " ;

	$wheref='';
	if ($f_field=='description' and $f_value!='')
		$wheref.=" and ( `Deal Component Terms Description` like '".addslashes($f_value)."%' or `Deal Component Allowance Description` like '".addslashes($f_value)."%'  )   ";
	elseif ($f_field=='code' and $f_value!='') {
		switch ($type) {
		case('Family'):
			$wheref.=" and  `Product Family Code` like '".addslashes($f_value)."%'";
			break;
		case('Department'):
			$wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";

			break;
		default:
			$wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";

		}



	}

	$sql=sprintf("select count(distinct `%s`)  as total  from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`)  $where  ",$group_by);
	//print $sql;
	$total=0;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(distinct `$group_by`)  as total   from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`)  $where  $wheref ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}

	//print $sql;
	$rtext=$total_records." ".ngettext($subject_label,$subject_label_plural,$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='subject') {
		switch ($type) {
		case('Family'):
			$order='`Product Family Code`';
			break;
		case('Department'):
			$order='`Product Department Code`';
			break;
		default:
			$order='`Product Code`';
		}

	}
	elseif ($order=='description') {
		switch ($type) {
		case('Family'):
			$order='`Product Family Name`';
			break;
		case('Department'):
			$order='`Product Department Name`';
			break;
		default:
			$order='`Product XHTML Short Description`';
		}


	}

	$adata=array();
	$sql=sprintf("select  count(distinct `Order Key`) as `Number of Orders`,sum(`Order Quantity`) as `Order Quantity`,`Current Dispatching State` ,`Product Code`,`Product Family Code`,PD.`Product Family Key`,PD.`Product Main Department Key`,D.`Product Department Code` ,`Product Family Name` , `Product XHTML Short Description` ,`Product Department Name` from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`) left join `Product Department Dimension` D on (D.`Product Department Key`=`Product Main Department Key`)   $where " ,$customer_id);

	$sql.=" $wheref ";
	$sql.=sprintf("  group by `%s`   order by $order $order_direction limit $start_from,$number_results   ",$group_by);

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);
	//print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$adata[]=array(
			'subject'=>$row[$subject],
			'description'=>$row[$description],'product_code'=>$row['Product Code'],
			'ordered'=>number($row['Order Quantity']),
			'dispatched'=>$row['Current Dispatching State'],
			'orders'=>number($row['Number of Orders']),


		);
	}
	mysql_free_result($res);
	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);

}



function list_companies() {
	$conf=$_SESSION['state']['companies']['table'];
	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$_SESSION['state']['companies']['view'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;




	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent=$conf['parent'];

	if (isset( $_REQUEST['mode']))
		$mode=$_REQUEST['mode'];
	else
		$mode=$conf['mode'];

	if (isset( $_REQUEST['restrictions']))
		$restrictions=$_REQUEST['restrictions'];
	else
		$restrictions=$conf['restrictions'];




	$_SESSION['state']['companies']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
		,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
	);




	$group='';


	switch ($restrictions) {
	case('forsale'):
		$where.=sprintf(" and `Product Sales State`='For Sale'  ");
		break;
	case('editable'):
		$where.=sprintf(" and `Product Sales State` in ('For Sale','In Process','Unknown')  ");
		break;
	case('notforsale'):
		$where.=sprintf(" and `Product Sales State` in ('Not For Sale')  ");
		break;
	case('discontinued'):
		$where.=sprintf(" and `Product Sales State` in ('Discontinued')  ");
		break;
	case('none'):

		break;
	}


	$filter_msg='';

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	//  if(!is_numeric($start_from))
	//        $start_from=0;
	//      if(!is_numeric($number_results))
	//        $number_results=25;


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='company name' and $f_value!='')
		$wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='email' and $f_value!='')
		$wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Company Dimension`  $where $wheref   ";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Product Dimension`  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}
	mysql_free_result($res);

	$rtext=$total_records." ".ngettext('company','companies',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;

	if ($order=='name')
		$order='`Company File As`';
	elseif ($order=='location')
		$order='`Company Main Location`';
	elseif ($order=='email')
		$order='`Company Main Plain Email`';
	elseif ($order=='telephone')
		$order='`Company Main Plain Telephone`';
	elseif ($order=='mobile')
		$order='`Company Main Plain Mobile`';
	elseif ($order=='fax')
		$order='`Company Main Plain FAX`';
	elseif ($order=='town')
		$order='`Address Town`';
	elseif ($order=='contact')
		$order='`Company Main Contact Name`';
	elseif ($order=='address')
		$order='`Company Main Plain Address`';
	elseif ($order=='postcode')
		$order='`Address Postal Code`';
	elseif ($order=='region')
		$order='`Address Country First Division`';
	elseif ($order=='country')
		$order='`Address Country Code`';


	$sql="select  * from `Company Dimension` P left join `Address Dimension` on (`Company Main Address Key`=`Address Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();

	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$id=sprintf('<a href="company.php?id=%d">%04d</a>',$row['Company Key'],$row['Company Key']);
		if ($row['Company Main Contact Key'])
			$contact=sprintf('<a href="company.php?id=%d">%s</a>',$row['Company Main Contact Key'],$row['Company Main Contact Name']);
		else
			$contact='';
		$adata[]=array(

			'company_key'=>$id
			,'id'=>$row['Company Key']
			,'name'=>$row['Company Name']
			,'location'=>$row['Company Main Location']
			,'email'=>$row['Company Main XHTML Email']
			,'telephone'=>$row['Company Main XHTML Telephone']
			,'fax'=>$row['Company Main XHTML FAX']
			,'contact'=>$contact
			,'town'=>$row['Address Town']
			,'postcode'=>$row['Address Postal Code']
			,'region'=>$row['Address Country First Division']
			,'country'=>$row['Address Country Code']
			,'address'=>$row['Company Main XHTML Address']
		);
	}
	mysql_free_result($res);


	// $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);




	echo json_encode($response);

}
function list_company_history() {
	$conf=$_SESSION['state']['company']['table'];

	if (isset( $_REQUEST['id']))
		$company_id=$_REQUEST['id'];
	else
		$company_id=$_SESSION['state']['company']['id'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['details']))
		$details=$_REQUEST['details'];
	else
		$details=$conf['details'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$conf['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf['to'];

	$elements=$conf['elements'];
	if (isset( $_REQUEST['element_orden']))
		$elements['orden']=$_REQUEST['e_orden'];
	if (isset( $_REQUEST['element_h_cust']))
		$elements['h_cust']=$_REQUEST['e_orden'];
	if (isset( $_REQUEST['element_h_cont']))
		$elements['h_cont']=$_REQUEST['e_orden'];
	if (isset( $_REQUEST['element_note']))
		$elements['note']=$_REQUEST['e_orden'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;




	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_SESSION['state']['company']['id']=$company_id;
	$_SESSION['state']['company']['table']=array('details'=>$details,'elements'=>$elements,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['company']['table']['from'],$_SESSION['state']['company']['table']['to']);
	} else {
		$_SESSION['state']['company']['table']['from']=$date_interval['from'];
		$_SESSION['state']['company']['table']['to']=$date_interval['to'];
	}

	$where.=sprintf(' and (  (`Subject`="Company" and  `Subject Key`=%d) or (`Direct Object`="Company" and  `Direct Object key`=%d ) or (`Indirect Object`="Company" and  `Indirect Object key`=%d )         ) ',$company_id,$company_id,$company_id);
	//   if(!$details)
	//    $where.=" and display!='details'";
	//  foreach($elements as $element=>$value){
	//    if(!$value ){
	//      $where.=sprintf(" and objeto!=%s ",prepare_mysql($element));
	//    }
	//  }

	$where.=$date_interval['mysql'];

	$wheref='';



	if ( $f_field=='notes' and $f_value!='' )
		$wheref.=" and   note like '%".addslashes($f_value)."%'   ";
	if ($f_field=='upto' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))<=".$f_value."    ";
	elseif ($f_field=='older' and is_numeric($f_value))
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))>=".$f_value."    ";
	elseif ($f_field=='author' and $f_value!='') {
		if (is_numeric($f_value))
			$wheref.=" and   staff_id=$f_value   ";
		else {
			$wheref.=" and  handle like='".addslashes($f_value)."%'   ";
		}
	}











	$sql="select count(*) as total from  `History Dimension`   $where $wheref ";
	// print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($where=='') {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	} else {

		$sql="select count(*) as total from  `History Dimension`  $where";
		// print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$filtered=$row['total']-$total;
			$total_records=$row['total'];
		}

	}

	mysql_free_result($result);

	$rtext=$total_records." ".ngettext('record','records',$total_records);

	if ($total==0)
		$rtext_rpp='';
	elseif ($total_records>$number_results)
		$rtext_rpp=sprintf('(%d%s)',$number_results,_('rpp'));
	else
		$rtext_rpp=_('Showing all');


	//   print "$f_value $filtered  $total_records  $filter_total";
	$filter_msg='';
	if ($filtered>0) {
		switch ($f_field) {
		case('notes'):
			if ($total==0 and $filtered>0)
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record matching")." <b>$f_value</b> ";
			elseif ($filtered>0)
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record matching','records matching')." <b>$f_value</b>";
			break;
		case('older'):
			if ($total==0 and $filtered>0)
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record older than")." <b>$f_value</b> ".ngettext($f_value,'day','days');
			elseif ($filtered>0)
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record older than','records older than')." <b>$f_value</b> ".ngettext($f_value,'day','days');
			break;
		case('upto'):
			if ($total==0 and $filtered>0)
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record in the last")." <b>$f_value</b> ".ngettext($f_value,'day','days');
			elseif ($filtered>0)
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record in the last','records inthe last')." <b>$f_value</b> ".ngettext($f_value,'day','days')."<span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
			break;


		}
	}



	$_order=$order;
	$_dir=$order_direction;
	if ($order=='date')
		$order='History Date';
	if ($order=='note')
		$order='History Abstract';
	if ($order=='objeto')
		$order='Direct Object';

	$sql="select * from `History Dimension`   $where $wheref  order by `$order` $order_direction limit $start_from,$number_results ";
	//  print $sql;
	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


		$data[]=array(
			'id'=>$row['History Key'],
			'date'=>strftime("%a %e %b %Y", strtotime($row['History Date'])),
			'time'=>strftime("%H:%M", strtotime($row['History Date'])),
			'objeto'=>$row['Direct Object'],
			'note'=>$row['History Abstract'],
			'handle'=>$row['Author Name']
		);
	}
	mysql_free_result($result);
	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			//  'records_returned'=>$start_from+$res->numRows(),
			'records_perpage'=>$number_results,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}
function list_contacts() {
	$conf=$_SESSION['state']['contacts']['table'];
	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$_SESSION['state']['contacts']['view'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent=$conf['parent'];

	if (isset( $_REQUEST['mode']))
		$mode=$_REQUEST['mode'];
	else
		$mode=$conf['mode'];

	if (isset( $_REQUEST['restrictions']))
		$restrictions=$_REQUEST['restrictions'];
	else
		$restrictions=$conf['restrictions'];




	$_SESSION['state']['contacts']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
		,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
	);






	switch ($parent) {
	case('company'):
		$where=sprintf(' where `Contact Company Key`=%d',$_SESSION['state']['company']['id']);
		break;
	case('supplier'):
		$where=sprintf(' left join `Contact Bridge` B on (P.`Contact Key`=B.`Contact Key`) where `Subject Type`="Supplier" and `Subject Key`=%d',$_SESSION['state']['supplier']['id']);
		break;
	case('customer'):
		$where=sprintf(' left join `Contact Bridge` B on (P.`Contact Key`=B.`Contact Key`) where `Subject Type`="Customer" and `Subject Key`=%d',$_SESSION['state']['customer']['id']);
		break;
	default:
		$where=sprintf(" where `Contact Fuzzy`='No' ");

	}
	$group='';
	/*    switch($mode){ */
	/*    case('same_code'): */
	/*      $where.=sprintf(" and `Product Same Code Most Recent`='Yes' "); */
	/*      break; */
	/*    case('same_id'): */
	/*      $where.=sprintf(" and `Product Same ID Most Recent`='Yes' "); */

	/*      break; */
	/*    } */

	switch ($restrictions) {
	case('forsale'):
		$where.=sprintf(" and `Product Sales State`='For Sale'  ");
		break;
	case('editable'):
		$where.=sprintf(" and `Product Sales State` in ('For Sale','In Process','Unknown')  ");
		break;
	case('notforsale'):
		$where.=sprintf(" and `Product Sales State` in ('Not For Sale')  ");
		break;
	case('discontinued'):
		$where.=sprintf(" and `Product Sales State` in ('Discontinued')  ");
		break;
	case('none'):

		break;
	}


	$filter_msg='';

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	//  if(!is_numeric($start_from))
	//        $start_from=0;
	//      if(!is_numeric($number_results))
	//        $number_results=25;


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Contact Name` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='email' and $f_value!='')
		$wheref.=" and  `Contact Main Plain Email` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Contact Dimension`  $where $wheref   ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Contact Dimension`  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}

	mysql_fetch_array($res);
	$rtext=$total_records." ".ngettext('contact','contacts',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('contacts with name like')." <b>".$f_value."*</b>";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('contacts with email like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Contact File As`';
	if ($_order=='name')
		$order='`Contact File As`';
	elseif ($_order=='location')
		$order='`Contact Main Location`';
	elseif ($_order=='email')
		$order='`Contact Main Plain Email`';
	elseif ($_order=='telephone')
		$order='`Contact Main Plain Telephone`';
	elseif ($_order=='mobile')
		$order='`Contact Main Plain Mobile`';
	elseif ($_order=='fax')
		$order='`Contact Main Plain FAX`';
	elseif ($_order=='town')
		$order='`Address Town`';
	elseif ($_order=='company')
		$order='`Contact Company Name`';
	elseif ($_order=='address')
		$order='`Contact Main Plain Address`';
	elseif ($_order=='postcode')
		$order='`Address Postal Code`';
	elseif ($_order=='region')
		$order='`Address Country First Division`';
	elseif ($_order=='country')
		$order='`Address Country Code`';
	elseif ($_order=='id')
		$order='`Contact Key`';


	$sql="select  * from `Contact Dimension` P left join `Address Dimension` on (`Contact Main Address Key`=`Address Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();

	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$id=sprintf('<a href="contact.php?id=%d">%04d</a>',$row['Contact Key'],$row['Contact Key']);
		if ($row['Contact Company Key'])
			$company=sprintf('<a href="company.php?id=%d">%s</a>',$row['Contact Company Key'],$row['Contact Company Name']);
		else
			$company='';
		$adata[]=array(

			'id'=>$id
			,'name'=>$row['Contact Name']
			,'location'=>$row['Contact Main Location']
			,'email'=>$row['Contact Main XHTML Email']
			,'telephone'=>$row['Contact Main XHTML Telephone']
			,'mobile'=>$row['Contact Main XHTML Mobile']
			,'fax'=>$row['Contact Main XHTML FAX']
			,'company'=>$company
			,'town'=>$row['Address Town']
			,'postcode'=>$row['Address Postal Code']
			,'region'=>$row['Address Country First Division']
			,'country'=>$row['Address Country Code']
			,'address'=>$row['Contact Main XHTML Address']
		);
	}
	mysql_fetch_array($res);


	// $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);




	echo json_encode($response);
}




function list_customers() {

	global $myconf,$output_type,$user;

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		return;
	}
	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		return;
	}



	switch ($parent) {
	case 'store':
		$conf_table='customers';
		break;
	case 'category':
		$conf_table='customer_categories';
		break;
	case 'list':
		$conf_table='customers_list';
		break;
	}

	$conf=$_SESSION['state'][$conf_table]['customers'];



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];


	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
		
		
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['where']))
		$awhere=$_REQUEST['where'];
	else
		$awhere=$conf['where'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];

	if (isset( $_REQUEST['elements_Active'])) {
		$elements['activity']['Active']=$_REQUEST['elements_Active'];
	}


	if (isset( $_REQUEST['elements_Lost'])) {
		$elements['activity']['Lost']=$_REQUEST['elements_Lost'];
	}


	if (isset( $_REQUEST['elements_Losing'])) {
		$elements['activity']['Losing']=$_REQUEST['elements_Losing'];
	}

	if (isset( $_REQUEST['elements_Normal'])) {
		$elements['level_type']['Normal']=$_REQUEST['elements_Normal'];
	}

	if (isset( $_REQUEST['elements_Partner'])) {
		$elements['level_type']['Partner']=$_REQUEST['elements_Partner'];
	}

	if (isset( $_REQUEST['elements_VIP'])) {
		$elements['level_type']['VIP']=$_REQUEST['elements_VIP'];
	}
	if (isset( $_REQUEST['elements_Staff'])) {
		$elements['level_type']['Staff']=$_REQUEST['elements_Staff'];
	}
	
	if (isset( $_REQUEST['elements_Domestic'])) {
		$elements['location']['Domestic']=$_REQUEST['elements_Domestic'];
	}	
	if (isset( $_REQUEST['elements_Export'])) {
		$elements['location']['Export']=$_REQUEST['elements_Export'];
	}
	
	if (isset( $_REQUEST['elements_type'])) {
		$elements_type=$_REQUEST['elements_type'];
	}else {
		$elements_type=$_SESSION['state'][$conf_table]['customers']['elements_type'];
	}
	if (isset( $_REQUEST['orders_type'])) {
		$orders_type=$_REQUEST['orders_type'];
	}else {
		$orders_type=$_SESSION['state'][$conf_table]['customers']['orders_type'];
	}


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state'][$conf_table]['customers']['elements']=$elements;
	$_SESSION['state'][$conf_table]['customers']['elements_type']=$elements_type;
	$_SESSION['state'][$conf_table]['customers']['orders_type']=$orders_type;

	$_SESSION['state'][$conf_table]['customers']['order']=$order;
	$_SESSION['state'][$conf_table]['customers']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_table]['customers']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['customers']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['customers']['where']=$awhere;
	$_SESSION['state'][$conf_table]['customers']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['customers']['f_value']=$f_value;


	include_once('splinters/customers_prepare_list.php');



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
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';



	//if($total_records>$number_results)
	// $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

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





	$_order=$order;
	$_dir=$order_direction;
	// if($order=='location'){
	//      if($order_direction=='desc')
	//        $order='country_code desc ,town desc';
	//      else
	//        $order='country_code,town';
	//      $order_direction='';
	//    }

	//     if($order=='total'){
	//       $order='supertotal';
	//    }


	if ($order=='name')
		$order='`Customer File As`';
	elseif ($order=='id')
		$order='C.`Customer Key`';
	elseif ($order=='location')
		$order='`Customer Main Location`';
	elseif ($order=='orders')
		$order='`Customer Orders`';
	elseif ($order=='email')
		$order='`Customer Main Plain Email`';
	elseif ($order=='telephone')
		$order='`Customer Main Plain Telephone`';
	elseif ($order=='last_order')
		$order='`Customer Last Order Date`';
	elseif ($order=='contact_name')
		$order='`Customer Main Contact Name`';
	elseif ($order=='address')
			$order='`Customer Main Plain Address`';
	elseif ($order=='town')
		$order='`Customer Main Town`';
	elseif ($order=='postcode')
		$order='`Customer Main Postal Code`';
	elseif ($order=='region')
		$order='`Customer Main Country First Division`';
	elseif ($order=='country')
		$order='`Customer Main Country`';
	//  elseif($order=='ship_address')
	//  $order='`customer main ship to header`';
	elseif ($order=='ship_town')
		$order='`Customer Main Delivery Address Town`';
	elseif ($order=='ship_postcode')
		$order='`Customer Main Delivery Address Postal Code`';
	elseif ($order=='ship_region')
		$order='`Customer Main Delivery Address Country Region`';
	elseif ($order=='ship_country')
		$order='`Customer Main Delivery Address Country`';
	elseif ($order=='net_balance')
		$order='`Customer Net Balance`';
	elseif ($order=='balance')
		$order='`Customer Outstanding Net Balance`';
	elseif ($order=='total_profit')
		$order='`Customer Profit`';
	elseif ($order=='total_payments')
		$order='`Customer Net Payments`';
	elseif ($order=='top_profits')
		$order='`Customer Profits Top Percentage`';
	elseif ($order=='top_balance')
		$order='`Customer Balance Top Percentage`';
	elseif ($order=='top_orders')
		$order='``Customer Orders Top Percentage`';
	elseif ($order=='top_invoices')
		$order='``Customer Invoices Top Percentage`';
	elseif ($order=='total_refunds')
		$order='`Customer Total Refunds`';
	elseif ($order=='contact_since')
		$order='`Customer First Contacted Date`';
	elseif ($order=='activity')
		$order='`Customer Type by Activity`';
	elseif ($order=='logins')
		$order='`Customer Number Web Logins`';
	elseif ($order=='failed_logins')
		$order='`Customer Number Web Failed Logins`';
	elseif ($order=='requests')
		$order='`Customer Number Web Requests`';
	else
		$order='`Customer File As`';


	$sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  $where_type  $group_by order by $order $order_direction ".($output_type=='ajax'?"limit $start_from,$number_results":'');

	$adata=array();



	$result=mysql_query($sql);

	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


		if ($parent=='category') {
			$category_other_value=$data['Other Note'];
		}else {
			$category_other_value='x';
		}


		$id="<a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';
		if ($data['Customer Type']=='Person') {
			$name='<img src="art/icons/user.png" alt="('._('Person').')">';
		} else {
			$name='<img src="art/icons/building.png" alt="('._('Company').')">';

		}

		$name.=" <a href='customer.php?p=cs&id=".$data['Customer Key']."'>".($data['Customer Name']==''?'<i>'._('Unknown name').'</i>':$data['Customer Name']).'</a>';



		if ($data['Customer Orders']==0)
			$last_order_date='';
		else
			$last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

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
			'id'=>$id,
			'name'=>$name,
			'location'=>$data['Customer Main Location'],
			
			'invoices'=>$data['Customer Orders Invoiced'],
			'email'=>$data['Customer Main XHTML Email'],
			'telephone'=>$data['Customer Main XHTML Telephone'],
			'orders'=>number($data['Customer Orders']),
			'last_order'=>$last_order_date,
			'contact_since'=>$contact_since,
			
			'other_value'=>$category_other_value,

			'total_payments'=>money($data['Customer Net Payments'],$currency),
			'net_balance'=>money($data['Customer Net Balance'],$currency),
			'total_refunds'=>money($data['Customer Net Refunds'],$currency),
			'total_profit'=>money($data['Customer Profit'],$currency),
			'balance'=>money($data['Customer Outstanding Net Balance'],$currency),


			'top_orders'=>percentage($data['Customer Orders Top Percentage'],1,2),
			'top_invoices'=>percentage($data['Customer Invoices Top Percentage'],1,2),
			'top_balance'=>percentage($data['Customer Balance Top Percentage'],1,2),
			'top_profits'=>percentage($data['Customer Profits Top Percentage'],1,2),
			'contact_name'=>$data['Customer Main Contact Name'],
			'address'=>$data['Customer Main XHTML Address'],
			'billing_address'=>$billing_address,
			'delivery_address'=>$delivery_address,

			'activity'=>$activity,
			'logins'=>number($data['Customer Number Web Logins']),
			'failed_logins'=>number($data['Customer Number Web Failed Logins']),
			'requests'=>number($data['Customer Number Web Requests']),


		);
		///if(isset($_REQUEST['textValue'])&isset($_REQUEST['typeValue']))
		///{
		/// $list_name=$_REQUEST['textValue'];
		/// $list_type=$_REQUEST['typeValue'];
		///}
		///$dataid[]=array('id'=>$id,'list_name'=>$list_name,'list_type'=>$list_type);//
	}




	mysql_free_result($result);

	///print_r($dataid);//



	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total

		)
	);
	if ($output_type=='ajax') {
		echo json_encode($response);
		return;
	} else {
		return $response;
	}
}



function list_customers_send_post() {


	global $myconf;

	$conf=$_SESSION['state']['customers']['customers'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['type']))
		$type=$_REQUEST['type'];
	else
		$type=$conf['type'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))



		$awhere=$_REQUEST['where'];
	else
		$awhere=$conf['where'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	/*   if (isset( $_REQUEST['store_id'])    ) {
           $store=$_REQUEST['store_id'];
           $_SESSION['state']['customers']['store']=$store;
       } else
           $store=$_SESSION['state']['customers']['store'];
    */

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_SESSION['state']['customers']['customers']['order']=$order;
	$_SESSION['state']['customers']['customers']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['customers']['nr']=$number_results;
	$_SESSION['state']['customers']['customers']['sf']=$start_from;
	$_SESSION['state']['customers']['customers']['where']=$awhere;
	$_SESSION['state']['customers']['customers']['type']=$type;
	$_SESSION['state']['customers']['customers']['f_field']=$f_field;
	$_SESSION['state']['customers']['customers']['f_value']=$f_value;


	$table='`Customer Send Post` CSD ';




	$where="where `Send Post Status`='To Send'";


	$wheref='';



	$sql="select count(Distinct CSD.`Customer Key`) as total from $table  $where $wheref";
	//print "$sql<br/>\n";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from $table  $where ";
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


	$rtext=$total_records." ".ngettext('customer','customers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_("Showing all customers");



	//if($total_records>$number_results)
	// $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

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





	$_order=$order;
	$_dir=$order_direction;

	$order='C.`Customer Key`';
	$sql="select   * from $table left join `Customer Dimension` C on (C.`Customer Key`=CSD.`Customer Key`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results";
	// print $sql;
	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


		$id="<a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';
		if ($data['Customer Type']=='Person') {
			$name='<img src="art/icons/user.png" alt="('._('Person').')">';
		} else {
			$name='<img src="art/icons/building.png" alt="('._('Company').')">';

		}

		$name.=" <a href='customer.php?p=cs&id=".$data['Customer Key']."'>".($data['Customer Name']==''?'<i>'._('Unknown name').'</i>':$data['Customer Name']).'</a>';



		if ($data['Customer Orders']==0)
			$last_order_date='';
		else
			$last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

		$contact_since=strftime("%e %b %y", strtotime($data['Customer First Contacted Date']." +00:00"));
		$created_on=strftime("%e %b %y", strtotime($data['Date Creation']." +00:00"));

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
		$checkbox='<input type="checkbox" name="check[]" id="check[]"  value='.$data['Customer Key'].'>';

		$adata[]=array(
			'ch'=>$checkbox,
			'id'=>$id,
			'name'=>$name,
			'location'=>$data['Customer Main Location'],
			'orders'=>number($data['Customer Orders']),
			'invoices'=>$data['Customer Orders Invoiced'],
			'email'=>$data['Customer Main XHTML Email'],
			'telephone'=>$data['Customer Main XHTML Telephone'],
			'last_order'=>$last_order_date,
			'created_on'=>$created_on,

			/*    'total_payments'=>money($data['Customer Net Payments'],$currency),
                         'net_balance'=>money($data['Customer Net Balance'],$currency),
                         'total_refunds'=>money($data['Customer Net Refunds'],$currency),
                         'total_profit'=>money($data['Customer Profit'],$currency),
                         'balance'=>money($data['Customer Outstanding Net Balance'],$currency),
                     */

			'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
			'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
			'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
			'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
			'contact_name'=>$data['Customer Main Contact Name'],
			'address'=>$data['Customer Main XHTML Address'],
			'billing_address'=>$billing_address,
			'delivery_address'=>$delivery_address,

			//'town'=>$data['Customer Main Town'],
			//'postcode'=>$data['Customer Main Postal Code'],
			//'region'=>$data['Customer Main Country First Division'],
			//'country'=>$data['Customer Main Country'],
			//'ship_address'=>$data['customer main ship to header'],
			//'ship_town'=>$data['Customer Main Delivery Address Town'],
			//'ship_postcode'>$data['Customer Main Delivery Address Postal Code'],
			//'ship_region'=>$data['Customer Main Delivery Address Region'],
			//'ship_country'=>$data['Customer Main Delivery Address Country'],
			'activity'=>$data['Customer Type by Activity'],
			'email'=>$data['Customer Main XHTML Email']
		);
		///if(isset($_REQUEST['textValue'])&isset($_REQUEST['typeValue']))
		///{
		/// $list_name=$_REQUEST['textValue'];
		/// $list_type=$_REQUEST['typeValue'];
		///}
		///$dataid[]=array('id'=>$id,'list_name'=>$list_name,'list_type'=>$list_type);//
	}
	mysql_free_result($result);

	///print_r($dataid);//


	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);

}












function show_posible_customer_matches($the_data) {



	$found_email=false;
	$found_name='';
	$candidates_data=array();
	// print_r($data);

	$data=$the_data['values'];


	if ($data['Customer Type']=='Person') {
		$data['Customer Name']=$data['Customer Main Contact Name'];
		$data['Customer Company Name']='';
	} else {
		$data['Customer Company Name']=$data['Customer Name'];
	}



	$scope='Customer';
	$store_key=$the_data['scope']['store_key'];






	// quick try to find the email
	if ($data['Customer Main Plain Email']!='') {
		$sql=sprintf("select T.`Email Key`,`Subject Key` from `Email Dimension` T left join `Email Bridge` TB  on (TB.`Email Key`=T.`Email Key`) where `Email`=%s and `Subject Type`='Contact'  "
			,prepare_mysql($data['Customer Main Plain Email'])
		);
		//print $sql;
		$scope_found_key=0;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result)) {
			$contact=new Contact($row['Subject Key']);


			$customer_keys=$contact->get_parent_keys('Customer');
			// print_r($customer_keys);
			$in_store=false;
			$in_other_store=false;



			foreach ($customer_keys as $customer_key) {
				$link='';
				$customer=new Customer($customer_key);
				$found_email=true;
				$scope_found_key=$customer->id;

				if ($customer->data['Customer Store Key']==$store_key) {
					$in_store=true;
					$found_name=$customer->data['Customer Name'];


					$link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s <a href="customer.php?id=%d">(%s)</a>'
						,_('Warning')
						,_('A customer found with similar data in this store')
						,$customer->id
						,_('Edit Customer')
					);
				} else {

					$other_store=new Store($customer->data['Customer Store Key']);
					$link.=sprintf('<br/>%s (<span onClick="clone_customer(%d)">%s</span>)'
						,_('Customer in another store').' ('.$other_store->data['Store Code'].')'
						,$customer->id
						,_('Use contact data to create new customer in this store')
					);

					$in_other_store=true;

				}

			}


			$candidates_data[]= array('link'=>$link,'card'=>$customer->display('card'),'score'=>1000,'key'=>$customer->id,'tipo'=>'customer','scope_tipo'=>$customer->data['Customer Type'],'found'=>1);




		}



	}




	$max_results=8;


	if ($data['Customer Type']=='Person')
		$subject=new contact('find from customer complete',$data);
	else
		$subject=new company('find from customer complete',$data);


	$found_key=0;

	if ($found_email) {
		if ($in_other_store)
			$action='found_email_other_store';
		else
			$action='found_email';
		$found_key=$scope_found_key;
	}
	elseif ($subject->found) {
		// $action='found';

		//$found_key=$contact->found_key;

		$customer_found_keys=$subject->get_customer_keys();

		if (count($customer_found_keys)>0) {
			foreach ($customer_found_keys as $customer_found_key) {
				$tmp_customer=new Customer($customer_found_key);
				if ($tmp_customer->id) {
					if ($tmp_customer->data['Customer Store Key']==$store_key) {
						$action='found';

						$found_key=$tmp_customer->id;
					} else {
						$found_in_another_store=true;
						$found_key_in_another_store=$tmp_customer->id;


					}
					$found_name=$tmp_customer->data['Customer Name'];
				}
			}
		}
	}
	else {
		$action='nothing_found';
	}

	$count=0;
	foreach ($subject->candidate as $contact_key=>$score) {
		$link='';

		if ($score<20)
			continue;


		if ($count>$max_results)
			break;
		$_contact=new Contact ($contact_key);



		$scope_found_key=0;

		$customer_keys=$_contact->get_customer_keys('Customer');
		$in_store=false;
		$in_other_store=false;
		// print_r($customer_keys);
		foreach ($customer_keys as $customer_key) {
			$card='';
			// print "** $found_email ::  $found_key $customer_key **";
			if ($found_email and $found_key==$customer_key) {
				continue;
			}
			$tipo_found='customer';
			$customer=new Customer($customer_key);
			$card=$customer->display('card');
			if ($customer->data['Customer Store Key']==$store_key) {
				$in_store=true;
				$scope_found_key=$customer->id;
				$link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s<br/>(<a href="customer.php?id=%d">%s</a>)'
					,_('Warning').":"
					,_('Customer in this store')
					,$customer->id
					,_('Edit Customer')
				);
			}
			elseif (!$in_other_store) {
				$other_store=new Store($customer->data['Customer Store Key']);
				$link.=sprintf('<br/>%s (<span onClick="clone_customer(%d)">%s</span>)'
					,_('Customer in another store').' ('.$other_store->data['Store Code'].')'
					,$customer->id
					,_('Use contact data to create new customer in this store')
				);
				$in_other_store=true;

			}
			$link=preg_replace('/^\<br\/\>/','',$link);

			$found=0;
			if ($subject->found_key==$_contact->id)
				$found=1;
			//$candidates_data[]= array('card'=>$_contact->display('card'),'score'=>$score,'key'=>$_contact->id,'tipo'=>'contact','found'=>$found);
			$candidates_data[]= array('link'=>$link,'card'=>$card,'score'=>$score,'key'=>$scope_found_key,'tipo'=>$tipo_found,'found'=>$found);

			$count++;
		}
	}
	/*
                $supplier_keys=$_contact->get_customer_keys('Supplier');
                foreach($supplier_keys as $supplier_key) {
                    $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_supplier(%s)">%s</span>)'
                                   ,_('Supplier')
                                   ,$supplier_key
                                   ,_('Recollect Data')
                                  );
                                   $card='';
                }
    */



	//print_r($company->candidate_companies);

	$response=array('candidates_data'=>$candidates_data,'action'=>$action,'found_key'=>$found_key,'found_name'=>$found_name);
	echo json_encode($response);
}

function find_customer($the_data) {
	//print_r($the_data);
	$data=$the_data['values'];
	$scope=$the_data['scope']['scope'];
	if ($scope=='customer') {
		$scope='Customer';
		$store_key=$the_data['scope']['store_key'];
	}

	$candidates_data=array();
	// quick try to find the email



	$max_results=8;
	//print_r($data);
	$customer=new Customer('find fuzzy',$data);

	//print_r($customer->candidate);

	//exit;

	$found_key=0;
	if ($customer->found) {
		$action='found';
		$found_key=$customer->found_key;
	}
	elseif (count($customer->candidate)) {
		$action='found_candidates';
	}
	else {
		$action='nothing_found';
	}

	$count=0;
	foreach ($customer->candidate as $contact_key=>$score) {
		if ($score<10)
			break;
		if ($count>$max_results)
			break;
		$_contact=new Contact ($contact_key);

		$link='';

		$scope_found_key=0;
		if ($scope=='Customer') {
			$parent_keys=$_contact->get_parent_keys('Customer');
			$in_store=false;
			$in_other_store=false;
			//print_r($parent_keys);
			foreach ($parent_keys as $parent_key) {
				$parent=new Customer($parent_key);
				if ($parent->data['Customer Store Key']==$store_key) {
					$in_store=true;
					$scope_found_key=$parent->id;
					$link.=sprintf('<br/><a href="customer.php?id=%d">%s</a>'

						,$parent->id
						,_('View Customer')
					);
				}
				elseif (!$in_other_store) {
					$link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
						,_('Customer in another store')
						,$customer->id
						,_('Recollect Data')
					);
					$in_other_store=true;

				}

			}

			$parent_keys=$_contact->get_parent_keys('Supplier');
			foreach ($parent_keys as $parent_key) {
				$link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
					,_('Supplier')
					,$customer->id
					,_('Recollect Data')
				);
			}





		}




		$link=preg_replace('/^\<br\/\>/','',$link);


		$found=0;
		if ($customer->found_key==$_contact->id)
			$found=1;
		$candidates_data[]= array('link'=>$link,'card'=>$_contact->display('card'),'score'=>$score,'key'=>$scope_found_key,'tipo'=>'company','found'=>$found);

		$count++;
	}
	//print_r($customer->candidate_companies);

	$response=array('candidates_data'=>$candidates_data,'action'=>$action,'found_key'=>$found_key,'number_candidates'=>$count);
	echo json_encode($response);
}


function find_company($the_data) {
	//print_r($the_data);
	$data=$the_data['values'];
	$scope=$the_data['scope']['scope'];
	if ($scope=='customer') {
		$scope='Customer';
		$store_key=$the_data['scope']['store_key'];
	}

	$candidates_data=array();
	// quick try to find the email


	if ($data['Company Main Plain Email']!='') {
		$sql=sprintf("select T.`Email Key`,`Subject Key` from `Email Dimension` T left join `Email Bridge` TB  on (TB.`Email Key`=T.`Email Key`) where `Email`=%s and `Subject Type`='Contact'  "
			,prepare_mysql($data['Company Main Plain Email'])
		);


		$scope_found_key=0;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result)) {
			$contact=new Contact($row['Subject Key']);
			$company_key=$contact->company_key();
			if ($company_key) {
				$link='';
				$_company=new Company ($company_key);
				$subject_key=$company_key;
				if ($scope=='Customer') {
					$parent_keys=$_company->get_parent_keys($scope);
					$in_store=false;
					$in_other_store=false;
					foreach ($parent_keys as $parent_key) {
						$parent=new Customer($parent_key);
						if ($parent->data['Customer Store Key']==$store_key) {
							$in_store=true;
							$scope_found_key=$parent->id;
							$link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s <a href="customer.php?id=%d">(%s)</a>'
								,_('Warning')
								,_('A customer found with similar data in this store')
								,$parent->id
								,_('Edit Customer')
							);
						}
						elseif (!$in_other_store) {
							$link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
								,_('A customer found with similar data in other store')
								,$company->id
								,_('Recollect Data')
							);
							$in_other_store=true;

						}

					}


				}

				$candidates_data[]= array('link'=>$link,'card'=>$_company->display('card'),'score'=>1000,'key'=>$scope_found_key,'tipo'=>'company','found'=>1);
			} else {
				$subject_key=$contact->id;


				$link='';

				if ($scope=='Customer') {
					$parent_keys=$contact->get_parent_keys($scope);
					$in_store=false;
					$in_other_store=false;
					foreach ($parent_keys as $parent_key) {
						$parent=new Customer($parent_key);
						if ($parent->data['Customer Store Key']==$store_key) {
							$in_store=true;
							$scope_found_key=$parent->id;
							$link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s (%s)'
								,_('Warning')
								,_('A customer found with similar data in this store')
								,$parent->id
								,_('Edit Customer')
							);
						}
						elseif (!$in_other_store) {
							$link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
								,_('A customer found with similar data in other store')
								,$company->id
								,_('Recollect Data')
							);
							$in_other_store=true;

						}

					}


				}



				$candidates_data[]= array('link'=>$link,'card'=>$contact->display('card'),'score'=>1000,'key'=>$scope_found_key,'tipo'=>'contact','found'=>1);

				$subject_key=$contact->key;
			}



			$response=array('candidates_data'=>$candidates_data,'action'=>'found_email','found_key'=>$scope_found_key);
			echo json_encode($response);
			return;
		}
	}



	$max_results=8;
	//print_r($data);
	$company=new company('find complete',$data);
	$found_key=0;
	if ($company->found) {
		$action='found';
		$found_key=$company->found_key;
	}
	elseif ($company->number_candidate_companies>0)
		$action='found_candidates';
	else
		$action='nothing_found';


	$count=0;
	foreach ($company->candidate_companies as $company_key=>$score) {
		if ($score<10)
			continue;
		if ($count>$max_results)
			break;
		$_company=new Company ($company_key);

		$link='';

		$scope_found_key=0;
		if ($scope=='Customer') {
			$parent_keys=$_company->get_parent_keys($scope);
			$in_store=false;
			$in_other_store=false;
			//  print_r($parent_keys);
			foreach ($parent_keys as $parent_key) {
				$parent=new Customer($parent_key);
				if ($parent->data['Customer Store Key']==$store_key) {
					$in_store=true;
					$scope_found_key=$parent->id;
					$link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s<br/>(<a href="customer.php?id=%d">%s</a>)'
						,_('Warning').":"
						,_('Customer in this store')
						,$parent->id
						,_('Edit Customer')
					);
				}
				elseif (!$in_other_store) {
					$link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
						,_('Customer in another store')
						,$company->id
						,_('Recollect Data')
					);
					$in_other_store=true;

				}

			}

			$parent_keys=$_company->get_parent_keys('Supplier');
			foreach ($parent_keys as $parent_key) {
				$link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
					,_('Supplier')
					,$company->id
					,_('Recollect Data')
				);
			}





		}




		$link=preg_replace('/^\<br\/\>/','',$link);


		$found=0;
		if ($company->found_key==$_company->id)
			$found=1;
		$candidates_data[]= array('link'=>$link,'card'=>$_company->display('card'),'score'=>$score,'key'=>$scope_found_key,'tipo'=>'company','found'=>$found);

		$count++;
	}
	//print_r($company->candidate_companies);

	$response=array('candidates_data'=>$candidates_data,'action'=>$action,'found_key'=>$found_key);
	echo json_encode($response);
}
function find_company_area($data) {
	$extra_where='';
	if ($data['parent_key']) {
		$extra_where=sprintf(' and `Company Key`=%d',$data['parent_key']);
	}

	$adata=array();
	$sql=sprintf("select `Company Area Key` ,`Company Area Code` ,`Company Area Name` from `Company Area Dimension`  where  (`Company Area Name` like '%%%s%%' or `Company Area Code` like '%s%%') %s limit 10"
		,addslashes($data['query'])
		,addslashes($data['query'])
		,$extra_where

	);
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result)) {


		$adata[]=array(

			'key'=>$row['Company Area Key'],
			'code'=>$row['Company Area Code'],
			'name'=>$row['Company Area Name']
		);
	}
	$response=array('data'=>$adata);
	echo json_encode($response);


}

function find_company_department($data) {
	$extra_where='';
	if ($data['parent_key']) {
		$extra_where.=sprintf(' and D.`Company Area Key`=%d',$data['parent_key']);
	}
	if ($data['grandparent_key']) {
		$extra_where.=sprintf(' and `Company Key`=%d',$data['grandparent_key']);
	}

	$adata=array();
	$sql=sprintf("select `Company Department Key` ,`Company Department Code` ,`Company Department Name`,D.`Company Area Key` ,`Company Area Code` ,`Company Area Name` from `Company Department Dimension` D left join `Company Area Dimension` A on (A.`Company Area Key`=D.`Company Area Key`)  where  (`Company Department Name` like '%%%s%%' or `Company Department Code` like '%s%%') %s limit 10"
		,addslashes($data['query'])
		,addslashes($data['query'])
		,$extra_where

	);
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result)) {


		$adata[]=array(

			'key'=>$row['Company Department Key'],
			'code'=>$row['Company Department Code'],
			'name'=>$row['Company Department Name'],
			'area_key'=>$row['Company Area Key'],
			'area_code'=>$row['Company Area Code'],
			'area_name'=>$row['Company Area Name'],
		);
	}
	$response=array('data'=>$adata);
	echo json_encode($response);


}




function find_contact($the_data) {

	$found_email=false;

	$candidates_data=array();
	// print_r($data);

	$data=$the_data['values'];



	$scope=$the_data['scope']['scope'];
	if ($scope=='customer') {
		$scope='Customer';
		$store_key=$the_data['scope']['store_key'];
	}



	// quick try to find the email
	if ($data['Contact Main Plain Email']!='') {
		$sql=sprintf("select T.`Email Key`,`Subject Key` from `Email Dimension` T left join `Email Bridge` TB  on (TB.`Email Key`=T.`Email Key`) where `Email`=%s and `Subject Type`='Contact'  "
			,prepare_mysql($data['Contact Main Plain Email'])
		);
		//print $sql;
		$scope_found_key=0;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result)) {
			$contact=new Contact($row['Subject Key']);

			if ($scope=='Customer') {
				$customer_keys=$contact->get_parent_keys('Customer');
				$in_store=false;
				$in_other_store=false;



				foreach ($customer_keys as $customer_key) {
					$link='';
					$customer=new Customer($customer_key);
					if ($customer->data['Customer Store Key']==$store_key) {
						$in_store=true;
						$scope_found_key=$customer->id;
						$found_email=true;
						$link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s <a href="customer.php?id=%d">(%s)</a>'
							,_('Warning')
							,_('A customer found with similar data in this store')
							,$customer->id
							,_('Edit Customer')
						);
					}
					elseif (!$in_other_store) {

						$other_store=new Store($customer->data['Customer Store Key']);
						$link.=sprintf('<br/>%s (<span onClick="recollect_data_from_customer(%s)">%s</span>)'
							,_('Customer in another store').' ('.$other_store->data['Store Code'].')'
							,$customer->id
							,_('Recollect Data')
						);

						$in_other_store=true;

					}

				}


				$candidates_data[]= array('link'=>$link,'card'=>$customer->display('card'),'score'=>1000,'key'=>$customer->id,'tipo'=>'customer','scope_tipo'=>$customer->data['Customer Type'],'found'=>1);


			}

			/*
            $company_key=$contact->company_key();
            if ($company_key) {
                $link='';
                $_company=new Company ($company_key);
                $subject_key=$company_key;
                if ($scope=='Customer') {
                    $parent_keys=$_company->get_parent_keys($scope);
                    $in_store=false;
                    $in_other_store=false;
                    foreach($parent_keys as $parent_key) {
                        $parent=new Customer($parent_key);
                        if ($parent->data['Customer Store Key']==$store_key) {
                            $in_store=true;
                            $scope_found_key=$parent->id;
                            $link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s <a href="customer.php?id=%d">(%s)</a>'
                                           ,_('Warning')
                                           ,_('A customer found with similar data in this store')
                                           ,$parent->id
                                           ,_('Edit Customer')
                                          );
                        }
                        elseif(!$in_other_store) {

                           $other_store=new Store($parent->data['Customer Store Key']);
                    $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_customer(%s)">%s</span>)'
                                   ,_('Customer in another store').' ('.$other_store->data['Store Code'].')'
                                   ,$parent->id
                                   ,_('Recollect Data')
                                  );

                            $in_other_store=true;

                        }

                    }


                }

                $candidates_data[]= array('link'=>$link,'card'=>$_company->display('card'),'score'=>1000,'key'=>$scope_found_key,'tipo'=>'company','found'=>1);
            } else {
                $subject_key=$contact->id;


                $link='';

                if ($scope=='Customer') {
                    $parent_keys=$contact->get_parent_keys($scope);
                    $in_store=false;
                    $in_other_store=false;
                    foreach($parent_keys as $parent_key) {
                        $parent=new Customer($parent_key);
                        if ($parent->data['Customer Store Key']==$store_key) {
                            $in_store=true;
                            $scope_found_key=$parent->id;
                            $link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s (%s)'
                                           ,_('Warning')
                                           ,_('A customer found with similar data in this store')
                                           ,$parent->id
                                           ,_('Edit Customer')
                                          );
                        }
                        elseif(!$in_other_store) {
                            $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
                                           ,_('A customer found with similar data in other store')
                                           ,$parent->id
                                           ,_('Recollect Data')
                                          );
                            $in_other_store=true;

                        }

                    }


                }



                $candidates_data[]= array('link'=>$link,'card'=>$contact->display('card'),'score'=>1000,'key'=>$scope_found_key,'tipo'=>'contact','found'=>1);

                $subject_key=$contact->id;
            }

            */

			//$response=array('candidates_data'=>$candidates_data,'action'=>'found_email','found_key'=>$scope_found_key);
			//echo json_encode($response);
			//return;
		}



	}




	$max_results=8;

	$contact=new contact('find complete',$data);
	$found_key=0;

	if ($found_email) {
		$action='found_email';

		$found_key=$scope_found_key;
	}
	elseif ($contact->found) {
		$action='found';
		$found_key=$contact->found_key;
	}
	else
		$action='nothing_found';


	$count=0;
	foreach ($contact->candidate as $contact_key=>$score) {
		$link='';

		if ($score<20)
			continue;


		if ($count>$max_results)
			break;
		$_contact=new Contact ($contact_key);



		$scope_found_key=0;
		if ($scope=='Customer') {
			$parent_keys=$_contact->get_parent_keys($scope);
			$in_store=false;
			$in_other_store=false;
			//  print_r($parent_keys);
			foreach ($parent_keys as $parent_key) {
				$parent=new Customer($parent_key);
				if ($parent->data['Customer Store Key']==$store_key) {
					$in_store=true;
					$scope_found_key=$parent->id;
					$link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s<br/>(<a href="customer.php?id=%d">%s</a>)'
						,_('Warning').":"
						,_('Customer in this store')
						,$parent->id
						,_('Edit Customer')
					);
				}
				elseif (!$in_other_store) {
					$other_store=new Store($parent->data['Customer Store Key']);
					$link.=sprintf('<br/>%s (<span onClick="recollect_data_from_customer(%s)">%s</span>)'
						,_('Customer in another store').' ('.$other_store->data['Store Code'].')'
						,$parent->id
						,_('Recollect Data')
					);
					$in_other_store=true;

				}

			}

			$parent_keys=$_contact->get_parent_keys('Supplier');
			foreach ($parent_keys as $parent_key) {
				$link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
					,_('Supplier')
					,$company->id
					,_('Recollect Data')
				);
			}





		}




		$link=preg_replace('/^\<br\/\>/','',$link);












		$found=0;
		if ($contact->found_key==$_contact->id)
			$found=1;
		//$candidates_data[]= array('card'=>$_contact->display('card'),'score'=>$score,'key'=>$_contact->id,'tipo'=>'contact','found'=>$found);
		$candidates_data[]= array('link'=>$link,'card'=>$_contact->display('card'),'score'=>$score,'key'=>$scope_found_key,'tipo'=>'company','found'=>$found);

		$count++;
	}
	//print_r($company->candidate_companies);

	$response=array('candidates_data'=>$candidates_data,'action'=>$action,'found_key'=>$found_key);
	echo json_encode($response);
}


function email_in_other_customer($data) {
	$email=_trim($data['query']);
	$sql=sprintf('select `Customer Name`,`Customer Key` from `Email Dimension` E left join  `Email Bridge` B on (E.`Email Key`=B.`Email Key`) left join  `Customer Dimension` on (`Subject Key`=`Customer Key`)  where `Email`=%s  and `Subject Type`="Customer" and `Subject Key`!=%d and `Customer Store Key`=%d ',
		prepare_mysql($email),
		$data['customer_key'],
		$data['store_key']
	);
	$result=mysql_query($sql);
	//print $sql;
	$num_rows = mysql_num_rows($result);
	if ($num_rows==0) {
		$response=array('state'=>200,'found'=>0,'msg'=>'');
		echo json_encode($response);
	} else {
		$customers='';

		while ($row=mysql_fetch_assoc($result)) {
			$customers.=sprintf(', <a href="customer.php?id=%d">%s (%d)</a>',$row['Customer Key'],$row['Customer Name'],$row['Customer Key']);
		}
		$customers=preg_replace('/^, /','',$customers);



		$response=array('state'=>200,'found'=>1,'msg'=>_('Email found in another').' '.ngettext('customer','customers',$num_rows).'. '.$customers);
		echo json_encode($response);
	}
}

function used_email() {

	$email=$_REQUEST('query');
	$sql=sprintf('select `Subject`,`Subject Key` from `Email Dimension` E left join `Email Bridge` EB on (E.`Email Key`=EB.`Email Key`) where `Email`=%s  '
		,prepare_mysql($email)
	);

	$result=mysql_query($sql);
	$num_rows = mysql_num_rows($result);
	if ($num_rows==0) {
		$response=array('state'=>200,'found'=>0);
		echo json_encode($response);
	}
	elseif ($num_rows==1) {


	}



}






function is_company_department_code() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['company_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$company_key=$_REQUEST['company_key'];

	$sql=sprintf("select `Company Department Key`,`Company Department Name`,`Company Department Code` from `Company Department Dimension` where `Company Key`=%d and `Company Department Code`=%s  "
		,$company_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Company Department <a href="company_department.php?id=%d">%s</a> already has this code (%s)'
			,$data['Company Department Key']
			,$data['Company Department Name']
			,$data['Company Department Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}
function is_company_department_name() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['company_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$company_key=$_REQUEST['company_key'];

	$sql=sprintf("select `Company Department Key`,`Company Department Name`,`Company Department Code` from `Company Department Dimension` where `Company Key`=%d and `Company Department Name`=%s  "
		,$company_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another Company Department <a href="company_department.php?id=%d">(%s)</a> already has this name'
			,$data['Company Department Key']
			,$data['Company Department Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}



function is_department_code() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['department_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$department_key=$_REQUEST['department_key'];

	$sql=sprintf("select `Company Department Key`,`Company Department Name`,`Company Department Code` from `Company Department Dimension` where `Company Department Key`=%d and  `Company Department Code`=%s  "
		,$department_key,prepare_mysql($query)
	);
	$res=mysql_query($sql);
	//print $sql;
	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Company Department <a href="company_department.php?id=%d">%s</a> already has this code (%s)'
			,$data['Company Department Key']
			,$data['Company Department Name']
			,$data['Company Department Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}
function is_department_name() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['department_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$department_key=$_REQUEST['department_key'];

	$sql=sprintf("select `Company Department Key`,`Company Department Name`,`Company Department Code` from `Company Department Dimension` where `Company Department Key`=%d and `Company Department Name`=%s  "
		,$department_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another Company Department <a href="company_department.php?id=%d">(%s)</a> already has this name'
			,$data['Company Department Key']
			,$data['Company Department Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}





function is_position_code() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['position_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$position_key=$_REQUEST['position_key'];

	$sql=sprintf("select `Company Position Key`,`Company Position Title`,`Company Position Code` from `Company Position Dimension` where `Company Position Key`=%d and  `Company Position Code`=%s  "
		,$position_key,prepare_mysql($query)
	);
	$res=mysql_query($sql);
	//print $sql;
	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Company Position <a href="company_position.php?id=%d">%s</a> already has this code (%s)'
			,$data['Company Position Key']
			,$data['Company Position Title']
			,$data['Company Position Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}
}
function is_position_name() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['position_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$position_key=$_REQUEST['position_key'];

	$sql=sprintf("select `Company Position Key`,`Company Position Title`,`Company Position Code` from `Company Position Dimension` where `Company Position Key`=%d and `Company Position Title`=%s  "
		,$position_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another Company Position <a href="company_department.php?id=%d">(%s)</a> already has this name'
			,$data['Company Position Key']
			,$data['Company Position Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function list_customer_categories() {
	$conf=$_SESSION['state']['customer_categories']['subcategories'];


	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	} else {
		exit("no parent");
	}
	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	} else {
		exit("no parent_key");
	}




	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		//  if ($start_from>0) {
		//   $page=floor($start_from/$number_results);
		//   $start_from=$start_from-$page;
		//  }

	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$_SESSION['state']['customer_categories']['subcategories']['order']=$order;
	$_SESSION['state']['customer_categories']['subcategories']['order_dir']=$order_direction;
	$_SESSION['state']['customer_categories']['subcategories']['nr']=$number_results;
	$_SESSION['state']['customer_categories']['subcategories']['sf']=$start_from;
	$_SESSION['state']['customer_categories']['subcategories']['f_field']=$f_field;
	$_SESSION['state']['customer_categories']['subcategories']['f_value']=$f_value;






	$where=sprintf("where `Category Subject`='Customer' and  `Category Parent Key`=%d",
		$parent_key
	);
	//  $where=sprintf("where `Category Subject`='Product'  ");

	// if ($stores_mode=='grouped')
	//  $group=' group by S.`Category Key`';
	// else
	//  $group='';

	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Category Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='label' and $f_value!='')
		$wheref.=" and  `Category Label` like '%".addslashes($f_value)."%'";



	$sql="select count(*) as total   from `Category Dimension`   $where $wheref";

	//$sql=" describe `Category Dimension`;";
	// $sql="select *  from `Category Dimension` where `Category Parent Key`=1 ";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['total'];
		//   print_r($row);
	}
	mysql_free_result($res);

	//exit;
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total  from `Category Dimension`  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=$total_records." ".ngettext('category','categories',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("There isn't any category with code like ")." <b>".$f_value."*</b> ";
			break;
		case('label'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("There isn't any category with label like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total "._('categories with code like')." <b>".$f_value."*</b>";
			break;
		case('label'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total "._('categories with label like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;
	if ($order=='subjects')
		$order='`Category Number Subjects`';
	elseif ($order=='code')
		$order='`Category Code`';
	elseif ($order=='label')
		$order='`Category Label`';


	$sql="select * from `Category Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);


	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$code=sprintf('<a href="customer_category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Code']);
		$adata[]=array(
			'id'=>$row['Category Key'],
			'code'=>$code,
			'label'=>$row['Category Label'],
			'subjects'=>number($row['Category Number Subjects']),
			'subcategories'=>number($row['Category Children']),



		);
	}
	mysql_free_result($res);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}




function marketing_post_lists() {

	global $user;

	$conf=$_SESSION['state']['customers']['list'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];



	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))



		$awhere=$_REQUEST['where'];
	else
		$awhere=$conf['where'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['store_id'])    ) {
		$store=$_REQUEST['store_id'];
		$_SESSION['state']['customers']['store']=$store;
	} else
		$store=$_SESSION['state']['customers']['store'];


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state']['customers']['list']['order']=$order;
	$_SESSION['state']['customers']['list']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['list']['nr']=$number_results;
	$_SESSION['state']['customers']['list']['sf']=$start_from;
	$_SESSION['state']['customers']['list']['where']=$awhere;
	$_SESSION['state']['customers']['list']['f_field']=$f_field;
	$_SESSION['state']['customers']['list']['f_value']=$f_value;


	$where=' true';




	if (in_array($store,$user->stores)) {
		$where.=sprintf(' and `Store Key`=%d  ',$store);

	}

	$wheref='';

	$sql="select count(distinct `Marketing Post Sent Fact Key`) as total from `Marketing Post Sent Fact` where $where  ";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from `Marketing Post Sent Fact` where $where $wheref ";
		//print $sql;
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


	$rtext=$total_records." ".ngettext('Record','Records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_("Showing all Records");




	$filter_msg='';





	$_order=$order;
	$_dir=$order_direction;


	if ($order=='date')
		$order='`Requested Date`';
	elseif ($order=='customer_key')
		$order='`Customer Key`';
	elseif ($order=='store_key')
		$order='`Store Key`';

	else
		$order='`Marketing Post Sent Fact Key`';


	$sql="select * from `Marketing Post Sent Fact` where $where  order by $order $order_direction limit $start_from,$number_results";


	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$customer=new Customer($data['Customer Key']);
		//print_r($customer);exit;

		$sql=sprintf("select `Marketing Post Name` from `Marketing Post Dimension` where `Marketing Post Key`=%d", $data['Marketing Post Key']);
		$result1=mysql_query($sql);
		if ($row=mysql_fetch_array($result1)) {
			$post_name=$row['Marketing Post Name'];
		}


		$cusomer_name=" <a href='customers.php?id=".$customer->id."'>".$customer->get("Customer Main Contact Name").'</a>';


		$adata[]=array(


			'requested_date'=>$data['Requested Date'],
			'name'=>$cusomer_name,
			//'type'=>$data['List key'],
			'post_name'=>$post_name,
			'key'=>$data['Marketing Post Sent Fact Key']
			//'creation_date'=>strftime("%a %e %b %y %H:%M", strtotime($data['List Creation Date']." +00:00")),
			// 'add_to_email_campaign_action'=>'<span class="state_details" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add List').'</span>',
			// 'delete'=>'<img src="art/icons/cross.png"/>'


		);

	}
	mysql_free_result($result);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_customers_lists() {

	global $user;

	$conf=$_SESSION['state']['customers']['list'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];



	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))



		$awhere=$_REQUEST['where'];
	else
		$awhere=$conf['where'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['store_id'])    ) {
		$store=$_REQUEST['store_id'];
		$_SESSION['state']['customers']['store']=$store;
	} else
		$store=$_SESSION['state']['customers']['store'];


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state']['customers']['list']['order']=$order;
	$_SESSION['state']['customers']['list']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['list']['nr']=$number_results;
	$_SESSION['state']['customers']['list']['sf']=$start_from;
	$_SESSION['state']['customers']['list']['where']=$awhere;
	$_SESSION['state']['customers']['list']['f_field']=$f_field;
	$_SESSION['state']['customers']['list']['f_value']=$f_value;


	$where=' where `List Scope`="Customer" and `List Use Type`="UserCreated" ';




	if (in_array($store,$user->stores)) {
		$where.=sprintf(' and `List Parent Key`=%d  ',$store);

	}



	if (($f_field=='name'     )  and $f_value!='') {
		$wheref="  and  `List Name` like '".addslashes($f_value)."%'";
	}else {
		$wheref='';
	}






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


	$rtext=number($total_records)." ".ngettext('list','lists',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';


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









	$_order=$order;
	$_dir=$order_direction;


	if ($order=='name')
		$order='`List Name`';
	elseif ($order=='creation_date')
		$order='`List Creation Date`';
	elseif ($order=='customer_list_type')
		$order='`List Type`';
	elseif ($order=='items')
		$order='`List Number Items`';
	else
		$order='`List Key`';


	$sql="select `List Number Items`, CLD.`List key`,CLD.`List Name`,CLD.`List Parent Key`,CLD.`List Creation Date`,CLD.`List Type` from `List Dimension` CLD $where $wheref order by $order $order_direction limit $start_from,$number_results";


	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





		$cusomer_list_name=" <a href='customers_list.php?id=".$data['List key']."'>".$data['List Name'].'</a>';
		switch ($data['List Type']) {
		case 'Static':
			$customer_list_type=_('Static');
			$items=number($data['List Number Items']);
			break;
		default:
			$customer_list_type=_('Dynamic');
			$items='<span> ~'.number($data['List Number Items']).'<span>';
			break;

		}

		$adata[]=array(


			'customer_list_type'=>$customer_list_type,
			'name'=>$cusomer_list_name,
			'key'=>$data['List key'],
			'creation_date'=>strftime("%c", strtotime($data['List Creation Date']." +00:00")),
			'add_to_email_campaign_action'=>'<div class="buttons small"><button class="positive" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add Emails').'</button></div>',
			'items'=>$items,
			'delete'=>'<img src="art/icons/cross.png"/>'


		);

	}
	mysql_free_result($result);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}



function list_customers_correlations() {


	global $myconf,$user;

	$conf=$_SESSION['state']['customers']['correlations'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];



	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))



		$awhere=$_REQUEST['where'];
	else
		$awhere=$conf['where'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['store_id'])    ) {
		$store=$_REQUEST['store_id'];
		$_SESSION['state']['customers']['store']=$store;
	} else
		$store=$_SESSION['state']['customers']['store'];


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_SESSION['state']['customers']['correlations']['order']=$order;
	$_SESSION['state']['customers']['correlations']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['correlations']['nr']=$number_results;
	$_SESSION['state']['customers']['correlations']['sf']=$start_from;
	$_SESSION['state']['customers']['correlations']['where']=$awhere;
	$_SESSION['state']['customers']['correlations']['f_field']=$f_field;
	$_SESSION['state']['customers']['correlations']['f_value']=$f_value;



	$where_type='';



	$filter_msg='';
	$wheref='';

	$currency='';
	if (is_numeric($store) and in_array($store,$user->stores)) {
		$where=sprintf(' where `Store Key`=%d ',$store);
		$store=new Store($store);
		$currency=$store->data['Store Currency Code'];
	} else {
		$where=' where false ';

	}






	//  print $f_field;


	if (($f_field=='name_a'     )  and $f_value!='') {
		$wheref="  and  `Customer A Name` like '%".addslashes($f_value)."%'";
		$wheref=sprintf('  and  (`Customer A Name`  REGEXP "[[:<:]]%s"  or `Customer B Name`  REGEXP "[[:<:]]%s") ',
		addslashes($f_value),
		addslashes($f_value)
		);
		
		
	}
	elseif (($f_field=='correlation_more' )  and $f_value!='') {
		$wheref=sprintf("  and  `Correlation` >=%.f",$f_value);
	}elseif (($f_field=='correlation_less' )  and $f_value!='') {
		$wheref=sprintf("  and  `Correlation` <=%.f",$f_value);
	}
	



	$sql="select count(*) as total from `Customer Correlation`   $where $wheref $where_type";
	//  print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from `Customer Correlation`  $where  $where_type";
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


	$rtext=number($total_records)." ".ngettext('correlation','correlations',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_("Showing all correlations");



	//if($total_records>$number_results)
	// $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

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





	$_order=$order;
	$_dir=$order_direction;
	// if($order=='location'){
	//      if($order_direction=='desc')
	//        $order='country_code desc ,town desc';
	//      else
	//        $order='country_code,town';
	//      $order_direction='';
	//    }

	//     if($order=='total'){
	//       $order='supertotal';
	//    }


	if ($order=='name_a')
		$order='`Customer A Name`';
	elseif ($order=='name_b')
		$order='`Customer B Name`';
	elseif ($order=='id_a')
		$order='`Customer A Key`';
	elseif ($order=='id_b')
		$order='`Customer B Key`';

	else
		$order='`Correlation`';




	$sql="select * from `Customer Correlation`   $where $wheref  $where_type order by $order $order_direction limit $start_from,$number_results";
	// print $sql;
	$adata=array();



	$result=mysql_query($sql);
	// print $sql;
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


		$id_a="<a href='customer.php?id=".$data['Customer A Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer A Key']).'</a>';
		$id_b="<a href='customer.php?id=".$data['Customer B Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer B Key']).'</a>';


		$name_a=" <a href='customer.php?id=".$data['Customer A Key']."'>".($data['Customer A Name']==''?'<i>'._('Unknown name').'</i>':$data['Customer A Name']).'</a>';
		$name_b=" <a href='customer.php?id=".$data['Customer B Key']."'>".($data['Customer B Name']==''?'<i>'._('Unknown name').'</i>':$data['Customer B Name']).'</a>';




		$adata[]=array(
			'id_a'=>$id_a,
			'id_b'=>$id_b,
			'name_a'=>$name_a,
			'name_b'=>$name_b,
			'correlation'=>$data['Correlation'],
			'action'=>sprintf('<a href="customer_split_view.php?id_a=%d&id_b=%d&p=cs&score=%f&name_a=%s&name_b=%s"><img src="art/icons/application_tile_horizontal.png" alt="split_view"></a>',
				$data['Customer A Key'],
				$data['Customer B Key'],
				$data['Correlation'],
				urlencode($data['Customer A Name']),
				urlencode($data['Customer B Name'])
			)

		);

	}
	mysql_free_result($result);



	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,

			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}



function can_merge_customer($data) {
	global $user;
	$customer_to_merge_id=_trim($data['customer_to_merge_id']);

	if ($customer_to_merge_id=='') {
		$response=array('state'=>200,
			'action'=>'empty',
			'msg'=>''
		);
		echo json_encode($response);
		exit;
	}

	if (!is_numeric($customer_to_merge_id)) {
		$response=array('state'=>200,
			'action'=>'error',
			'msg'=>_('Invalid Customer ID')

		);
		echo json_encode($response);
		exit;
	}

	$customer_a=new Customer($data['customer_key']);
	if (!$customer_a->id) {
		$response=array('state'=>400,'action'=>'error','msg'=>"Customer don't exists");
		echo json_encode($response);
		exit;
	}

	if (!in_array($customer_a->data['Customer Store Key'],$user->stores)) {
		$response=array('state'=>400,'action'=>'error','msg'=>_('Forbidden operation'));
		echo json_encode($response);
		exit;
	}



	$customer_b=new Customer($customer_to_merge_id);
	if (!$customer_b->id) {
		$response=array('state'=>200,'action'=>'error','msg'=>"Customer don't exists");
		echo json_encode($response);
		exit;
	}

	if ($customer_a->id==$customer_b->id) {
		$response=array('state'=>200,'action'=>'error','msg'=>"Same customer ID");
		echo json_encode($response);
		exit;
	}

	if ($customer_a->data['Customer Store Key']!=$customer_b->data['Customer Store Key']) {
		$response=array('state'=>200,'action'=>'error','msg'=>"Customer bellows to another store");
		echo json_encode($response);
		exit;
	}


	$response=array('state'=>200,'action'=>'ok','msg'=>'','id'=>$customer_b->id);
	echo json_encode($response);
	exit;


}

function number_orders_in_process($data) {
	$number_orders_in_process=0;
	$orders_list='';
	$msg='';
	$sql=sprintf("select `Order Key`,`Order Public ID`  from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State`='In Process'",
		$data['customer_key']
	);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$orders_list.=sprintf(", <a class='id' href='order.php?id=%d'>%s</a>",$row['Order Key'],$row['Order Public ID']);
		$number_orders_in_process++;

		if ($number_orders_in_process==10)
			break;

	}
	$orders_list=preg_replace('/^,\s*/','',$orders_list);

	if ($number_orders_in_process==0) {
		$response=array('state'=>200,'orders_in_process'=>$number_orders_in_process,'msg'=>'');
		echo json_encode($response);
		exit;
	}

	if ($number_orders_in_process==1) {
		$orders_list=_('Current order in process').": ".$orders_list;
		$msg=_('This customer has already one order in process. Are you sure you want to create a new one?');
	}elseif ($number_orders_in_process>1) {
		$orders_list=_('Current orders in process').": ".$orders_list;
		$msg=_('This customer has already several orders in process. Are you sure you want to create a new one?');

	}
	$response=array('state'=>200,'orders_in_process'=>$number_orders_in_process,'msg'=>$msg,'orders_list'=>$orders_list);
	echo json_encode($response);
	exit;

}


function check_tax_number($data) {


	$customer= new Customer($data['customer_key']);

	$country=new Country('code',$customer->data['Customer Billing Address Country Code']);

	if ($country->id) {

		$tax_number=$customer->data['Customer Tax Number'];
		$tax_number=preg_replace('/^'.$country->data['Country 2 Alpha Code'].'/i','',$tax_number);
		$tax_number=preg_replace('/[^a-z^0-9]/i','',$tax_number);



		if (preg_match('/^gr$/i',$country->data['Country 2 Alpha Code'])) {
			$country_code='EL';
		}else {
			$country_code=$country->data['Country 2 Alpha Code'];
		}

		$tax_number=preg_replace('/^'.$country_code.'/i','',$tax_number);
		$tax_number=preg_replace('/[^a-z^0-9]/i','',$tax_number);
		check_european_tax_number($country_code,$tax_number,$customer);
	}

}


function check_european_tax_number($country_code,$tax_number,$customer) {
	//print "$country_code,$tax_number";



	$result=array();

	try {
		$client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
		$result = $client->checkVat(array('countryCode'=>$country_code,'vatNumber'=>$tax_number));
	} catch (Exception $e) {
		//  echo "<h2>Exception Error!</h2>";

		$msg=$e->getMessage();

		if ($msg=="{ 'INVALID_INPUT' }") {
			$msg="<div style='padding:10px 0px'><img src='art/icons/error.png'/> "._('Invalid Tax Number').'<br/><span style="margin-left:22px">'.$country_code.' '.$tax_number.'</span></div>';

			$update_data=array('Customer Tax Number Valid'=>'No','Customer Tax Number Details Match'=>'Unknown','Customer Tax Number Validation Date'=>gmdate('Y-m-d H:i:s'));

			$customer->update($update_data);

			$result=array('valid'=>false);

			$response=array('state'=>200,'result'=>$result,'msg'=>$msg);
			echo json_encode($response);
			exit;
		}else {

		}

		$response=array('state'=>400,'msg'=>$msg);
		echo json_encode($response);
		exit;
	}

	//print_r($result);

	if ($result->valid) {
		$update_data=array('Customer Tax Number Valid'=>'Yes','Customer Tax Number Details Match'=>'Unknown','Customer Tax Number Validation Date'=>gmdate('Y-m-d H:i:s'));

		$msg="<div style='padding:10px 0px'><img src='art/icons/accept.png'/> "._('Valid Tax Number').'</div>';

		if (isset($result->address)) {
			$result->address=nl2br($result->address);

		}


	}else {
		$update_data=array('Customer Tax Number Valid'=>'No','Customer Tax Number Details Match'=>'Unknown','Customer Tax Number Validation Date'=>gmdate('Y-m-d H:i:s'));
		$msg="<div style='padding:10px 0px'><img src='art/icons/error.png'/> "._('Invalid Tax Number').'<br/><span style="margin-left:22px">'.$country_code.' '.$tax_number.'</span></div>';


	}
	//print $customer->id;
	//print_r($update_data);

	$customer->update($update_data);




	$response=array('state'=>200,'result'=>$result,'msg'=>$msg);
	echo json_encode($response);
	exit;

}

function pending_post() {


	//$conf=$_SESSION['state']['warehouse']['locations'];

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		return;
	}
	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		return;
	}


	$conf=$_SESSION['state']['customers']['pending_post'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];


	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$elements=$conf['elements'];

	if (isset( $_REQUEST['elements_Send'])) {
		$elements['Send']=$_REQUEST['elements_Send'];
	}
	if (isset( $_REQUEST['elements_ToSend'])) {
		$elements['ToSend']=$_REQUEST['elements_ToSend'];
	}




	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_SESSION['state']['customers']['pending_post']['elements']=$elements;
	$_SESSION['state']['customers']['pending_post']['order']=$order;
	$_SESSION['state']['customers']['pending_post']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['pending_post']['nr']=$number_results;
	$_SESSION['state']['customers']['pending_post']['sf']=$start_from;
	$_SESSION['state']['customers']['pending_post']['f_field']=$f_field;
	$_SESSION['state']['customers']['pending_post']['f_value']=$f_value;

	$where=sprintf('where `Customer Store Key`=%d ',$parent_key);
	$table='`Customer Send Post` CSP left join  `Customer Dimension` C  on (CSP.`Customer Key`=C.`Customer Key`) ';
	$where_type='';
	$currency='';



	$filter_msg='';
	$wheref='';



	$_elements='';
	foreach ($elements as $_key=>$_value) {
		if ($_value) {
			if ($_key=='Send') {
				$_elements.=",'Send'";
			}
			elseif ($_key=='ToSend') {
				$_elements.=",'To Send'";
			}
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} else {
		$where.=' and `Send Post Status` in ('.$_elements.')' ;
	}





	//  print $f_field;


	if (($f_field=='customer name'     )  and $f_value!='') {
		$wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
	}
	elseif (($f_field=='postcode'     )  and $f_value!='') {
		$wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
	}
	elseif ($f_field=='id'  )
		$wheref.=" and  `Customer Key` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
	elseif ($f_field=='last_more' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
	elseif ($f_field=='last_less' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
	elseif ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  `Customer Orders`<=".$f_value."    ";
	elseif ($f_field=='min' and is_numeric($f_value) )
		$wheref.=" and  `Customer Orders`>=".$f_value."    ";
	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  `Customer Net Balance`>=".$f_value."    ";
	elseif ($f_field=='country' and  $f_value!='') {
		if ($f_value=='UNK') {
			$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
			$find_data=' '._('a unknown country');
		} else {

			$f_value=Address::parse_country($f_value);
			if ($f_value!='UNK') {
				$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
				$country=new Country('code',$f_value);
				$find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
			}

		}
	}



	$sql="select count(Distinct C.`Customer Key`) as total from $table   $where $wheref $where_type";
	//  print $sql;
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


	$rtext=$total_records." ".ngettext('customer','customers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';



	//if($total_records>$number_results)
	// $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

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





	$_order=$order;
	$_dir=$order_direction;
	// if($order=='location'){
	//      if($order_direction=='desc')
	//        $order='country_code desc ,town desc';
	//      else
	//        $order='country_code,town';
	//      $order_direction='';
	//    }

	//     if($order=='total'){
	//       $order='supertotal';
	//    }


	if ($order=='name')
		$order='`Customer File As`';
	elseif ($order=='id')
		$order='C.`Customer Key`';
	elseif ($order=='location')
		$order='`Customer Main Location`';
	elseif ($order=='orders')
		$order='`Customer Orders`';
	elseif ($order=='email')
		$order='`Customer Main Plain Email`';
	elseif ($order=='telephone')
		$order='`Customer Main Plain Telephone`';
	elseif ($order=='last_order')
		$order='`Customer Last Order Date`';
	elseif ($order=='contact_name')
		$order='`Customer Main Contact Name`';
	elseif ($order=='address')
		$order='`Customer Main Location`';
	elseif ($order=='town')
		$order='`Customer Main Town`';
	elseif ($order=='postcode')
		$order='`Customer Main Postal Code`';
	elseif ($order=='region')
		$order='`Customer Main Country First Division`';
	elseif ($order=='country')
		$order='`Customer Main Country`';
	//  elseif($order=='ship_address')
	//  $order='`customer main ship to header`';
	elseif ($order=='ship_town')
		$order='`Customer Main Delivery Address Town`';
	elseif ($order=='ship_postcode')
		$order='`Customer Main Delivery Address Postal Code`';
	elseif ($order=='ship_region')
		$order='`Customer Main Delivery Address Country Region`';
	elseif ($order=='ship_country')
		$order='`Customer Main Delivery Address Country`';
	elseif ($order=='net_balance')
		$order='`Customer Net Balance`';
	elseif ($order=='balance')
		$order='`Customer Outstanding Net Balance`';
	elseif ($order=='total_profit')
		$order='`Customer Profit`';
	elseif ($order=='total_payments')
		$order='`Customer Net Payments`';
	elseif ($order=='top_profits')
		$order='`Customer Profits Top Percentage`';
	elseif ($order=='top_balance')
		$order='`Customer Balance Top Percentage`';
	elseif ($order=='top_orders')
		$order='``Customer Orders Top Percentage`';
	elseif ($order=='top_invoices')
		$order='``Customer Invoices Top Percentage`';
	elseif ($order=='total_refunds')
		$order='`Customer Total Refunds`';
	elseif ($order=='contact_since')
		$order='`Customer First Contacted Date`';
	elseif ($order=='activity')
		$order='`Customer Type by Activity`';
	else
		$order='`Customer File As`';
	$sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  $where_type group by C.`Customer Key` order by $order $order_direction limit $start_from,$number_results";
	//print $sql;
	$adata=array();



	$result=mysql_query($sql);
	//print $sql;exit;
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


		$id="<a href='customer.php?p=cs&id=".$data['Customer Key']."'>".sprintf("%05d",$data['Customer Key']).'</a>';
		if ($data['Customer Type']=='Person') {
			$name='<img src="art/icons/user.png" alt="('._('Person').')">';
		} else {
			$name='<img src="art/icons/building.png" alt="('._('Company').')">';

		}

		$name.=" <a href='customer.php?p=cs&id=".$data['Customer Key']."'>".($data['Customer Name']==''?'<i>'._('Unknown name').'</i>':$data['Customer Name']).'</a>';



		if ($data['Customer Orders']==0)
			$last_order_date='';
		else
			$last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

		$contact_since=strftime("%e %b %y", strtotime($data['Customer First Contacted Date']." +00:00"));
		$requested=strftime("%e %b %y", strtotime($data['Date Creation']." +00:00"));
		$send='';
		switch ($data['Send Post Status']) {
		case 'To Send':
			$request_state=_('To Send');
			break;
		case 'Send':
			$request_state=_('Send');
			$send=strftime("%e %b %y", strtotime($data['Date Send']." +00:00"));
			break;
		case 'Cancelled':
			$request_state=_('Cancelled');
			break;
		default:
			$request_state=$data['Send Post Status'];
			break;
		}


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
			'id'=>$id,
			'key'=>$data['Customer Send Post Key'],
			'name'=>$name,
			'location'=>$data['Customer Main Location'],
			'orders'=>number($data['Customer Orders']),
			'invoices'=>$data['Customer Orders Invoiced'],
			'email'=>$data['Customer Main XHTML Email'],
			'telephone'=>$data['Customer Main XHTML Telephone'],
			'last_order'=>$last_order_date,
			'contact_since'=>$contact_since,


			'total_payments'=>money($data['Customer Net Payments'],$currency),
			'net_balance'=>money($data['Customer Net Balance'],$currency),
			'total_refunds'=>money($data['Customer Net Refunds'],$currency),
			'total_profit'=>money($data['Customer Profit'],$currency),
			'balance'=>money($data['Customer Outstanding Net Balance'],$currency),


			'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
			'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
			'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
			'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
			'contact_name'=>$data['Customer Main Contact Name'],
			'address'=>$data['Customer Main XHTML Address'],
			'billing_address'=>$billing_address,
			'delivery_address'=>$delivery_address,

			'activity'=>$activity,
			'requested'=>$requested,
			'send'=>$send,
			'request_state'=>$request_state,
			'delete'=>'<img src="art/icons/cross.png" alt="'._('Delete').'"/>',
			'mark_as_send'=>'<img src="art/icons/email_go.png" alt="'._('Mark as Send').'"/>',

		);
		///if(isset($_REQUEST['textValue'])&isset($_REQUEST['typeValue']))
		///{
		/// $list_name=$_REQUEST['textValue'];
		/// $list_type=$_REQUEST['typeValue'];
		///}
		///$dataid[]=array('id'=>$id,'list_name'=>$list_name,'list_type'=>$list_type);//
	}
	mysql_free_result($result);

	///print_r($dataid);//




	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total

		)
	);
	echo json_encode($response);


}

function get_contacts_numbers() {


	$elements_number_all_contacts=array('Active'=>0,'Losing'=>0,'Lost'=>0);
	$sql=sprintf("select count(*) as num,`Customer Type by Activity` from  `Customer Dimension` where `Customer Store Key`=%d group by `Customer Type by Activity`",$store->id);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_number_all_contacts[$row['Customer Type by Activity']]=$row['num'];
	}

	$elements_number_contacts_with_orders=array('Active'=>0,'Losing'=>0,'Lost'=>0);
	$sql=sprintf("select count(*) as num,`Customer Type by Activity` from  `Customer Dimension` where `Customer Store Key`=%d and `Customer With Orders`='Yes' group by `Customer Type by Activity`",$store->id);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_number_contacts_with_orders[$row['Customer Type by Activity']]=$row['num'];
	}

}

function get_contacts_elements_numbers($data) {

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];

	$elements_numbers=array(
		'Active'=>0,'Losing'=>0,'Lost'=>0,'Normal'=>0,'VIP'=>0,'Partner'=>0,'Staff'=>0,'Domestic'=>0,'Export'=>0
	);

	if ($parent=='store') {
		$where='';
		if ($_SESSION['state']['customers']['customers']['orders_type']=='contacts_with_orders') {
			$where=' and `Customer With Orders`="Yes" ';
		}
		$sql=sprintf("select count(*) as num,`Customer Type by Activity` from  `Customer Dimension` where `Customer Store Key`=%d  %s  group by `Customer Type by Activity`",
			$parent_key,
			$where
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Customer Type by Activity']]=number($row['num']);
		}

		$sql=sprintf("select count(*) as num,`Customer Level Type` from  `Customer Dimension` where `Customer Store Key`=%d %s group by `Customer Level Type`",
			$parent_key,
			$where
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Customer Level Type']]=number($row['num']);
		}
		
		$sql=sprintf("select count(*) as num,`Customer Location Type` from  `Customer Dimension` where `Customer Store Key`=%d %s group by `Customer Location Type`",
			$parent_key,
			$where
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Customer Location Type']]=number($row['num']);
		}
		
	}
	elseif ($parent=='category') {
		$where='';
		if ($_SESSION['state']['customer_categories']['customers']['orders_type']=='contacts_with_orders') {
			$where=' and `Customer With Orders`="Yes" ';
		}

		$sql=sprintf("select count(*) as num,`Customer Type by Activity` from  `Category Bridge` left join  `Customer Dimension` C on (`Subject Key`=`Customer Key`)  where  `Subject`='Customer' and  `Category Key`=%d %s  group by `Customer Type by Activity`",
			$parent_key,
			$where
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Customer Type by Activity']]=number($row['num']);
		}

		$sql=sprintf("select count(*) as num,`Customer Level Type` from  `Category Bridge` left join  `Customer Dimension` C on (`Subject Key`=`Customer Key`)  where  `Subject`='Customer' and  `Category Key`=%d  %s group by `Customer Level Type`",
			$parent_key,
			$where
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Customer Level Type']]=number($row['num']);
		}

	}elseif($parent=='list'){
	
	
		$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$parent_key);

		$res=mysql_query($sql);
		if ($customer_list_data=mysql_fetch_assoc($res)) {
			$awhere=false;
			if ($customer_list_data['List Type']=='Static') {
				$table='`List Customer Bridge` CB left join `Customer Dimension` C  on (CB.`Customer Key`=C.`Customer Key`)';
				$where=sprintf('  where `List Key`=%d ',$parent_key);

			} else {

				$tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
				$tmp=preg_replace('/\\\\\"/','"',$tmp);
				$tmp=preg_replace('/\'/',"\'",$tmp);

				$raw_data=json_decode($tmp, true);

				$raw_data['store_key']=$customer_list_data['List Parent Key'];
				include_once 'list_functions_customer.php';

				list($where,$table,$group_by)=customers_awhere($raw_data);


			}

		} else {
			return;
		}
		
		if ($_SESSION['state']['customer_categories']['customers']['orders_type']=='contacts_with_orders') {
			$where.=' and `Customer With Orders`="Yes" ';
		}

		$sql=sprintf("select count(DISTINCT C.`Customer Key`) as num,`Customer Type by Activity`  from  %s  %s group by `Customer Type by Activity`",
				$table,
			$where
		);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Customer Type by Activity']]=number($row['num']);
		}

		
		
		$sql=sprintf("select count(DISTINCT C.`Customer Key`) as num,`Customer Level Type` from  %s   %s group by `Customer Level Type`",
			$table,
			$where
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Customer Level Type']]=number($row['num']);
		}

	
	}


	$response= array('state'=>200,'elements_numbers'=>$elements_numbers);
	echo json_encode($response);




}


?>
