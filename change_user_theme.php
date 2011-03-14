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
//include_once('class.User.php');

	


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skUser Themesins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		
		 'button.css',
		 'container.css'
		 );

//for changing the theme by the user
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
		$yui_path.'connection/connection-min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'js/change_user_theme.js'
		);



$smarty->assign('parent','users');


$title=_('Change User Theme');


$smarty->assign('title', $title);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('change_user_theme.tpl');     
?>
