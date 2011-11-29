<?php
/*
 File: insert_csv.php

 UI store page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
/*ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);*/
include_once('common.php');
$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'editor/assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',

               
               'common.css',
               'button.css',
               'container.css',
               'table.css',
               'theme.css.php'
               
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'uploader/uploader-debug.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/table_common.js',
		'js/dropdown.js',
		'js/insert_csv.js'
        	);
        	
        	
        	


$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);






$smarty->display('insert_csv.tpl');
unset($_SESSION['getQueryString']);


?>
