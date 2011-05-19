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
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 // $yui_path.'assets/skins/sam/autocomplete.css',
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
		'js/common.js',
		'js/table_common.js',
		'js/dropdown.js',
        	);

	//total array from the csv
	require_once 'csvparser.php';
	$csv = new CSV_PARSER;
	$csv->load($_SESSION['file_path']);
	$h = $csv->getHeaders();
	$raw = $csv->getrawArray();
	$count_rows = $csv->countRows();
	
	
	print_r($_SESSION['records_ignored_by_user']);
	$session_array = array_unique($_SESSION['records_ignored_by_user']);
	$records_ignored_by_user = array();
	foreach($session_array as $session=>$vv)
	{
		if($session != '')
		{
			$records_ignored_by_user[] = $vv;
		}
	}
	print_r($records_ignored_by_user);
	$csv_data_map = isset($_REQUEST['assign_field'])?$_REQUEST['assign_field']:'Ignore';
	
	$csv_parsed_data = array();
	$k = 0;
	$nArray = array();
	for($i=0; $i<=$count_rows; $i++)
	{
	  $k = 0;
	  for($j=0; $j<count($csv_data_map); $j++)
	  {
		if($csv_data_map[$k] != 'Ignore')
		{
			$nArray[$csv_data_map[$k]]=$raw[$i][$j];
		}
			$k++;
			}
			$csv_parsed_data[]=$nArray;
		}
		//print_r($csv_parsed_data);
		$previous=array();
                $previous=$csv_parsed_data;
		foreach($records_ignored_by_user as $key=>$value)
		{
			if(array_key_exists($value,$csv_parsed_data))
			{
				unset($csv_parsed_data[$value]);
			}
		}
	       $ignore[]=array_diff($previous,$csv_parsed_data);
		
		//print_r($csv_parsed_data);


// Importing to database //
/*

if(!isset($_REQUEST['subject'])){
exit("to do a page where the user can choose the correct options");
}
if(!isset($_REQUEST['subject_key'])){
	if($_REQUEST['subject']!='staff' && $_REQUEST['subject']!='positions' && $_REQUEST['subject']!='areas' && $_REQUEST['subject']!='departments')
exit("to do a page where the user can choose the correct options");
}
$scope=$_REQUEST['subject'];
$scope_args=$_REQUEST['subject_key'];
switch($scope){
	case('customers_store'):
	$tbl = "Customer Dimension";
	$fld = "Customer Store Key";
	$pk = "Customer Key";
	break;

	case('supplier_products'):
	$tbl = "Supplier Product Dimension";
	$fld = "Supplier Key";
	$pk = "Supplier Product Key";
	break;

	case('staff'):
	$tbl="Staff Dimension";
	$fld = "";
	$pk = "Staff Key";
	break;

	case('positions'):
	$tbl="Company Position Dimension";
	$fld = "";
	$pk = "Company Position Key";
	break;

	case('areas'):
	$tbl="Company Area Dimension";
	$fld = "";
	$pk = "Company Area Key";
	break;

	case('departments'):
	$tbl="Company Department Dimension";
	$fld = "";
	$pk = "Company Department Key";
	break;

	default:
}


for($x=1; $x<count($csv_parsed_data); $x++){
	$data=$csv_parsed_data[$x];
	insert($data, $tbl, $fld, $scope_args);
}*/ //Put off this comments to insert data in database //

$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);



exit;

$smarty->display('insert_csv.tpl');
unset($_SESSION['getQueryString']);


?>
