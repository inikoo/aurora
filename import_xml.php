<?php
/*
 File: store.php 

 UI store page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');
include_once('assets_header_functions.php');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',

		 //	 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css',
		 'css/import_data.css'
		 );
$css_files[]='theme.css.php';
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
        'import_data.js.php'    
		);


if(!isset($_REQUEST['tipo'])){
exit("to do a page where the user can choose the correct options");
}

$scope=$_REQUEST['tipo'];

include_once('xml2array.php');

switch($scope){
case('customers_store'):
$scope_args=$_SESSION['state']['customers']['store'];

// $xml=file_get_contents('conf/import_file_customers.xml');
//$fields=xml2array($xml);
if(isset($_GET['id']))
{
  $smarty->assign('wrong',"Invalid File");
}

break;
default:
$scope_args='';
}

$smarty->assign('scope',$scope);
$smarty->assign('scope_args',$scope_args);
$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




 
  $smarty->display('import_xml.tpl');

?>
