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
case 'nodes':
	nodes(get_table_parameters(), $db, $user);
	break;
case 'root_nodes':
	root_nodes(get_table_parameters(), $db, $user);
	break;


case 'pages':
	pages(get_table_parameters(), $db, $user);
	break;
case 'pageviews':
	pageviews(get_table_parameters(), $db, $user);
	break;
case 'queries':
	queries(get_table_parameters(), $db, $user);
	break;
case 'search_history':
	search_history(get_table_parameters(), $db, $user);
	break;
case 'users':
	users(get_table_parameters(), $db, $user);
	break;
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}

function users($_data, $db, $user) {

	$rtext_label='user';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	//print $sql;
	foreach ($db->query($sql) as $data) {

		$adata[]=array(
			'site_key'=>$data['User Site Key'],
			'id'=>$data['User Key'],
			'customer_key'=>$data['User Parent Key'],
			'user'=>$data['User Handle'],
			'customer'=>$data['User Alias'],
			'sessions'=>number($data['User Sessions Count']),
			'last_login'=>($data['User Last Login']?strftime("%a %e %b %Y %H:%M %Z", strtotime($data['User Last Login'].' +0:00')):''),
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


function queries($_data, $db, $user) {

	$rtext_label='query';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();


	foreach ($db->query($sql) as $data) {


		$adata[]=array(
			'site_key'=>$data['Website Key'],
			'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['date'].' +0:00')),
			'query'=>$data['Query'],
			'number'=>number($data['number']),
			'users'=>number($data['users']),
			'results'=>number($data['results'], 1),
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


function search_history($_data, $db, $user) {

	$rtext_label='search';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	foreach ($db->query($sql) as $data) {


		$user=$data['User Alias'];

		$adata[]=array(
			'site_key'=>$data['Website Key'],
			'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Date'].' +0:00')),
			'query'=>$data['Query'],
			'user_key'=>$data['User Key'],
			'user'=>$user,
			'results'=>number($data['Number Results']),
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


function websites($_data, $db, $user) {

	$rtext_label='website';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();




	foreach ($db->query($sql) as $data) {


		$adata[]=array(
			'id'=>(integer) $data['Website Key'],
			'code'=>$data['Website Code'],
			'name'=>$data['Website Name'],
			'url'=>$data['Website URL'],
			'users'=>number($data['Website Total Acc Users']),
			'visitors'=>number($data['Website Total Acc Visitors']),
			'requests'=>number($data['Website Total Acc Requests']),
			'sessions'=>number($data['Website Total Acc Sessions']),
			'pages'=>number($data['Website Number WebPages']),
			'pages_products'=>number($data['Website Number WebPages with Products']),
			'pages_out_of_stock'=>number($data['Website Number WebPages with Out of Stock Products']),
			'pages_out_of_stock_percentage'=>percentage($data['Website Number WebPages with Out of Stock Products'], $data['Website Number WebPages with Products']),
			'products'=>number($data['Website Number Products']),
			'out_of_stock'=>number($data['Website Number Out of Stock Products']),
			'out_of_stock_percentage'=>percentage($data['Website Number Out of Stock Products'], $data['Website Number Products']),
			//'email_reminders_customers'=>number($data['Website Number Back in Stock Reminder Customers']),
			//'email_reminders_products'=>number($data['Website Number Back in Stock Reminder Products']),
			//'email_reminders_waiting'=>number($data['Website Number Back in Stock Reminder Waiting']),
			//'email_reminders_ready'=>number($data['Website Number Back in Stock Reminder Ready']),
			//'email_reminders_sent'=>number($data['Website Number Back in Stock Reminder Sent']),
			//'email_reminders_cancelled'=>number($data['Website Number Back in Stock Reminder Cancelled'])

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

		$period_visitors=number($data["Page Store $interval_db Acc Visitors"]);
		$period_sessions=number($data["Page Store $interval_db Acc Sessions"]);
		$period_requests=number($data["Page Store $interval_db Acc Requests"]);
		$period_users=number($data["Page Store $interval_db Acc Users"]);

		$users=number($data["Page Store Total Acc Users"]);
		$requests=number($data["Page Store Total Acc Requests"]);

		switch ($data['Page Store Section']) {
		case 'Department Catalogue':
			$type=sprintf("d(<span class=\"link\" onClick=\"change_view('department/%d')\"  >%s</span>)", $data['Page Parent Key'], $data['Page Parent Code']);
			break;
		case 'Family Catalogue':
			$type=sprintf("f(<span class=\"link\" onClick=\"change_view('family/%d')\"  >%s</span>)", $data['Page Parent Key'], $data['Page Parent Code']);
			break;
		case 'Product Description':
			$type=sprintf("p(<span class=\"link\" onClick=\"change_view('product/%d')\"  >%s</span>)", $data['Page Parent Key'], $data['Page Parent Code']);
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


		switch ($data['Website Flag']) {
		case 'Blue': $flag="<img  src='art/icons/flag_blue.png' title='".$data['Website Flag']."' />"; break;
		case 'Green':  $flag="<img  src='art/icons/flag_green.png' title='".$data['Website Flag']."' />";break;
		case 'Orange': $flag="<img src='art/icons/flag_orange.png' title='".$data['Website Flag']."'  />"; break;
		case 'Pink': $flag="<img  src='art/icons/flag_pink.png' title='".$data['Website Flag']."'/>"; break;
		case 'Purple': $flag="<img src='art/icons/flag_purple.png' title='".$data['Website Flag']."'/>"; break;
		case 'Red':  $flag="<img src='art/icons/flag_red.png' title='".$data['Website Flag']."'/>";break;
		case 'Yellow':  $flag="<img src='art/icons/flag_yellow.png' title='".$data['Website Flag']."'/>";break;
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
		$percentage_products_out_of_stock=percentage($data['Page Store Number Out of Stock Products'], $data['Page Store Number Products']);
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
			'url'=>($data['Website SSL']=='Yes'?'https://':'http://').$data['Page URL'],
			'title'=>$data['Page Store Title'],
			'state'=>$state,
			'users'=>$users,
			'requests'=>$requests,
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


function pageviews($_data, $db, $user) {

	$rtext_label='pageview';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;

	$interval_db= get_interval_db_name($parameters['f_period']);
	foreach ($db->query($sql) as $data) {

		switch ($data['Page Store Section']) {
		case 'Department Catalogue':
			$type=sprintf("d(<span class=\"link\" onClick=\"change_view('department/%d')\"  >%s</span>)", $data['Page Parent Key'], $data['Page Parent Code']);
			break;
		case 'Family Catalogue':
			$type=sprintf("f(<span class=\"link\" onClick=\"change_view('family/%d')\"  >%s</span>)", $data['Page Parent Key'], $data['Page Parent Code']);
			break;
		case 'Product Description':
			$type=sprintf("p(<span class=\"link\" onClick=\"change_view('product/%d')\"  >%s</span>)", $data['Page Parent Key'], $data['Page Parent Code']);
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

		$adata[]=array(
			'id'=>(integer) $data['User Request Key'],
			'page'=>$data['Page Code'],
			'title'=>$data['Page Store Title'],
			'type'=>$type,

			'page_key'=>$data['Page Key'],
			'site_key'=>$data['Page Site Key'],
			'date'=>strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($data['Date'])),

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


function root_nodes($_data, $db, $user) {

	$rtext_label='section';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	foreach ($db->query($sql) as $data) {

		$adata[]=array(
			'id'=>(integer) $data['Website Node Key'],
			'code'=>$data['Website Node Code'],
			'name'=>$data['Website Node Name'],

	
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

function nodes($_data, $db, $user) {

	$rtext_label='section';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	foreach ($db->query($sql) as $data) {

		$adata[]=array(
			'id'=>(integer) $data['Website Node Key'],
			'code'=>$data['Website Node Code'],
			'name'=>$data['Website Node Name'],

	
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
