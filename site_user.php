<?php

/*
 File: user.php

 UI index page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once 'class.User.php';
include_once 'class.Site.php';
include_once 'class.Customer.php';


if (isset($_REQUEST['id'])) {
	$site_user=new User($_REQUEST['id']);
	if (!$site_user->id) {
		header('Location: users.php');
		exit;
	}
	
	if(!$site_user->data['User Type']=='Customer'){
		header('Location: users.php');
		exit;
	}
	
	$site=new Site($site_user->data['User Site Key']);
	
}else {
	header('Location: users.php');
	exit;
}

$store=new Store($site->data['Site Store Key']);

$customer=new Customer($site_user->data['User Parent Key']);
$smarty->assign('customer',$customer);
$smarty->assign('store',$store);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/edit.css',
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
	'js/table_common.js',
	'js/search.js',
	'js/sha256.js',
		'js/change_password.js',

	'site_user.js.php'
);


$modify=$user->can_edit('sites');
$smarty->assign('modify',$modify);

$block_view=$_SESSION['state']['site_user']['block_view'];
$smarty->assign('block_view',$block_view);
//$smarty->assign('user_key',$_REQUEST['id']);
$general_options_list[]=array('class'=>'edit','tipo'=>'js','id'=>'forgot_password','label'=>_('Forgot Password'));
$smarty->assign('search_scope','users');
$smarty->assign('search_label',_('Search'));
$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('parent','users');

$title=_('Customer User');

$smarty->assign('site_user',$site_user);
$smarty->assign('site',$site);
//print_r($site);
$smarty->assign('title', $title);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$tipo_filter=$_SESSION['state']['site_user']['login_history']['f_field'];

$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['site_user']['login_history']['f_value']);
$filter_menu=array(
	// 'alias'=>array('db_key'=>'alias','menu_label'=>'Alias like  <i>x</i>','label'=>'Alias'),
	'ip'=>array('db_key'=>'ip','menu_label'=>_('IP address ike <i>x</i>'),'label'=>'IP'),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('site_user.tpl');


?>
