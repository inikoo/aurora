<?php
/*


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
		'js/ajax_function.js',
		'js/dropdown.js'
        	);


if(!isset($_REQUEST['tipo'])){
exit("to do a page where the user can choose the correct options");
}

$scope=$_REQUEST['tipo'];



switch($scope){
case('customers_store'):
$scope_args=$_SESSION['state']['customers']['store'];


break;
default:
$scope_args='';
}

if(isset($_POST['submit']))
{
	if($_FILES['fileUpload']['name']=='') { header('location:import_csv.php?tipo=customers_store'); }
		
	if (($_FILES["fileUpload"]["type"] == "text/plain") || ($_FILES["fileUpload"]["type"] == "text/csv") && ($_FILES["fileUpload"]["size"] < 20000))
	  {
	  if ($_FILES["fileUpload"]["error"] > 0)
	    {
	    echo "Error: " . $_FILES["fileUpload"]["error"] . "<br />";
	    }
	  else
	    {
			$target_path = "uploads/";

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
						
				//echo '<pre>'; print_r($r);
			}
	    }
	  }
	else
	  {
		header('location:import_csv.php?tipo=customers_store&error=Invalid File');	 	

	  }
}

$v = 0;

$smarty->assign('v',$v);

$smarty->assign('scope',$scope);
$smarty->assign('scope_args',$scope_args);
$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);

$smarty->display('import_csv_verify.tpl');

?>
