<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 20:14:17 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('sites')) {
	echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
	exit;
}


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'websites':
	websites(get_table_parameters(), $db, $user);
	break;
case 'pages':
	pages(get_table_parameters(), $db, $user);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function websites($_data, $db, $user) {

	$rtext_label='website';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;


	foreach ($db->query($sql) as $data) {


		$adata[]=array(
			'id'=>(integer) $data['Site Key'],
			'code'=>$data['Site Code'],
			'name'=>$data['Site Name'],
			'url'=>($data['Site SSL']=='Yes'?'https://':'http://').$data['Site URL'],
			'users'=>number($data['Site Total Acc Users']),
			'visitors'=>number($data['Site Total Acc Visitors']),
			'requests'=>number($data['Site Total Acc Requests']),
			'sessions'=>number($data['Site Total Acc Sessions']),
			'pages'=>number($data['Site Number Pages']),
			'pages_products'=>number($data['Site Number Pages with Products']),
			'pages_out_of_stock'=>number($data['Site Number Pages with Out of Stock Products']),
			'pages_out_of_stock_percentage'=>percentage($data['Site Number Pages with Out of Stock Products'], $data['Site Number Pages with Products']),
			'products'=>number($data['Site Number Products']),
			'out_of_stock'=>number($data['Site Number Out of Stock Products']),
			'out_of_stock_percentage'=>percentage($data['Site Number Out of Stock Products'], $data['Site Number Products']),
			'email_reminders_customers'=>number($data['Site Number Back in Stock Reminder Customers']),
			'email_reminders_products'=>number($data['Site Number Back in Stock Reminder Products']),
			'email_reminders_waiting'=>number($data['Site Number Back in Stock Reminder Waiting']),
			'email_reminders_ready'=>number($data['Site Number Back in Stock Reminder Ready']),
			'email_reminders_sent'=>number($data['Site Number Back in Stock Reminder Sent']),
			'email_reminders_cancelled'=>number($data['Site Number Back in Stock Reminder Cancelled'])

		);

	}

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

function pages($_data, $db, $user) {

	$rtext_label='page';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;

$interval_db= get_interval_db_name($parameters['f_period']);
	foreach ($db->query($sql) as $data) {

	$visitors=number($data["Page Store $interval_db Acc Visitors"]);
		$sessions=number($data["Page Store $interval_db Acc Sessions"]);
		$requests=number($data["Page Store $interval_db Acc Requests"]);
		$users=number($data["Page Store $interval_db Acc Users"]);


		switch ($data['Page Store Section']) {
		case 'Department Catalogue':
			$type=sprintf("d(<span class=\"link\" onClick=\"change_view('department/%d')\"  >%s</span>)",$data['Page Parent Key'],$data['Page Parent Code']);
			break;
		case 'Family Catalogue':
			$type=sprintf("f(<span class=\"link\" onClick=\"change_view('family/%d')\"  >%s</span>)",$data['Page Parent Key'],$data['Page Parent Code']);
			break;
		case 'Product Description':
			$type=sprintf("p(<span class=\"link\" onClick=\"change_view('product/%d')\"  >%s</span>)",$data['Page Parent Key'],$data['Page Parent Code']);
			break;

		case 'Welcome':
			$type=_('Welcome');
			break;
		case 'Login':
			$type=_('Login');
			break;
		case 'Information':
			$type=_('Information');
			break;
		case 'Checkout':
			$type=_('Checkout');
			break;
		case 'Reset':
			$type=_('Reset');
			break;
		case 'Registration':
			$type=_('Registration');
			break;
		case 'Not Found':
			$type=_('Not Found');
			break;
		case 'Client Section':
			$type=_('Client Section');
			break;
		case 'Client Section':
			$type=_('Client Section');
			break;
		case 'Front Page Store':
			$type=_('Home');
			break;
		case 'Basket':
			$type=_('Basket');
			break;
		case 'Thanks':
			$type=_('Thanks');
			break;
		case 'Payment Limbo':
			$type=_('Payment Limbo');
			break;
		case 'Search':
			$type=_('Search');
			break;
		default:
			$type=_('Other').' '.$data['Page Store Section'];
			break;
		}


		switch ($data['Site Flag']) {
		case 'Blue': $flag="<img  src='art/icons/flag_blue.png' title='".$data['Site Flag']."' />"; break;
		case 'Green':  $flag="<img  src='art/icons/flag_green.png' title='".$data['Site Flag']."' />";break;
		case 'Orange': $flag="<img src='art/icons/flag_orange.png' title='".$data['Site Flag']."'  />"; break;
		case 'Pink': $flag="<img  src='art/icons/flag_pink.png' title='".$data['Site Flag']."'/>"; break;
		case 'Purple': $flag="<img src='art/icons/flag_purple.png' title='".$data['Site Flag']."'/>"; break;
		case 'Red':  $flag="<img src='art/icons/flag_red.png' title='".$data['Site Flag']."'/>";break;
		case 'Yellow':  $flag="<img src='art/icons/flag_yellow.png' title='".$data['Site Flag']."'/>";break;
		default:
			$flag='';

		}

		switch ($data['Page State']) {
		case 'Online':
			//$state='<img src="/art/icons/world.png" alt='._('Online').'/>';
			$state=_('Online');
			break;
		case 'Offline':
			//$state='<img src="/art/icons/world_bw.png" alt='._('Offline').'/>';
			$state=_('Offline');
			break;
		default:
			$state='';
		}


		$products=number($data['Page Store Number Products']);
		$products_out_of_stock=number($data['Page Store Number Out of Stock Products']);
		$products_sold_out=number($data['Page Store Number Sold Out Products']);
		$percentage_products_out_of_stock=percentage($data['Page Store Number Out of Stock Products'],$data['Page Store Number Products']);
		$list_products=number($data['Page Store Number List Products']);
		$button_products=number($data['Page Store Number Button Products']);

		if ($data['Page State']=='Offline') {
			$products='<span style="color:#777;font-style:italic">'.$products.'</span>';
			$products_out_of_stock='<span style="color:#777;font-style:italic">'.$products_out_of_stock.'</span>';
			$products_sold_out='<span style="color:#777;font-style:italic">'.$products_sold_out.'</span>';
			$percentage_products_out_of_stock='<span style="color:#777;font-style:italic">'.$percentage_products_out_of_stock.'</span>';
			$list_products='<span style="color:#777;font-style:italic">'.$list_products.'</span>';
			$button_products='<span style="color:#777;font-style:italic">'.$button_products.'</span>';

		}

		$adata[]=array(
			'id'=>(integer) $data['Page Key'],
			'code'=>$data['Page Code'],
			'type'=>$type,
			'url'=>($data['Site SSL']=='Yes'?'https://':'http://').$data['Page URL'],
			'title'=>$data['Page Store Title'],
			'state'=>$state
	
		);

	}

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
