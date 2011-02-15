<?php
/*
 File: store.php 

 UI store page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');



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
		'common.js.php',
		'table_common.js.php',
		'js/dropdown.js',
        	);

	

	//value of the assigned field
	$assign = isset($_REQUEST['assign_field'])?$_REQUEST['assign_field']:'0';


	//value of the right column
	$values = isset($_REQUEST['values'])?$_REQUEST['values']:'';

	
	//removed list of array
	$hidden_array = isset($_REQUEST['hidden_array'])?$_REQUEST['hidden_array']:'0';

	

	//code to generate the final array		
	for($i = 0; $i < count($assign);  $i++) 
	{

		//restrict whether any ignore field is there 
		if($assign[$i] != '0')
		{

		 	$rows[$assign[$i]] = $values[$i];

		}		
		
	}

	
	require_once 'csvparser.php';
	$csv = new CSV_PARSER;
	//loading the CSV File
	$csv->load($_SESSION['file_path']);
	//extracting the HEADERS
	$h = $csv->getHeaders();
	$count_rows = $csv->countRows();
	
	//count the removed array
	
		$numIndex = count($hidden_array);	
	
	
		$new_arr = array();

		for($y=0; $y<$count_rows; $y++)
		{
			$total_row = $csv->getRow($y);
			array_push($new_arr, $total_row);

		}
	

		if($hidden_array != '0')
		{
			foreach($hidden_array as $value)
			{


				unset($new_arr[$value - 1]);

			}
		}

		
	

	print_r($new_arr);

$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);
$smarty->assign('assign',$assign);
$smarty->assign('values',$values);


$smarty->display('insert_csv.tpl');
	unset($_SESSION['getQueryString']);

?>
