<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:27 16 September 2015 17:50:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$ar_file='ar_assets.php';
$tipo='departments';
$parameters=json_encode(array(
		'parent'=>'store',
		'parent_key'=>$state['key'],
		'awhere'=>0,
		'f_field'=>'',
		'f_value'=>'',
		'elements_type'=>'',
		'period'=>'1y',
		'avg'=>'totals',
		'percentages'=>0,
		'stock_percentages'=>0,
	));

$request='/'.$ar_file.'?tipo='.$tipo.'&parameters='.$parameters;
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
$smarty->assign('ar_file',$ar_file);
$smarty->assign('tipo',$tipo);
$smarty->assign('parameters',$parameters);




$table_views=array(
	'overview'=>array('label'=>_('Overview')),
	'sales'=>array('label'=>_('Sales'))

);
$smarty->assign('table_views',$table_views);

$html=$smarty->fetch('table.tpl');

?>
