<?php
/*
 File: Staff.php 

 This file contains the Staff Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2012, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
if(!$user->can_view('staff')){
   header('Location: index.php');
   exit;
}

if( !$user->can_edit('staff')){
 header('Location: hr.php');
   exit;
}

$sql="select `Account Company Key` from `Account Dimension` ";
$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){
   $company_key=$row['Account Company Key'];
}else{
       header('Location: new_corporation.php');
       exit;

}

mysql_free_result($res);
$general_options_list=array();


  $general_options_list[]=array('tipo'=>'url','url'=>'hr.php','label'=>_('Exit Edit'));


$smarty->assign('general_options_list',$general_options_list);





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
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/edit_common.js',
		'edit_staff.js.php?company_key='.$company_key
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','staff');
$smarty->assign('sub_parent','staff');

$smarty->assign('title', _('Company Staff'));

$tipo_filter=$_SESSION['state']['hr']['staff']['f_field'];

$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['hr']['staff']['f_value']);

$smarty->assign('view',$_SESSION['state']['hr']['view']);

$filter_menu=array(
		   'name'=>array('db_key'=>'staff.alias','menu_label'=>'staff name <i>*x*</i>','label'=>'Name'),
		   'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
		   'staff_id'=>array('db_key'=>'staff_id','menu_label'=>'Staff Id','label'=>'Staff Id'),
		   );
$smarty->assign('filter_menu0',$filter_menu);

$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->assign('edit','staff');


$smarty->display('edit_hr.tpl');
?>
