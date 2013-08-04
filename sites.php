<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once 'class.Store.php';

include_once 'assets_header_functions.php';

if (!($user->can_view('sites')    ) ) {
	header('Location: index.php');
	exit;
}


$number_sites=count($user->websites);

if ($number_sites==0) {
	header('Location: index.php');
	exit;
} else if ($number_sites==1) {

		header('Location: site.php?id='.array_pop($user->websites));
		exit;
	}



$create=$user->can_create('sites');

$modify=$user->can_edit('sites');


$smarty->assign('create',$create);
$smarty->assign('modify',$modify);




$smarty->assign('search_label',_('Websites'));
$smarty->assign('search_scope','sites');

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
	$yui_path.'dragdrop/dragdrop-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/csv_common.js',
	'js/search.js'
);


//$js_files[]='common_plot.js.php?page='.'site';

$js_files[]='sites.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('store_key',false);




if (isset($_REQUEST['view'])) {
	$valid_views=array('sites','pages');
	if (in_array($_REQUEST['view'], $valid_views))
		$_SESSION['state']['sites']['block_view']=$_REQUEST['view'];

}

$smarty->assign('block_view',$_SESSION['state']['sites']['block_view']);




if (isset($_REQUEST['pages_view'])) {
	$valid_views=array('general','hits','visitors');
	if (in_array($_REQUEST['view'], $valid_views))
		$_SESSION['state']['sites']['pages']['view']=$_REQUEST['view'];

}

$smarty->assign('pages_view',$_SESSION['state']['sites']['pages']['view']);
$smarty->assign('page_period',$_SESSION['state']['sites']['pages']['period']);








$smarty->assign('parent','websites');
$smarty->assign('title', _('Websites'));

$tipo_filter=($_SESSION['state']['sites']['sites']['f_field']);
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['sites']['sites']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Website code starting with  <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Website title like  <i>x</i>'),'label'=>_('Name')),
);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);



$tipo_filter=($_SESSION['state']['sites']['pages']['f_field']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['sites']['pages']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Page code starting with  <i>x</i>'),'label'=>_('Code')),
	'title'=>array('db_key'=>'title','menu_label'=>_('Page title like  <i>x</i>'),'label'=>_('Title')),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$table_type_options=array(
	'list'=>array('mode'=>'list','label'=>_('List')),
	'thumbnails'=>array('mode'=>'thumbnails','label'=>_('Thumbnails')),
);
$smarty->assign('pages_table_type',$_SESSION['state']['sites']['pages']['table_type']);
$smarty->assign('pages_table_type_label',$table_type_options[$_SESSION['state']['sites']['pages']['table_type']]['label']);
$smarty->assign('pages_table_type_menu',$table_type_options);

$elements_number=array('FamilyCatalogue'=>0,'DepartmentCatalogue'=>0,'ProductDescription'=>0,'Other'=>0);

$sql=sprintf("select count(*) as num,`Page Store Section` from  `Page Store Dimension` where `Page Site Key` in (%s) group by `Page Store Section`",
	join(',',$user->websites)
);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$_key=preg_replace('/ /','',$row['Page Store Section']);

	if (in_array($_key,array('FamilyCatalogue','DepartmentCatalogue','ProductDescription')))
		$elements_number[$_key]=$row['num'];
	else {
		$elements_number['Other']+=$row['num'];
	}


}
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['sites']['pages']['elements']);

$smarty->display('sites.tpl');

?>
