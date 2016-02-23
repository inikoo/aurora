<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:  16 February 2016 at 11:20:41 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$category=$state['_object'];


if ($category->get('Category Scope')=='Product') {

	if ($category->get('Category Subject')=='Product') {

		$tab='category.products';
		$ar_file='ar_products_tables.php';
		$tipo='products';

		$default=$user->get_tab_defaults($tab);

		$table_views=array(
			'overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
			'sales'=>array('label'=>_('Sales'), 'title'=>_('Sales')),

		);

		$table_filters=array(
			'code'=>array('label'=>_('Code'), 'title'=>_('Product code')),
			'name'=>array('label'=>_('Name'), 'title'=>_('Product name')),

		);

		$parameters=array(
			'parent'=>$state['object'],
			'parent_key'=>$state['key'],

		);


	}else {


		$tab='subject_categories';
		$ar_file='ar_categories_tables.php';
		$tipo='subject_categories';

		$default=$user->get_tab_defaults($tab);



		$table_views=array(

		);

		$table_filters=array(
			'label'=>array('label'=>_('Label'), 'title'=>_('Category label')),
			'code'=>array('label'=>_('Code'), 'title'=>_('Category code')),

		);

		$parameters=array(
			'parent'=>$state['object'],
			'parent_key'=>$state['key'],

		);



	}


}






include 'utils/get_table_html.php';



?>
