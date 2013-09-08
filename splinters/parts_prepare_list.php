<?php

$where="where true  ";
$table="`Part Dimension` P";
$filter_msg='';
$sql_type='part';
$filter_msg='';
$wheref='';

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
	if ($list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		if ($list_data['List Type']=='Static') {

			$table='`List Part Bridge` PB left join `Part Dimension` P  on (PB.`Part SKU`=P.`Part SKU`)';
			$where.=sprintf(' and `List Key`=%d ',$parent_key);

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
elseif ($parent=='warehouse') {
	$where=sprintf(" where  `Warehouse Key`=%d",$parent_key);

	$table="`Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)";


}else {


}


if (!$awhere ) {

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
	default:
		$where.=' and false' ;

	}


}





if ($f_field=='used_in' and $f_value!='')
	$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
elseif ($f_field=='reference' and $f_value!='')
	$wheref.=" and  `Part Reference` like '".addslashes($f_value)."%'";
elseif ($f_field=='supplied_by' and $f_value!='')
	$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
elseif ($f_field=='sku' and $f_value!='')
	$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";
elseif ($f_field=='description' and $f_value!='')
	$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";



?>
