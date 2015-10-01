<?php
require_once 'common.php';

require_once 'class.Warehouse.php';
require_once 'class.WarehouseArea.php';
require_once 'class.PartLocation.php';
require_once 'class.ShelfType.php';
require_once 'class.Location.php';
require_once 'class.Product.php';
require_once 'class.Department.php';
require_once 'class.Family.php';

require_once 'ar_edit_common.php';

//print_r($editor);

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}



$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case('edit_location_flags'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'okey'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'newvalue'=>array('type'=>'string')
		));
	edit_location_flags($data);
	break;
case('part_location_update_can_pick'):
	$data=prepare_values($_REQUEST,array(
			'sku'=>array('type'=>'key'),
			'location_key'=>array('type'=>'key'),
			'can_pick'=>array('type'=>'string')
		));
	part_location_update_can_pick($data);
	break;
case('audit_stock'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	audit_stock($data);
	break;
case('add_stock'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	add_stock($data);
	break;
case('save_description'):
	save_description();
	break;

case('add_part_to_location'):
	$data=prepare_values($_REQUEST,array(
			'location_key'=>array('type'=>'key'),
			'part_sku'=>array('type'=>'key'),


		));
	add_part_to_location($data);
	break;

case ('update_move_qty'):
	$data=prepare_values($_REQUEST,array(
			'location_key'=>array('type'=>'key'),
			'part_sku'=>array('type'=>'key'),
			'move_qty'=>array('type'=>'string'),

		));



	update_move_qty($data);
	break;
case('update_save_picking_location_quantity_limits'):
	$data=prepare_values($_REQUEST,array(
			'location_key'=>array('type'=>'key'),
			'part_sku'=>array('type'=>'key'),
			'newvalue_max'=>array('type'=>'string'),
			'newvalue_min'=>array('type'=>'string')

		));



	update_save_picking_location_quantity_limits($data);
	break;
case('new_area'):
	new_warehouse_area();
	break;

case('new_shelf_type'):
	new_shelf_type();
	break;

case('new_shelf'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	new_shelf($data);
	break;
case('new_location'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));

	new_location($data);
	break;
case('edit_warehouse_areas'):
case('warehouse_areas'):
	list_warehouse_areas_for_edition();
	break;
case('edit_locations'):
	list_locations_for_edition();
	break;
case('shelf_types'):
	list_shelf_types_for_edition();
	break;
case('edit_location_description'):
	$data=prepare_values($_REQUEST,array(
			'location_key'=>array('type'=>'key'),
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'okey'=>array('type'=>'string'),

			'table_record_index'=>array('type'=>'numeric','optional'=>true)

		));



	update_location($data);
	break;
case('hedit_warehouse_area'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'id'=>array('type'=>'key')
		));
	//update_warehouse_area($data);
	break;
case('edit_part_location'):
	update_part_location();
	break;

case('edit_shelf_type'):
	update_shelf_type();
	break;
case('edit_shelf'):
	update_shelf();
	break;

case('edit_shelf_location_type'):
	update_shelf_location_type();
	break;


case('delete_warehouse_area'):
	delete_warehouse_area();
	break;
case('delete_location'):
	delete_location();
	break;
case('delete_part_location'):
	delete_part_location();
	break;
case('delete_warehouse'):
	delete_warehouse();
	break;
case('lost_stock'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	lost_stock($data);
	break;
case('move_stock'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	move_stock($data);
	break;
case('locations'):
	list_locations();
	break;
case('edit_po_settings'):
case('edit_warehouse'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'okey'=>array('type'=>'string'),
			'id'=>array('type'=>'key')
		));

	edit_warehouse($data);
	break;
case('edit_warehouse_area'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'wa_key'=>array('type'=>'key')
		));

	edit_warehouse_area($data);
	break;
case('edit_location_area'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'id'=>array('type'=>'key')
		));

	edit_location_area($data);
	break;
default:

	$response=array('state'=>404,'msg'=>_('Operation not found'));
	echo json_encode($response);

}

function save_description() {

	$warehouse_key = $_REQUEST['key'];
	$warehouse_code = $_REQUEST['code'];
	$warehouse_name = $_REQUEST['name'];
	if (!isset($_REQUEST['key'])
		or !isset($_REQUEST['code'])
		or !isset($_REQUEST['code'])
	) {
		$response=array('state'=>400,'action'=>'error','msg'=>'');
		echo json_encode($response);
		return;
	}

	if (trim($warehouse_code) == '' or trim($warehouse_name) == '') {
		echo json_encode('error');
		exit;
	}

	$sql_warehouse = "UPDATE `Warehouse Dimension` SET `Warehouse Code` = '$warehouse_code', `Warehouse Name` = '$warehouse_name' WHERE `Warehouse Dimension`.`Warehouse Key` = '$warehouse_key'";

	$query_warehouse = mysql_query($sql_warehouse);
	if (!query_warehouse) {
		echo json_encode('Problem in editing');
		exit;
	}

	if (mysql_affected_rows() > 0) {
		echo json_encode('Warehouse edited successfully.');
	}

}
function edit_warehouse_area($data) {
	//print $data['newvalue'];


	$warehouse=new WarehouseArea($data['wa_key']);
	global $editor;
	$warehouse->editor=$editor;

	$translator=array(
		'name'=>'Warehouse Area Name',
		'code'=>'Warehouse Area Code',
		'description'=>'Warehouse Area Description'
	);


	$key=$data['key'];

	if (array_key_exists($key, $translator)) {
		$db_field=$translator[$key];
	}else {
		$db_field=$jey;
	}


	$update_data=array($db_field=>stripslashes(urldecode($data['newvalue'])));


	$warehouse->update($update_data);
	if ($warehouse->updated) {
		if ($data['key']=='Warehouse Area Code')
			$data['key']='warehouse_area_code';
		if ($data['key']=='Warehouse Area Name')
			$data['key']='warehouse_area_name';
		$response= array('state'=>200,'newvalue'=>$warehouse->new_value,'key'=>$data['key']);

	} else {
		$response= array('state'=>400,'msg'=>$warehouse->msg,'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);
}

function edit_location_area($data) {
	//print $data['newvalue'];


	$location=new Location($data['id']);
	global $editor;
	$location->editor=$editor;

	$translator=array(
		'location_key'=>'Location Warehouse Area Key',
		'warehouse_code'=>'Warehouse Code'

	);
	//print_r($data);
	foreach ($data as $key=>$value) {
		if (array_key_exists($key, $translator)) {
			$data[$translator[$key]]=$value;
			print $translator[$key].":".$value;
		}
	}



	$location->update(array($data['key']=>stripslashes(urldecode($data['newvalue']))));
	if ($location->updated) {
		if ($data['key']=='Warehouse Area Code')
			$data['key']='warehouse_area_code';
		if ($data['key']=='Warehouse Area Name')
			$data['key']='warehouse_area_name';
		$response= array('state'=>200,'newvalue'=>$location->new_value,'key'=>$data['key']);

	} else {
		$response= array('state'=>400,'msg'=>$location->msg,'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);
}

function edit_location_flags($data) {
	$warehouse=new warehouse($data['id']);

	if (!$warehouse->id) {
		$response=array('state'=>400,'action'=>'nochange','msg'=>'warehouse not found');
		echo json_encode($response);
		return;
	}

	global $editor;
	$warehouse->editor=$editor;
	if (preg_match('/\d+$/', $data['okey'],$match)) {
		$flag_key=$match[0];
		$field=$data['key'];
		$value=$data['newvalue'];
		$warehouse->update_flag($flag_key,$field,$value);

		if (!$warehouse->error) {
			$response= array('state'=>200,'newvalue'=>$warehouse->new_value,'key'=>$data['okey']);
			echo json_encode($response);
			return;
		}else {
			$response=array('state'=>400,'action'=>'nochange','msg'=>$warehouse->msg);
			echo json_encode($response);
			return;
		}


	}else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>'no flag key');
		echo json_encode($response);
		return;
	}
}


function edit_warehouse($data) {
	//print $data['newvalue'];


	$warehouse=new warehouse($data['id']);
	global $editor;
	$warehouse->editor=$editor;

/*
	$translator=array(
		'warehouse_name'=>'Warehouse Name',
		'warehouse_code'=>'Warehouse Code'

	);

	foreach ($data as $key=>$value) {
		if (array_key_exists($key, $translator)) {
			$data[$translator[$key]]=$value;
			print $translator[$key].":".$value;
		}
	}

*/

	$warehouse->update(array($data['key']=>stripslashes(urldecode($data['newvalue']))));
	if ($warehouse->updated) {
		if ($data['key']=='Warehouse Code')
			$data['key']='warehouse_code';
		if ($data['key']=='Warehouse Name')
			$data['key']='warehouse_name';
		$response= array('state'=>200,'newvalue'=>$warehouse->new_value,'key'=>$data['okey']);

	} else {
		$response= array('state'=>400,'msg'=>$warehouse->msg,'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);
}

function part_location_update_can_pick($data) {
	global $editor;
	$part_sku=$data['sku'];
	$location_key=$data['location_key'];
	$can_pick=$data['can_pick'];

	$part_location=new PartLocation($part_sku,$location_key);
	$part_location->editor=$editor;
	$part_location->update_can_pick($can_pick);
	if ($part_location->updated) {
		$response=array(
			'state'=>200,
			'action'=>'updated',
			'msg'=>$part_location->msg,
			'can_pick'=>$part_location->data['Can Pick'],
			'location_key'=>$part_location->location_key,
			'sku'=>$part_location->part_sku,
		);
		echo json_encode($response);
		return;
	} else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
		echo json_encode($response);
		return;

	}

}

function audit_stock($data) {
	global $editor;
	include_once('product_common_functions.php');
	$part_sku=$data['values']['part_sku'];
	$location_key=$data['values']['location_key'];
	$qty=$data['values']['qty'];
	$note=$data['values']['note'];
	$part_location=new PartLocation($part_sku,$location_key);
	$part_location->editor=$editor;
	$part_location->audit($qty,$note,$editor['Date']);

	if ($part_location->updated) {
	
		

	
		$response=array(
			'state'=>200,
			'action'=>'updates',
			'msg'=>$part_location->msg,
			'qty'=>$part_location->data['Quantity On Hand'],
			'formated_qty'=>number($part_location->data['Quantity On Hand']),
			'newvalue'=>$part_location->data['Quantity On Hand'],
			'stock'=>$part_location->part->get('Part Current Stock'),
			'location_key'=>$part_location->location_key,
			'sku'=>$part_location->part_sku,
			'value_at_cost'=>$part_location->part->get_current_formated_value_at_cost(),
			'value_at_current_cost'=>$part_location->part->get_current_formated_value_at_current_cost(),
			'commercial_value'=>$part_location->part->get_current_formated_commercial_value(),
			'current_stock'=>$part_location->part->get('Part Current Stock'),
			'current_stock_picked'=>$part_location->part->get('Part Current Stock Picked'),
			'current_stock_in_process'=>$part_location->part->get('Part Current Stock In Process'),
			'current_stock_available'=>$part_location->part->get('Current Stock Available'),
			'product_data'=>get_product_web_state_labels($part_location->part->get_current_products()),
			'available_for_forecast'=>$part_location->part->get('Part XHTML Available For Forecast')
		);
		echo json_encode($response);
		return;
	} else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
		echo json_encode($response);
		return;

	}

}

function add_stock($data) {
	global $editor;
	include_once('product_common_functions.php');
	$part_sku=$data['values']['part_sku'];
	$location_key=$data['values']['location_key'];
	$qty=$data['values']['qty'];
	$note=$data['values']['note'];
	$part_location=new PartLocation($part_sku,$location_key);
	$part_location->editor=$editor;
	$_data=array('Quantity'=>$qty,'Origin'=>$note);
	$part_location->add_stock($_data,$editor['Date']);



	if ($part_location->updated) {
		$response=array(
			'state'=>200,
			'action'=>'updates',
			'msg'=>$part_location->msg,
			'qty'=>$part_location->data['Quantity On Hand'],
			'formated_qty'=>number($part_location->data['Quantity On Hand']),
			'newvalue'=>$part_location->data['Quantity On Hand'],
			'stock'=>$part_location->part->get('Part Current Stock'),
			'location_key'=>$part_location->location_key,
			'sku'=>$part_location->part_sku,
			'value_at_cost'=>$part_location->part->get_current_formated_value_at_cost(),
			'value_at_current_cost'=>$part_location->part->get_current_formated_value_at_current_cost(),
			'commercial_value'=>$part_location->part->get_current_formated_commercial_value(),
			'current_stock'=>$part_location->part->get('Part Current Stock'),
			'current_stock_picked'=>$part_location->part->get('Part Current Stock Picked'),
			'current_stock_in_process'=>$part_location->part->get('Part Current Stock In Process'),
			'current_stock_available'=>$part_location->part->get('Current Stock Available'),
			'product_data'=>get_product_web_state_labels($part_location->part->get_current_products()),
			'available_for_forecast'=>$part_location->part->get('Part XHTML Available For Forecast'),
			'next_expected_shipment'=>$part_location->part->get('Next Supplier Shipment')



		);
		echo json_encode($response);
		return;
	} else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
		echo json_encode($response);
		return;

	}

}

function update_save_picking_location_quantity_limits($data) {
	global $editor;



	$part_sku=$data['part_sku'];
	$location_key=$data['location_key'];
	$new_value_min=stripslashes(urldecode($data['newvalue_min']));
	$new_value_max=stripslashes(urldecode($data['newvalue_max']));
	

	if($new_value_min==0 and $new_value_max==0){
	$new_value_min='';
	$new_value_max='';
	}
	
	if($new_value_min<0){
	$new_value_min='';
	}
	
	if($new_value_max<0){
	$new_value_max='';
	}

	if($new_value_max>$new_value_min){
		$check_errors=false;
	}else{
	$check_errors=false;
	}

	$part_location=new PartLocation($part_sku,$location_key);

	$part_location->editor=$editor;
	if (!$part_location->ok) {
		$response=array('state'=>400,'action'=>'error','msg'=>'');
		echo json_encode($response);
		return;
	}
	$data=array('Minimum Quantity'=>$new_value_min);
	$part_location->editor=$editor;
	$part_location->update($data,$check_errors);


	$updated=$part_location->updated;
	if ($part_location->error) {
		$response=array('state'=>200,'action'=>'error','msg'=>$part_location->msg);
		echo json_encode($response);
		return;
	}

	$data=array('Maximum Quantity'=>$new_value_max);
	$part_location->editor=$editor;
	$part_location->update($data,$check_errors);

	if ($part_location->error) {
		$response=array('state'=>200,'action'=>'error','msg'=>$part_location->msg);
		echo json_encode($response);
		return;
	}

	if (!$updated and !$part_location->updated) {
		$updated=false;
	}




	if ($updated) {
		$new_value_min=$part_location->data['Minimum Quantity'];
		$new_value_max=$part_location->data['Maximum Quantity'];

		$response=array('state'=>200,'action'=>'updated','min_value'=>($new_value_min==''?'?':$new_value_min),'max_value'=>($new_value_max==''?'?':$new_value_max),'sku'=>$part_location->part_sku,'location_key'=>$part_location->location_key);
		echo json_encode($response);
		return;
	}else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
		echo json_encode($response);
		return;

	}

}
function update_move_qty($data) {
	global $editor;



	$part_sku=$data['part_sku'];
	$location_key=$data['location_key'];
	$new_move_qty=stripslashes(urldecode($data['move_qty']));


	$part_location=new PartLocation($part_sku,$location_key);

	$part_location->editor=$editor;
	if (!$part_location->ok) {
		$response=array('state'=>400,'action'=>'error','msg'=>'');
		echo json_encode($response);
		return;
	}


	$data=array('Moving Quantity'=>$new_move_qty);
	$part_location->editor=$editor;
	$part_location->update($data);


	$new_value=$part_location->data['Moving Quantity'];


	if ($part_location->updated) {
		$response=array('state'=>200,'action'=>'updated','move_qty'=>($new_value==''?'?':$new_value),'sku'=>$part_location->part_sku,'location_key'=>$part_location->location_key);
		echo json_encode($response);
		return;
	}else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
		echo json_encode($response);
		return;

	}

}

function update_part_location() {
	global $editor;

	if (
		!isset($_REQUEST['location_key'])
		or !isset($_REQUEST['part_sku'])
		or !isset($_REQUEST['key'])
		or !isset($_REQUEST['newvalue'])
	) {
		$response=array('state'=>400,'action'=>'error','msg'=>'');
		echo json_encode($response);
		return;
	}
	//print_r($editor);

	$part_sku=$_REQUEST['part_sku'];
	$location_key=$_REQUEST['location_key'];
	$new_value=stripslashes(urldecode($_REQUEST['newvalue']));
	$traslator=array(
		'qty'=>'Quantity On Hand',
		'can_pick'=>'Can Pick',
		'min'=>'Minimum Quantity',
		'max'=>'Maximum Quantity',
		'move_qty'=>'Moving Qty'
	);
	if (array_key_exists($_REQUEST['key'],$traslator)) {
		$key=$traslator[$_REQUEST['key']];
	}else {
		$response=array('state'=>400,'action'=>'error','msg'=>'Unknown key '.$_REQUEST['key']);
		echo json_encode($response);
		return;
	}



	$part_location=new PartLocation($part_sku,$location_key);

	$part_location->editor=$editor;
	if (!$part_location->ok) {
		$response=array('state'=>400,'action'=>'error','msg'=>'');
		echo json_encode($response);
		return;
	}
	$data=array($key=>$new_value);
	$part_location->editor=$editor;
	$part_location->update($data);

	if ($part_location->updated) {
		$response=array('state'=>200,'action'=>'updates','msg'=>$part_location->msg
			,'qty'=>$part_location->data['Quantity On Hand']
			,'formated_qty'=>number($part_location->data['Quantity On Hand'])
			,'newvalue'=>$part_location->data[$key]
			,'stock'=>$part_location->part->get('Part Current Stock')
		);
		echo json_encode($response);
		return;
	}else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
		echo json_encode($response);
		return;

	}


}

function update_warehouse_area($data) {
	//print_r($data['key']);

	/*
  if(
     !isset($data['wa_key'])
     or !isset($data['key'])
     or !isset($data['newvalue'])
     ){
    $response=array('state'=>400,'action'=>'error','msg'=>'');
    echo json_encode($response);
     return;
  }
  */

	$wa_key=$_REQUEST['key'];

	$new_value=stripslashes(urldecode($_REQUEST['newvalue']));

	$traslator=array(
		'code'=>'Warehouse Area Code',
		'name'=>'Warehouse Area Name',
		'description'=>'Warehouse Area Description'
	);
	if (array_key_exists($_REQUEST['key'],$traslator)) {
		$key=$traslator[$_REQUEST['key']];
	}else {
		$response=array('state'=>400,'action'=>'error','msg'=>'Unknown key '.$_REQUEST['key']);
		echo json_encode($response);
		return;
	}


	$wa=new WarehouseArea($wa_key);
	if (!$wa->id) {
		$response=array('state'=>400,'action'=>'error','msg'=>'');
		echo json_encode($response);
		return;
	}

	$wa->update(array($key=>$new_value));
	if ($wa->updated) {
		$response=array('state'=>200,'action'=>'updates','msg'=>$wa->msg,'newvalue'=>$wa->data[$key]);
		echo json_encode($response);
		return;
	}else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$wa->msg);
		echo json_encode($response);
		return;
	}
}





function update_shelf_type() {

	if (
		!isset($_REQUEST['id'])
		or !isset($_REQUEST['newvalue'])
	) {
		$response=array('state'=>400,'action'=>'error','msg'=>'no data');
		echo json_encode($response);
		return;
	}

	$id=$_REQUEST['id'];
	$key=$_REQUEST['key'];
	$new_value=stripslashes(urldecode($_REQUEST['newvalue']));

	$traslator=array(
		'name'=>'Shelf Type Name',
		'description'=>'Shelf Type Description',
		'rows'=>'Shelf Type Rows',
		'columns'=>'Shelf Type Columns',
		'deep'=>'Shelf Type Location Deep',
		'length'=>'Shelf Type Location Length',
		'height'=>'Shelf Type Location Height',
		'max_weight'=>'Shelf Type Location Max Weight',
		'max_volume'=>'Shelf Type Location Max Volume',
		'type'=>'Shelf Type Type',
	);
	if (array_key_exists($_REQUEST['key'],$traslator)) {
		$key=$traslator[$_REQUEST['key']];
	}else {
		$response=array('state'=>400,'action'=>'error','msg'=>'Unknown key '.$_REQUEST['key']);
		echo json_encode($response);
		return;
	}

	$wa=new ShelfType($id);
	if (!$wa->id) {
		$response=array('state'=>400,'action'=>'error','msg'=>'object not found');
		echo json_encode($response);
		return;
	}
	$wa->update(array($key=>$new_value));
	if ($wa->updated) {
		$response=array('state'=>200,'action'=>'updates','msg'=>$wa->msg,'newvalue'=>$wa->data[$key]);
		echo json_encode($response);
		return;
	}else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$wa->msg);
		echo json_encode($response);
		return;

	}
}


function list_warehouse_areas_for_edition() {



	

	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];

	}else{
		exit();
		}
		
		
		
	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];

	}else{
		exit();
		}
		
		
		switch($parent){
		case 'none':
						$conf_table='warehouses';
						break;
						
	case 'warehouse':
						$conf_table='warehouse';
						break;
				}
		
		$conf=$_SESSION['state'][$conf_table]['warehouse_areas'];

	if (isset( $_REQUEST['sf'])) {
		$start_from=$_REQUEST['sf'];


	}else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	}else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



$_SESSION['state'][$conf_table]['warehouse_areas']['order']=$order;
$_SESSION['state'][$conf_table]['warehouse_areas']['order_dir']=$order_direction;
$_SESSION['state'][$conf_table]['warehouse_areas']['nr']=$number_results;
$_SESSION['state'][$conf_table]['warehouse_areas']['sf']=$start_from;
$_SESSION['state'][$conf_table]['warehouse_areas']['f_field']=$f_field;
$_SESSION['state'][$conf_table]['warehouse_areas']['f_value']=$f_value;





	switch ($parent) {
	case('warehouse'):
		$where=sprintf("where  `Warehouse Key`=%d",$parent_key);
		break;
	default:
		$where='where true';

	}

	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Warehouse Area Name` like '".addslashes($f_value)."%'";
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Warehouse Area Code` like '".addslashes($f_value)."%'";



	$sql="select count(*) as total from `Warehouse Area Dimension`   $where $wheref";

	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0; $total_records=$total;
	}else {
		$sql="select count(*) as total `Warehouse Area Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
			mysql_free_result($result);
		}

	}

	$rtext=number($total_records)." ".ngettext('area','areas',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
else
$rtext_rpp='';

	$_dir=$order_direction;
	$_order=$order;

	$order='`Warehouse Area Code`';
	if ($order=='name')
		$order='`Warehouse Area Name`';
	elseif ($order=='code')
		$order='`Warehouse Area Code`';
	elseif ($order=='description')
		$order='`Warehouse Area Description`';

	$sql="select *  from `Warehouse Area Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
	//  print $sql;
	$res = mysql_query($sql);
	$adata=array();

	$sum_active=0;


	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$adata[]=array(
			'go'=>sprintf("<a href='edit_warehouse_area.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Warehouse Area Key']),


			'wa_key'=>$row['Warehouse Area Key'],
			'code'=>$row['Warehouse Area Code'],
			'name'=>$row['Warehouse Area Name'],
			'description'=>$row['Warehouse Area Description'],


		);
	}
	mysql_free_result($res);









	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);

}

function new_location($data) {
	global $editor;



	//print_r($data['values']);


	if (!isset($data['values']['Location Warehouse Area Key'])  or !is_numeric($data['values']['Location Warehouse Area Key']) ) {
		$data['values']['Location Warehouse Area Key']=1;
	}



	// print_r($data['values']);

	$warehouse_area=new WarehouseArea($data['values']['Location Warehouse Area Key']);

	if (!$warehouse_area->id) {
		$response=array('state'=>400,'msg'=>'Wrong Warehouse Area');
		echo json_encode($response);
		return;
	}


	$data['values']['editor']=$editor;

	$warehouse_area->add_location($data['values']);
	if ($warehouse_area->updated) {
		$response=array(
			'state'=>200
			,'action'=>'created'
			,'location_key'=>$warehouse_area->new_location->id
			,'msg'=>_('Location added to Warehouse Area')
			,'min'=>''
			,'max'=>''
		);
		echo json_encode($response);
		return;

	}else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>$warehouse_area->new_location_msg);
		echo json_encode($response);
		return;

	}
}

function new_warehouse_area() {
	global $editor;

	if ( !isset($_REQUEST['values']) ) {
		$response=array('state'=>400,'msg'=>'Error no value');
		echo json_encode($response);
		return;
	}

	$tmp=preg_replace('/\\\"/','"',$_REQUEST['values']);
	$tmp=preg_replace('/\\\\\"/','"',$tmp);

	$raw_data=json_decode($tmp, true);
	// print_r($raw_data);
	if (!is_array($raw_data)) {
		$response=array('state'=>400,'msg'=>'Wrong value');
		echo json_encode($response);
		return;
	}

	if (!isset($raw_data['Warehouse Key'])  or !is_numeric($raw_data['Warehouse Key']) ) {
		$response=array('state'=>400,'msg'=>'Wrong value');
		echo json_encode($response);
		return;
	}
	$warehouse=new warehouse($raw_data['Warehouse Key']);

	if (!$warehouse->id) {
		$response=array('state'=>400,'msg'=>'Wrong value');
		echo json_encode($response);
		return;
	}


	$raw_data['editor']=$editor;

	$warehouse->add_area($raw_data);
	if ($warehouse->new_area) {
		$response=array(
			'state'=>200
			,'action'=>'created'
			,'warehouse_area_key'=>$warehouse->new_area_key
			,'msg'=>_('Area added to Warehouse')
		);
		echo json_encode($response);
		return;

	}else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>$warehouse->new_area_msg);
		echo json_encode($response);
		return;

	}
}

function new_shelf_type() {
	global $editor;

	if ( !isset($_REQUEST['values']) ) {
		$response=array('state'=>400,'msg'=>'Error no value');
		echo json_encode($response);
		return;
	}

	$tmp=preg_replace('/\\\"/','"',$_REQUEST['values']);
	$tmp=preg_replace('/\\\\\"/','"',$tmp);

	$raw_data=json_decode($tmp, true);
	// print_r($raw_data);
	if (!is_array($raw_data)) {
		$response=array('state'=>400,'msg'=>'Wrong value');
		echo json_encode($response);
		return;
	}




	$raw_data['editor']=$editor;

	$shelf_type=new ShelfType('find',$raw_data,'create');
	if ($shelf_type->new) {
		$response=array(
			'state'=>200
			,'action'=>'created'
			,'msg'=>_('Shelf Type Created')
		);
		echo json_encode($response);
		return;

	}else {
		$response=array('state'=>200,'action'=>'nochange','msg'=>$shelf_type->msg);
		echo json_encode($response);
		return;

	}
}




function delete_location() {
	if (!isset($_REQUEST['location_key'])) {
		$response=array('state'=>400,'msg'=>'Error');
		echo json_encode($response);
		return;
	}

	$location_key=$_REQUEST['location_key'];
	$location=new Location($location_key);

	$location->delete();

	if ($location->deleted) {
		$response= array('state'=>200,'action'=>'deleted');
	} else {
		$response= array('state'=>400,'msg'=>$location->deleted_msg);
	}

	$unknown_wa=new WarehouseArea($_REQUEST['area_key']);
	$unknown_wa->update_children();


	echo json_encode($response);
	exit;


}

function delete_part_location() {
	global $editor;
	include_once('product_common_functions.php');
	if (!isset($_REQUEST['location_key'])
		or !isset($_REQUEST['part_sku'])
	) {
		$response=array('state'=>400,'msg'=>'Error 1');
		echo json_encode($response);
		return;
	}

	$location_key=$_REQUEST['location_key'];
	$part_sku=$_REQUEST['part_sku'];

	$part_location=new PartLocation($part_sku,$location_key);
	$part_location->editor=$editor;
	$part=$part_location->part;
	$part_location->delete();




	if ($part_location->deleted) {
		$response= array('state'=>200,'action'=>'deleted',
					'product_data'=>get_product_web_state_labels($part->get_current_products())

		);

	}else {
		$response= array('state'=>200,'action'=>'nochange',
					'product_data'=>get_product_web_state_labels($part->get_current_products())

		);
	}

	echo json_encode($response);
	exit;
}
function add_part_to_location($data) {
	global $editor;
include_once('product_common_functions.php');
	if (!isset($data['location_key'])
		or !isset($data['part_sku'])
	) {
		$response=array('state'=>400,'msg'=>'Error');
		echo json_encode($response);
		return;
	}

	$location_key=$data['location_key'];
	$part_sku=$data['part_sku'];
	$part_location_data=array(
		'Location Key'=>$location_key
		,'Part SKU'=>$part_sku
		,'editor'=>$editor
	);



	$part_location=new PartLocation('find',$part_location_data,'create');
	if ($part_location->new) {

		$response=array(
			'state'=>200,'action'=>'added'
			,'msg'=>$part_location->msg
			,'sku'=>$part_location->part_sku
			,'formated_sku'=>$part_location->part->get_sku()
			,'location_key'=>$part_location->location_key
			,'location_code'=>$part_location->location->data['Location Code']
			,'qty'=>$part_location->data['Quantity On Hand']
			,'formated_qty'=>number($part_location->data['Quantity On Hand'])
			,'can_pick'=>$part_location->data['Can Pick']
			,'limits'=>'{<span id="picking_limit_min_'.$part_location->part_sku.'_'.$part_location->location_key.'"  >?</span>,<span id="picking_limit_max_'.$part_location->part_sku.'_'.$part_location->location_key.'">?</span>}'
			,'product_data'=>get_product_web_state_labels($part_location->part->get_current_products())

		);
		echo json_encode($response);
		return;
	}else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
		echo json_encode($response);
		return;
	}



}


function lost_stock($data) {
	global $editor;
include_once('product_common_functions.php');



	$raw_data=$data['values'];

	if (
		!isset($raw_data['location_key'])
		or !isset($raw_data['part_sku'])
		or !isset($raw_data['qty'])
		or !isset($raw_data['why'])
		or !isset($raw_data['action'])
		or !isset($raw_data['type'])
	) {
		$response=array('state'=>400,'action'=>'error','msg'=>'wp');
		echo json_encode($response);
		return;
	}

	$traslator=array(
		'qty'=>'Lost Quantity',
		'why'=>'Reason',
		'action'=>'Action',
		'type'=>'Type',
	);

	foreach ($raw_data as $key =>$value) {
		if (array_key_exists($key,$traslator)) {
			$data[$traslator[$key]]=$value;
		}
	}

	$part_location=new PartLocation($raw_data['part_sku'],$raw_data['location_key']);


	if (!$part_location->ok) {
		$response=array('state'=>400,'action'=>'error','msg'=>'errr n pl');
		echo json_encode($response);
		return;
	}
	$part_location->editor=$editor;
	$part_location->set_stock_as_lost($data,$editor['Date']);
	if ($part_location->error) {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
		echo json_encode($response);
		return;

	}else {
		list($stock,$value)=$part_location->part->get_current_stock();

		$response=array(
			'state'=>200,'action'=>'ok','msg'=>$part_location->msg,
			'qty'=>$part_location->data['Quantity On Hand'],
			'formated_qty'=>number($part_location->data['Quantity On Hand']),
			'stock'=>$stock,
			'value_at_cost'=>$part_location->part->get_current_formated_value_at_cost(),
			'value_at_current_cost'=>$part_location->part->get_current_formated_value_at_current_cost(),
			'commercial_value'=>$part_location->part->get_current_formated_commercial_value(),
			'current_stock'=>$part_location->part->get('Part Current Stock'),
			'current_stock_picked'=>$part_location->part->get('Part Current Stock Picked'),
			'current_stock_in_process'=>$part_location->part->get('Part Current Stock In Process'),
			'current_stock_available'=>$part_location->part->get('Current Stock Available'),
			'product_data'=>get_product_web_state_labels($part_location->part->get_current_products()),
						'available_for_forecast'=>$part_location->part->get('Part XHTML Available For Forecast')


		);
		echo json_encode($response);
		return;

	}
}


function move_stock($_data) {



	global $editor;

	$raw_data=$_data['values'];



	$traslator=array(
		'qty'=>'Quantity To Move',
		'to_key'=>'Destination Key',
	);
	$data=array();
	foreach ($raw_data as $key =>$value) {
		if (array_key_exists($key,$traslator)) {
			$data[$traslator[$key]]=$value;
		}
	}

	if (!$data['Destination Key']) {
		$response=array('state'=>400,'action'=>'error','msg'=>_('No location selected'));
		echo json_encode($response);
		return;
	}


	if ($raw_data['from_key']==$data['Destination Key']) {
		$response=array('state'=>400,'action'=>'error','msg'=>'wp');
		echo json_encode($response);
		return;

	}


	$part_location=new PartLocation($raw_data['part_sku'],$raw_data['from_key']);


	if (!$part_location->ok) {
		$response=array('state'=>400,'action'=>'error','msg'=>'');
		echo json_encode($response);
		return;
	}
	$part_location->editor=$editor;
	// print_r($data);
	$part_location->move_stock($data,$editor['Date']);
	$to_part_location=new PartLocation($raw_data['part_sku'],$data['Destination Key']);
	if ($part_location->error) {
		$response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
		echo json_encode($response);
		return;

	}else {
		$response=array('state'=>200,'action'=>'ok','msg'=>$part_location->msg
			,'formated_sku'=>$part_location->part->get_sku()
			,'sku'=>$part_location->part->sku
			,'qty_from'=>$part_location->data['Quantity On Hand']
			,'qty_to'=>$to_part_location->data['Quantity On Hand']
			,'location_key_from'=>$part_location->location_key
			,'location_key_to'=>$to_part_location->location_key
			,'location_code_from'=>$part_location->location->data['Location Code']
			,'location_code_to'=>$to_part_location->location->data['Location Code']

			,'formated_qty_from'=>$part_location->data['Quantity On Hand']
			,'formated_qty_to'=>$to_part_location->data['Quantity On Hand']

			,'stock'=>$part_location->part->get('Part Current Stock')


		);
		echo json_encode($response);
		return;

	}
}

function list_locations() {
	$conf=$_SESSION['state']['warehouse']['edit_locations'];


	if (!isset( $_REQUEST['parent']) or !isset( $_REQUEST['parent_key']) ) {
		print "no parent info\n";
		return;
	}

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$elements=$conf['elements'];
	$parent=$_REQUEST['parent'];

	$parent_key=$_REQUEST['parent_key'];


	$_SESSION['state']['warehouse']['locations']['order']=$order;
	$_SESSION['state']['warehouse']['locations']['order_dir']=$order_direction;
	$_SESSION['state']['warehouse']['locations']['nr']=$number_results;
	$_SESSION['state']['warehouse']['locations']['sf']=$start_from;
	$_SESSION['state']['warehouse']['locations']['f_field']=$f_field;
	$_SESSION['state']['warehouse']['locations']['f_value']=$f_value;



	//$elements=$conf['elements'];

	if (isset( $_REQUEST['elements_Yellow'])) {
		$elements['Yellow']=$_REQUEST['elements_Yellow'];
	}
	if (isset( $_REQUEST['elements_Red'])) {
		$elements['Red']=$_REQUEST['elements_Red'];
	}
	if (isset( $_REQUEST['elements_Purple'])) {
		$elements['Purple']=$_REQUEST['elements_Purple'];
	}
	if (isset( $_REQUEST['elements_Pink'])) {
		$elements['Pink']=$_REQUEST['elements_Pink'];
	}
	if (isset( $_REQUEST['elements_Orange'])) {
		$elements['Orange']=$_REQUEST['elements_Orange'];
	}
	if (isset( $_REQUEST['elements_Green'])) {
		$elements['Green']=$_REQUEST['elements_Green'];
	}
	if (isset( $_REQUEST['elements_Blue'])) {
		$elements['Blue']=$_REQUEST['elements_Blue'];
	}






	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Location Code` like '".addslashes($f_value)."%'";



	$where='where true ';


	switch ($parent) {
	case('warehouse'):
		$where.=sprintf(' and `Location Warehouse Key`=%d',$parent_key);
		break;
	case('warehouse_area'):
		$where.=sprintf(' and `Location Warehouse Area Key`=%d',$parent_key);
		break;
	case('shelf'):
		$where.=sprintf(' and `Location Shelf Key`=%d',$parent_key);
		break;
	}



	$_elements='';
	foreach ($elements as $_key=>$_value) {
		if ($_value) {
			if ($_key=='Blue') {
				$_elements.=",'Blue'";
			}
			elseif ($_key=='Green') {
				$_elements.=",'Green'";
			}
			elseif ($_key=='Orange') {
				$_elements.=",'Orange'";
			}
			elseif ($_key=='Pink') {
				$_elements.=",'Pink'";
			}
			elseif ($_key=='Purple') {
				$_elements.=",'Purple'";
			}
			elseif ($_key=='Red') {
				$_elements.=",'Red'";
			}
			elseif ($_key=='Yellow') {
				$_elements.=",'Yellow'";
			}
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} else {
		$where.=' and `Warehouse Flag` in ('.$_elements.')' ;
	}

	$sql="select count(*) as total from `Location Dimension`    $where $wheref";
	// print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	}else {
		$sql="select count(*) as total from `Location Dimension`  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}






	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any location name starting with")." <b>$f_value</b> ";
			break;
		}
	}elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only locations starting with')." <b>$f_value</b>";
			break;
		}
	}else
		$filter_msg='';





	$rtext=number($total_records)." ".ngettext('location','locations',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';



	$_order=$order;
	$_dir=$order_direction;

	if ($order=='code')
		$order='`Location Code`';
	elseif ($order=='parts')
		$order='`Location Distinct Parts`';
	elseif ($order=='max_volumen')
		$order='`Location Max Volume`';
	elseif ($order=='max_weight')
		$order='`Location Max Weight`';
	elseif ($order=='tipo')
		$order='`Location Mainly Used For`';
	elseif ($order=='area')
		$order='`Warehouse Area Code`';

	$data=array();
	$sql="select `Location Warehouse Area Key`,`Location Key`,`Location Distinct Parts`,`Location Mainly Used For`,`Location Max Weight`,`Location Max Volume`, `Warehouse Area Code`  ,`Location Code`,`Location Shelf Key`,`Shelf Code` from `Location Dimension` L left join `Shelf Dimension` S on (`Location Shelf Key`=S.`Shelf Key`) left join `Warehouse Area Dimension` WAD on (`Location Warehouse Area Key`=WAD.`Warehouse Area Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
	//  print $sql;
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
		$tipo=$row['Location Mainly Used For'];

		if ($row['Location Max Weight']=='' or $row['Location Max Weight']<=0)
			$max_weight=_('Unknown');
		else
			$max_weight=weight($row['Location Max Weight']);
		if ($row['Location Max Volume']==''  or $row['Location Max Volume']<=0)
			$max_vol=_('Unknown');
		else
			$max_vol=volume($row['Location Max Volume']);

		if ($row['Warehouse Area Code']=='')
			$area=_('Unknown');
		else
			$area=$row['Warehouse Area Code'];



		if ($row['Location Distinct Parts']>0) {
			$delete='';
		}else {
			$delete='<img src="art/icons/delete.png"/>';
		}



		$data[]=array(
			'id'=>$row['Location Key'],
			'go'=>sprintf("<a href='edit_location.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Location Key']),
			'area'=>$row['Warehouse Area Code'],
			'area_key'=>$row['Location Warehouse Area Key'],
			'shelf'=>$row['Shelf Code'],
			'shelf_key'=>$row['Location Shelf Key'],
			'delete'=>$delete,
			'delete_type'=>'delete',
			'tipo'=>$tipo,
			'code'=>$row['Location Code'],
			'parts'=>number($row['Location Distinct Parts']),
			'max_weight'=>$max_weight,
			'max_volumen'=>$max_vol,
		);
	}
	$response=array('resultset'=>
		array('state'=>200,
			'state'=>200,
			'data'=>$data,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total
		)
	);
	echo json_encode($response);
}


function list_shelf_types_for_edition() {


	$conf_table='shelf_types';
	$conf=$_SESSION['state']['shelf_types']['table'];

	if (isset( $_REQUEST['sf'])) {
		$start_from=$_REQUEST['sf'];


	}else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	}else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;






	$_SESSION['state'][$conf_table]['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Shelf Type Name` like '".addslashes($f_value)."%'";






	$sql="select count(*) as total from `Shelf Type Dimension`   $where $wheref";
	//  print $sql;
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0; $total_records=$total;
	}else {
		$sql="select count(*) as total from `Shelf Type Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
			mysql_free_result($result);
		}

	}

	$rtext=number($total_records)." ".ngettext('shelf type','shelf types',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
else
$rtext_rpp='';

	$_dir=$order_direction;
	$_order=$order;

	$order='`Shelf Type Name`';
	if ($order=='name')
		$order='`Shelf Type Name`';
	elseif ($order=='rows')
		$order='`Shelf Type Rows`';
	elseif ($order=='columns')
		$order='`Shelf Type Columns`';
	elseif ($order=='deep')
		$order='`Shelf Type Location Deep`';
	elseif ($order=='length')
		$order='`Shelf Type Location Length`';
	elseif ($order=='height')
		$order='`Shelf Type Location Height`';
	elseif ($order=='type')
		$order='`Shelf Type Type`';
	elseif ($order=='max_weight')
		$order='`Shelf Type Location Max Weight`';
	elseif ($order=='max_vol')
		$order='`Shelf Type Location Max Volume`';

	$sql="select *  from `Shelf Type Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();
	$sum_active=0;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$max_vol='';
		$max_weight='';
		$deep='';
		$length='';
		$height='';

		if ($row['Shelf Type Location Max Volume']!='')
			$max_vol=number($row['Shelf Type Location Max Volume']);

		if ($row['Shelf Type Location Max Weight']!='')
			$max_weight=number($row['Shelf Type Location Max Weight']);
		if ($row['Shelf Type Location Deep']!='')
			$deep=number($row['Shelf Type Location Deep']);

		if ($row['Shelf Type Location Length']!='')
			$length=number($row['Shelf Type Location Length']);

		if ($row['Shelf Type Location Height']!='')
			$height=number($row['Shelf Type Location Height']);

		$type=$row['Shelf Type Type'];

		$adata[]=array(
			'id'=>$row['Shelf Type Key']
			,'name'=>$row['Shelf Type Name']
			,'description'=>$row['Shelf Type Description']
			,'rows'=>number($row['Shelf Type Rows'])
			,'columns'=>number($row['Shelf Type Columns'])
			,'deep'=>$deep
			,'length'=>$length
			,'height'=>$height
			,'max_vol'=>$max_vol
			,'max_weight'=>$max_weight
			,'type'=>$type
			,'delete'=>'<img alt="'._('Delete').'" src="art/icons/cross.png" />'
		);
	}
	mysql_free_result($res);









	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);

}

function new_shelf($data) {
	require_once 'class.Shelf.php';

	$shelf_data=$data['values']['Shelf Data'];
	$locations_data=$data['values']['Locations Data'];

	$shelf=new Shelf('find',$shelf_data,'create');
	if ($shelf->new) {
		foreach ($locations_data as $_data) {
			foreach ($_data as $j=>$__data) {
				$shelf->add_location($__data);
			}

		}


	}

}




function update_location($data) {




	$location=new Location($data['location_key']);


	if (!$location->id) {
		$response= array('state'=>400,'msg'=>'Location not found');
		echo json_encode($response);
		exit;
	}

	$key_dic=array(
		'code'=>'Location Code',
		'radius'=>'Location Radius',
		'deep'=>'Location Deep',
		'height'=>'Location Height',
		'width'=>'Location Width',
		'volume'=>'Location Max Volume',
		'weight'=>'Location Max Weight',
		'slots'=>'Location Max Slots',
		'parts'=>'Location Distinct Parts',
		'used_for'=>'Location Mainly Used For',
		'flag'=>'Warehouse Flag Key'
	);



	if (array_key_exists($data['key'],$key_dic))
		$key=$key_dic[$data['key']];
	else
		$key=$data['okey'];


	$the_new_value=_trim($data['newvalue']);


	$location->update(array($key=>$the_new_value));



	if (!$location->error) {
		$response= array('state'=>200,'action'=>'updated','newvalue'=>$location->new_value,'key'=>$data['okey']);

		if ($data['okey']=='flag') {

			$sql=sprintf("select * from  `Warehouse Flag Dimension` where `Warehouse Flag Key`=%d",$location->new_value);

			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {



				switch ($row['Warehouse Flag Color']) {
				case 'Blue': $flag="<img  src='art/icons/flag_blue.png' title='".$row['Warehouse Flag Color']."' />"; break;
				case 'Green':  $flag="<img  src='art/icons/flag_green.png' title='".$row['Warehouse Flag Color']."' />";break;
				case 'Orange': $flag="<img src='art/icons/flag_orange.png' title='".$row['Warehouse Flag Color']."'  />"; break;
				case 'Pink': $flag="<img  src='art/icons/flag_pink.png' title='".$row['Warehouse Flag Color']."'/>"; break;
				case 'Purple': $flag="<img src='art/icons/flag_purple.png' title='".$row['Warehouse Flag Color']."'/>"; break;
				case 'Red':  $flag="<img src='art/icons/flag_red.png' title='".$row['Warehouse Flag Color']."'/>";break;
				case 'Yellow':  $flag="<img src='art/icons/flag_yellow.png' title='".$row['Warehouse Flag Color']."'/>";break;
				default:
					$flag='';

				}

				$response['flag_label']=$row['Warehouse Flag Label'];
				$response['flag_icon']="flag_".strtolower($row['Warehouse Flag Color']).".png";
				$response['flag']=$flag;
				$response['flag_value']=$row['Warehouse Flag Color'];

			}
		}

		if (isset($data['table_record_index'])) {
			$response['record_index']=(float) $data['table_record_index'];

			$sql=sprintf("select * from  `Warehouse Flag Dimension` where `Warehouse Key`=%d and `Warehouse Flag Active`='Yes' ",$location->data['Location Warehouse Key']);
			$res=mysql_query($sql);
			$elements_data=array();
			while ($row=mysql_fetch_assoc($res)) {

				$elements_data[]=
					array(
					'number'=>number($row['Warehouse Flag Number Locations']),
					'color'=>$row['Warehouse Flag Color'],

				);
			}
			$response['locations_flag_data']=$elements_data;

		}

	} else {
		$response= array('state'=>400,'msg'=>$location->msg,'key'=>$data['okey']);
	}
	echo json_encode($response);




}

function delete_warehouse_area() {

	$data=$_REQUEST;
	$wa=new WarehouseArea($_REQUEST['area_key']);

	if (!$wa->id) {
		$response= array('state'=>400,'msg'=>'Area not found');
		echo json_encode($response);
		exit;
	}

	$wa->delete();




	if ($wa->deleted) {
		$response= array('state'=>200,'action'=>'deleted');
	} else {
		$response= array('state'=>400,'msg'=>$wa->deleted_msg);
	}

	$unknown_wa=new WarehouseArea(1);
	$unknown_wa->update_children();

	echo json_encode($response);
	exit;

}

?>
