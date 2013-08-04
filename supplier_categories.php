<?php
include_once 'class.Category.php';

include_once 'common.php';



if (!$user->can_view('suppliers')  ) {
	header('Location: index.php');
	exit;
}
$view_sales=$user->can_view('supplier sales');
$smarty->assign('view_suppliers',$user->can_view('suppliers'));
$smarty->assign('view_sales',$view_sales);
//$modify=false;
$modify=$user->can_edit('supplier categories');



$smarty->assign('view',$_SESSION['state']['supplier_categories']['view']);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
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
	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'external_libs/ammap/ammap/swfobject.js',
	'js/suppliers_common.js',
	'supplier_categories.js.php'
);





$smarty->assign('search_label',_('Suppliers'));
$smarty->assign('search_scope','suppliers');

$smarty->assign('subcategories_view',$_SESSION['state']['supplier_categories']['view']);

$smarty->assign('subcategories_period',$_SESSION['state']['supplier_categories']['period']);
$smarty->assign('subcategories_avg',$_SESSION['state']['supplier_categories']['avg']);

$smarty->assign('category_period',$_SESSION['state']['supplier_categories']['period']);







$block_view=$_SESSION['state']['supplier_categories']['root_block_view'];
$smarty->assign('block_view',$block_view);




$tipo_filter=$_SESSION['state']['supplier_categories']['main_categories']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['supplier_categories']['main_categories']['f_value']);

$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Category Code'),'label'=>_('Code')),
	'label'=>array('db_key'=>'label','menu_label'=>_('Category Label'),'label'=>_('Label')),

);


$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['store']['history']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['site']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>_('Done by <i>x</i>*'),'label'=>_('Notes')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)')),

);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu2',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$tipo_filter=$_SESSION['state']['supplier_categories']['no_assigned_suppliers']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['supplier_categories']['no_assigned_suppliers']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_("Name"),'label'=>_("Name")),
	'code'=>array('db_key'=>'code','menu_label'=>_("Code"),'label'=>_("Code")),


);
$smarty->assign('filter_menu3',$filter_menu);

$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$smarty->assign('parent','suppliers');
$smarty->assign('title', _('Supplier Categories'));

$smarty->assign('subject','Supplier');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

include_once 'conf/period_tags.php';
unset($period_tags['hour']);
$smarty->assign('period_tags',$period_tags);

$plot_data=array('pie'=>array('forecast'=>3,'interval'=>''));
$smarty->assign('plot_tipo','store');
$smarty->assign('plot_data',$plot_data);

$elements_number=array('Changes'=>0,'Assign'=>0);
$sql=sprintf("select count(*) as num ,`Type` from  `Supplier Category History Bridge`  group by  `Type`");
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=number($row['num']);
}
$smarty->assign('history_elements_number',$elements_number);
$smarty->assign('history_elements',$_SESSION['state']['supplier_categories']['history']['elements']);
$smarty->assign('supplier_id',0);

$smarty->display('supplier_categories.tpl');
?>
