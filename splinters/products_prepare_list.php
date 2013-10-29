<?php



$table="`Product Dimension` P left join `Store Dimension` S on (`Product Store Key`=`Store Key`)";
$where_type='';
$where_interval='';
$where='where true';
$wheref='';

if ($awhere) {

	$tmp=preg_replace('/\\\"/','"',$awhere);
	$tmp=preg_replace('/\\\\\"/','"',$tmp);
	$tmp=preg_replace('/\'/',"\'",$tmp);

	$raw_data=json_decode($tmp, true);
	$raw_data['store_key']=$store;
	list($where,$table)=product_awhere($raw_data);

	$where_type='';
	$where_interval='';
}






switch ($parent) {
case('list'):

	$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$_REQUEST['parent_key']);

	$res=mysql_query($sql);
	if ($customer_list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		if ($customer_list_data['List Type']=='Static') {

			$table='`List Product Bridge` PB left join `Product Dimension` P  on (PB.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)';
			$where_type=sprintf(' and `List Key`=%d ',$_REQUEST['parent_key']);

		} else {// Dynamic by DEFAULT



			$tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/','"',$tmp);
			$tmp=preg_replace('/\'/',"\'",$tmp);

			$raw_data=json_decode($tmp, true);

			$raw_data['store_key']=$store;
			list($where,$table)=product_awhere($raw_data);
		}

	} else {
		exit("error");
	}

	break;
case('stores'):
	$where.=sprintf(" and `Product Store Key` in (%s) ",join(',',$user->stores));
	break;
case('store'):
	$where.=sprintf(' and `Product Store Key`=%d',$parent_key);
	break;
case('department'):
	$where.=sprintf('  and `Product Main Department Key`=%d',$parent_key);
	break;
case('family'):
	$where.=sprintf(' and `Product Family Key`=%d',$parent_key);
	break;
case('category'):
	include_once('class.Category.php');
		$category=new Category($parent_key);

		if (!in_array($category->data['Category Store Key'],$user->stores)) {
			return;
		}
		$where_type='';
	
		$where=sprintf(" where `Subject`='Product' and  `Category Key`=%d",$parent_key);
		$table=' `Category Bridge` left join  `Product Dimension` C on (`Subject Key`=`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)';
	break;		
default:


}


$group='';
$where.=$where_type;


$elements_counter=0;



switch ($elements_type) {
case 'type':
	$_elements='';
	foreach ($elements['type'] as $_key=>$_value) {
		if ($_value) {
			$_elements.=','.prepare_mysql($_key);
			$elements_counter++;
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($elements_counter<5) {
		$where.=' and `Product Main Type` in ('.$_elements.')' ;
	}
	break;
case 'web':
	$_elements='';
	foreach ($elements['web'] as $_key=>$_value) {
		if ($_value) {
			if ($_key=='OutofStock')
				$_key='Out of Stock';
			elseif ($_key=='ForSale')
				$_key='For Sale';
			$_elements.=','.prepare_mysql($_key);
			$elements_counter++;
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($elements_counter<4) {
		$where.=' and `Product Web State` in ('.$_elements.')' ;
	}
	break;

case 'stock':


	switch ($elements_stock_aux) {
	case 'InWeb':
		$where.=' and `Product Web State`!="Offline" ' ;
		break;
	case 'ForSale':
		$where.=' and `Product Main Type`="Sale" ' ;
		break;
	}


	$_elements='';
	foreach ($elements['stock'] as $_key=>$_value) {
		if ($_value) {
			$_elements.=','.prepare_mysql($_key);
			$elements_counter++;
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($elements_counter<6) {
		$where.=' and `Product Availability State` in ('.$_elements.')' ;
	}
	break;


}



if ($f_field=='code' and $f_value!='')
	$wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
elseif ($f_field=='description' and $f_value!='')
	$wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";



?>
