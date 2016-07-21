<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2016 at 22:02:56 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016 Inikoo

 Version 3

*/

$tab='supplier.deliveries';
$ar_file='ar_suppliers_tables.php';
$tipo='deliveries';

$default=$user->get_tab_defaults($tab);

$table_views=array(
);

$table_filters=array(
	'number'=>array('label'=>_('Number')),
);

$parameters=array(
	'parent'=>$state['object'],
	'parent_key'=>$state['key'],

);




include 'utils/get_table_html.php';

?>
