<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2016 at 11:30:47 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



$part=$state['_object'];

$sales_max_sample_domain=1;

$number_records=0;
$sql=sprintf('select count(distinct `Date`) as num from `Inventory Spanshot Fact` where `Sold Amount`!=0 and `Part SKU`=%d ',
	$state['key']
);
if ($result=$db->query($sql)) {
	if ($row = $result->fetch()) {
		$number_records=$row['num'];
	}
}else {
	print_r($error_info=$db->errorInfo());
	exit;
}
//print $sql;

//print $number_records;

$sql=sprintf("select  sum(`Sold Amount`) as value from  `Inventory Spanshot Fact`  where `Part SKU`=%d   group by `Date`  order by sum(`Sold Amount`) desc limit %d ,1 ",
	$state['key'],
	$number_records/20
);

//print $sql;
if ($result=$db->query($sql)) {
	if ($row = $result->fetch()) {
		$sales_max_sample_domain=$row['value'];
	}
}else {
	print_r($error_info=$db->errorInfo());
	exit;
}
//print $sales_max_sample_domain;

$data=base64_encode(json_encode(array(
'valid_from'=>$part->get('Part Valid From'),
'valid_to'=>($part->get('Part Status')=='Not In Use'?$part->get('Part Valid To'):gmdate("Y-m-d H:i:s")  ) ,
'sales_max_sample_domain'=>$sales_max_sample_domain,
'parent'=>$state['object'],
'parent_key'=>$state['key']
)));




$smarty->assign('data',$data);
$html=$smarty->fetch('calendar.tpl');


?>
