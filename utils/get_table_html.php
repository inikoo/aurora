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
}else {
	$table_state=array();
}

foreach ($default as $key=>$value) {
	if ($key=='rpp_options' or $key=='frequency' ) {

$parameters[$key]=$value;
	}elseif ($key=='export_fields') {


		$smarty->assign('export_fields', $value);

	}elseif ($key=='sort_key') {

		if (isset($table_state['o'])) {
			$sort_key=$table_state['o'];
		}else {
			$sort_key=$default['sort_key'];
		}

	}elseif ($key=='sort_order') {
		if (isset($table_state['od'])) {
			$sort_order=$table_state['od'];
		}else {
			$sort_order=$default['sort_order'];
		}


	}elseif ($key=='rpp') {
		if (isset($table_state['nr'])) {
			$results_per_page=$table_state['nr'];
		}else {
			$results_per_page=$default['rpp'];
		}


	}else {
		if (isset($table_state[$key])) {
			$parameters[$key]=$table_state[$key];
		}else {
			$parameters[$key]=$value;
		}
	}
}

//print_r($parameters['elements']['type']);

if (isset($metadata['parameters'])) {
	foreach ($metadata['parameters'] as $_key=>$_value) {
		if (isset($parameters[$_key]))$parameters[$_key]=$_value;
	}
}

//print_r($metadata);

if (isset($metadata['element'])) {

	foreach ($metadata['element'] as $element_type=>$elements) {

		if (isset($parameters['elements'][$element_type])) {

			foreach ($elements as $_key=>$value) {
				$parameters['elements'][$element_type]['items'][$_key]['selected']=$value;
			}

		}

	}

}
//print_r($parameters['elements']['type']);



$parameters['tab']=$tab;

if (isset($parameters['period'])) {
	$smarty->assign('period', $parameters['period']);

	if ($parameters['period']=='day' or $parameters['period']=='interval') {

		$smarty->assign('from', $parameters['from']);
		$smarty->assign('to', $parameters['to']);
		$smarty->assign('from_mmddyy', strftime("%m/%d/%Y", strtotime($parameters['from'])));
		$smarty->assign('to_mmddyy', strftime("%m/%d/%Y", strtotime($parameters['to'])));
		$smarty->assign('from_locale', strftime("%x", strtotime($parameters['from'])));
		$smarty->assign('to_locale', strftime("%x", strtotime($parameters['to'])));
	}else {
		$smarty->assign('from', '');
		$smarty->assign('to', '');
		$smarty->assign('from_mmddyy', '');
		$smarty->assign('to_mmddyy', '');
		$smarty->assign('from_locale', '');
		$smarty->assign('to_locale', '');
	}

}

$smarty->assign('f_field', $parameters['f_field']);
$smarty->assign('f_label', ($parameters['f_field'] ? $table_filters[$parameters['f_field']]['label']:''  ));
$table_view=$parameters['view'];
$smarty->assign('table_view', $parameters['view']);


if (array_key_exists('elements', $parameters))
	$smarty->assign('elements', $parameters['elements']);
if (array_key_exists('elements_type', $parameters))
	$smarty->assign('elements_type', $parameters['elements_type']);



$parameters=json_encode($parameters);



$request='/'.$ar_file.'?tipo='.$tipo.'&parameters='.$parameters;


$smarty->assign('results_per_page_options', $default['rpp_options']);
$smarty->assign('results_per_page', $results_per_page);



$smarty->assign('sort_key', $sort_key);
$smarty->assign('sort_order', $sort_order);
$smarty->assign('request', $request);
$smarty->assign('ar_file', $ar_file);
$smarty->assign('tipo', $tipo);

$smarty->assign('parameters', $parameters);
$smarty->assign('tab', $tab);

if (isset($columns_parameters)){
$smarty->assign('columns_parameters', $columns_parameters);

}


if (isset($table_views[$table_view]))
	$table_views[$table_view]['selected']=true;



$smarty->assign('table_views', $table_views);

$html=$smarty->fetch('table.tpl');

?>
