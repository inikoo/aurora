<?php
/*
 File: ar_warehouse.php

 Ajax Server Anchor for the warehouse Clases

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

require_once 'common.php';
require_once 'ar_edit_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case('other_locations_quick_buttons'):
	$data=prepare_values($_REQUEST,array(
			'sku'=>array('type'=>'key'),
			'location_key'=>array('type'=>'key')

		));

	other_locations_quick_buttons($data);
	break;
case('location_stock_history'):
	history_stock_location();
	break;

case('parts_at_location'):
	parts_at_location();
	break;
case('find_warehouse_area'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'string'),
			'query'=>array('type'=>'string')

		));

	find_warehouse_area($data);
	break;
case('find_location'):
	find_location();
	break;
case('find_shelf_type'):
	$data=prepare_values($_REQUEST,array(
			'query'=>array('type'=>'string')
		));
	find_shelf_type($data);
	break;
case('locations'):
	list_locations();
	break;
case('shelfs'):
	list_shelfs();
	break;
case('warehouse_areas'):
	list_warehouse_areas();
	break;
case('warehouses'):
	list_warehouses();
	break;

case('is_warehouse_code'):
	$data=prepare_values($_REQUEST,array(
			'warehouse_code'=>array('type'=>'string'),
			'query'=>array('type'=>'string')
		));
	is_warehouse_code($data);
	break;
case('is_warehouse_area_code'):
	$data=prepare_values($_REQUEST,array(
			'warehouse_code'=>array('type'=>'string'),
			'query'=>array('type'=>'string')
		));
	is_warehouse_area_code($data);
	break;
default:

	$response=array('state'=>404,'resp'=>_('Operation not found ha ha'));
	echo json_encode($response);


}

function is_warehouse_code($data) {
	if (!isset($data['query']) or !isset($data['warehouse_code'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$warehouse_code=$data['warehouse_code'];

	$sql=sprintf("select * from `Warehouse Dimension` where  `Warehouse Code`=%s"
		,prepare_mysql($data['query'])
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another warehouse (<a href="warehouse.php?pid=%d">%s</a>) already has this name'
			,$data['Warehouse Key']
			,$data['Warehouse Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function is_warehouse_area_code($data) {
	if (!isset($data['query']) or !isset($data['warehouse_code'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$warehouse_code=$data['warehouse_code'];

	$sql=sprintf("select * from `Warehouse Area Dimension` where  `Warehouse Area Code`=%s"
		,prepare_mysql($data['query'])
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another warehouse (<a href="warehouse.php?pid=%d">%s</a>) already has this name'
			,$data['Warehouse Area Key']
			,$data['Warehouse Area Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}
function list_locations() {

	if (!isset( $_REQUEST['parent']) or !isset( $_REQUEST['parent_key']) ) {
		print "no parent info\n";
		return;
	}

	$conf=$_SESSION['state']['warehouse']['locations'];

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

	$elements=$conf['elements'];


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




	$parent=$_REQUEST['parent'];

	$parent_key=$_REQUEST['parent_key'];

	$_SESSION['state']['warehouse']['locations']['elements']=$elements;

	$_SESSION['state']['warehouse']['locations']['order']=$order;
	$_SESSION['state']['warehouse']['locations']['order_dir']=$order_direction;
	$_SESSION['state']['warehouse']['locations']['nr']=$number_results;
	$_SESSION['state']['warehouse']['locations']['sf']=$start_from;
	$_SESSION['state']['warehouse']['locations']['f_field']=$f_field;
	$_SESSION['state']['warehouse']['locations']['f_value']=$f_value;



	//$elements=$conf['elements'];


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
		$where.=' and `Location Flag` in ('.$_elements.')' ;
	}





	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Location Code` like '".addslashes($f_value)."%'";




	$sql="select count(*) as total from `Location Dimension`    $where $wheref";
	// print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Location Dimension`  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}



	$rtext=$total_records." ".ngettext('location','locations',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp='('._("Showing all").')';




	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any location name starting with")." <b>$f_value</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only locations starting with')." <b>$f_value</b>";
			break;
		}
	}
	else
		$filter_msg='';



	$_order=$order;
	$_dir=$order_direction;



	if ($order=='parts')
		$order='`Location Distinct Parts`';
	elseif ($order=='max_volumen')
		$order='`Location Max Volume`';
	elseif ($order=='max_weight')
		$order='`Location Max Weight`';
	elseif ($order=='tipo')
		$order='`Location Mainly Used For`';
	elseif ($order=='area')
		$order='`Warehouse Area Code`';
	elseif ($order=='warehouse')
		$order='`Warehouse Code`';
	else
		$order='`Location Code`';


	$data=array();
	$sql="select * from `Location Dimension` left join `Warehouse Area Dimension` WAD on (`Location Warehouse Area Key`=WAD.`Warehouse Area Key`) left join `Warehouse Dimension` WD on (`Location Warehouse Key`=WD.`Warehouse Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
	// print $where;
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
		$code=sprintf('<a href="location.php?id=%d" >%s</a>',$row['Location Key'],$row['Location Code']);
		$tipo=$row['Location Mainly Used For'];

		if ($row['Location Max Weight']=='' or $row['Location Max Weight']<=0)
			$max_weight=_('Unknown');
		else
			$max_weight=number($row['Location Max Weight'])._('Kg');
		if ($row['Location Max Volume']==''  or $row['Location Max Volume']<=0)
			$max_vol=_('Unknown');
		else
			$max_vol=number($row['Location Max Volume'])._('L');

		if ($row['Warehouse Area Code']=='')
			$area=_('Unknown');
		else
			$area=sprintf('<a href="warehouse_area.php?id=%d">%s</a>',$row['Warehouse Area Key'],$row['Warehouse Area Code']);
		$warehouse=sprintf('<a href="warehouse.php?id=%d">%s</a>',$row['Warehouse Key'],$row['Warehouse Code']);

		switch ($row['Location Flag']) {
		case 'Blue': $flag="<img src='art/icons/flag_blue.png'/>"; break;
		case 'Green':  $flag="<img src='art/icons/flag_green.png'/>";break;
		case 'Orange': $flag="<img src='art/icons/flag_orange.png'/>"; break;
		case 'Pink': $flag="<img src='art/icons/flag_pink.png'/>"; break;
		case 'Purple': $flag="<img src='art/icons/flag_purple.png'/>"; break;
		case 'Red':  $flag="<img src='art/icons/flag_red.png'/>";break;
		case 'Yellow':  $flag="<img src='art/icons/flag_yellow.png'/>";break;
		default:
			$flag='';

		}

		$data[]=array(
			'id'=>$row['Location Key']
			,'tipo'=>$tipo
			,'code'=>$code
			,'area'=>$area
			,'warehouse'=>$warehouse
			,'parts'=>number($row['Location Distinct Parts'])
			,'max_weight'=>$max_weight
			,'max_volumen'=>$max_vol
			,'flag'=>$flag
		);
	}
	$response=array('resultset'=>
		array(
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
function list_shelfs() {
	$conf=$_SESSION['state']['shelfs']['table'];

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

	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
		$_SESSION['state']['shelfs']['parent']=$parent;
	} else
		$parent=$_SESSION['state']['shelfs']['parent'];




	$_SESSION['state']['shelfs']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);


	switch ($parent) {
	case('warehouse'):
		$where.=sprintf(' and `Shelf Warehouse Key`=%d',$_SESSION['state']['warehouse']['id']);
		break;
	case('warehouse_area'):
		$where.=sprintf(' and `Shelf Area Key`=%d',$_SESSION['state']['warehouse_area']['id']);
		break;
	case('shelf'):
		$where.=sprintf(' and `Shelf Shelf Key`=%d',$_SESSION['state']['shelf']['id']);
		break;
	}


	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Shelf Code` like '".addslashes($f_value)."%'";




	$sql="select count(*) as total from `Shelf Dimension`    $where $wheref";
	// print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Shelf Dimension`  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}






	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any shelf name starting with")." <b>$f_value</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only shelfs starting with')." <b>$f_value</b>";
			break;
		}
	}
	else
		$filter_msg='';


	$rtext=$total_records." ".ngettext('shelf','shelfs',$total_records);
	if ($total_records>$number_results)
		$rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
	$_order=$order;
	$_dir=$order_direction;


	$order='`Shelf Code`';
	if ($order=='parts')
		$order='`Shelf Distinct Parts`';
	if ($order=='locations')
		$order='`Shelf Number Locations`';
	elseif ($order=='area')
		$order='`Warehouse Area Code`';
	elseif ($order=='warehouse')
		$order='`Warehouse Code`';
	$data=array();
	$sql="select * from `Shelf Dimension` left join `Warehouse Area Dimension` WAD on (`Shelf Area Key`=WAD.`Warehouse Area Key`) left join `Warehouse Dimension` WD on (`Shelf Warehouse Key`=WD.`Warehouse Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
	//  print $sql;
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
		$code=sprintf('<a href="shelf.php?id=%d" >%s</a>',$row['Shelf Key'],$row['Shelf Code']);



		if ($row['Warehouse Area Code']=='')
			$area=_('Unknown');
		else
			$area=sprintf('<a href="warehouse_area.php?id=%d">%s</a>',$row['Warehouse Area Key'],$row['Warehouse Area Code']);
		$warehouse=sprintf('<a href="warehouse.php?id=%d">%s</a>',$row['Warehouse Key'],$row['Warehouse Code']);

		$data[]=array(
			'id'=>$row['Shelf Key']
			// ,'tipo'=>$tipo
			,'code'=>$code
			,'area'=>$area
			,'warehouse'=>$warehouse
			,'locations'=>number($row['Shelf Number Locations'])

			,'parts'=>number($row['Shelf Distinct Parts'])

		);
	}
	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
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
function list_warehouse_areas() {


	$conf=$_SESSION['state']['warehouse_areas']['table'];




	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
		$_SESSION['state']['warehouse_areas']['parent']=$parent;
	} else
		$parent=$_SESSION['state']['warehouse_areas']['parent'];


	if (isset( $_REQUEST['sf'])) {
		$start_from=$_REQUEST['sf'];


	} else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else
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






	$_SESSION['state']['warehouse_area']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'parent'=>$parent);



	switch ($parent) {
	case('warehouse'):
		if (isset( $_REQUEST['warehouse']) and  is_numeric( $_REQUEST['warehouse']))
			$warehouse_id=$_REQUEST['warehouse'];
		else
			$warehouse_id=$_SESSION['state']['warehouse']['id'];




		$where=sprintf("where  `Warehouse Key`=%d",$warehouse_id);

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
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total `Warehouse Area Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
			mysql_free_result($result);
		}

	}

	$rtext=$total_records." ".ngettext('area','areas',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';
	$_dir=$order_direction;
	$_order=$order;

	$order='`Warehouse Area Code`';
	if ($order=='name')
		$order='`Warehouse Area Name`';
	elseif ($order=='code')
		$order='`Warehouse Area Code`';
	elseif ($order=='locations')
		$order='`Warehouse Area Number Locations`';
	elseif ($order=='shelfs')
		$order='`Warehouse Area Number Shelfs`';

	$sql="select *  from `Warehouse Area Dimension` WA left join `Warehouse Dimension` W  on (WA.`Warehouse Key`=W.`Warehouse Key`) $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
	// print $sql;
	$res = mysql_query($sql);
	$adata=array();

	$sum_active=0;


	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$code=sprintf('<a href="warehouse_area.php?id=%d">%s</a>',$row['Warehouse Area Key'],$row['Warehouse Area Code']);
		$name=sprintf('<a href="warehouse_area.php?id=%d">%s</a>',$row['Warehouse Area Key'],$row['Warehouse Area Name']);
		$warehouse=sprintf('<a href="warehouse.php?id=%d">%s</a>',$row['Warehouse Key'],$row['Warehouse Code']);

		$locations=number($row['Warehouse Area Number Locations']);
		$shelfs=number($row['Warehouse Area Number Shelfs']);

		$adata[]=array(
			'code'=>$code,
			'name'=>$name,
			'locations'=>$locations,
			'shelfs'=>$shelfs,
			'parts'=>number($row['Warehouse Area Distinct Parts']),
			'warehouse'=>$warehouse,

			'description'=>$row['Warehouse Area Description']

		);
	}
	mysql_free_result($res);









	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
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

function find_warehouse_area($data) {

	$q=$data['query'];

	$where='';
	if ( $data['parent_key'])
		$where=sprintf(' and `Warehouse Key`=%d ',$data['parent_key']);


	$sql=sprintf("select `Warehouse Area Key`,`Warehouse Area Code`,`Warehouse Area Name` from `Warehouse Area Dimension` where (`Warehouse Area Code`like '%s%%' or `Warehouse Area Name` like '%%%s%%'   )  %s  order by `Warehouse Area Name` limit 10 "
		,addslashes($q)
		,addslashes($q)
		,$where
	);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {
		$adata[]=array(
			'code'=>$row['Warehouse Area Code'],
			'key'=>$row['Warehouse Area Key'],

			'name'=>$row['Warehouse Area Name'],

		);
	}
	$response=array('data'=>$adata);
	echo json_encode($response);


}

function find_shelf_type($data) {

	$q=$data['query'];
	$where='';
	$sql=sprintf("select *  from `Shelf Type Dimension` where (`Shelf Type Name`like '%s%%' or `Shelf Type Description` like '%%%s%%'   )  %s  order by `Shelf Type Name` limit 10 "
		,addslashes($q)
		,addslashes($q)
		,$where
	);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {

		$info=sprintf("<h3>%s</h3><p>%s</p>",$row['Shelf Type Name'],$row['Shelf Type Description']);
		$adata[]=array(
			"key"=>$row['Shelf Type Key']
			,"name"=>$row['Shelf Type Name']
			,"description"=>$row['Shelf Type Description']
			,"type"=>$row['Shelf Type Type']
			,"rows"=>$row['Shelf Type Rows']
			,"columns"=>$row['Shelf Type Columns']
			,"l_height"=>$row['Shelf Type Location Height']
			,"l_length"=>$row['Shelf Type Location Length']
			,'l_deep'=>$row['Shelf Type Location Deep']
			,'l_weight'=>$row['Shelf Type Location Max Weight']
			,'l_volume'=>$row['Shelf Type Location Max Volume']
			,'info'=>$info


		);
	}
	$response=array('data'=>$adata);
	echo json_encode($response);


}




function find_location() {
	if (!isset($_REQUEST['query']))
		$q='';
	else
		$q=$_REQUEST['query'];
	$where='';
	if (isset($_REQUEST['parent']) and $_REQUEST['parent']=='warehouse')
		$where=sprintf(' and `Warehouse Key`=%d ',$_SESSION['state']['warehouse']['id']);

	if (isset($_REQUEST['except_location']) )
		$where=sprintf(' and LD.`Location Key`!=%d ',$_REQUEST['except_location']);


	$part_sku=0;
	if (isset($_REQUEST['get_data'])) {
		if (preg_match('/^sku\d+$/i',$_REQUEST['get_data']))
			$part_sku=preg_replace('/sku/','',$_REQUEST['get_data']);
	}


	if ($part_sku) {

		if (isset($_REQUEST['with'])) {
			if ($_REQUEST['with']=='stock')
				$where.=sprintf(' and (`Quantity On Hand` IS NOT NULL and `Quantity On Hand`>0 ')   ;
		}
		$sql=sprintf("select LD.`Location Key`,`Location Code`,(select `Quantity On Hand` from `Part Location Dimension` t where t.`Location Key`=LD.`Location Key` and `Part SKU`=%d) as `Quantity On Hand` from `Location Dimension` LD    where (`Location Code` like '%s%%' )  %s  order by `Location Code` limit 10 "
			,$part_sku
			,addslashes($q)
			,$where
		);

	} else {
		$sql=sprintf("select `Location Key`,`Location Code`,0 as `Quantity On Hand` from `Location Dimension` LD where (`Location Code` like '%s%%'    )  %s  order by `Location Code` limit 10 "
			,addslashes($q)
			,$where
		);
	}
	//  print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {
		if (!is_numeric($row['Quantity On Hand']))
			$row['Quantity On Hand']=0;
		$adata[]=array(

			'key'=>$row['Location Key'],
			'code'=>$row['Location Code'],
			'stock'=>$row['Quantity On Hand']
		);
	}
	$response=array('data'=>$adata);
	echo json_encode($response);


}


function history_stock_location() {


	$conf=$_SESSION['state']['location']['stock_history'];
	$location_id=$_SESSION['state']['location']['id'];
	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$conf['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf['to'];
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


	list($date_interval,$error)=prepare_mysql_dates($from,$to);
	if ($error) {
		list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
	} else {
		$_SESSION['state']['location']['stock_history']['from']=$from;
		$_SESSION['state']['location']['stock_history']['to']=$to;
	}



	$_SESSION['state']['location']['stock_history']['order']=$order;
	$_SESSION['state']['location']['stock_history']['order_dir']=$order_direction;
	$_SESSION['state']['location']['stock_history']['nr']=$number_results;
	$_SESSION['state']['location']['stock_history']['sf']=$start_from;
	$_SESSION['state']['location']['stock_history']['where']=$where;
	$_SESSION['state']['location']['stock_history']['f_field']=$f_field;
	$_SESSION['state']['location']['stock_history']['f_value']=$f_value;
	$_SESSION['state']['location']['stock_history']['from']=$from;
	$_SESSION['state']['location']['stock_history']['to']=$to;
	$_SESSION['state']['location']['stock_history']['elements']=$elements;


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';




	$wheref='';


	if ($f_field=='note' and $f_value!='')
		$wheref.=" and  `Product Note` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='author' and $f_value!='')
		$wheref.=" and  `User Alias` like '".addslashes($f_value)."%'";

	$where=$where.sprintf(" and `History Type`='Normal' and `Location Key`=%d  ",$location_id);


	//   $where =$where.$view.sprintf(' and product_id=%d  %s',$product_id,$date_interval);



	$sql="select count(*) as total from  `Inventory Transaction Fact`   $where $wheref";

	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from  `Inventory Transaction Fact`  $where ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
			mysql_free_result($result);
		}

	}


	$rtext=$total_records." ".ngettext('stock operation','stock operations',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';




	$sql=sprintf("select  *,IFNULL(ITF.`User Key`,-1) as user from `Inventory Transaction Fact` ITF left join `User Dimension` UD on (ITF.`User Key`=UD.`User Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ");
	// print $sql;

	$result=mysql_query($sql);
	$adata=array();
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

		if ($data['user']==-1)
			$author=_('Unknown');
		elseif ($data['user']==0)
			$author=_('System');
		else {
			$author=$data['User Alias'];

		}

		$tipo=$data['Inventory Transaction Type'];


		if ($tipo=='Move In' or $tipo=='Audit' or   $tipo=='Move Out' )
			$qty=number($data['Inventory Transaction Quantity']);
		else
			$qty='';

		$adata[]=array(

			'author'=>$author,
			'tipo'=>$tipo,
			'diff_qty'=>$qty,
			'diff_amount'=>money($data['Inventory Transaction Amount']),
			'note'=>$data['Note'],
			'date'=>strftime("%a %e %b %Y %T", strtotime($data['Date'].' UTC'))
		);
	}
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
			'records_perpage'=>$number_results
		)
	);
	echo json_encode($response);
}

function historic_parts_at_location() {
	$conf=$_SESSION['state']['location']['parts'];
	$location_id=$_SESSION['state']['location']['id'];

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

	if (isset( $_REQUEST['date']))
		$date=$_REQUEST['date'];
	else
		$date=date("Y-m-d");



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




	$_SESSION['state']['location']['parts']=
		array(
		'order'=>$order,
		'order_dir'=>$order_direction,
		//'nr'=>$number_results,
		// 'sf'=>$start_from,
		'where'=>$where,
		'f_field'=>$f_field,
		'f_value'=>$f_value,
		//  'from'=>$from,
		//  'to'=>$to,
		//  'elements'=>$elements
	);
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';




	//  $view='';
	//  foreach($elements as $key=>$val){
	//    if(!$val)
	//      $view.=' and op_tipo!='.$key;
	//  }


	$wheref='';
	//   if($f_field=='name' and $f_value!='')
	//     $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


	$start_from=0;
	$number_results=99999999;



	$where=$where.sprintf(" and `Location Key`=%d and Date=%s",$location_id,prepare_mysql($date));


	//   $where =$where.$view.sprintf(' and part_id=%d  %s',$part_id,$date_interval);

	$sql="select count(*) as total from `Inventory Spanshot Fact`   $where $wheref";
	//   print "$sql";

	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select  count(*) as total from `Inventory Spanshot Fact`  $where ";
		$res = mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}

	if ($order=='sku')
		$order='PD.`Part SKU`';

	if ($total_records==0)
		$rtext=_('No parts on this location');
	else
		$rtext=$total_records.' '.ngettext('part','parts',$total_records);

	if ($total_records>$number_results)
		$rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));





	$sql=sprintf("select  * from `Inventory Spanshot Fact` ISF left join `Part Dimension` PD on (PD.`Part SKU`=ISF.`Part SKU`)    $where $wheref    order by $order $order_direction  ");


	$adata=array();

	$res = mysql_query($sql);
	// print $sql;
	while ($data=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$loc_sku=$data['Location Key'].'_'.$data['Part SKU'];

		$adata[]=array(

			'sku'=>sprintf('<a href="part.php?sku=%d">%05d</a>',$data['Part SKU'],$data['Part SKU'])
			,'description'=>$data['Part Unit Description']
			,'current_qty'=>sprintf('<span  used="0"  value="%s" id="s%s"  onclick="fill_value(%s,%d,%d)">%s</span>',$data['Quantity On Hand'],$loc_sku,$data['Quantity On Hand'],$data['Location Key'],$data['Part SKU'],number($data['Quantity On Hand']))
			,'changed_qty'=>sprintf('<span   used="0" id="cs%s"  onclick="change_reset(\'%s\',%d)"   ">0</span>',$loc_sku,$loc_sku,$data['Part SKU'])
			,'new_qty'=>sprintf('<span  used="0"  value="%s" id="ns%s"  onclick="fill_value(%s,%d,%d)">%s</span>',$data['Quantity On Hand'],$loc_sku,$data['Quantity On Hand'],$data['Location Key'],$data['Part SKU'],number($data['Quantity On Hand']))
			,'_qty_move'=>'<input id="qm'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
			,'_qty_change'=>'<input id="qc'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
			,'_qty_damaged'=>'<input id="qd'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
			,'note'=>'<input  id="n'.$loc_sku.'" type="text" value="" style="width:100px">'
			,'delete'=>($data['Quantity On Hand']==0?'<img onclick="remove_prod('.$data['Location Key'].','.$data['Part SKU'].')" style="cursor:pointer" title="'._('Remove').' '.$data['Part SKU'].'" alt="'._('Desassociate Product').'" src="art/icons/cross.png".>':'')
		);
	}
	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'rtext'=>$rtext,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}


function parts_at_location() {
	$conf=$_SESSION['state']['location']['parts'];
	$location_id=$_REQUEST['id'];



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

	if (isset( $_REQUEST['date']))
		$date=$_REQUEST['date'];
	else
		$date=date("Y-m-d");



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


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];




	$_SESSION['state']['location']['parts']['order']=$order;
	$_SESSION['state']['location']['parts']['order_dir']=$order_direction;
	$_SESSION['state']['location']['parts']['where']=$where;
	$_SESSION['state']['location']['parts']['f_field']=$f_field;
	$_SESSION['state']['location']['parts']['f_value']=$f_value;
	$_SESSION['state']['location']['parts']['nr']=$number_results;
	$_SESSION['state']['location']['parts']['sf']=$start_from;


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';




	//  $view='';
	//  foreach($elements as $key=>$val){
	//    if(!$val)
	//      $view.=' and op_tipo!='.$key;
	//  }


	$wheref='';


	if ($f_field=='used_in' and $f_value!='')
		$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='supplied_by' and $f_value!='')
		$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='sku' and $f_value!='')
		$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";




	$where=$where.sprintf(" and PLD.`Location Key`=%d ",$location_id);


	//   $where =$where.$view.sprintf(' and part_id=%d  %s',$part_id,$date_interval);


	$sql="select count(*) as total from `Part Location Dimension` PLD  $where $wheref";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters `Part Location Dimension` PLD $where ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);



	$rtext=$total_records." ".ngettext('part','parts',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');
	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with ")." <b>".sprintf("SKU%05d",$f_value)."*</b> ";
			break;

		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part used in ")." <b>".$f_value."*</b> ";
			break;
		case('suppiled_by'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part supplied by ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {


		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with')." <b>".sprintf("SKU%05d",$f_value)."*</b>";
			break;

		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts used in')." <b>".$f_value."*</b>";
			break;
		case('supplied_by'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts supplied by')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';



	$order='PD.`Part SKU`';






	$sql=sprintf("select  * from `Part Location Dimension` PLD left join `Part Dimension` PD on (PD.`Part SKU`=PLD.`Part SKU`) left join `Location Dimension` LD on (LD.`Location Key`=PLD.`Location Key`)    $where $wheref    order by $order $order_direction  limit $start_from,$number_results ");
	//print $sql;

	$adata=array();

	$res = mysql_query($sql);

	while ($data=mysql_fetch_array($res, MYSQL_ASSOC)) {



		if ($data['Part Current Stock']==0 or !is_numeric($data['Quantity On Hand'])) {
			$move='';
		} else {
			if ($data['Quantity On Hand']==0)
				$move='<img src="art/icons/package_come.png" alt="'._('Move').'" />';
			else
				$move='<img src="art/icons/package_go.png" alt="'._('Move').'" />';

		}
		/*
	$min='0';
	$max='0';
	if($data['Can Pick']=='Yes'){
		$min=$data['Minimum Quantity'];
		$max=$data['Maximum Quantity'];
	}
*/
		$adata[]=array(

			'sku'=>sprintf('<a href="part.php?id=%d&edit_stock=1">SKU%05d</a>',$data['Part SKU'],$data['Part SKU']),
			'part_sku'=>$data['Part SKU'],
			'location_key'=>$data['Location Key'],
			'location'=>$data['Location Code'],
			'description'=>$data['Part Unit Description'].' ('.$data['Part XHTML Currently Used In'].')',
			'formated_qty'=>number($data['Quantity On Hand']),
			'qty'=>$data['Quantity On Hand'],
			'can_pick'=>($data['Can Pick']=='Yes'?_('Yes'):_('No')),
			'move'=>$move,
			'audit'=>'<img src="art/icons/page_white_edit.png" alt="'._('Audit').'" />',
			'lost'=>($data['Quantity On Hand']==0?'':'<img src="art/icons/package_delete.png" alt="'._('Set stock as damaged/lost').'" />'),
			'add'=>'<img src="art/icons/lorry.png" alt="'._('Add stock').'" />',
			'delete'=>($data['Quantity On Hand']==0?'<img src="art/icons/cross.png"  alt="'._('Free location').'" />':''),
			'number_locations'=>$data['Part Distinct Locations'],
			'number_qty'=>$data['Quantity On Hand'],
			'part_stock'=>$data['Part Current Stock'],
			'min'=>$data['Minimum Quantity'],
			'max'=>$data['Maximum Quantity']
		);

	}
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
			'records_perpage'=>$number_results
		)



	);
	echo json_encode($response);
}
function list_warehouses() {



	$conf=$_SESSION['state']['warehouses']['warehouses'];

	$conf_table='warehouses';


	if (isset( $_REQUEST['sf'])) {
		$start_from=$_REQUEST['sf'];


	} else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else
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






	$_SESSION['state'][$conf_table]['table']['order']=$order;
	$_SESSION['state'][$conf_table]['table']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_table]['table']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['table']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['table']['where']=$where;
	$_SESSION['state'][$conf_table]['table']['f_field']=$order;
	$_SESSION['state'][$conf_table]['table']['f_value']=$f_value;




	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Warehouse Name` like '".addslashes($f_value)."%'";
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Warehouse Code` like '".addslashes($f_value)."%'";





	$sql="select count(*) as total from `Warehouse Dimension`   $where $wheref";
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total `Warehouse Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
			mysql_free_result($result);
		}

	}

	$rtext=$total_records." ".ngettext('warehouse','warehouses',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';
	$_dir=$order_direction;
	$_order=$order;

	$order='`Warehouse Code`';
	if ($order=='name')
		$order='`Warehouse Name`';
	elseif ($order=='code')
		$order='`Warehouse Code`';
	elseif ($order=='locations')
		$order='`Warehouse Number Locations`';
	elseif ($order=='areas')
		$order='`Warehouse Number Areas`';
	elseif ($order=='shelfs')
		$order='`Warehouse Number Shelfs`';
	$sql="select *  from `Warehouse Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
	// print $sql;
	$res = mysql_query($sql);
	$adata=array();

	$sum_active=0;


	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$code=sprintf('<a href="warehouse.php?id=%d">%s</a>',$row['Warehouse Key'],$row['Warehouse Code']);
		$name=sprintf('<a href="warehouse.php?id=%d">%s</a>',$row['Warehouse Key'],$row['Warehouse Name']);
		$locations=number($row['Warehouse Number Locations']);
		$areas=number($row['Warehouse Number Areas']);
		$shelfs=number($row['Warehouse Number Shelfs']);

		$adata[]=array(
			'id'=>$row['Warehouse Key'],
			'code'=>$code,
			'name'=>$name,
			'locations'=>$locations,
			'areas'=>$areas,
			'shelfs'=>$shelfs


			//'description'=>$row['Warehouse Area Description']
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






function other_locations_quick_buttons($data) {


$sql=sprintf("select `Quantity On Hand`,L.`Location Key`,`Location Code` from `Part Location Dimension` B left join `Location Dimension` L on (B.`Location Key`=L.`Location Key`) where `Part Sku`=%d and B.`Location Key`not in (0,%d)",
	$data['sku'],
	$data['location_key']
);
//print $sql;

$res=mysql_query($sql);
$locations_data=array();
while ($row=mysql_fetch_assoc($res)) {
	$locations_data[]=array('location_key'=>$row['Location Key'],'location_code'=>$row['Location Code'],'stock'=>$row['Quantity On Hand']);
}


$number_cols=5;
$row=0;
$location_buttons=array();
$contador=0;
$_row_tmp='';



$other_locations_quick_buttons='<div class="options" style="xwidth:270px;padding:0px 0px 0px 0px;text-align:center;margin:0px" >
<table border=1 style="margin:auto" id="pack_it_buttons"><tr>'."\n";
foreach ($locations_data as $location_data) {



	if (fmod($contador,$number_cols)==0 and $contador>0)
		$_row_tmp.="</tr><tr>\n";

	$other_locations_quick_buttons.='<td onClick="select_move_location('.$location_data['location_key'].',\''.$location_data['location_code'].'\',\''.$location_data['stock'].'\')" >'.$location_data['location_code']."</td>\n";
	$contador++;
}
$other_locations_quick_buttons.='</tr></table></div>';



//print "\n $other_locations_quick_buttons \n\n";



$response=array(
	'state'=>200,
	'other_locations_quick_buttons'=>$other_locations_quick_buttons

);


echo json_encode($response);
}

?>
