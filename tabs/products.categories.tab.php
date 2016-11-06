<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 February 2016 at 13:39:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'products.categories';
$ar_file = 'ar_products_tables.php';
$tipo    = 'categories';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'label' => array(
        'label' => _('Label'),
        'title' => _('Category label')
    ),
    'code'  => array(
        'label' => _('Code'),
        'title' => _('Category code')
    ),

);

$parameters = array(
    'parent'     => 'store',
    'parent_key' => $state['parent_key'],
    'subject'    => 'product',
);


/*

$table_buttons[]=array(
	'icon'=>'plus',
	'title'=>_('New category'),
	'id'=>'new_record',
	'inline_new_object'=>
	array(
		'field_id'=>'Category_Code',
		'field_label'=>_('Add category').':',
		'field_edit'=>'string',
		'object'=>'Category',
		'parent'=>$state['parent'],
		'parent_key'=>$state['parent_key'],
'placeholder'=>_("Category's code")
	)

);

*/
/*
$table_buttons[]=array(
	'icon'=>'link',
	'title'=>_('Associate supplier'),
	'id'=>'new_record',
	'inline_new_object'=>
	array(
		'field_id'=>'Supplier_Code',
		'field_label'=>_('Associate supplier').':',
		'field_edit'=>'string',
		'object'=>'Agent_Supplier',
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
		'placeholder'=>_("Supplier's code")
	)

);
*/

$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New category'),
    'reference' => "products/".$state['parent_key']."/category/new"
);

$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
