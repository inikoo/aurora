<?php
/*
 File: users.php

 UI user managment page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.Site.php';

include_once 'common.php';
if (!$user->can_view('users'))
	exit();


if (isset($_REQUEST['site_key'])) {
	$site=new Site($_REQUEST['site_key']);
	if (!$site->id) {
		header('Location: users.php');
		exit;
	}

}else {
	header('Location: users.php');
	exit;
}


$smarty->assign('site',$site);

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
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'button/button-min.js',
	$yui_path.'menu/menu-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'users_site.js.php',

);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$modify=$user->can_edit('sites');
$smarty->assign('modify',$modify);



$general_options_list=array();
if ($user->can_edit('users')) {
	$general_options_list[]=array('tipo'=>'url','url'=>'edit_users_customer.php','label'=>_('Edit Customer'));

}

$smarty->assign('general_options_list',$general_options_list);



$smarty->assign('search_label',_('Users'));
$smarty->assign('search_scope','users');






$sql="select (select count(*) from `User Group Dimension`) as number_groups ,( select count(*) from `User Dimension`) as number_users ";
$result = mysql_query($sql);
if (!$user=mysql_fetch_array($result, MYSQL_ASSOC))
	exit;
mysql_free_result($result);
$smarty->assign('box_layout','yui-t4');



$smarty->assign('parent','users');
$smarty->assign('title', _('Users'));


$sql="select `Language Code` as  id from `Language Dimension`";
$newuser_langs=array();
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$newuser_langs[$row['id']]=$_lang[$row['id']];
}
mysql_free_result($result);
$smarty->assign('newuser_langs',$newuser_langs);

$sql="select `User Group Key` as id from `User Group Dimension`";
$newuser_groups=array();
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$newuser_groups[$row['id']]=$_group[$row['id']];
}
mysql_free_result($res);
$smarty->assign('newuser_groups',$newuser_groups);

/* //create user list */
/* $sql=sprintf("select `Staff ID` as id,`Staff Alias` as alias,(select count(*) from liveuser_users where tipo=1 and id_in_table=`Staff Dimension`.`Staff Key`) as is_user from `Staff Dimension` where `Staff Currently Working`='Yes' and `Staff Most Recent`='Yes' order by `Staff Alias`"); */
/* $result=mysql_query($sql); */
/* $num_cols=5; */
/* $staff=array(); */
/* print $sql; */
/* while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){ */
/*   $staff[]=array('alias'=>$row['alias'],'id'=>$row['id'],'is_user'=>$row['is_user']); */
/*  } */
/* foreach($staff as $key=>$_staff){ */
/*   $staff[$key]['mod']=fmod($key,$num_cols); */
/* } */
/* $smarty->assign('staff',$staff); */
/* $smarty->assign('staff_cols',$num_cols); */



/* $sql=sprintf("select `Supplier Key` as id,`Supplier Code` as alias,(select count(*) from liveuser_users where tipo=2 and id_in_table=`Supplier Dimension`.`Supplier Key`) as is_user from `Supplier Dimension`  order by `Supplier Code`"); */
/* $result=mysql_query($sql); */
/* $num_cols=4; */
/* $supplier=array(); */
/* while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){ */
/*   $supplier[]=array('alias'=>$row['alias'],'id'=>$row['id'],'is_user'=>$row['is_user']); */
/*  } */
/*  mysql_free_result($result); */
/* foreach($supplier as $key=>$_supplier){ */
/*   $supplier[$key]['mod']=fmod($key,$num_cols); */
/* } */
/* $smarty->assign('suppliers',$supplier); */
/* $smarty->assign('supplier_cols',$num_cols); */



$tipo_filter=$_SESSION['state']['users']['site']['f_field'];

$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['users']['site']['f_value']);
$filter_menu=array(
	// 'alias'=>array('db_key'=>'alias','menu_label'=>'Alias like  <i>x</i>','label'=>'Alias'),
	'handle'=>array('db_key'=>'handle','menu_label'=>_('Handle like <i>x</i>'),'label'=>_('Handle')),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->display('users_site.tpl');
?>
