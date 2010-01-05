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
if(!$user->can_view('customers')){
  exit();
}
if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $_SESSION['state']['customer']['id']=$_REQUEST['id'];
  $customer_id=$_REQUEST['id'];
}else{
  $customer_id=$_SESSION['state']['customer']['id'];
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"out.txt\"");

$customer=new customer($customer_id);
$csv_data=$customer->export_data();
$out = fopen('php://output', 'w');
fputcsv($out, $csv_data,"\t");
fclose($out);






?>