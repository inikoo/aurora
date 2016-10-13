<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 October 2016 at 01:08:48 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$group_by='';

$table=sprintf(' `Part Dimension` P   left join `Supplier Part Dimension` SP on (SP.`Supplier Part Part SKU`=P.`Part SKU`)  left join `Part Data` PD on (PD.`Part SKU`=P.`Part SKU`)');



$where=sprintf("where  (`Part Current On Hand Stock`- `Part Current Stock In Process`- `Part Current Stock Ordered Paid` )<0 and (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>0 and `Supplier Part Supplier Key`=%d  ", $parameters['parent_key']);



$filter_msg='';
$sql_type='part';
$filter_msg='';
$wheref='';

$fields='';




if ($parameters['f_field']=='reference' and $f_value!='')
	$wheref.=" and  `Part Reference` like '".addslashes($f_value)."%'";

$_order=$order;
$_dir=$order_direction;

if ($order=='stock') {
	$order='`Part Current On Hand Stock`';
}elseif ($order=='reference') {
	$order='`Part Reference`';
}elseif ($order=='description') {
	$order='`Part Unit Description`';
}elseif ($order=='available_forecast') {
	$order='`Part Days Available Forecast`';
}elseif ($order=='dispatched_per_week') {
	$order='`Part 1 Quarter Acc Dispatched`';
}else {

	$order='P.`Part SKU`';
}



$sql_totals="select count(Distinct P.`Part SKU`) as num from $table  $where  ";


//print $sql_totals;

$fields.="
`Supplier Part Reference`,`Supplier Part Status`,`Part Stock Status`,`Part Units Per Package`,`Supplier Part Packages Per Carton`,`Supplier Part Unit Cost`,`Part Unit Description`,`Supplier Part Currency Code`,
`Supplier Part Minimum Carton Order`,`Supplier Part Key`,`Supplier Part Supplier Key`,`Supplier Part Part SKU`,`Part Current Stock`,`Part Current Stock In Process`+ `Part Current Stock Ordered Paid` as required


";
//min(`Order Date`) as date,sum((`Order Quantity`+`Order Bonus Quantity`)*`Product Part Ratio`) as required


?>
