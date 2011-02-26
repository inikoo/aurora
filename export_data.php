<?php
/*
File: export_data.php

Data for export process

About:
Autor: Raul Perusquia <rulovico@gmail.com>

Copyright (c) 2010, Kaktus

Version 2.0
*/
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
if($_GET['source'] =='db'){//To ensure that map is loaded from database //
$no_of_maps_saved = numExportMapData($customer_id, $map_type);
	if($no_of_maps_saved > 0){
	$exported_data = getExportMapData($customer_id, $map_type);
	}else{
	echo "<Script>alert('No Map is stored yet !!!');</script>";
	//Assign Redirect path here ... //
	exit;
	}
}else{
	if(!$_POST['SUBMIT']){ // To ensure whether the form is properly submitted - Case create new map //
	header('Location: index.php');
	exit;
	}
	$exported_data = $_SESSION['list']; // Catching values from session [processing through Wizard] //
	//print_r($exported_data);*/

	## Saving Map into Database ##
	if($_POST['save']=='save'){
		if(mysql_real_escape_string($_REQUEST['default']) == 'yes'){
			$default='yes';
		}else{
			$default='no';
		}
		$map_name = 'Map of '.$customer->data['Customer Main Contact Name'];
		$map_data = base64_encode(serialize($exported_data));
		$sql = "INSERT INTO `Export Map` (`Map Name` ,`Map Type` ,`Map Data` ,`Customer Key` ,`Export Map Default` , `Exported Date`)
		VALUES ('$map_name', '$map_type', '$map_data', '$customer_id', '$default' , now())";
		$query = mysql_query($sql);
	}
}

// COMMON CODES FOR BOTH NEW MAP & LOAD MAP FROM DB //
foreach($exported_data as $key=>$value){

	if(!isset($value) || $value == ""){
			$value = ",";
			if($_POST['header']=='header'){
			$header .= $key.",";
			}
			//continue;
		}else{
			$value = str_replace('"', '""', $value);
			$value = $value.",";
			if($_POST['header']=='header'){
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
	//echo "SELECT `Map Data` FROM `Export Map` WHERE `Customer Key` = '$customerKey' AND `Map Type` = '$mapType'";
	if($_REQUEST['id']){
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
function numExportMapData($subject_key, $subject){
	$q = mysql_query("SELECT `Map Key` FROM `Export Map` WHERE `Customer Key` = '$subject_key' AND `Map Type` = '$subject'");
	$num = mysql_num_rows($q);
	return $num;
}
?>
