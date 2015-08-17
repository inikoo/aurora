<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2015 17:17:36 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


switch ($parent) {
case('store'):

	$where=sprintf(' where `Product Family Store Key`=%d',$parent_key);
	$table='`Product Family Dimension` F left join `Product Family Data Dimension` FD on (FD.`Product Family Key`=F.`Product Family Key`)';
	break;
case('department'):

	$where=sprintf(' where `Product Family Main Department Key`=%d',$parent_key);
	$table='`Product Family Dimension` F left join `Product Family Data Dimension` FD on (FD.`Product Family Key`=F.`Product Family Key`)';

	break;
case('category'):

	$where=sprintf(' where `Category Key`=%d',$parent_key);
	$table='`Product Family Dimension` F left join `Product Family Data Dimension` FD on (FD.`Product Family Key`=F.`Product Family Key`) left join `Category Bridge` on (`Subject`="Family" and `Subject Key`=`Product Family Key`)';

	break;

default:
	if (count($user->stores)==0)
		$where="where false";
	else {

		$where=sprintf("where `Product Family Store Key` in (%s) ",join(',',$user->stores));
	}
	$table='`Product Family Dimension` F left join `Product Family Data Dimension` FD on (FD.`Product Family Key`=F.`Product Family Key`)';


}


$_elements='';
$number_elements=0;
foreach ($elements as $_key=>$_value) {
	if ($_value){
		$_elements.=','.prepare_mysql($_key);
		$number_elements++;
		}
}
$_elements=preg_replace('/^\,/','',$_elements);
if ($_elements=='') {
	$where.=' and false' ;
} elseif($number_elements<4) {
	$where.=' and `Product Family Record Type` in ('.$_elements.')' ;
} 




$filter_msg='';
$wheref='';
if ($f_field=='code' and $f_value!='')
	$wheref.=" and `Product Family Code`  like '".addslashes($f_value)."%'";
if ($f_field=='name' and $f_value!='')
	$wheref.=" and `Product Family Name`  like '%".addslashes($f_value)."%'";








?>
