<?php
/*
 File: index.php 

 UI index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

include_once('common.php');


$site_address='kactus-isystems.com';
$cancel_email='contact@kactus-isystems.com';

$smarty->assign('site_address',$site_address);
$smarty->assign('cancel_email',$cancel_email);



$css_files=array(
              $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
            
		 'css/terms_use.css',
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
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'terms_use.js.php'
		);


$smarty->assign('parent','home');
$smarty->assign('title', _('Terms & Conditions'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->display('terms_use.tpl');





?>

