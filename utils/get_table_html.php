<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2015 12:46:17 GMT+8, Kuala Lumour, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


if (isset($_SESSION['table_state'][$tab])) {
	$table_state=$_SESSION['table_state'][$tab];

	if (isset($table_state['o'])) {
		$sort_key=$table_state['o'];
	}else {
		$sort_key=$default['sort_key'];
	}

	if (isset($table_state['od'])) {
		$sort_order=$table_state['od'];
	}else {
		$sort_order=$default['sort_order'];
	}

	if (isset($table_state['nr'])) {
		$results_per_page=$table_state['nr'];
	}else {
		$results_per_page=$default['rpp'];
	}

	if (isset($table_state['view'])) {
		$table_view=$table_state['view'];
	}else {
		$table_view=$default['view'];
	}

	if (isset($table_state['f_field'])) {
		$f_field=$table_state['f_field'];
	}else {
		$f_field=$default['f_field'];
	}


}else {
	$sort_key=$default['sort_key'];
	$sort_order=$default['sort_order'];
	$results_per_page=$default['rpp'];
	$table_view=$default['view'];
	$f_field=$default['f_field'];
}

$parameters['f_field']=$f_field;
$parameters['tab']=$tab;

$parameters=json_encode($parameters);



$request='/'.$ar_file.'?tipo='.$tipo.'&parameters='.$parameters;



$smarty->assign('results_per_page_options',$default['rpp_options']);
$smarty->assign('results_per_page',$results_per_page);


$smarty->assign('f_field',$f_field);
$smarty->assign('f_label',$table_filters[$f_field]['label']);

$smarty->assign('sort_key',$sort_key);
$smarty->assign('sort_order',$sort_order);
$smarty->assign('request',$request);
$smarty->assign('ar_file',$ar_file);
$smarty->assign('tipo',$tipo);
$smarty->assign('parameters',$parameters);
$smarty->assign('tab',$tab);
$smarty->assign('table_view',$table_view);

if (isset($table_views[$table_view]))
	$table_views[$table_view]['selected']=true;

$smarty->assign('table_views',$table_views);

$html=$smarty->fetch('table.tpl');

?>
