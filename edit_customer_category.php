<?php
include_once 'class.Category.php';
include_once 'class.Store.php';

include_once 'common.php';
include_once 'assets_header_functions.php';



if (!$user->can_view('customers')  ) {
	header('Location: index.php');
	exit;
}

if (isset($_REQUEST['id'])) {
	$category_key=$_REQUEST['id'];


} else {
	header('Location: index.php?error_no_wrong_category_id');
	exit;
}
$category=new Category($category_key);
if (!$category->id) {
	header('Location: customer_categories.php?id=0&error=cat_not_found');
	exit;

}
if ($category->data['Category Subject']!='Customer') {
	header('Location: index.php?error_no_wrong_category_id');
	exit;
}


$modify=$user->can_edit('customers');
if (!$modify) {
	header('Location: customer_categories.php');
}



get_header_info($user,$smarty);
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');

$css_files=array(

	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
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
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	$yui_path.'animation/animation-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'js/edit_category_common.js'
);
$smarty->assign('css_files',$css_files);





$_SESSION['state']['customer_categories']['category_key']=$category_key;
$_SESSION['state']['customer_categories']['no_assigned_customers']['checked_all']=0;







$category_key=$category->id;
$category->update_no_assigned_subjects();
$view=$_SESSION['state']['customer_categories']['edit'];


if ($category->data['Category Max Deep']<=$category->data['Category Deep'] ) {
	$create_subcategory=false;
	if ( $_SESSION['state']['customer_categories']['edit']=='subcategory') {
		$view='customers';
		$_SESSION['state']['customer_categories']['edit']=$view;
	}

}else {
	$create_subcategory=true;


}



$smarty->assign('category',$category);
$smarty->assign('category_key',$category->id);

// $tpl_file='customer_category.tpl';
$store_id=$category->data['Category Store Key'];




$store=new Store($store_id);

if (!$store->id) {

	//print_r($category);

	header('Location: index.php?error=store_not_found');
	exit;

}



$order=$_SESSION['state']['customer_categories']['subcategories']['order'];
if ($order=='code') {
	$order='`Category Code`';
	$order_label=_('Code');
} else {
	$order='`Category Label`';
	$order_label=_('Label');
}
$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Category Key` as id , `Category Code` as name from `Category Dimension`  where  `Category Parent Key`=%d and `Category Root Key`=%d  and %s < %s  order by %s desc  limit 1",
	$category->data['Category Parent Key'],
	$category->data['Category Root Key'],
	$order,
	prepare_mysql($category->get($_order)),
	$order
);
//print $sql;
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']='edit_customer_category.php?id='.$row['id'];
	$prev['title']=$row['name'];
	$smarty->assign('prev',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select`Category Key` as id , `Category Code` as name from `Category Dimension`  where  `Category Parent Key`=%d  and `Category Root Key`=%d    and  %s>%s  order by %s   ",
	$category->data['Category Parent Key'],
	$category->data['Category Root Key'],
	$order,
	prepare_mysql($category->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']='edit_customer_category.php?id='.$row['id'];
	$next['title']=$row['name'];
	$smarty->assign('next',$next);
}
mysql_free_result($result);




$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);


$smarty->assign('show_history',$_SESSION['state']['customer_categories']['show_history']);


$smarty->assign('store_id',$store_id);
$js_files[]='edit_customer_category.js.php?key='.$category_key;
$smarty->assign('js_files',$js_files);
$smarty->assign('category_key',$category_key);
$smarty->assign('create_subcategory',$create_subcategory);



$smarty->assign('edit',$view);
$smarty->assign('store',$store);
$smarty->assign('subject','Customer');

$smarty->assign('parent','customers');
$smarty->assign('title', _('Customer Category').' '.$category->data['Category Code'].' ('._('Editing').')');






$tipo_filter=$_SESSION['state']['customer_categories']['subcategories']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customer_categories']['subcategories']['f_value']);

$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Category Code'),'label'=>_('Name')),
);


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['customer_categories']['no_assigned_customers']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['customer_categories']['no_assigned_customers']['f_value']);
$filter_menu=array(
	'customer name'=>array('db_key'=>'customer name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
	'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),
	'min'=>array('db_key'=>'min','menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
	'max'=>array('db_key'=>'min','menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
	'last_more'=>array('db_key'=>'last_more','menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
	'last_less'=>array('db_key'=>'last_more','menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>_('Balance less than').' '.$currency_symbol  ,'label'=>_('Balance')." <($currency_symbol)"),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>_('Balance more than').' '.$currency_symbol  ,'label'=>_('Balance')." >($currency_symbol)"),

);
$smarty->assign('filter_menu3',$filter_menu);

$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$tipo_filter=$_SESSION['state']['customer_categories']['history']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['customer_categories']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
	'upto'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
	'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))

);

$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu1',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['customer_categories']['edit_customers']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['customer_categories']['edit_customers']['f_value']);
$filter_menu=array(
	'customer name'=>array('db_key'=>'customer name','menu_label'=>_('Customer Name'),'label'=>_('Name')),

);
$smarty->assign('filter_menu2',$filter_menu);

$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$smarty->assign('filter4','customer name');
$smarty->assign('filter_value4','');
$filter_menu=array(
	'customer name'=>array('db_key'=>'customer name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
);
$smarty->assign('filter_menu4',$filter_menu);
$smarty->assign('filter_name4',$filter_menu['customer name']['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu4',$paginator_menu);

$smarty->assign('filter5','code');
$smarty->assign('filter_value5','');
$filter_menu=array(
	'code'=>array('db_key'=>'sku','menu_label'=>_('Category Code'),'label'=>_('Code')),
	'label'=>array('db_key'=>'used_in','menu_label'=>_('Category Label'),'label'=>_('Label')),
);
$smarty->assign('filter_menu5',$filter_menu);
$smarty->assign('filter_name5',$filter_menu['code']['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu5',$paginator_menu);

$elements_number=array('Changes'=>0,'Assign'=>0);
$sql=sprintf("select count(*) as num ,`Type` from  `Customer Category History Bridge` where  `Category Key`=%d group by  `Type`",$category->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=number($row['num']);
}


$smarty->assign('history_elements_number',$elements_number);
$smarty->assign('history_elements',$_SESSION['state']['customer_categories']['history']['elements']);

$smarty->display('edit_customer_category.tpl');
?>
