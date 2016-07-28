<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2016 at 01:37:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab='supplier_part.supplier.orders';
$ar_file='ar_suppliers_tables.php';
$tipo='orders';

$default=$user->get_tab_defaults($tab);

$table_views=array(
);

$table_filters=array(
	'number'=>array('label'=>_('Number'), 'title'=>_('Order number')),
);

$parameters=array(
	'parent'=>$state['object'],
	'parent_key'=>$state['key'],

);


include 'utils/get_table_html.php';

?>
