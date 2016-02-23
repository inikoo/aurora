<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 February 2016 at 13:39:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



$tab='products.categories';
$ar_file='ar_products_tables.php';
$tipo='categories';

$default=$user->get_tab_defaults($tab);



$table_views=array(

);

$table_filters=array(
	'label'=>array('label'=>_('Label'),'title'=>_('Category label')),
	'code'=>array('label'=>_('Code'),'title'=>_('Category code')),

);

$parameters=array(
		'parent'=>'store',
		'parent_key'=>$state['parent_key'],
		'subject'=>'product',
);


include('utils/get_table_html.php');


?>
