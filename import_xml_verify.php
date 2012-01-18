<?php
/*


 UI store page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',

		 //	 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'css/container.css',
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
		'js/ajax_function.js',
		'js/dropdown.js',
        'import_xml_verify.js.php'    
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


break;
default:
$scope_args='';
}

if(isset($_POST['submit']))
{
$target_path = "app_files/uploads/";

$target_path = $target_path . basename( $_FILES['fileUpload']['name']); 

if(move_uploaded_file($_FILES['fileUpload']['tmp_name'], $target_path)) {
    $v=basename( $_FILES['fileUpload']['name']);
}
$p=explode('.', $v);
foreach($p as $p1)
{
}

if($p1=="xml")
{
function objectsIntoArray($arrObjData, $arrSkipIndices = array())
	{
		$arrData = array();
	   
		// if input is object, convert into array
		if (is_object($arrObjData)) {
			$arrObjData = get_object_vars($arrObjData);
		}
	   
		if (is_array($arrObjData)) {
			foreach ($arrObjData as $index => $value) {
				if (is_object($value) || is_array($value)) {
					$value = objectsIntoArray($value, $arrSkipIndices); // recursive call
				}
				if (in_array($index, $arrSkipIndices)) {
					continue;
				}
				$arrData[$index] = $value;
			}
		}
		return $arrData;
	}

	$xmlUrl = "app_files/uploads/".$v; // XML feed file/URL
        

	$xmlStr = file_get_contents($xmlUrl);
	$xmlObj = simplexml_load_string($xmlStr);
	$arrXml = objectsIntoArray($xmlObj);
	
foreach($arrXml as $arr)
	{
	  $size=count($arr);
	$arrXml2[]=$arr;

	}
     $_SESSION['size']=$size;
	$smarty->assign('success',$arrXml2);
  
       
	}
	else
	{
         ?>
           <script>location.href = "import_xml.php?tipo=customers_store&id=0" </script>
	 <?php 
	}}
	else
	{
	$smarty->assign('success',"not successfully");
	}
       //echo $_SESSION['size'];
	///echo "<pre>";
	
	//print_r($arrXml2);

$smarty->assign('size',$size);
$smarty->assign('scope',$scope);
$smarty->assign('scope_args',$scope_args);
$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




 
  $smarty->display('import_xml_verify.tpl');

?>
