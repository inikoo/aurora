<?php
/*
File: new_customer_csv.php

Customer CSV data for export process

About:
Autor: Raul Perusquia <rulovico@gmail.com>

Copyright (c) 2010, Kaktus

Version 2.0
*/
include_once('common.php');
include_once('class.Customer.php');


if(!$_REQUEST['id']){ //To check whether the form has proper parameters in query string //
	header('Location: index.php');
	exit;
}

if(!$user->can_view('customers')){
  exit();
}

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $_SESSION['state']['customer']['id']=$_REQUEST['id'];
  $customer_id=$_REQUEST['id'];
}else{
  $customer_id=$_SESSION['state']['customer']['id'];
}

$customer=new customer($customer_id);
$address=new Address($customer->data['Customer Main Address Key']);
$address_lines=$address->display('3lines');
$number_orders=$customer->data['Customer Orders'];

$exported_data=array(); //This is final array of selected and sorted fields //

//$actual_data=array();
//$included_data=array();

$line = ''; $data = '';$header = '';

/*
if(!$_POST['fld']){
	die('Select atleast one field');
	exit;
}
$included_data = $_POST['fld'];
//print_r($included_data);
$actual_data=$customer->data;
//print_r($actual_data);
*/

if($_REQUEST['loaddb']=='yes'){

$exported_data = getExportMapData($customer_id, 'Customer');
//print_r($exported_data);


}else{
	if(!$_POST['SUBMIT']){ // To check whether the form is properly submitted //
	header('Location: index.php');
	exit;
	}
	$exported_data = $_SESSION['list']; // Catching values from session [processing through Wizard] //

	/*$exported_data = final_array($actual_data , $included_data);
	//print_r($exported_data);*/

	## Saving Map into Database ##
	if($_Request['default'] == 'yes'){

		$default='yes';

	}else{

		$default='no';
	}

	$map_name = 'Map of '.$customer->data['Customer Main Contact Name'];
	$map_data = base64_encode(serialize($exported_data));

	if(numExportMapData($customer_id, 'Customer') == 0){
	$sql = "INSERT INTO `Export Map` (`Map Name` ,`Map Type` ,`Map Data` ,`Customer Key` ,`Export Map Default`)
	VALUES ('$map_name', 'Customer', '$map_data', '$customer_id', '$default')";
	}else{
	$sql = "UPDATE `Export Map` SET `Map Name` = '$map_name', `Map Data` =  '$map_data', `Export Map Default` = '$default' WHERE `Customer Key` ='$customer_id' AND `Map Type` = 'Customer'";
	}

	$query = mysql_query($sql);

}

foreach($exported_data as $key=>$value){

	if(!isset($value) || $value == ""){
			$value = ",";
			if(!isset($_Request['header']) OR $_Request['header']=='yes'){
			$header .= $key.",";
			}
			//continue;
		}else{
			$value = str_replace('"', '""', $value);
			$value = $value.",";
			if(!isset($_Request['header']) OR $_Request['header']=='yes'){
			$header .= $key.",";
			}
	}
			$line .= $value;
}

$data .= trim($line)."\n";
$line = '';

unset($exported_data);
//unset($actual_data);
//unset($included_data);

$data = str_replace("\r", "", $data);

if ($data == "") {
  $data = "\nno matching records found\n";
}
$filename = $customer->data['Customer Key'].'.csv'; // Define your exported file name from here //
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

echo $header."\n".$data;

## METHODS USED FOR THIS PAGE ##

function getExportMapData($customerKey, $mapType){
	//echo "SELECT `Map Data` FROM `Export Map` WHERE `Customer Key` = '$customerKey' AND `Map Type` = '$mapType'";
	$q = mysql_query("SELECT `Map Data` FROM `Export Map` WHERE `Customer Key` = '$customerKey' AND `Map Type` = '$mapType'");
	$r = mysql_fetch_assoc($q);
	$data= unserialize(base64_decode($r['Map Data']));
	return $data;
}

function numExportMapData($customerKey, $mapType){
	$q = mysql_query("SELECT `Map Key` FROM `Export Map` WHERE `Customer Key` = '$customerKey' AND `Map Type` = '$mapType'");
	$num = mysql_num_rows($q);
	return $num;
}

/*
function final_array($assoc_arr, $num_arr){
	$final_arr = array();

	foreach($assoc_arr as $assoc_key => $assoc_val){

		if(in_array($assoc_key, $num_arr)){

			$final_arr[$assoc_key]=$assoc_val;

		}
	}
	//print_r($final_arr);
	return $final_arr;
}*/

?>
