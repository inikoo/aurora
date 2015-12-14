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





if (!isset($_REQUEST['tab'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tab=$_REQUEST['tab'];

switch ($tab) {
case 'customers':
case 'website.favourites.customers':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_customers_element_numbers($db, $data['parameters']);
	break;

case 'orders':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_orders_element_numbers($db, $data['parameters']);
	break;
case 'invoices':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_invoices_element_numbers($db, $data['parameters']);
	break;	
case 'customer.history':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_history_elements($db, $data['parameters']);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tab not found '.$tab);
	echo json_encode($response);
	exit;
	break;
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
	case 'favourites':
		$where=sprintf(' where C.`Customer Key` in (select DISTINCT F.`Customer Key` from `Customer Favorite Product Bridge` F where `Site Key`=%d )', $data['parent_key']);
		break;
	default:
	    $response=array('state'=>405, 'resp'=>'customer parent not founs '.$data['parent']);
	echo json_encode($response);
	    
	    return;		
	}


	


	$sql=sprintf("select count(*) as number,`Customer With Orders` as element from `Customer Dimension`  C $where  group by `Customer With Orders` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['orders'][$row['element']]=number($row['number']);

	}

	$sql=sprintf("select count(*) as number,`Customer Type by Activity` as element from `Customer Dimension` C $where group by `Customer Type by Activity` ");

	foreach ($db->query($sql) as $row) {

		$elements_numbers['activity'][$row['element']]=number($row['number']);

	}

	$sql=sprintf("select count(*) as number,`Customer Level Type` as element from `Customer Dimension`  C $where group by `Customer Level Type` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['type'][$row['element']]=number($row['number']);

	}


	$sql=sprintf("select count(*) as number,`Customer Location Type` as element from `Customer Dimension` C $where group by `Customer Location Type` ");
	foreach ($db->query($sql) as $row) {

		$elements_numbers['location'][$row['element']]=number($row['number']);

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
	elseif ($data['parent']=='store')
		$sql=sprintf("select count(*) as num ,`Type` from  `%s Category History Bridge` where  `Store Key`=%d group by  `Type`",
			$data['subject'],
			$data['parent_key']);
	elseif ($data['parent']=='none')
		$sql=sprintf("select count(*) as num ,`Type` from  `%s Category History Bridge`  group by  `Type`",
			$data['subject']);
	else {
		$response=array('state'=>405, 'resp'=>'parent not found '.data['parent']);
		echo json_encode($response);
		return;
	}


	foreach ($db->query($sql) as $row) {
		$elements_numbers['type'][$row['Type']]=number($row['num']);
	}

	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);



	echo json_encode($response);


}


function get_orders_element_numbers($db, $data) {

	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($data['period'], $data['from'], $data['to']);



	$parent_key=$data['parent_key'];



	$where_interval=prepare_mysql_dates($from, $to, '`Order Date`');
	$where_interval=$where_interval['mysql'];

	$elements_numbers=array(
		'dispatch'=>array('InProcessCustomer'=>0, 'InProcess'=>0, 'Warehouse'=>0, 'Dispatched'=>0, 'Cancelled'=>0, 'Suspended'=>0),
		'source'=>array('Internet'=>0, 'Call'=>0, 'Store'=>0, 'Other'=>0, 'Email'=>0, 'Fax'=>0),
		'payment'=>array('Paid'=>0, 'PartiallyPaid'=>0, 'Unknown'=>0, 'WaitingPayment'=>0, 'NA'=>0),
		'type'=>array('Order'=>0, 'Sample'=>0, 'Donation'=>0, 'Other'=>0)
	);



	$sql=sprintf("select count(*) as number,`Order Main Source Type` as element from `Order Dimension` USE INDEX (`Main Source Type Store Key`)  where `Order Store Key`=%d %s group by `Order Main Source Type` ",
		$parent_key, $where_interval);
	$res=mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_assoc($res)) {

		$elements_numbers['source'][$row['element']]=number($row['number']);
	}

	$sql=sprintf("select count(*) as number,`Order Type` as element from `Order Dimension` USE INDEX (`Type Store Key`)  where `Order Store Key`=%d %s group by `Order Type` ",
		$parent_key, $where_interval);
	foreach ($db->query($sql) as $row) {

		$elements_numbers['type'][$row['element']]=number($row['number']);
	}


	$sql=sprintf("select count(*) as number,`Order Current Dispatch State` as element from `Order Dimension` USE INDEX (`Current Dispatch State Store Key`)    where `Order Store Key`=%d %s group by `Order Current Dispatch State` ",
		$parent_key, $where_interval);
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

	$sql=sprintf("select count(*) as number,`Order Current Payment State` as element from `Order Dimension` USE INDEX (`Current Payment State Store Key`)  where `Order Store Key`=%d %s group by `Order Current Payment State` ",
		$parent_key, $where_interval);
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

function get_invoices_element_numbers($db, $data) {

	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($data['period'], $data['from'], $data['to']);



	$parent_key=$data['parent_key'];



	$where_interval=prepare_mysql_dates($from, $to, '`Order Date`');
	$where_interval=$where_interval['mysql'];

	$elements_numbers=array(
		'type'=>array('Invoice'=>0, 'Refund'=>0),
		'payment_state'=>array('Yes'=>0, 'No'=>0, 'Partially'=>0),
	);



	$sql=sprintf("select count(*) as number,`Invoice Paid` as element from `Invoice Dimension`  where `Invoice Store Key`=%d %s group by `Invoice Paid` ",
		$parent_key, $where_interval);
	$res=mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_assoc($res)) {

		$elements_numbers['source'][$row['element']]=number($row['number']);
	}

	$sql=sprintf("select count(*) as number,`Invoice Type` as element from `Invoice Dimension`   where `Invoice Store Key`=%d %s group by `Invoice Type` ",
		$parent_key, $where_interval);
	foreach ($db->query($sql) as $row) {

		$elements_numbers['type'][$row['element']]=number($row['number']);
	}





	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}

function get_delivery_note_element_numbers($db, $data) {

	list($db_interval, $from, $to, $from_date_1yb, $to_1yb)=calculate_interval_dates($data['period'], $data['from'], $data['to']);



	$parent_key=$data['parent_key'];



	$where_interval=prepare_mysql_dates($from, $to, '`Order Date`');
	$where_interval=$where_interval['mysql'];

	
$elements_numbers=array(
		'dispatch'=>array('Ready'=>0,'Picking'=>0,'Packing'=>0,'Done'=>0,'Send'=>0,'Returned'=>0),
		'type'=>array('Order'=>0,'Sample'=>0,'Donation'=>0,'Replacements'=>0,'Shortages'=>0)
	);

	$sql=sprintf("select count(*) as number,`Delivery Note Type` as element from %s %s group by `Delivery Note Type` ",
		$table,$where

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

	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


?>
