<?php
/*
 File: ar_porders.php


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyrigh (c) 2010, Inikoo

 Version 2.0
*/
require_once 'common.php';
if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
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


	$sql="select  POTF.`Purchase Order Net Amount`,`Purchase Order Quantity`,PO.`Purchase Order Last Updated Date`,`Purchase Order Currency Code`,`Purchase Order Current Dispatch State`,PO.`Purchase Order Key`,`Purchase Order Public ID`,`Purchase Order Total Amount`,`Purchase Order Number Items` from  $db_table   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
	//  print $sql;
	//  print $sql;
	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$status=$row['Purchase Order Current Dispatch State'];

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

		$date_interval=prepare_mysql_dates($_SESSION['state']['porders']['table']['from'],$_SESSION['state']['porders']['table']['to']);
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
		$where=sprintf(' and `Purchase Order Supplier Key`=%d',$parent_key);
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


	$sql="select  PO.`Purchase Order Last Updated Date`,`Purchase Order Currency Code`,`Purchase Order Main Buyer Name`,PO.`Purchase Order Current Dispatch State`,PO.`Purchase Order Key`,`Purchase Order Public ID`,`Purchase Order Total Amount`,`Purchase Order Number Items` from  $db_table   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
	// print $sql;
	//  print $sql;
	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$status=$row['Purchase Order Current Dispatch State'];

		$data[]=array(
			/* 'id'=>'<a href="porder.php?id='.$row['Purchase Order Key'].'">'.$row['Purchase Order Public ID']."</a>",
		   'date'=>strftime("%e %b %Y %H:%M", strtotime($row['Purchase Order Last Updated Date'])),
		   'total'=>money($row['Purchase Order Total Amount'],$row['Purchase Order Currency Code']),
		   'items'=>number($row['Purchase Order Number Items']),
		   'status'=>$status*/
			'id'=>'<a href="porder.php?id='.$row['Purchase Order Key'].'">'.$row['Purchase Order Public ID']."</a>",
			'date'=>strftime("%e %b %Y %H:%M", strtotime($row['Purchase Order Last Updated Date'])),
			'customer'=>money($row['Purchase Order Total Amount'],$row['Purchase Order Currency Code']),
			'buyer_name'=>$row['Purchase Order Main Buyer Name'],
			'state'=>number($row['Purchase Order Number Items']),
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
		if ($_REQUEST['parent']=='supplier')
			$_SESSION['state']['supplier_dns']['parent']='supplier';
		else
			$_SESSION['state']['supplier_dns']['parent']='none';
	}

	$parent=$_SESSION['state']['supplier_dns']['parent'];
	if ($parent=='supplier') {
		if (isset($_REQUEST['parent_key']) and is_numeric($_REQUEST['parent_key'])) {
			$_SESSION['state']['supplier_dns']['parent_key']=$_REQUEST['parent_key'];
		}

	}else
		$_SESSION['state']['supplier_dns']['parent_key']='';



	$parent_key=$_SESSION['state']['supplier_dns']['parent_key'];

	$conf=$_SESSION['state']['supplier_dns']['table'];
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
		$from=$_SESSION['state']['supplier_dns']['table']['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state']['supplier_dns']['table']['to'];


	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$_SESSION['state']['supplier_dns']['table']['view'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_SESSION['state']['supplier_dns']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
	$_SESSION['state']['supplier_dns']['table']['view']=$view;
	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['supplier_dns']['table']['from'],$_SESSION['state']['supplier_dns']['table']['to']);
	}else {
		$_SESSION['state']['supplier_dns']['table']['from']=$date_interval['from'];
		$_SESSION['state']['supplier_dns']['table']['to']=$date_interval['to'];
	}

	////// if($parent=='supplier')
	//////   $where.=sprintf(' and `Supplier Delivery Note Supplier Key`=%d',$parent_key);


	//    switch($view){
	//    case('all'):
	//      break;
	//    case('submited'):
	//      $where.=' and porden.status_id==10 ';
	//      break;
	//    case('new'):
	//      $where.=' and porden.status_id<10 ';
	//      break;
	//    case('received'):
	//      $where.=' and porden.status_id>80 ';
	//      break;
	//    default:


	//    }
	$where.=$date_interval['mysql'];

	$wheref='';

	if ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
	else if ($f_field=='min' and is_numeric($f_value) )
			$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
		elseif (($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
			$wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
		else if ($f_field=='maxvalue' and is_numeric($f_value) )
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
	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);



	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_("Showing all orders");



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

	$sql="select  SDND.`Supplier Delivery Note Last Updated Date`,SDND.`Supplier Delivery Note Current State`,SDND.`Supplier Delivery Note Key`,SDND.`Supplier Delivery Note Public ID`,SDND.`Supplier Delivery Note Number Items`,POD.`Purchase Order Public ID`,POD.`Purchase Order Supplier Name`,POD.`Purchase Order Total Amount`,POD.`Purchase Order Currency Code` from  `Supplier Delivery Note Dimension` SDND left join `Purchase Order Transaction Fact` POTF on (SDND.`Supplier Delivery Note Key`=POTF.`Supplier Delivery Note Key`) left join `Purchase Order Dimension` POD on (POD.`Purchase Order Key`=POTF.`Purchase Order Key`) $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

	// $sql="select  `Supplier Delivery Note Last Updated Date`,`Supplier Delivery Note Current State`,`Supplier Delivery Note Key`,`Supplier Delivery Note Public ID`,`Supplier Delivery Note Number Items` from  `Supplier Delivery Note Dimension`   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

	// print $sql;
	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$status=$row['Supplier Delivery Note Current State'];

		$data[]=array(
			'id'=>'<a href="supplier_dn.php?id='.$row['Supplier Delivery Note Key'].'">'.$row['Supplier Delivery Note Public ID']."</a>",
			'date'=>strftime("%e %b %Y %H:%M", strtotime($row['Supplier Delivery Note Last Updated Date'])),
			'items'=>number($row['Supplier Delivery Note Number Items']),
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
function list_invoices() {


	if (isset($_REQUEST['parent'])) {
		if ($_REQUEST['parent']=='supplier')
			$_SESSION['state']['supplier_dns']['parent']='supplier';
		else
			$_SESSION['state']['supplier_dns']['parent']='none';
	}

	$parent=$_SESSION['state']['supplier_dns']['parent'];
	if ($parent=='supplier') {
		if (isset($_REQUEST['parent_key']) and is_numeric($_REQUEST['parent_key'])) {
			$_SESSION['state']['supplier_dns']['parent_key']=$_REQUEST['parent_key'];
		}

	}else
		$_SESSION['state']['supplier_dns']['parent_key']='';



	$parent_key=$_SESSION['state']['supplier_dns']['parent_key'];

	$conf=$_SESSION['state']['supplier_dns']['table'];
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
		$from=$_SESSION['state']['supplier_dns']['table']['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state']['supplier_dns']['table']['to'];


	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$_SESSION['state']['supplier_dns']['table']['view'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_SESSION['state']['supplier_dns']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
	$_SESSION['state']['supplier_dns']['table']['view']=$view;
	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['supplier_dns']['table']['from'],$_SESSION['state']['supplier_dns']['table']['to']);
	}else {
		$_SESSION['state']['supplier_dns']['table']['from']=$date_interval['from'];
		$_SESSION['state']['supplier_dns']['table']['to']=$date_interval['to'];
	}

	////// if($parent=='supplier')
	//////   $where.=sprintf(' and `Supplier Delivery Note Supplier Key`=%d',$parent_key);


	//    switch($view){
	//    case('all'):
	//      break;
	//    case('submited'):
	//      $where.=' and porden.status_id==10 ';
	//      break;
	//    case('new'):
	//      $where.=' and porden.status_id<10 ';
	//      break;
	//    case('received'):
	//      $where.=' and porden.status_id>80 ';
	//      break;
	//    default:


	//    }
	$where.=$date_interval['mysql'];

	$wheref='';

	if ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
	else if ($f_field=='min' and is_numeric($f_value) )
			$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
		elseif (($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
			$wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
		else if ($f_field=='maxvalue' and is_numeric($f_value) )
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
	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);



	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_("Showing all orders");



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

	// print $sql;
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
?>
