<?php
/*
 File: customer.php


 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Recreated: 17 April 2014 10:45:07 CEST, Ourense Spain

 Copyright (c) 2014, Inikoo

 Version 2.0


*/

include_once 'common.php';
include_once 'class.Supplier.php';
include_once 'class.SupplierProduct.php';

$view_suppliers=$user->can_view('suppliers');

if (!$view_suppliers) {
	header('Location: index.php');
	exit();
}

$create=$user->can_create('suppliers');
$modify=$user->can_edit('suppliers');

$modify_stock=$user->can_edit('product stock');

$smarty->assign('modify_stock',$modify_stock);

$view_suppliers=$user->can_view('suppliers');
$smarty->assign('view_suppliers',$view_suppliers);


$smarty->assign('create',$create);
$smarty->assign('modify',$modify);


if (isset($_REQUEST['pid'])) {
	$product_supplier_key=$_REQUEST['pid'];
}else {
	exit("no supplier product pid");
}



if (!$product_supplier_key) {
	header('Location: suppliers.php?e');
	exit();
}
$supplier_product= new SupplierProduct('pid',$product_supplier_key);
if (!$supplier_product->id) {
	header('Location: suppliers.php');
	exit;

}
$supplier_key=$supplier_product->supplier_key;
$supplier_product_code=$supplier_product->code;
$supplier=new Supplier($supplier_product->data['Supplier Key']);

$smarty->assign('supplier_key',$supplier_key);
$smarty->assign('supplier_id',$supplier_key);
$smarty->assign('supplier',$supplier);

$smarty->assign('supplier',$supplier);



$smarty->assign('search_label',_('Suppliers'));
$smarty->assign('search_scope','supplier_products');
$smarty->assign('supplier_product',$supplier_product);



$units_tipo=array(
	'Piece'=>array('fname'=>_('Piece'),'name'=>'Piece','selected'=>false),
	'Grams'=>array('fname'=>_('Grams'),'name'=>'Grams','selected'=>false),
	'Liters'=>array('fname'=>_('Liters'),'name'=>'Liters','selected'=>false),
	'Meters'=>array('fname'=>_('Meters'),'name'=>'Meters','selected'=>false),
	'Other'=>array('fname'=>_('Other'),'name'=>'Other','selected'=>false),
);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',

	$yui_path.'editor/assets/skins/sam/editor.css',
	'css/container.css',
	'css/text_editor.css',
	'css/common.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
	'theme.css.php'

);



$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'datatable/datatable-debug.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'editor/editor-min.js',
	$yui_path.'uploader/uploader-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/search.js',
	'js/editor_image_uploader.js',
	'js/table_common.js',
	'js/upload_image.js',

	'js/edit_common.js',
	'js/part_common.js',

	'edit_supplier_product.js.php?pid='.$supplier_product->pid
);

$smarty->assign('parent','suppliers');





$smarty->assign('date',date('Y-m-d'));
$smarty->assign('time',date('H:i'));

if (isset($_REQUEST['edit'])  and in_array($_REQUEST['edit'],array('description', 'products', 'suppliers','transactions'))) {
	$_SESSION['state']['supplier_product']['edit']=$_REQUEST['edit'];
}



if (isset($_REQUEST['edit_description_block'])  and in_array($_REQUEST['edit_description_block'],array('status','description','properties','pictures','info','health_and_safety'))) {
	$_SESSION['state']['supplier_product']['edit_description_block']=$_REQUEST['edit_description_block'];
}

$smarty->assign('edit',$_SESSION['state']['supplier_product']['edit']);
$smarty->assign('description_block',$_SESSION['state']['supplier_product']['edit_description_block']);





$smarty->assign('shape_example',$_shape_example);
$smarty->assign('shapes',$_shape);
$_SESSION['state']['product']['shapes_example']=json_encode($_shape_example);
$_SESSION['state']['product']['shapes']=json_encode($_shape);



$smarty->assign('thousands_sep',$_SESSION['locale_info']['thousands_sep']);
$smarty->assign('decimal_point',$_SESSION['locale_info']['decimal_point']);


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$units_types=getEnumValues("Supplier Product Dimension","Supplier Product Unit Type" );
//print_r($units_types);
$unit_type_options=array();
foreach ($units_types as $units_type ) {
	$unit_type_options[$units_type]=$units_type;
}

$smarty->assign('unit_type_options',$unit_type_options

);
$smarty->assign('unit_type',$supplier_product->data['Supplier Product Unit Type']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('title',_('Editing').' '.$supplier_product->data['Supplier Product Code']);





$tipo_filter=$_SESSION['state']['supplier_product']['history']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['supplier_product']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'abstract','menu_label'=>_('Records with abstract *<i>x</i>*'),'label'=>_('Abstract')),
	'author'=>array('db_key'=>'author','menu_label'=>_('Done by <i>x</i>*'),'label'=>_('Notes')),
	// 'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	// 'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)')),
);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu0',$filter_menu);

/*
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['supplier_product']['products']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['supplier_product']['products']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Products with code *<i>x</i>*'),'label'=>_('Code')),
);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu1',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);
*/
$tipo_filter=$_SESSION['state']['supplier_product']['parts']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['supplier_product']['parts']['f_value']);
$filter_menu=array(
	'reference'=>array('db_key'=>'code','menu_label'=>_('Parts with reference *<i>x</i>*'),'label'=>_('Reference'))
);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu2',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);



$tipo_filter3='code';
$filter_menu3=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Supplier Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Supplier Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name3',$filter_menu3[$tipo_filter3]['label']);
$smarty->assign('filter_menu3',$filter_menu3);
$smarty->assign('filter3',$tipo_filter3);
$smarty->assign('filter_value3','');

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$tipo_filter4='code';
$filter_menu4=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Country Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Country Name'),'label'=>_('Name')),
	'wregion'=>array('db_key'=>'wregion','menu_label'=>_('World Region Name'),'label'=>_('Region')),
);
$smarty->assign('filter_name4',$filter_menu4[$tipo_filter4]['label']);
$smarty->assign('filter_menu4',$filter_menu4);
$smarty->assign('filter4',$tipo_filter4);
$smarty->assign('filter_value4','');

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu4',$paginator_menu);



$tipo_filter=$_SESSION['state']['supplier_product']['historic_parts']['f_field'];
$smarty->assign('filter5',$tipo_filter);
$smarty->assign('filter_value5',$_SESSION['state']['supplier_product']['historic_parts']['f_value']);
$filter_menu=array(
	'reference'=>array('db_key'=>'reference','menu_label'=>_('Parts with reference *<i>x</i>*'),'label'=>_('Reference'))
);
$smarty->assign('filter_name5',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu5',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu5',$paginator_menu);


$smarty->assign('show_history',$_SESSION['state']['supplier_product']['show_history']);
//$smarty->assign('products_view',$_SESSION['state']['supplier_product']['products']['view']);

$lenght_units=array('cm'=>'cm','mm'=>'mm','m'=>'m','in'=>'in','yd'=>'yd','ft'=>'ft');
$weight_units=array('kg'=>'Kg','g'=>'g','oz'=>'oz','lb'=>'lb');

$smarty->assign('lenght_units',$lenght_units);
$smarty->assign('weight_units',$weight_units);
$smarty->assign('transactions_type_elements',$_SESSION['state']['supplier_product']['transactions']['elements']);



$order=$_SESSION['state']['supplier']['supplier_products']['order'];



	$db_period=get_interval_db_name($_SESSION['state']['supplier']['supplier_products']['period']);

	if ($order=='id'){
		$order='`Supplier Product ID`';
		$_order='Supplier Product ID';
		$order_label=_('Supplier Product ID');
	}elseif ($order=='supplier'){
		$order='`Supplier Code`';
		$_order='Supplier Code';
		$order_label=_('Supplier Code');
	}elseif ($order=='code'){
		$order='`Supplier Product Code`';
		$_order='Supplier Product Code';
		$order_label=_('Code');
	}elseif ($order=='used_in'){
		$order='`Supplier Product XHTML Sold As`';
			$_order='Supplier Product XHTML Sold As';
		$order_label=_('Sold As');
	}elseif ($order=='tuos'){
		$order='`Supplier Product Days Available`';
			$_order='Supplier Product Days Available';
		$order_label=_('Days Available');
	}elseif ($order=='stock'){
		$order='`Supplier Product Stock`';
			$_order='Supplier Product Stock`';
		$order_label=_('Stock`');
	}elseif ($order=='name'){
		$order='`Supplier Product Name`';
			$_order='Supplier Product Name';
		$order_label=_('Name');
	}elseif ($order=='profit') {
		$order="`Supplier Product $db_period Acc Parts Profit`";
			$_order="Supplier Product $db_period Acc Parts Profit";
		$order_label=_('Profit');
	}
	elseif ($order=='required') {
		$order="`Supplier Product $db_period Acc Parts Required`";
			$_order="Supplier Product $db_period Acc Parts Required";
		$order_label=_('Required');
	}elseif ($order=='state') {
		$order="`Supplier Product State`";
			$_order='Supplier Product State';
		$order_label=_('State');
	}
	elseif ($order=='sold') {
		$order="`Supplier Product $db_period Acc Parts Sold`";
	$_order="Supplier Product $db_period Acc Parts Sold";
		$order_label=_('Parts Sold');

	}
	elseif ($order=='sales') {
		$order="`Supplier Product $db_period Acc Parts Sold Amount`";
$_order="Supplier Product $db_period Acc Parts Sold Amount";
		$order_label=_('Sales');
	}
	elseif ($order=='margin') {
		$order="`Supplier Product $db_period Acc Parts Margin`";
$_order="Supplier Product $db_period Acc Parts Margin";
		$order_label=_('Margin');
	}
	elseif ($order=='dispatched') {
		$order="`Supplier Product $db_period Acc Parts Dispatched`";
$_order="Supplier Product $db_period Acc Parts Dispatched";
		$order_label=_('Dispatched');
	}else{
		$order='`Supplier Product Code`';
	$_order='Supplier Product Code';
		$order_label=_('Code');
}

//$_order=preg_replace('/`/','',$order);
$sql=sprintf("select  P.`Supplier Product ID` as id , `Supplier Product Name` as name from `Supplier Product Dimension` P  where  `Supplier Key`=%d  and %s < %s  order by %s desc  limit 1",
	$supplier->id,
	$order,
	prepare_mysql($supplier_product->get($_order)),
	$order
);
//print $sql;
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']='edit_supplier_product.php?pid='.$row['id'];
	$prev['title']=$row['name'];
	$smarty->assign('prev_pid',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select P.`Supplier Product ID` as id , `Supplier Product Name` as name from `Supplier Product Dimension` P  where  `Supplier Key`=%d   and  %s>%s  order by %s   ",
	$supplier->id,
	$order,
	prepare_mysql($supplier_product->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']='edit_supplier_product.php?pid='.$row['id'];
	$next['title']=$row['name'];
	$smarty->assign('next_pid',$next);
}
mysql_free_result($result);

	$smarty->assign('corporate_currency_symbol',$corporate_currency_symbol);


$smarty->display('edit_supplier_product.tpl');

?>
