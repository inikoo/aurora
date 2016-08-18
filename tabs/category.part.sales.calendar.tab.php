<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2016 at 16:30:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



$category=$state['_object'];

$sales_max_sample_domain=1;

/*
if ($category->get('Product Max Day Sales')>0) {
	$top_range=$category->get('Product Avg with Sale Day Sales')+(3*$category->get('Product STD with Sale Day Sales'));
	if ($category->get('Product Max Day Sales')<$top_range) {
		$sales_max_sample_domain=$category->get('Product Max Day Sales');
	}else {
		$sales_max_sample_domain=$top_range;
	}

}
*/
$timeseries_key='';
$number_records=0;
$sql=sprintf('select `Timeseries Key`,`Timeseries Number Records` from `Timeseries Dimension` where `Timeseries Parent`="Category" and `Timeseries Parent Key`=%s and `Timeseries Frequency`="Daily" and  `Timeseries Type`="PartCategorySales" ',
	$state['key']
);
if ($result=$db->query($sql)) {
	if ($row = $result->fetch()) {
		$timeseries_key=$row['Timeseries Key'];
		$number_records=$row['Timeseries Number Records'];
	}
}else {
	print_r($error_info=$db->errorInfo());
	exit;
}



$sql=sprintf("select  `Timeseries Record Float A` as value from  `Timeseries Record Dimension`  where `Timeseries Record Timeseries Key`=%d   order by `Timeseries Record Float A` desc limit %d ,1",
	$timeseries_key,
	$number_records/20
);

if ($result=$db->query($sql)) {
	if ($row = $result->fetch()) {
		$sales_max_sample_domain=$row['value'];
	}
}else {
	print_r($error_info=$db->errorInfo());
	exit;
}






$data=base64_encode(json_encode(array(
			'valid_from'=>$category->get('Part Category Valid From'),
			'valid_to'=>($category->get('Category Status')=='NotInUse'?$category->get('Part Category Valid To'):gmdate("Y-m-d H:i:s")  ) ,
			'sales_max_sample_domain'=>$sales_max_sample_domain,
			'parent'=>'part_category',
			'parent_key'=>$state['key']
		)));



$smarty->assign('data', $data);
$html=$smarty->fetch('calendar.tpl');


?>
