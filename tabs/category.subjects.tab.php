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
			'overview'=>array('label'=>_('Overview')),
			'performance'=>array('label'=>_('Performance')),
			'sales'=>array('label'=>_('Sales')),
			'sales_y'=>array('label'=>_('Invoiced amount (Yrs)')),
			'sales_q'=>array('label'=>_('Invoiced amount (Qs)')),

		);

		$table_filters=array(
			'code'=>array('label'=>_('Code'), 'title'=>_('Product code')),
			'name'=>array('label'=>_('Name'), 'title'=>_('Product name')),

		);

		$parameters=array(
			'parent'=>$state['object'],
			'parent_key'=>$state['key'],

		);


		$table_buttons[]=array('icon'=>'edit', 'title'=>_("Edit products"), 'id'=>'edit_table');

		$table_buttons[]=array('icon'=>'cube', 'title'=>_('All products'), 'change_tab'=>'category.all_subjects', 'class'=>'move_left');


		$table_buttons[]=array(
			'icon'=>'link',
			'title'=>_('Associate product'),
			'id'=>'new_record',
			'inline_new_object'=>
			array(
				'field_id'=>'Store_Product_Code',
				'field_label'=>_('Associate product').':',
				'field_edit'=>'dropdown',
				'object'=>'Category_Product',
				'parent'=>$state['object'],
				'parent_key'=>$state['key'],
				'placeholder'=>_("Product's code"),
				'dropdown_select_metadata'=>base64_encode(json_encode(array(
							'scope'=>'products',
							'parent'=>'store',
							'parent_key'=>$state['_object']->get('Product Category Store Key'),
							'options'=>array()
						)))
			)

		);
		$smarty->assign('table_buttons', $table_buttons);


	}
	else {


		$tab='category.product_families';
		$ar_file='ar_products_tables.php';
		$tipo='product_families';

		$default=$user->get_tab_defaults($tab);



		$table_views=array(

		);

		$table_filters=array(
			'code'=>array('label'=>_('Code')),
			'label'=>array('label'=>_('Label')),

		);

		$parameters=array(
			'parent'=>$state['object'],
			'parent_key'=>$state['key'],

		);



	}


}

elseif ($category->get('Category Scope')=='Part') {



	$tab='category.parts';
	$ar_file='ar_inventory_tables.php';
	$tipo='parts';

	$default=$user->get_tab_defaults($tab);

	$table_views=array(
		'overview'=>array('label'=>_('Overview')),
		'performance'=>array('label'=>_('Performance')),
		'stock'=>array('label'=>_('Stock')),
		'sales'=>array('label'=>_('Revenue')),
		'dispatched_q'=>array('label'=>_('Dispatched (Qs)')),
		'dispatched_y'=>array('label'=>_('Dispatched (Yrs)')),
		'revenue_q'=>array('label'=>_('Revenue (Qs)')),
		'revenue_y'=>array('label'=>_('Revenue (Yrs)')),

	);


	$table_filters=array(
		'reference'=>array('label'=>_('Reference'), 'title'=>_('Part reference')),
		'name'=>array('label'=>_('Name'), 'title'=>_('Part name')),

	);

	$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],

	);


	$table_buttons[]=array('icon'=>'edit', 'title'=>_("Edit supplier's parts"), 'id'=>'edit_table');
	$table_buttons[]=array('icon'=>'square', 'title'=>_('All parts'), 'change_tab'=>'category.all_subjects', 'class'=>'move_left');



	$table_buttons[]=array(
		'icon'=>'link',
		'title'=>_('Associate part'),
		'id'=>'new_record',
		'inline_new_object'=>
		array(
			'field_id'=>'Part_Reference',
			'field_label'=>_('Associate part').':',
			'field_edit'=>'dropdown',
			'object'=>'Category_Part',
			'parent'=>$state['object'],
			'parent_key'=>$state['key'],
			'placeholder'=>_("Parts's reference"),
			'dropdown_select_metadata'=>base64_encode(json_encode(array(
						'scope'=>'parts',
						'parent'=>'account',
						'parent_key'=>1,
						'options'=>array()
					)))

		)

	);




	$smarty->assign('table_buttons', $table_buttons);






}
elseif ($category->get('Category Scope')=='Supplier') {



	$tab='category.suppliers';
	$ar_file='ar_suppliers_tables.php';
	$tipo='suppliers';

	$default=$user->get_tab_defaults($tab);

	$table_views=array(
	'overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
	'contact'=>array('label'=>_('Contact'), 'title'=>_('Contact details')),
	'parts'=>array('label'=>_("Parts's stock")),
	'sales'=>array('label'=>_("Parts's sales")),
	'sales_q'=>array('label'=>_("Parts's sales (Qs)")),
	'sales_y'=>array('label'=>_("Parts's sales (Yrs)")),
	'orders'=>array('label'=>_('Orders'), 'title'=>_('Purchase orders, deliveries & invoices')),
);

	$table_filters=array(
		'code'=>array('label'=>_('Code')),
		'name'=>array('label'=>_('Name')),

	);

	$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],

	);



	$table_buttons[]=array('icon'=>'ship', 'title'=>_('All suppliers'), 'change_tab'=>'category.all_subjects');


	$table_buttons[]=array(
		'icon'=>'link',
		'title'=>_('Associate supplier'),
		'id'=>'new_record',
		'inline_new_object'=>
		array(
			'field_id'=>'Supplier_Code',
			'field_label'=>_('Associate supplier').':',
			'field_edit'=>'dropdown',
			'object'=>'Category_Supplier',
			'parent'=>$state['object'],
			'parent_key'=>$state['key'],
			'placeholder'=>_("Supplier's code"),
			'dropdown_select_metadata'=>base64_encode(json_encode(array(
						'scope'=>'suppliers',
						'parent'=>'account',
						'parent_key'=>1,
						'options'=>array()
					)))
		)

	);
	$smarty->assign('table_buttons', $table_buttons);






}




include 'utils/get_table_html.php';



?>
