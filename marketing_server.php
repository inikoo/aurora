<?php
/*
 File: customers.php

 UI customers page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';

if (!$user->can_view('customers')) {
	exit();
}


	
$css_files=array(

	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
		$yui_path.'button/assets/skins/sam/button.css',

	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/button.css',
	'css/container.css',
	'css/table.css',
	'theme.css.php'


);




$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'js/deals_common.js',
	'js/marketing_server.js'
);



$smarty->assign('parent','marketing');
$smarty->assign('title', _('Marketing'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Marketing'));

$smarty->assign('search_scope','marketing');
$smarty->assign('search_label',_('Search'));



$smarty->assign('store_id','');
$smarty->assign('block_view',$_SESSION['state']['stores']['marketing_block_view']);



$tipo_filter=$_SESSION['state']['stores']['marketing']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['stores']['marketing']['f_value']);

$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Store Code'),'label'=>_('Code')),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['stores']['offers']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['stores']['offers']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Offers with name like *<i>x</i>*'),'label'=>_('Name')),
	'code'=>array('db_key'=>'code','menu_label'=>_('Offers with code like x</i>*'),'label'=>_('Code')),
);
$smarty->assign('filter_menu1',$filter_menu);

$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['stores']['campaigns']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['stores']['campaigns']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Campaign with name like *<i>x</i>*'),'label'=>_('Name')),
	'code'=>array('db_key'=>'code','menu_label'=>_('Campaign with code like x</i>*'),'label'=>_('Code')),
);
$smarty->assign('filter_menu2',$filter_menu);

$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$session_data=base64_encode(json_encode(array(
			'label'=>array(


				'Code'=>_('Code'),
				'Store_Name'=>_('Store Name'),
				'Marketing_Emails'=>_('Marketing Emails'),
				'Newsletters'=>_('Newsletters'),
				'Reminders'=>_('Reminders'),
				'Campaigns'=>_('Campaigns'),
				'Offers'=>_('Offers'),
				'Store'=>_('Store'),
				'Description'=>_('Description'),
				'Duration'=>_('Duration'),
				'Orders'=>_('Orders'),
				'Customers'=>_('Customers'),
				'Name'=>_('Name'),

				'Duration'=>_('Duration'),
				'Page'=>_('Page'),
				'of'=>_('of')
			),
			'state'=>array(
				'marketing'=>$_SESSION['state']['stores']['marketing'],
				'offers'=>$_SESSION['state']['stores']['offers'],
				'campaigns'=>$_SESSION['state']['stores']['campaigns']
			)
		)));
$smarty->assign('session_data',$session_data);



$smarty->display('marketing_server.tpl');

?>
