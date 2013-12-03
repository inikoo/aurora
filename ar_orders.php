<?php
/*
 File: ar_orders.php

 Ajax Server Anchor for the Order Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyrigh (c) 2009, Inikoo

 Version 2.0
*/
require_once 'common.php';
require_once 'class.Order.php';
require_once 'class.Invoice.php';

require_once 'ar_common.php';

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


case('number_orders_in_interval'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'parent'=>array('type'=>'string'),
			'to'=>array('type'=>'string'),
			'from'=>array('type'=>'string')
		));
	number_orders_in_interval($data);
	break;

case('number_invoices_in_interval'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'parent'=>array('type'=>'string'),
			'to'=>array('type'=>'string'),
			'from'=>array('type'=>'string')
		));
	number_invoices_in_interval($data);
	break;
case('number_delivery_notes_in_interval'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'parent'=>array('type'=>'string'),
			'to'=>array('type'=>'string'),
			'from'=>array('type'=>'string')
		));
	number_delivery_notes_in_interval($data);
	break;


case('orders_lists'):
	$data=prepare_values($_REQUEST,array(
			'store'=>array('type'=>'key'),
			'block_view'=>array('type'=>'enum',
				'valid values regex'=>'/orders|invoices|dn/i'
			)

		));
	$results=orders_lists($data);
	break;
case('invoices_lists'):
	$data=prepare_values($_REQUEST,array(
			'store'=>array('type'=>'key'),
			'block_view'=>array('type'=>'enum',
				'valid values regex'=>'/orders|invoices|dn/i'
			)

		));
	$results=invoices_lists($data);
	break;
case('dn_lists'):
	$data=prepare_values($_REQUEST,array(
			'store'=>array('type'=>'key'),
			'block_view'=>array('type'=>'enum',
				'valid values regex'=>'/orders|invoices|dn/i'
			)

		));
	$results=dn_lists($data);
	break;
case('transactions_dipatched'):
	transactions_dipatched();
	break;
case('post_transactions_dipatched'):
	post_transactions_dipatched();
	break;
case('post_transactions'):
	post_transactions();
	break;
case('shortcut_key_search'):
	list_shortcut_key_search();
	break;
case('transactions_in_dn'):
	list_transactions_in_dn();
	break;
case('transactions_in_process_in_dn'):
	list_transactions_in_process_in_dn();
	break;
case('transactions_to_pick'):
	list_transactions_to_pick();
	break;
case('transactions_in_warehouse'):
	transactions_in_warehouse();
	break;
case('create_po'):
	$po=new Order('po',array('supplier_id'=>$_SESSION['state']['supplier']['id']));
	if (is_numeric($po->id)) {
		$response= array('state'=>200,'id'=>$po->id);

	} else
		$response= array('state'=>400,'id'=>_("Error: Purchase order could 't be created"));
	echo json_encode($response);
	break;



case('report_orders'):
	$_REQUEST['saveto']='report_sales';
case('orders'):
	if (!$user->can_view('orders')) {
		$results=array();
	}else {
		$results=list_orders();
	}
	break;
case('report_invoices'):
	$_REQUEST['saveto']='report_sales';

case('invoices'):

	if (!$user->can_view('orders')) {
		$results=array();
	}else {
		$results=list_invoices();
	}
	break;

case('dn'):
	if (!$user->can_view('orders')) {
		$results=array();
	}else {
		$results=list_delivery_notes();
	}

	break;
case('po_supplier'):

	if (!$user->can_view('purchase orders'))
		exit();

	list_purchase_orders_of_supplier();


	break;


case('transactions_cancelled'):
	transactions_cancelled();
	break;
case('transactions_to_process'):

	if (isset( $_REQUEST['show_all']) and preg_match('/^(yes|no)$/',$_REQUEST['show_all'])  ) {

		if ($_REQUEST['show_all']=='yes')
			$show_all=true;
		else
			$show_all=false;
		$_SESSION['state']['order']['show_all']=$show_all;
	} else
		$show_all=$_SESSION['state']['order']['show_all'];

	if ($show_all)
		products_to_sell();
	else
		transactions_to_process();

	break;
case('transactions_invoice'):
	list_transactions_in_invoice();
	break;
case('transactions_refund'):
	list_transactions_in_refund();
	break;
case('transactions'):
	list_transactions();
	break;
case('withproduct'):
	$can_see_customers=$user->can_view('customers');
	list_orders_with_product( $can_see_customers);
	break;

case('invoice_categories'):
	invoice_categories();
	break;
default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);

}


function list_orders() {

	global $myconf,$output_type,$user;

	if (isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
		$conf=$_SESSION['state']['report']['sales'];
	else
		$conf=$_SESSION['state']['orders']['orders'];

	if (isset( $_REQUEST['list_key']))
		$list_key=$_REQUEST['list_key'];
	else
		$list_key=false;


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit('no parent_key');
	}

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit('no parent');
	}


	if (isset( $_REQUEST['where']))
		$awhere=$_REQUEST['where'];
	else
		$awhere='';



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


	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['orders']['from']=$from;

	}else {
		if (isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
			$from=$conf['from'];
		else
			$from=$_SESSION['state']['orders']['from'];
	}

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['orders']['to']=$to;
	}else {
		if (isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
			$to=$conf['to'];
		else
			$to=$_SESSION['state']['orders']['to'];
	}

	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else {
		if (isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
			$view=$conf['view'];
		else
			$view=$_SESSION['state']['orders']['view'];

	}
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['elements_type']))
		$elements_type=$_REQUEST['elements_type'];
	else {
		$elements_type=$conf['elements_type'];
	}

	$elements=$conf['elements'];
	if (isset( $_REQUEST['elements_order_dispatch_InProcessCustomer'])) {
		$elements['dispatch']['InProcessCustomer']=$_REQUEST['elements_order_dispatch_InProcessCustomer'];
	}
	if (isset( $_REQUEST['elements_order_dispatch_InProcess'])) {
		$elements['dispatch']['InProcess']=$_REQUEST['elements_order_dispatch_InProcess'];
	}

	if (isset( $_REQUEST['elements_order_dispatch_Warehouse'])) {
		$elements['dispatch']['Warehouse']=$_REQUEST['elements_order_dispatch_Warehouse'];
	}
	if (isset( $_REQUEST['elements_order_dispatch_Dispatched'])) {
		$elements['dispatch']['Dispatched']=$_REQUEST['elements_order_dispatch_Dispatched'];
	}
	if (isset( $_REQUEST['elements_order_dispatch_Cancelled'])) {
		$elements['dispatch']['Cancelled']=$_REQUEST['elements_order_dispatch_Cancelled'];
	}
	if (isset( $_REQUEST['elements_order_dispatch_Suspended'])) {
		$elements['dispatch']['Suspended']=$_REQUEST['elements_order_dispatch_Suspended'];
	}




	if (isset( $_REQUEST['elements_order_source_Other'])) {
		$elements['source']['Other']=$_REQUEST['elements_order_source_Other'];
	}
	if (isset( $_REQUEST['elements_order_source_Internet'])) {
		$elements['source']['Internet']=$_REQUEST['elements_order_source_Internet'];
	}
	if (isset( $_REQUEST['elements_order_source_Call'])) {
		$elements['source']['Call']=$_REQUEST['elements_order_source_Call'];
	}
	if (isset( $_REQUEST['elements_order_source_Store'])) {
		$elements['source']['Store']=$_REQUEST['elements_order_source_Store'];
	}
	if (isset( $_REQUEST['elements_order_source_Email'])) {
		$elements['source']['Email']=$_REQUEST['elements_order_source_Email'];
	}
	if (isset( $_REQUEST['elements_order_source_Fax'])) {
		$elements['source']['Fax']=$_REQUEST['elements_order_source_Fax'];
	}

	// //  'type'=>array('Order'=>1,'Sample'=>1,'Donation'=>1,'Other'=>1),

	if (isset( $_REQUEST['elements_order_type_Other'])) {
		$elements['type']['Other']=$_REQUEST['elements_order_type_Other'];
	}
	if (isset( $_REQUEST['elements_order_type_Sample'])) {
		$elements['type']['Sample']=$_REQUEST['elements_order_type_Sample'];
	}
	if (isset( $_REQUEST['elements_order_type_Donation'])) {
		$elements['type']['Donation']=$_REQUEST['elements_order_type_Donation'];
	}
	if (isset( $_REQUEST['elements_order_type_Order'])) {
		$elements['type']['Order']=$_REQUEST['elements_order_type_Order'];
	}

	//  'payment'=>array('Paid'=>1,'PartiallyPaid'=>1,'Unknown'=>1,'WaitingPayment'=>1,'NA'=>1),

	//&elements_order_payment_PartiallyPaid=0&elements_order_payment_WaitingPayment=1&elements_order_payment_Unknown=0&elements_order_payment_Paid=0&elements_order_payment_NA=1

	if (isset( $_REQUEST['elements_order_payment_Paid'])) {
		$elements['payment']['Paid']=$_REQUEST['elements_order_payment_Paid'];
	}
	if (isset( $_REQUEST['elements_order_payment_PartiallyPaid'])) {
		$elements['payment']['PartiallyPaid']=$_REQUEST['elements_order_payment_PartiallyPaid'];
	}
	if (isset( $_REQUEST['elements_order_payment_Unknown'])) {
		$elements['payment']['Unknown']=$_REQUEST['elements_order_payment_Unknown'];
	}
	if (isset( $_REQUEST['elements_order_payment_WaitingPayment'])) {
		$elements['payment']['WaitingPayment']=$_REQUEST['elements_order_payment_WaitingPayment'];
	}
	if (isset( $_REQUEST['elements_order_payment_NA'])) {
		$elements['payment']['NA']=$_REQUEST['elements_order_payment_NA'];
	}


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');




	if (isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales') {

		$_SESSION['state']['report']['sales']['order']=$order;
		$_SESSION['state']['report']['sales']['order_dir']=$order_dir;
		$_SESSION['state']['report']['sales']['nr']=$number_results;
		$_SESSION['state']['report']['sales']['sf']=$start_from;
		$_SESSION['state']['report']['sales']['where']=$where;
		$_SESSION['state']['report']['sales']['f_field']=$f_field;
		$_SESSION['state']['report']['sales']['f_value']=$f_value;
		$_SESSION['state']['report']['sales']['to']=$to;
		$_SESSION['state']['report']['sales']['from']=$from;
		$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Order Date`');

	} else {



		$_SESSION['state']['orders']['orders']['order']=$order;
		$_SESSION['state']['orders']['orders']['order_dir']=$order_dir;
		$_SESSION['state']['orders']['orders']['nr']=$number_results;
		$_SESSION['state']['orders']['orders']['sf']=$start_from;
		$_SESSION['state']['orders']['orders']['f_field']=$f_field;
		$_SESSION['state']['orders']['orders']['f_value']=$f_value;
		$_SESSION['state']['orders']['orders']['elements_type']=$elements_type;
		$_SESSION['state']['orders']['orders']['elements']=$elements;



		$_SESSION['state']['orders']['view']=$view;

	}
	$filter_msg='';

	include_once 'splinters/orders_prepare_list.php';



	$sql="select count(Distinct O.`Order Key`) as total from $table   $where $wheref ";

	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(Distinct O.`Order Key`) as total_without_filters from $table  $where  ";
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


	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('customer_name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with customer like")." <b>$f_value</b> ";
			break;
		case('public_id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with number like")." <b>$f_value</b> ";
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with balance")."< <b>".money($f_value,$currency)."</b> ";
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with balance")."> <b>".money($f_value,$currency)."</b> ";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order from")." <b>".$find_data."</b> ";
			break;

		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('customer_name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('with name like')." <b>*".$f_value."*</b>";
			break;
		case('public_id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('with Number like')." <b>".$f_value."*</b>";
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('which balance')."< ".money($f_value,$currency);
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('which balance')."> ".money($f_value,$currency);
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('from')." ".$find_data;
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


	if ($order=='id')
		$order='`Order File As`';
	elseif ($order=='last_date' or $order=='date')
		$order='O.`Order Date`';

	elseif ($order=='customer')
		$order='O.`Order Customer Name`';
	elseif ($order=='state')
		$order='O.`Order Current Dispatch State`';
	elseif ($order=='total_amount')
		$order='O.`Order Total Amount`';
	else
		$order='`Order File As`';

	//$sql="select   * from  $table   $where $wheref  $where_type $where_interval  order by $order $order_direction limit $start_from,$number_results";
	//    $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  $where_type group by O.`Order Key` order by $order $order_direction limit $start_from,$number_results";
	$sql="select `Order Balance Total Amount`,`Order Current Payment State`,`Order Current Dispatch State`,`Order Out of Stock Net Amount`,`Order Invoiced Total Net Adjust Amount`,`Order Invoiced Total Tax Adjust Amount`,FORMAT(`Order Invoiced Total Net Adjust Amount`+`Order Invoiced Total Tax Adjust Amount`,2) as `Order Adjust Amount`,`Order Out of Stock Net Amount`,`Order Out of Stock Tax Amount`,FORMAT(`Order Out of Stock Net Amount`+`Order Out of Stock Tax Amount`,2) as `Order Out of Stock Amount`,`Order Invoiced Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,`Order Key`,`Order Public ID`,`Order Customer Key`,`Order Customer Name`,`Order Last Updated Date`,`Order Date`,`Order Total Amount` ,`Order Current XHTML State` from `Order Dimension` O  $where $wheref  order by $order $order_direction ".($output_type=='ajax'?"limit $start_from,$number_results":'');
	//print $where;exit;
	//  print $sql;
	$adata=array();



	$result=mysql_query($sql);


	if ($output_type=='ajax') {
		while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


			$id="<a href='order.php?p=cs&id=".$data['Order Key']."'>".$myconf['order_id_prefix'].sprintf("%05s",$data['Order Public ID']).'</a>';

			$name=" <a href='customer.php?p=cs&id=".$data['Order Key']."'>".($data['Order Customer Name']==''?'<i>'._('Unknown name').'</i>':$data['Order Customer Name']).'</a>';


			$state=$data['Order Current XHTML State'];
			if ($data ['Order Type'] != 'Order')
				$state.=' ('.$data ['Order Type'].')';


			$mark_out_of_stock="<span style='visibility:hidden'>&otimes;</span>";
			$mark_out_of_credits="<span style='visibility:hidden'>&crarr;</span>";
			$mark_out_of_error="<span style='visibility:hidden'>&epsilon;</span>";
			$out_of_stock=false;
			$errors=false;
			$refunded=false;
			if ($data['Order Out of Stock Amount']!=0) {
				$out_of_stock=true;
				$info='';
				if ($data['Order Out of Stock Net Amount']!=0) {
					$info.=_('Net').': '.money($data['Order Out of Stock Net Amount'],$data['Order Currency'])."";
				}
				if ($data['Order Out of Stock Tax Amount']!=0) {
					$info.='; '._('Tax').': '.money($data['Order Out of Stock Tax Amount'],$data['Order Currency']);
				}
				$info=preg_replace('/^\;\s*/','',$info);
				$mark_out_of_stock="<span style='color:bdatan'  title='$info'  >&otimes;</span>";

			}

			if ($data['Order Adjust Amount']<-0.01 or $data['Order Adjust Amount']>0.01 ) {
				$errors=true;
				$info='';
				if ($data['Order Invoiced Total Net Adjust Amount']!=0) {
					$info.=_('Net').': '.money($data['Order Invoiced Total Net Adjust Amount'],$data['Order Currency'])."";
				}
				if ($data['Order Invoiced Total Tax Adjust Amount']!=0) {
					$info.='; '._('Tax').': '.money($data['Order Invoiced Total Tax Adjust Amount'],$data['Order Currency']);
				}
				$info=_('Errors').' '.preg_replace('/^\;\s*/','',$info);
				if ($data['Order Adjust Amount']<-1 or $data['Order Adjust Amount']>1 ) {
					$mark_out_of_error ="<span style='color:red' title='$info'>&epsilon;</span>";
				} else {
					$mark_out_of_error ="<span style='color:bdatan'  title='$info'>&epsilon;</span>";
				}
				//$mark_out_of_error.=$data['Order Adjust Amount'];
			}


			if (!$out_of_stock and !$refunded)
				$mark=$mark_out_of_error.$mark_out_of_stock.$mark_out_of_credits;
			elseif (!$refunded and $out_of_stock and $errors)
				$mark=$mark_out_of_stock.$mark_out_of_error.$mark_out_of_credits;
			else
				$mark=$mark_out_of_stock.$mark_out_of_credits.$mark_out_of_error;

			$customer=sprintf('<a href="customer.php?id=%d">%s</a>',$data['Order Customer Key'],$data['Order Customer Name']);


			$adata[]=array(
				'id'=>$id,
				'date'=>strftime("%c", strtotime($data['Order Date'].' +0:00')),
				'last_date'=>strftime("%c", strtotime($data['Order Last Updated Date'].' +0:00')),
				'customer'=>$customer,
				'state'=>$data['Order Current Dispatch State'],
				'total_amount'=>money($data['Order Total Amount'],$data['Order Currency']).$mark,


			);

		}
	}
	else {
		$fields=explode(",",$user->get_table_export_fields('ar_orders','orders'));
		while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$_data=array();
			foreach ($fields as $field) {
				$_data[]=$data[$field];
			}
			$adata[]=$_data;
			//exit;
		}
	}


	mysql_free_result($result);

	//print_r($adata);


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
	if ($output_type=='ajax') {
		echo json_encode($response);
		return;
	} else {
		return $response;
	}
}

function list_transactions_in_invoice() {

	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
		$order_id=$_REQUEST['id'];
	else
		$order_id=$_SESSION['state']['invoice']['id'];




	$where=' where `Invoice Quantity`!=0 and  `Invoice Key`='.$order_id;
	$where2=' where  `Invoice Key`='.$order_id;
	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();
	$sql="select * from `Order Transaction Fact` O   left join  `Product Dimension` P on (O.`Product ID`=P.`Product ID`) $where order by O.`Product Code`  ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
	//   print $sql;
	$result=mysql_query($sql);
	$total_gross=0;
	$total_discount=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//   $total_charged+=$row['charge'];
		//      $total_discounts+=$ndiscount;
		//      $total_picks+=$row['dispatched'];
		$total_discount+=$row['Invoice Transaction Total Discount Amount'];
		$total_gross+=$row['Invoice Transaction Gross Amount'];
		$code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);

		if ($row['Invoice Transaction Total Discount Amount']==0)
			$discount='';
		else
			$discount=money($row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code']);

		if ($row['Product Tariff Code']!='')
			$tariff_code=' <span style="color:#777" >('.$row['Product Tariff Code'].')</span>';
		else
			$tariff_code='';

		$data[]=array(

			'code'=>$code,
			'description'=>$row['Product XHTML Short Description'].$tariff_code,
			'tariff_code'=>$row['Product Tariff Code'],
			'quantity'=>number($row['Invoice Quantity']),
			'gross'=>money($row['Invoice Transaction Gross Amount'],$row['Invoice Currency Code']),
			'discount'=>$discount,
			'to_charge'=>money($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code'])
		);
	}


	$sql="select * from `Order No Product Transaction Fact` $where2  ";
	//print $sql;
	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
	//   print $sql;
	$result=mysql_query($sql);
	$total_gross=0;
	$total_discount=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//   $total_charged+=$row['charge'];
		//      $total_discounts+=$ndiscount;
		//      $total_picks+=$row['dispatched'];
		//$total_discount+=$row['Invoice Transaction Total Discount Amount'];
		//$total_gross+=$row['Invoice Transaction Gross Amount'];
		//$code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);


		$data[]=array(

			'code'=>'',
			'description'=>$row['Transaction Description'],
			'tariff_code'=>'',
			'quantity'=>'',
			'gross'=>money($row['Transaction Invoice Net Amount'],$row['Currency Code']),
			'discount'=>'',
			'to_charge'=>money($row['Transaction Invoice Net Amount']+$row['Transaction Invoice Tax Amount'],$row['Currency Code'])
		);
	}


	/*
        $invoice=new Invoice($order_id);



        if ($invoice->data['Invoice Shipping Net Amount']!=0) {

            $data[]=array(

                        'code'=>'',
                        'description'=>_('Shipping'),
                        'tariff_code'=>'',
                        'quantity'=>'',
                        'gross'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency']),
                        'discount'=>'',
                        'to_charge'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency'])
                    );

        }
        if ($invoice->data['Invoice Charges Net Amount']!=0) {
            $data[]=array(

                        'code'=>'',
                        'description'=>_('Charges'),
                        'tariff_code'=>'',
                        'quantity'=>'',
                        'gross'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency']),
                        'discount'=>'',
                        'to_charge'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency'])
                    );
        }
        if ($invoice->data['Invoice Total Tax Amount']!=0) {
            $data[]=array(

                        'code'=>'',
                        'description'=>_('Tax'),
                        'tariff_code'=>'',
                        'quantity'=>'',
                        'gross'=>money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency']),
                        'discount'=>'',
                        'to_charge'=>money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency'])
                    );
        }

        $data[]=array(

                    'code'=>'',
                    'description'=>_('Total'),
                    'tariff_code'=>'',
                    'quantity'=>'',
                    'gross'=>'',
                    'discount'=>'',
                    'to_charge'=>'<b>'.money($invoice->data['Invoice Total Amount'],$invoice->data['Invoice Currency']).'</b>'
                );

             */


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}
function list_transactions_in_refund() {

	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$order_id=$_REQUEST['id'];
	} else {
		$order_id=$_SESSION['state']['invoice']['id'];
	}
	$where=' where   `Refund Key`='.$order_id;
	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();
	$sql="select `Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Item Tax Amount`,`Invoice Quantity`,`Invoice Transaction Tax Refund Amount`,`Invoice Currency Code`,`Invoice Transaction Net Refund Amount`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code` from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) $where   ";
	$result=mysql_query($sql);

	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
		$data[]=array(
			'code'=>$code,
			'description'=>$row['Product XHTML Short Description'],
			'charged'=>$row['Invoice Quantity'].'/'.money($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code']).'('.money($row['Invoice Transaction Item Tax Amount'],$row['Invoice Currency Code']).')',
			'refund_net'=>money($row['Invoice Transaction Net Refund Amount'],$row['Invoice Currency Code']),
			'refund_tax'=>money($row['Invoice Transaction Tax Refund Amount'],$row['Invoice Currency Code'])
		);
	}
	$sql="select * from `Order No Product Transaction Fact`    $where   ";
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$data[]=array(
			'code'=>'',
			'description'=>$row['Transaction Description'],
			'refund_net'=>money($row['Transaction Invoice Net Amount'],$row['Currency Code']),
			'refund_tax'=>money($row['Transaction Invoice Tax Amount'],$row['Currency Code'])

		);
	}


	$invoice=new Invoice($order_id);

	if ($invoice->data['Invoice Shipping Net Amount']!=0) {

		$data[]=array(
			'code'=>'',
			'description'=>_('Shipping'),
			'refund_net'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency'])
		);

	}
	if ($invoice->data['Invoice Charges Net Amount']!=0) {
		$data[]=array(
			'code'=>'',
			'gross'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency']),
			'refund_net'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency'])
		);
	}

	$data[]=array(
		'code'=>'',
		'description'=>_('Total'),
		'refund_net'=>'<b>'.money($invoice->data['Invoice Total Net Amount'],$invoice->data['Invoice Currency']).'</b>',
		'refund_tax'=>'<b>'.money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency']).'</b>'

	);

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
		)
	);
	echo json_encode($response);
}

function list_transactions_in_dn() {

	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
		$order_id=$_REQUEST['id'];
	else
		$order_id=$_SESSION['state']['dn']['id'];




	$where=sprintf(' where   `Delivery Note Key`=%d and `Inventory Transaction Type`!="Adjust"',$order_id);

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();
	$sql="select `Delivery Note Quantity`,`Product Tariff Code`,O.`Product Code`, PH.`Product ID` ,`Product XHTML Short Description` from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) $where   ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";

	$result=mysql_query($sql);
	$total_gross=0;
	$total_discount=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


		$data[]=array(

			'code'=>sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code'])
			,'description'=>$row['Product XHTML Short Description']
			,'tariff_code'=>$row['Product Tariff Code']
			,'quantity'=>number($row['Delivery Note Quantity'])

		);
	}



	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}
function list_transactions_in_process_in_dn() {

	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
		$order_id=$_REQUEST['id'];
	else
		$order_id=$_SESSION['state']['dn']['id'];




	$where=sprintf(' where   `Delivery Note Key`=%d and `Inventory Transaction Type`!="Adjust"',$order_id);

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();

	$sql=sprintf("select `Inventory Transaction Quantity`,`Given`,`Packed`,`Picked`,`Out of Stock`,`Not Found`,`No Picked Other`,`Picking Note`,`Required`,`Part Unit Description`,`Part XHTML Currently Used In`,ITF.`Part SKU` from `Inventory Transaction Fact` as ITF left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`)$where");

	$result=mysql_query($sql);
	$total_gross=0;
	$total_discount=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


		$notes='';

		if ($row['Out of Stock']!=0) {
			$notes.=_('Out of Stock').' '.number($row['Out of Stock']).'<br/>';
		}
		if ($row['Not Found']!=0) {
			$notes.=_('Not Found').' '.number($row['Not Found']).'<br/>';
		}
		if ($row['No Picked Other']!=0) {
			$notes.=_('Not picked (other)').' '.number($row['No Picked Other']).'<br/>';
		}

		$notes=preg_replace('/\<br\/\>$/', '', $notes);

		$data[]=array(

			'part'=>sprintf('<a href="part.php?sku=%d">SKU%05d</a>',$row['Part SKU'],$row['Part SKU']),
			'description'=>$row['Part Unit Description'].($row['Picking Note']?' <i>('.$row['Picking Note'].')</i>':''),
			'given'=>($row['Given']==0?'':number($row['Given'])),

			'quantity'=>number($row['Required']),
			'dispatched'=>number(-1*$row['Inventory Transaction Quantity']),
			'packed'=>number($row['Packed']),
			'picked'=>number($row['Picked']),
			'notes'=>$notes,



		);
	}



	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}



function list_orders_with_product($can_see_customers=false) {

	$conf=$_SESSION['state']['product']['orders'];





	if (isset( $_REQUEST['code'])) {
		$tag=$_REQUEST['code'];
		$mode='code';
	} elseif (isset( $_REQUEST['id'])) {
		$tag=$_REQUEST['product_pid'];
		$mode='pid';
	} elseif (isset( $_REQUEST['key'])) {
		$tag=$_REQUEST['key'];
		$mode='key';
	} else {
		$tag=$_SESSION['state']['product']['tag'];
		$mode=$_SESSION['state']['product']['mode'];
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (!is_numeric($start_from))
		$start_from=0;

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



	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_SESSION['state']['product']['orders']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'tag'=>$tag,'mode'=>$mode);
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';




	if ($mode=='code')
		$where=$where.sprintf(" and `Product Code`=%s ",prepare_mysql($tag));
	elseif ($mode=='pid')
		$where=$where.sprintf(" and `Product ID`=%d ",$tag);
	elseif ($mode=='key')
		$where=$where.sprintf(" and `Product Key`=%d ",$tag);

	$wheref='';

	if (($f_field=='customer_name')  and $f_value!='') {
		$wheref="  and  `Order Customer Name` like '%".addslashes($f_value)."%'";
	}
	elseif (($f_field=='postcode')  and $f_value!='') {
		$wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
	}
	elseif ($f_field=='public_id')
		$wheref.=" and  `Order Public ID`  like '".addslashes($f_value)."%'";

	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  `Order Invoiced Balance Total Amount`<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  `Order Invoiced Balance Total Amount`>=".$f_value."    ";
	elseif ($f_field=='country' and  $f_value!='') {
		if ($f_value=='UNK') {
			$wheref.=" and  `Order Main Country Code`='".$f_value."'    ";
			$find_data=' '._('a unknown country');
		} else {
			$f_value=Address::parse_country($f_value);
			if ($f_value!='UNK') {
				$wheref.=" and  `Order Main Country Code`='".$f_value."'    ";
				$country=new Country('code',$f_value);
				$find_data=' '.$country->data['Country Name'].' <img style="vertical-align: text-bottom;position:relative;bottom:2px" src="art/flags/'.strtolower($country->data['Country 2 Alpha Code']).'.gif" alt="'.$country->data['Country Code'].'"/>';
			}
		}
	}



	$sql="select count(DISTINCT `Order Key`) as total from `Order Transaction Fact` OTF    $where $wheref";
	//print $sql;
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(DISTINCT `Order Key`) as total from `Order Transaction Fact` OTF  $where      ";
		$res = mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}

	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('customer_name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with customer like")." <b>$f_value</b> ";
			break;
		case('public_id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with number like")." <b>$f_value</b> ";
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with balance")."< <b>".money($f_value,$currency)."</b> ";
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with balance")."> <b>".money($f_value,$currency)."</b> ";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order from")." <b>".$find_data."</b> ";
			break;

		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('customer_name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('with name like')." <b>*".$f_value."*</b>";
			break;
		case('public_id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('with Number like')." <b>".$f_value."*</b>";
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('which balance')."< ".money($f_value,$currency);
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('which balance')."> ".money($f_value,$currency);
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('from')." ".$find_data;
			break;
		}
	}
	else
		$filter_msg='';







	if ($order=='dispatched')
		$order='`Shipped Quantity`';
	elseif ($order=='order') {
		$order='`Order Public ID`';

	}
	elseif ($order=='customer_name') {
		$order='`Customer Name`';

	}
	elseif ($order=='dispatched') {
		$order='dispatched';

	}
	elseif ($order=='undispatched') {
		$order='undispatched';


	}
	else {
		$order='OTF.`Order Date`';

	}


	$sql=sprintf("select OTF.`Order Key`,OTF.`Order Public ID`,`Customer Name`,CD.`Customer Key`,OTF.`Order Date`,sum(`Shipped Quantity`) as dispatched,
                 sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`+`No Shipped Due Other`) as undispatched  from
                 `Order Transaction Fact` OTF left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)     %s %s   group by OTF.`Order Key`  order by  $order $order_direction  limit $start_from,$number_results"
		,$where
		,$wheref
	);
	// print $sql;

	$res=mysql_query($sql);
	$data=array();

	while ($row= mysql_fetch_array($res, MYSQL_ASSOC) ) {
		if ($can_see_customers)
			$customer='<a href="customer.php?id='.$row['Customer Key'].'">'.$row['Customer Name'].'</a>';
		else
			$customer=$myconf['customer_id_prefix'].sprintf("%05d",$row['Customer Key']);



		$data[]=array(
			'order'=>sprintf("<a href='order.php?id=%d'>%s</a>",$row['Order Key'],$row['Order Public ID']),
			'customer_name'=>$customer,
			'date'=> strftime("%e %b %y", strtotime($row['Order Date'].' +0:00')),
			'dispatched'=>number($row['dispatched']),
			'undispatched'=>number($row['undispatched'])

		);
	}

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$data,
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



function list_delivery_notes() {
	global $myconf,$output_type,$user;


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit('no parent_key');
	}

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit('no parent');
	}


	switch ($parent) {
	case 'part':
		$conf=$_SESSION['state']['part']['dn'];
		$conf_tag='part';
					$conf2=$_SESSION['state']['part'];

		break;
	default:
		$conf=$_SESSION['state']['orders']['dn'];
		$conf_tag='orders';
			$conf2=$_SESSION['state']['orders'];
		

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

	if (isset( $_REQUEST['where']))
		$awhere=$_REQUEST['where'];
	else
		$awhere='';

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$conf2['from'];

	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf2['to'];



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['elements_type']))
		$elements_type=$_REQUEST['elements_type'];
	else {
		$elements_type=$conf['elements_type'];
	}



	$elements=$conf['elements'];
	if (isset( $_REQUEST['elements_dn_dispatch_Ready'])) {
		$elements['dispatch']['Ready']=$_REQUEST['elements_dn_dispatch_Ready'];
	}
	if (isset( $_REQUEST['elements_dn_dispatch_Picking'])) {
		$elements['dispatch']['Picking']=$_REQUEST['elements_dn_dispatch_Picking'];
	}

	if (isset( $_REQUEST['elements_dn_dispatch_Packing'])) {
		$elements['dispatch']['Packing']=$_REQUEST['elements_dn_dispatch_Packing'];
	}
	if (isset( $_REQUEST['elements_dn_dispatch_Done'])) {
		$elements['dispatch']['Done']=$_REQUEST['elements_dn_dispatch_Done'];
	}
	if (isset( $_REQUEST['elements_dn_dispatch_Send'])) {
		$elements['dispatch']['Send']=$_REQUEST['elements_dn_dispatch_Send'];
	}
	if (isset( $_REQUEST['elements_dn_dispatch_Returned'])) {
		$elements['dispatch']['Returned']=$_REQUEST['elements_dn_dispatch_Returned'];
	}



	if (isset( $_REQUEST['elements_dn_type_Replacements'])) {
		$elements['type']['Replacements']=$_REQUEST['elements_dn_type_Replacements'];
	}
	if (isset( $_REQUEST['elements_dn_type_Sample'])) {
		$elements['type']['Sample']=$_REQUEST['elements_dn_type_Sample'];
	}
	if (isset( $_REQUEST['elements_dn_type_Donation'])) {
		$elements['type']['Donation']=$_REQUEST['elements_dn_type_Donation'];
	}
	if (isset( $_REQUEST['elements_dn_type_Order'])) {
		$elements['type']['Order']=$_REQUEST['elements_dn_type_Order'];
	}
	if (isset( $_REQUEST['elements_dn_type_Shortages'])) {
		$elements['type']['Shortages']=$_REQUEST['elements_dn_type_Shortages'];
	}

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_SESSION['state'][$conf_tag]['dn']['order']=$order;
	$_SESSION['state'][$conf_tag]['dn']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_tag]['dn']['nr']=$number_results;
	$_SESSION['state'][$conf_tag]['dn']['sf']=$start_from;
	$_SESSION['state'][$conf_tag]['dn']['where']=$awhere;
	$_SESSION['state'][$conf_tag]['dn']['f_field']=$f_field;
	$_SESSION['state'][$conf_tag]['dn']['f_value']=$f_value;
	$_SESSION['state'][$conf_tag]['dn']['elements_type']=$elements_type;
	$_SESSION['state'][$conf_tag]['dn']['elements']=$elements;

	$_SESSION['state'][$conf_tag]['from']=$from;
	$_SESSION['state'][$conf_tag]['to']=$to;

	include_once 'splinters/dn_prepare_list.php';

	$sql="select count(distinct D.`Delivery Note Key`) as total from $table  $where $wheref ";
	// print $sql ;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(distinct D.`Delivery Note Key`) as total from $table  $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);
	}
	$rtext=number($total_records)." ".ngettext('delivery note','delivery notes',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';
	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No delivery note with ID")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes starting with')." <b>$f_value</b>)";
		break;
	case('customer_name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No delivery note with with customer")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes with customer')." <b>".$f_value."*</b>)";
		break;
	case('country'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No delivery note from")." <b>".$find_data."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('delivery note','delivery notes',$total)." "._('to')." ".$find_data;
		break;





	}




	$_order=$order;
	$_dir=$order_direction;


	if ($order=='date' or $order=='')
		$order='`Delivery Note Date Created`';
	elseif ($order=='id')
		$order='`Delivery Note File As`';
	elseif ($order=='customer')
		$order='`Delivery Note Customer Name`';
	elseif ($order=='type')
		$order='`Delivery Note Type`';
	elseif ($order=='weight')
		$order='`Delivery Note Weight`';
	elseif ($order=='parcels')
		$order='`Delivery Note Parcel Type`,`Delivery Note Number Parcels`';


	$sql="select *  from $table  $where $wheref $group order by $order $order_direction ".($output_type=='ajax'?"limit $start_from,$number_results":'');
	// print $sql;

	$adata=array();

	$res = mysql_query($sql);
	if ($output_type=='ajax') {
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$order_id=sprintf('<a href="dn.php?id=%d">%s</a>',$row['Delivery Note Key'],$row['Delivery Note ID']);
			$customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Delivery Note Customer Key'],$row['Delivery Note Customer Name']);


			$type=$row['Delivery Note Type'];

			switch ($row['Delivery Note Parcel Type']) {
			case('Pallet'):
				$parcel_type='P';
				break;
			case('Envelope'):
				$parcel_type='e';
				break;
			default:
				$parcel_type='b';

			}

			if ($row['Delivery Note Number Parcels']=='') {
				$parcels='?';
			}
			elseif ($row['Delivery Note Parcel Type']=='Pallet' and $row['Delivery Note Number Boxes']) {
				$parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type.' ('.$row['Delivery Note Number Boxes'].' b)';
			}
			else {
				$parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type;
			}


			//if ($row['Delivery Note State']=='Dispatched')
			// $date=strftime("%e %b %y", strtotime($row['Delivery Note Date'].' +0:00'));
			//else
			$date=strftime("%c", strtotime($row['Delivery Note Date Created'].' +0:00'));




			$adata[]=array(
				'id'=>$order_id
				,'customer'=>$customer
				,'date'=>$date
				,'type'=>$type
				,'state'=>$row['Delivery Note XHTML State']
				//,'orders'=>$row['Delivery Note XHTML Orders']
				//,'invoices'=>$row['Delivery Note XHTML Invoices']
				,'weight'=>number($row['Delivery Note Weight'],1,true).' Kg'
				,'parcels'=>$parcels


			);
		}
	}
	else {
		$fields=explode(",",$user->get_table_export_fields('ar_orders','dn'));
		while ($data=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$_data=array();
			foreach ($fields as $field) {
				$_data[]=$data[$field];
			}
			$adata[]=$_data;
		}
	}
	mysql_free_result($res);

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
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	if ($output_type=='ajax') {
		echo json_encode($response);
		return;
	} else {
		return $response;
	}
}

function list_invoices() {


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




	$conf=$_SESSION['state']['orders']['invoices'];
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
		$awhere=false;//$conf['where'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=1;

	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['orders']['from']=$from;
	}else
		$from=$_SESSION['state']['orders']['from'];

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['orders']['to']=$to;
	}else
		$to=$_SESSION['state']['orders']['to'];



	if (isset( $_REQUEST['elements_type']))
		$elements_type=$_REQUEST['elements_type'];
	else {
		$elements_type=$conf['elements_type'];
	}

	$elements=$conf['elements'];

	if (isset( $_REQUEST['elements_invoice_type_Invoice'])) {
		$elements['type']['Invoice']=$_REQUEST['elements_invoice_type_Invoice'];
	}
	if (isset( $_REQUEST['elements_invoice_type_Refund'])) {
		$elements['type']['Refund']=$_REQUEST['elements_invoice_type_Refund'];
	}


	if (isset( $_REQUEST['elements_invoice_payment_Yes'])) {
		$elements['payment']['Yes']=$_REQUEST['elements_invoice_payment_Yes'];
	}
	if (isset( $_REQUEST['elements_invoice_payment_Partially'])) {
		$elements['payment']['Partially']=$_REQUEST['elements_invoice_payment_Partially'];
	}
	if (isset( $_REQUEST['elements_invoice_payment_No'])) {
		$elements['payment']['No']=$_REQUEST['elements_invoice_payment_No'];
	}





	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_SESSION['state']['orders']['invoices']['order']=$order;
	$_SESSION['state']['orders']['invoices']['order_dir']=$order_direction;
	$_SESSION['state']['orders']['invoices']['nr']=$number_results;
	$_SESSION['state']['orders']['invoices']['sf']=$start_from;
	$_SESSION['state']['orders']['invoices']['where']=$awhere;
	$_SESSION['state']['orders']['invoices']['f_field']=$f_field;
	$_SESSION['state']['orders']['invoices']['f_value']=$f_value;
	$_SESSION['state']['orders']['invoices']['elements']=$elements;
	$_SESSION['state']['orders']['invoices']['elements_type']=$elements_type;

	include_once 'splinters/invoices_prepare_list.php';


	$sql="select count(Distinct I.`Invoice Key`) as total from $table   $where $wheref ";

	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(Distinct I.`Invoice Key`) as total_without_filters from $table  $where ";
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


	$rtext=number($total_records)." ".ngettext('invoice','invoices',$total_records);
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
		case('customer_name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No invoice with customer like")." <b>$f_value</b> ";
			break;
		case('public_id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any invoice with ID like")." <b>$f_value</b> ";
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No invoice with total")."< <b>".money($f_value,$currency)."</b> ";
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No invoice with total")."> <b>".money($f_value,$currency)."</b> ";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any invoice billed to").$find_data;
			break;


		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('customer_name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('invoice','invoices',$total)." "._('with customer like')." <b>*".$f_value."*</b>";
			break;
		case('public_id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('invoice','invoices',$total)." "._('with ID like')." <b>".$f_value."*</b>";
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('invoice','invoices',$total)." "._('which total')."< ".money($f_value,$currency);
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('invoice','invoices',$total)." "._('which total')."> ".money($f_value,$currency);
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('invoice','invoices',$total)." "._('billed to').$find_data;
			break;

		}
	}
	else
		$filter_msg='';





	$_order=$order;
	$_dir=$order_direction;

	if ($order=='date')
		$order='`Invoice Date`';
	elseif ($order=='last_date')
		$order='`Invoice Last Updated Date`';
	elseif ($order=='id')
		$order='`Invoice File As`';

	elseif ($order=='total_amount')
		$order='`Invoice Total Amount`';

	elseif ($order=='items')
		$order='`Invoice Items Net Amount`';
	elseif ($order=='shipping')
		$order='`Invoice Shipping Net Amount`';

	elseif ($order=='customer')
		$order='`Invoice Customer Name`';
	elseif ($order=='method')
		$order='`Invoice Main Payment Method`';
	elseif ($order=='type')
		$order='`Invoice Type`';
	elseif ($order=='state')
		$order='`Invoice Paid`';
	elseif ($order=='net')
		$order='`Invoice Total Net Amount`';

	//
	$sql="select  `S4`,`S1`,`Invoice Total Tax Amount`,`Invoice Type`,`Invoice XHTML Delivery Notes`,`Invoice Shipping Net Amount`,`Invoice Total Net Amount`,`Invoice Items Net Amount`,`Invoice XHTML Orders`,`Invoice Total Amount`,I.`Invoice Key`,`Invoice Customer Name`,`Invoice Public ID`,`Invoice Customer Key`,`Invoice Date`,`Invoice Currency`,`Invoice Has Been Paid In Full` from  $table  left join `Invoice Tax Dimension` IT on (I.`Invoice Key`=IT.`Invoice Key`)  $where $wheref  $where_type $where_interval   order by $order $order_direction ".($output_type=='ajax'?"limit $start_from,$number_results":'');


	$sql="select  * from  $table  left join `Invoice Tax Dimension` IT on (I.`Invoice Key`=IT.`Invoice Key`)  $where $wheref     order by $order $order_direction ".($output_type=='ajax'?"limit $start_from,$number_results":'');

	//    $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  $where_type group by O.`Order Key` order by $order $order_direction limit $start_from,$number_results";




	//print $sql;
	$adata=array();



	$result=mysql_query($sql);
	// print $sql;


	if ($output_type=='ajax') {

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$order_id=sprintf('<a href="invoice.php?id=%d">%s</a>',$row['Invoice Key'],$row['Invoice Public ID']);
			$customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Invoice Customer Key'],$row['Invoice Customer Name']);
			if ($row['Invoice Paid']=='Yes')
				$state=_('Paid');
			else if ($row['Invoice Paid']=='Partially')
					$state=_('No Paid');
				else
					$state=_('Partially Paid');

				if ($row['Invoice Type']=='Invoice')
					$type=_('Invoice');
				else
					$type=_('Refund');

				switch ($row['Invoice Main Payment Method']) {
				default:
					$method=$row['Invoice Main Payment Method'];
				}


			$adata[]=array(
				'id'=>$order_id
				,'customer'=>$customer
				,'date'=>strftime("%c", strtotime($row['Invoice Date'].' +0:00'))
				//,'day_of_week'=>strftime("%a", strtotime($row['Invoice Date'].' +0:00'))
				,'total_amount'=>money($row['Invoice Total Amount'],$row['Invoice Currency'])
				,'net'=>money($row['Invoice Total Net Amount'],$row['Invoice Currency'])
				,'shipping'=>money($row['Invoice Shipping Net Amount'],$row['Invoice Currency'])
				,'items'=>money($row['Invoice Items Net Amount'],$row['Invoice Currency'])
				,'type'=>$type
				,'method'=>$method
				,'state'=>$state
				,'orders'=>$row['Invoice XHTML Orders']
				,'dns'=>$row['Invoice XHTML Delivery Notes']
			);

		}
	}
	else {
		$fields=explode(",",$user->get_table_export_fields('ar_orders','invoices'));
		while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$_data=array();
			foreach ($fields as $field) {
				$_data[]=$data[$field];
			}
			$adata[]=$_data;
		}
	}

	mysql_free_result($result);

	///print_r($dataid);//


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
	if ($output_type=='ajax') {
		echo json_encode($response);
		return;
	} else {
		return $response;
	}

}








function transactions_to_process() {
	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
		$order_id=$_REQUEST['id'];
	else
		$order_id=$_SESSION['state']['order']['id'];




	$where=' where `Order Key`='.$order_id;

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();
	$sql="select * from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where   ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";





	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//   $total_charged+=$row['charge'];
		//      $total_discounts+=$ndiscount;
		//      $total_picks+=$row['dispatched'];
		$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
		$data[]=array(





			'code'=>$code
			,'description'=>$row['Product XHTML Short Description']
			,'tariff_code'=>$row['Product Tariff Code']
			,'quantity'=>number($row['Order Quantity'])
			,'gross'=>money($row['Order Transaction Gross Amount'],$row['Order Currency Code'])
			,'discount'=>money($row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
			,'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function transactions_dipatched() {
	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
		$order_id=$_REQUEST['id'];
	else
		$order_id=$_SESSION['state']['order']['id'];




	$where=' where `Order Transaction Type` not in ("Resend")  and  O.`Order Key`='.$order_id;

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();

	$order=' order by O.`Product Code`';

	$sql="select `No Shipped Due Other`,`No Shipped Due Not Found`,`No Shipped Due No Authorized`,O.`Order Transaction Fact Key`,`Deal Info`,`Operation`,`Quantity`,`Order Currency Code`,`Order Quantity`,`Order Bonus Quantity`,`No Shipped Due Out of Stock`,P.`Product ID` ,P.`Product Code`,`Product XHTML Short Description`,`Shipped Quantity`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as amount
         from `Order Transaction Fact` O left join `Product Dimension` P on (P.`Product ID`=O.`Product ID`)
         left join `Order Post Transaction Dimension` POT on (O.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`)
         left join `Order Transaction Deal Bridge` DB on (DB.`Order Transaction Fact Key`=O.`Order Transaction Fact Key`)

         $where $order  ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";

	//print $sql;

	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$ordered='';
		if ($row['Order Quantity']!=0)
			$ordered.=number($row['Order Quantity']);
		if ($row['Order Bonus Quantity']>0) {
			$ordered='<br/>'._('Bonus').' +'.number($row['Order Bonus Quantity']);
		}
		if ($row['No Shipped Due No Authorized']>0) {
			$ordered.='<br/> '._('No Authorized').' -'.number($row['No Shipped Due No Authorized']);
		}
		if ($row['No Shipped Due Out of Stock']>0) {
			$ordered.='<br/> '._('No Stk').' -'.number($row['No Shipped Due Out of Stock']);
		}
		if ($row['No Shipped Due Not Found']>0) {
			$ordered.='<br/> '._('No Found').' -'.number($row['No Shipped Due Not Found']);
		}
		if ($row['No Shipped Due Other']>0) {
			$ordered.='<br/> '._('No Other').' -'.number($row['No Shipped Due Other']);
		}

		$ordered=preg_replace('/^<br\/>/','',$ordered);
		$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);

		$dispatched=number($row['Shipped Quantity']);

		if ($row['Quantity']>0  and $row['Operation']=='Resend') {
			$dispatched.='<br/> '._('Resend').' +'.number($row['Quantity']);
		}

		$data[]=array(

			'code'=>$code
			,'description'=>$row['Product XHTML Short Description'].' <span style="color:red">'.$row['Deal Info'].'</span>'

			,'ordered'=>$ordered
			,'dispatched'=>$dispatched
			,'invoiced'=>money($row['amount'],$row['Order Currency Code'])
		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}
function post_transactions_dipatched() {
	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
		$order_id=$_REQUEST['id'];
	else
		$order_id=$_SESSION['state']['order']['id'];




	$where=' where `Order Transaction Type`  in ("Replacement","Missing")  and  `Order Key`='.$order_id;

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();

	$order=' order by `Product Code`';

	$sql="select `Order Transaction Type`,`Delivery Note Quantity`,`Delivery Note ID`,`Delivery Note Key`,P.`Product ID`,`Product Code`,`Product XHTML Short Description` from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where $order  ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";



	//print $sql;

	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


		switch ($row['Order Transaction Type']) {
		case 'Replacement':
			$notes=_('Replacement');
			break;
		case 'Missing':
			$notes=_('Missing');
			break;
		default:
			$notes='';

		}


		$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
		$data[]=array(

			'code'=>$code
			,'description'=>$row['Product XHTML Short Description']
			,'dn'=>sprintf('<a href="dn.php?id=%d">%s</a>',$row['Delivery Note Key'],$row['Delivery Note ID'])
			,'dispatched'=>number($row['Delivery Note Quantity'])
			,'notes'=>$notes
		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}
function post_transactions() {

	if (isset( $_REQUEST['order_key']) and is_numeric( $_REQUEST['order_key']))
		$order_id=$_REQUEST['order_key'];
	else
		return;




	$where=sprintf(' where  (POT.`Order Key`=%d or  O.`Order Key`=%d )',$order_id,$order_id);
	$where=sprintf(' where  POT.`Order Key`=%d ',$order_id);

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();

	$order=' order by O.`Product Code`';
	$order='';
	$sql="select ``,POT.`Quantity`,`State`,`Operation`,O.`Delivery Note Quantity`,O.`Delivery Note ID`,O.`Delivery Note Key`,P.`Product ID`,O.`Product Code`,`Product XHTML Short Description` from `Order Post Transaction Dimension` POT left  join `Order Transaction Fact` O on (O.`Order Transaction Fact Key`=POT.`Order Post Transaction Fact Key`) left  join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) left join `Product History Dimension` PH on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where $order  ";
	$sql="select POT.`Customer Key`,`Reason`,O.`Invoice Currency Code`,`Credit`,O.`Shipped Quantity`,POT.`Quantity`,`State`,`Operation`,O.`Delivery Note Quantity`,PO.`Delivery Note ID`,PO.`Delivery Note Key`,P.`Product ID`,O.`Product Code`,`Product XHTML Short Description`
	from `Order Post Transaction Dimension` POT
	left join `Order Transaction Fact` O on (O.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`)
		left join `Order Transaction Fact` PO on (PO.`Order Transaction Fact Key`=POT.`Order Post Transaction Fact Key`)

	left join `Product Dimension` P on (P.`Product ID`=O.`Product ID`)  $where $order  ";


	$sql="select POT.`Customer Key`,`Reason`,OTF.`Invoice Currency Code`,`Credit`,OTF.`Shipped Quantity`,POT.`Quantity`,`State`,`Operation`,OTF.`Delivery Note Quantity`,OTF.`Delivery Note ID`,POT.`Delivery Note Key`,P.`Product ID`,OTF.`Product Code`,`Product XHTML Short Description`
	from `Order Post Transaction Dimension` POT
	left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Post Transaction Fact Key`)

	left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  $where $order  ";


	//print $sql;

	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {




		$notes='';

		switch ($row['Operation']) {
		case 'Resend':
			switch ($row['State']) {
			case 'In Process':
				$notes.=sprintf('<a href="new_post_order.php?id=%d">%s</a>',$order_id,_('Item to be resended in process'));
				break;
			case 'In Warehouse':
				$notes.=sprintf('%s (<a href="dn.php?id=%d">%s</a>)',_('In warehouse'),$row['Delivery Note Key'],$row['Delivery Note ID']);


				break;
			case 'Dispatched':
				$notes.=sprintf(',%s <a href="dn.php?id=%d">%s</a>',_('Dispatched'),$row['Delivery Note Key'],$row['Delivery Note ID']);
				break;
			default:
				$notes.='';

			}

			break;
		case 'Refund':
			$notes=_('Refund');
			break;
		case 'Credit':
			//'In Process','In Warehouse','Dispatched','Saved','Applied'
			switch ($row['State']) {
			case 'In Process':
				$notes.=sprintf('<a href="new_post_order.php?id=%d">%s</a>',$order_id,_('Credit in process'));
				break;
			case 'Saved':
				$notes.=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Customer Key'],_('Credit in customer file'));


				break;
			case 'Dispatched':
				$notes.=sprintf(',%s <a href="dn.php?id=%d">%s</a>',_('Dispatched'),$row['Delivery Note Key'],$row['Delivery Note ID']);
				break;
			default:
				$notes.='';

			}

			break;

		default:
			$notes='';
		}


		$notes=preg_replace('/^,/','',$notes);

		/*
		switch ($row['Operation']) {
		case 'Resend':
			$notes=_('Resend');
			break;
		case 'Refund':
			$notes=_('Refund');
			break;
		default:
			$notes='';

		}
		switch ($row['State']) {
		case 'In Process':
			$notes.=sprintf(', <a href="new_post_order.php?id=%d">%s</a>',$order_id,_('In Process'));
			break;
		case 'In Warehouse':
			$notes.=sprintf(',%s <a href="dn.php?id=%d">%s</a>',_('In Warehouse'),$row['Delivery Note Key'],$row['Delivery Note ID']);
			break;
		case 'Dispatched':
			$notes.=sprintf(',%s <a href="dn.php?id=%d">%s</a>',_('Dispatched'),$row['Delivery Note Key'],$row['Delivery Note ID']);
			break;
		default:
			$notes.='';

		}
*/

		$quantity=number($row['Quantity']);

		if ($row['Operation']=='Credit') {
			$quantity.=' ('.money($row['Credit'],$row['Invoice Currency Code']).')';
		}
		$reason=$row['Reason'];

		$operation=$row['Operation'];

		$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
		$data[]=array(

			'code'=>$code
			,'description'=>$row['Product XHTML Short Description']
			,'dn'=>sprintf('<a href="dn.php?id=%d">%s</a>',$row['Delivery Note Key'],$row['Delivery Note ID'])
			,'dispatched'=>number($row['Shipped Quantity'])
			,'quantity'=>$quantity
			,'notes'=>$notes
			,'operation'=>$operation
			,'reason'=>$reason

		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}
function transactions_cancelled() {
	if (isset( $_REQUEST['order_key']) and is_numeric( $_REQUEST['order_key']))
		$order_id=$_REQUEST['order_key'];
	else
		return;




	$where=' where `Order Key`='.$order_id;

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();
	$sql="select * from `Order Transaction Fact` O left join `Product Dimension` P on (P.`Product ID`=O.`Product ID`)  $where   ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";





	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//   $total_charged+=$row['charge'];
		//      $total_discounts+=$ndiscount;
		//      $total_picks+=$row['dispatched'];
		$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
		$data[]=array(

			'code'=>$code,
			'description'=>$row['Product XHTML Short Description'],
			'tariff_code'=>$row['Product Tariff Code'],
			'quantity'=>number($row['Order Quantity']),
			'gross'=>money($row['Order Transaction Gross Amount'],$row['Order Currency Code']),
			'discount'=>money($row['Order Transaction Total Discount Amount'],$row['Order Currency Code']),
			'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_transactions_in_warehouse() {
	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
		$order_id=$_REQUEST['id'];
	else
		$order_id=$_SESSION['state']['order']['id'];




	$where=' where `Order Key`='.$order_id;

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();
	$sql="select * from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where   ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";





	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//   $total_charged+=$row['charge'];
		//      $total_discounts+=$ndiscount;
		//      $total_picks+=$row['dispatched'];
		$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
		$data[]=array(

			'code'=>$code
			,'description'=>$row['Product XHTML Short Description']
			,'tariff_code'=>$row['Product Tariff Code']
			,'quantity'=>number($row['Order Quantity'])
			,'gross'=>money($row['Order Transaction Gross Amount'])
			,'discount'=>money($row['Order Transaction Total Discount Amount'])
			,'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'])
		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}



function list_shortcut_key_search() {
	$conf=$_SESSION['state']['product']['orders'];
	if (isset( $_REQUEST['code'])) {
		$tag=$_REQUEST['code'];
		$mode='code';
	} elseif (isset( $_REQUEST['id'])) {
		$tag=$_REQUEST['id'];
		$mode='id';
	} elseif (isset( $_REQUEST['key'])) {
		$tag=$_REQUEST['key'];
		$mode='key';
	} else {
		$tag=$_SESSION['state']['product']['tag'];
		$mode=$_SESSION['state']['product']['mode'];
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (!is_numeric($start_from))
		$start_from=0;

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



	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_SESSION['state']['product']['orders']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'tag'=>$tag,'mode'=>$mode);
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$where='where true';



	if ($mode=='code')
		$where=$where.sprintf(" and P.`Product Code`=%s ",prepare_mysql($tag));
	elseif ($mode=='pid')
		$where=$where.sprintf(" and PD.`Product ID`=%d ",$tag);
	elseif ($mode=='key')
		$where=$where.sprintf(" and PD.`Product Key`=%d ",$tag);



	$wheref="";
	if (isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])) {
		if ($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer') {
			if ($_REQUEST['f_value']!='')
				$wheref=" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
		}
	}


	$sql="select count(*) as total from `Product Family Dimension` $where $wheref";

	//$sql="select count(DISTINCT `Order Key`) as total from `Order Transaction Fact` OTF  left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)  left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)   $where $wheref";
	//print $sql;
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(DISTINCT `Order Key`) as total from `Order Transaction Fact` OTF left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)   left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)  $where      ";
		$res = mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}

	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);
	if ($total_records>$number_results)
		$rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
	$filter_msg='';


	if ($order=='dispatched')
		$order='`Shipped Quantity`';
	elseif ($order=='order') {
		$order='';
		$order_direction ='';

	}
	else {
		$order='`Delivery Note Date`';

	}


	$sql=sprintf("select `Delivery Note XHTML Orders`,`Customer Name`,CD.`Customer Key`,`Delivery Note Date`,sum(`Shipped Quantity`) as dispatched,sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`+`No Shipped Due Other`) as undispatched  from     `Order Transaction Fact` OTF  left join   `Delivery Note Dimension` DND on (OTF.`Delivery Note Key`=DND.`Delivery Note Key`) left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)   left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)    left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)    %s %s  and OTF.`Delivery Note Key`>0  group by OTF.`Delivery Note Key`  order by  $order $order_direction  limit $start_from,$number_results"
		,$where
		,$wheref
	);
	// print $sql;

	$res=mysql_query($sql);
	$data=array();

	while ($row= mysql_fetch_array($res, MYSQL_ASSOC) ) {
		if ($can_see_customers)
			$customer='<a href="customer.php?id='.$row['Customer Key'].'">'.$row['Customer Name'].'</a>';
		else
			$customer=$myconf['customer_id_prefix'].sprintf("%05d",$row['Customer Key']);



		$data[]=array(
			'order'=>$row['Delivery Note XHTML Orders'],
			'customer_name'=>$customer,
			'date'=> strftime("%e %b %y", strtotime($row['Delivery Note Date'].' +0:00')),
			'dispatched'=>number($row['dispatched']),
			'undispatched'=>number($row['undispatched'])

		);
	}

	$response=array('resultset'=>
		array('state'=>200,

			'data'=>$data,
			'rtext'=>$rtext,
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


function orders_lists($data) {

	global $user;

	$conf=$_SESSION['state']['orders_lists'][$data['block_view']];
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
		$_SESSION['state']['orders_lists']['store']=$store;
	} else
		$store=$_SESSION['state']['orders_lists']['store'];


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state']['customers']['list']['order']=$order;
	$_SESSION['state']['customers']['list']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['list']['nr']=$number_results;
	$_SESSION['state']['customers']['list']['sf']=$start_from;
	$_SESSION['state']['customers']['list']['where']=$awhere;
	$_SESSION['state']['customers']['list']['f_field']=$f_field;
	$_SESSION['state']['customers']['list']['f_value']=$f_value;



	$translate_list_scope=array(
		'orders'=>'Order',
		'invoices'=>'Invoice',
		'dn'=>'Delivery Note',

	);


	$where=' where `List Scope`="'.addslashes($translate_list_scope[$data['block_view']]).'"';




	if (in_array($store,$user->stores)) {
		$where.=sprintf(' and `List Parent Key`=%d  ',$store);

	}

	$wheref='';

	$sql="select count(distinct `List Key`) as total from `List Dimension`  $where  ";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from `List Dimension` $where $wheref ";
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


	$rtext=number($total_records)." ".ngettext('List','Lists',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';




	$filter_msg='';





	$_order=$order;
	$_dir=$order_direction;


	if ($order=='name')
		$order='`List Name`';
	elseif ($order=='creation_date')
		$order='`List Creation Date`';
	elseif ($order=='list_type')
		$order='`List Type`';

	else
		$order='`List Key`';


	$sql="select  CLD.`List key`,CLD.`List Name`,CLD.`List Parent Key`,CLD.`List Creation Date`,CLD.`List Type` from `List Dimension` CLD $where  order by $order $order_direction limit $start_from,$number_results";


	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





		$cusomer_list_name=" <a href='orders_list.php?id=".$data['List key']."'>".$data['List Name'].'</a>';
		switch ($data['List Type']) {
		case 'Static':
			$customer_list_type=_('Static');
			break;
		default:
			$customer_list_type=_('Dynamic');
			break;

		}

		$adata[]=array(


			'list_type'=>$customer_list_type,
			'name'=>$cusomer_list_name,
			'key'=>$data['List key'],
			'creation_date'=>strftime("%c", strtotime($data['List Creation Date']." +00:00")),
			'add_to_email_campaign_action'=>'<span class="state_details" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add List').'</span>',
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

function invoices_lists($data) {

	global $user;

	$conf=$_SESSION['state']['orders_lists'][$data['block_view']];
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
		$_SESSION['state']['orders_lists']['store']=$store;
	} else
		$store=$_SESSION['state']['orders_lists']['store'];


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state']['customers']['list']['order']=$order;
	$_SESSION['state']['customers']['list']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['list']['nr']=$number_results;
	$_SESSION['state']['customers']['list']['sf']=$start_from;
	$_SESSION['state']['customers']['list']['where']=$awhere;
	$_SESSION['state']['customers']['list']['f_field']=$f_field;
	$_SESSION['state']['customers']['list']['f_value']=$f_value;



	$translate_list_scope=array(
		'orders'=>'Order',
		'invoices'=>'Invoice',
		'dn'=>'Delivery Note',

	);


	$where=' where `List Scope`="'.addslashes($translate_list_scope[$data['block_view']]).'"';




	if (in_array($store,$user->stores)) {
		$where.=sprintf(' and `List Parent Key`=%d  ',$store);

	}

	$wheref='';

	$sql="select count(distinct `List Key`) as total from `List Dimension`  $where  ";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from `List Dimension` $where $wheref ";
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


	$rtext=number($total_records)." ".ngettext('List','Lists',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';




	$filter_msg='';





	$_order=$order;
	$_dir=$order_direction;


	if ($order=='name')
		$order='`List Name`';
	elseif ($order=='creation_date')
		$order='`List Creation Date`';
	elseif ($order=='list_type')
		$order='`List Type`';

	else
		$order='`List Key`';


	$sql="select  CLD.`List key`,CLD.`List Name`,CLD.`List Parent Key`,CLD.`List Creation Date`,CLD.`List Type` from `List Dimension` CLD $where  order by $order $order_direction limit $start_from,$number_results";


	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





		$cusomer_list_name=" <a href='invoices_list.php?id=".$data['List key']."'>".$data['List Name'].'</a>';
		switch ($data['List Type']) {
		case 'Static':
			$customer_list_type=_('Static');
			break;
		default:
			$customer_list_type=_('Dynamic');
			break;

		}

		$adata[]=array(


			'list_type'=>$customer_list_type,
			'name'=>$cusomer_list_name,
			'key'=>$data['List key'],
			'creation_date'=>strftime("%c", strtotime($data['List Creation Date']." +00:00")),
			'add_to_email_campaign_action'=>'<span class="state_details" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add List').'</span>',
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

function dn_lists($data) {

	global $user;

	$conf=$_SESSION['state']['orders_lists'][$data['block_view']];
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
		$_SESSION['state']['orders_lists']['store']=$store;
	} else
		$store=$_SESSION['state']['orders_lists']['store'];


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state']['customers']['list']['order']=$order;
	$_SESSION['state']['customers']['list']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['list']['nr']=$number_results;
	$_SESSION['state']['customers']['list']['sf']=$start_from;
	$_SESSION['state']['customers']['list']['where']=$awhere;
	$_SESSION['state']['customers']['list']['f_field']=$f_field;
	$_SESSION['state']['customers']['list']['f_value']=$f_value;



	$translate_list_scope=array(
		'orders'=>'Order',
		'invoices'=>'Invoice',
		'dn'=>'Delivery Note',

	);


	$where=' where `List Scope`="'.addslashes($translate_list_scope[$data['block_view']]).'"';




	if (in_array($store,$user->stores)) {
		$where.=sprintf(' and `List Parent Key`=%d  ',$store);

	}

	$wheref='';

	$sql="select count(distinct `List Key`) as total from `List Dimension`  $where  ";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from `List Dimension` $where $wheref ";
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


	$rtext=number($total_records)." ".ngettext('List','Lists',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';




	$filter_msg='';





	$_order=$order;
	$_dir=$order_direction;


	if ($order=='name')
		$order='`List Name`';
	elseif ($order=='creation_date')
		$order='`List Creation Date`';
	elseif ($order=='list_type')
		$order='`List Type`';

	else
		$order='`List Key`';


	$sql="select  CLD.`List key`,CLD.`List Name`,CLD.`List Parent Key`,CLD.`List Creation Date`,CLD.`List Type` from `List Dimension` CLD $where  order by $order $order_direction limit $start_from,$number_results";


	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





		$cusomer_list_name=" <a href='dn_list.php?id=".$data['List key']."'>".$data['List Name'].'</a>';
		switch ($data['List Type']) {
		case 'Static':
			$customer_list_type=_('Static');
			break;
		default:
			$customer_list_type=_('Dynamic');
			break;

		}

		$adata[]=array(


			'list_type'=>$customer_list_type,
			'name'=>$cusomer_list_name,
			'key'=>$data['List key'],
			'creation_date'=>strftime("%c", strtotime($data['List Creation Date']." +00:00")),
			'add_to_email_campaign_action'=>'<span class="state_details" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add List').'</span>',
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


function invoice_categories() {
	global $corporate_currency;

	$conf=$_SESSION['state']['invoice_categories']['subcategories'];
	$conf2=$_SESSION['state']['invoice_categories'];


	if (isset($_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];

	}else {
		exit();
	}

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

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




	$_SESSION['state']['invoice_categories']['subcategories']['order']=$order;
	$_SESSION['state']['invoice_categories']['subcategories']['order_dir']=$order_direction;
	$_SESSION['state']['invoice_categories']['subcategories']['nr']=$number_results;
	$_SESSION['state']['invoice_categories']['subcategories']['sf']=$start_from;
	$_SESSION['state']['invoice_categories']['subcategories']['f_field']=$f_field;
	$_SESSION['state']['invoice_categories']['subcategories']['f_value']=$f_value;


	// print_r($_SESSION['tables']['families_list']);

	//  print_r($_SESSION['tables']['families_list']);




	$where=sprintf("where `Category Subject`='Invoice' and  `Category Parent Key`=%d",$parent_key);
	//  $where=sprintf("where `Category Subject`='Product'  ");

	//  if ($stores_mode=='grouped')
	//     $group=' group by S.`Category Key`';
	// else
	$group='';

	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";




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


	$rtext=number($total_records)." ".ngettext('category','categories',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {

		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {

		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with name like')." <b>*".$f_value."*</b>";
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




	$sql="select * from `Category Dimension` C left join `Invoice Category Dimension` P on (P.`Invoice Category Key`=C.`Category Key`) $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();


	//print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



		$name=sprintf('<a href="invoice_category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Code']);


		$adata[]=array(
			'id'=>$row['Category Key'],
			'code'=>$name,
			'label'=>$row['Category Label'],
			'subjects'=>number($row['Category Number Subjects']),
			//'sold'=>number($sold,0),
			'sales'=>money($row['Invoice Category Total Acc Invoiced Amount'],$corporate_currency)



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

function number_invoices_in_interval($data) {


	$parent_key=$data['parent_key'];
	$parent=$data['parent'];
	$from=$data['from'];
	$to=$data['to'];

	$where_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Invoice Date`');
	$where_interval=$where_interval['mysql'];

	$elements_numbers=array(
		'payment'=>array('Yes'=>0, 'No'=>0, 'Partially'=>0),
		'type'=>array('Invoice'=>0,'Refund'=>0)
	);

	if ($parent=='store') {
		$sql=sprintf("select count(*) as number,`Invoice Type` as element from `Invoice Dimension`  where `Invoice Store Key`=%d %s group by `Invoice Type` ",
			$parent_key,$where_interval);
		$sql2=sprintf("select count(*) as number,`Invoice Paid` as element from `Invoice Dimension`  where `Invoice Store Key`=%d %s group by `Invoice Paid` ",
			$parent_key,$where_interval);
	}elseif ($parent=='category') {
		$sql=sprintf("select count(*) as number,`Invoice Type` as element from `Invoice Dimension` left join `Category Bridge` on (`Invoice Key`=`Subject Key` and `Subject`='Invoice') where `Category Key`=%d %s group by `Invoice Type` ",
			$parent_key,$where_interval);
		$sql2=sprintf("select count(*) as number,`Invoice Paid` as element from `Invoice Dimension`  left join `Category Bridge` on (`Invoice Key`=`Subject Key` and `Subject`='Invoice') where `Category Key`=%d  %s group by `Invoice Paid` ",
			$parent_key,$where_interval);

	}



	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$elements_numbers['type'][$row['element']]=number($row['number']);
	}


	$res=mysql_query($sql2);
	while ($row=mysql_fetch_assoc($res)) {

		$elements_numbers['payment'][$row['element']]=number($row['number']);
	}

	$response= array('state'=>200,'elements_numbers'=>$elements_numbers);
	echo json_encode($response);

}

function number_orders_in_interval($data) {
	$parent_key=$data['parent_key'];

	$from=$data['from'];
	$to=$data['to'];

	$where_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Order Date`');
	$where_interval=$where_interval['mysql'];

	$elements_numbers=array(
		'dispatch'=>array('InProcessCustomer'=>0,'InProcess'=>0,'Warehouse'=>0,'Dispatched'=>0,'Cancelled'=>0,'Suspended'=>0),
		'source'=>array('Internet'=>0,'Call'=>0,'Store'=>0,'Other'=>0,'Email'=>0,'Fax'=>0),
		'payment'=>array('Paid'=>0,'PartiallyPaid'=>0,'Unknown'=>0,'WaitingPayment'=>0,'NA'=>0),
		'type'=>array('Order'=>0,'Sample'=>0,'Donation'=>0,'Other'=>0)
	);

	$sql=sprintf("select count(*) as number,`Order Main Source Type` as element from `Order Dimension`  where `Order Store Key`=%d %s group by `Order Main Source Type` ",
		$parent_key,$where_interval);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$elements_numbers['source'][$row['element']]=number($row['number']);
	}

	$sql=sprintf("select count(*) as number,`Order Main Source Type` as element from `Order Dimension`  where `Order Store Key`=%d %s group by `Order Main Source Type` ",
		$parent_key,$where_interval);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$elements_numbers['source'][$row['element']]=number($row['number']);
	}

	$sql=sprintf("select count(*) as number,`Order Type` as element from `Order Dimension`  where `Order Store Key`=%d %s group by `Order Type` ",
		$parent_key,$where_interval);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$elements_numbers['type'][$row['element']]=number($row['number']);
	}


	$sql=sprintf("select count(*) as number,`Order Current Dispatch State` as element from `Order Dimension`  where `Order Store Key`=%d %s group by `Order Current Dispatch State` ",
		$parent_key,$where_interval);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		if ($row['element']!='') {

			if ($row['element']=='In Process' or $row['element']=='Submitted by Customer' ) {
				$_element='InProcess';
			}elseif ($row['element']=='Ready to Pick'  or $row['element']=='Picking & Packing'  or $row['element']=='Ready to Ship'   or $row['element']=='Packing' or $row['element']=='Packed'  or $row['element']=='Packed Done') {
				$_element='Warehouse';
			}else {
				$_element=$row['element'];
			}
			$elements_numbers['dispatch'][$_element]+=$row['number'];
		}
	}

	foreach ( $elements_numbers['dispatch'] as $key=>$value) {
		$elements_numbers['dispatch'][$key]=number($value);
	}

	$sql=sprintf("select count(*) as number,`Order Current Payment State` as element from `Order Dimension`  where `Order Store Key`=%d %s group by `Order Current Payment State` ",
		$parent_key,$where_interval);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		if ($row['element']=='Waiting Payment' ) {
			$_element='WaitingPayment';
		}elseif ($row['element']=='Partially Paid' ) {
			$_element='PartiallyPaid';
		}elseif ($row['element']=='No Applicable' ) {
			$_element='NA';
		}else {
			$_element=$row['element'];
		}
		$elements_numbers['payment'][$_element]=number($row['number']);
	}

	//print_r($elements_numbers);
	$response= array('state'=>200,'elements_numbers'=>$elements_numbers);
	echo json_encode($response);

}

function number_delivery_notes_in_interval($data) {
	global $user;

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];

	$from=$data['from'];
	$to=$data['to'];
	$awhere='';
	$elements_type='';
	$f_field ='';
	$f_value='';


	include "splinters/dn_prepare_list.php";


	$elements_numbers=array(
		'dispatch'=>array('Ready'=>0,'Picking'=>0,'Packing'=>0,'Done'=>0,'Send'=>0,'Returned'=>0),
		'type'=>array('Order'=>0,'Sample'=>0,'Donation'=>0,'Replacements'=>0,'Shortages'=>0)
	);


	//print "$table $where";

	$sql=sprintf("select count(*) as number,`Delivery Note Type` as element from %s %s group by `Delivery Note Type` ",
		$table,$where

	);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		if ($row['element']=='Replacement & Shortages' ) {
			$_element='Replacements';
		}if ($row['element']=='Replacement' ) {
			$_element='Replacements';
		}else {
			$_element=$row['element'];
		}
		if ($_element!='')
			$elements_numbers['type'][$_element]+=$row['number'];
	}

	foreach ($elements_numbers['type'] as $key=>$value) {
		$elements_numbers['type'][$key]=number($value);
	}



	$sql=sprintf("select count(*) as number,`Delivery Note State` as element  from %s %s group by `Delivery Note State` ",
		$table,$where);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		if ($row['element']=='Ready to be Picked' ) {
			$_element='Ready';
		}elseif ($row['element']=='Picking'  or $row['element']=='Picking & Packing' or $row['element']=='Picked'  or $row['element']=='Picker Assigned' or $row['element']=='Picker & Packer Assigned' ) {
			$_element='Picking';
		}elseif ($row['element']=='Packing'  or $row['element']=='Packed' or $row['element']=='Packer Assigned' or $row['element']=='Packed Done' ) {
			$_element='Packing';
		}elseif ($row['element']=='Approved' ) {
			$_element='Done';
		}elseif ($row['element']=='Dispatched'  ) {
			$_element='Send';
		}elseif ($row['element']=='Cancelled'  or $row['element']=='Cancelled to Restock' ) {
			$_element='Returned';
		}else {
			continue;
		}

		$elements_numbers['dispatch'][$_element]+=$row['number'];
	}

	foreach ($elements_numbers['dispatch'] as $key=>$value) {
		$elements_numbers['dispatch'][$key]=number($value);
	}



	//print_r($elements_numbers);
	$response= array('state'=>200,'elements_numbers'=>$elements_numbers);
	echo json_encode($response);

}




function transactions_in_warehouse() {
	if (isset( $_REQUEST['order_key']) and is_numeric( $_REQUEST['order_key'])) {
		$order_id=$_REQUEST['order_key'];

	} else {
		return;
	}

	if (isset( $_REQUEST['store_key']) and is_numeric( $_REQUEST['store_key'])) {
		$store_key=$_REQUEST['store_key'];
		$_SESSION['state']['order']['store_key']=$store_key;
	} else
		$store_key=$_SESSION['state']['order']['store_key'];


	$conf=$_SESSION['state']['order']['products'];


	//print_r($conf);


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




	$display='ordered_products';


	if (isset( $_REQUEST['sf'])) {
		$start_from=$_REQUEST['sf'];
		$_SESSION['state']['order'][$display]['sf']=$start_from;

	} else
		$start_from=$_SESSION['state']['order'][$display]['sf'];



	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['order'][$display]['nr']=$number_results;
	}      else
		$number_results=$_SESSION['state']['order'][$display]['nr'];





	$_SESSION['state']['order']['products']['order']=$order;
	$_SESSION['state']['order']['products']['order_dir']=$order_direction;

	$_SESSION['state']['order']['products']['f_field']=$f_field;
	$_SESSION['state']['order']['products']['f_value']=$f_value;
	$_SESSION['state']['order']['products']['display']=$display;



	$store=new Store($store_key);


	$table='  `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`)  ';
	$where=sprintf(' where `Order Quantity`>0 and `Order Key`=%d',$order_id);
	$sql_qty='`No Shipped Due No Authorized`,`No Shipped Due Not Found`,`No Shipped Due Other`,`No Shipped Due Out of Stock`,`Picking Factor`,`Packing Factor`,`Order Transaction Fact Key`, `Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`,`Current Dispatching State`';





	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  P.`Product Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  P.`Product Name` like '%".addslashes($f_value)."%'";

	$sql="select count(*) as total from $table   $where $wheref   ";

	// print_r($conf);exit;
	//  print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from $table  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Product Code File As`';
	if ($order=='stock')
		$order='`Product Availability`';
	if ($order=='code')
		$order='`Product Code File As`';
	else if ($order=='name')
			$order='`Product Name`';
		else if ($order=='available_for')
				$order='`Product Available Days Forecast`';
			elseif ($order=='family') {
				$order='`Product Family`Code';
			}
		elseif ($order=='dept') {
			$order='`Product Main Department Code`';
		}
	elseif ($order=='expcode') {
		$order='`Product Tariff Code`';
	}
	elseif ($order=='parts') {
		$order='`Product XHTML Parts`';
	}
	elseif ($order=='supplied') {
		$order='`Product XHTML Supplied By`';
	}
	elseif ($order=='gmroi') {
		$order='`Product GMROI`';
	}
	elseif ($order=='state') {
		$order='`Product Sales State`';
	}
	elseif ($order=='web') {
		$order='`Product Web Configuration`';
	}



	$sql="select `Delivery Note Quantity`,`Picked Quantity`,`Product Stage`, `Product Availability`,`Product Record Type`,P.`Product ID`,P.`Product Code`,`Product XHTML Short Description`,`Product Price`,`Product Units Per Case`,`Product Record Type`,`Product Web Configuration`,`Product Family Name`,`Product Main Department Name`,`Product Tariff Code`,`Product XHTML Parts`,`Product GMROI`,`Product XHTML Parts`,`Product XHTML Supplied By`,`Product Stock Value`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";


	$res = mysql_query($sql);

	$adata=array();

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		if (is_numeric($row['Product Availability']))
			$stock=number($row['Product Availability']);
		else
			$stock='?';
		$type=$row['Product Record Type'];

		if ($row['Product Stage']=='In Process')
			$type.='<span style="color:red">*</span>';

		switch ($row['Product Web Configuration']) {
		case('Online Force Out of Stock'):
			$web_state=_('Out of Stock');
			break;
		case('Online Auto'):
			$web_state=_('Auto');
			break;
		case('Unknown'):
			$web_state=_('Unknown');
		case('Offline'):
			$web_state=_('Offline');
			break;
		case('Online Force Hide'):
			$web_state=_('Hide');
			break;
		case('Online Force For Sale'):
			$web_state=_('Sale');
			break;
		default:
			$web_state=$row['Product Web Configuration'];
		}


		$deal_info='';
		if ($row['Deal Info']!='') {
			$deal_info=' <span class="deal_info">'.$row['Deal Info'].'</span>';
		}

		$not_to_disptach=$row['No Shipped Due Out of Stock']+$row['No Shipped Due No Authorized']+$row['No Shipped Due Not Found']+$row['No Shipped Due Other'];
		//'In Process by Customer','Submitted by Customer','In Process','Ready to Pick','Picking','Ready to Pack','Ready to Ship','Dispatched','Unknown','Packing','Cancelled','No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Suspended'
		switch ($row['Current Dispatching State']) {
		case 'In Process by Customer':
			$dispatching_status=_('In Process by Customer');
			break;
		case 'Submitted by Customer':
			$dispatching_status=_('Submitted by Customer');
			break;
		case 'In Process':
			$dispatching_status=_('In Process');
			break;
		case 'Ready to Pick':
			$dispatching_status=_('Ready to Pick').' ['.$row['Picked Quantity'].'/'.($row['Order Quantity']-$not_to_disptach).']';
			break;
		case 'Picking':
			$dispatching_status=_('Picking').' ['.$row['Picked Quantity'].'/'.($row['Order Quantity']-$not_to_disptach).']';
			break;
		case 'Ready to Pack':
			$to_pick=$row['Order Quantity']-$not_to_disptach;
			$dispatching_status=_('Ready to Pack').' ['.($row['Delivery Note Quantity']).'/'.($to_pick!=$row['Picked Quantity']?'('.$row['Picked Quantity'].')'.$to_pick:$to_pick).']';
			break;
		case 'Ready to Ship':
			$dispatching_status=_('Ready to Ship');
			break;
		case 'Dispatched':
			$dispatching_status=_('Dispatched');
			break;
		case 'Unknown':
			$dispatching_status=_('Unknown');
			break;
		case 'Packing':
			$to_pick=$row['Order Quantity']-$not_to_disptach;
			$dispatching_status=_('Packing').' ['.($row['Delivery Note Quantity']).'/'.($to_pick!=$row['Picked Quantity']?'('.$row['Picked Quantity'].')'.$to_pick:$to_pick).']';

			break;

		case 'Cancelled':
			$dispatching_status=_('Cancelled');
			break;
		case 'No Picked Due Out of Stock':
			$dispatching_status=_('No Picked Due Out of Stock');
			break;
		case 'No Picked Due No Authorised':
			$dispatching_status=_('No Picked Due No Authorised');
			break;
		case 'No Picked Due Not Found':
			$dispatching_status=_('No Picked Due Not Found');
			break;
		case 'No Picked Due Other':
			$dispatching_status=_('No Picked Due Other');
			break;
		case 'Suspended':
			$dispatching_status=_('Suspended');
			break;
		default:
			$dispatching_status=$row['Current Dispatching State'];
			break;
		}

		$no_charge_quantity=0;
		$quantity=number($row['Order Quantity']);
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


		$code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
		$adata[]=array(
			'pid'=>$row['Product ID'],
			'otf_key'=>$row['Order Transaction Fact Key'],//($display=='ordered_products'?$row['Order Transaction Fact Key']:0),
			'code'=>$code,
			'description'=>$row['Product XHTML Short Description'].' <span style="color:#777">['.$stock.']</span> '.$deal_info,
			'shortname'=>number($row['Product Units Per Case']).'x @'.money($row['Product Price']/$row['Product Units Per Case'],$store->data['Store Currency Code']).' '._('ea'),
			'family'=>$row['Product Family Name'],
			'dept'=>$row['Product Main Department Name'],
			'expcode'=>$row['Product Tariff Code'],
			'parts'=>$row['Product XHTML Parts'],
			'supplied'=>$row['Product XHTML Supplied By'],
			'gmroi'=>$row['Product GMROI'],
			//    'stock_value'=>money($row['Product Stock Value']),
			'stock'=>$stock,
			'quantity'=>$quantity,
			'state'=>$type,
			'web'=>$web_state,
			//    'image'=>$row['Product Main Image'],
			'type'=>'item',

			//'change'=>'<span onClick="quick_change("+",'.$row['Product ID'].')" class="quick_add">+</span> <span class="quick_add" onClick="quick_change("-",'.$row['Product ID'].')" >-</span>',
			'to_charge'=>money($charge_quantity_amount,$store->data['Store Currency Code']),

			'dispatching_status'=>$dispatching_status,
			'discount_percentage'=>($row['Order Transaction Total Discount Amount']>0?percentage($row['Order Transaction Total Discount Amount'],$row['Order Transaction Gross Amount'],$fixed=1,$error_txt='NA',$psign=''):'')




		);


	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records-$filtered,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);


}

function get_order_details($data) {

	$order=new Order($data['order_key']);

	$delivery_notes_html='';
	$delivery_notes=$this->get_delivery_notes_objects();
	foreach ($delivery_notes as $delivery_note) {
		$delivery_notes_html='<div class="delivery_note" style="border:1px solid #ccc;width:400px;padding:10px">';
		$delivery_notes_html='<h2>'.$delivery_notes->data['Delivey Note Public id'].'</h2>';
		$delivery_notes_html='</div>';

	}



}


?>
