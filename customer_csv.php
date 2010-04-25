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




$address=new Address($customer->data['Customer Main Address Key']);
$address_lines=$address->display('3lines');

$number_orders=$customer->data['Customer Orders'];


$export_data=array(
		   "Public"
		   ,""
		   ,$customer->data['Customer Name']
		   ,$customer->data['Customer Main Contact Name']
		   ,$address_lines[1]
		   ,$address_lines[3]
		   ,$address_lines[2]
		   ,$customer->data['Customer Main Address Town']
		   ,$address->display('Country Divisions')
		   ,$customer->data['Customer Main Address Postal Code']
		   ,$customer->data['Customer Main Address Country']
		   ,"Staff"
		   ,$customer->data['Customer Main XHTML Telephone']
		   ,$customer->data['Customer Main XHTML FAX']
		   ,"a"
		   ,"mobile"
		   ,"26/09/2002"
		   ,"David"
		   ,"b"
		   ,"c"
		   ,"d"
		   ,"03/03/2003"
		   ,"e"
		   ,"f"
		   ,"g"
		   ,"Wholesaler website"
		   ,"h"
		   ,"i"
		   ,"j"
		   ,"k"
		   ,"2"
		   ,"l"
		   ,"Gold Reward Member"
		   ,$user->get("User Alias")
		   ,"m"
		   ,"n"
		   ,"o"
		   ,$number_orders+1
		   ,"900"
		   ,"q"
		   ,""
		   ,""
		   ,""
		   ,""
		   ,""
		   ,""
		   ,"","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","",""
		   ,''
		   ,''
		   ,"Yes","","","","",""
		   ,$customer->data['Customer Main Plain Email']
                         ,""
                         ,
                     );

     








$csv_data=$export_data;
$out = fopen('php://output', 'w');
fputcsv($out, $csv_data,"\t");
fclose($out);






?>