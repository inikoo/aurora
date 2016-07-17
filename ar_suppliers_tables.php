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
case 'delivery.items':
	delivery_items(get_table_parameters(), $db, $user);
	break;
case 'delivery.checking_items':
	delivery_checking_items(get_table_parameters(), $db, $user);
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
case 'deliveries':
	deliveries(get_table_parameters(), $db, $user, $account);
	break;
case 'supplier.order.supplier_parts':
	order_supplier_parts(get_table_parameters(), $db, $user, $account);
	break;
case 'category_all_suppliers':
	category_all_suppliers(get_table_parameters(), $db, $user, $account);
	break;
case 'order.supplier_parts':
	order_supplier_all_parts(get_table_parameters(), $db, $user);
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


			if ($_data['parameters']['parent']=='agent') {
				$operations=sprintf('<i agent_key="%d" supplier_key="%d"  class="fa fa-chain-broken button" aria-hidden="true"  onClick="bridge_supplier(this)" ></i>',
					$_data['parameters']['parent_key'],
					$data['Supplier Key']
				);
			}else {
				$operations='';
			}

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

			$associated=sprintf('<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Supplier Key']);

			$adata[]=array(
				'id'=>(integer)$data['Supplier Key'],
				'operations'=>$operations,
				'associated'=>$associated,

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

				'checkbox'=>sprintf('<i key="" class="fa fa-fw fa-square-o button" aria-hidden="true"></i>', $data['Supplier Key']),
				'operations'=>sprintf('<i key="" class="fa fa-fw fa-cloud hide button" aria-hidden="true"></i>', $data['Supplier Key']),
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
			case 'InProcess':
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
				'parent_type'=> strtolower($data['Purchase Order Parent']),
				'parent'=> strtolower($data['Purchase Order Parent Name']),

				'public_id'=>$data['Purchase Order Public ID'],
				'date'=>strftime("%e %b %Y", strtotime($data['Purchase Order Creation Date'].' +0:00')),
				'last_date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Purchase Order Last Updated Date'].' +0:00')),
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


function deliveries($_data, $db, $user) {
	$rtext_label='delivery';


	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {

			switch ($data['Supplier Delivery State']) {
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
				$state=$data['Supplier Delivery State'];
				break;
			}

			$adata[]=array(
				'id'=>(integer)$data['Supplier Delivery Key'],
				'parent_key'=> (integer) $data['Supplier Delivery Parent Key'],
				'public_id'=>$data['Supplier Delivery Public ID'],
				'date'=>strftime("%e %b %Y", strtotime($data['Supplier Delivery Creation Date'].' +0:00')),
				'last_date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Supplier Delivery Last Updated Date'].' +0:00')),
				'parent_name'=>$data['Supplier Delivery Parent Name'],
				'state'=>$state,

				'total_amount'=>money($data['Supplier Delivery Total Amount'], $data['Supplier Delivery Currency Code'])


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

	include_once 'class.PurchaseOrder.php';
	$purchase_order=new PurchaseOrder($_data['parameters']['parent_key']);

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {


			$quantity=number($data['Purchase Order Quantity']);



			$units_per_carton=$data['Supplier Part Units Per Package']*$data['Supplier Part Packages Per Carton'];


			$subtotals=sprintf('<span  class="subtotals" >');
			if ($data['Purchase Order Quantity']>0) {
				$subtotals.=money($data['Purchase Order Quantity']*$units_per_carton*$data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code'));

				if ($data['Part Package Weight']>0) {
					$subtotals.=' '.weight($data['Part Package Weight']*$data['Purchase Order Quantity']*$data['Supplier Part Packages Per Carton']);
				}
				if ($data['Supplier Part Carton CBM']>0) {
					$subtotals.=' '.number($data['Purchase Order Quantity']*$data['Supplier Part Carton CBM']).' m³';
				}
			}
			$subtotals.='</span>';


			if (!$data['Supplier Delivery Key']) {

				$delivery_qty=$data['Purchase Order Quantity'];

				$delivery_quantity=sprintf('<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
					$data['Purchase Order Transaction Fact Key'],
					$data['Purchase Order Transaction Fact Key'],
					$data['Supplier Part Key'],
					$data['Supplier Part Historic Key'],
					$delivery_qty+0,
					$delivery_qty+0
				);
			}else {
				$delivery_quantity=number($data['Purchase Order Delivery Quantity']);

			}

			$adata[]=array(

				'id'=>(integer)$data['Purchase Order Transaction Fact Key'],
				'item_index'=>$data['Purchase Order Item Index'],
				'parent_key'=>$purchase_order->get('Purchase Order Parent Key'),
				'parent_type'=>strtolower($purchase_order->get('Purchase Order Parent')),
				'supplier_part_key'=>(integer)$data['Supplier Part Key'],
				'checkbox'=>sprintf('<i key="%d" class="fa fa-fw fa-square-o button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']),

				'operations'=>sprintf('<i key="%d" class="fa fa-fw fa-truck fa-flip-horizontal button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']),

				'reference'=>$data['Supplier Part Reference'],
				'description'=>$data['Part Unit Description'].' ('.number($units_per_carton).'/C)',
				'quantity'=>sprintf('<span item_key="%d" item_historic_key=%d ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw button" aria-hidden="true"></i></span>',
					$data['Supplier Part Key'],
					$data['Supplier Part Historic Key'],
					$data['Purchase Order Quantity']+0,
					$data['Purchase Order Quantity']+0
				),
				'delivery_quantity'=>$delivery_quantity,
				'subtotals'=>$subtotals,
				'ordered'=>number($data['Purchase Order Quantity'])


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


function delivery_items($_data, $db, $user) {


	$rtext_label='item';

	include_once 'class.PurchaseOrder.php';
	$purchase_order=new PurchaseOrder($_data['parameters']['parent_key']);

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {


			$quantity=number($data['Supplier Delivery Quantity']);



			$units_per_carton=$data['Supplier Part Units Per Package']*$data['Supplier Part Packages Per Carton'];


			$subtotals=sprintf('<span  class="subtotals" >');
			if ($data['Supplier Delivery Quantity']>0) {
				$subtotals.=money($data['Supplier Delivery Net Amount'], $data['Currency Code']);

				if ($data['Supplier Delivery Weight']>0) {
					$subtotals.=' '.weight($data['Supplier Delivery Weight']);
				}
				if ($data['Supplier Delivery CBM']>0) {
					$subtotals.=' '.number($data['Supplier Delivery CBM']).' m³';
				}
			}
			$subtotals.='</span>';




			$delivery_quantity=sprintf('<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
				$data['Purchase Order Transaction Fact Key'],
				$data['Purchase Order Transaction Fact Key'],
				$data['Supplier Part Key'],
				$data['Supplier Part Historic Key'],
				$quantity+0,
				$quantity+0
			);


			$adata[]=array(

				'id'=>(integer)$data['Purchase Order Transaction Fact Key'],
				'supplier_part_key'=>(integer)$data['Supplier Part Key'],
				'checkbox'=>sprintf('<i key="%d" class="fa fa-fw fa-square-o button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']),

				'operations'=>sprintf('<i key="%d" class="fa fa-fw fa-truck fa-flip-horizontal button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']),

				'reference'=>$data['Supplier Part Reference'],
				'description'=>$data['Part Unit Description'].' ('.number($units_per_carton).'/C)',

				'quantity'=>$delivery_quantity,
				'subtotals'=>$subtotals,
				'ordered'=>number($data['Purchase Order Quantity']),
				'qty'=>number($quantity)

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


function delivery_checking_items($_data, $db, $user) {


	$rtext_label='item';

	include_once 'class.PurchaseOrder.php';
	$purchase_order=new PurchaseOrder($_data['parameters']['parent_key']);

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {


			$quantity=number($data['Supplier Delivery Quantity']);



			$units_per_carton=$data['Supplier Part Units Per Package']*$data['Supplier Part Packages Per Carton'];


			$subtotals=sprintf('<span  class="subtotals" >');
			if ($data['Supplier Delivery Quantity']>0) {
				$subtotals.=money($data['Supplier Delivery Net Amount'], $data['Currency Code']);

				if ($data['Supplier Delivery Weight']>0) {
					$subtotals.=' '.weight($data['Supplier Delivery Weight']);
				}
				if ($data['Supplier Delivery CBM']>0) {
					$subtotals.=' '.number($data['Supplier Delivery CBM']).' m³';
				}
			}
			$subtotals.='</span>';


			$description=$data['Part Package Description'].' ('.number($units_per_carton).'/'.number($data['Supplier Part Packages Per Carton']).'/C)';

			$locations_data=preg_split('/,/', $data['location_data']);

			$locations='<div  class="part_locations mini_table left hide" transaction_key="'.$data['Purchase Order Transaction Fact Key'].'" >';
			$number_locations=0;
			foreach ($locations_data as $location_data) {
				$number_locations++;
				$location_data=preg_split('/\:/', $location_data);
				$locations.='<div class="button" style="clear:both;" onClick="set_placement_location(this)"  location_key="'.$location_data[0].'" >
				<div  class="code data w150"  >'.$location_data[1].'</div>
				<div class="data w30 aright" >'.number($location_data[3]).'</div>
				</div>';

			}
			$locations.='<div style="clear:both"></div></div>';



			$description.=' <i style="margin-left:4px" class="fa fa-map-marker button discreet '.($number_locations==0?'hide':'').'" aria-hidden="true" title="'._('Show locations').'"  show_title="'._('Show locations').'" hide_title="'._('Hide locations').'"    onClick="show_part_locations(this)" ></i>';



			$description.= $locations;

			$delivery_quantity=sprintf('<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
				$data['Purchase Order Transaction Fact Key'],
				$data['Purchase Order Transaction Fact Key'],
				$data['Supplier Part Key'],
				$data['Supplier Part Historic Key'],
				$quantity+0,
				$quantity+0
			);


			$sko_checked_quantity=$data['Supplier Delivery Received Quantity']*$data['Supplier Part Packages Per Carton'];
			$edit_sko_checked_quantity=sprintf('<span data-settings=\'{"field": "Supplier Delivery Received Quantity", "transaction_key":%d,"item_key":%d, "item_historic_key":%d ,"on":1 }\' class="delivery_quantity" id="delivery_quantity_%d" ><input class="received_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-cloud fa-fw button %s" aria-hidden="true"></span>',
				$data['Purchase Order Transaction Fact Key'],
				$data['Supplier Part Key'],
				$data['Supplier Part Historic Key'],
				$data['Purchase Order Transaction Fact Key'],

				$sko_checked_quantity+0,
				$sko_checked_quantity+0,
				($data['Supplier Delivery Received Quantity']==''?'':'invisible')
			);

			$quantity=($data['Supplier Delivery Received Quantity']-$data['Supplier Delivery Placed Quantity'])*$data['Supplier Part Packages Per Carton'];


			if ($data['Metadata']=='') {
				$metadata=array();
			}else {
				$metadata=json_decode($data['Metadata'], true);
			}

			$placement='<div  class="placement_data mini_table right no_padding" style="padding-right:2px">';
			if (  isset($metadata['placement_data'])) {

				foreach ($metadata['placement_data'] as $placement_data) {
					$placement.='<div style="clear:both;">
				<div class="data w150 aright link" onClick="change_view(\'\')" >'.$placement_data['l'].'</div>
				<div  class=" data w75 aleft"  >'.$placement_data['qty'].' '._('SKO').' <i class="fa fa-sign-out" aria-hidden="true"></i></div>
				</div>';


				}
			}
			$placement.='<div style="clear:both"></div></div>';


			$placement.='
			    <div style="clear:both"  id="place_item_'.$data['Purchase Order Transaction Fact Key'].'" class="place_item '.($data['Supplier Delivery Transaction Placed']=='No'?'':'hide').' " part_sku="'.$data['Part SKU'].'" transaction_key="'.$data['Purchase Order Transaction Fact Key'].'"  >
			    <input class="place_qty width_50 changed" value="'.($quantity+0).'" ovalue="'.($quantity+0).'"  min="1" max="'.$quantity.'"  >
				<input class="location_code"  placeholder="'._('Location code').'"  >
				<i  class="fa  fa-cloud  fa-fw save " aria-hidden="true" title="'._('Place to location').'"  location_key="" onClick="place_item(this)"  ></i>
                <div>

			';


			$adata[]=array(

				'id'=>(integer)$data['Purchase Order Transaction Fact Key'],
				'supplier_part_key'=>(integer)$data['Supplier Part Key'],
				'part_sku'=>(integer)$data['Part SKU'],
				'checkbox'=>sprintf('<i key="%d" class="fa fa-fw fa-square-o button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']),

				'operations'=>sprintf('<i key="%d" class="fa fa-fw fa-truck fa-flip-horizontal button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']),

				'reference'=>$data['Supplier Part Reference'],
				'part_reference'=>$data['Part Reference'],
				'description'=>$description,

				'sko_edit_checked_quantity'=>$edit_sko_checked_quantity,
				'sko_checked_quantity'=>number($sko_checked_quantity),

				'subtotals'=>$subtotals,
				'ordered'=>number($data['Purchase Order Quantity']),
				'qty'=>number($quantity),
				'c_sko_u'=>sprintf('<span data-metadata=\'{"qty":%d}\' onClick="copy_qty(this)" class="button"><span class="very_discreet">%s/</span> <span>%s</span> <span class="super_discreet">/%s</span></span>', $data['Supplier Part Packages Per Carton']*$data['Supplier Delivery Quantity'], number($data['Supplier Delivery Quantity']), number($data['Supplier Part Packages Per Carton']*$data['Supplier Delivery Quantity']), number($data['Supplier Part Packages Per Carton']*$data['Supplier Part Units Per Package']*$data['Supplier Delivery Quantity'])),
				'placement'=>$placement
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

	print $sql;


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


function category_all_suppliers($_data, $db, $user, $account) {


	$rtext_label='supplier';

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	$adata=array();
	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			if ($data['associated'])
				$associated=sprintf('<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Supplier Key']);
			else
				$associated=sprintf('<i key="%d" class="fa fa-fw fa-unlink button very_discreet" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Supplier Key']);


			$adata[]=array(
				'id'=>(integer) $data['Supplier Key'],
				'operations'=>$associated,
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


function order_supplier_all_parts($_data, $db, $user) {

	include_once 'class.PurchaseOrder.php';

	$rtext_label='supplier part';


	$purchase_order=new PurchaseOrder($_data['parameters']['parent_key']);

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


			$units_per_carton=$data['Supplier Part Units Per Package']*$data['Supplier Part Packages Per Carton'];

			$subtotals=sprintf('<span  class="subtotals" >');
			if ($data['Purchase Order Quantity']>0) {
				$subtotals=money($data['Purchase Order Quantity']*$units_per_carton*$data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code'));

				if ($data['Part Package Weight']>0) {
					$subtotals.=' '.weight($data['Part Package Weight']*$data['Purchase Order Quantity']*$data['Supplier Part Packages Per Carton']);
				}
				if ($data['Supplier Part Carton CBM']>0) {
					$subtotals.=' '.number($data['Purchase Order Quantity']*$data['Supplier Part Carton CBM']).' m³';
				}
			}
			$subtotals.='</span>';

			$transaction_key='';

			$adata[]=array(
				'id'=>(integer)$data['Supplier Part Key'],
				'supplier_key'=>(integer)$data['Supplier Part Supplier Key'],
				'supplier_code'=>$data['Supplier Code'],
				'part_key'=>(integer)$data['Supplier Part Part SKU'],
				'part_reference'=>$data['Part Reference'],
				'parent_key'=>$purchase_order->get('Purchase Order Parent Key'),
				'parent_type'=>strtolower($purchase_order->get('Purchase Order Parent')),
				'reference'=>$data['Supplier Part Reference'],
				'formatted_sku'=>sprintf("SKU%05d", $data['Supplier Part Part SKU']),
				'part_description'=>'<span style="min-width:80px;display: inline-block;" class="link padding_right_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$data['Part Reference'].'</span> '.$data['Part Unit Description'],

				'description'=>$data['Part Unit Description'].' <span class="discreet">('.number($units_per_carton).'/C '.money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')).')</span>',
				'status'=>$status,
				'cost'=>money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
				'packing'=>'<div style="float:left;min-width:20px;text-align:right"><span>'.$data['Supplier Part Units Per Package'].'</span></div><div style="float:left;min-width:70px;text-align:left"> <i  class="fa fa-arrow-right very_discret padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div> <span class="discret">'.($data['Supplier Part Units Per Package']*$data['Supplier Part Packages Per Carton'].'</span>'),
				'stock'=>number(floor($data['Part Current Stock']))." $stock_status",
				'quantity'=>sprintf('<span    data-settings=\'{"field": "Purchase Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw button" aria-hidden="true"></i></span>',
					$transaction_key,
					$data['Supplier Part Key'],
					$data['Supplier Part Historic Key'],
					($data['Purchase Order Quantity']==0?'':$data['Purchase Order Quantity']+0),
					($data['Purchase Order Quantity']==0?'':$data['Purchase Order Quantity']+0)
				),
				'subtotals'=>$subtotals


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
