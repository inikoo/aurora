<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 3 April 2016 at 18:16:39 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='supplier.supplier_parts';
$ar_file='ar_inventory_tables.php';
$tipo='supplier_parts';

$default=$user->get_tab_defaults($tab);



$table_views=array(
	'overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
	'parts'=>array('label'=>_('Part'), 'title'=>_('Part details')),
	'reorder'=>array('label'=>_('Reorder')),

);

$table_filters=array(
	'reference'=>array('label'=>_('Reference'), 'title'=>_('Part reference')),

);

$parameters=array(
	'parent'=>$state['object'],
	'parent_key'=>$state['key'],

);


include 'utils/get_table_html.php';


?>
