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
include_once('Theme.php');
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skUser Themesins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		
		 'button.css',
		 'container.css'
		 );




if($themeRow)
{
array_push($css_files, 'themes_css/'.$ThemeCommon);   
array_push($css_files, 'themes_css/'.$ThemeTable);
array_push($css_files, 'themes_css/'.$ThemeIndex); 
array_push($css_files, 'themes_css/'.$ThemeDropdown);
array_push($css_files, 'themes_css/'.$ThemeCampaign);
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
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'js/change_style.js'
		);


$smarty->assign('parent','users');


$title=_('Change Style');


$smarty->assign('title', $title);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('change_style.tpl');     
?>
