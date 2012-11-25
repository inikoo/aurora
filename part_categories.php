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
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'external_libs/ammap/ammap/swfobject.js',
	'js/parts_common.js',
	'js/edit_category_common.js'

);





$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');

$smarty->assign('subcategories_view',$_SESSION['state']['part_categories']['view']);

$smarty->assign('subcategories_period',$_SESSION['state']['part_categories']['period']);
$smarty->assign('subcategories_avg',$_SESSION['state']['part_categories']['avg']);

$smarty->assign('category_period',$_SESSION['state']['part_categories']['period']);




if (isset($_REQUEST['id'])) {
	$category_key=$_REQUEST['id'];
} else {
	$category_key=$_SESSION['state']['part_categories']['category_key'];
}

if (!$category_key) {


	if (isset($_REQUEST['warehouse_id'])  and is_numeric($_REQUEST['warehouse_id']) ) {

		$warehouse=new Warehouse($_REQUEST['warehouse_id']);
		if (!$warehouse->id) {

			header('Location: index.php');
			exit;

		}

	} else {
		header('Location: index.php');
		exit;
	}



	$block_view=$_SESSION['state']['part_categories']['base_block_view'];
	$smarty->assign('block_view',$block_view);
	$js_files[]='part_categories_base.js.php';
	$tpl_file='part_categories_base.tpl';

} else {



	$category=new Category($category_key);
	if (!$category->id) {
		header('Location: part_categories.php?id=0&error=cat_not_found');
		exit;

	}
	
	
	
	

	$category_key=  $category->id;
	$warehouse=new Warehouse($category->data['Category Warehouse Key']);


	$smarty->assign('category',$category);

	if (isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('subcategories','subjects','subcategories_charts','history'))) {
		$_SESSION['state']['part_categories']['block_view']=$_REQUEST['block_view'];
	}

	$block_view=$_SESSION['state']['part_categories']['block_view'];
	
	if($block_view=='subcategories' and $category->get('Category Children')==0){
		$block_view='subjects';
	}
	
	if($block_view=='subjects' and $category->get('Category Number Subjects')==0  ){
		$block_view='subcategories';
	}
	
	
	$smarty->assign('block_view',$block_view);


	$tipo_filter=$_SESSION['state']['warehouse']['parts']['f_field'];
	$smarty->assign('filter0',$tipo_filter);
	$smarty->assign('filter_value0',$_SESSION['state']['warehouse']['parts']['f_value']);
	$filter_menu=array(
		'used_in'=>array('db_key'=>'used_in','menu_label'=>_('Used in <i>x</i>'),'label'=>_('Used in')),
		'supplied_by'=>array('db_key'=>'supplied_by','menu_label'=>_('Supplied by <i>x</i>'),'label'=>_('Supplied by')),
		'description'=>array('db_key'=>'description','menu_label'=>_('Part Description like <i>x</i>'),'label'=>_('Description')),

	);
	$smarty->assign('filter_menu0',$filter_menu);

	$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

	$paginator_menu=array(10,25,50,100,500);
	$smarty->assign('paginator_menu0',$paginator_menu);
	$smarty->assign('view',$_SESSION['state']['warehouse']['parts_view']);
	$smarty->assign('parts_view',$_SESSION['state']['warehouse']['parts']['view']);
	$smarty->assign('parts_period',$_SESSION['state']['warehouse']['parts']['period']);
	$smarty->assign('parts_avg',$_SESSION['state']['warehouse']['parts']['avg']);


	$elements_number=array('Keeping'=>0,'LastStock'=>0,'Discontinued'=>0,'NotKeeping'=>0);

	$sql=sprintf("select count(*) as num ,`Part Main State` from  `Category Bridge` left join  `Part Dimension` P on (`Subject Key`=`Part SKU`)  left join `Part Warehouse Bridge` B  on (P.`Part SKU`=B.`Part SKU`)  where `Warehouse Key`=%d  and `Subject`='Part' and  `Category Key`=%d group by  `Part Main State`   ",
		$warehouse->id,
		$category->id
	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_number[$row['Part Main State']]=$row['num'];
	}
	$smarty->assign('elements_number',$elements_number);
	$smarty->assign('elements',$_SESSION['state']['warehouse']['parts']['elements']);

	$js_files[]='part_categories.js.php';
	$tpl_file='part_category.tpl';





}
$smarty->assign('warehouse_id',$warehouse->id);
$smarty->assign('warehouse',$warehouse);


$_SESSION['state']['part_categories']['category_key']=$category_key;


$tipo_filter=$_SESSION['state']['part_categories']['subcategories']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['part_categories']['subcategories']['f_value']);

$filter_menu=array(
	'name'=>array('db_key'=>_('name'),'menu_label'=>_('Category Code'),'label'=>_('Name')),
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
	'uptu'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)')),
	'abstract'=>array('db_key'=>'abstract','menu_label'=>_('Records with abstract'),'label'=>_('Abstract'))

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
$smarty->assign('category_key',$category_key);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

include_once('conf/period_tags.php');
unset($period_tags['hour']);
$smarty->assign('period_tags',$period_tags);

$plot_data=array('pie'=>array('forecast'=>3,'interval'=>''));
$smarty->assign('plot_tipo','store');
$smarty->assign('plot_data',$plot_data);

$smarty->display($tpl_file);
?>
