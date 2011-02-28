<?php
/*
File: export_data.php

Data for export process

About:
Autor: Raul Perusquia <rulovico@gmail.com>

Copyright (c) 2010, Kaktus

Version 2.0
*/
/*ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);*/
include_once('common.php');
include_once('class.Customer.php');
if(!$_REQUEST['subject_key'] || !$_REQUEST['subject']){ //To ensure whether the form has proper parameters in query string //
	header('Location: index.php');
	exit;
}
$map_type = mysql_real_escape_string($_REQUEST['subject']);
if(!$user->can_view('customers')){
  exit();
}
if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key']) ){
  $_SESSION['state']['customer']['id']=mysql_real_escape_string($_REQUEST['subject_key']);
  $customer_id=mysql_real_escape_string($_REQUEST['subject_key']);
}else{
  $customer_id=$_SESSION['state']['customer']['id'];
}
$customer=new customer($customer_id);
$exported_data=array(); //This is final array of selected and sorted fields - Now assigned as an empty array//
$line = ''; $data = '';$header = '';
if(isset($_GET['source']) && $_GET['source'] =='db'){//To ensure that map is loaded from database //
$no_of_maps_saved = numExportMapData($customer_id, $map_type);
	if($no_of_maps_saved > 0){
	$exported_data = getExportMapData($customer_id, $map_type);
	}else{
	//Assign "Default Export Fields" in this array ... //
	$included_data[0] = 'Customer Main Contact Name';
	$included_data[1] = 'Customer Main Plain Email';
	$included_data[2] = 'Customer Main Plain Telephone';
	// If header is required in default export //
	foreach($included_data as $arr_val){
		$header .= $arr_val.",";
	}
	//print_r($included_data);

	$actual_data=$customer->data;
	//print_r($actual_data);

	$exported_data = final_array($actual_data , $included_data);
	//print_r($exported_data);

	}
}else{
	if(!isset($_POST['SUBMIT'])){ // To ensure whether the form is properly submitted - Case create new map //
	header('Location: index.php');
	exit;
	}
	$exported_data = $_SESSION['list']; // Catching values from session [processing through Wizard] //
	//print_r($exported_data);*/

	## Saving Map into Database ##
	if(isset($_POST['save']) && $_POST['save']=='save'){
		if(isset($_REQUEST['default']) && mysql_real_escape_string($_REQUEST['default']) == 'yes'){
			$default='yes';
		}else{
			$default='no';
		}
		$map_name = mysql_real_escape_string($_POST['map_name']) ;
		$map_desc = mysql_real_escape_string($_POST['map_desc']) ;
		if(isset($_POST['header']) && $_POST['header']=='header'){
			$map_header = 'yes';
		}else{
			$map_header = 'no';
		}
		$map_data = base64_encode(serialize($exported_data));
		$sql = "INSERT INTO `Export Map` (`Map Name` , `Map Description` , `Map Type` ,`Map Data` ,`Customer Key` , `Export Header` , `Export Map Default` , `Exported Date`)
		VALUES ('$map_name', '$map_desc', '$map_type', '$map_data', '$customer_id', '$map_header', '$default' , now())";
		$query = mysql_query($sql);
	}
}

// COMMON CODES FOR BOTH NEW MAP & LOAD MAP FROM DB //
foreach($exported_data as $key=>$value){

	if(!isset($value) || $value == ""){
			$value = ",";
			if(getExportMapHeader($customer_id, $map_type) == 'yes' || (isset($_REQUEST['header']) && $_REQUEST['header']=='header')){
			$header .= $key.",";
			}
		}else{
			$value = str_replace('"', '""', $value);
			$value = $value.",";
			if(getExportMapHeader($customer_id, $map_type) == 'yes' || (isset($_REQUEST['header']) && $_REQUEST['header']=='header')){
			$header .= $key.",";
			}
	}
			$line .= $value;
}
$data .= trim($line)."\n";
$line = '';
unset($exported_data);
$data = str_replace("\r", "", $data);
if ($data == "") {
  $data = "\nno matching records found\n";
}
$filename = $customer->data['Customer Key'].'-'.time().'.csv'; // Define your exported file name from here //
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
echo $header."\n".$data;

## METHODS USED FOR THIS PAGE ##
function getExportMapData($subject_key, $subject){
	if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
		$id=mysql_real_escape_string($_REQUEST['id']);
		$s="SELECT `Map Data` FROM `Export Map` WHERE `Customer Key` = '$subject_key' AND `Map Type` = '$subject' AND `Map Key` = '$id'";

	}else{
		$s="SELECT `Map Data` FROM `Export Map` WHERE `Customer Key` = '$subject_key' AND `Map Type` = '$subject' ORDER BY `Export Map`.`Exported Date` DESC
LIMIT 0 , 1";
	}
	$q = mysql_query($s);
	$r = mysql_fetch_assoc($q);
	$data= unserialize(base64_decode($r['Map Data']));
	return $data;
}

function getExportMapHeader($subject_key, $subject){
	if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
		$id=mysql_real_escape_string($_REQUEST['id']);
		$s="SELECT `Export Header` FROM `Export Map` WHERE `Customer Key` = '$subject_key' AND `Map Type` = '$subject' AND `Map Key` = '$id'";

	}else{
		$s="SELECT `Export Header` FROM `Export Map` WHERE `Customer Key` = '$subject_key' AND `Map Type` = '$subject' ORDER BY `Export Map`.`Exported Date` DESC LIMIT 0 , 1";
	}
	$q = mysql_query($s);
	if($q){
		$r = mysql_fetch_assoc($q);
		$data= $r['Export Header'];
	}else{
		$data = 'yes';
	}
	return $data;
}

function numExportMapData($subject_key, $subject){
	$q = mysql_query("SELECT `Map Key` FROM `Export Map` WHERE `Customer Key` = '$subject_key' AND `Map Type` = '$subject'");
	$num = mysql_num_rows($q);
	return $num;
}
function final_array($assoc_arr, $num_arr){
	$final_arr = array();

	foreach($assoc_arr as $assoc_key => $assoc_val){

		if(in_array($assoc_key, $num_arr)){

			$final_arr[$assoc_key]=$assoc_val;

		}
	}
	//print_r($final_arr);
	return $final_arr;
}

?>
