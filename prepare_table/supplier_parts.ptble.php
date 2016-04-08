<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 3 April 2016 at 18:28:53 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$where="where true  ";
$table="`Supplier Part Dimension` SP  left join `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`) left join `Supplier Dimension` S on (SP.`Supplier Part Supplier Key`=S.`Supplier Key`)  ";
$filter_msg='';
$sql_type='part';
$filter_msg='';
$wheref='';

$fields='';

if ($parameters['parent']=='supplier') {
	$where=sprintf(" where  `Supplier Part Supplier Key`=%d", $parameters['parent_key']);
  
}elseif ($parameters['parent']=='account') {




}elseif ($parameters['parent']=='part') {
	$where=sprintf(" where  SP.`Supplier Part Part SKU`=%d", $parameters['parent_key']);




}else{
exit("parent not found: ".$parameters['parent']);
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

if ($order=='part_description'){
	$order='`Part Reference`';
}elseif ($order=='reference'){
	$order='`Supplier Part Reference`';
}else {

	$order='`Supplier Part Part SKU`';
}



$sql_totals="select count(Distinct SP.`Supplier Part Key`) as num from $table  $where  ";
				
$fields.='`Supplier Part Key`,`Supplier Part Part SKU`,`Part Reference`,`Part Unit Description`,`Supplier Part Supplier Key`,`Supplier Part Reference`,`Supplier Part Status`,`Supplier Part From`,`Supplier Part To`,`Supplier Part Cost`,`Supplier Part Batch`';


?>
