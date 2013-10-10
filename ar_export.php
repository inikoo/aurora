<?php
require_once 'common.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];

$export_method='gearman';//or 'gearman';


switch ($tipo) {

case('get_wait_info'):
	$data=prepare_values($_REQUEST,array(
			'fork_key'=>array('type'=>'key'),
			'table'=>array('type'=>'string')
		));
	get_wait_info($data);
	break;
case('export'):

	$data=prepare_values($_REQUEST,array(
			'table'=>array('type'=>'string'),
			'output'=>array('type'=>'enum','valid values regex'=>'/csv|xls/i'),
			'fields'=>array('type'=>'string'),
			'date'=>array('type'=>'string','optional'=>true)

		));
	export($data);
	break;
default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);

}


function export($data) {
	global $account_code,$export_method;
	include 'splinters/new_fork.php';
	$user=$data['user'];
	list ($sql_count,$sql_data,$fetch_type)=get_sql_query($_REQUEST);

	$export_data=array(
		'table'=>$data['table'],
		'output'=>$data['output'],
		'user_key'=>$user->id,
		'sql_count'=>$sql_count,
		'sql_data'=>$sql_data,
		'fetch_type'=>$fetch_type
	);

	list($fork_key,$msg)=new_fork('export',$export_data,$account_code);


	$response= array(
		'state'=>200,'fork_key'=>$fork_key,'msg'=>$msg,'table'=>$data['table']
	);
	echo json_encode($response);

}

function get_wait_info($data) {

	$fork_key=$data['fork_key'];
	$sql=sprintf("select `Fork Result`,`Fork Scheduled Date`,`Fork Start Date`,`Fork State`,`Fork Operations Done`,`Fork Operations No Changed`,`Fork Operations Errors`,`Fork Operations Total Operations` from `Fork Dimension` where `Fork Key`=%d ",
		$fork_key);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		if ($row['Fork State']=='In Process')
			$msg=number($row['Fork Operations Done']+$row['Fork Operations Errors']+$row['Fork Operations No Changed']).'/'.$row['Fork Operations Total Operations'];
		elseif ($row['Fork State']=='Queued')
			$msg=_('Queued');
		else
			$msg='';

		$result_info=number($row['Fork Operations Done']);

		switch ($data['table']) {
		case 'customers':
			$result_info.=' '.ngettext('customer','customers',$row['Fork Operations Done']);
			break;
		case 'orders':
			$result_info.=' '.ngettext('order','orders',$row['Fork Operations Done']);
			break;
		case 'invoices':
			$result_info.=' '.ngettext('invoice','invoices',$row['Fork Operations Done']);
			break;
		case 'dn':
			$result_info.=' '.ngettext('delivery note','delivery notes',$row['Fork Operations Done']);
			break;
		case 'parts':
			$result_info.=' '.ngettext('part','parts',$row['Fork Operations Done']);
			break;
		case 'products':
			$result_info.=' '.ngettext('product','products',$row['Fork Operations Done']);
			break;
		case 'families':
			$result_info.=' '.ngettext('family','families',$row['Fork Operations Done']);
			break;
		case 'departments':
			$result_info.=' '.ngettext('department','departments',$row['Fork Operations Done']);
			break;
		case 'pages':
			$result_info.=' '.ngettext('page','pages',$row['Fork Operations Done']);
			break;
		default:
			$result_info.=' '.ngettext('record','records',$row['Fork Operations Done']);

		}

		$response= array(
			'state'=>200,
			'fork_key'=>$fork_key,
			'fork_state'=>$row['Fork State'],
			'done'=>$row['Fork Operations Done'],
			'no_changed'=>$row['Fork Operations No Changed'],
			'errors'=>$row['Fork Operations Errors'],
			'total'=>$row['Fork Operations Total Operations'],
			'result'=>$row['Fork Result'],
			'msg'=>$msg,
			'progress'=>sprintf('%s/%s (%s)',number($row['Fork Operations Done']),number($row['Fork Operations Total Operations']),percentage($row['Fork Operations Done'],$row['Fork Operations Total Operations'])),
			'table'=>$data['table'],
			'result_info'=>$result_info

		);
		echo json_encode($response);

	}else {
		$response= array(
			'state'=>400,

		);
		echo json_encode($response);

	}

}



function get_sql_query($data) {
	//print_r($data);

	switch ($data['table']) {
	case 'customers':
		return customers_sql_query($data);
		break;
	case 'parts':
		return parts_sql_query($data);
		break;
	case 'products':
		return products_sql_query($data);
		break;
	case 'families':
		return families_sql_query($data);
		break;
	case 'departments':
		return departments_sql_query($data);
		break;
	case 'pages':
		return pages_sql_query($data);
		break;
	case 'orders':
		return orders_sql_query($data);
		break;
	case 'invoices':
		return invoices_sql_query($data);
		break;
	case 'locations':
		return locations_sql_query($data);
		break;
	case 'part_locations':
		return part_locations_sql_query($data);
		break;	
	case 'dn':
		return dn_sql_query($data);
		break; case 'part_stock_historic':
		return part_stock_historic_sql_query($data);
		break;
	default:
		return false;
	}
}

function part_stock_historic_sql_query($data) {

	$fetch_type='simple';
	$group='';
	$wheref='';
	switch ($data['parent']) {
	case 'none':
		$where=sprintf("where  `Date`=%s  ",prepare_mysql($data['date']));
		$table='`Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)';
		break;
	case 'warehouse':

		$where=sprintf("where `Warehouse key`=%d and `Date`=%s  ",$data['parent_key'],prepare_mysql($data['date']));
		$table='`Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)';


		break;

	default;
		$where.='false';
	}
	$sql_count="select count(Distinct P.`Part SKU`) as num from `Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)  $where $wheref";

	$data['fields']=preg_replace('/value_at_end_day/','sum(`Value At Day Cost`) as value_at_end_day',$data['fields']);
	$data['fields']=preg_replace('/locations/','count(DISTINCT `Location Key`) as locations',$data['fields']);
	$data['fields']=preg_replace('/value_at_cost/','sum(`Value At Cost`) as value_at_cost',$data['fields']);
	$data['fields']=preg_replace('/stock/','sum(`Quantity On Hand`) as stock',$data['fields']);
	$data['fields']=preg_replace('/commercial_value/','sum(`Value Commercial`) as commercial_value',$data['fields']);
	$data['fields']=	addslashes($data['fields']);
	
	$data['fields']=preg_replace('/delta_last_sold/',sprintf('DATEDIFF(`Part Last Sale Date`,%s) as delta_last_sold',prepare_mysql($data['date'])),$data['fields']);
	$data['fields']=preg_replace('/delta_last_booked_in/',sprintf('DATEDIFF(`Part Last Booked In Date`,%s) as delta_last_booked_in',prepare_mysql($data['date'])),$data['fields']);
	$data['fields']=preg_replace('/delta_last_purchased/',sprintf('DATEDIFF(`Part Last Purchase Date`,%s) as delta_last_purchased',prepare_mysql($data['date'])),$data['fields']);

	//print $data['fields'];
//	$sql_data="select sum(`Quantity On Hand`) as stock,sum(`Quantity Open`) as stock_open,sum(`Value At Cost`) as value_at_cost,sum(`Value Commercial`) as commercial_value from `Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)  $where $wheref group by ISF.`Part SKU`   order by ISF.`Part SKU` ";

	$sql_data=sprintf("select %s from `Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)  $where $wheref group by ISF.`Part SKU`   order by ISF.`Part SKU` ",
		$data['fields']
	);
	/*
	$sql_data=sprintf("select %s from %s %s %s",
		addslashes($data['fields']),
		$table,
		$where,
		$group
	);*/
	//print $sql_data;
	//exit;
	return array($sql_count,$sql_data,$fetch_type);
}

function customers_sql_query($data) {

	include_once 'class.Store.php';
	global $user;
	$fetch_type='simple';




	$parent_key=$data['parent_key'];
	$parent=$data['parent'];

	switch ($parent) {
	case 'store':
		$conf_table='customers';
		break;
	case 'category':
		$conf_table='customer_categories';
		break;
	case 'list':
		$conf_table='customers_list';
		break;
	}

	$conf=$_SESSION['state'][$conf_table]['customers'];
	$orders_type=$_SESSION['state'][$conf_table]['customers']['orders_type'];

	$elements_type=$_SESSION['state'][$conf_table]['customers']['elements_type'];
	$elements=$conf['elements'];
	$f_field=$conf['f_field'];
	$f_value=$conf['f_value'];
	$awhere='';

	include_once 'splinters/customers_prepare_list.php';


	$sql_count="select count(Distinct C.`Customer Key`)         as num from $table   $where $wheref $where_type";

	$data['fields']=addslashes($data['fields']);
	$data['fields']=preg_replace('/`Customer Address`/','REPLACE(`Customer Main XHTML Address`,"<br/>","\n") as`Customer Address`',$data['fields']);
	$data['fields']=preg_replace('/`Customer Billing Address`/','REPLACE(`Customer XHTML Billing Address`,"<br/>","\n") as`Customer Billing Address`',$data['fields']);
	$data['fields']=preg_replace('/`Customer Delivery Address`/','REPLACE(`Customer XHTML Main Delivery Address`,"<br/>","\n") as`Customer Delivery Address`',$data['fields']);
	$data['fields']=preg_replace('/Customer Address Elements/','`Customer Main Town`,`Customer Main Postal Code`,`Customer Main Country First Division`,`Customer Main Country Code`',$data['fields']);
	$data['fields']=preg_replace('/Customer Billing Address Elements/','`Customer Billing Address Town`,`Customer Billing Address Country Code`',$data['fields']);
	$data['fields']=preg_replace('/Customer Delivery Address Elements/','`Customer Main Delivery Address Town`,`Customer Main Delivery Address Postal Code`,`Customer Main Delivery Address Region`,`Customer Main Delivery Address Country Code`',$data['fields']);

	$sql_data="select ".$data['fields']." from $table   $where $wheref $where_type $group_by"
	;
	//print $sql_data;

	return array($sql_count,$sql_data,$fetch_type);
}

function parts_sql_query($data) {

	global $user;
	$fetch_type='simple';

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];
	if ($parent=='category') {
		$conf_node='part_categories';
	}elseif ($parent=='list') {
		$conf_node='parts_list';
	}else {
		$conf_node='warehouse';
	}
	$conf=$_SESSION['state'][$conf_node]['parts'];

	$elements_type=$conf['elements_type'];

	$elements=$conf['elements'];
	$f_field=$conf['f_field'];
	$f_value=$conf['f_value'];
	$awhere='';

	include_once 'splinters/parts_prepare_list.php';

	$sql_count="select count(Distinct P.`Part SKU`) as num from $table $where $wheref";
	$fields=addslashes($data['fields']);
	$sql_data="select $fields from $table $where $wheref";

	return array($sql_count,$sql_data,$fetch_type);
}

function orders_sql_query($data) {

	global $user;
	$fetch_type='simple';

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];
	if ($parent=='category') {
		$conf_node='orders';
	}elseif ($parent=='list') {
		$conf_node='orders';
	}else {
		$conf_node='orders';
	}
	$conf=$_SESSION['state'][$conf_node]['orders'];

	$elements_type=$conf['elements_type'];

	$elements=$conf['elements'];
	$f_field=$conf['f_field'];
	$f_value=$conf['f_value'];
	$awhere='';

	$to=$_SESSION['state']['orders']['to'];
	$from=$_SESSION['state']['orders']['from'];

	include_once 'splinters/orders_prepare_list.php';

	$sql_count="select count(Distinct O.`Order Key`) as num from $table $where $wheref";
	$fields=addslashes($data['fields']);
	$sql_data="select $fields from $table $where $wheref";

	return array($sql_count,$sql_data,$fetch_type);
}

function invoices_sql_query($data) {

	global $user;
	$fetch_type='simple';

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];
	if ($parent=='category') {
		$conf_node='orders';
	}elseif ($parent=='list') {
		$conf_node='orders';
	}else {
		$conf_node='orders';
	}
	$conf=$_SESSION['state'][$conf_node]['invoices'];

	$elements_type=$conf['elements_type'];

	$elements=$conf['elements'];
	$f_field=$conf['f_field'];
	$f_value=$conf['f_value'];
	$awhere='';

	$to=$_SESSION['state']['orders']['to'];
	$from=$_SESSION['state']['orders']['from'];

	include_once 'splinters/invoices_prepare_list.php';

	$sql_count="select count(Distinct I.`Invoice Key`) as num from $table $where $wheref";
	$fields=addslashes($data['fields']);
	$sql_data="select $fields from $table $where $wheref";

	return array($sql_count,$sql_data,$fetch_type);
}

function dn_sql_query($data) {

	global $user;
	$fetch_type='simple';

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];
	if ($parent=='category') {
		$conf_node='orders';
	}elseif ($parent=='list') {
		$conf_node='orders';
	}else {
		$conf_node='orders';
	}
	$conf=$_SESSION['state'][$conf_node]['dn'];

	$elements_type=$conf['elements_type'];

	$elements=$conf['elements'];
	$f_field=$conf['f_field'];
	$f_value=$conf['f_value'];
	$awhere='';

	$to=$_SESSION['state']['orders']['to'];
	$from=$_SESSION['state']['orders']['from'];

	include_once 'splinters/dn_prepare_list.php';

	$sql_count="select count(Distinct D.`Delivery Note Key`) as num from $table $where $wheref";
	$fields=addslashes($data['fields']);
	$sql_data="select $fields from $table $where $wheref";

	return array($sql_count,$sql_data,$fetch_type);
}



function locations_sql_query($data) {

	global $user;
	$fetch_type='simple';

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];
	if ($parent=='warehouse') {
		$conf_node='warehouse';
	}elseif ($parent=='warehouse_area') {
		$conf_node='warehouse_area';
	}
	
	$conf=$_SESSION['state'][$conf_node]['locations'];

//	$elements_type=$conf['elements'];

	$elements=$conf['elements'];
	$f_field=$conf['f_field'];
	$f_value=$conf['f_value'];
	$awhere='';

	$to=$_SESSION['state']['orders']['to'];
	$from=$_SESSION['state']['orders']['from'];

	include_once 'splinters/locations_prepare_list.php';
	$sql_count="select count(*) as num from `Location Dimension`    $where $wheref";
	$fields=addslashes($data['fields']);
	$sql_data="select $fields from `Location Dimension`  $where $wheref";

	return array($sql_count,$sql_data,$fetch_type);
}

function part_locations_sql_query($data) {

	global $user;
	$fetch_type='simple';

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];
	if ($parent=='warehouse') {
		$conf_node='warehouse';
	}elseif ($parent=='warehouse_area') {
		$conf_node='warehouse_area';
	}
	
	$conf=$_SESSION['state'][$conf_node]['part_locations'];

//	$elements_type=$conf['elements'];

	//$elements=$conf['elements'];
	$f_field=$conf['f_field'];
	$f_value=$conf['f_value'];
	$awhere='';

	$to=$_SESSION['state']['orders']['to'];
	$from=$_SESSION['state']['orders']['from'];

	include_once 'splinters/part_locations_prepare_list.php';
	$sql_count="select count(*) as num from  `Part Location Dimension` PL left join `Location Dimension` L on (PL.`Location Key`=L.`Location Key`) left join `Part Dimension` P on (PL.`Part SKU`=P.`Part SKU`)    $where $wheref";
	$fields=addslashes($data['fields']);
	$sql_data="select $fields from  `Part Location Dimension` PL left join `Location Dimension` L on (PL.`Location Key`=L.`Location Key`) left join `Part Dimension` P on (PL.`Part SKU`=P.`Part SKU`)    $where $wheref";

	return array($sql_count,$sql_data,$fetch_type);
}

