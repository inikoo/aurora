<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:  16 September 2015 14:43:02 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='orders';
$ar_file='ar_orders_tables.php';
$tipo='orders';

$default=$user->get_tab_defaults($tab);

$table_views=array(

);

$table_filters=array(
	'customer'=>array('label'=>_('Customer'),'title'=>_('Customer name')),
	'number'=>array('label'=>_('Number'),'title'=>_('Order number')),

);

$parameters=array(
		'parent'=>'store',
		'parent_key'=>$state['parent_key'],
		'awhere'=>0,
		'elements_type'=>'',
		'period'=>$default['period'],
		'to'=>$default['to'],
		'from'=>$default['from']
	);



include('utils/get_table_html.php');



?>
