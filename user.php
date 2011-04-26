<?php
/*
 File: user.php 

 UI index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.User.php');



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 //$yui_path.'datatable/assets/skins/sam/datatable.css',
		 //$yui_path.'container/assets/skins/sam/container.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		
		 'css/edit.css'
		 );


if($common)
{
array_push($css_files, 'themes_css/'.$common);   
array_push($css_files, 'themes_css/'.$row['Themes css4']);
array_push($css_files, 'themes_css/'.$row['Themes css2']); 
array_push($css_files, 'themes_css/'.$row['Themes css3']);
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
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'js/change_password.js',    
                 'js/edit_common.js',	
		'sha256.js.php',
		'passwordmeter.js.php',
                 'edit_user_password.js.php'
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
if(isset($_SESSION['user_key']))
{
  $sql="select `User Key`,`User Alias` from `User Dimension` where `User Key`='".$_SESSION['user_key']."'";
  $result=mysql_query($sql);
  $row=mysql_fetch_assoc($result);
  $smarty->assign('user_name',$row['User Alias']);
  $smarty->assign('user_id',$row['User Key']);

}
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


?>
