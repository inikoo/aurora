<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2016 at 00:13:30 GMT+8, Kaula Lumpur, Mlaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='inventory.barcodes';
$ar_file='ar_inventory_tables.php';
$tipo='barcodes';

$default=$user->get_tab_defaults($tab);



$table_views=array(

);

$table_filters=array(
	'number'=>array('label'=>_('Number'), 'title'=>_('Barcode Number')),
	'reference'=>array('label'=>_('Part Reference'), 'title'=>_('Part Reference')),

);


$parameters=array(
	'parent'=>$state['parent'],
	'parent_key'=>$state['parent_key'],

);


include 'utils/get_table_html.php';


?>
