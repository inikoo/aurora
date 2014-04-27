<?php
/*
 File: ar_parts.php

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/

require_once 'common.php';

require_once 'class.Part.php';
//require_once 'common_functions.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case ('parts_availability_timeline'):

	list_parts_availability_timeline();
	break;

case('get_part_elements_numbers'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'parent'=>array('type'=>'string')
		));
	get_part_elements_numbers($data);
	break;
case('get_inventory_assets_sales_data'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'=>array('type'=>'key'),
			'subject'=>array('type'=>'string'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));

	get_inventory_assets_sales_data($data);
	break;

case('get_part_sales_data'):
	$data=prepare_values($_REQUEST,array(
			'part_sku'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));

	$data['subject']='part';
	$data['subject_key']=$data['part_sku'];

	get_inventory_assets_sales_data($data);
	break;
case('get_part_category_sales_data'):
	$data=prepare_values($_REQUEST,array(
			'category_key'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	get_part_category_sales_data($data);
	break;
case('parts_at_date'):
	list_parts_at_date();
	break;

case('number_transactions_in_interval'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),

			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	number_transactions_in_interval($data);
	break;
case('parts_lists'):
	list_parts_lists();
	break;
case('part_transactions'):
	part_transactions();
	break;
case('find_part'):
	find_part();
	break;
case('parts'):
	list_parts();
	break;

case('part_stock_history'):
case('stock_history'):
	part_stock_history();
	break;
case('part_location_info'):
	$data=prepare_values($_REQUEST,array(
			'sku'=>array('type'=>'key'),
		));
	part_location_info($data);
	break;
case('part_categories'):
	list_part_categories();
	break;
case('warehouse_parts_stock_history'):
	warehouse_part_stock_history();
	break;
case ('supplier_products_in_part_historic'):
	list_supplier_products_in_part_historic();
	break;
default:

	$response=array('state'=>404,'msg'=>_('Operation not found'));
	echo json_encode($response);

}


function list_parts() {
	global $user;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		return;
	}
	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		return;
	}


	if ($parent=='category') {
		$conf_node='part_categories';
	}elseif ($parent=='list') {
		$conf_node='parts_list';
	}else {
		$conf_node='warehouse';
	}
	$conf=$_SESSION['state'][$conf_node]['parts'];

	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];

	else
		$view=$conf['view'];



	if (isset( $_REQUEST['list_key']))
		$list_key=$_REQUEST['list_key'];
	else
		$list_key=false;


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];

	} else
		$number_results=$conf['nr'];



	if (!is_numeric($number_results))
		$number_results=25;

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
		$awhere=addslashes($_REQUEST['where']);
	else
		$awhere=$conf['where'];


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

	if (isset( $_REQUEST['avg']))
		$avg=$_REQUEST['avg'];
	else
		$avg=$conf['avg'];


	if (isset( $_REQUEST['period']))
		$period=$_REQUEST['period'];
	else
		$period=$conf['period'];


	if (isset( $_REQUEST['percentage']))
		$percentage=$_REQUEST['percentage'];
	else
		$percentage=$conf['percentage'];

	if (isset( $_REQUEST['elements_type']))
		$elements_type=$_REQUEST['elements_type'];
	else {
		$elements_type=$conf['elements_type'];
	}



	$elements=$conf['elements'];
	if (isset( $_REQUEST['elements_InUse'])) {
		$elements['use']['InUse']=$_REQUEST['elements_InUse'];
	}
	if (isset( $_REQUEST['elements_NotInUse'])) {
		$elements['use']['NotInUse']=$_REQUEST['elements_NotInUse'];
	}
	if (isset( $_REQUEST['elements_InUse_bis'])) {
		$elements['use']['InUse']=$_REQUEST['elements_InUse_bis'];
	}
	if (isset( $_REQUEST['elements_NotInUse_bis'])) {
		$elements['use']['NotInUse']=$_REQUEST['elements_NotInUse_bis'];
	}

	if (isset( $_REQUEST['elements_InUse_tris'])) {

		$elements['use']['InUse']=$_REQUEST['elements_InUse_tris'];
	}
	if (isset( $_REQUEST['elements_NotInUse_tris'])) {
		$elements['use']['NotInUse']=$_REQUEST['elements_NotInUse_tris'];
	}

	//print_r($elements['use']);




	if (isset( $_REQUEST['elements_Keeping'])) {
		$elements['state']['Keeping']=$_REQUEST['elements_Keeping'];
	}
	if (isset( $_REQUEST['elements_NotKeeping'])) {
		$elements['state']['NotKeeping']=$_REQUEST['elements_NotKeeping'];
	}

	if (isset( $_REQUEST['elements_Discontinued'])) {
		$elements['state']['Discontinued']=$_REQUEST['elements_Discontinued'];
	}
	if (isset( $_REQUEST['elements_LastStock'])) {
		$elements['state']['LastStock']=$_REQUEST['elements_LastStock'];
	}

	if (isset( $_REQUEST['elements_Error'])) {
		$elements['stock_state']['Error']=$_REQUEST['elements_Error'];
	}
	if (isset( $_REQUEST['elements_Normal'])) {
		$elements['stock_state']['Normal']=$_REQUEST['elements_Normal'];
	}

	if (isset( $_REQUEST['elements_Excess'])) {
		$elements['stock_state']['Excess']=$_REQUEST['elements_Excess'];
	}
	if (isset( $_REQUEST['elements_Low'])) {
		$elements['stock_state']['Low']=$_REQUEST['elements_Low'];
	}
	if (isset( $_REQUEST['elements_VeryLow'])) {
		$elements['stock_state']['VeryLow']=$_REQUEST['elements_VeryLow'];
	}
	if (isset( $_REQUEST['elements_OutofStock'])) {
		$elements['stock_state']['OutofStock']=$_REQUEST['elements_OutofStock'];
	}


	if (isset( $_REQUEST['elements_None'])) {
		$elements['next_shipment']['None']=$_REQUEST['elements_None'];
	}
	if (isset( $_REQUEST['elements_Set'])) {
		$elements['next_shipment']['Set']=$_REQUEST['elements_Set'];
	}
	if (isset( $_REQUEST['elements_Overdue'])) {
		$elements['next_shipment']['Overdue']=$_REQUEST['elements_Overdue'];
	}



	$_SESSION['state'][$conf_node]['parts']['order']=$order;
	$_SESSION['state'][$conf_node]['parts']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_node]['parts']['nr']=$number_results;
	$_SESSION['state'][$conf_node]['parts']['sf']=$start_from;
	$_SESSION['state'][$conf_node]['parts']['where']=$awhere;
	$_SESSION['state'][$conf_node]['parts']['f_field']=$f_field;
	$_SESSION['state'][$conf_node]['parts']['f_value']=$f_value;
	$_SESSION['state'][$conf_node]['parts']['elements']=$elements;
	$_SESSION['state'][$conf_node]['parts']['elements_type']=$elements_type;

	$_SESSION['state'][$conf_node]['parts']['view']=$view;
	$_SESSION['state'][$conf_node]['parts']['percentage']=$percentage;
	$_SESSION['state'][$conf_node]['parts']['period']=$period;
	$_SESSION['state'][$conf_node]['parts']['avg']=$avg;

	//print $conf_node;



	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;

	if (!is_numeric($start_from))
		$start_from=0;
	if (!is_numeric($number_results))
		$number_results=25;


	include_once 'splinters/parts_prepare_list.php';


	if ($sql_type=='part')
		$sql="select count(Distinct P.`Part SKU`) as total from $table  $where $wheref";
	else
		$sql="select count(Distinct ITF.`Part SKU`) as total from $table  $where $wheref";


	//print $sql;

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		if ($sql_type=='part')
			$sql="select count(Distinct P.`Part SKU`) as total_without_filters from $table  $where ";
		else
			$sql="select count(Distinct ITF.`Part SKU`) as total_without_filters from $table  $where ";



		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	}

	//print $sql;


	$rtext=number($total_records)." ".ngettext('part','parts',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';
	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with ")." <b>".sprintf("SKU%05d",$f_value)."*</b> ";
			break;
		case('reference'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part reference ")." <b>".$f_value."*</b> ";
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
		case('reference'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts reference')." <b>".$f_value."*</b>";
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

	$period_tag=get_interval_db_name($period);



	$_order=$order;
	$_order_dir=$order_dir;

	if ($order=='stock')
		$order='`Part Current Stock`';
	elseif ($order=='sku')
		$order='`Part SKU`';
	elseif ($order=='reference')
		$order='`Part Reference`';
	elseif ($order=='description')
		$order='`Part Unit Description`';
	elseif ($order=='available_for')
		$order='`Part Available Days Forecast`';
	elseif ($order=='supplied_by')
		$order='`Part XHTML Currently Supplied By`';
	elseif ($order=='used_in')
		$order='`Part Currently Used In`';
	elseif ($order=='margin') {
		$order=' `Part '.$period_tag.' Acc Margin` ';
	} elseif ($order=='sold') {
		$order=' `Part '.$period_tag.' Acc Sold` ';
	} elseif ($order=='money_in') {
		$order=' `Part '.$period_tag.' Acc Sold Amount` ';
	} elseif ($order=='profit_sold') {

		$order=' `Part '.$period_tag.' Acc Profit` ';
	} elseif ($order=='avg_stock') {

		$order=' `Part '.$period_tag.' Acc AVG Stock` ';


	} elseif ($order=='avg_stockvalue') {

		$order=' `Part '.$period_tag.' Acc AVG Stock Value` ';

	} elseif ($order=='keep_days') {

		$order=' `Part '.$period_tag.' Acc Keeping Days` ';
	} elseif ($order=='outstock_days') {

		$order=' `Part '.$period_tag.' Acc Out of Stock Days` ';

	} elseif ($order=='unknown_days') {

		$order=' `Part '.$period_tag.' Acc Unknown Stock Days` ';

	} elseif ($order=='gmroi') {

		$order=' `Part '.$period_tag.' Acc GMROI` ';

	}elseif ($order=='stock_value') {

		$order=' `Part Current Value` ';

	}elseif ($order=='delta_money_in') {

		$order=' `Part '.$period_tag.' Acc 1YD Sold`';

	}elseif ($order=='delta_sold') {

		$order=' `Part '.$period_tag.' Acc 1YD Sold Amount`';

	}elseif ($order=='stock_days') {

		$order=' `Part Days Available Forecast`';

	}elseif ($order=='next_shipment') {

		$order=' `Part Next Supplier Shipment`';

	}else {

		$order='`Part SKU`';
	}

	$order='P.'.$order;

	$group='';

	if ($sql_type=='part')
		$sql="select *,IFNULL((select GROUP_CONCAT(L.`Location Key`,':',L.`Location Code`,':',`Can Pick`,':',`Quantity On Hand` SEPARATOR ',') from `Part Location Dimension` PLD  left join `Location Dimension` L on (L.`Location Key`=PLD.`Location Key`) where PLD.`Part SKU`=P.`Part SKU`),'') as location_data from $table  $where $wheref    order by $order $order_direction limit $start_from,$number_results    ";
	else
		$sql="select *,IFNULL((select GROUP_CONCAT(L.`Location Key`,':',L.`Location Code`,':',`Can Pick`,':',`Quantity On Hand` SEPARATOR ',') from `Part Location Dimension` PLD  left join `Location Dimension` L on (L.`Location Key`=PLD.`Location Key`) where PLD.`Part SKU`=P.`Part SKU`),'') as location_data from $table  $where $wheref  group by ITF.`Part SKU`  order by $order $order_direction limit $start_from,$number_results    ";

	//print $sql;

	$adata=array();
	$result=mysql_query($sql);

	//print $sql;
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
		//'location'=>sprintf(" <img style='width:12px;cursor:pointer' src='art/icons/info_bw.png' onClick='get_locations(this,%d)'> <b>%s</b> <span style='float:right;color:#777;margin-left:10px'>[<b>%d</b>,%d]</span>", $_id, $row['Location Code'],$stock_in_picking,$total_stock),


		if ($sql_type=='part') {
			$locations='<table border=0 style="width:150px">';
			$locations_data=preg_split('/,/',$data['location_data']);
			//print_r($locations_data);
			$i=0;
			foreach ($locations_data as $raw_location_data) {
				if ($raw_location_data!='') {
					//print_r($raw_location_data);
					$locations.='<tr style="border:none">';

					if ($i==0) {
						$locations.=sprintf("<td style='width:20px'><img style='width:14px;cursor:pointer' src='art/icons/edit.gif' onClick='get_locations(this,%d)'></td>",
							$data['Part SKU']
						);
					}else {
						$locations.="<td style='width:14px'></td>";
					}
					$locations_data=preg_split('/\:/',$raw_location_data);

					$locations.='<td><a href="location.php?id='.$locations_data[0].'">'.$locations_data[1].'</a></td><td style="text-align:right">'.number($locations_data[3]).'</td>';
					$locations.='</tr>';
					$i++;
				}
			}
			$locations.='</table>';
			//print $locations;
		}else {

			$locations='';
		}




		if ($avg=='totals') {
			$sold=number($data['Part '.$period_tag.' Acc Sold'],0);
			$given=number($data['Part '.$period_tag.' Acc Given']);
			$sold_amount=money($data['Part '.$period_tag.' Acc Sold Amount']);
			$abs_profit=money($data['Part '.$period_tag.' Acc Profit']);
			$profit_sold=money($data['Part '.$period_tag.' Acc Profit']);

			$delta_sold=delta($data['Part '.$period_tag.' Acc Sold'],$data['Part '.$period_tag.' Acc 1YB Sold']);
			$delta_sold_amount=delta($data['Part '.$period_tag.' Acc Sold Amount'],$data['Part '.$period_tag.' Acc 1YB Sold Amount']);


		} else {
			if ($avg=='week')
				$factor=$data['Part '.$period_tag.' Acc Keeping Days']/30.4368499;
			elseif ($avg=='week_eff')
				$factor=($data['Part '.$period_tag.' Acc Keeping Days']-$data['Part '.$period_tag.' Acc Out of Stock Days'])/30.4368499;
			elseif ($avg=='week')
				$factor=$data['Part '.$period_tag.' Acc Keeping Days']/7;
			elseif ($avg=='week_eff')
				$factor=($data['Part '.$period_tag.' Acc Keeping Days']-$data['Part '.$period_tag.' Acc Out of Stock Days'])/7;
			else
				$factor=1;
			if ($factor==0) {
				$sold=0;
				$given=0;
				$sold_amount=money(0);
				$abs_profit=money(0);
				$profit_sold=money(0);
			} else {
				$sold=number($data['Part '.$period_tag.' Acc Sold']/$factor);
				$given=number($data['Part '.$period_tag.' Acc Given']/$factor);
				$sold_amount=money($data['Part '.$period_tag.' Acc Sold Amount']/$factor);
				$abs_profit=money($data['Part '.$period_tag.' Acc Profit']/$factor);
				$profit_sold=money($data['Part '.$period_tag.' Acc Profit']/$factor);
			}
		}
		if ($given!=0)
			$sold="$sold ($given)";
		$margin=percentage($data['Part '.$period_tag.' Acc Margin'],1);
		$avg_stock=number($data['Part '.$period_tag.' Acc AVG Stock']);
		$avg_stockvalue=money($data['Part '.$period_tag.' Acc AVG Stock Value']);
		$keep_days=number($data['Part '.$period_tag.' Acc Keeping Days'],0);
		$outstock_days=percentage($data['Part '.$period_tag.' Acc Out of Stock Days'],$data['Part '.$period_tag.' Acc Keeping Days']);
		$unknown_days=percentage($data['Part '.$period_tag.' Acc Unknown Stock Days'],$data['Part '.$period_tag.' Acc Keeping Days']);
		$gmroi=number($data['Part '.$period_tag.' Acc GMROI'],0);


		if ($data['Part Next Supplier Shipment']) {
			$next_shipment=strftime("%a %e %b %Y", strtotime($data['Part Next Supplier Shipment'].' +0:00'));
		}else {
			$next_shipment='';
		}
		$stock_days=number($data['Part Days Available Forecast'],0);


		switch ($data['Part Stock State']) {
		case 'Excess':
			$stock_state=_('Excess');
			break;
		case 'Normal':
			$stock_state=_('Ok');
			break;
		case 'Low':
			$stock_state=_('Low');
			break;
		case 'VeryLow':
			$stock_state=_('Very Low');
			break;
		case 'OutofStock':
			$stock_state=_('Out of Stock');
			break;
		case 'Error':
			$stock_state=_('Error');
			break;
		default:
			$stock_state=$data['Part Stock State'];
		}

		$adata[]=array(
			'stock_days'=>$stock_days,
			'stock_state'=>$stock_state,
			'locations'=>$locations,
			'sku'=>sprintf('<a href="part.php?sku=%d">%06d</a>',$data['Part SKU'],$data['Part SKU']),
			'reference'=>sprintf('<a href="part.php?sku=%d">%s</a>',$data['Part SKU'],$data['Part Reference']),
			'description'=>$data['Part Unit Description'],
			'description_small'=>'<b>'.$data['Part Reference'].'</b> '.$data['Part Unit Description'],
			'tariff_code'=>$data['Part Tariff Code'],
			'used_in'=>$data['Part XHTML Currently Used In'],
			'supplied_by'=>$data['Part XHTML Currently Supplied By'],
			'stock'=>number($data['Part Current Stock']),
			'available_for'=>interval($data['Part XHTML Available For Forecast']),
			'stock_value'=>money($data['Part Current Value']),
			'sold'=>$sold,
			'delta_sold'=>$delta_sold,
			'given'=>$given,
			'money_in'=>$sold_amount,
			'delta_money_in'=>$delta_sold_amount,
			'profit'=>$abs_profit,
			'profit_sold'=>$profit_sold,
			'margin'=>$margin,
			'avg_stock'=>$avg_stock,
			'avg_stockvalue'=>$avg_stockvalue,
			'keep_days'=>$keep_days,
			'outstock_days'=>$outstock_days,
			'unknown_days'=>$unknown_days,
			'gmroi'=>$gmroi,
			'next_shipment'=>$next_shipment
		);
	}
	/*
        $total_title=_('Total');

        $adata[]=array(

                     'sku'=>$total_title,
                 );

        $total_records=ceil($total_records/$number_results)+$total_records;
    */
	$response=array('resultset'=>
		array(
			'state'=>200,
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

function list_parts_at_date() {
	global $user,$corporate_currency;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		return;
	}
	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		return;
	}
	if (isset( $_REQUEST['date']))
		$date=$_REQUEST['date'];
	else {
		return;
	}
	$conf=$_SESSION['state']['stock_history']['parts'];




	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];

	} else
		$number_results=$conf['nr'];



	if (!is_numeric($number_results))
		$number_results=25;

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


	$_SESSION['state']['stock_history']['parts']['order']=$order;
	$_SESSION['state']['stock_history']['parts']['order_dir']=$order_direction;
	$_SESSION['state']['stock_history']['parts']['nr']=$number_results;
	$_SESSION['state']['stock_history']['parts']['sf']=$start_from;
	$_SESSION['state']['stock_history']['parts']['f_field']=$f_field;
	$_SESSION['state']['stock_history']['parts']['f_value']=$f_value;



	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');







	$where=sprintf("where `Warehouse key`=%d and `Date`=%s  ",$parent_key,prepare_mysql($date));















	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='used_in' and $f_value!='')
		$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='supplied_by' and $f_value!='')
		$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='sku' and $f_value!='')
		$wheref.=" and  ISF.`Part SKU` ='".addslashes($f_value)."'";

	$sql="select count(Distinct P.`Part SKU`) as total from `Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)  $where $wheref";


	//print $sql;

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {


		$sql="select count(Distinct P.`Part SKU`) as total from `Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)  $where $wheref";



		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	}

	//print $sql;


	$rtext=number($total_records)." ".ngettext('part','parts',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';
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




	$_order=$order;
	$_order_dir=$order_dir;

	if ($order=='stock')
		$order='`Part Current Stock`';
	elseif ($order=='sku')
		$order='ISF.`Part SKU`';
	elseif ($order=='description')
		$order='`Part Unit Description`';
	elseif ($order=='locations')
		$order='locations';
	elseif ($order=='stock')
		$order='stock';
	elseif ($order=='value_at_cost')
		$order='value_at_cost';
	elseif ($order=='value_at_end_day')
		$order='value_at_end_day';
	elseif ($order=='commercial_value')
		$order='commercial_value';
	elseif ($order=='last_sold')
		$order='`Part Last Sale Date`';
	elseif ($order=='last_purchased')
		$order='`Part Last Purchase Date`';
	elseif ($order=='last_booked_in')
		$order='`Part Last Booked In Date`';
	elseif ($order=='delta_last_sold')
		$order='delta_last_sold';
	elseif ($order=='delta_last_purchased')
		$order='delta_last_purchased';
	elseif ($order=='delta_last_booked_in')
		$order='delta_last_booked_in';
	else {

		$order='`Part SKU`';
	}



	$group='';


	$sql=sprintf("select DATEDIFF(`Part Last Purchase Date`,%s) as delta_last_purchased,DATEDIFF(`Part Last Booked In Date`,%s) as delta_last_booked_in,DATEDIFF(`Part Last Sale Date`,%s) as delta_last_sold,`Part Last Sale Date`,`Part Last Booked In Date`,`Part Last Purchase Date`,ISF.`Part SKU`,count(DISTINCT `Location Key`) as locations,`Part Unit Description`,`Part XHTML Currently Used In`,sum(`Quantity On Hand`) as stock,sum(`Quantity Open`) as stock_open,sum(`Value At Cost`) as value_at_cost,sum(`Value At Day Cost`) as value_at_end_day,sum(`Value Commercial`) as commercial_value from `Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)  $where $wheref group by ISF.`Part SKU`   order by $order $order_direction limit $start_from,$number_results  ",
		prepare_mysql($date),
		prepare_mysql($date),
		prepare_mysql($date)
	);
	//print $sql;
	$adata=array();
	$result=mysql_query($sql);

	//print $sql;
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {




		$locations='';

		$adata[]=array(
			'locations'=>number($data['locations']),
			'sku'=>sprintf('<a href="part.php?sku=%d">%06d</a>',$data['Part SKU'],$data['Part SKU']),
			'description'=>$data['Part Unit Description'],
			'description_small'=>$data['Part Unit Description'].'<br/>'.$data['Part XHTML Currently Used In'],
			'stock'=>sprintf('<span title="%s: %s">%s</span>',_('Open Value'),number($data['stock_open']),number($data['stock'])),
			'value_at_cost'=>money($data['value_at_cost'],$corporate_currency),
			'value_at_end_day'=>money($data['value_at_end_day'],$corporate_currency),
			'commercial_value'=>money($data['commercial_value'],$corporate_currency),
			'last_sold'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Part Last Sale Date'].' +0:00')),
			'last_booked_in'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Part Last Booked In Date'].' +0:00')),
			'last_purchased'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Part Last Purchase Date'].' +0:00')),
			'delta_last_sold'=>number($data['delta_last_sold']),
			'delta_last_booked_in'=>number($data['delta_last_booked_in']),
			'delta_last_purchased'=>number($data['delta_last_purchased']),

		);
	}
	/*
        $total_title=_('Total');

        $adata[]=array(

                     'sku'=>$total_title,
                 );

        $total_records=ceil($total_records/$number_results)+$total_records;
    */
	$response=array('resultset'=>
		array(
			'state'=>200,
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




function part_location_info($data) {

	$part=new Part($data['sku']);


	$data=array(
		'description'=>'<span class="id">'.$part->get_sku().'</span><br/>'.$part->data['Part Unit Description'].'<br/>'._('Sold as').': '.$part->data['Part XHTML Currently Used In']
	);

	$response= array('state'=>200,'data'=>$data);
	echo json_encode($response);
	return;




}

function number_transactions_in_interval($data) {

	$parent=$data['parent'];

	$parent_key=$data['parent_key'];

	$from=$data['from'];
	$to=$data['to'];

	$transactions=array(
		'OIP'=>0,
		'Move'=>0,
		'In'=>0,
		'Out'=>0,
		'Audit'=>0,
		'NoDispatched'=>0
	);


	switch ($parent) {
	case 'part':
		$where=sprintf(' and `Part SKU`=%d',$parent_key);
		break;
	case 'warehouse':
		$where=sprintf(' and `Warehouse Key`=%d',$parent_key);
		break;
	case 'supplier_product':
		$where=sprintf(' and `Supplier Product ID`=%d',$parent_key);
		break;
	}


	$where_interval=prepare_mysql_dates($from,$to,'`Date`','dates_only.startend');
	$where_interval=$where_interval['mysql'];
	$sql=sprintf("select count(*) as number,`Inventory Transaction Section` from `Inventory Transaction Fact` where `Inventory Transaction Record Type`='Movement'   %s %s group by `Inventory Transaction Section`  ",
		$where,
		$where_interval
	);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$transactions[$row['Inventory Transaction Section']]=number($row['number']);

	}
	// }
	$response= array('state'=>200,'transactions'=>$transactions);
	echo json_encode($response);
}



function list_parts_lists() {

	global $user;

	$conf=$_SESSION['state']['products']['list'];
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
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))



		$awhere=$_REQUEST['where'];
	else
		$awhere=$conf['where'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	/*
        if (isset( $_REQUEST['store_id'])    ) {
            $store=$_REQUEST['store_id'];
            $_SESSION['state']['products']['store']=$store;
        } else
            $store=$_SESSION['state']['products']['store'];
    */

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state']['parts']['list']['order']=$order;
	$_SESSION['state']['parts']['list']['order_dir']=$order_direction;
	$_SESSION['state']['parts']['list']['nr']=$number_results;
	$_SESSION['state']['parts']['list']['sf']=$start_from;
	$_SESSION['state']['parts']['list']['where']=$awhere;
	$_SESSION['state']['parts']['list']['f_field']=$f_field;
	$_SESSION['state']['parts']['list']['f_value']=$f_value;



	$where=' where `List Scope`="Part"';


	$wheref='';

	$sql="select count(distinct `List Key`) as total from `List Dimension`  $where  ";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from `List Dimension` $where $wheref ";
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


	$rtext=number($total_records)." ".ngettext('List','Lists',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_("Showing all Lists");




	$filter_msg='';





	$_order=$order;
	$_dir=$order_direction;


	if ($order=='name')
		$order='`List Name`';
	elseif ($order=='creation_date')
		$order='`List Creation Date`';
	elseif ($order=='product_list_type')
		$order='`List Type`';

	else
		$order='`List Key`';


	$sql="select  `List Number Items`,CLD.`List key`,CLD.`List Name`,CLD.`List Parent Key`,CLD.`List Creation Date`,CLD.`List Type` from `List Dimension` CLD $where  order by $order $order_direction limit $start_from,$number_results";

	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





		$list_name=" <a href='parts_list.php?id=".$data['List key']."'>".$data['List Name'].'</a>';
		switch ($data['List Type']) {
		case 'Static':
			$list_type=_('Static');
			break;
		default:
			$list_type=_('Dynamic');
			break;

		}



		$adata[]=array(


			'list_type'=>$list_type,
			'name'=>$list_name,
			'key'=>$data['List key'],
			'creation_date'=>strftime("%a %e %b %y %H:%M", strtotime($data['List Creation Date']." +00:00")),
			'add_to_email_campaign_action'=>'<span class="state_details" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add List').'</span>',
			'delete'=>'<img src="art/icons/cross.png"/>',
			'items'=>number($data['List Number Items'])


		);

	}


	mysql_free_result($result);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function part_stock_history() {

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit();
	}
	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit();
	}

	if ($parent=='part') {
		$conf_base='part';
	}elseif ($parent=='supplier_product') {
		$conf_base='supplier_product';
	}else {
		exit("x");
	}


	$conf=$_SESSION['state'][$conf_base]['stock_history'];



	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$_SESSION['state'][$conf_base]['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state'][$conf_base]['to'];

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


	if (isset( $_REQUEST['timeline_group']))
		$timeline_group=$_REQUEST['timeline_group'];
	else
		$timeline_group=$conf['timeline_group'];





	$_SESSION['state'][$conf_base]['stock_history']['order']=$order;
	$_SESSION['state'][$conf_base]['stock_history']['timeline_group']=$timeline_group;
	$_SESSION['state'][$conf_base]['stock_history']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_base]['stock_history']['nr']=$number_results;
	$_SESSION['state'][$conf_base]['stock_history']['sf']=$start_from;
	$_SESSION['state'][$conf_base]['stock_history']['f_field']=$f_field;
	$_SESSION['state'][$conf_base]['stock_history']['f_value']=$f_value;

	$_SESSION['state'][$conf_base]['stock_history']['elements']=$elements;





	$date_interval=prepare_mysql_dates($from,$to,'`Date`','only_dates');


	if ($date_interval['error']) {

		$date_interval=prepare_mysql_dates($_SESSION['state'][$conf_base]['from'],$_SESSION['state'][$conf_base]['to']);
	} else {

		$_SESSION['state']['part']['from']=$date_interval['from'];
		$_SESSION['state']['part']['to']=$date_interval['to'];


	}

	//print_r($_SESSION['state']['part']['stock_history']);
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';

	$wheref='';




	switch ($timeline_group) {
	case 'month':
		$group=' group by DATE_FORMAT(`Date`,"%Y%m")   ';
		break;
	case 'day':
		$group=' group by `Date`   ';
		break;
	default:
		$group=' group by YEARWEEK(`Date`)   ';
		break;
	}


	switch ($parent) {
	case'part':
		$where=sprintf(" where `Part SKU`=%d ",$parent_key);
		$sub_where=sprintf(" where `Part SKU`=%d and OISF.`Date`=ISF.`Date`",$parent_key);

		break;
	case 'supplier_product':

		$supplier_product=new SupplierProduct('pid',$parent_key);
		$part_skus=$supplier_product->get_part_skus();
		$number_parts=count($part_skus);
		if ($number_parts==0) {
			$where=" where false ";
			$sub_where=" where false ";

		}elseif ($number_parts==1) {
			$part_sku=array_pop($part_skus);
			$where=sprintf(" where `Part SKU`=%d ",$part_sku);
			$sub_where=sprintf(" where `Part SKU`=%d and OISF.`Date`=ISF.`Date`",$part_sku);

		}else {
			$where=sprintf(" where `Part SKU` in (%s) ",join(',',$part_skus));
			$sub_where=sprintf(" where `Part SKU` in (%s) and OISF.`Date`=ISF.`Date`",join(',',$part_skus));

		}



		break;
	}

	$where.=$date_interval['mysql'];


	$sql="select count(*) as total from `Inventory Spanshot Fact`     $where $wheref $group";

	$result=mysql_query($sql);
	$total=mysql_num_rows($result);





	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Inventory Spanshot Fact`   $where  $group";



		$total_records=$result;
		$filtered=$total_records-$total;

	}




	switch ($timeline_group) {
	case 'month':
		$rtext=number($total_records).' '.ngettext('month','months',$total);
		break;
	case 'day':
		$rtext=number($total_records).' '.ngettext('day','days',$total);
		break;
	default:
		$rtext=number($total_records).' '.ngettext('week','weeks',$total);
		break;
	}



	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	if ($total_records==0) {
		$rtext=_('No stock history');
		$rtext_rpp='';
	}


	$order='`Date`';

	$sql=sprintf("select  GROUP_CONCAT(distinct '<a href=\"location.php?id=',ISF.`Location Key`,'\">',`Location Code`,'<a/>') as locations,`Date`, ( select  sum(`Quantity On Hand`) from `Inventory Spanshot Fact` OISF  %s)as `Quantity On Hand`, ( select  sum(`Value Commercial`) from `Inventory Spanshot Fact` OISF %s)as `Value Commercial`, ( select  sum(`Value At Day Cost`) from `Inventory Spanshot Fact` OISF  %s  )as `Value At Day Cost`, ( select  sum(`Value At Cost`) from `Inventory Spanshot Fact` OISF %s  )as `Value At Cost`,sum(`Sold Amount`) as `Sold Amount`,sum(`Storing Cost`) as `Storing Cost`,sum(`Quantity Sold`) as `Quantity Sold`,sum(`Quantity In`) as `Quantity In`,sum(`Quantity Lost`) as `Quantity Lost`  from `Inventory Spanshot Fact` ISF left join `Location Dimension` L on (ISF.`Location key`=L.`Location key`)  $where $wheref   %s order by $order $order_direction  limit $start_from,$number_results "
		,$sub_where
		,$sub_where
		,$sub_where
		,$sub_where
		,$group
	);

	// print $sql;

	$result=mysql_query($sql);
	$adata=array();
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



		switch ($timeline_group) {
		case 'month':
			$date=strftime("%m/%Y", strtotime($data['Date']));
			break;
		case 'day':
			$date=strftime("%a %d/%m/%Y", strtotime($data['Date']));
			break;
		default:
			$date=_('Week').' '.strftime("%V %Y", strtotime($data['Date']));
			break;
		}
		$adata[]=array(

			'date'=>$date.$_SESSION['state']['part']['stock_history']['from'],
			'locations'=>$data['locations'],
			'quantity'=>number($data['Quantity On Hand']),
			'value'=>money($data['Value At Cost']),
			'end_day_value'=>money($data['Value At Day Cost']),
			'commercial_value'=>money($data['Value Commercial']),
			'sold_qty'=>number($data['Quantity Sold']),
			'in_qty'=>number($data['Quantity In']),
			'lost_qty'=>number($data['Quantity Lost'])
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
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function warehouse_part_stock_history() {


	$conf=$_SESSION['state']['warehouse']['stock_history'];

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit("error no warehouyse key");
	}



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


	if (isset( $_REQUEST['timeline_group']))
		$timeline_group=$_REQUEST['timeline_group'];
	else
		$timeline_group=$conf['timeline_group'];





	$_SESSION['state']['warehouse']['stock_history']['order']=$order;
	$_SESSION['state']['warehouse']['stock_history']['timeline_group']=$timeline_group;
	$_SESSION['state']['warehouse']['stock_history']['order_dir']=$order_direction;
	$_SESSION['state']['warehouse']['stock_history']['nr']=$number_results;
	$_SESSION['state']['warehouse']['stock_history']['sf']=$start_from;
	$_SESSION['state']['warehouse']['stock_history']['f_field']=$f_field;
	$_SESSION['state']['warehouse']['stock_history']['f_value']=$f_value;

	$_SESSION['state']['warehouse']['stock_history']['elements']=$elements;





	$date_interval=prepare_mysql_dates($from,$to,'`Date`','only_dates');


	if ($date_interval['error']) {

		$date_interval=prepare_mysql_dates($_SESSION['state']['warehouse']['stock_history']['from'],$_SESSION['state']['warehouse']['stock_history']['to']);
	} else {

		$_SESSION['state']['warehouse']['stock_history']['from']=$date_interval['from'];
		$_SESSION['state']['warehouse']['stock_history']['to']=$date_interval['to'];


	}

	//print_r($_SESSION['state']['warehouse']['stock_history']);
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';

	$wheref='';


	$where=sprintf(" where `Warehouse Key`=%d %s",$parent_key,$date_interval['mysql']);

	switch ($timeline_group) {
	case 'month':
		$group=' group by DATE_FORMAT(`Date`,"%Y%m")   ';
		break;
	case 'day':
		$group=' group by `Date`   ';
		break;
	default:
		$group=' group by YEARWEEK(`Date`)   ';
		break;
	}





	$sql="select count(*) as total from `Inventory Warehouse Spanshot Fact`     $where $wheref $group";
	//print $sql;
	$result=mysql_query($sql);
	$total=mysql_num_rows($result);





	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Inventory Warehouse Spanshot Fact`   $where  $group";



		$total_records=$result;
		$filtered=$total_records-$total;

	}




	switch ($timeline_group) {
	case 'month':
		$rtext=number($total_records).' '.ngettext('month','months',$total);
		break;
	case 'day':
		$rtext=number($total_records).' '.ngettext('day','days',$total);
		break;
	default:
		$rtext=number($total_records).' '.ngettext('week','weeks',$total);
		break;
	}



	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	if ($total_records==0) {
		$rtext=_('No stock history');
		$rtext_rpp='';
	}


	$order='`Date`';

	$sql=sprintf("select * from `Inventory Warehouse Spanshot Fact`   $where $wheref %s   order by $order $order_direction  limit $start_from,$number_results ",
		$group



	);


	$result=mysql_query($sql);
	$adata=array();
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



		switch ($timeline_group) {
		case 'month':
			$date=strftime("%m/%Y", strtotime($data['Date']));
			break;
		case 'day':
			$date=strftime("%a %d/%m/%Y", strtotime($data['Date']));
			break;
		default:
			$date=_('Week').' '.strftime("%V %Y", strtotime($data['Date']));
			break;
		}

		$date=sprintf('<a href="stock_history_parts.php?warehouse_id=%d&date=%s">%s</a>',
			$parent_key,
			$data['Date'],
			$date
		);
		$parts=sprintf('<a href="stock_history_parts.php?warehouse_id=%d&date=%s">%s</a>',
			$parent_key,
			$data['Date'],
			number($data['Parts'])
		);

		$adata[]=array(

			'date'=>$date,
			'locations'=>number($data['Locations']),
			'parts'=>$parts,
			'value'=>money($data['Value At Cost']),
			'end_day_value'=>money($data['Value At Day Cost']),
			'commercial_value'=>money($data['Value Commercial'])
			//'sold_qty'=>number($data['Quantity Sold']),
			//'in_qty'=>number($data['Quantity In']),
			//'lost_qty'=>number($data['Quantity Lost'])
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
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function part_transactions() {



	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	}else {
		return;
	}


	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		return;
	}

	if ($parent=='part') {
		$conf=$_SESSION['state']['part']['transactions'];
		$conf_base=$_SESSION['state']['part'];
		$conf_field='part';
	}elseif ($parent=='warehouse') {
		$conf=$_SESSION['state']['warehouse']['transactions'];
		$conf_base=$_SESSION['state']['warehouse'];
		$conf_field='warehouse';
	}elseif ($parent=='supplier_product') {
		$conf=$_SESSION['state']['supplier_product']['transactions'];
		$conf_base=$_SESSION['state']['supplier_product'];
		$conf_field='supplier_product';
	}else {
		return;
	}

	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$_SESSION['state'][$conf_field]['from'];


	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state'][$conf_field]['to'];

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



	if (isset( $_REQUEST['transactions_type_elements_OIP'])) {
		$elements['OIP']=$_REQUEST['transactions_type_elements_OIP'];
	}
	if (isset( $_REQUEST['transactions_type_elements_Move'])) {
		$elements['Move']=$_REQUEST['transactions_type_elements_Move'];
	}
	if (isset( $_REQUEST['transactions_type_elements_In'])) {
		$elements['In']=$_REQUEST['transactions_type_elements_In'];
	}
	if (isset( $_REQUEST['transactions_type_elements_Out'])) {
		$elements['Out']=$_REQUEST['transactions_type_elements_Out'];
	}
	if (isset( $_REQUEST['transactions_type_elements_Audit'])) {
		$elements['Audit']=$_REQUEST['transactions_type_elements_Audit'];
	}if (isset( $_REQUEST['transactions_type_elements_NoDispatched'])) {
		$elements['NoDispatched']=$_REQUEST['transactions_type_elements_NoDispatched'];
	}


	$date_interval=prepare_mysql_dates($from,$to,'`Date`','only_dates');



	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state'][$conf_field]['from'],$_SESSION['state'][$conf_field]['to']);
	} else {

		$_SESSION['state'][$conf_field]['from']=$date_interval['from'];
		$_SESSION['state'][$conf_field]['to']=$date_interval['to'];
	}


	//print_r($_SESSION['state'][$conf_field]['transactions']);

	$_SESSION['state'][$conf_field]['transactions']['order']=$order;
	$_SESSION['state'][$conf_field]['transactions']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_field]['transactions']['nr']=$number_results;
	$_SESSION['state'][$conf_field]['transactions']['sf']=$start_from;
	$_SESSION['state'][$conf_field]['transactions']['f_field']=$f_field;
	$_SESSION['state'][$conf_field]['transactions']['f_value']=$f_value;
	$_SESSION['state'][$conf_field]['transactions']['elements']=$elements;
	//$_SESSION['state'][$conf_field]['transactions']['f_show']=$f_show;




	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';







	$wheref='';

	if ($f_field=='note' and $f_value!='') {
		// $wheref.=" and  `Note` like '%".addslashes($f_value)."%'  or  `Note` REGEXP '[[:<:]]".$f_value."'  ";
		$wheref.=" and  `Note` like '".addslashes($f_value)."%'  ";

	}

	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Date`');
	$where_interval=$where_interval['mysql'];



	if ($parent=='part') {
		$where=sprintf(" where `Part SKU`=%d %s ",$parent_key,$where_interval);
	}elseif ($parent=='warehouse') {
		$where=sprintf(" where `Warehouse Key`=%d %s ",$parent_key,$where_interval);
	}elseif ($parent=='supplier_product') {
		$where=sprintf(" where `Supplier Product ID`=%d %s ",$parent_key,$where_interval);
	}else {
		exit ("x");
	}


	$_elements='';
	$elements_count=0;
	foreach ($elements as $_key=>$_value) {


		if ($_value) {

			$elements_count++;



			$_elements.=','.prepare_mysql($_key);
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($elements_count==0) {
		$where.=' and false' ;
	} elseif ($elements_count<6) {
		$where.=' and `Inventory Transaction Section` in ('.$_elements.')' ;
	}else {
		$where.=' and `Inventory Transaction Record Type`="Movement"' ;
	}

	//print $where;



	$sql="select count(*) as total from `Inventory Transaction Fact` $where $wheref";
	//print $sql;exit;

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Inventory Transaction Fact` $where ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}







	$rtext=number($total_records)." ".ngettext('stock operation','stock operations',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('note'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("There isn't any note like")." <b>".$f_value."*</b> ";
			break;

		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('note'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total "._('notes with')." <b>".$f_value."*</b>";
			break;

		}
	}
	else
		$filter_msg='';



	$order=' `Date` desc  ';
	$order_direction=' ';

	if ($parent=='part' or 'supplier_product') {
		$sql="select `Inventory Transaction Section`,`Part Stock`,`Part Location Stock`,`User Alias`, ITF.`User Key`,`Required`,`Picked`,`Packed`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Date`,ITF.`Location Key`,`Location Code` ,ITF.`Inventory Transaction Key` from `Inventory Transaction Fact` ITF left join `Location Dimension` L on (ITF.`Location key`=L.`Location key`) left join `User Dimension` U on (ITF.`User Key`=U.`User Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ";
	}elseif ($parent=='warehouse') {
		$sql="select  `Part Stock`,`Part Location Stock`,`User Alias`,ITF.`User Key`,`Required`,`Picked`,`Packed`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Date`,ITF.`Location Key`,`Location Code` ,ITF.`Inventory Transaction Key` from `Inventory Transaction Fact` ITF left join `Location Dimension` L on (ITF.`Location key`=L.`Location key`) left join `User Dimension` U on (ITF.`User Key`=U.`User Key`)   $where $wheref limit $start_from,$number_results ";
	}


	//print $sql;exit;
	$result=mysql_query($sql);
	$adata=array();
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$qty=$data['Inventory Transaction Quantity'];



		if ($qty>0) {
			$qty='+'.$qty;
		}
		// elseif ($qty==0) {
		//   $qty=;
		//  }

		switch ($data['Inventory Transaction Type']) {
		case 'Order In Process':
			$transaction_type='OIP';
			$todo=$data['Required']+$data['Inventory Transaction Quantity'];
			if ($todo!=0) {
				$qty.='('.(-1*$todo).')';
			}
			break;

		case 'Move':
			$transaction_type=_('Move');
			$qty='&harr;';
			break;
		case 'Audit':
			$transaction_type=_('Audit');
			$qty='&#3663; <b>'.$data['Part Location Stock'].'</b>';
			break;
		case 'Lost':
			$transaction_type=_('Lost');
			break;
		case 'Broken':
			$transaction_type=_('Broken');
			break;
		case 'Other Out':
			$transaction_type=_('Out');
			break;
		default:
			$transaction_type=$data['Inventory Transaction Type'];
			break;
		}




		$location=sprintf('<a href="location.php?id=%d">%s</a>',$data['Location Key'],$data {'Location Code'});
		$adata[]=array(

			'type'=>$transaction_type,
			'change'=>$qty,
			'stock'=>number($data['Part Stock']),
			'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Date'].' +0:00')),
			'note'=>$data['Note'],//.$data['Inventory Transaction Key'],
			'location'=>$location,
			'user'=>$data['User Alias']


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
			'total_records'=>$total_records-$filtered,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}



function find_part() {

	if (!isset($_REQUEST['query']) or $_REQUEST['query']=='') {
		$response= array(
			'state'=>400,
			'data'=>array()
		);
		echo json_encode($response);
		return;
	}


	if (isset($_REQUEST['except']) and  isset($_REQUEST['except_id'])  and   is_numeric($_REQUEST['except_id']) and $_REQUEST['except']=='location' ) {

		$sql=sprintf("select `Part SKU`,`Part Unit Description`,`Part Currently Used In` from `Part Dimension` where  (`Part SKU`=%d or `Part XHTML Currently Used In` like '%%%s%%' ) limit 20 ",$_REQUEST['query'],addslashes($_REQUEST['query']));

	} else {
		$sql=sprintf("select `Part SKU`,`Part Unit Description`,`Part Currently Used In` from `Part Dimension` where  (`Part SKU`=%d or `Part XHTML Currently Used In` like '%%%s%%' ) limit 20",$_REQUEST['query'],addslashes($_REQUEST['query']));

	}
	//print $sql;

	$_data=array();
	$res=mysql_query($sql);

	//  $qty_on_hand=0;
	//        $location_key=$_REQUEST['except_id'];

	while ($data=mysql_fetch_array($res)) {
		//$loc_sku=$location_key.'_'.$data['Part SKU'];


		$_data[]= array(

			'info'=>sprintf("%s%05d - %s",_('SKU'),$data['Part SKU'],$data['Part Unit Description'])
			,'info_plain'=>sprintf("%s%05d - %s",_('SKU'),$data['Part SKU'],strip_tags($data['Part Unit Description']))

			,'sku'=>$data['Part SKU']
			,'formated_sku'=>sprintf("%s%05d",_('SKU'),$data['Part SKU'])
			,'description'=>$data['Part Unit Description']
			,'usedin'=>$data['Part Currently Used In']

			//  'sku'=>sprintf('<a href="part.php?sku=%d">%s</a>',$data['Part SKU'],$data['Part SKU'])
			// ,'description'=>$data['Part Unit Description']
			//  ,'current_qty'=>sprintf('<span  used="0"  value="%s" id="s%s"  onclick="fill_value(%s,%d,%d)">%s</span>',$qty_on_hand,$loc_sku,$qty_on_hand,$location_key,$data['Part SKU'],number($qty_on_hand))
			//     ,'changed_qty'=>sprintf('<span   used="0" id="cs%s"  onclick="change_reset(\'%s\',%d)"   ">0</span>',$loc_sku,$loc_sku,$data['Part SKU'])
			//     ,'new_qty'=>sprintf('<span  used="0"  value="%s" id="ns%s"  onclick="fill_value(%s,%d,%d)">%s</span>',$qty_on_hand,$loc_sku,$qty_on_hand,$location_key,$data['Part SKU'],number($qty_on_hand))
			//     ,'_qty_move'=>'<input id="qm'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
			//     ,'_qty_change'=>'<input id="qc'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
			//     ,'_qty_damaged'=>'<input id="qd'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
			//     ,'note'=>'<input  id="n'.$loc_sku.'" type="text" value="" style="width:100px">'
			//     ,'delete'=>($qty_on_hand==0?'<img onclick="remove_prod('.$location_key.','.$data['Part SKU'].')" style="cursor:pointer" title="'._('Remove').' '.$data['Part SKU'].'" alt="'._('Desassociate Product').'" src="art/icons/cross.png".>':'')
			//     ,'part_sku'=>$data['Part SKU']

		);
	}
	$response= array(
		'state'=>200,
		'data'=>$_data
	);
	echo json_encode($response);


}

function list_part_categories() {
	global $corporate_currency;

	$conf=$_SESSION['state']['part_categories']['subcategories'];
	$conf2=$_SESSION['state']['part_categories'];


	$parent_key=$_REQUEST['parent_key'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
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


	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];
		$_SESSION['state']['part_categories']['percentages']=$percentages;
	} else
		$percentages=$_SESSION['state']['part_categories']['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['part_categories']['period']=$period;
	} else
		$period=$_SESSION['state']['part_categories']['period'];




	if (isset( $_REQUEST['elements_type']))
		$elements_type=$_REQUEST['elements_type'];
	else
		$elements_type=$conf['elements_type'];

	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];




	if (isset( $_REQUEST['elements_part_category_NotInUse'])) {
		$elements['use']['NotInUse']=$_REQUEST['elements_part_category_NotInUse'];
	}
	if (isset( $_REQUEST['elements_part_category_InUse'])) {
		$elements['use']['InUse']=$_REQUEST['elements_part_category_InUse'];
	}





	$_SESSION['state']['part_categories']['subcategories']['order']=$order;
	$_SESSION['state']['part_categories']['subcategories']['order_dir']=$order_direction;
	$_SESSION['state']['part_categories']['subcategories']['nr']=$number_results;
	$_SESSION['state']['part_categories']['subcategories']['sf']=$start_from;
	$_SESSION['state']['part_categories']['subcategories']['f_field']=$f_field;
	$_SESSION['state']['part_categories']['subcategories']['f_value']=$f_value;
	$_SESSION['state']['part_categories']['subcategories']['period']=$period;
	$_SESSION['state']['part_categories']['subcategories']['elements']=$elements;
	$_SESSION['state']['part_categories']['subcategories']['elements_type']=$elements_type;




	$where=sprintf("where `Category Subject`='Part' and  `Category Parent Key`=%d",$parent_key);

	switch ($elements_type) {
	case 'use':
		$_elements='';
		$elements_count=0;
		foreach ($elements['use'] as $_key=>$_value) {
			if ($_value) {
				$elements_count++;



				$_elements.=','.prepare_mysql($_key);
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($elements_count==0) {
			$where.=' and false' ;
		} elseif ($elements_count==1) {
			$where.=' and `Part Category Status` in ('.$_elements.')' ;
		}
		break;


	default:
		$where.=' and false' ;

	}




	$group='';

	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";




	$sql="select count(*) as total   from `Category Dimension` C left join `Part Category Dimension` P on (P.`Part Category Key`=C.`Category Key`)   $where $wheref";

	//$sql=" describe `Category Dimension`;";
	// $sql="select *  from `Category Dimension` where `Category Parent Key`=1 ";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['total'];
		//   print_r($row);
	}
	mysql_free_result($res);

	//exit;
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total   from `Category Dimension` C left join `Part Category Dimension` P on (P.`Part Category Key`=C.`Category Key`)   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('category','categories',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with code like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with code like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;
	$period_tag=get_interval_db_name($period);

	if ($order=='subjects')
		$order='`Category Number Subjects`';

	elseif ($order=='sold') {
		$order='`Part Category '.$period_tag.' Acc Sold`';
	}  elseif ($order=='profit') {
		$order='`Part Category '.$period_tag.' Acc Profit`';
	}
	elseif ($order=='sales') {
		$order='`Part Category '.$period_tag.' Acc Sold Amount`';
	}elseif ($order=='delta_sales') {

		if ($period_tag=='Total' or $period_tag=='3 Year')
			$order='`Part Category '.$period_tag.' Acc Sold Amount`';

		else
			$order='`Part Category '.$period_tag.' Acc 1YD Sold Amount`';



	}
	elseif ($order=='code')
		$order='`Category Code`';




	$sql="select * from `Category Dimension` C left join `Part Category Dimension` P on (P.`Part Category Key`=C.`Category Key`) $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();


	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$sold=$row['Part Category '.$period_tag.' Acc Sold'];
		$amount=$row['Part Category '.$period_tag.' Acc Sold Amount'];



		$code=sprintf('<a href="part_category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Code']);
		$label=sprintf('<a href="part_category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Label']);

		if ($period_tag=='Total' or $period_tag=='3 Year')
			$delta_sales='';
		else
			$delta_sales=delta($row['Part Category '.$period_tag.' Acc Sold Amount'],$row['Part Category '.$period_tag.' Acc 1YB Sold Amount']);

		$adata[]=array(
			'id'=>$row['Category Key'],
			'code'=>$code,
			'label'=>$label,
			'subjects'=>number($row['Category Number Subjects']),
			'sold'=>number($sold,0),
			'sales'=>money($amount,$corporate_currency),
			'delta_sales'=>$delta_sales



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









function get_inventory_assets_sales_data($data) {
	global $corporate_currency;


	$subject=$data['subject'];

	$subject_key=$data['subject_key'];


	switch ($subject) {
	case 'part':
		$where=sprintf(" where `Part SKU`=%d  ",$subject_key);
		break;
	case 'supplier':
		$where=sprintf(" where `Supplier Key`=%d  ",$subject_key);
		break;
	case 'supplier_product':
		$where=sprintf(" where `Supplier Product ID`=%d  ",$subject_key);
		break;
	}

	$from_date=$data['from'];
	$to_date=$data['to'];

	if ($from_date)$from_date=$from_date.' 00:00:00';
	if ($to_date)$to_date=$to_date.' 23:59:59';
	$where_interval=prepare_mysql_dates($from_date,$to_date,'`Invoice Date`');
	$where_interval=$where_interval['mysql'];

	$sales=0;
	$profits=0;
	$profits_after_storing=0;
	$margin=0;
	$gmroi=0;
	$no_supplied=0;
	$given=0;
	$broken=0;
	$required=0;
	$sold=0;
	$lost=0;
	$adquired=0;
	$dispatched=0;

	$not_found=0;
	$out_of_stock=0;
	$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing
                     from `Inventory Transaction Fact` ITF   %s %s %s" ,
		$where,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

	);
	$result=mysql_query($sql);
	//   print "$sql\n";
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$profits=$row['profit'];
		$profits_after_storing=$row['profit']-$row['cost_storing'];

	}


	$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                     from `Inventory Transaction Fact` ITF  %s and `Inventory Transaction Type` like 'In'  %s %s" ,
		$where,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

	);
	$result=mysql_query($sql);
	//print "$sql\n";
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


		$adquired=$row['bought'];

	}


	$sql=sprintf("select sum(`Amount In`) as sold_amount,
                     sum(`Inventory Transaction Quantity`) as dispatched,
                     sum(`Required`) as required,
                     sum(`Given`) as given,
                     sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                     sum(-`Given`-`Inventory Transaction Quantity`) as sold
                     from `Inventory Transaction Fact` ITF  %s and `Inventory Transaction Type` like 'Sale'  %s %s" ,
		$where,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

	);
	$result=mysql_query($sql);
	//print "$sql\n";
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$sales=$row['sold_amount'];
		$sold=$row['sold'];
		$dispatched=-1.0*$row['dispatched'];
		$required=$row['required'];
		$given=$row['given'];

	}

	$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                     from `Inventory Transaction Fact` ITF   %s and `Inventory Transaction Type`='Broken'  %s %s" ,
		$where,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

	);
	$result=mysql_query($sql);

	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$broken=-1.*$row['broken'];

	}

	$sql=sprintf("select sum(`Inventory Transaction Quantity`) as not_found
                     from `Inventory Transaction Fact` ITF   %s and  `Inventory Transaction Type`='Not Found' %s %s" ,
		$where,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')
	);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$not_found=-1.*$row['not_found'];

	}

	$sql=sprintf("select sum(`Inventory Transaction Quantity`) as out_of_stock
                     from `Inventory Transaction Fact` ITF   %s and `Inventory Transaction Type`='Out of Stock'  %s %s" ,
		$where,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')
	);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$out_of_stock=-1.*$row['out_of_stock'];

	}



	$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                     from `Inventory Transaction Fact` ITF   %s and  `Inventory Transaction Type` like 'Lost'  %s %s" ,
		$where,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

	);
	$result=mysql_query($sql);
	//print "$sql\n";
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$lost=-1.*$row['lost'];

	}


	if ($sales!=0)
		$margin=$profits_after_storing/$sales;
	else
		$margin=0;


	$no_supplied=$not_found+$out_of_stock;
	$response= array('state'=>200,

		'sales'=>money($sales,$corporate_currency),
		'profits'=>money($profits,$corporate_currency),
		'profits_after_storing'=>money($profits_after_storing,$corporate_currency),
		'margin'=>number($margin),
		'gmroi'=>number($gmroi),
		'no_supplied'=>number($no_supplied),
		'not_found'=>number($not_found),
		'out_of_stock'=>number($out_of_stock),

		'given'=>number($given),
		'broken'=>number($broken),
		'required'=>number($required),
		'sold'=>number($sold),
		'lost'=>number($lost),
		'adquired'=>number($adquired),
		'dispatched'=>number($dispatched)
	);

	echo json_encode($response);




}


function get_part_category_sales_data($data) {
	global $corporate_currency;

	$category_key=$data['category_key'];
	$from_date=$data['from'];
	$to_date=$data['to'];

	if ($from_date)$from_date=$from_date.' 00:00:00';
	if ($to_date)$to_date=$to_date.' 23:59:59';
	$where_interval=prepare_mysql_dates($from_date,$to_date,'`Invoice Date`');
	$where_interval=$where_interval['mysql'];

	$sales=0;
	$profits=0;
	$profits_after_storing=0;
	$margin=0;
	$gmroi=0;
	$no_supplied=0;
	$given=0;
	$broken=0;
	$required=0;
	$sold=0;
	$lost=0;
	$adquired=0;
	$dispatched=0;

	$not_found=0;
	$out_of_stock=0;


	$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing
                     from `Inventory Transaction Fact` ITF   left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Category Key`=%d %s %s" ,
		$category_key,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

	);
	$result=mysql_query($sql);
	//   print "$sql\n";
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$profits=$row['profit'];
		$profits_after_storing=$row['profit']-$row['cost_storing'];

	}


	$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                     from `Inventory Transaction Fact` ITF   left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')  where `Inventory Transaction Type` like 'In'  and `Category Key`=%d  %s %s" ,
		$category_key,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

	);
	$result=mysql_query($sql);
	//print "$sql\n";
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


		$adquired=$row['bought'];

	}


	$sql=sprintf("select sum(`Amount In`) as sold_amount,
                     sum(`Inventory Transaction Quantity`) as dispatched,
                     sum(`Required`) as required,
                     sum(`Given`) as given,
                     sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                     sum(-`Given`-`Inventory Transaction Quantity`) as sold
                     from `Inventory Transaction Fact` ITF  left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Inventory Transaction Type` like 'Sale' and `Category Key`=%d %s %s" ,
		$category_key,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

	);
	$result=mysql_query($sql);
	//print "$sql\n";
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$sales=$row['sold_amount'];
		$sold=$row['sold'];
		$dispatched=-1.0*$row['dispatched'];
		$required=$row['required'];
		$given=$row['given'];

	}

	$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                     from `Inventory Transaction Fact` ITF  left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Inventory Transaction Type`='Broken' and `Category Key`=%d %s %s" ,
		$category_key,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

	);
	$result=mysql_query($sql);
	//print "$sql\n";
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$broken=-1.*$row['broken'];

	}

	$sql=sprintf("select sum(`Inventory Transaction Quantity`) as not_found
	                     from `Inventory Transaction Fact` ITF  left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Inventory Transaction Type`='Not Found' and `Category Key`=%d %s %s" ,

		$category_key,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')
	);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$not_found=-1.*$row['not_found'];

	}

	$sql=sprintf("select sum(`Inventory Transaction Quantity`) as out_of_stock
	                     from `Inventory Transaction Fact` ITF  left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Inventory Transaction Type`='Out of Stock' and `Category Key`=%d %s %s" ,
		$category_key,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')
	);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$out_of_stock=-1.*$row['out_of_stock'];

	}



	$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
	                     from `Inventory Transaction Fact` ITF  left join `Category Bridge` on (`Part SKU`=`Subject Key` and `Subject`='Part')   where `Inventory Transaction Type` like 'Lost' and `Category Key`=%d %s %s" ,

		$category_key,
		($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

	);
	$result=mysql_query($sql);
	//print "$sql\n";
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$lost=-1.*$row['lost'];

	}

	if ($sales!=0)
		$margin=$profits_after_storing/$sales;
	else
		$margin=0;

	$no_supplied=$not_found+$out_of_stock;
	$response= array('state'=>200,

		'sales'=>money($sales,$corporate_currency),
		'profits'=>money($profits,$corporate_currency),
		'profits_after_storing'=>money($profits_after_storing,$corporate_currency),
		'margin'=>number($margin),
		'gmroi'=>number($gmroi),
		'no_supplied'=>number($no_supplied),
		'not_found'=>number($not_found),
		'out_of_stock'=>number($out_of_stock),

		'given'=>number($given),
		'broken'=>number($broken),
		'required'=>number($required),
		'sold'=>number($sold),
		'lost'=>number($lost),
		'adquired'=>number($adquired),
		'dispatched'=>number($dispatched)
	);

	echo json_encode($response);




}



function get_part_elements_numbers($data) {

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];

	$elements_numbers=array(
		'InUse'=>0,'NotInUse'=>0,
		'Keeping'=>0,'LastStock'=>0,'Discontinued'=>0,'NotKeeping'=>0,
		'Excess'=>0,'Normal'=>0,'Low'=>0,'VeryLow'=>0,'OutofStock'=>0,'Error'=>0,
		'None'=>0,'Set'=>0,'Overdue'=>0
	);

	if ($parent=='warehouse') {

		$sql=sprintf("select count(*) as num ,`Part Status` from  `Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)  where B.`Warehouse Key`=%d group by  `Part Status`   ",
			$parent_key);
		//print_r($sql);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[preg_replace('/\s/','',$row['Part Status'])]=number($row['num']);

		}

		$sql=sprintf("select count(*) as num ,`Part Main State` from  `Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)  where B.`Warehouse Key`=%d group by  `Part Main State`   ",
			$parent_key);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Part Main State']]=number($row['num']);
		}

		$_elements='';
		$elements_count=0;
		foreach ($_SESSION['state']['warehouse']['parts']['elements']['use'] as $_key=>$_value) {
			if ($_value) {
				$elements_count++;

				if ($_key=='InUse') {
					$_key='In Use';
				}else {
					$_key='Not In Use';
				}

				$_elements.=','.prepare_mysql($_key);
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($elements_count==0) {
			$where=' and false' ;
		} elseif ($elements_count==1) {
			$where=' and `Part Status` in ('.$_elements.')' ;
		}else {
			$where='';
		}

		$sql=sprintf("select count(*) as num ,`Part Stock State` from  `Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)  where B.`Warehouse Key`=%d %s group by  `Part Stock State`   ",
			$parent_key,
			$where
		);
		//print_r($sql);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Part Stock State']]=number($row['num']);
		}

		$sql=sprintf("select count(*) as num ,`Part Next Shipment State` from  `Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)  where B.`Warehouse Key`=%d %s group by  `Part Next Shipment State`   ",
			$parent_key,
			$where
		);
		//print_r($sql);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Part Next Shipment State']]=number($row['num']);
		}





	}
	elseif ($parent=='category') {

		$sql=sprintf("select count(*) as num ,`Part Status` from  `Part Dimension` P left join `Category Bridge` B on (P.`Part SKU`=B.`Subject Key`)  where B.`Category Key`=%d and `Subject`='Part' group by  `Part Status`   ",
			$parent_key);
		//print_r($sql);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[preg_replace('/\s/','',$row['Part Status'])]=number($row['num']);

		}

		$sql=sprintf("select count(*) as num ,`Part Main State` from  `Part Dimension` P left join `Category Bridge` B on (P.`Part SKU`=B.`Subject Key`)  where B.`Category Key`=%d and `Subject`='Part' group by  `Part Main State`   ",
			$parent_key);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Part Main State']]=number($row['num']);

		}

		$_elements='';
		$elements_count=0;
		foreach ($_SESSION['state']['warehouse']['parts']['elements']['use'] as $_key=>$_value) {
			if ($_value) {
				$elements_count++;

				if ($_key=='InUse') {
					$_key='In Use';
				}else {
					$_key='Not In Use';
				}

				$_elements.=','.prepare_mysql($_key);
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($elements_count==0) {
			$where=' and false' ;
		} elseif ($elements_count==1) {
			$where=' and `Part Status` in ('.$_elements.')' ;
		}else {
			$where='';
		}


		$sql=sprintf("select count(*) as num ,`Part Stock State` from  `Part Dimension` P left join `Category Bridge` B on (P.`Part SKU`=B.`Subject Key`)  where B.`Category Key`=%d and `Subject`='Part' %s group by  `Part Stock State`   ",
			$parent_key,
			$where
		);
		//print_r($sql);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Part Stock State']]=number($row['num']);

		}



		$sql=sprintf("select count(*) as num ,`Part Next Shipment State` from  `Part Dimension` P left join `Category Bridge` B on (P.`Part SKU`=B.`Subject Key`)  where B.`Category Key`=%d and `Subject`='Part' %s group by   `Part Next Shipment State`   ",
			$parent_key,
			$where
		);
		//print_r($sql);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Part Next Shipment State']]=number($row['num']);
		}



	}
	elseif ($parent=='list') {


		$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$parent_key);
		//print $sql;exit;
		$res=mysql_query($sql);
		if ($list_data=mysql_fetch_assoc($res)) {
			$awhere=false;
			if ($list_data['List Type']=='Static') {

				$table='`List Part Bridge` PB left join `Part Dimension` P  on (PB.`Part SKU`=P.`Part SKU`)';
				$where=sprintf(' where `List Key`=%d ',$parent_key);

			} else {
				$tmp=preg_replace('/\\\"/','"',$list_data['List Metadata']);
				$tmp=preg_replace('/\\\\\"/','"',$tmp);
				$tmp=preg_replace('/\'/',"\'",$tmp);

				$raw_data=json_decode($tmp, true);
				//print_r($raw_data);
				//$raw_data['store_key']=$store;
				list($where,$table,$sql_type)=parts_awhere($raw_data);
			}

		} else {

		}

		$sql=sprintf("select count(distinct P.`Part SKU`) as num ,`Part Status` from  %s %s group by  `Part Status`   ",
			$table,
			$where
		);
		//print_r($sql);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[preg_replace('/\s/','',$row['Part Status'])]=number($row['num']);

		}

		$sql=sprintf("select count(distinct P.`Part SKU`) as num ,`Part Main State` from     %s %s group by  `Part Main State`   ",
			$table,
			$where
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Part Main State']]=number($row['num']);

		}

		$_elements='';
		$elements_count=0;
		foreach ($_SESSION['state']['warehouse']['parts']['elements']['use'] as $_key=>$_value) {
			if ($_value) {
				$elements_count++;

				if ($_key=='InUse') {
					$_key='In Use';
				}else {
					$_key='Not In Use';
				}

				$_elements.=','.prepare_mysql($_key);
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($elements_count==0) {
			$where=' and false' ;
		} elseif ($elements_count==1) {
			$where=' and `Part Status` in ('.$_elements.')' ;
		}else {
			$where='';
		}

		$sql=sprintf("select count(distinct P.`Part SKU`) as num ,`Part Stock State` from %s %s group by  `Part Stock State`   ",
			$table,
			$where
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			if ($row['Part Stock State']!='')
				$elements_numbers[$row['Part Stock State']]=number($row['num']);

		}




		$sql=sprintf("select count(distinct P.`Part SKU`) as num  ,`Part Next Shipment State`  from %s %s group by  group by   `Part Next Shipment State`   ",
			$parent_key,
			$where
		);
		//print_r($sql);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Part Next Shipment State']]=number($row['num']);
		}



	}

	$response= array('state'=>200,'elements_numbers'=>$elements_numbers);
	echo json_encode($response);

}

function list_parts_availability_timeline() {

	include_once 'common_date_functions.php';

	global $user;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		$parent_key='';

	$conf_var='page_changelog';

	if ($parent=='part') {
		$conf=$_SESSION['state']['part']['availability'];
		$conf_table='store';
	}
	elseif ($parent=='warehouse') {
		$conf=$_SESSION['state']['warehouse']['product_changelog'];
		$conf_table='department';
	}
	else {

		exit;
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
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state'][$conf_table][$conf_var]['order']=$order;
	$_SESSION['state'][$conf_table][$conf_var]['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table][$conf_var]['nr']=$number_results;
	$_SESSION['state'][$conf_table][$conf_var]['sf']=$start_from;
	$_SESSION['state'][$conf_table][$conf_var]['f_field']=$f_field;
	$_SESSION['state'][$conf_table][$conf_var]['f_value']=$f_value;


	$_order=$order;
	$_dir=$order_direction;

	if (count($user->stores)==0) {
		$where='where false ';
	}else {
		$where='where true ';
	}

	switch ($parent) {
	case('part'):
		$where.=sprintf(' and PAPT.`Part SKU`=%d',$parent_key);
		break;

	case('warehouse'):
		$where.=sprintf(' and PAPT.`Warehouse Key`=%d',$parent_key);
		break;

	default:
		exit();
		break;

	}



	$wheref='';
	if ($f_field=='user'  and $f_value!='')
		$wheref.=" and `User Alias` like '".addslashes($f_value)."%'";
	elseif ($f_field=='handle' and $f_value!='')
		$wheref.=" and `User Handle` like '%".addslashes($f_value)."%'";






	$sql="select  count(*) as total from `Part Availability for Products Timeline` PAPT  $where   ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select  count(*) as total_without_filters from `Part Availability for Products Timeline` PAPT  left join `User Dimension` UD on (PAPT.`User Key` = UD.`User Key`)  $where  $wheref ";


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







	$rtext=number($total_records)." ".ngettext('change','changes',$total_records);





	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	$filter_msg='';

	switch ($f_field) {
	case('user'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any change done by")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('changes done by')." <b>$f_value</b>*)";
		break;
	case('handle'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any change done by")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('changes done by')." <b>$f_value</b>*)";
		break;

	}



	$_order=$order;
	$_dir=$order_direction;


	if ($order=='handle') {
		$order='`User Handle`';
	}if ($order=='user') {
		$order='`User Alias`';
	}if ($order=='reference') {
		$order='`Part Reference`';
	}if ($order=='availability') {
		$order='`Availability for Products`';
	}if ($order=='duration') {
		$order='`Duration`';
	}else {



		$order='`Date`';
	}

	$sql=sprintf("select `Duration`, PAPT.`Part SKU`,`Part Reference`,PAPT.`User Key`,`User Handle`,`User Alias`,`Availability for Products`,`Date`  from `Part Availability for Products Timeline` PAPT  left join `Part Dimension` PD on (PAPT.`Part SKU` = PD.`Part SKU`) left join `User Dimension` UD on (PAPT.`User Key` = UD.`User Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ");



	$result=mysql_query($sql);

	//print $sql;
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

		switch ($row['Availability for Products']) {
		case('Yes'):
			$availability=_('Yes');
			break;
		case('No'):
			$availability=_('No');
			break;

		default:
			$availability=$row['Availability for Products'];
			break;
		}

		$duration=gettext_relative_time($row['Duration']);

		$data[]=array(
			'reference'=>sprintf("<a href='part.php?sku=%d'>%s</a>",$row['Part SKU'],$row['Part Reference']),
			'user'=>sprintf("<a href='user.php?id=%d'>%s</a>",$row['User Key'],$row['User Alias']),
			'handle'=>sprintf("<a href='user.php?id=%d'>%s</a>",$row['User Key'],$row['User Handle']),
			'date'=>strftime("%a %e %b %y %H:%M %Z", strtotime($row['Date']." +00:00")),
			'availability'=>$availability,
			'duration'=>$duration




		);


	}


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_dir'=>$_dir,
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

function list_supplier_products_in_part_historic() {

	$conf=$_SESSION['state']['part']['supplier_products'];


	if (isset( $_REQUEST['sku']))
		$sku=$_REQUEST['sku'];
	else {
		exit("");
	}


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];


	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
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



	$_SESSION['state']['part']['supplier_products']['order']=$order;
	$_SESSION['state']['part']['supplier_products']['order_dir']=$order_direction;
	$_SESSION['state']['part']['supplier_products']['nr']=$number_results;
	$_SESSION['state']['part']['supplier_products']['sf']=$start_from;
	$_SESSION['state']['part']['supplier_products']['f_field']=$f_field;
	$_SESSION['state']['part']['supplier_products']['f_value']=$f_value;




	$filter_msg='';

	$wheref='';
	$where=sprintf("where `Supplier Product Part Most Recent`='No' and  `Part SKU`=%d ",$sku);;

	if ($f_field=='code' and $f_value!='')
		$wheref.=sprintf(" and `Supplier Product Code` like '%s%%'   ",addslashes($f_value));



	$sql="select count(*) as total from `Supplier Product Part List`  L  left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`) left join `Supplier Product Dimension` P on (P.`Supplier Product ID`=PP.`Supplier Product ID`)left join `Supplier Dimension` S on (P.`Supplier Key`=S.`Supplier Key`)  $where $wheref";
	//  print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Supplier Product Part List`  L  left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`) left join `Supplier Product Dimension` P on (P.`Supplier Product ID`=PP.`Supplier Product ID`)left join `Supplier Dimension` S on (P.`Supplier Key`=S.`Supplier Key`)  $where ";
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with this code ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('product with code like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('product with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';
	/*  }else{//products parts for new product */

	/*      $total=count($_SESSION['state']['new_product']['parts']); */
	/*      $total_records=$total; */
	/*      $filtered=0; */
	/*    } */






	$_dir=$order_direction;
	$_order=$order;


	if ($order=='from') {
		$order='`Supplier Product Part Valid From`';
	}else if ($order=='to') {
			$order='`Supplier Product Part Valid To`';
		}else if ($order=='code') {
			$order='`Supplier Product Code`';
		}else if ($order=='name') {
			$order='`Supplier Product Name`';
		}else if ($order=='supplier') {
			$order='`Supplier Code`';
		}else if ($order=='relation') {
			$order='`SSupplier Product Units Per Part`';
		}else {
		$order='`Part SKU`';
	}

	$sql="select `Supplier Product Part Valid From`,`Supplier Product Part Valid To`,`Supplier Product Status`,`Supplier Product Part Most Recent`,`Supplier Product Part Valid To`,`Supplier Product Part Valid From`,P.`Supplier Product ID`,`Supplier Product Part List Key`,`Supplier Product Part In Use`,`Supplier Product Name`,`Supplier Product Units Per Part`,`Part SKU`,`Supplier Product Code` ,S.`Supplier Code`,S.`Supplier Key`
		from `Supplier Product Part List` L
		left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`)
		left join `Supplier Product Dimension` P on (P.`Supplier Product ID`=PP.`Supplier Product ID`)
		left join `Supplier Dimension` S on (P.`Supplier Key`=S.`Supplier Key`) $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);
	$total=mysql_num_rows($res);
	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC) ) {
		// $meta_data=preg_split('/,/',$row['Deal Component Allowance']);

		if ($row['Supplier Product Part In Use']=='Yes') {
			$available_state=_('Available');
		} else {
			$available_state=_('No available');
		}

		if ($row['Supplier Product Status']=='In Use') {
			$formated_status=_('Ok');
		} else {
			$formated_status=_('Discontinued');
		}




		$relation=$row['Supplier Product Units Per Part'].' &rarr; 1';
		$adata[]=array(
			'sppl_key'=>$row['Supplier Product Part List Key'],
			'sku'=>$row['Part SKU'],
			'relation'=>$relation,
			'code'=>'<a href="supplier_product.php?pid='.$row['Supplier Product ID'].'">'.$row['Supplier Product Code'].' ('.$row['Supplier Product ID'].')'.'</a>',
			'name'=>$row['Supplier Product Name'],
			'supplier'=>'<a href="supplier.php?id='.$row['Supplier Key'].'">'.$row['Supplier Code'].'</a>',
			'available'=>$row['Supplier Product Part In Use'],
			'available_state'=>$available_state,
			'status'=>$row['Supplier Product Status'],
			'formated_status'=>$formated_status,
			'from'=>sprintf('<span title="%s">%s</span>',  strftime("%a %e %b %Y %H:%M %Z", strtotime($row['Supplier Product Part Valid From'].' +0:00')),strftime("%d-%m-%Y", strtotime($row['Supplier Product Part Valid From'].' +0:00'))),
			'to'=>sprintf('<span title="%s">%s</span>',  strftime("%a %e %b %Y %H:%M %Z", strtotime($row['Supplier Product Part Valid To'].' +0:00')),strftime("%d-%m-%Y", strtotime($row['Supplier Product Part Valid To'].' +0:00'))),


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

?>
