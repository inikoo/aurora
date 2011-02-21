<?php
include_once('common.php');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 'common.css',
		 'container.css',
		 'table.css'
		 );


$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'animation/animation-min.js',

		$yui_path.'datasource/datasource.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/phpjs.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		 'new_subject.js.php'
	
		);
$store_key=1;
$js_files[]='company.js.php';
$js_files[]='js/validate_telecom.js';
$js_files[]='new_company.js.php?scope=customer&store_key='.$store_key;
$js_files[]='edit_address.js.php';
$js_files[]='edit_contact_from_parent.js.php';
$js_files[]='edit_contact_telecom.js.php';
$js_files[]='edit_contact_name.js.php';
$js_files[]='edit_contact_email.js.php';

$smarty->assign('parent','customers');
$smarty->assign('title', _('Advanced Search, Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->display('search_customers.tpl');
?>