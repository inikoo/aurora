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
		   ,$this->data['Customer Name']
		   ,$this->data['Customer Main Contact Name']
		   ,$address_lines[1]
		   ,$address_lines[3]
		   ,$address_lines[2]
		   ,$this->data['Customer Main Address Town']
		   ,$address->display('Country Divisions')
		   ,$this->data['Customer Main Address Postal Code']
		   ,$this->data['Customer Main Address Country']
		   ,"Staff"
		   ,$this->data['Customer Main Telephone']
		   ,$this->data['Customer Main FAX']
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
		   ,"p"
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
		   ,$this->data['Customer Main Plain Email']
                         ,""
                         ,
                     );

        return $export_data;








$csv_data=$customer->export_data();
$out = fopen('php://output', 'w');
fputcsv($out, $csv_data,"\t");
fclose($out);






?>