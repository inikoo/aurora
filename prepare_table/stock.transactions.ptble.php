<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 18 April 2016 at 14:45:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$where="where true  ";
$table="`Inventory Transaction Fact` ITF left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`) left join `Delivery Note Dimension` DN on (DN.`Delivery Note Key`=ITF.`Delivery Note Key`)
 left join `Location Dimension` L on (ITF.`Location Key`=L.`Location Key`)  left join `User Dimension` U on (ITF.`User Key`=U.`User Key`)
 ";
$filter_msg='';
$sql_type='part';
$filter_msg='';
$wheref='';

$fields='';

if ($parameters['parent']=='part') {

	$where=sprintf(" where  `Inventory Transaction Record Type`='Movement' and ITF.`Part SKU`=%d", $parameters['parent_key']);



}elseif ($parameters['parent']=='account') {


	$where=sprintf(" where  `Inventory Transaction Record Type`='Movement' " );


}elseif ($parameters['parent']=='location') {

	$where=sprintf(" where  `Inventory Transaction Record Type`='Movement' and ITF.`Location Key`=%d", $parameters['parent_key']);



}else{
exit("parent not found ".$parameters['parent']);
}

if(isset($extra_where))
$where.=$extra_where;


if (isset($parameters['elements_type'])) {

	switch ($parameters['elements_type']) {
	case 'type':
		$_elements='';
		$count_elements=0;
		foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key=>$_value) {
			if ($_value['selected']) {
				$count_elements++;
				$_elements.=','.prepare_mysql($_key);

			}
		}




		$_elements=preg_replace('/^\,/', '', $_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} elseif ($count_elements<5) {
			$where.=' and `Inventory Transaction Section` in ('.$_elements.')' ;

		}
		break;
	
}
}



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

$order_direction='';

	$order='`Date` desc  ,`Inventory Transaction Key` desc ';




$sql_totals="select count(Distinct `Inventory Transaction Key`) as num from $table  $where  ";

$fields.='`Date`,`Inventory Transaction Section`,`Inventory Transaction Key`,`Inventory Transaction Quantity`,
`Part Reference`,ITF.`Part SKU`,`Delivery Note ID`,ITF.`Delivery Note Key`,ITF.`Location Key`,`Location Code`,`Required`,`Part Location Stock`,`Inventory Transaction Type`,`Metadata`,
`Note`,`User Alias`,ITF.`User Key`,`User Handle`';



?>
