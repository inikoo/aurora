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
	$response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('parts_at_date'):
	list_parts_at_date();
	break;
case('number_warehouse_element_transactions_in_interval'):
	$data=prepare_values($_REQUEST,array(
			'warehouse_key'=>array('type'=>'key'),
			'element'=>array('type'=>'string'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	number_warehouse_element_transactions_in_interval($data);
	break;

case('number_warehouse_transactions_in_interval'):
	$data=prepare_values($_REQUEST,array(
			'warehouse_key'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	number_warehouse_transactions_in_interval($data);
	break;
case('number_part_transactions_in_interval'):
	$data=prepare_values($_REQUEST,array(
			'part_sku'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	number_part_transactions_in_interval($data);
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
	
	
	if($parent=='category'){
		$conf_node='part_categories';
	}else{
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




	$elements=$conf['elements'];
	if (isset( $_REQUEST['elements_Keeping'])) {
		$elements['Keeping']=$_REQUEST['elements_Keeping'];
	}
	if (isset( $_REQUEST['elements_NotKeeping'])) {
		$elements['NotKeeping']=$_REQUEST['elements_NotKeeping'];
	}

	if (isset( $_REQUEST['elements_Discontinued'])) {
		$elements['Discontinued']=$_REQUEST['elements_Discontinued'];
	}
	if (isset( $_REQUEST['elements_LastStock'])) {
		$elements['LastStock']=$_REQUEST['elements_LastStock'];
	}

	$_SESSION['state'][$conf_node]['parts']['order']=$order;
	$_SESSION['state'][$conf_node]['parts']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_node]['parts']['nr']=$number_results;
	$_SESSION['state'][$conf_node]['parts']['sf']=$start_from;
	$_SESSION['state'][$conf_node]['parts']['where']=$awhere;
	$_SESSION['state'][$conf_node]['parts']['f_field']=$f_field;
	$_SESSION['state'][$conf_node]['parts']['f_value']=$f_value;
	$_SESSION['state'][$conf_node]['parts']['elements']=$elements;
	$_SESSION['state'][$conf_node]['parts']['view']=$view;
	$_SESSION['state'][$conf_node]['parts']['percentage']=$percentage;
	$_SESSION['state'][$conf_node]['parts']['period']=$period;
	$_SESSION['state'][$conf_node]['parts']['avg']=$avg;
	
	


	$filter_msg='';
	$sql_type='part';
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (!is_numeric($start_from))
		$start_from=0;
	if (!is_numeric($number_results))
		$number_results=25;

	$where="where true  ";
	$table="`Part Dimension` P";

	if ($awhere) {

		$tmp=preg_replace('/\\\"/','"',$awhere);
		$tmp=preg_replace('/\\\\\"/','"',$tmp);
		$tmp=preg_replace('/\'/',"\'",$tmp);

		$raw_data=json_decode($tmp, true);
		//$raw_data['store_key']=$store;
		//print_r($raw_data);exit;
		list($where,$table,$sql_type)=parts_awhere($raw_data);

		$where_type='';
		$where_interval='';
	}
	elseif ($parent=='list') {

		$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$parent_key);
		//print $sql;exit;
		$res=mysql_query($sql);
		if ($customer_list_data=mysql_fetch_assoc($res)) {
			$awhere=false;
			if ($customer_list_data['List Type']=='Static') {

				$table='`List Part Bridge` PB left join `Part Dimension` P  on (PB.`Part SKU`=P.`Part SKU`)';
				$where.=sprintf(' and `List Key`=%d ',$parent_key);

			} else {// Dynamic by DEFAULT



				$tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
				$tmp=preg_replace('/\\\\\"/','"',$tmp);
				$tmp=preg_replace('/\'/',"\'",$tmp);

				$raw_data=json_decode($tmp, true);

				//$raw_data['store_key']=$store;
				list($where,$table,$sql_type)=parts_awhere($raw_data);
			}

		} else {
			exit("error");
		}
	}
	elseif ($parent=='category') {

		include_once 'class.Category.php';

		$category=new Category($parent_key);

		if (!in_array($category->data['Category Warehouse Key'],$user->warehouses)) {
			return;
		}

		$where=sprintf(" where `Subject`='Part' and  `Category Key`=%d",$parent_key);
		$table=' `Category Bridge` left join  `Part Dimension` P on (`Subject Key`=`Part SKU`) ';
		$where_type='';



	}
	else {


	}






	if (!$awhere  and $parent!='list') {
		$_elements='';
		foreach ($elements as $_key=>$_value) {
			if ($_value)
				$_elements.=','.prepare_mysql($_key);
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} else {
			$where.=' and `Part Main State` in ('.$_elements.')' ;
		}
	}







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
		$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";

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

	$period_tag=get_interval_db_name($period);



	$_order=$order;
	$_order_dir=$order_dir;

	if ($order=='stock')
		$order='`Part Current Stock`';
	elseif ($order=='sku')
		$order='`Part SKU`';
	elseif ($order=='description')
		$order='`Part Unit Description`';
	elseif ($order=='available_for')
		$order='`Part Available Days Forecast`';
	elseif ($order=='supplied_by')
		$order='`Part XHTML Currently Supplied By`';
	elseif ($order=='used_in')
		$order='`Part XHTML Currently Used In`';

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

	}else {

		$order='`Part SKU`';
	}

	$order='P.'.$order;

	$group='';

	if ($sql_type=='part')
		$sql="select *,IFNULL((select GROUP_CONCAT(L.`Location Key`,':',L.`Location Code`,':',`Can Pick`,':',`Quantity On Hand` SEPARATOR ',') from `Part Location Dimension` PLD  left join `Location Dimension` L on (L.`Location Key`=PLD.`Location Key`) where PLD.`Part SKU`=P.`Part SKU`),'') as location_data from $table  $where $wheref    order by $order $order_direction limit $start_from,$number_results    ";
	else
		$sql="select *,IFNULL((select GROUP_CONCAT(L.`Location Key`,':',L.`Location Code`,':',`Can Pick`,':',`Quantity On Hand` SEPARATOR ',') from `Part Location Dimension` PLD  left join `Location Dimension` L on (L.`Location Key`=PLD.`Location Key`) where PLD.`Part SKU`=P.`Part SKU`),'') as location_data from $table  $where $wheref  group by ITF.`Part SKU`  order by $order $order_direction limit $start_from,$number_results    ";



	$adata=array();
	$result=mysql_query($sql);

	//print $sql;
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


		if ($sql_type=='part') {
			$locations='<table border=0 style="width:150px">';
			$locations_data=preg_split('/,/',$data['location_data']);
			//print_r($locations_data);
			foreach ($locations_data as $raw_location_data) {
				if ($raw_location_data!='') {
					//print_r($raw_location_data);
					$locations.='<tr style="border:none">';
					$locations_data=preg_split('/\:/',$raw_location_data);

					$locations.='<td style="0border:1px solid red;"><a href="locations.php?id='.$locations_data[0].'">'.$locations_data[1].'</a></td><td style="text-align:right">'.number($locations_data[3]).'</td>';
					$locations.='</tr>';
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




		$adata[]=array(
			'locations'=>$locations,
			'sku'=>sprintf('<a href="part.php?sku=%d">%06d</a>',$data['Part SKU'],$data['Part SKU']),
			'description'=>$data['Part Unit Description'],
			'description_small'=>$data['Part Unit Description'].'<br/>'.$data['Part XHTML Currently Used In'],

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
			'gmroi'=>$gmroi
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
	else {

		$order='`Part SKU`';
	}



	$group='';


	$sql="select ISF.`Part SKU`,count(DISTINCT `Location Key`) as locations,`Part Unit Description`,`Part XHTML Currently Used In`,sum(`Quantity On Hand`) as stock,sum(`Quantity Open`) as stock_open,sum(`Value At Cost`) as value_at_cost,sum(`Value At Day Cost`) as value_at_end_day,sum(`Value Commercial`) as commercial_value from `Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)  $where $wheref group by ISF.`Part SKU`   order by $order $order_direction limit $start_from,$number_results  ";

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
			'commercial_value'=>money($data['commercial_value'],$corporate_currency)

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

function number_part_transactions_in_interval($data) {
	$part_sku=$data['part_sku'];

	$from=$data['from'];
	$to=$data['to'];

	if (!$to and !$from) {
		$part=new Part($part_sku);
		$transactions=array(
			'all_transactions'=>number($part->data['Part Transactions']),
			'in_transactions'=>number($part->data['Part Transactions In']),
			'out_transactions'=>number($part->data['Part Transactions Out']),
			'audit_transactions'=>number($part->data['Part Transactions Audit']),
			'oip_transactions'=>number($part->data['Part Transactions OIP']),
			'move_transactions'=>number($part->data['Part Transactions Move']),
		);

	}
	else {
		$transactions=array(
			'all_transactions'=>0,
			'in_transactions'=>0,
			'out_transactions'=>0,
			'audit_transactions'=>0,
			'oip_transactions'=>0,
			'move_transactions'=>0
		);

		$where_interval=prepare_mysql_dates($from,$to,'`Date`','dates_only.startend');
		$where_interval=$where_interval['mysql'];
		$sql=sprintf("select sum(if(`Inventory Transaction Type` not in ('Move In','Move Out','Associate','Disassociate'),1,0))  as all_transactions , sum(if(`Inventory Transaction Type`='Not Found' or `Inventory Transaction Type`='No Dispatched' or `Inventory Transaction Type`='Audit',1,0)) as audit_transactions,sum(if(`Inventory Transaction Type`='Move',1,0)) as move_transactions,sum(if(`Inventory Transaction Type`='Sale' or `Inventory Transaction Type`='Other Out' or `Inventory Transaction Type`='Broken' or `Inventory Transaction Type`='Lost',1,0)) as out_transactions, sum(if(`Inventory Transaction Type`='Order In Process',1,0)) as oip_transactions, sum(if(`Inventory Transaction Type`='In',1,0)) as in_transactions from `Inventory Transaction Fact` where `Part SKU`=%d %s",
			$part_sku,
			$where_interval
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$transactions=array(
				'all_transactions'=>number($row['all_transactions']),
				'in_transactions'=>number($row['in_transactions']),
				'out_transactions'=>number($row['out_transactions']),
				'audit_transactions'=>number($row['audit_transactions']),
				'oip_transactions'=>number($row['oip_transactions']),
				'move_transactions'=>number($row['move_transactions'])
			);
		}
	}
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


	$rtext=$total_records." ".ngettext('List','Lists',$total_records);
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


	$sql="select  CLD.`List key`,CLD.`List Name`,CLD.`List Parent Key`,CLD.`List Creation Date`,CLD.`List Type` from `List Dimension` CLD $where  order by $order $order_direction limit $start_from,$number_results";

	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





		$cusomer_list_name=" <a href='parts_list.php?id=".$data['List key']."'>".$data['List Name'].'</a>';
		switch ($data['List Type']) {
		case 'Static':
			$product_list_type=_('Static');
			break;
		default:
			$product_list_type=_('Dynamic');
			break;

		}

		$adata[]=array(


			'product_list_type'=>$product_list_type,
			'name'=>$cusomer_list_name,
			'key'=>$data['List key'],
			'creation_date'=>strftime("%a %e %b %y %H:%M", strtotime($data['List Creation Date']." +00:00")),
			'add_to_email_campaign_action'=>'<span class="state_details" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add List').'</span>',
			'delete'=>'<img src="art/icons/cross.png"/>'


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
	$conf=$_SESSION['state']['part']['stock_history'];

	if (isset( $_REQUEST['parent_key']))
		$part_sku=$_REQUEST['parent_key'];
	else {
		exit();
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


	if (isset( $_REQUEST['type']))
		$type=$_REQUEST['type'];
	else
		$type=$conf['type'];





	$_SESSION['state']['part']['stock_history']['order']=$order;
	$_SESSION['state']['part']['stock_history']['type']=$type;
	$_SESSION['state']['part']['stock_history']['order_dir']=$order_direction;
	$_SESSION['state']['part']['stock_history']['nr']=$number_results;
	$_SESSION['state']['part']['stock_history']['sf']=$start_from;
	$_SESSION['state']['part']['stock_history']['f_field']=$f_field;
	$_SESSION['state']['part']['stock_history']['f_value']=$f_value;

	$_SESSION['state']['part']['stock_history']['elements']=$elements;

	$_SESSION['state']['part']['stock_history']['f_show']=$_SESSION['state']['part']['stock_history']['f_show'];




	$date_interval=prepare_mysql_dates($from,$to,'`Date`','only_dates');


	if ($date_interval['error']) {

		$date_interval=prepare_mysql_dates($_SESSION['state']['part']['stock_history']['from'],$_SESSION['state']['part']['stock_history']['to']);
	} else {

		$_SESSION['state']['part']['stock_history']['from']=$date_interval['from'];
		$_SESSION['state']['part']['stock_history']['to']=$date_interval['to'];


	}

	//print_r($_SESSION['state']['part']['stock_history']);
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';

	$wheref='';




	switch ($type) {
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




	$where=sprintf(" where `Part SKU`=%d %s",$part_sku,$date_interval['mysql']);


	$sql="select count(*) as total from `Inventory Spanshot Fact`     $where $wheref $group";
	//print $sql;
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




	switch ($type) {
	case 'month':
		$rtext=$total_records.' '.ngettext('month','months',$total);
		break;
	case 'day':
		$rtext=$total_records.' '.ngettext('day','days',$total);
		break;
	default:
		$rtext=$total_records.' '.ngettext('week','weeks',$total);
		break;
	}



	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';



	if ($total_records==0) {
		$rtext=_('No stock history');
		$rtext_rpp='';
	}


	$order='`Date`';

	$sql=sprintf("select  GROUP_CONCAT(distinct '<a href=\"location.php?id=',ISF.`Location Key`,'\">',`Location Code`,'<a/>') as locations,`Date`, ( select  sum(`Quantity On Hand`) from `Inventory Spanshot Fact` OISF where `Part SKU`=%d and OISF.`Date`=ISF.`Date`  )as `Quantity On Hand`, ( select  sum(`Value Commercial`) from `Inventory Spanshot Fact` OISF where `Part SKU`=%d and OISF.`Date`=ISF.`Date`  )as `Value Commercial`, ( select  sum(`Value At Day Cost`) from `Inventory Spanshot Fact` OISF where `Part SKU`=%d and OISF.`Date`=ISF.`Date`  )as `Value At Day Cost`, ( select  sum(`Value At Cost`) from `Inventory Spanshot Fact` OISF where `Part SKU`=%d and OISF.`Date`=ISF.`Date`  )as `Value At Cost`,sum(`Sold Amount`) as `Sold Amount`,sum(`Storing Cost`) as `Storing Cost`,sum(`Quantity Sold`) as `Quantity Sold`,sum(`Quantity In`) as `Quantity In`,sum(`Quantity Lost`) as `Quantity Lost`  from `Inventory Spanshot Fact` ISF left join `Location Dimension` L on (ISF.`Location key`=L.`Location key`)  $where $wheref   %s order by $order $order_direction  limit $start_from,$number_results "
		,$part_sku
		,$part_sku
		,$part_sku
		,$part_sku
		,$group
	);

	//print $sql;

	$result=mysql_query($sql);
	$adata=array();
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



		switch ($type) {
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


	if (isset( $_REQUEST['type']))
		$type=$_REQUEST['type'];
	else
		$type=$conf['type'];





	$_SESSION['state']['warehouse']['stock_history']['order']=$order;
	$_SESSION['state']['warehouse']['stock_history']['type']=$type;
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

	switch ($type) {
	case 'month':
		$where.=' and LAST_DAY(`Date`)=`Date`   ';
		break;
	case 'day':
		$where.='  ';
		break;
	default:
		$where.=' and DAYOFWEEK(`Date`)=1   ';
		break;
	}



	$sql=sprintf("select count(*) as total from `Inventory Warehouse Spanshot Fact` $where" );
	//print $sql;
	$total=0;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Inventory Warehouse Spanshot Fact` $where  $wheref ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}





	switch ($type) {
	case 'month':
		$rtext=$total_records.' '.ngettext('month','months',$total);
		break;
	case 'day':
		$rtext=$total_records.' '.ngettext('day','days',$total);
		break;
	default:
		$rtext=$total_records.' '.ngettext('week','weeks',$total);
		break;
	}



	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';



	if ($total_records==0) {
		$rtext=_('No stock history');
		$rtext_rpp='';
	}


	$order='`Date`';

	$sql=sprintf("select * from `Inventory Warehouse Spanshot Fact`   $where $wheref    order by $order $order_direction  limit $start_from,$number_results "



	);

	//print $sql;

	$result=mysql_query($sql);
	$adata=array();
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



		switch ($type) {
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

	}elseif ($parent=='warehouse') {
		$conf=$_SESSION['state']['warehouse']['transactions'];
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

	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$conf['view'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$date_interval=prepare_mysql_dates($from,$to,'`Date`','only_dates');
	if ($parent=='part') {

		if ($date_interval['error']) {
			$date_interval=prepare_mysql_dates($_SESSION['state']['part']['transactions']['from'],$_SESSION['state']['part']['transactions']['to']);
		} else {

			$_SESSION['state']['part']['transactions']['from']=$date_interval['from'];
			$_SESSION['state']['part']['transactions']['to']=$date_interval['to'];
		}



	}elseif ($parent=='warehouse') {



		if ($date_interval['error']) {
			$date_interval=prepare_mysql_dates($_SESSION['state']['warehouse']['transactions']['from'],$_SESSION['state']['warehouse']['transactions']['to']);
		} else {

			$_SESSION['state']['warehouse']['transactions']['from']=$date_interval['from'];
			$_SESSION['state']['warehouse']['transactions']['to']=$date_interval['to'];
		}


	}



	if ($parent=='part') {
		$_SESSION['state']['part']['transactions']=
			array(
			'view'=>$view,
			'order'=>$order,
			'order_dir'=>$order_direction,
			'nr'=>$number_results,
			'sf'=>$start_from,

			'f_field'=>$f_field,
			'f_value'=>$f_value,
			'from'=>$from,
			'to'=>$to,
			'elements'=>$elements,
			'f_show'=>$_SESSION['state']['part']['transactions']['f_show']
		);
	}elseif ($parent=='warehouse') {
		$_SESSION['state']['warehouse']['transactions']=
			array(
			'view'=>$view,
			'order'=>$order,
			'order_dir'=>$order_direction,
			'nr'=>$number_results,
			'sf'=>$start_from,

			'f_field'=>$f_field,
			'f_value'=>$f_value,
			'from'=>$from,
			'to'=>$to,
			'elements'=>$elements,
			'f_show'=>$_SESSION['state']['warehouse']['transactions']['f_show']
		);
	}

	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';







	$wheref='';

	if ($f_field=='note' and $f_value!='') {
		// $wheref.=" and  `Note` like '%".addslashes($f_value)."%'  or  `Note` REGEXP '[[:<:]]".$f_value."'  ";
		$wheref.=" and  `Note` like '".addslashes($f_value)."%'  ";

	}

	if ($parent=='part') {
		$where=sprintf(" where `Part SKU`=%d %s",$parent_key,$date_interval['mysql']);
	}elseif ($parent=='warehouse') {
		$where=sprintf(" where `Warehouse Key`=%d %s",$parent_key,$date_interval['mysql']);
	}





	switch ($view) {
	case 'oip_transactions':
		$where.=" and `Inventory Transaction Type`='Order In Process' ";
		break;
	case('in_transactions'):
		$where.=" and `Inventory Transaction Type` in ('In') ";
		break;
	case('move_transactions'):
		$where.=" and `Inventory Transaction Type` in ('Move') ";
		break;
	case('out_transactions'):
		$where.=" and `Inventory Transaction Type` in ('Sale','Broken','Lost','Other Out') ";
		break;
	case('audit_transactions'):
		$where.="and `Inventory Transaction Type` in ('Not Found','No Dispatched','Audit','Adjust') ";
		break;
	default:
		$where.="and `Inventory Transaction Type` not in ('Move In','Move Out','Associate','Disassociate') ";
		break;
		break;
	}



	$sql="select count(*) as total from `Inventory Transaction Fact`     $where $wheref";
	//print $sql;exit;

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Inventory Transaction Fact`   $where ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}







	$rtext=number($total_records)." ".ngettext('stock operation','stock operations',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';



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

	if ($parent=='part') {
		$sql="select `Inventory Transaction Stock`,`User Alias`, ITF.`User Key`,`Required`,`Picked`,`Packed`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Date`,ITF.`Location Key`,`Location Code` ,ITF.`Inventory Transaction Key` from `Inventory Transaction Fact` ITF left join `Location Dimension` L on (ITF.`Location key`=L.`Location key`) left join `User Dimension` U on (ITF.`User Key`=U.`User Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ";
	}
	elseif ($parent=='warehouse') {
		$sql="select  `Inventory Transaction Stock`,`User Alias`,ITF.`User Key`,`Required`,`Picked`,`Packed`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Date`,ITF.`Location Key`,`Location Code` ,ITF.`Inventory Transaction Key` from `Inventory Transaction Fact` ITF left join `Location Dimension` L on (ITF.`Location key`=L.`Location key`) left join `User Dimension` U on (ITF.`User Key`=U.`User Key`)   $where $wheref limit $start_from,$number_results ";
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
			$qty.='('.(-1*$data['Required']).')';
			break;

		case 'Move':
			$transaction_type=_('Move');
			$qty='&harr;';
			break;
		case 'Audit':
			$transaction_type=_('Audit');
			$qty='&#3663; <b>'.$data['Inventory Transaction Stock'].'</b>';
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
			'date'=>strftime("%c", strtotime($data['Date'])),
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



	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];
		$_SESSION['state']['part_categories']['avg']=$avg;
	} else
		$avg=$_SESSION['state']['part_categories']['avg'];

	if (isset( $_REQUEST['stores_mode'])) {
		$stores_mode=$_REQUEST['stores_mode'];
		$_SESSION['state']['part_categories']['stores_mode']=$stores_mode;
	} else
		$stores_mode=$_SESSION['state']['part_categories']['stores_mode'];



	$_SESSION['state']['part_categories']['subcategories']['order']=$order;
	$_SESSION['state']['part_categories']['subcategories']['order_dir']=$order_direction;
	$_SESSION['state']['part_categories']['subcategories']['nr']=$number_results;
	$_SESSION['state']['part_categories']['subcategories']['sf']=$start_from;
	$_SESSION['state']['part_categories']['subcategories']['f_field']=$f_field;
	$_SESSION['state']['part_categories']['subcategories']['f_value']=$f_value;





	$where=sprintf("where `Category Subject`='Part' and  `Category Parent Key`=%d",$parent_key);
	//  $where=sprintf("where `Category Subject`='Product'  ");

	//  if ($stores_mode=='grouped')
	//     $group=' group by S.`Category Key`';
	// else
	$group='';

	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";




	$sql="select count(*) as total   from `Category Dimension`   $where $wheref";

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
		$sql="select count(*) as total  from `Category Dimension`  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=$total_records." ".ngettext('category','categories',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

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


		$adata[]=array(
			'id'=>$row['Category Key'],
			'code'=>$code,
			'label'=>$label,
			'subjects'=>number($row['Category Number Subjects']),
			'sold'=>number($sold,0),
			'sales'=>money($amount,$corporate_currency),
			'delta_sales'=>delta($row['Part Category '.$period_tag.' Acc Sold Amount'],$row['Part Category '.$period_tag.' Acc 1YB Sold Amount'])



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





function number_warehouse_transactions_in_interval($data) {
	$warehouse_key=$data['warehouse_key'];

	$from=$data['from'];
	$to=$data['to'];


	$transactions=array(
		'all_transactions'=>0,
		'in_transactions'=>0,
		'out_transactions'=>0,
		'audit_transactions'=>0,
		'oip_transactions'=>0,
		'move_transactions'=>0
	);

	$where_interval=prepare_mysql_dates($from,$to,'`Date`','dates_only.startend');
	$where_interval=$where_interval['mysql'];


	$sql=sprintf("select sum(if(`Inventory Transaction Type` not in ('Move In','Move Out','Associate','Disassociate'),1,0)) as all_transactions , sum(if(`Inventory Transaction Type`='Not Found' or `Inventory Transaction Type`='No Dispatched' or `Inventory Transaction Type`='Audit',1,0)) as audit_transactions,sum(if(`Inventory Transaction Type`='Move',1,0)) as move_transactions,sum(if(`Inventory Transaction Type`='Sale' or `Inventory Transaction Type`='Other Out' or `Inventory Transaction Type`='Broken' or `Inventory Transaction Type`='Lost',1,0)) as out_transactions, sum(if(`Inventory Transaction Type`='Order In Process',1,0)) as oip_transactions, sum(if(`Inventory Transaction Type`='In',1,0)) as in_transactions from `Inventory Transaction Fact` where `Warehouse Key`=%d %s",
		$warehouse_key,
		$where_interval
	);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {

		$transactions=array(
			'all_transactions'=>number($row['all_transactions']),
			'in_transactions'=>number($row['in_transactions']),
			'out_transactions'=>number($row['out_transactions']),
			'audit_transactions'=>number($row['audit_transactions']),
			'oip_transactions'=>number($row['oip_transactions']),
			'move_transactions'=>number($row['move_transactions'])
		);
	}

	$response= array('state'=>200,'transactions'=>$transactions);
	echo json_encode($response);
}


function number_warehouse_element_transactions_in_interval($data) {
	$warehouse_key=$data['warehouse_key'];

	$from=$data['from'];
	$to=$data['to'];
	$number_of_transactions=0;


	$where_interval=prepare_mysql_dates($from,$to,'`Date`','dates_only.startend');
	$where_interval=$where_interval['mysql'];

	switch ($data['element']) {

	case 'all':
		$sql=sprintf("select count(*)  as number_of_transactions from `Inventory Transaction Fact` where `Inventory Transaction Type` not in ('Move In','Move Out','Associate','Disassociate') and  `Warehouse Key`=%d %s",
			$warehouse_key,
			$where_interval
		);
		break;
	case 'out':
		$sql=sprintf("select count(*)  as number_of_transactions from `Inventory Transaction Fact` where `Inventory Transaction Type`  in ('Sale','Broken','Lost','Other Out') and  `Warehouse Key`=%d %s",
			$warehouse_key,
			$where_interval
		);
		break;
	case 'move':
		$sql=sprintf("select count(*)  as number_of_transactions from `Inventory Transaction Fact` where `Inventory Transaction Type`='Move' and  `Warehouse Key`=%d %s",
			$warehouse_key,
			$where_interval
		);
		break;
	case 'in':
		$sql=sprintf("select count(*)  as number_of_transactions from `Inventory Transaction Fact` where `Inventory Transaction Type`='In' and  `Warehouse Key`=%d %s",
			$warehouse_key,
			$where_interval
		);
		break;
	case 'oip':
		$sql=sprintf("select count(*)  as number_of_transactions from `Inventory Transaction Fact` where `Inventory Transaction Type`='Order In Process' and  `Warehouse Key`=%d %s",
			$warehouse_key,
			$where_interval
		);
		break;
	case 'audit':
		$sql=sprintf("select count(*)  as number_of_transactions from `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Not Found','No Dispatched','Audit')  and  `Warehouse Key`=%d %s",
			$warehouse_key,
			$where_interval
		);
		break;

	default:
		$response= array('state'=>400,'msg'=>'unknown element');
		echo json_encode($response);
		exit;
		break;
	}

	//print $sql;

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {


		$number_of_transactions=number($row['number_of_transactions']);


	}

	$response= array('state'=>200,'element'=>$data['element'],'number'=>$number_of_transactions);
	echo json_encode($response);
}
