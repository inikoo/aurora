<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 April 2016 at 16:33:04 GMT+8, Lovina, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='suppliers.categories';
$ar_file='ar_suppliers_tables.php';
$tipo='categories';

$default=$user->get_tab_defaults($tab);



$table_views=array(

);

$table_filters=array(
	'label'=>array('label'=>_('Label'),'title'=>_('Category label')),
	'code'=>array('label'=>_('Code'),'title'=>_('Category code')),

);

$parameters=array(
		'parent'=>$state['parent'],
		'parent_key'=>$state['parent_key'],
		'subject'=>'supplier',
);


include('utils/get_table_html.php');


?>
