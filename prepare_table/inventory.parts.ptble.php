<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 30 September 2015 20:13:47 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$where="where true  ";
$table="`Part Dimension` P";
$filter_msg='';
$sql_type='part';
$filter_msg='';
$wheref='';

if (isset($parameters['awhere']) and $parameters['awhere']) {

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
elseif ($parameters['parent']=='list') {

	$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$parameters['parent_key']);
	//print $sql;exit;
	$res=mysql_query($sql);
	if ($list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		if ($list_data['List Type']=='Static') {

			$table='`List Part Bridge` PB left join `Part Dimension` P  on (PB.`Part SKU`=P.`Part SKU`)';
			$where.=sprintf(' and `List Key`=%d ',$parameters['parent_key']);

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
}
elseif ($parameters['parent']=='category') {

	include_once 'class.Category.php';

	$category=new Category($parameters['parent_key']);

	if (!in_array($category->data['Category Warehouse Key'],$user->warehouses)) {
		return;
	}

	$where=sprintf(" where `Subject`='Part' and  `Category Key`=%d",$parameters['parent_key']);
	$table=' `Category Bridge` left join  `Part Dimension` P on (`Subject Key`=`Part SKU`) ';
	$where_type='';



}
elseif ($parameters['parent']=='warehouse') {
	$where=sprintf(" where  `Warehouse Key`=%d",$parameters['parent_key']);

	$table="`Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)";


}else {


}

/*


	switch ($elements_type) {
	case 'use':
		$_elements='';
		$elements_count=0;
		foreach ($elements['use'] as $_key=>$_value) {
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
			$where.=' and false' ;
		} elseif ($elements_count==1) {
			$where.=' and `Part Status` in ('.$_elements.')' ;
		}
		break;
	case 'state':
		$_elements='';
		$element_counter=0;
		foreach ($elements['state'] as $_key=>$_value) {
			if ($_value) {
				$_elements.=','.prepare_mysql($_key);
				$element_counter++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ( $element_counter<4) {

			$where.=' and `Part Main State` in ('.$_elements.')' ;
		}


		break;
	case 'stock_state':

		$_elements='';
		$elements_count=0;
		foreach ($elements['use'] as $_key=>$_value) {
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
			$where.=' and false' ;
		} elseif ($elements_count==1) {
			$where.=' and `Part Status` in ('.$_elements.')' ;
		}



		$_elements='';
		$element_counter=0;
		foreach ($elements['stock_state'] as $_key=>$_value) {
			if ($_value) {
				$_elements.=','.prepare_mysql($_key);
				$element_counter++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ( $element_counter<4) {

			$where.=' and `Part Stock State` in ('.$_elements.')' ;
		}
		break;



	case 'next_shipment':

		$_elements='';
		$elements_count=0;
		foreach ($elements['use'] as $_key=>$_value) {
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
			$where.=' and false' ;
		} elseif ($elements_count==1) {
			$where.=' and `Part Status` in ('.$_elements.')' ;
		}



		$_elements='';
		$element_counter=0;
		foreach ($elements['next_shipment'] as $_key=>$_value) {
			if ($_value) {
				$_elements.=','.prepare_mysql($_key);
				$element_counter++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ( $element_counter<3) {

			$where.=' and `Part Next Shipment State` in ('.$_elements.')' ;
		}
		break;

	default:
		$where.=' and false' ;

	}

*/


if ($parameters['f_field']=='used_in' and $f_value!='')
	$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='reference' and $f_value!='')
	$wheref.=" and  `Part Reference` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='supplied_by' and $f_value!='')
	$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='sku' and $f_value!='')
	$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";
elseif ($parameters['f_field']=='description' and $f_value!='')
	$wheref.=" and  `Part Unit Description` like '".addslashes($f_value)."%'";

	$_order=$order;
$_dir=$order_direction;

	if ($order=='stock')
		$order='`Part Current Stock`';
	elseif ($order=='sku')
		$order='`Part SKU`';
	elseif ($order=='id')
		$order='`Part SKU`';	
	elseif ($order=='formated_sku')
		$order='`Part SKU`';		
	elseif ($order=='reference')
		$order='`Part Reference`';
	elseif ($order=='description')
		$order='`Part Unit Description`';
	elseif ($order=='available_for')
		$order='`Part Available Days Forecast`';
	elseif ($order=='supplied_by')
		$order='`Part XHTML Currently Supplied By`';
	elseif ($order=='products')
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

	}elseif ($order=='package_type') {
		$order='`Part Package Type`';
	}elseif ($order=='package_weight') {
		$order='`Part Package Weight`';
	}elseif ($order=='Package') {
		$order='`Part Package Dimensions Volume`';
	}elseif ($order=='package_volume') {
		$order='`Part Package Dimensions Volume`';
	}elseif ($order=='unit_weight') {
		$order='`Part Unit Weight`';
	}elseif ($order=='unit_dimension') {
		$order='`Part Unit Dimensions Volume`';
	}elseif ($order=='from') {
		$order='`Part Valid From`';
	}elseif ($order=='to') {
		$order='`Part Valid To`';
	}elseif ($order=='last_update') {
		$order='`Part Last Updated`';
	}else {

		$order='`Part SKU`';
	}

	$order='P.'.$order;
	
	
	$sql_totals="select count(Distinct P.`Part SKU`) as num from $table  $where  ";

    $fields='P.`Part SKU`,`Part Reference`,`Part Unit Description`,`Part XHTML Currently Used In`'
?>
