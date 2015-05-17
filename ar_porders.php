<?php
/*
 File: ar_porders.php


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyrigh (c) 2010, Inikoo

 Version 2.0
*/
require_once 'common.php';
require_once 'ar_common.php';
require_once 'class.PurchaseOrder.php';
require_once 'class.SupplierDeliveryNote.php';
require_once 'class.SupplierInvoice.php';


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}




$tipo=$_REQUEST['tipo'];

switch ($tipo) {

case('get_attachments_showcase'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'=>array('type'=>'key'),
			'subject'=>array('type'=>'string'),
		));
	get_attachments_showcase($data);
	break;
case ('get_history_numbers'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'=>array('type'=>'key'),
			'subject'=>array('type'=>'string'),
		));
	get_history_numbers($data);
	break;
case('purchase_orders_with_product'):
	list_purchase_orders_with_product();
	break;
case('purchase_orders'):
	list_purchase_orders();
	break;
case('delivery_notes'):
	list_delivery_notes();
	break;
case('invoices'):
	list_invoices();
	break;
default:
	$response=array('state'=>404,'resp'=>_('Operation not found'));
	echo json_encode($response);
}

function list_purchase_orders_with_product() {






	$pid= $_SESSION['state']['supplier_product']['pid'];



	$conf=$_SESSION['state']['supplier_product']['porders'];
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
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$_SESSION['state']['supplier_product']['porders']['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state']['supplier_product']['porders']['to'];


	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$_SESSION['state']['supplier_product']['porders']['view'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_SESSION['state']['supplier_product']['porders']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

	$_SESSION['state']['supplier_product']['porders']['order']=$order;
	$_SESSION['state']['supplier_product']['porders']['order_dir']=$order_direction;
	$_SESSION['state']['supplier_product']['porders']['nr']=$number_results;
	$_SESSION['state']['supplier_product']['porders']['sf']=$start_from;
	$_SESSION['state']['supplier_product']['porders']['where']=$where;
	$_SESSION['state']['supplier_product']['porders']['f_field']=$f_field;
	$_SESSION['state']['supplier_product']['porders']['f_value']=$f_value;



	$_SESSION['state']['supplier_product']['porders']['view']=$view;
	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['supplier_product']['porders']['from'],$_SESSION['state']['supplier_product']['porders']['to']);
	}else {
		$_SESSION['state']['supplier_product']['porders']['from']=$date_interval['from'];
		$_SESSION['state']['supplier_product']['porders']['to']=$date_interval['to'];
	}


	$where.=sprintf(' and POTF.`Supplier Product ID`=%d  ',$pid);
	$db_table=' `Purchase Order Transaction Fact` POTF left join `Supplier Product History Dimension`  SPHD on (`SPH Key`=POTF.`Supplier Product ID`) left join `Purchase Order Dimension` PO on (PO.`Purchase Order Key`=POTF.`Purchase Order Key`) ';

	$where.=$date_interval['mysql'];

	$wheref='';

	if ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
	elseif ($f_field=='min' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
	elseif (($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
		$wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  total<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  total>=".$f_value."    ";








	$sql="select count( distinct PO.`Purchase Order Key`) as total from $db_table   $where $wheref ";
	//   print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	}else {

		$sql="select count( distinct PO.`Purchase Order Key`) as total from $db_table  $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}
	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);


	if ($total_records==0) {
		$rtext_rpp='';
	}elseif ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';



	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
		break;
	case('minvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
		break;
	case('maxvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>)";
		break;
	case('max'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
		break;
	}




	$_order=$order;
	$_dir=$order_direction;
	$order='`Purchase Order Last Updated Date`';


	$sql="select  POTF.`Purchase Order Net Amount`,`Purchase Order Quantity`,PO.`Purchase Order Last Updated Date`,`Purchase Order Currency Code`,`Purchase Order State`,PO.`Purchase Order Key`,`Purchase Order Public ID`,`Purchase Order Total Amount`,`Purchase Order Number Items` from  $db_table   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
	//  print $sql;
	//  print $sql;
	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$status=$row['Purchase Order State'];

		$data[]=array(
			'id'=>'<a href="porder.php?id='.$row['Purchase Order Key'].'">'.$row['Purchase Order Public ID']."</a>",
			'date'=>strftime("%e %b %Y %H:%M", strtotime($row['Purchase Order Last Updated Date'])),
			'amount'=>money($row['Purchase Order Net Amount'],$row['Purchase Order Currency Code']),
			'qty'=>number($row['Purchase Order Quantity']),
			'status'=>$status
		);
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_purchase_orders() {


	if (isset($_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	}else {
		exit;
	}

	if (isset($_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		exit;
	}


	if ($parent=='none') {
		$conf=$_SESSION['state']['suppliers']['porders'];
		$conf_table='suppliers';

	}elseif ($parent=='supplier') {
		$conf=$_SESSION['state']['supplier']['porders'];
		$conf_table='suppliers';
	}else {
		exit;
	}

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
	else {
		if ($parent=='none') {
			$from=$_SESSION['state']['suppliers']['from'];

		}elseif ($parent=='supplier') {
			$from=$_SESSION['state']['supplier']['from'];

		}
	}

	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else {
		if ($parent=='none') {
			$to=$_SESSION['state']['suppliers']['to'];

		}elseif ($parent=='supplier') {
			$to=$_SESSION['state']['supplier']['to'];

		}
	}



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state'][$conf_table]['porders']['order']=$order;
	$_SESSION['state'][$conf_table]['porders']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_table]['porders']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['porders']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['porders']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['porders']['f_value']=$f_value;

	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		if ($parent=='none') {
			$to=$_SESSION['state']['suppliers']['to'];
			$from=$_SESSION['state']['suppliers']['from'];

		}elseif ($parent=='supplier') {
			$to=$_SESSION['state']['supplier']['to'];
			$from=$_SESSION['state']['supplier']['from'];

		}

		$date_interval=prepare_mysql_dates($from,$to);
	}else {

		if ($parent=='none') {
			$_SESSION['state']['suppliers']['to']=$date_interval['to'];
			$_SESSION['state']['suppliers']['from']=$date_interval['from'];

		}elseif ($parent=='supplier') {
			$_SESSION['state']['supplier']['to']=$date_interval['to'];
			$_SESSION['state']['supplier']['from']=$date_interval['from'];

		}

	}

	if ($parent=='none') {
		$where=sprintf(' where true ');
		$db_table='`Purchase Order Dimension` as PO ';
	}elseif ($parent=='supplier') {
		$where=sprintf(' where  `Purchase Order Supplier Key`=%d',$parent_key);
		$db_table='`Purchase Order Dimension` as PO ';
	}


	$where.=$date_interval['mysql'];

	$wheref='';

	if ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
	elseif ($f_field=='min' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
	elseif (($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
		$wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  total<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  total>=".$f_value."    ";


	$sql="select count( distinct PO.`Purchase Order Key`) as total from $db_table   $where $wheref ";

	$result=mysql_query($sql);

	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	}else {

		$sql="select count( distinct PO.`Purchase Order Key`) as total from $db_table  $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}
	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);



	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
		break;
	case('minvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
		break;
	case('maxvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>)";
		break;
	case('max'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
		break;
	}




	$_order=$order;
	$_dir=$order_direction;
	$order='`Purchase Order Last Updated Date`';


	$sql="select  PO.`Purchase Order Last Updated Date`,`Purchase Order Currency Code`,`Purchase Order Main Buyer Name`,PO.`Purchase Order State`,PO.`Purchase Order Key`,`Purchase Order Public ID`,`Purchase Order Total Amount`,`Purchase Order Number Items` from  $db_table   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
	// print $sql;
	//  print $sql;
	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$status=$row['Purchase Order State'];


		switch ($row['Purchase Order State']) {
		case 'In Process':
			$status= _('In Process');
			break;
		case 'Submitted':
			$status= _('Submitted');
			break;
		case 'Confirmed':
			$status= _('Confirmed');
			break;
		case 'In Warehouse':
			$status= _('In Warehouse');
			break;
		case 'Done':
			$status= _('Consolidates');
			break;

		case 'Cancelled':
			$status= _('Cancelled');
			break;

		default:
			$status= $row['Purchase Order State'];
			break;
		}



		$data[]=array(
			/* 'id'=>'<a href="porder.php?id='.$row['Purchase Order Key'].'">'.$row['Purchase Order Public ID']."</a>",
		   'date'=>strftime("%e %b %Y %H:%M", strtotime($row['Purchase Order Last Updated Date'])),
		   'total'=>money($row['Purchase Order Total Amount'],$row['Purchase Order Currency Code']),
		   'items'=>number($row['Purchase Order Number Items']),
		   'status'=>$status*/
			'public_id'=>'<a href="porder.php?id='.$row['Purchase Order Key'].'">'.$row['Purchase Order Public ID']."</a>",
			'date'=>strftime("%e %b %Y %H:%M", strtotime($row['Purchase Order Last Updated Date'])),
			'total'=>money($row['Purchase Order Total Amount'],$row['Purchase Order Currency Code']),
			'buyer_name'=>$row['Purchase Order Main Buyer Name'],
			'items'=>number($row['Purchase Order Number Items']),
			'total'=>money($row['Purchase Order Total Amount'],$row['Purchase Order Currency Code']),
			'status'=>$status
		);
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_delivery_notes() {


	if (isset($_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];

	}else {
		exit();
	}


	if (isset($_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];

	}else {
		exit();
	}



	switch ($parent) {
	case 'none':
		$conf=$_SESSION['state']['suppliers']['supplier_invoices'];
		$conf_table='suppliers';
		break;
	case 'supplier':
		$conf=$_SESSION['state']['supplier']['supplier_invoices'];
		$conf_table='supplier';
		break;
	default:
		exit();
	}

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
		$from=$_SESSION['state'][$conf_table]['from'];


	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state'][$conf_table]['to'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state'][$conf_table]['supplier_invoices']['order']=$order;
	$_SESSION['state'][$conf_table]['supplier_invoices']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_table]['supplier_invoices']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['supplier_invoices']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['supplier_invoices']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['supplier_invoices']['f_value']=$f_value;




	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state'][$conf_table]['from'],$_SESSION['state'][$conf_table]['to']);
	}else {
		$_SESSION['state'][$conf_table]['from']=$date_interval['from'];
		$_SESSION['state'][$conf_table]['to']=$date_interval['to'];
	}

	if ($parent=='none') {

		$where=' where true';
	}else {
		$where=sprintf('where `Supplier Delivery Note Supplier Key`=%d',$parent_key);
	}


	$where.=$date_interval['mysql'];





	$wheref='';

	if ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
	elseif ($f_field=='min' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
	elseif (($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
		$wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  total<=".$f_value."    ";
	else if ($f_field=='minvalue' and is_numeric($f_value) )
			$wheref.=" and  total>=".$f_value."    ";






		$sql="select count(*) as total from `Supplier Delivery Note Dimension`   $where $wheref ";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	}else {

		$sql="select count(*) as total from `Supplier Delivery Note Dimension`   $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}
	$rtext=number($total_records)." ".ngettext('delivery','deliveries',$total_records);



	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records>10)
		$rtext_rpp=' ('._("Showing all").')';
    else
        $rtext_rpp='';


	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
		break;
	case('minvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
		break;
	case('maxvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>)";
		break;
	case('max'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
		break;
	}




	$_order=$order;
	$_dir=$order_direction;
	$order='`Supplier Delivery Note Last Updated Date`';

	$sql="select 
	
		(select group_concat('<a href=\"porder.php?id=',PO.`Purchase Order Key`,'\">',PO.`Purchase Order Public ID`,'</a>') from  `Purchase Order Dimension` PO  left join `Purchase Order SDN Bridge` B on (PO.`Purchase Order Key`=B.`Purchase Order Key`)  where B.`Supplier Delivery Note Key`=SDND.`Supplier Delivery Note Key` ) as pos,
	
	 
`Supplier Delivery Note Key`,`Supplier Delivery Note Public ID`,`Supplier Delivery Note Last Updated Date`,`Supplier Delivery Note Number Items`,`Supplier Delivery Note Current State`,`Supplier Name`,`Supplier Key`
	 from  `Supplier Delivery Note Dimension` SDND left join `Supplier Dimension` S on (S.`Supplier Key`=SDND.`Supplier Delivery Note Supplier Key`)
	  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

	// $sql="select  `Supplier Delivery Note Last Updated Date`,`Supplier Delivery Note Current State`,`Supplier Delivery Note Key`,`Supplier Delivery Note Public ID`,`Supplier Delivery Note Number Items` from  `Supplier Delivery Note Dimension`   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

	// print $sql;
	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$status=$row['Supplier Delivery Note Current State'];

		$data[]=array(
			'id'=>'<a href="supplier_dn.php?id='.$row['Supplier Delivery Note Key'].'">'.$row['Supplier Delivery Note Public ID']."</a>",
			'date'=>strftime("%e %b %Y %H:%M", strtotime($row['Supplier Delivery Note Last Updated Date'].' +0:00')),
			'items'=>number($row['Supplier Delivery Note Number Items']),
			'status'=>$status,
			'pos'=>$row['pos'],
			//'invoices'=>$row['invoices'],
			'supplier_name'=>sprintf('<a href="supplier.php?id=%d">%s</a>',$row['Supplier Key'],$row['Supplier Name'])
		);
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}
function list_invoices() {


	if (isset($_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];

	}else {
		exit();
	}


	if (isset($_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];

	}else {
		exit();
	}



	switch ($parent) {
	case 'none':
		$conf=$_SESSION['state']['suppliers']['supplier_invoices'];
		$conf_table='suppliers';
		break;
	case 'supplier':
		$conf=$_SESSION['state']['supplier']['supplier_invoices'];
		$conf_table='supplier';
		break;
	default:
		exit();
	}

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
		$from=$_SESSION['state'][$conf_table]['from'];


	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state'][$conf_table]['to'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state'][$conf_table]['supplier_invoices']['order']=$order;
	$_SESSION['state'][$conf_table]['supplier_invoices']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_table]['supplier_invoices']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['supplier_invoices']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['supplier_invoices']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['supplier_invoices']['f_value']=$f_value;




	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state'][$conf_table]['from'],$_SESSION['state'][$conf_table]['to']);
	}else {
		$_SESSION['state'][$conf_table]['from']=$date_interval['from'];
		$_SESSION['state'][$conf_table]['to']=$date_interval['to'];
	}

	$where=' where true';

	$where.=$date_interval['mysql'];

	$wheref='';

	if ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
	elseif ($f_field=='min' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
	elseif (($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
		$wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  total<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  total>=".$f_value."    ";






	$sql="select count(*) as total from `Supplier Invoice Dimension`   $where $wheref ";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	}else {

		$sql="select count(*) as total from `Supplier Invoice Dimension`   $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}
	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);



	if ($total_records<10)
		$rtext_rpp='';

	elseif ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));

	else
		$rtext_rpp='('._("Showing all").')';



	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
		break;
	case('minvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
		break;
	case('maxvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>)";
		break;
	case('max'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
		break;
	}




	$_order=$order;
	$_dir=$order_direction;
	$order='`Supplier Delivery Note Last Updated Date`';

	//$sql="select  SDND.`Supplier Delivery Note Last Updated Date`,SDND.`Supplier Delivery Note Current State`,SDND.`Supplier Delivery Note Key`,SDND.`Supplier Delivery Note Public ID`,SDND.`Supplier Delivery Note Number Items`,POD.`Purchase Order Public ID`,POD.`Purchase Order Supplier Name`,POD.`Purchase Order Total Amount`,POD.`Purchase Order Currency Code` from  `Supplier Delivery Note Dimension` SDND left join `Purchase Order Transaction Fact` POTF on (SDND.`Supplier Delivery Note Key`=POTF.`Supplier Delivery Note Key`) left join `Purchase Order Dimension` POD on (POD.`Purchase Order Key`=POTF.`Purchase Order Key`) $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

	$sql="select  SDND.`Supplier Invoice Last Updated Date`,SDND.`Supplier Invoice Current State`,SDND.`Supplier Invoice Key`,SDND.`Supplier Invoice Public ID`,SDND.`Supplier Invoice Number Items`,POD.`Purchase Order Public ID`,POD.`Purchase Order Supplier Name`,POD.`Purchase Order Total Amount`,POD.`Purchase Order Currency Code` from  `Supplier Invoice Dimension` SDND left join `Purchase Order Transaction Fact` POTF on (SDND.`Supplier Invoice Key`=POTF.`Supplier Invoice Key`) left join `Purchase Order Dimension` POD on (POD.`Purchase Order Key`=POTF.`Purchase Order Key`) $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

	// $sql="select  `Supplier Delivery Note Last Updated Date`,`Supplier Delivery Note Current State`,`Supplier Delivery Note Key`,`Supplier Delivery Note Public ID`,`Supplier Delivery Note Number Items` from  `Supplier Delivery Note Dimension`   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

	//print $sql;
	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$status=$row['Supplier Invoice Current State'];

		$data[]=array(
			'id'=>'<a href="supplier_dn.php?id='.$row['Supplier Invoice Key'].'">'.$row['Supplier Invoice Public ID']."</a>",
			'date'=>strftime("%e %b %Y %H:%M", strtotime($row['Supplier Invoice Last Updated Date'])),
			'items'=>number($row['Supplier Invoice Number Items']),
			'status'=>$status,
			'order_id'=>$row['Purchase Order Public ID'],
			'supplier_name'=>$row['Purchase Order Supplier Name'],
			'total'=>money($row['Purchase Order Total Amount'],$row['Purchase Order Currency Code']),
		);
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function get_history_numbers($data) {

	$subject_key=$data['subject_key'];
	$subject=$data['subject'];

	$elements_numbers=array('WebLog'=>0,'Notes'=>0,'Orders'=>0,'Changes'=>0,'Attachments'=>0,'Emails'=>0);

	if ($subject=='porder') {
		$sql=sprintf("select count(*) as num , `Type` from  `Purchase Order History Bridge` where `Purchase Order Key`=%d group by `Type`",$subject_key);
	}elseif ($subject=='supplier_dn') {
		$sql=sprintf("select count(*) as num , `Type` from  `Supplier Delivery Note History Bridge` where `Supplier Delivery Note Key`=%d group by `Type`",$subject_key);
	}elseif ($subject=='supplier_invoice') {
		$sql=sprintf("select count(*) as num , `Type` from  `Supplier Invoice History Bridge` where `Supplier Invoice Key`=%d group by `Type`",$subject_key);
	}

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_numbers[$row['Type']]=$row['num'];
	}
	$response= array('state'=>200,'elements_numbers'=>$elements_numbers);
	echo json_encode($response);
}

function get_attachments_showcase($data) {
	global $smarty;

	$subject_key=$data['subject_key'];
	$subject=$data['subject'];

	if ($subject=='porder') {
		$object=new PurchaseOrder($data['subject_key']);
	}elseif ($subject=='supplier_dn') {
		$object=new SupplierDeliveryNote($data['subject_key']);
	}elseif ($subject=='supplier_invoice') {
		$object=new SupplierInvoice($data['subject_key']);
	}else {
		exit;
	}

	$smarty->assign('attachments',$object->get_attachments_data());
	$attachments_showcase=$smarty->fetch('attachments_showcase_splinter.tpl');
	$attachments_label=_('Attachments');
	$number_attachments=$object->get_number_attachments_formated();
	if ($number_attachments!=0) {
		$attachments_label.=' ('.$number_attachments.')';
	}

	$response= array('state'=>200,'attachments_showcase'=>$attachments_showcase,'attachments_label'=>$attachments_label);
	echo json_encode($response);
}




?>
