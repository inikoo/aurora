<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2015 20:13:55 GMT+7, Bangkok Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';


if (!$user->can_view('suppliers')) {
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
case 'order.items':
	order_items(get_table_parameters(), $db, $user, $account);
	break;
case 'invoice.items':
	invoice_items(get_table_parameters(), $db, $user, $account);
	break;
case 'delivery_note.items':
	delivery_note_items(get_table_parameters(), $db, $user);
	break;
case 'suppliers':
	suppliers(get_table_parameters(), $db, $user, $account);
	break;
case 'suppliers_edit':
	suppliers_edit(get_table_parameters(), $db, $user, $account);
	break;	
case 'agents':
	agents(get_table_parameters(), $db, $user, $account);
	break;
case 'categories':
	categories(get_table_parameters(), $db, $user, $account);
	break;
case 'orders':
	orders(get_table_parameters(), $db, $user, $account);
	break;
case 'supplier.order.supplier_parts':
	order_supplier_parts(get_table_parameters(), $db, $user, $account);
	break;
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function suppliers($_data, $db, $user, $account) {


	$rtext_label='supplier';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {


			/*
			$sales=money($data["Supplier $db_period Acc Parts Sold Amount"], $account->get('Account Currency'));

			if (in_array($parameters['f_period'], array('all', '3y', 'three_year'))) {
				$delta_sales='';
			}else {
				$delta_sales='<span title="'.money($data["Supplier $db_period Acc 1YB Parts Sold Amount"], $account->get('Account Currency')).'">'.delta($data["Supplier $db_period Acc Parts Sold Amount"], $data["Supplier $db_period Acc 1YB Parts Sold Amount"]).'</span>';
			}

			$profit=money($data["Supplier $db_period Acc Parts Profit"], $account->get('Account Currency'));
			$profit_after_storing=money($data["Supplier $db_period Acc Parts Profit After Storing"], $account->get('Account Currency'));
			$cost=money($data["Supplier $db_period Acc Parts Cost"], $account->get('Account Currency'));
			$margin=percentage($data["Supplier $db_period Acc Parts Margin"], 1);
			$sold=number($data["Supplier $db_period Acc Parts Sold"], 0);
			$required=number($data["Supplier $db_period Acc Parts Required"], 0);
*/

			$adata[]=array(
				'id'=>(integer)$data['Supplier Key'],
				'code'=>$data['Supplier Code'],
				'name'=>$data['Supplier Name'],
				'supplier_parts'=>number($data['Supplier Number Parts']),

				'surplus'=>sprintf('<span class="%s" title="%s">%s</span>', (ratio($data['Supplier Number Surplus Parts'], $data['Supplier Number Parts'])>.75?'error':(ratio($data['Supplier Number Surplus Parts'], $data['Supplier Number Parts'])>.5?'warning':'')), percentage($data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']), number($data['Supplier Number Surplus Parts'])),
				'optimal'=>sprintf('<span  title="%s">%s</span>', percentage($data['Supplier Number Optimal Parts'], $data['Supplier Number Parts']), number($data['Supplier Number Optimal Parts'])),
				'low'=>sprintf('<span class="%s" title="%s">%s</span>', (ratio($data['Supplier Number Low Parts'], $data['Supplier Number Parts'])>.5?'error':(ratio($data['Supplier Number Low Parts'], $data['Supplier Number Parts'])>.25?'warning':'')), percentage($data['Supplier Number Low Parts'], $data['Supplier Number Parts']), number($data['Supplier Number Low Parts'])),
				'critical'=>sprintf('<span class="%s" title="%s">%s</span>', ($data['Supplier Number Critical Parts']==0?'': (ratio($data['Supplier Number Critical Parts'], $data['Supplier Number Parts'])>.25?'error':'warning')), percentage($data['Supplier Number Critical Parts'], $data['Supplier Number Parts']), number($data['Supplier Number Critical Parts'])),
				'out_of_stock'=>sprintf('<span class="%s" title="%s">%s</span>', ($data['Supplier Number Out Of Stock Parts']==0?'':(ratio($data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts'])>.10?'error':'warning')), percentage($data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts']), number($data['Supplier Number Out Of Stock Parts'])),


				'location'=>$data['Supplier Location'],
				'email'=>$data['Supplier Main Plain Email'],
				'telephone'=>$data['Supplier Preferred Contact Number Formatted Number'],
				'contact'=>$data['Supplier Main Contact Name'],
				'company'=>$data['Supplier Company Name'],
				'revenue'=>'<span class="realce">'.money($data['revenue'], $account->get('Currency')).'</span>',
				'revenue_1y'=>'<span class="realce" title="'.money($data['revenue_1y'], $account->get('Currency')).'">'.delta($data['revenue'], $data['revenue_1y']).'</span>',


				//'sold'=>$sold,
				//'required'=>$required,
				//'origin'=>$data['Supplier Products Origin Country Code'],

				//'delivery_time'=>seconds_to_string(3600*24*$data['Supplier Average Delivery Days']),

				//'sales'=>$sales,
				//'delta_sales'=>$delta_sales,
				//'profit'=>$profit,
				//'profit_after_storing'=>$profit_after_storing,
				//'cost'=>$cost,
				//'pending_pos'=>number($data['Supplier Open Purchase Orders']),
				//'margin'=>$margin,
				'sales_year0'=>sprintf('<span title="%s">%s</span>', delta($data["Supplier Year To Day Acc Parts Sold Amount"], $data["Supplier Year To Day Acc 1YB Parts Sold Amount"]), money($data['Supplier Year To Day Acc Parts Sold Amount'], $account->get('Account Currency'))),
				'sales_year1'=>sprintf('<span title="%s">%s</span>', delta($data["Supplier 1 Year Ago Sales Amount"], $data["Supplier 2 Year Ago Sales Amount"]), money($data['Supplier 1 Year Ago Sales Amount'], $account->get('Account Currency'))),
				'sales_year2'=>sprintf('<span title="%s">%s</span>', delta($data["Supplier 2 Year Ago Sales Amount"], $data["Supplier 3 Year Ago Sales Amount"]), money($data['Supplier 2 Year Ago Sales Amount'], $account->get('Account Currency'))),
				'sales_year3'=>sprintf('<span title="%s">%s</span>', delta($data["Supplier 3 Year Ago Sales Amount"], $data["Supplier 4 Year Ago Sales Amount"]), money($data['Supplier 3 Year Ago Sales Amount'], $account->get('Account Currency'))),
				'sales_year4'=>money($data['Supplier 4 Year Ago Sales Amount'], $account->get('Account Currency')),

				//'delta_sales_year0'=>'<span title="'.money($data["Supplier Year To Day Acc 1YB Parts Sold Amount"], $account->get('Account Currency')).'">'.delta($data["Supplier Year To Day Acc Parts Sold Amount"], $data["Supplier Year To Day Acc 1YB Parts Sold Amount"]).'</span>',
				//'delta_sales_year1'=>'<span title="'.money($data["Supplier 2 Year Ago Sales Amount"], $account->get('Account Currency')).'">'.delta($data["Supplier 1 Year Ago Sales Amount"], $data["Supplier 2 Year Ago Sales Amount"]).'</span>',
				//'delta_sales_year2'=>'<span title="'.money($data["Supplier 3 Year Ago Sales Amount"], $account->get('Account Currency')).'">'.delta($data["Supplier 2 Year Ago Sales Amount"], $data["Supplier 3 Year Ago Sales Amount"]).'</span>',
				//'delta_sales_year3'=>'<span title="'.money($data["Supplier 4 Year Ago Sales Amount"], $account->get('Account Currency')).'">'.delta($data["Supplier 3 Year Ago Sales Amount"], $data["Supplier 4 Year Ago Sales Amount"]).'</span>'

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

function suppliers_edit($_data, $db, $user, $account) {


	$rtext_label='supplier';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {



			$adata[]=array(
				'id'=>(integer)$data['Supplier Key'],
				'link'=>$data['Supplier Code'],
				'checkbox'=>sprintf('<i key="" class="fa fa-fw fa-square-o button" aria-hidden="true"></i>',$data['Supplier Key']),
				'operations'=>sprintf('<i key="" class="fa fa-fw fa-cloud hide button" aria-hidden="true"></i>',$data['Supplier Key']),
				'code'=>$data['Supplier Code'],
				'name'=>$data['Supplier Name'],
		
				'email'=>$data['Supplier Main Plain Email'],
				'mobile'=>$data['Supplier Main XHTML Mobile'],
				'telephone'=>$data['Supplier Main XHTML Telephone'],
				'contact'=>$data['Supplier Main Contact Name'],
				'company'=>$data['Supplier Company Name'],


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


function agents($_data, $db, $user, $account) {


	$rtext_label='agent';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {




			$adata[]=array(
				'id'=>(integer)$data['Agent Key'],
				'code'=>$data['Agent Code'],
				'name'=>$data['Agent Name'],
				'suppliers'=>number($data['Agent Number Suppliers']),
				'supplier_parts'=>number($data['Agent Number Parts']),



				'surplus'=>sprintf('<span class="%s" title="%s">%s</span>', (ratio($data['Agent Number Surplus Parts'], $data['Agent Number Parts'])>.75?'error':(ratio($data['Agent Number Surplus Parts'], $data['Agent Number Parts'])>.5?'warning':'')), percentage($data['Agent Number Surplus Parts'], $data['Agent Number Parts']), number($data['Agent Number Surplus Parts'])),
				'optimal'=>sprintf('<span  title="%s">%s</span>', percentage($data['Agent Number Optimal Parts'], $data['Agent Number Parts']), number($data['Agent Number Optimal Parts'])),
				'low'=>sprintf('<span class="%s" title="%s">%s</span>', (ratio($data['Agent Number Low Parts'], $data['Agent Number Parts'])>.5?'error':(ratio($data['Agent Number Low Parts'], $data['Agent Number Parts'])>.25?'warning':'')), percentage($data['Agent Number Low Parts'], $data['Agent Number Parts']), number($data['Agent Number Low Parts'])),
				'critical'=>sprintf('<span class="%s" title="%s">%s</span>', ($data['Agent Number Critical Parts']==0?'': (ratio($data['Agent Number Critical Parts'], $data['Agent Number Parts'])>.25?'error':'warning')), percentage($data['Agent Number Critical Parts'], $data['Agent Number Parts']), number($data['Agent Number Critical Parts'])),
				'out_of_stock'=>sprintf('<span class="%s" title="%s">%s</span>', ($data['Agent Number Out Of Stock Parts']==0?'':(ratio($data['Agent Number Out Of Stock Parts'], $data['Agent Number Parts'])>.10?'error':'warning')), percentage($data['Agent Number Out Of Stock Parts'], $data['Agent Number Parts']), number($data['Agent Number Out Of Stock Parts'])),


				'location'=>$data['Agent Location'],
				'email'=>$data['Agent Main Plain Email'],
				'telephone'=>$data['Agent Preferred Contact Number Formatted Number'],
				'contact'=>$data['Agent Main Contact Name'],
				'company'=>$data['Agent Company Name'],


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


function orders($_data, $db, $user) {
	$rtext_label='purchase order';


	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();



	if ($result=$db->query($sql)) {
		foreach ($result as $data) {



			switch ($data['Purchase Order State']) {
			case 'In Process':
				$state=sprintf('%s', _('In Process'));
				break;
			case 'Submitted':
				$state=sprintf('%s', _('Submitted'));
				break;
			case 'Confirmed':
				$state=sprintf('%s', _('Confirmed'));
				break;
			case 'In Warehouse':
				$state=sprintf('%s', _('In Warehouse'));
				break;
			case 'Done':
				$state=sprintf('%s', _('Done'));
				break;
			case 'Cancelled':
				$state=sprintf('%s', _('Cancelled'));
				break;

			default:
				$state=$data['Purchase Order State'];
				break;
			}

			$adata[]=array(
				'id'=>(integer)$data['Purchase Order Key'],
				'parent_key'=> (integer) $data['Purchase Order Parent Key'],
				'public_id'=>$data['Purchase Order Public ID'],
				'date'=>strftime("%e %b %Y", strtotime($data['Purchase Order Creation Date'].' +0:00')),
				'last_date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Purchase Order Last Updated Date'].' +0:00')),
				'parent_name'=>$data['Purchase Order Parent Name'],
				'state'=>$state,

				'total_amount'=>money($data['Purchase Order Total Amount'], $data['Purchase Order Currency Code'])


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


function order_items($_data, $db, $user) {


	$rtext_label='item';

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {


			$quantity=number($data['Order Quantity']);

			if ($data['Order Bonus Quantity']!=0) {
				if ($data['Order Quantity']!=0) {
					$quantity.='<br/> +'.number($data['Order Bonus Quantity']).' '._('free');
				}else {
					$quantity=number($data['Order Bonus Quantity']).' '._('free');
				}
			}


			if (is_numeric($data['Product Availability']))
				$stock=number($data['Product Availability']);
			else
				$stock='?';

			$deal_info='';
			if ($data['Deal Info']!='') {
				$deal_info='<br/> <span class="deal_info">'.$data['Deal Info'].'</span>';
			}

			$units=$data['Product Units Per Case'];
			$name=$data['Product History Name'];
			$price=$data['Product History Price'];
			$currency=$data['Product Currency'];


			$description='';
			if ($units>1) {
				$description=number($units).'x ';
			}
			$description.=' '.$name;
			if ($price>0) {
				$description.=' ('.money($price, $currency, $_locale ).')';
			}


			$description.=' <span style="color:#777">['.$stock.']</span> '.$deal_info;



			if ($data['Current Dispatching State']=='Out of Stock in Basket') {
				$description.='<br> <span class="attention"><img src="/art/icons/error.png"> '._('Product out of stock, removed from basket').'</span>';
				$quantity=number($data['Out of Stock Quantity']);

				$class='out_of_stock';

			}


			$adata[]=array(

				'id'=>(integer)$data['Order Transaction Fact Key'],
				'product_pid'=>(integer)$data['Product ID'],
				'code'=>$data['Product Code'],
				'description'=>$description,
				'quantity'=>$quantity,
				'net'=>money($data['Order Transaction Amount'], $data['Order Currency Code']),


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


function order_supplier_parts($_data, $db, $user) {

	$purchase_order=get_object($_data['parameters']['parent'], $_data['parameters']['parent_key']);

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
				'formatted_sku'=>sprintf("SKU%05d", $data['Supplier Part Part SKU']),
				'part_description'=>'<span style="min-width:80px;display: inline-block;" class="link padding_right_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$data['Part Reference'].'</span> '.$data['Part Unit Description'],

				'description'=>$data['Part Unit Description'],
				'status'=>$status,
				'cost'=>money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
				'packing'=>'<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Supplier Part Units Per Package'].'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discret padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discret">'.($data['Supplier Part Units Per Package']*$data['Supplier Part Packages Per Carton'].'</span>'),
				'stock'=>number(floor($data['Part Current Stock']))." $stock_status"
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
