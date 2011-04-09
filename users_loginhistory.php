<?php
/*
 File: users.php 

 UI user managment page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
if(!$user->can_view('users'))
  exit();		 
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 
		 'container.css',
		 'button.css'
		 );
$theme="";
if($theme)
{
array_push($css_files, 'themes_css/'.$Themecss1);   
array_push($css_files, 'themes_css/'.$Themecss2);
array_push($css_files, 'themes_css/'.$Themecss3);
}    
   

else{
array_push($css_files, 'common.css'); 
array_push($css_files, 'css/dropdown.css'); 
array_push($css_files, 'css/index.css');
array_push($css_files, 'table.css');
}



		 		 
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
		'users_loginhistory.js.php',	
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$general_options_list=array();
$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Users'));
$smarty->assign('search_scope','users');
$sql="select (select count(*) from `User Group Dimension`) as number_groups ,( select count(*) from `User Dimension`) as number_users ";
$result = mysql_query($sql);
if(!$user=mysql_fetch_array($result, MYSQL_ASSOC))
  exit;
mysql_free_result($result);
$smarty->assign('box_layout','yui-t4');
$smarty->assign('parent','users');
$smarty->assign('title', _('Users'));
$tipo_filter=$_SESSION['state']['users']['loginhistory']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['users']['loginhistory']['f_value']);
$filter_menu=array(
		   'user'=>array('db_key'=>'user','menu_label'=>'User Handle like  <i>x</i>','label'=>'User'),
		   'ip'=>array('db_key'=>'ip','menu_label'=>'IP Address like <i>x</i>','label'=>'IP Address'),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->display('users_loginhistory.tpl');
?>
