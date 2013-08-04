<?php
include_once 'class.Category.php';
include_once 'class.Warehouse.php';

include_once 'common.php';
include_once 'assets_header_functions.php';



if (!$user->can_view('warehouses')  ) {
	header('Location: index.php');
	exit;
}
$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
//$modify=false;
$modify=$user->can_edit('stores');

get_header_info($user,$smarty);
$general_options_list=array();


$smarty->assign('view',$_SESSION['state']['part_categories']['view']);

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
	'js/parts_common.js',
	'part_categories.js.php'
);





$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');

$smarty->assign('subcategories_view',$_SESSION['state']['part_categories']['view']);

$smarty->assign('subcategories_period',$_SESSION['state']['part_categories']['period']);
$smarty->assign('subcategories_avg',$_SESSION['state']['part_categories']['avg']);

$smarty->assign('category_period',$_SESSION['state']['part_categories']['period']);




if (isset($_REQUEST['warehouse_id']) ) {

	$warehouse=new Warehouse($_REQUEST['warehouse_id']);
	if (!$warehouse->id) {

		header('Location: index.php');
		exit;

	}

} else {

	if (count($user->warehouses)==0) {
		header('Location: index.php');
		exit;
	}else {
		header('Location: part_categories.php?warehouse_id='.$user->warehouses[0]);
		exit;


	}

}



$block_view=$_SESSION['state']['part_categories']['root_block_view'];
$smarty->assign('block_view',$block_view);



$smarty->assign('warehouse_id',$warehouse->id);
$smarty->assign('warehouse',$warehouse);




$tipo_filter=$_SESSION['state']['part_categories']['main_categories']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['part_categories']['main_categories']['f_value']);

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


$tipo_filter=$_SESSION['state']['part_categories']['no_assigned_parts']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['part_categories']['no_assigned_parts']['f_value']);
$filter_menu=array(
	'sku'=>array('db_key'=>'sku','menu_label'=>_("SKU"),'label'=>_("SKU")),

	'used_in'=>array('db_key'=>'used_in','menu_label'=>_('Used in <i>x</i>'),'label'=>_('Used in')),
	'supplied_by'=>array('db_key'=>'supplied_by','menu_label'=>_('Supplied by <i>x</i>'),'label'=>_('Supplied by')),
	'description'=>array('db_key'=>'description','menu_label'=>_('Part Description like <i>x</i>'),'label'=>_('Description')),

);
$smarty->assign('filter_menu3',$filter_menu);

$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$smarty->assign('parent','parts');
$smarty->assign('title', _('Part Categories'));

$smarty->assign('subject','Part');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

include_once 'conf/period_tags.php';
unset($period_tags['hour']);
$smarty->assign('period_tags',$period_tags);

$plot_data=array('pie'=>array('forecast'=>3,'interval'=>''));
$smarty->assign('plot_tipo','store');
$smarty->assign('plot_data',$plot_data);

$elements_number=array('Changes'=>0,'Assign'=>0);
$sql=sprintf("select count(*) as num ,`Type` from  `Part Category History Bridge` where  `Warehouse Key`=%d group by  `Type`",$warehouse->id);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=number($row['num']);
}
$smarty->assign('history_elements_number',$elements_number);
$smarty->assign('history_elements',$_SESSION['state']['part_categories']['history']['elements']);

$smarty->display('part_categories.tpl');
?>
