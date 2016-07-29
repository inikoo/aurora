<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 15:38:12 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/natural_language.php';


if (!$user->can_view('parts')) {
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

case 'parts':
	parts(get_table_parameters(), $db, $user, 'active', $account);
	break;
case 'stock_transactions':
	stock_transactions(get_table_parameters(), $db, $user);
	break;
case 'stock_history':
	stock_history(get_table_parameters(), $db, $user, $account);
	break;
case 'inventory_stock_history':
	inventory_stock_history(get_table_parameters(), $db, $user, $account);
	break;	
case 'discontinued_parts':
	parts(get_table_parameters(), $db, $user, 'discontinued');
	break;
case 'barcodes':
	barcodes(get_table_parameters(), $db, $user);
	break;
case 'supplier_parts':
	supplier_parts(get_table_parameters(), $db, $user);
	break;

case 'categories':
	categories(get_table_parameters(), $db, $user);
	break;
case 'product_families':
	product_families(get_table_parameters(), $db, $user);
	break;
case 'category_all_parts':
	category_all_parts(get_table_parameters(), $db, $user);
	break;


default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}



function parts($_data, $db, $user, $type, $account) {

	if ($type=='discontinued') {
		$extra_where=' and `Part Status`="Not In Use"';
		$rtext_label='discontinued part';

	}elseif ($type=='active') {
		$extra_where=' and `Part Status`="In Use"';
		$rtext_label='part';

	}else {
		$extra_where='';
		$rtext_label='part';

	}


	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {



			switch ($data['Part Stock Status']) {
			case 'Surplus':
				$stock_status='<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Optimal':
				$stock_status='<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Low':
				$stock_status='<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Critical':
				$stock_status='<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Out_Of_Stock':
				$stock_status='<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
				break;
			case 'Error':
				$stock_status='<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
				break;
			default:
				$stock_status=$data['Part Stock Status'];
				break;
			}


			$adata[]=array(
				'id'=>(integer)$data['Part SKU'],
				'reference'=>$data['Part Reference'],
				'unit_description'=>$data['Part Unit Description'],
				'stock_status'=>$stock_status,
				'stock'=>'<span class="'.($data['Part Current Stock']<0?'error':'').'">'.number(floor($data['Part Current Stock'])).'</span>',
				'sold'=>number($data['sold']),
				'sold_1y'=>delta($data['sold'], $data['sold_1y']),
				'revenue'=>money($data['revenue'], $account->get('Currency')),
				'revenue_1y'=>delta($data['revenue'], $data['revenue_1y']),

				'lost'=>number($data['lost']),
				'bought'=>number($data['bought']),

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


function stock_history($_data, $db, $user, $account) {



	if ($_data['parameters']['frequency']=='annually') {
		$rtext_label='year';

	}elseif ($_data['parameters']['frequency']=='monthy') {
		$rtext_label='month';
	}elseif ($_data['parameters']['frequency']=='weekly') {
		$rtext_label='week';
	}else {
		$rtext_label='day';
	}


	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {



			$adata[]=array(
				'date'=>strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00')),
				'year'=>strftime("%Y", strtotime($data['Date'].' +0:00')),
				'month_year'=>strftime("%b %Y", strtotime($data['Date'].' +0:00')),
				'week_year'=>strftime("(%e %b) %Y %W ", strtotime($data['Date'].' +0:00')),
				'stock'=>number($data['Quantity On Hand']),
				'value'=>money($data['Value At Day Cost'], $account->get('Currency')),
				'in'=>number($data['Quantity In']),
				'sold'=>number($data['Quantity Sold']),
				'lost'=>number($data['Quantity Lost']),

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

function inventory_stock_history($_data, $db, $user, $account) {



	if ($_data['parameters']['frequency']=='annually') {
		$rtext_label='year';

	}elseif ($_data['parameters']['frequency']=='monthy') {
		$rtext_label='month';
	}elseif ($_data['parameters']['frequency']=='weekly') {
		$rtext_label='week';
	}else {
		$rtext_label='day';
	}


	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {



			$adata[]=array(
				'date'=>$data['Date'],
				'day'=>strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00')),
				'year'=>strftime("%Y", strtotime($data['Date'].' +0:00')),
				'month_year'=>strftime("%b %Y", strtotime($data['Date'].' +0:00')),
				'week_year'=>strftime("(%e %b) %Y %W ", strtotime($data['Date'].' +0:00')),
				'parts'=>number($data['Parts']),
				'locations'=>number($data['Locations']),

				'value'=>money($data['Value At Day Cost'], $account->get('Currency')),
				'commercial_value'=>money($data['Value Commercial'], $account->get('Currency')),
				//'in'=>number($data['Quantity In']),
				//'sold'=>number($data['Quantity Sold']),
				//'lost'=>number($data['Quantity Lost']),

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


function stock_transactions($_data, $db, $user) {


	$rtext_label='transaction';

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	//print $sql;
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {
			//MossRB-04 227330 Taken from: 11A1

			$note=$data['Note'];
			$stock=$data['Inventory Transaction Quantity'];
			switch ($data['Inventory Transaction Type']) {
			case 'OIP':
				$type='<i class="fa  fa-clock-o discret fa-fw" aria-hidden="true"></i>';

				if ($parameters['parent']=='part') {
					$note=sprintf(_('%s %s (%s) to be taken from %s'),

						number($data['Required']),
						'<span title="'._('Stock keeping outers').'">SKO</span>',

						sprintf('<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-fw fa-shopping-basket" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']),
						sprintf('<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code'])


					);
				}else {
					$note=sprintf(_('%sx %s (%s) to be taken from %s'),
						number($data['Required']),

						($parameters['parent']=='part'?
							sprintf('<i class="fa fa-square" aria-hidden="true"></i> %s', $data['Part Reference']):
							sprintf('<span class="button" onClick="change_view(\'part/%d\')"><i class="fa fa-square" aria-hidden="true"></i> %s</span>', $data['Part SKU'], $data['Part Reference'])
						),
						sprintf('<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-shopping-basket" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']),
						sprintf('<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code'])

					);
				}


				break;
			case 'Sale':
				$type='<i class="fa fa-sign-out fa-fw" aria-hidden="true"></i>';
				if ($parameters['parent']=='part') {
					$note=sprintf(_('%s %s (%s) taken from %s'),

						number(-1*$data['Inventory Transaction Quantity']),
						'<span title="'._('Stock keeping outers').'">SKO</span>',

						sprintf('<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']),
						sprintf('<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code'])


					);
				}else {
					$note=sprintf(_('%sx %s (%s) taken from %s'),
						number(-1*$data['Inventory Transaction Quantity']),

						($parameters['parent']=='part'?
							sprintf('<i class="fa fa-square" aria-hidden="true"></i> %s', $data['Part Reference']):
							sprintf('<span class="button" onClick="change_view(\'part/%d\')"><i class="fa fa-square" aria-hidden="true"></i> %s</span>', $data['Part SKU'], $data['Part Reference'])
						),
						sprintf('<span class="button" onClick="change_view(\'delivery_note/%d\')"><i class="fa fa-truck" aria-hidden="true"></i> %s</span>', $data['Delivery Note Key'], $data['Delivery Note ID']),
						sprintf('<span class="button" onClick="change_view(\'location/%d\')">%s</span>', $data['Location Key'], $data['Location Code'])

					);
				}



				break;
			case 'In':
				$type='<i class="fa fa-sign-in fa-fw" aria-hidden="true"></i>';
				break;
			case 'Audit':




				$type='<i class="fa fa-fw fa-dot-circle-o" aria-hidden="true"></i>';

				$stock=sprintf('<b>'.$data['Part Location Stock'].'</b>');
				break;
			case 'Adjust':

				if ($stock>0) {
					$stock='+'.number($stock);
				}

				$type='<i class="fa fa-fw fa-sliders" aria-hidden="true"></i>';


				break;

			case 'Move':
				$stock='±'.number($data['Metadata']);
				$type='<i class="fa fa-refresh fa-fw" aria-hidden="true"></i>';
				break;
			case 'Error':
				$type='<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
				break;
			default:
				$type=$data['Inventory Transaction Section'];
				break;
			}


			$adata[]=array(
				'id'=>(integer)$data['Inventory Transaction Key'],
				'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Date'].' +0:00')),
				'user'=>sprintf('<span title="%s">%s</span>', $data['User Alias'], $data['User Handle']),

				'change'=>$stock,
				'note'=>$note,
				'type'=>$type,

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


function supplier_parts($_data, $db, $user) {


	$rtext_label='supplier part';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {

			switch ($data['Supplier Part Status']) {
			case 'Available':
				$status=sprintf('<i class="fa fa-stop success" title="%s"></i>', _('Available'));
				break;
			case 'NoAvailable':
				$status=sprintf('<i class="fa fa-stop warning" title="%s"></i>', _('No available'));

				break;
			case 'Discontinued':
				$status=sprintf('<i class="fa fa-ban error" title="%s"></i>', _('Discontinued'));

				break;
			default:
				$status=$data['Supplier Part Status'];
				break;
			}

			switch ($data['Part Stock Status']) {
			case 'Surplus':
				$stock_status='<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Optimal':
				$stock_status='<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Low':
				$stock_status='<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Critical':
				$stock_status='<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Out_Of_Stock':
				$stock_status='<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
				break;
			case 'Error':
				$stock_status='<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
				break;
			default:
				$stock_status=$data['Part Stock Status'];
				break;
			}



			$adata[]=array(
				'id'=>(integer)$data['Supplier Part Key'],
				'supplier_key'=>(integer)$data['Supplier Part Supplier Key'],
				'supplier_code'=>$data['Supplier Code'],
				'part_key'=>(integer)$data['Supplier Part Part SKU'],
				'part_reference'=>$data['Part Reference'],
				'reference'=>$data['Supplier Part Reference'],
				'part_description'=>'<span style="min-width:80px;display: inline-block;" class="link padding_right_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$data['Part Reference'].'</span> '.$data['Part Unit Description'],

				'description'=>$data['Part Unit Description'],
				'status'=>$status,
				'cost'=>money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
				'packing'=>'<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Supplier Part Units Per Package'].'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discret padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discret">'.($data['Supplier Part Units Per Package']*$data['Supplier Part Packages Per Carton'].'</span>'),
				'stock'=>number(floor($data['Part Current Stock']))." $stock_status",


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




function barcodes($_data, $db, $user) {


	$rtext_label='barcodes';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	//print $sql;
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {

			switch ($data['Barcode Status']) {
			case 'Available':
				$status=sprintf('<i class="fa fa-barcode fa-fw" ></i> %s', _('Available'));
				break;
			case 'Used':
				$status=sprintf('<span class="disabled"><i class="fa fa-cube fa-fw " ></i> %s', _('Used').'</span>');

				break;
			case 'Reserved':
				$status=sprintf('<span class="disabled"> <i class="fa fa-shield fa-fw " ></i> %s', _('Reserved').'</span>');

				break;
			default:
				$status=$data['Barcode Status'];
				break;
			}
			if ($data['parts']!='') {
				$_parts=preg_split('/,/', $data['parts']);
				$assets=sprintf('<i class="fa fa-square fa-fw"></i> <span class="link" onClick="change_view(\'part/%d\')">%s</span>', $_parts[0], $_parts[1]);
			}else {
				$assets='';
			}

			$adata[]=array(
				'id'=>(integer)$data['Barcode Key'],
				'link'=>'<i class="fa fa-barcode "></i>',
				// '<span class="fa-stack fa-lg"><i class="fa fa-barcode fa-stack-1x"></i><i class="fa fa-angle-down fa-inverse fa-stack-1x"></i></span>',
				'number'=>$data['Barcode Number'],

				'status'=>$status,
				'notes'=>$data['Barcode Sticky Note'],
				'assets'=>$assets

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


function category_all_parts($_data, $db, $user) {


	$rtext_label='part';

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	$adata=array();
	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			if ($data['associated'])
				$associated=sprintf('<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Part SKU']);
			else
				$associated=sprintf('<i key="%d" class="fa fa-fw fa-unlink button very_discreet" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Part SKU']);


			$adata[]=array(
				'id'=>(integer) $data['Part SKU'],
				'associated'=>$associated,
				'reference'=>$data['Part Reference'],
				'unit_description'=>$data['Part Unit Description'],
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


function product_families($_data, $db, $user) {


	$rtext_label='store';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	//print $sql;

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {


			if ($data['category_data']=='') {
				$family='<span class="super_discreet">'._('Family not set').'</span>';
				$number_products ='<span class="super_discreet">-</span>';
				$operations=(in_array($data['Store Key'], $user->stores)?'<i class="fa fa-plus button" aria-hidden="true" onClick="open_new_product_family('.$data['Store Key'].')" ></i>':'<i class="fa fa-lock "></i>');
			}else {
				$family_data=preg_split('/,/', $data['category_data']);
				$family=sprintf('<span class="button" onClick="change_view(\'products/%d/category/%d\')">%s</span>', $data['Store Key'], $family_data[1], $family_data[0]);
				$number_products =number($data['number_products']);
				$operations=(in_array($data['Store Key'], $user->stores)?'<i class="fa fa-refresh button" aria-hidden="true" onClick="open_new_product_family('.$data['Store Key'].')" )"></i>':'<i class="fa fa-lock "></i>');

			}


			$adata[]=array(
				'operations'=>$operations,

				'id'=>(integer) $data['Store Key'],
				'code'=>$data['Store Code'],
				'name'=>$data['Store Name'],
				'family'=>$family,
				'number_products'=>$number_products
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
