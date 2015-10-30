<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:  24 September 2015 00:36:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
$tab='delivery_notes';
$ar_file='ar_orders_tables.php';
$tipo='delivery_notes';

$default=$user->get_tab_defaults($tab);

$table_views=array(

);

$table_filters=array(
	'customer'=>array('label'=>_('Customer'), 'title'=>_('Customer name')),
	'number'=>array('label'=>_('Number'), 'title'=>_('Order number')),

);

$parameters=array(
	'parent'=>$state['parent'],
	'parent_key'=>$state['parent_key'],
);


include 'utils/get_table_html.php';



?>
