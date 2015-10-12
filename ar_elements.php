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





if (!isset($_REQUEST['tab'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tab=$_REQUEST['tab'];

switch ($tab) {
case 'orders':
	$data=prepare_values($_REQUEST, array(
			'parameters'=>array('type'=>'json array')
		));
	get_order_element_numbers($data['parameters']);
	break;


default:
	$response=array('state'=>405, 'resp'=>'Tab not found '.$tab);
	echo json_encode($response);
	exit;
	break;
}





function get_order_element_numbers($data) {

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
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$elements_numbers['type'][$row['element']]=number($row['number']);
	}


	$sql=sprintf("select count(*) as number,`Order Current Dispatch State` as element from `Order Dimension` USE INDEX (`Current Dispatch State Store Key`)    where `Order Store Key`=%d %s group by `Order Current Dispatch State` ",
		$parent_key, $where_interval);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

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



	$response= array('state'=>200, 'elements_numbers'=>$elements_numbers);
	echo json_encode($response);



}


?>
