<?php
/*
 UI import_csv_verify.php page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
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
		'js/import_csv_data.js',
		'js/dropdown.js'
        	);

if(!isset($_REQUEST['subject'])){
exit("to do a page where the user can choose the correct options");
}
if(!isset($_REQUEST['subject_key'])){
	if($_REQUEST['subject']!='staff' && $_REQUEST['subject']!='positions' && $_REQUEST['subject']!='areas' && $_REQUEST['subject']!='departments')
exit("to do a page where the user can choose the correct options");
}
$scope=$_REQUEST['subject'];

switch($scope){
case('customers_store'):
//$scope_args=$_SESSION['state']['customers']['store'];
$scope_args=$_REQUEST['subject_key'];
break;

case('supplier_products'):
//$scope_args=$_SESSION['state']['supplier']['id'];
$scope_args=$_REQUEST['subject_key'];
break;

case('staff'):
$scope_args=$_REQUEST['subject_key'];
break;

case('positions'):
$scope_args=$_REQUEST['subject_key'];
break;

case('areas'):
$scope_args=$_REQUEST['subject_key'];
break;

case('departments'):
$scope_args=$_REQUEST['subject_key'];
break;

default:
$scope_args='';
}

if(isset($_POST['submit']))
{
	if($_FILES['fileUpload']['name']=='') { header("location:import_csv.php?subject=$scope&subject_key=$scope_args"); }
	$filesize = '2097152'; // in bytes eqv. to 2MB

	if(($_FILES["fileUpload"]["size"]) >= $filesize)
	{
		$_SESSION['error'] = 'Uploading Error : too large file to upload';
		header("location:import_csv.php?subject=$scope&subject_key=$scope_args");
		exit();
	}
	else
	{	
		if (($_FILES["fileUpload"]["type"] == "text/plain") || ($_FILES["fileUpload"]["type"] == "text/csv"))
	  	{
	  		if ($_FILES["fileUpload"]["error"] > 0)
	    		{
	   		 echo "Error: " . $_FILES["fileUpload"]["error"] . "<br />";
	    		}
	  		else
	    		{
				$target_path = "app_files/uploads/";

				$target_path = $target_path . basename( $_FILES['fileUpload']['name']); 

				if(move_uploaded_file($_FILES['fileUpload']['tmp_name'], $target_path)) 
				{
					$vv=basename( $_FILES['fileUpload']['name']);
					require_once 'csvparser.php';
					$csv = new CSV_PARSER;
					//loading the CSV File
					$csv->load($target_path);

					$h = $csv->getHeaders();

					$_SESSION['file_path'] = $target_path;
					$r = $csv->connect();
				}
	    		}
	  	}
		else
	  	{
		    header("location:import_csv.php?subject=$scope&subject_key=$scope_args&error=Invalid File");
		}
	}
}

if(isset($_SESSION['error'])) 
{ 
   $smarty->assign('showerror',$_SESSION['error']);
}

$v = 0;

$smarty->assign('v',$v);
$smarty->assign('subject',$scope);
$smarty->assign('subject_key',$scope_args);
$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);
$smarty->display('import_csv_verify.tpl');
?>
