<?php
/*
 File: location.php

 UI location page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once 'class.Staff.php';


if (!$user->can_view('staff') or !$user->can_edit('staff')  ) {
	header('Location: index.php');
	exit;
}



if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])) {
	$staff_id=$_REQUEST['id'];
}else {
	exit("no staff id");

}

$staff= new Staff($staff_id);

$smarty->assign('edit',$_SESSION['state']['staff']['edit']);
$smarty->assign('edit_description_block',$_SESSION['state']['staff']['edit_description_block']);


$smarty->assign('staff_id',$staff_id);

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'staff.php?id='.$staff->id,'label'=>_('Exit Edit'));
$smarty->assign('general_options_list',$general_options_list);

$smarty->assign('search_label',_('Staff'));
$smarty->assign('search_scope','staff');

$staff_position=array();

$sql=sprintf("select `Company Position Key`,`Company Position Title`,(select count(*) from `Company Position Staff Bridge` where `Position Key`=`Company Position Key` and `Staff Key`=%d) as Selected  from `Company Position Dimension` order by `Company Position Title`", $staff->id);
$result=mysql_query($sql);
while ($row=mysql_fetch_assoc($result)) {
	$staff_position[$row['Company Position Key']]=array('label'=>$row['Company Position Title'],'selected'=>$row['Selected']);
}
$smarty->assign('staff_position',$staff_position);


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
	$yui_path.'datatable/datatable.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'js/dropdown.js',
	'js/edit_common.js',
	'js/edit_staff.js'
);

//print_r($location);


$tipo_filter0=$_SESSION['state']['location']['stock_history']['f_field'];
$filter_menu0=array(
	'note'=>array('db_key'=>_('note'),'menu_label'=>'Part SKU','label'=>_('Note')),
	'author'=>array('db_key'=>_('author'),'menu_label'=>'Used in','label'=>_('Author')),
);
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0','');

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$tipo_filter2='code';
$filter_menu2=array(
	'code'=>array('db_key'=>_('code'),'menu_label'=>_('Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>_('name'),'menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2','');



//$staff->load('product');
$smarty->assign('parent','staff');
$smarty->assign('title',_('Editing Staff ').$staff->data['Staff Name']);
$smarty->assign('staff',$staff);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('edit_staff.tpl');
?>
