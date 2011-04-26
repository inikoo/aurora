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

ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skUser Themesins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		'themes_css/ui-lightness/jquery-ui-1.8.10.custom.css',
		 'button.css',
		 'container.css'
		 );


include_once('Theme.php');



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
		$yui_path.'yahoo/yahoo-min.js',
		$yui_path.'event/event-min.js',
		$yui_path.'connection/connection_core-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'js/change_style.js',
		'js/jquery-1.4.4.min.js',
		'js/jquery-ui-1.8.10.custom.min.js'
		);



if(isset($_FILES['image']["name"])!='')
{

$current_image=$_FILES['image']['name'];
//$new_image = $id.".png";

$new_image =$user_key.".png";
$destination="uploads/".$new_image;
$action = copy($_FILES['image']['tmp_name'], $destination);
if (!$action) 
{
die('File copy failed');
}else{
	

     $sql="update `User Dimension` set `User Theme Background Status`='1' where `User Key`='$user_key'";
            mysql_query($sql);

}

}








$smarty->assign('parent','users');


$title=_('Change Style');


$smarty->assign('title', $title);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('change_style.tpl');     
?>
