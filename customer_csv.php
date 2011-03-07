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
$data=array(
	    'payment_method'=>'',
	    'courier'=>'',
	    'special_instructions'=>'',
	    );



if(isset($_REQUEST['data'])){
  $json=$_REQUEST['data'];
      $tmp=stripslashes ($json);
      $raw_data=json_decode($tmp, true);
      foreach($raw_data as $key=>$value){
	if(array_key_exists($key,$data)){
	  $data[$key]=$value;
	}
      }

}





$customer=new customer($customer_id);


$gold='Not Current';
//print_r($customer->data);
if($customer->data['Customer Last Order Date']    ){
  $last_order_date=$customer->data['Customer Last Order Date'];
  $last_order_date='2011-01-15';
  $last_order_time=strtotime( $last_order_date);
  // print $last_order_time;
  if( (date('U')-$last_order_time)<2592000 )
    $gold='Gold Reward Member';

}

//print $gold;
//exit("s");

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"out.txt\"");


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
		   ,$customer->data['Customer Main Town']
		   ,$address->display('Country Divisions')
		   ,$customer->data['Customer Main Postal Code']
		   ,$customer->data['Customer Main Country']
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
		   ,$gold
		   ,$user->get("User Alias")
		   ,"m"
		   ,"n"
		   ,"o"
		   ,$data['payment_method']
		   ,$customer->id
		   ,"q"
		   ,""
		   ,""
		   ,""
		   ,""
		   ,""
		   ,""
		   ,"","","","","","","","","","","","","","","","","","","","kaktus","kaktus","c","d","e"
		   ,$number_orders+1
		   ,"","","","","","","","","","","","",""
		   ,$data['courier']
		   ,$data['special_instructions']
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
