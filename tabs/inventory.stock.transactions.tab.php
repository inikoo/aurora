<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2016 at 10:48:49 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



$tab='inventory.stock.transactions';
$ar_file='ar_inventory_tables.php';
$tipo='stock_transactions';

$default=$user->get_tab_defaults($tab);



$table_views=array(

);

$table_filters=array(
	'note'=>array('label'=>_('Note'), 'title'=>_('Note')),

);


$parameters=array(
	'parent'=>'account',
	'parent_key'=>1,

);

include 'utils/get_table_html.php';


?>
