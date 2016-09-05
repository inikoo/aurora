<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:  27 May 2016 at 12:26:25 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$category=$state['_object'];


if ($category->get('Category Scope')=='Product') {

	if ($category->get('Category Subject')=='Product') {

		$tab='category.all_products';
		$ar_file='ar_products_tables.php';
		$tipo='category_all_products';

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



		$table_buttons[]=array('icon'=>'leaf', 'title'=>_('Associated products'), 'change_tab'=>'category.subjects');



		$smarty->assign('table_buttons', $table_buttons);


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
elseif ($category->get('Category Scope')=='Part') {

	if ($category->get('Category Subject')=='Part') {




		if ($category->get('Category Branch Type')=='Head') {

			$tab='category.all_parts';
			$ar_file='ar_inventory_tables.php';
			$tipo='category_all_availeable_parts';

			$default=$user->get_tab_defaults($tab);

			$table_views=array(
				'overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
				'sales'=>array('label'=>_('Sales'), 'title'=>_('Sales')),

			);

			$table_filters=array(
				'reference'=>array('label'=>_('Reference')),
				'name'=>array('label'=>_('Name')),

			);

			$parameters=array(
				'parent'=>$state['object'],
				'parent_key'=>$state['key'],

			);


			$table_buttons[]=array('icon'=>'leaf', 'title'=>_('Associated parts'), 'change_tab'=>'category.subjects');
			
			$smarty->assign('table_buttons', $table_buttons);
			
			
		}elseif ($category->get('Category Branch Type')=='Root'){
		
			$tab='category_root.all_parts';
			$ar_file='ar_inventory_tables.php';
			$tipo='category_all_parts';

			$default=$user->get_tab_defaults($tab);

			$table_views=array(
				'overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),

			);

			$table_filters=array(
				'reference'=>array('label'=>_('Reference')),

			);

			$parameters=array(
				'parent'=>$state['object'],
				'parent_key'=>$state['key'],

			);


			//$table_buttons[];
			//$smarty->assign('table_buttons', $table_buttons);

		
		}

	}
	else {


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
elseif ($category->get('Category Scope')=='Supplier') {



	$tab='category.all_suppliers';
	$ar_file='ar_suppliers_tables.php';
	$tipo='category_all_suppliers';

	$default=$user->get_tab_defaults($tab);

	$table_views=array(
		'overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
		'sales'=>array('label'=>_('Sales'), 'title'=>_('Sales')),

	);

	$table_filters=array(
		'code'=>array('label'=>_('Code')),
		'name'=>array('label'=>_('Name')),

	);

	$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],

	);


	$table_buttons[]=array('icon'=>'leaf', 'title'=>_('Associated suppliers'), 'change_tab'=>'category.subjects');



	$smarty->assign('table_buttons', $table_buttons);





}




include 'utils/get_table_html.php';



?>
