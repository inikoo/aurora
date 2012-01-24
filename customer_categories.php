<?php
include_once 'class.Store.php';

include_once 'class.Category.php';

include_once 'common.php';
include_once 'assets_header_functions.php';



if (!$user->can_view('stores')  ) {
	header('Location: index.php');
	exit;
}
$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
//$modify=false;
$modify=$user->can_edit('customers');

get_header_info($user,$smarty);
$general_options_list=array();


$smarty->assign('view',$_SESSION['state']['customer_categories']['view']);

$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
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
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_category_common.js',
	'common_customers.js.php',
	'external_libs/ammap/ammap/swfobject.js'
);


$smarty->assign('options_box_width','200px');

if (isset($_REQUEST['id'])) {
	$category_key=$_REQUEST['id'];
} else {
	$category_key=$_SESSION['state']['customer_categories']['category_key'];
}

if (!$category_key) {

	if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
		$store_id=$_REQUEST['store'];

	} else {
		header('Location: customers.php&error=no_store_id');
		exit;
	}

	$store=new Store($store_id);
	$block_view=$_SESSION['state']['customer_categories']['base_block_view'];
	$smarty->assign('block_view',$block_view);

	$js_files[]='customer_categories_base.js.php';
	$tpl_file='customer_categories_base.tpl';

}
else {

	$category=new Category($category_key);
	if (!$category->id) {
		header('Location: customer_categories.php?id=0&error=cat_not_found');
		exit;

	}

	$store_id=$category->data['Category Store Key'];
	if (isset($_REQUEST['store_id']) and is_numeric($_REQUEST['store_id']) ) {
		$store_id=$_REQUEST['store_id'];

	} else {
		$store_id=$_SESSION['state']['store']['id'];
	}


	$store=new Store($store_id);

	if (!$store->id) {

		exit("Error wrong store");
	}

	$currency=$store->data['Store Currency Code'];
	$currency_symbol=currency_symbol($currency);


	$category_key=  $category->id;



	$block_view=$_SESSION['state']['customer_categories']['block_view'];
	$smarty->assign('block_view',$block_view);
	$smarty->assign('category',$category);

	if ($category->data['Category Deep']>1) {
		$parent_category=new Category($category->data['Category Parent Key']);
		$smarty->assign('parent_category',$parent_category);
	}


	$js_files[]='customer_categories.js.php';
	$tpl_file='customer_category.tpl';





	$tipo_filter=$_SESSION['state']['customers']['table']['f_field'];
	$smarty->assign('filter0',$tipo_filter);
	$smarty->assign('filter_value0',$_SESSION['state']['customers']['table']['f_value']);

	$filter_menu=array(
		'customer name'=>array('db_key'=>_('customer name'),'menu_label'=>_('Customer Name'),'label'=>_('Name')),
		'postcode'=>array('db_key'=>_('postcode'),'menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
		'country'=>array('db_key'=>_('country'),'menu_label'=>_('Customer Country'),'label'=>_('Country')),

		'min'=>array('db_key'=>_('min'),'menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
		'max'=>array('db_key'=>_('min'),'menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
		'last_more'=>array('db_key'=>_('last_more'),'menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
		'last_less'=>array('db_key'=>_('last_more'),'menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
		'maxvalue'=>array('db_key'=>_('maxvalue'),'menu_label'=>_('Balance less than').' '.$currency_symbol  ,'label'=>_('Balance')." <($currency_symbol)"),
		'minvalue'=>array('db_key'=>_('minvalue'),'menu_label'=>_('Balance more than').' '.$currency_symbol  ,'label'=>_('Balance')." >($currency_symbol)"),
	);


	$smarty->assign('filter_menu0',$filter_menu);
	$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
	$paginator_menu=array(10,25,50,100,500);
	$smarty->assign('paginator_menu0',$paginator_menu);


	//print_r($category->data);

}


$_SESSION['state']['customer_categories']['category_key']=$category_key;



$tipo_filter=$_SESSION['state']['customer_categories']['subcategories']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['customer_categories']['subcategories']['f_value']);

$filter_menu=array(
	'name'=>array('db_key'=>_('name'),'menu_label'=>_('Category Name'),'label'=>_('Name')),
);


$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['store']['history']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['site']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
	'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
	'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))

);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu2',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);





$_SESSION['state']['store']['id']=$store->id;
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);

$smarty->assign('parent','customers');
$smarty->assign('title',_('Customers Categories'));

$smarty->assign('subject','Customer');
//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('category_key',$category_key);
$smarty->assign('store_id',$store_id);
$smarty->assign('options_box_width','600px');

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display($tpl_file);
?>
