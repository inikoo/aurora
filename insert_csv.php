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

//total array from the csv
	require_once 'csvparser.php';
	$csv = new CSV_PARSER;

	
	$csv->load($_SESSION['file_path']);

	
	$h = $csv->getHeaders();

	
	$raw = $csv->getrawArray();
	

	$count_rows = $csv->countRows();

	
	$session_array = array_unique($_SESSION['colorArray']);

	
	$tt = array();

	foreach($session_array as $session=>$vv)
	{
		$tt[] = $vv;
	}

	
	$assign = isset($_REQUEST['assign_field'])?$_REQUEST['assign_field']:'Ignore';

	

	
	$arr = array();
	$k = 0;
	$nArray = array();
	
	
		for($i=0; $i<=$count_rows; $i++)
		{
			$k = 0;
			for($j=0; $j<count($assign); $j++)
			{
				
				if($assign[$k] != 'Ignore')
				{
					$nArray[$assign[$k]]=$raw[$i][$j];	
			
				}	
					$k++;
				
			}
				$arr[]=$nArray;  
		}
		$previous=array();
                $previous=$arr;
		foreach($tt as $key=>$value)
		{
			if(array_key_exists($value,$arr))
			{	
				unset($arr[$value]);
			}
		}

	       $ignore[]=array_diff($previous,$arr);
		
		//print_r($ignore);
			
		


$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);
$smarty->assign('arr',$arr);
$smarty->assign('tt',$tt);
$smarty->assign('values',$values);


$smarty->display('insert_csv.tpl');
unset($_SESSION['getQueryString']);

?>
