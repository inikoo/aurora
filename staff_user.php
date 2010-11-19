<?php

/*
 File: user.php 

 UI index page

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
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 
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
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'staff_user.js.php',
		
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$general_options_list=array();
if($user->can_edit('users')){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_users_staff.php','label'=>_('Edit Users'));
  
}

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

$tipo_filter=$_SESSION['state']['users']['staff']['f_field'];

$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['users']['staff']['f_value']);
$filter_menu=array(
		   'alias'=>array('db_key'=>'alias','menu_label'=>'Alias like  <i>x</i>','label'=>'Alias'),
		  // 'name'=>array('db_key'=>'name','menu_label'=>'Name Like <i>x</i>','label'=>'Name'),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->display('staff_user.tpl');



/*include_once('common.php');
include_once('class.User.php');

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css'
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
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		
		);


$smarty->assign('parent','users');


$smarty->assign('user_class',$user);

switch ($user->data['User Type']) {
    case 'Administrator':
    $title=_('Administrative User');
       $tpl='user_administrator.tpl';
       $js_files[]='user_administrator.js.php';
        break;
   case 'Staff':
   $title=_('Staff User');
       $tpl='staff_user.tpl';
       $js_files[]='staff_user.js.php';
        break;
    case 'Customer':
       $title=_('Customer User');
       $tpl='customer_user.tpl';
       $js_files[]='customer_user.js.php';
        break;
        case 'Supplier':
           $title=_('Supplier User');
       $tpl='supplier_user.tpl';
       $js_files[]='supplier_user.js.php';
        break;    
}

$smarty->assign('title', $title);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
       $smarty->display($tpl);
*/

?>
