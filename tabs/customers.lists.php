<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2015 12:39:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='customers.lists';
$ar_file='ar_customers_tables.php';
$tipo='lists';


$default_view='overview';
$default_f_field='name';
$default_sort_key='creation_date';
$default_sort_order=1;
$default_results_per_page=20;
$results_per_page_options=array(500,100,50,20);
$table_views=array(

);
$table_filters=array(
	'name'=>array('label'=>_('Name'),'title'=>_('List name')),
	
);

$parameters=json_encode(array(
		'parent'=>'store',
		'parent_key'=>$state['parent_key'],
		'awhere'=>0,
		'f_field'=>$default_f_field,
		'tab'=>$tab


	));


include('utils/get_table_html.php');



?>
