<?php
/*
 File: users.php 

 UI user managment page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('common.php');
if(!$user->can_view('users'))
  exit();
  
  
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
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'button/button-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'users_staff.js.php',
		
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('options_box_width','200px');

$modify=$user->can_edit('users');
$smarty->assign('modify',$modify);




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


$sql="select `Language Code` as  id from `Language Dimension`";
$newuser_langs=array();
$result=mysql_query($sql);
 while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $newuser_langs[$row['id']]=$_lang[$row['id']];
 }
 mysql_free_result($result);
$smarty->assign('newuser_langs',$newuser_langs);

$sql="select `User Group Key` as id from `User Group Dimension`";
$newuser_groups=array();
$res=mysql_query($sql);
while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
  $newuser_groups[$row['id']]=$_group[$row['id']];
 }
 mysql_free_result($res);
$smarty->assign('newuser_groups',$newuser_groups);





$block_view=$_SESSION['state']['users']['staff']['block_view'];
$smarty->assign('block_view',$block_view);
$smarty->assign('users_view',$_SESSION['state']['users']['staff']['view']);


$tipo_filter=$_SESSION['state']['users']['staff']['f_field'];

$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['users']['staff']['f_value']);
$filter_menu=array(
		   'alias'=>array('db_key'=>'alias','menu_label'=>'Alias like  <i>x</i>','label'=>'Alias'),
		   'name'=>array('db_key'=>'name','menu_label'=>'Name Like <i>x</i>','label'=>'Name'),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$elements_number=array('ActiveWorking'=>0,'ActiveNotWorking'=>0,'InactiveWorking'=>0,'InactiveNotWorking'=>0,'NonUsers'=>0);
$sql=sprintf("select count(*) as num,`User Staff Type` from  `User Dimension` where `User Type`='Staff' group by `User Staff Type`");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[str_replace(' ','',$row['User Staff Type'])]=$row['num'];
}


$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['users']['staff']['elements']);



$smarty->display('users_staff.tpl');
?>
