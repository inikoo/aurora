<?php
/*
 File: new_contact.php

 UI new contact page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Warehouse.php';
include_once 'class.Supplier.php';
include_once 'class.SupplierProduct.php';

if (!$user->can_view('customers')) {
	header('Location: index.php');
	exit();
}
if (!$user->can_edit('parts')) {
	header('Location: index.php');
	exit();

}

//TODO a better way to deal with several warehouses;
$warehouse=new Warehouse(1);
$smarty->assign('part_families_root_category_key',$warehouse->data['Warehouse Family Category Key']);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
	'css/text_editor.css',
	'css/common.css',
	'css/button.css',
	'css/container.css',
	'css/table.css',
	'css/edit.css',
	'css/new_part.css',
	'theme.css.php'
);

$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/php.default.min.js',
	'js/jquery.min.js',
'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/search.js',
	'js/new_part.js'
);


if (isset($_REQUEST['parent'])) {
	$parent=$_REQUEST['parent'];
}else {
	exit();
}

$smarty->assign('parent',$parent);

if ($parent=='parts_family') {

	$smarty->assign('search_label',_('Parts'));
	$smarty->assign('search_scope','parts');
	
	$smarty->assign('parent','parts');
	$smarty->assign('supplier_id','');


}elseif ($parent=='supplier_product') {
	$smarty->assign('search_label',_('Suppliers'));
	$smarty->assign('search_scope','supplier_products');

	$smarty->assign('parent',$parent);



	$supplier_product=new SupplierProduct('pid',$_REQUEST['parent_key']);
	
	if(!$supplier_product->id){
		exit('supplier product not found');
	}
	
	$supplier=new Supplier($supplier_product->data['Supplier Key']);
	$smarty->assign('supplier_product',$supplier_product);
	$smarty->assign('supplier',$supplier);
	
	$smarty->assign('parent','suppliers');
	$smarty->assign('supplier_id',$supplier->id);



}



$tipo_filter0='code';
$filter_menu0=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Category Code'),'label'=>_('Code')),
	'label'=>array('db_key'=>'label','menu_label'=>_('Category Label'),'label'=>_('Label')),
);
$smarty->assign('filter_name0', $filter_menu0[$tipo_filter0]['label']);
$smarty->assign('filter_menu0', $filter_menu0);
$smarty->assign('filter0', $tipo_filter0);
$smarty->assign('filter_value0', '');
$paginator_menu=array(10, 25, 50, 100, 500);
$smarty->assign('paginator_menu0', $paginator_menu);


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('title','New part');

$session_data=base64_encode(json_encode(array(
			'label'=>array(
				'Invalid_code'=>_('Invalid code'),
				'Invalid_name'=>_('Invalid name'),
				'Invalid_description'=>_('Invalid description'),
				'Invalid_date'=>_('Invalid date'),
				'Invalid_amount'=>_('Invalid amount'),
				'Invalid_number'=>_('Invalid number'),
				'Invalid_percentage'=>_('Invalid percentage'),
				'Code'=>_('Code'),
				'Label'=>_('Label'),
				'Parts'=>_('Parts'),
				'Name'=>_('Name'),

				'Page'=>_('Page'),
				'of'=>_('of')

			)
		)));
$smarty->assign('session_data', $session_data);


$smarty->display('new_part.tpl');


?>
