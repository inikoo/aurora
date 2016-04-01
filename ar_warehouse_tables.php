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
case 'warehouses':
	warehouses(get_table_parameters(), $db, $user);
	break;
case 'parts':
	parts(get_table_parameters(), $db, $user);
	break;
case 'locations':
	locations(get_table_parameters(), $db, $user);
	break;
case 'replenishments':
	replenishments(get_table_parameters(), $db, $user);
	break;
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}

function warehouses($_data, $db, $user) {
	global $db;
	$rtext_label='warehouse';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;


	foreach ($db->query($sql) as $data) {

		$adata[]=array(
			'id'=>(integer) $data['Warehouse Key'],
			'code'=>$data['Warehouse Code'],
			'name'=>$data['Warehouse Name'],
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


function locations($_data, $db, $user) {


	$rtext_label='location';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	foreach ($db->query($sql) as $data) {
		switch ($data['Location Mainly Used For']) {
		case 'Picking':
			$used_for=_('Picking');
			break;
		case 'Storing':
			$used_for=_('Storing');
			break;
		case 'Loading':
			$used_for=_('Loading');
			break;
		case 'Displaying':
			$used_for=_('Displaying');
			break;
		case 'Other':
			$used_for=_('Other');
			break;
		default:
			$used_for=$data['Location Mainly Used For'];
			break;
		}

		switch ($data['Warehouse Flag']) {
		case 'Blue': $flag="<img  src='/art/icons/flag_blue.png' title='".$data['Warehouse Flag']."' />"; break;
		case 'Green':  $flag="<img  src='/art/icons/flag_green.png' title='".$data['Warehouse Flag']."' />";break;
		case 'Orange': $flag="<img src='/art/icons/flag_orange.png' title='".$data['Warehouse Flag']."'  />"; break;
		case 'Pink': $flag="<img  src='/art/icons/flag_pink.png' title='".$data['Warehouse Flag']."'/>"; break;
		case 'Purple': $flag="<img src='/art/icons/flag_purple.png' title='".$data['Warehouse Flag']."'/>"; break;
		case 'Red':  $flag="<img src='/art/icons/flag_red.png' title='".$data['Warehouse Flag']."'/>";break;
		case 'Yellow':  $flag="<img src='/art/icons/flag_yellow.png' title='".$data['Warehouse Flag']."'/>";break;
		default:
			$flag='';

		}
		if ($data['Location Max Weight']=='' or $data['Location Max Weight']<=0)
			$max_weight=_('Unknown');
		else
			$max_weight=number($data['Location Max Weight'])._('Kg');
		if ($data['Location Max Volume']==''  or $data['Location Max Volume']<=0)
			$max_vol=_('Unknown');
		else
			$max_vol=number($data['Location Max Volume'])._('L');

		$adata[]=array(
			'id'=>(integer)$data['Location Key'],
			'warehouse_key'=>(integer)$data['Location Warehouse Key'],
			'warehouse_area_key'=>(integer)$data['Location Warehouse Area Key'],
			'code'=>$data['Location Code'],
			'flag'=>$flag,
			'flag_key'=>$data['Warehouse Flag Key'],
			'area'=>$data['Warehouse Area Code'],
			'max_weight'=>$max_weight,
			'max_volume'=>$max_vol,
			'parts'=>number($data['Location Distinct Parts']),
			'used_for'=>$used_for
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


function replenishments($_data, $db, $user) {


	$rtext_label='replenishment';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	//print $sql;

	foreach ($db->query($sql) as $data) {


		switch ($data['Warehouse Flag']) {
		case 'Blue': $flag="<img  src='/art/icons/flag_blue.png' title='".$data['Warehouse Flag']."' />"; break;
		case 'Green':  $flag="<img  src='/art/icons/flag_green.png' title='".$data['Warehouse Flag']."' />";break;
		case 'Orange': $flag="<img src='/art/icons/flag_orange.png' title='".$data['Warehouse Flag']."'  />"; break;
		case 'Pink': $flag="<img  src='/art/icons/flag_pink.png' title='".$data['Warehouse Flag']."'/>"; break;
		case 'Purple': $flag="<img src='/art/icons/flag_purple.png' title='".$data['Warehouse Flag']."'/>"; break;
		case 'Red':  $flag="<img src='/art/icons/flag_red.png' title='".$data['Warehouse Flag']."'/>";break;
		case 'Yellow':  $flag="<img src='/art/icons/flag_yellow.png' title='".$data['Warehouse Flag']."'/>";break;
		default:
			$flag='';

		}


		$stock='<div border=0 style="xwidth:150px">';
		$locations_data=preg_split('/,/', $data['location_data']);

		foreach ($locations_data as $raw_location_data) {
			if ($raw_location_data!='' ) {
				$_locations_data=preg_split('/\:/', $raw_location_data);
				if ($_locations_data[0]!=$data['Location Key']) {
					$stock.='<div style="clear:both">';
					$stock.='<div style="float:left;min-width:100px;"><a href="location.php?id='.$_locations_data[0].'">'.$_locations_data[1].'</a></div><div style="float:left;min-width:100px;text-align:right">'.number($_locations_data[3]).'</div>';
					$stock.='</div>';
				}
			}
		}
		$stock.='</div>';

		$pl_data='<span style="font-weight:800">'.number($data['Quantity On Hand']).'</span>  {'.number($data['Minimum Quantity']).','.number($data['Maximum Quantity']).'}';


		$adata[]=array(
			'id'=>(integer)$data['Location Key'],
			'location'=>$flag.' '.$data['Location Code'],
			'location_key'=>$data['Location Key'],
			'part'=>$data['Part Reference'],
			'part_sku'=>$data['Part SKU'],
			'stock'=>$stock,
			'pl_data'=>$pl_data
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


function parts($_data, $db, $user) {


	$rtext_label='part';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $data) {



			$adata[]=array(
				'id'=>(integer)$data['Part SKU'],
				'warehouse_key'=>(integer)$data['Warehouse Key'],
				'warehouse'=>$data['Warehouse Code'],
				'reference'=>$data['Part Reference'],
				'formatted_sku'=>sprintf("SKU%05d", $data['Part SKU']),
				'reference'=>$data['Part Reference'],
				'description'=>$data['Part Unit Description'],
				'products'=>$data['Part XHTML Currently Used In'],


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
