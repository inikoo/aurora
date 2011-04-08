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
	    'gold_reward'=>'Standard Order'
	    );

//print_r($_REQUEST);



if(isset($_REQUEST['data'])){
  $json=$_REQUEST['data'];
    //  $tmp=stripslashes ($json);
  //   print_r($json);
      $raw_data=json_decode($json, true);
     // print($json);
      foreach($raw_data as $key=>$value){
	if(array_key_exists($key,$data)){
	  $data[$key]=html_entity_decode($value);
	}
      }

}

//print_r($data);
//exit;


$customer=new customer($customer_id);




//print $gold;
//exit("s");

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"out.txt\"");


$address=new Address($customer->data['Customer Main Address Key']);
$address_lines=$address->display('3lines');

$number_orders=$customer->data['Customer Orders'];

if(preg_match('/^[a-z]+/i', $user->get("User Alias"),$match))
$alias=$match[0];
else
$alias=$user->get("User Alias");

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
		   ,($customer->data['Customer Main XHTML Telephone']==''?$customer->data['Customer Main XHTML Mobile']:$customer->data['Customer Main XHTML Telephone'])
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
		   ,$data['gold_reward']
		   ,$alias
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
		   ,"Yes",
		   $customer->data['Customer Tax Number'],
		   "","","",""
		   ,$customer->data['Customer Main Plain Email']
                         ,""
                         ,
                     );

     




$csv_data=$export_data;
$out = fopen('php://output', 'w');
fputcsv($out, $csv_data,"\t");
fclose($out);






?>
