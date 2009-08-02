<?php
/*
 File: new_contact.php 

 UI new contact page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Contact.php');
if(!$LU->checkRight(CUST_VIEW))
  exit;

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'assets/skins/sam/container.css',
		 $yui_path.'assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/skin.css',
		 'external_libs/inputex/css/inputEx.css',		  
		 'common.css'
		
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/search.js',
		'external_libs/inputex/build/inputex-min.js',
		'js/new_contact.js.php'
		);


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','contacts.php');
$smarty->assign('title','New Contact');
$smarty->display('new_contact.tpl');


?>