<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:  16 September 2015 17:30:56 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$from='';
$to='';

$request="/ar_orders.php?tipo=invoices&parent=store&parent_key=".$state['parent_key'].'&awhere=0&f_field=&f_value=&elements_type&from='.$from.'&to='.$to;
$default_sort_key='id';
$default_sort_order=1;

if (isset($_SESSION['state'][$state['module']][$state['section']][$tab]['o'])) {
	$sort_key=$_SESSION['state'][$state['module']][$state['section']][$tab]['o'];
}else {
	$sort_key=$default_sort_key;
}

if (isset($_SESSION['state'][$state['module']][$state['section']][$tab]['od'])) {
	$sort_order=$_SESSION['state'][$state['module']][$state['section']][$tab]['od'];
}else {
	$sort_order=$default_sort_order;
}

$smarty->assign('sort_key',$sort_key);
$smarty->assign('sort_order',$sort_order);



$smarty->assign('request',$request);




$table_views=array(


);
$smarty->assign('table_views',$table_views);

$html=$smarty->fetch('table.tpl');

?>
