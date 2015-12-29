<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:  28 December 2015 at 17:49:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='invoices';
$ar_file='ar_orders_tables.php';
$tipo='invoices';

$default=$user->get_tab_defaults($tab);

$table_views=array(

);


$table_filters=array(
	'customer'=>array('label'=>_('Customer'), 'title'=>_('Customer name')),
	'number'=>array('label'=>_('Number'), 'title'=>_('Invoice number')),

);

$parameters=array(
	'parent'=>$state['object'],
	'parent_key'=>$state['key'],
);



include 'utils/get_table_html.php';



?>
