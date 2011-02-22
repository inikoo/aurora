<?php
/*
File: customer_csv.php

Customer CSV data for export proprces

About:
Autor: Raul Perusquia <rulovico@gmail.com>

Copyright (c) 2010, Kaktus

Version 2.0
*/

include_once('common.php');
include_once('class.Customer.php');

if(!$_POST['SUBMIT']){
	header('Location: index.php');
	exit;
}

if(!$_REQUEST['id']){
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

$actual_data=array();
//$post_data=array();
$exported_data=array();

if(!$_POST['fld']){
	die('Select al least one field');
	exit;
}
$exported_data = $_POST['fld'];
print_r($exported_data);
$line = ''; $data = '';
$filename = $customer->data['Customer Key'].'.csv';

//$post_data[0]='something that need not to process';
print_r($post_data);

$actual_data=$customer->data;
print_r($actual_data);

$exported_data = get_exported_array($actual_data , $post_data);
print_r($exported_data);


foreach($exported_data as $key=>$value){

	if(!isset($value) || $value == ""){
			$value = ",";
			$header .= $key.",";
			//continue;
		}else{
			$value = str_replace('"', '""', $value);
			$value = $value.",";
			$header .= $key.",";
	}
			$line .= $value;
}

$data .= trim($line)."\n";
$line = '';
unset($exported_data);
unset($post_data);
unset($actual_data);

$data = str_replace("\r", "", $data);

if ($data == "") {
  $data = "\nno matching records found\n";
}

//header("Content-type: application/octet-stream");
//header("Content-Disposition: attachment; filename=$filename");
//header("Pragma: no-cache");
//header("Expires: 0");

//echo $header."\n".$data;



function get_exported_array($actual, $input){

	$result = array();
	foreach($input as $value){

		if(array_key_exists($value, $actual)){
			$var=$actual[$value];
			array_push($result, $var);

		}
	}
	return $result;
}

?>
