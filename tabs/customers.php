<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:27 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='customers';
$ar_file='ar_contacts.php';
$tipo='customers';
$parameters=json_encode(array(
		'parent'=>'store',
		'parent_key'=>$state['parent_key'],
		'awhere'=>0,
		'f_field'=>'',
		'f_value'=>'',
		'elements_type'=>'',
		'tab'=>$tab


	));

$request='/'.$ar_file.'?tipo='.$tipo.'&parameters='.$parameters;
$default_sort_key='id';
$default_sort_order=1;
$default_results_per_page=20;
$results_per_page_options=array(500,100,50,20);


if (isset($_SESSION['table_state'][$tab])) {
		$table_state=$_SESSION['table_state'][$tab];

		if (isset($table_state['o'])) {
			$sort_key=$table_state['o'];
		}else {
			$sort_key=$default_sort_key;
		}

		if (isset($table_state['od'])) {
			$sort_order=$table_state['od'];
		}else {
			$sort_order=$default_sort_order;
		}

		if (isset($table_state['nr'])) {
			$results_per_page=$table_state['nr'];
		}else {
			$results_per_page=$default_results_per_page;
		}


	}else {
		$sort_key=$default_sort_key;
		$sort_order=$default_sort_order;
		$results_per_page=$default_results_per_page;
	}



	$smarty->assign('results_per_page_options',$results_per_page_options);
	$smarty->assign('results_per_page',$results_per_page);



	$smarty->assign('sort_key',$sort_key);
	$smarty->assign('sort_order',$sort_order);
	$smarty->assign('request',$request);
	$smarty->assign('ar_file',$ar_file);
	$smarty->assign('tipo',$tipo);
	$smarty->assign('parameters',$parameters);







	$table_views=array(
		'overview'=>array('label'=>_('Overview'),'title'=>_('Overview')),
		'sales'=>array('label'=>_('Sales'),'title'=>_('Sales'))

	);
	$smarty->assign('table_views',$table_views);

	$html=$smarty->fetch('table.tpl');


?>
