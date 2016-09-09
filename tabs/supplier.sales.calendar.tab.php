<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 September 2016 at 13:25:55 GMT+8, Kuta , Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/



$supplier=$state['_object'];

$sales_max_sample_domain=1;

/*
if ($supplier->get('Product Max Day Sales')>0) {
	$top_range=$supplier->get('Product Avg with Sale Day Sales')+(3*$supplier->get('Product STD with Sale Day Sales'));
	if ($supplier->get('Product Max Day Sales')<$top_range) {
		$sales_max_sample_domain=$supplier->get('Product Max Day Sales');
	}else {
		$sales_max_sample_domain=$top_range;
	}

}
*/
$timeseries_key='';
$number_records=0;
$sql=sprintf('select `Timeseries Key`,`Timeseries Number Records` from `Timeseries Dimension` where `Timeseries Parent`="Supplier" and `Timeseries Parent Key`=%s and `Timeseries Frequency`="Daily" and  `Timeseries Type`="SupplierSales" ',
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
			'valid_from'=>$supplier->get('Supplier Valid From'),
			'valid_to'=>($supplier->get('Supplier Type')=='Archived'?$supplier->get('Supplier Valid To'):gmdate("Y-m-d H:i:s")  ) ,
			'sales_max_sample_domain'=>$sales_max_sample_domain,
			'parent'=>'supplier',
			'parent_key'=>$state['key']
		)));



$smarty->assign('data', $data);
$html=$smarty->fetch('calendar.tpl');


?>
