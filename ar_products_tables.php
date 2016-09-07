<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 09:35:34 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';


if (!$user->can_view('stores')) {
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
case 'stores':
	stores(get_table_parameters(), $db, $user);
	break;

case 'products':
	products(get_table_parameters(), $db, $user);
	break;
case 'services':
	services(get_table_parameters(), $db, $user);
	break;	
case 'categories':
	categories(get_table_parameters(), $db, $user);
	break;

case 'category_all_products':
	category_all_products(get_table_parameters(), $db, $user);
	break;
case 'sales_history':
	sales_history(get_table_parameters(), $db, $user, $account);
	break;
case 'parts':
	parts(get_table_parameters(), $db, $user, $account);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function stores($_data, $db, $user) {


	$rtext_label='store';

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;
	foreach ($db->query($sql) as $data) {


		$adata[]=array(
			'access'=>(in_array($data['Store Key'], $user->stores)?'':'<i class="fa fa-lock "></i>'),

			'id'=>(integer) $data['Store Key'],
			'code'=>$data['Store Code'],
			'name'=>$data['Store Name'],

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


function products($_data, $db, $user) {



	if ($_data['parameters']['parent']=='customer_favourites')
		$rtext_label='product favourited';
	else
		$rtext_label='product';





	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	$adata=array();
	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			$associated=sprintf('<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']);


			$adata[]=array(

				'id'=>(integer) $data['Product ID'],
				'store_key'=>(integer) $data['Store Key'],
				'associated'=>$associated,
				'store'=>$data['Store Code'],
				'code'=>$data['Product Code'],
				'name'=>$data['Product Name'],
				'price'=>money($data['Product Price'], $data['Store Currency Code']),
			);


		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
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

function services($_data, $db, $user) {



		$rtext_label='service';


	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	$adata=array();
	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			$associated=sprintf('<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']);


			$adata[]=array(

				'id'=>(integer) $data['Product ID'],
				'store_key'=>(integer) $data['Store Key'],
				'associated'=>$associated,
				'store'=>$data['Store Code'],
				'code'=>$data['Product Code'],
				'name'=>$data['Product Name'],
				'price'=>money($data['Product Price'], $data['Store Currency Code']),
			);


		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
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

function categories($_data, $db, $user) {

	$rtext_label='category';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			switch ($data['Category Branch Type']) {
			case 'Root':
				$level=_('Root');
				break;
			case 'Head':
				$level=_('Head');
				break;
			case 'Node':
				$level=_('Node');
				break;
			default:
				$level=$data['Category Branch Type'];
				break;
			}
			$level=$data['Category Branch Type'];


			$adata[]=array(
				'id'=>(integer) $data['Category Key'],
				'store_key'=>(integer) $data['Category Store Key'],
				'code'=>$data['Category Code'],
				'label'=>$data['Category Label'],
				'subjects'=>number($data['Category Number Subjects']),
				'level'=>$level,
				'subcategories'=>number($data['Category Children']),
				'percentage_assigned'=>percentage($data['Category Number Subjects'], ($data['Category Number Subjects']+$data['Category Subjects Not Assigned']))
			);

		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
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


function category_all_products($_data, $db, $user) {


	$rtext_label='product';

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	$adata=array();
	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			if ($data['associated'])
				$associated=sprintf('<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']);
			else
				$associated=sprintf('<i key="%d" class="fa fa-fw fa-unlink button very_discreet" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']);


			$adata[]=array(
				'id'=>(integer) $data['Product ID'],
				'associated'=>$associated,
				'code'=>$data['Product Code'],
				'name'=>$data['Product Name'],
				'price'=>money($data['Product Price'], $data['Store Currency Code']),
				'family'=>$data['Category Code']
			);
		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
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


function sales_history($_data, $db, $user, $account) {




	$skip_get_table_totals=true;

	include_once 'prepare_table/init.php';
	include_once 'utils/natural_language.php';
	include_once 'class.Store.php';


	if ($_data['parameters']['frequency']=='annually') {
		$rtext_label='year';
		$_group_by=' group by Year(`Date`) ';
		$sql_totals_fields='Year(`Date`)';
	}elseif ($_data['parameters']['frequency']=='monthy') {
		$rtext_label='month';
		$_group_by='  group by DATE_FORMAT(`Date`,"%Y-%m") ';
		$sql_totals_fields='DATE_FORMAT(`Date`,"%Y-%m")';
	}elseif ($_data['parameters']['frequency']=='weekly') {
		$rtext_label='week';
		$_group_by=' group by Yearweek(`Date`) ';
		$sql_totals_fields='Yearweek(`Date`)';
	}elseif ($_data['parameters']['frequency']=='daily') {
		$rtext_label='day';

		$_group_by=' group by Date(`Date`) ';
		$sql_totals_fields='`Date`';
	}

	switch ($_data['parameters']['parent']) {
	case 'product':
		include_once 'class.Product.php';
		$product=new Product($_data['parameters']['parent_key']);
		$currency=$product->get('Product Currency');
		$from=$product->get('Product Valid From');
		$to=($product->get('Product Status')=='Discontinued'?$product->get('Product Valid To'):gmdate('Y-m-d'));
		$date_field='`Invoice Date`';
		break;
	case 'category':
		include_once 'class.Category.php';
		$category=new Category($_data['parameters']['parent_key']);
		$currency=$category->get('Product Category Currency Code');
		$from=$category->get('Product Category Valid From');
		$to=($category->get('Product Category Status')=='Discontinued'?$product->get('Product Category Valid To'):gmdate('Y-m-d'));
		$date_field='`Timeseries Record Date`';
		break;
	default:
		print_r($_data);
		exit('parent not configurated');
		break;
	}


	$sql_totals=sprintf('select count(distinct %s) as num from kbase.`Date Dimension` where `Date`>=date(%s) and `Date`<=date(%s) ',
		$sql_totals_fields,
		prepare_mysql($from),
		prepare_mysql($to)

	);
	list($rtext, $total, $filtered)=get_table_totals($db, $sql_totals, '', $rtext_label, false);


	$sql=sprintf('select `Date` from kbase.`Date Dimension` where `Date`>=date(%s) and `Date`<=date(%s) %s order by %s  limit %s',
		prepare_mysql($from),
		prepare_mysql($to),
		$_group_by,
		"`Date` $order_direction ",
		"$start_from,$number_results"
	);
	//print $sql;


	$adata=array();

	$from_date='';
	$to_date='';
	if ($result=$db->query($sql)) {


		foreach ($result as $data) {

			if ($to_date=='') {
				$to_date=$data['Date'];
			}
			$from_date=$data['Date'];

			if ($_data['parameters']['frequency']=='annually') {
				$date=strftime("%Y", strtotime($data['Date'].' +0:00'));
				$_date=$date;
			}elseif ($_data['parameters']['frequency']=='monthy') {
				$date=strftime("%b %Y", strtotime($data['Date'].' +0:00'));
				$_date=$date;
			}elseif ($_data['parameters']['frequency']=='weekly') {
				$date=strftime("(%e %b) %Y %W ", strtotime($data['Date'].' +0:00'));
				$_date=strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
			}elseif ($_data['parameters']['frequency']=='daily') {
				$date=strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00'));
				$_date=$date;
			}

			$adata[$_date]=array(
				'sales'=>'<span class="very_discreet">'.money(0, $currency).'</span>',
				'customers'=>'<span class="very_discreet">'.number(0).'</span>',
				'invoices'=>'<span class="very_discreet">'.number(0).'</span>',
				'date'=>$date



			);

		}

	}else {
		print_r($error_info=$db->errorInfo());
		print "$sql";
		exit;
	}




	switch ($_data['parameters']['parent']) {
	case 'product':
		if ($_data['parameters']['frequency']=='annually') {
			$from_date=gmdate("Y-01-01 00:00:00", strtotime($from_date.' +0:00'));
			$to_date=gmdate("Y-12-31 23:59:59", strtotime($to_date.' +0:00'));
		}elseif ($_data['parameters']['frequency']=='monthy') {
			$from_date=gmdate("Y-m-01 00:00:00", strtotime($from_date.' +0:00'));
			$to_date=gmdate("Y-m-01 00:00:00", strtotime($to_date.' + 1 month +0:00'));
		}elseif ($_data['parameters']['frequency']=='weekly') {
			$from_date=gmdate("Y-m-d 00:00:00", strtotime($from_date.'  -1 week  +0:00'));
			$to_date=gmdate("Y-m-d 00:00:00", strtotime($to_date.' + 1 week +0:00'));
		}elseif ($_data['parameters']['frequency']=='daily') {
			$from_date=$from_date.' 00:00:00';
			$to_date=$to_date.' 23:59:59';
		}
		break;
	case 'category':
		if ($_data['parameters']['frequency']=='annually') {
			$from_date=gmdate("Y-01-01", strtotime($from_date.' +0:00'));
			$to_date=gmdate("Y-12-31", strtotime($to_date.' +0:00'));
		}elseif ($_data['parameters']['frequency']=='monthy') {
			$from_date=gmdate("Y-m-01", strtotime($from_date.' +0:00'));
			$to_date=gmdate("Y-m-01", strtotime($to_date.' + 1 month +0:00'));
		}elseif ($_data['parameters']['frequency']=='weekly') {
			$from_date=gmdate("Y-m-d", strtotime($from_date.'  -1 week  +0:00'));
			$to_date=gmdate("Y-m-d", strtotime($to_date.' + 1 week +0:00'));
		}elseif ($_data['parameters']['frequency']=='daily') {
			$from_date=$from_date.'';
			$to_date=$to_date.'';
		}

		break;
	default:
		print_r($_data);
		exit('parent not configurated');
		break;
	}




	$sql=sprintf("select $fields from $table $where $wheref and %s>=%s and  %s<=%s %s",
		$date_field,
		prepare_mysql($from_date),
		$date_field,
		prepare_mysql($to_date),
		" $group_by "
	);

	//print $sql;
	if ($result=$db->query($sql)) {



		foreach ($result as $data) {
			if ($_data['parameters']['frequency']=='annually') {
				$date=strftime("%Y", strtotime($data['Date'].' +0:00'));
				$_date=$date;
			}elseif ($_data['parameters']['frequency']=='monthy') {
				$date=strftime("%b %Y", strtotime($data['Date'].' +0:00'));
				$_date=$date;
			}elseif ($_data['parameters']['frequency']=='weekly') {
				$date=strftime("(%e %b) %Y %W ", strtotime($data['Invoice Date'].' +0:00'));
				$_date=strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
			}elseif ($_data['parameters']['frequency']=='daily') {
				$date=strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00'));
				$_date=$date;
			}

			if (array_key_exists($_date, $adata)) {

				$adata[$_date]=array(
					'sales'=>money($data['sales'], $currency),
					'customers'=>number($data['customers']),
					'invoices'=>number($data['invoices']),
					'date'=>$date


				);
			}
		}

	}else {
		print_r($error_info=$db->errorInfo());
		print "$sql";
		exit;
	}


	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>array_values($adata),
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}


function parts($_data, $db, $user, $account) {


	if (!$user->can_view('stores')) {
		echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
		exit;
	}


	$rtext_label='part';




	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();
	if ($result=$db->query($sql)) {
		foreach ($result as $data) {


			switch ($data['Part Stock Status']) {
			case 'Surplus':
				$stock_status='<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
				$stock_status_label=_('Surplus');
				break;
			case 'Optimal':
				$stock_status='<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
				$stock_status_label=_('Ok');
				break;
			case 'Low':
				$stock_status='<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
				$stock_status_label=_('Low');
				break;
			case 'Critical':
				$stock_status='<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
				$stock_status_label=_('Critical');
				break;
			case 'Out_Of_Stock':
				$stock_status='<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
				$stock_status_label=_('Out of stock');
				break;
			case 'Error':
				$stock_status='<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
				$stock_status_label=_('Error');
				break;
			default:
				$stock_status=$data['Part Stock Status'];
				$stock_status_label=$data['Part Stock Status'];
				break;
			}


			if ($data['Part Current Stock']<=0) {
				$weeks_available='-';
			}else {
				$weeks_available=number($data['Part Days Available Forecast']/7, 0);
			}

			$dispatched_per_week=number($data['Part 1 Quarter Acc Provided']*4/52, 0);



			$adata[]=array(
				'id'=>(integer)$data['Part SKU'],
				'reference'=>$data['Part Reference'],
				'package_description'=>$data['Part Package Description'],
				'picking_ratio'=>number($data['Product Part Ratio'],5),
				'picking_note'=>$data['Product Part Note'],
				'stock_status'=>$stock_status,
				'stock_status_label'=>$stock_status_label,
				'stock'=>'<span class="'.($data['Part Current Stock']<0?'error':'').'">'.number(floor($data['Part Current Stock'])).'</span>',
				'weeks_available'=>$weeks_available,
				'dispatched_per_week'=>$dispatched_per_week
			);


		}
	}else {
		print_r($error_info=$db->errorInfo());
		print $sql;
		exit;
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
