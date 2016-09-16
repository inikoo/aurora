<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 October 2015 at 11:53:01 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';





if (!isset($_REQUEST['tab'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tab=$_REQUEST['tab'];

switch ($tab) {
case 'suppliers':
case 'agent.suppliers':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_suppliers_element_numbers($db, $data['parameters'], $user);
	break;
case 'suppliers.deliveries':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_supplier_deliveries_element_numbers($db, $data['parameters'], $user);
	break;
case 'suppliers.orders':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_supplier_orders_elements($db, $data['parameters'], $user);
	break;
case 'website.nodes':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_webnodes_element_numbers($db, $data['parameters'], $user);
	break;
case 'campaigns':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_campaigns_element_numbers($db, $data['parameters'], $user);
	break;
case 'deals':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_deals_element_numbers($db, $data['parameters'], $user);
	break;
case 'inventory.parts':
case 'category.parts':
case 'category.all_parts':
case 'material.parts':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));

	if ($tab=='category.all_parts') {
		$data['parameters']['parent']='account';
		$data['parameters']['parent_key']=1;
	}

	get_parts_elements($db, $data['parameters'], $user);
	break;
case 'warehouse.locations':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_warehouse_locations_elements($db, $data['parameters'], $user);
	break;
case 'customers':
case 'website.favourites.customers':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_customers_element_numbers($db, $data['parameters'], $user);
	break;
case 'store.products':
case 'category.products':
case 'part.products':

	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_products_element_numbers($db, $data['parameters'], $user);
	break;
case 'orders':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_orders_element_numbers($db, $data['parameters'], $user);
	break;
case 'invoices':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_invoices_element_numbers($db, $data['parameters'], $user);
	break;
case 'customer.history':
case 'supplier_part.history':
case 'agent.history':
case 'location.history':
case 'deal.history':
case 'campaign.history':
case 'supplier.order.history':

	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_history_elements($db, $data['parameters'], $user);
	break;
case 'inventory.barcodes':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_barcodes_elements($db, $data['parameters'], $user);
	break;
case 'supplier.supplier_parts':
case 'agent.supplier_parts':
case 'supplier.order.supplier_parts':

	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_supplier_parts_elements($db, $data['parameters'], $user);
	break;
case 'supplier.orders':
case 'agent.orders':

	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_supplier_orders_elements($db, $data['parameters'], $user);
	break;

case 'part.stock.transactions':
case 'inventory.stock.transactions':
case 'location.stock.transactions':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_stock_transactions_elements($db, $data['parameters'], $user);
	break;
case 'category_root.all_parts':

	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_category_root_all_parts_elements($db, $data['parameters'], $user);
	break;
case 'ec_sales_list':

	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_ec_sales_list_elements($db, $data['parameters'], $user);
	break;

default:


	$response=array('state'=>405, 'resp'=>'Tab not found '.$tab);
	echo json_encode($response);
	exit;
	break;
}


function get_webnodes_element_numbers($db, $data, $user) {



	$parent_key=$data['parent_key'];

	$elements_numbers=array(
		'status'=>array('Online'=>0, 'Offline'=>0, ),

	);


	switch ($data['parent']) {
	case 'website':
		$where=sprintf(' where `Website Node Website Key`=%d  ', $data['parent_key']);
		break;
	case 'node':
		$where=sprintf(' where `	Website Node Parent Key`=%d  ', $data['parent_key']);
		break;
	default:
		$response=array('state'=>405, 'resp'=>'customer parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}





	$sql=sprintf("select count(*) as number,`Website Node Status` as element from `Website Node Dimension` D $where  group by `Website Node Status` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['status'][$row['element']]=number($row['number']);

	}




	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_deals_element_numbers($db, $data, $user) {



	$parent_key=$data['parent_key'];

	$elements_numbers=array(
		'status'=>array('Active'=>0, 'Waiting'=>0, 'Suspended'=>0, 'Finish'=>0),
		'trigger'=>array('Order'=>0, 'Product_Category'=>0, 'Product'=>0, 'Customer'=>0, 'Customer_Cateogory'=>0, 'Customer_List'=>0),

	);


	switch ($data['parent']) {
	case 'store':
		$where=sprintf(' where `Deal Store Key`=%d  ', $data['parent_key']);
		break;
	case 'campaign':
		$where=sprintf(' where `Deal Campaign Key`=%d  ', $data['parent_key']);
		break;
	default:
		$response=array('state'=>405, 'resp'=>'customer parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}





	$sql=sprintf("select count(*) as number,`Deal Status` as element from `Deal Dimension` D $where  group by `Deal Status` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['status'][$row['element']]=number($row['number']);

	}

	$sql=sprintf("select count(*) as number,`Deal Trigger` as element from `Deal Dimension` D $where  group by `Deal Trigger` ");

	foreach ($db->query($sql) as $row) {

		$elements_numbers['trigger'][preg_replace('/\s/', '_', $row['element'])]=number($row['number']);

	}





	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_campaigns_element_numbers($db, $data, $user) {



	$parent_key=$data['parent_key'];

	$elements_numbers=array(
		'status'=>array('Active'=>0, 'Waiting'=>0, 'Suspended'=>0, 'Finish'=>0),

	);


	switch ($data['parent']) {
	case 'store':
		$where=sprintf(' where `Deal Campaign Store Key`=%d  ', $data['parent_key']);
		break;

	default:
		$response=array('state'=>405, 'resp'=>'customer parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}





	$sql=sprintf("select count(*) as number,`Deal Campaign Status` as element from `Deal Campaign Dimension` D $where  group by `Deal Campaign Status` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['status'][$row['element']]=number($row['number']);

	}

	$sql=sprintf("select count(*) as number,`Deal Trigger` as element from `Deal Dimension` D $where  group by `Deal Trigger` ");

	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_stock_transactions_elements($db, $data, $user) {



	$parent_key=$data['parent_key'];
	$elements_numbers=array(
		'stock_status'=>array('OIP'=>0, 'In'=>0, 'Move'=>0, 'Out'=>0, 'Audit'=>0, 'NoDispatched'=>0),

	);


	$table='`Inventory Transaction Fact`  ITF  ';
	switch ($data['parent']) {
	case 'part':
		$where=sprintf("where `Inventory Transaction Record Type`='Movement' and `Part SKU`=%d", $data['parent_key']);
		break;
	case 'account':
		$where=sprintf("where `Inventory Transaction Record Type`='Movement' ");
		break;
	case 'location':
		$where=sprintf("where `Inventory Transaction Record Type`='Movement' and `Location Key`=%d", $data['parent_key']);
		break;
	default:
		$response=array('state'=>405, 'resp'=>'parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}



	$sql=sprintf("select count(*) as number,`Inventory Transaction Section` as element from $table $where  group by `Inventory Transaction Section` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['stock_status'][preg_replace('/\s/', '', $row['element'])]=number($row['number']);

	}



	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_parts_elements($db, $data, $user) {



	$parent_key=$data['parent_key'];
	$elements_numbers=array(
		'stock_status'=>array('Surplus'=>0, 'Optimal'=>0, 'Low'=>0, 'Critical'=>0, 'Out_Of_Stock'=>0, 'Error'=>0),

	);




	$table='`Part Dimension`  P  ';
	switch ($data['parent']) {
	case 'account':
		$where="where `Part Status`='In Use'";
		break;
	case 'category':
		$where=sprintf(" where `Subject`='Part' and  `Category Key`=%d  and `Part Status`='In Use' ", $data['parent_key']);
		$table=' `Category Bridge` left join  `Part Dimension` P on (`Subject Key`=`Part SKU`) ';
		break;
	case 'material':
		$where=sprintf(" where `Material Key`=%d", $data['parent_key']);
		$table=' `Part Material Bridge` B left join  `Part Dimension` P on (B.`Part SKU`=P.`Part SKU`) ';
		break;
	default:
		$response=array('state'=>405, 'resp'=>'part parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}



	$sql=sprintf("select count(*) as number,`Part Stock Status` as element from $table $where  group by `Part Stock Status` ");

	foreach ($db->query($sql) as $row) {

		$elements_numbers['stock_status'][preg_replace('/\s/', '', $row['element'])]=number($row['number']);

	}



	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_supplier_parts_elements($db, $data, $user) {



	$parent_key=$data['parent_key'];

	$elements_numbers=array(
		'status'=>array('Available'=>0, 'NoAvailable'=>0, 'Discontinued'=>0),
		'part_status'=>array('InUse'=>0, 'NotInUse'=>0),

	);


	$table='`Supplier Part Dimension`  SP left join `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`) ';
	switch ($data['parent']) {
	case 'supplier':
		$where=sprintf(' where `Supplier Part Supplier Key`=%d  ', $data['parent_key']);
		break;
	case 'agent':

		$where=sprintf(" where  `Agent Supplier Agent Key`=%d", $data['parent_key']);
		$table.=' left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)';

		break;
	case 'purchase_order':

		$purchase_order=get_object('PurchaseOrder', $data['parent_key']);

		if ($purchase_order->get('Purchase Order Parent')=='Supplier') {

			$where=sprintf(" where  `Supplier Part Supplier Key`=%d", $purchase_order->get('Purchase Order Parent Key'));


		}else {


			$where=sprintf("  where  `Agent Supplier Agent Key`=%d", $purchase_order->get('Purchase Order Parent Key'));
			$table.=' left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)';




		}

		break;
	default:
		$response=array('state'=>405, 'resp'=>'product parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}



	$sql=sprintf("select count(*) as number,`Part Status` as element from $table $where  group by `Part Status` ");


	foreach ($db->query($sql) as $row) {

		$elements_numbers['part_status'][preg_replace('/\s/', '', $row['element'])]=number($row['number']);

	}

	$sql=sprintf("select count(*) as number,`Supplier Part Status` as element from $table $where  group by `Supplier Part Status` ");

	foreach ($db->query($sql) as $row) {

		$elements_numbers['status'][$row['element']]=number($row['number']);

	}



	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_warehouse_locations_elements($db, $data, $user) {



	$parent_key=$data['parent_key'];

	$elements_numbers=array(
		'flags'=>array('Blue'=>0, 'Green'=>0, 'Orange'=>0, 'Pink'=>0, 'Purple'=>0, 'Red'=>0, 'Yellow'=>0),

	);


	$table='`Location Dimension`  ';
	switch ($data['parent']) {
	case 'warehouse':
		$where=sprintf(' where `Location Warehouse Key`=%d  ', $data['parent_key']);
		break;

		break;
	default:
		$response=array('state'=>405, 'resp'=>'product parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}



	$sql=sprintf("select count(*) as number,`Warehouse Flag` as element from $table $where  group by `Warehouse Flag` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['flags'][preg_replace('/\s/', '', $row['element'])]=number($row['number']);

	}




	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_products_element_numbers($db, $data, $user) {


	$parent_key=$data['parent_key'];

	$elements_numbers=array(
		'status'=>array('InProcess'=>0, 'Active'=>0, 'Suspended'=>0, 'Discontinued'=>0, 'Discontinuing'=>0),

	);


	$table='`Product Dimension`  P';
	switch ($data['parent']) {
	case 'store':
		$where=sprintf(' where `Product Store Key`=%d  ', $data['parent_key']);
		break;
	case 'part':
		$table='`Product Dimension`  P left join `Product Part Bridge` B on (B.`Product Part Product ID`=P.`Product ID`)';

		$where=sprintf(' where `Product Part Part SKU`=%d  ', $data['parent_key']);
		break;
	case 'account':
		$where=sprintf(" where `Product Store Key` in (%s) ", join(',', $user->stores));

		break;
	case 'category':


		$where=sprintf(" where `Subject`='Product' and  `Category Key`=%d", $data['parent_key']);
		$table=' `Category Bridge` left join  `Product Dimension` P on (`Subject Key`=`Product ID`) ';


		break;
	case 'list':
		$tab='customers.list';
		break;
	case 'favourites':
		$where=sprintf(' where C.`Customer Key` in (select DISTINCT F.`Customer Key` from `Customer Favorite Product Bridge` F where `Site Key`=%d )', $data['parent_key']);
		break;


	default:
		$response=array('state'=>405, 'resp'=>'product parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}





	$sql=sprintf("select count(*) as number,`Product Status` as element from $table $where  group by `Product Status` ");

	foreach ($db->query($sql) as $row) {

		$elements_numbers['status'][$row['element']]=number($row['number']);

	}



	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_customers_element_numbers($db, $data) {

	global $user;

	$parent_key=$data['parent_key'];

	$elements_numbers=array(
		'orders'=>array('Yes'=>0, 'No'=>0),
		'activity'=>array('Active'=>0, 'Losing'=>0, 'Lost'=>0),
		'type'=>array('Normal'=>0, 'VIP'=>0, 'Partner'=>0, 'Staff'=>0),
		'location'=>array('Domestic'=>0, 'Export'=>0)
	);

	$table='`Customer Dimension`  C';

	switch ($data['parent']) {
	case 'store':
		$where=sprintf(' where `Customer Store Key`=%d  ', $data['parent_key']);
		break;
	case 'category':
		$tab='customer.categories';
		break;
	case 'list':
		$tab='customers.list';
		break;
	case 'campaign':
		$table='`Order Dimension` O  left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`) ';
		$where=sprintf(' where `Deal Campaign Key`=%d', $data['parent_key']);
		break;
	case 'deal':
		$table='`Order Dimension` O  left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`) ';
		$where=sprintf(' where `Deal Key`=%d', $data['parent_key']);
		break;
	case 'favourites':
		$where=sprintf(' where C.`Customer Key` in (select DISTINCT F.`Customer Key` from `Customer Favorite Product Bridge` F where `Site Key`=%d )', $data['parent_key']);
		break;
	default:
		$response=array('state'=>405, 'resp'=>'customer parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}





	$sql=sprintf("select count(Distinct C.`Customer Key`) as number,`Customer With Orders` as element from $table $where  group by `Customer With Orders` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['orders'][$row['element']]=number($row['number']);

	}


	$sql=sprintf("select count(Distinct C.`Customer Key`) as number,`Customer Type by Activity` as element from $table $where group by `Customer Type by Activity` ");

	foreach ($db->query($sql) as $row) {

		$elements_numbers['activity'][$row['element']]=number($row['number']);

	}

	$sql=sprintf("select count(Distinct C.`Customer Key`) as number,`Customer Level Type` as element from $table $where group by `Customer Level Type` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['type'][$row['element']]=number($row['number']);

	}


	$sql=sprintf("select count(Distinct C.`Customer Key`) as number,`Customer Location Type` as element from $table $where group by `Customer Location Type` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['location'][$row['element']]=number($row['number']);

	}





	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_suppliers_element_numbers($db, $data) {

	global $user;

	$parent_key=$data['parent_key'];

	$elements_numbers=array(
		'type'=>array('Free'=>0, 'Agent'=>0, 'Archived'=>0),
	);

	$table='`Supplier Dimension` S';

	switch ($data['parent']) {
	case 'account':
		$where=sprintf(' where true ');
		break;
	case 'agent':
		$where=sprintf(" where `Agent Supplier Agent Key`=%d", $parent_key);
		$table=' `Agent Supplier Bridge` B left join  `Supplier Dimension` S on (`Agent Supplier Supplier Key`=`Supplier Key`) ';

		break;
	default:
		$response=array('state'=>405, 'resp'=>'parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}




	$sql=sprintf("select count(*) as number,`Supplier Type` as element from $table $where group by `Supplier Type` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['type'][$row['element']]=number($row['number']);

	}





	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_history_elements($db, $data) {




	$elements_numbers=array(
		'type'=>array('Changes'=>0, 'Assign'=>0, 'Notes'=>0, 'Orders'=>0, 'Changes'=>0, 'Attachments'=>0, 'WebLog'=>0, 'Emails'=>0)
	);
	if ($data['parent']=='category')
		$sql=sprintf("select count(*) as num ,`Type` from  `%s Category History Bridge` where  `Category Key`=%d group by  `Type`",
			$data['subject'],
			$data['parent_key']);
	elseif ($data['parent']=='warehouse')
		$sql=sprintf("select count(*) as num ,`Type` from  `%s Category History Bridge` where  `Warehouse Key`=%d group by  `Type`",
			$data['subject'],
			$data['parent_key']);
	elseif ($data['parent']=='customer')
		$sql=sprintf("select count(*) as num ,`Type` from  `Customer History Bridge` where  `Customer Key`=%d group by  `Type`",
			$data['parent_key']);
	elseif ($data['parent']=='location')
		$sql=sprintf("select count(*) as num ,`Type` from  `Location History Bridge` where  `Location Key`=%d group by  `Type`",
			$data['parent_key']);
	elseif ($data['parent']=='supplier_part')
		$sql=sprintf("select count(*) as num ,`Type` from  `Supplier Part History Bridge` where  `Supplier Part Key`=%d group by  `Type`",
			$data['parent_key']);
	elseif ($data['parent']=='agent')
		$sql=sprintf("select count(*) as num ,`Type` from  `Agent History Bridge` where  `Agent Key`=%d group by  `Type`",
			$data['parent_key']);
	elseif ($data['parent']=='store')
		$sql=sprintf("select count(*) as num ,`Type` from  `%s Category History Bridge` where  `Store Key`=%d group by  `Type`",
			$data['subject'],
			$data['parent_key']);
	elseif ($data['parent']=='deal')
		$sql=sprintf("select count(*) as num ,`Type` from  `Deal History Bridge` where  `Deal Key`=%d group by  `Type`",
			$data['parent_key']);
	elseif ($data['parent']=='campaign')
		$sql=sprintf("select count(*) as num ,`Type` from  `Deal Campaign History Bridge` where  `Deal Campaign Key`=%d group by  `Type`",
			$data['parent_key']);
	elseif ($data['parent']=='purchase_order')
		$sql=sprintf("select count(*) as num ,`Type` from  `Purchase Order History Bridge` where  `Purchase Order Key`=%d group by  `Type`",
			$data['parent_key']);
	elseif ($data['parent']=='none')
		$sql=sprintf("select count(*) as num ,`Type` from  `%s Category History Bridge`  group by  `Type`",
			$data['subject']);
	else {
		$response=array('state'=>405, 'resp'=>'parent not found: '.$data['parent']);
		echo json_encode($response);
		return;
	}


	foreach ($db->query($sql) as $row) {
		$elements_numbers['type'][$row['Type']]=number($row['num']);
	}

	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);



	echo json_encode($response);


}


function get_orders_element_numbers($db, $data, $user) {

	if (!$user->can_view('orders')) {
		echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
		exit;
	}


	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);



	$parent_key=$data['parent_key'];


	switch ($data['parent']) {
	case 'store':
		$table='`Order Dimension` O';
		$where=sprintf('where  `Order Store Key`=%d', $parent_key);
		break;
	case 'campaign':
		$table='`Order Dimension` O left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) ';
		$where=sprintf('where  `Deal Campaign Key`=%d', $parent_key);
		break;
	case 'deal':
		$table='`Order Dimension` O left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) ';
		$where=sprintf('where  `Deal Key`=%d', $parent_key);
		break;
	default:
		exit ($data['parent']);
		break;
	}

	$where_interval=prepare_mysql_dates($from, $to, '`Order Date`');
	$where_interval=$where_interval['mysql'];

	$elements_numbers=array(
		'dispatch'=>array('InProcessCustomer'=>0, 'InProcess'=>0, 'Warehouse'=>0, 'Dispatched'=>0, 'Cancelled'=>0, 'Suspended'=>0),
		'source'=>array('Internet'=>0, 'Call'=>0, 'Store'=>0, 'Other'=>0, 'Email'=>0, 'Fax'=>0),
		'payment'=>array('Paid'=>0, 'PartiallyPaid'=>0, 'Unknown'=>0, 'WaitingPayment'=>0, 'NA'=>0),
		'type'=>array('Order'=>0, 'Sample'=>0, 'Donation'=>0, 'Other'=>0)
	);


	//USE INDEX (`Main Source Type Store Key`)
	$sql=sprintf("select count(*) as number,`Order Main Source Type` as element from %s    %s  %s group by `Order Main Source Type` ",
		$table, $where, $where_interval);

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$elements_numbers['source'][$row['element']]=number($row['number']);
		}
	}else {
		print "$sql";
		print_r($error_info=$db->errorInfo());
		exit;
	}



	// USE INDEX (`Type Store Key`)
	$sql=sprintf("select count(*) as number,`Order Type` as element from %s %s %s group by `Order Type` ",
		$table, $where, $where_interval);
	foreach ($db->query($sql) as $row) {

		$elements_numbers['type'][$row['element']]=number($row['number']);
	}
	//USE INDEX (`Current Dispatch State Store Key`)

	$sql=sprintf("select count(*) as number,`Order Current Dispatch State` as element from %s  %s %s group by `Order Current Dispatch State` ",
		$table, $where, $where_interval);
	foreach ($db->query($sql) as $row) {

		if ($row['element']!='') {

			if ($row['element']=='Cancelled by Customer')
				continue;

			if ($row['element']=='In Process by Customer' or $row['element']=='Waiting for Payment Confirmation') {
				$_element='InProcessCustomer';
			}elseif ($row['element']=='In Process' or $row['element']=='Submitted by Customer' ) {
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
	// USE INDEX (`Current Payment State Store Key`)
	$sql=sprintf("select count(*) as number,`Order Current Payment State` as element from %s  %s %s group by `Order Current Payment State` ",
		$table, $where, $where_interval);
	foreach ($db->query($sql) as $row) {
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



	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_invoices_element_numbers($db, $parameters) {

	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($db, $parameters['period'], $parameters['from'], $parameters['to']);



	$parent_key=$parameters['parent_key'];



	$where_interval=prepare_mysql_dates($from, $to, '`Order Date`');
	$where_interval=$where_interval['mysql'];

	$elements_numbers=array(
		'type'=>array('Invoice'=>0, 'Refund'=>0),
		'payment_state'=>array('Yes'=>0, 'No'=>0, 'Partially'=>0),
	);



	if (isset($parameters['awhere']) and $parameters['awhere']) {

		include_once 'invoices_awhere.php';

		$tmp=preg_replace('/\\\"/', '"', $parameters['awhere']);
		$tmp=preg_replace('/\\\\\"/', '"', $tmp);
		$tmp=preg_replace('/\'/', "\'", $tmp);

		$raw_data=json_decode($tmp, true);
		//$raw_data['store_key']=$store;
		//print_r( $raw_data);exit;
		list($where, $table)=invoices_awhere($raw_data);


	}
	elseif ($parameters['parent']=='category') {
		$category=new Category($parameters['parent_key']);



		$where=sprintf(" where `Subject`='Invoice' and  `Category Key`=%d", $parameters['parent_key']);
		$table=' `Category Bridge` left join  `Invoice Dimension` I on (`Subject Key`=`Invoice Key`) ';
		$where_type='';

		$store_key=$category->data['Category Store Key'];

	}
	elseif ($parameters['parent']=='list') {
		$sql=sprintf("select * from `List Dimension` where `List Key`=%d", $parameters['parent_key']);

		$res=mysql_query($sql);
		if ($list_data=mysql_fetch_assoc($res)) {
			$parameters['awhere']=false;
			$store_key=$list_data['List Parent Key'];
			if ($list_data['List Type']=='Static') {
				$table='`List Invoice Bridge` OB left join `Invoice Dimension` I  on (OB.`Invoice Key`=I.`Invoice Key`)';
				$where_type=sprintf(' and `List Key`=%d ', $parameters['parent_key']);

			} else {// Dynamic by DEFAULT



				$tmp=preg_replace('/\\\"/', '"', $list_data['List Metadata']);
				$tmp=preg_replace('/\\\\\"/', '"', $tmp);
				$tmp=preg_replace('/\'/', "\'", $tmp);

				$raw_data=json_decode($tmp, true);

				//$raw_data['store_key']=$store;
				list($where, $table)=invoices_awhere($raw_data);




			}

		} else {
			exit("error");
		}
	}
	elseif ($parameters['parent']=='store') {
		if (is_numeric($parameters['parent_key']) and in_array($parameters['parent_key'], $user->stores)) {
			$where=sprintf(' where  `Invoice Store Key`=%d ', $parameters['parent_key']);
			include_once 'class.Store.php';
			$store=new Store($parameters['parent_key']);
			$currency=$store->data['Store Currency Code'];
		}
		else {
			$where.=sprintf(' and  false');
		}


	}
	elseif ($parameters['parent']=='account') {
		if (is_numeric($parameters['parent_key']) and in_array($parameters['parent_key'], $user->stores)) {

			if (count($user->stores)==0) {
				$where=' where false';
			}
			else {

				$where=sprintf('where  `Invoice Store Key` in (%s)  ', join(',', $user->stores));

			}
		}
	}
	elseif ($parameters['parent']=='order') {

		$table='`Order Invoice Bridge` B left join   `Invoice Dimension` I  on (I.`Invoice Key`=B.`Invoice Key`)     left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
		$where=sprintf('where  B.`Order Key`=%d  ', $parameters['parent_key']);

	}
	elseif ($parameters['parent']=='delivery_note') {

		$table='`Invoice Delivery Note Bridge` B left join   `Invoice Dimension` I  on (I.`Invoice Key`=B.`Invoice Key`)     left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
		$where=sprintf('where  B.`Delivery Note Key`=%d  ', $parameters['parent_key']);

	}
	elseif ($parameters['parent']=='billingregion_taxcategory.invoices') {

		$fields='`Store Code`,`Store Name`,`Country Name`,';
		$table='`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`)  left join kbase.`Country Dimension` C on (I.`Invoice Billing Country 2 Alpha Code`=C.`Country 2 Alpha Code`) '   ;

		$parents=preg_split('/_/', $parameters['parent_key']);
		$where=sprintf('where  `Invoice Type`="Invoice" and  `Invoice Billing Region`=%s and `Invoice Tax Code`=%s  ',
			prepare_mysql($parents[0]),
			prepare_mysql($parents[1])
		);


	}
	elseif ($parameters['parent']=='billingregion_taxcategory.refunds') {

		$table='`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`)  left join kbase.`Country Dimension` C on (I.`Invoice Billing Country 2 Alpha Code`=C.`Country 2 Alpha Code`) '   ;

		$parents=preg_split('/_/', $parameters['parent_key']);
		$where=sprintf('where  `Invoice Type`!="Invoice"  and  `Invoice Billing Region`=%s and `Invoice Tax Code`=%s  ',
			prepare_mysql($parents[0]),
			prepare_mysql($parents[1])
		);


	}else {
		exit("unknown parent ".$parameters['parent']." \n");
	}




	$sql=sprintf("select count(*) as number,`Invoice Paid` as element from %s %s %s group by `Invoice Paid` ",
		$table, $where, $where_interval);
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {

		$elements_numbers['source'][$row['element']]=number($row['number']);
	}

	$sql=sprintf("select count(*) as number,`Invoice Type` as element   from %s %s %s group by `Invoice Type` ",
		$table, $where, $where_interval);
	foreach ($db->query($sql) as $row) {

		$elements_numbers['type'][$row['element']]=number($row['number']);
	}





	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_delivery_note_element_numbers($db, $data) {

	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);



	$parent_key=$data['parent_key'];



	$where_interval=prepare_mysql_dates($from, $to, '`Order Date`');
	$where_interval=$where_interval['mysql'];


	$elements_numbers=array(
		'dispatch'=>array('Ready'=>0, 'Picking'=>0, 'Packing'=>0, 'Done'=>0, 'Send'=>0, 'Returned'=>0),
		'type'=>array('Order'=>0, 'Sample'=>0, 'Donation'=>0, 'Replacements'=>0, 'Shortages'=>0)
	);

	$sql=sprintf("select count(*) as number,`Delivery Note Type` as element from %s %s group by `Delivery Note Type` ",
		$table, $where

	);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		if ($row['element']=='Replacement & Shortages' ) {
			$_element='Replacements';
		}elseif ($row['element']=='Replacement' ) {
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
		$table, $where);
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

	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}



function get_barcodes_elements($db, $data, $user) {



	$elements_numbers=array(
		'status'=>array('Available'=>0, 'Used'=>0, 'Reserved'=>0),

	);


	$table='`Barcode Dimension`  B';
	switch ($data['parent']) {
	case 'account':
		$where='';
		break;
	default:
		$response=array('state'=>405, 'resp'=>'product parent not found '.$data['parent']);
		echo json_encode($response);

		return;
	}





	$sql=sprintf("select count(*) as number,`Barcode Status` as element from $table $where  group by `Barcode Status` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['status'][$row['element']]=number($row['number']);

	}



	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_supplier_orders_elements($db, $data) {


	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);


	$parent_key=$data['parent_key'];


	switch ($data['parent']) {
	case 'supplier':
		$table='`Purchase Order Dimension` O';
		$where=sprintf('where  `Purchase Order Parent`="Supplier" and `Purchase Order Parent Key`=%d', $parent_key);
		break;
	case 'agent':
		$table='`Purchase Order Dimension` O';
		$where=sprintf('where  `Purchase Order Parent`="Agent" and `Purchase Order Parent Key`=%d', $parent_key);





		break;
	case 'account':
		$table='`Purchase Order Dimension` O';
		$where=sprintf('where  true');
		break;
	default:
		exit ($data['parent']);
		break;
	}

	$where_interval=prepare_mysql_dates($from, $to, '`Order Date`');
	$where_interval=$where_interval['mysql'];


	$elements_numbers=array(
		'state'=>array('InProcess'=>0, 'SubmittedInputtedDispatched'=>0, 'ReceivedChecked'=>0, 'Placed'=>0,  'Cancelled'=>0),
	);




	//USE INDEX (`Main Source Type Store Key`)
	$sql=sprintf("select count(*) as number,`Purchase Order State` as element from %s    %s  %s group by `Purchase Order State` ",
		$table, $where, $where_interval);

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {


			if ($row['element']=='Submitted' or $row['element']=='Inputted' or $row['element']=='Dispatched') {
				$element='SubmittedInputtedDispatched';
			}elseif ($row['element']=='Received' or $row['element']=='Checked') {
				$element='ReceivedChecked';
			}else {
				$element=$row['element'];
			}
			if (isset($elements_numbers['state'][$element]))
				$elements_numbers['state'][$element]+=$row['number'];
		}
	}else {
		print "$sql";
		print_r($error_info=$db->errorInfo());
		exit;
	}





	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_supplier_deliveries_element_numbers($db, $data) {

	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);

	$parent_key=$data['parent_key'];
	$where_interval=prepare_mysql_dates($from, $to, '`Supplier Delivery Date`');
	$where_interval=$where_interval['mysql'];

	$table='`Supplier Delivery Dimension`  SD  ';
	switch ($data['parent']) {
	case 'account':
		$where=sprintf(' where true');
		break;
	default:
		$response=array('state'=>405, 'resp'=>'product parent not found '.$data['parent']);
		echo json_encode($response);
		return;
	}

	$elements_numbers=array(
		'state'=>array('InProcess'=>0, 'Dispatched'=>0, 'Received'=>0, 'Checked'=>0, 'Placed'=>0, 'Cancelled'=>0),
	);

	$sql=sprintf("select count(*) as number,`Supplier Delivery State` as element from %s %s group by `Supplier Delivery State` ",
		$table, $where

	);


	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$elements_numbers['state'][$row['element']]=number($row['number']);
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_category_root_all_parts_elements($db, $data) {




	$elements_numbers=array(
		'status'=>array('Assigned'=>0, 'NoAssigned'=>0),
	);

	$sql=sprintf("select count(*) as number from `Category Bridge` where `Subject`='Part' and `Category Key`=%d ",
		$data['parent_key']

	);

	$assigned=0;

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$assigned=$row['number'];
			$elements_numbers['status']['Assigned']=number($row['number']);
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	$sql=sprintf("select count(*) as number from `Part Dimension`");


	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$elements_numbers['status']['NoAssigned']=number($row['number']-$assigned);
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


function get_ec_sales_list_elements($db, $parameters) {

	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($db, $parameters['period'], $parameters['from'], $parameters['to']);






	$where_interval=prepare_mysql_dates($from, $to, '`Invoice Date`');
	$where_interval=$where_interval['mysql'];

	$elements_numbers=array(
		'tax_status'=>array('Yes'=>0, 'No'=>0, 'Missing'=>0),
	);



	$countries='';
	$sql=sprintf('select `Country 2 Alpha Code`  from kbase.`Country Dimension`  where `EC Fiscal VAT Area`="Yes" and `Country 2 Alpha Code` not in ("GB","IM")  order by `Country Name`');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$countries.="'".addslashes($row['Country 2 Alpha Code'])."',";

		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	$countries=preg_replace('/\,$/', '', $countries);



	$where=' where `Invoice Billing Country 2 Alpha Code` in ('.$countries.')';


	$table='`Invoice Dimension`';

$group_by='group by `Invoice Tax Number`,`Invoice Billing Country 2 Alpha Code`,`Invoice Customer Key`';



$sql=sprintf("select `Invoice Key` as number from %s %s %s and `Invoice Tax Number`!='' and `Invoice Tax Number Valid`='Yes'  $group_by ",
	$table, $where, $where_interval);
	//print $sql;
	
	$stmt=$db->prepare($sql);
	$stmt->execute();
	$elements_numbers['tax_status']['Yes']=$stmt->rowCount();


$sql=sprintf("select `Invoice Key` as number from %s %s %s and `Invoice Tax Number`!='' and `Invoice Tax Number Valid`!='Yes'  $group_by ",
	$table, $where, $where_interval);
	//print $sql;
	
	$stmt=$db->prepare($sql);
	$stmt->execute();
	$elements_numbers['tax_status']['No']=$stmt->rowCount();


	$sql=sprintf("select `Invoice Key` as number from %s %s %s and ( `Invoice Tax Number` is NULL or `Invoice Tax Number`='' ) $group_by ",
	$table, $where, $where_interval);
	//print $sql;
	
	$stmt=$db->prepare($sql);
	$stmt->execute();
	$elements_numbers['tax_status']['Missing']=$stmt->rowCount();
	





	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


?>
