<?php
/*
 File: customer_csv.php

 Customer CSV data for export proprces

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/

include_once('common.php');
include_once('class.Customer.php');
if (!$user->can_view('customers')) {
    exit();
}


if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $_SESSION['state']['customer']['id']=$_REQUEST['id'];
    $customer_id=$_REQUEST['id'];
} else {
    $customer_id=$_SESSION['state']['customer']['id'];
}
$data=array(
          'payment_method'=>'',
          'courier'=>'',
          'source'=>'',
          'special_instructions'=>'',
          'gold_reward'=>'Standard Order',
          'offer'=>''
      );

//print_r($_REQUEST);



if (isset($_REQUEST['data'])) {
    $json=$_REQUEST['data'];
    //  $tmp=stripslashes ($json);
    //   print_r($json);
    $raw_data=json_decode($json, true);
    // print($json);
    foreach($raw_data as $key=>$value) {
        if (array_key_exists($key,$data)) {
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


$address=new Address($customer->data['Customer Billing Address Key']);
$address_lines=$address->display('3lines');

if($customer->data['Customer Main XHTML Telephone']!='' and $customer->data['Customer Main XHTML Mobile']!=''){
$tel=$customer->data['Customer Main XHTML '.$customer->data['Customer Preferred Contact Number']];
}else{
$tel=($customer->data['Customer Main XHTML Telephone']==''?$customer->data['Customer Main XHTML Mobile']:$customer->data['Customer Main XHTML Telephone']);
}
$delivery_address=new Address($customer->data['Customer Main Delivery Address Key']);
$delivery_address_lines=$delivery_address->display('3lines');


$delivery_tel=$delivery_address->get_formated_principal_telephone();
if ($delivery_tel=='')
    $delivery_tel=$tel;
$number_orders=$customer->data['Customer Orders'];

if (preg_match('/^[a-z]+/i', $user->get("User Alias"),$match))
    $alias=$match[0];
else
    $alias=$user->get("User Alias");

if ($customer->data['Customer Type']=='Company') {
    if ($customer->get('Customer Fiscal Name')!=$customer->data['Customer Name']) {
        $contact_name1=$customer->get('Customer Fiscal Name');
        $contact_name2=$customer->data['Customer Main Contact Name'].' ('.$customer->data['Customer Name'].')' ;

    } else {
        $contact_name1=$customer->data['Customer Name'];
        $contact_name2=$customer->data['Customer Main Contact Name'];
    }
} else {
    $contact_name1=$customer->data['Customer Name'];
    $contact_name2='';
}



if ($customer->data['Customer Type']=='Company') {
  $delivery_address_contact_line1=$customer->data['Customer Name'];
$delivery_address_contact_line2=$customer->data['Customer Main Contact Name'];

} else {
   $delivery_address_contact_line1=$customer->data['Customer Name'];
$delivery_address_contact_line2='';
}


if ($delivery_address->data['Address Contact']!='') {
    $delivery_address_contact_line1=$delivery_address->data['Address Contact'];
    $delivery_address_contact_line2='';
}


$export_data=array(
                 "Public"
                 ,""
                 ,$contact_name1
                 ,$contact_name2
                 ,$address_lines[1]
                 ,$address_lines[3]
                 ,$address_lines[2]
                 ,$address->display('Town with Divisions')
                 ,$address->display('Country Divisions')
                 ,$address->data['Address Postal Code']
                 ,$address->data['Address Country Name']
                 ,"Staff"
                 ,$tel
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
                 ,$data['offer']
                 ,"i"
                 ,"j"
                 ,"k"
                 ,"2"
                  ,$data['source']
                 ,$data['gold_reward']
                 ,$alias
                 ,"m"
                 ,"n"
                 ,$customer->data['Recargo Equivalencia']
                 ,$data['payment_method']
                 ,$customer->id
                 ,$customer->data['Customer Tax Category Code']
                 ,$customer->get_fiscal_name()
                 ,""
                 ,""
                 ,"inikoo_del_address"
                 ,$delivery_tel
                 ,$delivery_address_lines[1]
                 ,$delivery_address_lines[3]
                 ,$delivery_address_lines[2]
                 ,$delivery_address->display('Town with Divisions')
                 ,$delivery_address->display('Country Divisions')
                 ,$delivery_address->data['Address Postal Code']
                 ,$delivery_address->data['Address Country Name']
                 ,$delivery_address_contact_line1

                 ,$delivery_address_contact_line2
                 ,"","","","","","","","","","","","inikoo","inikoo","c","d","e"
                 ,$number_orders+1
                 ,"","","","","","","","","","","","",""
                 ,$data['courier']
                 ,$data['special_instructions']
                 ,"Yes",
                 $customer->data['Customer Tax Number'],
                 $customer->data['Customer Tax Number Valid'],
                 "","",""
                 ,$customer->data['Customer Main Plain Email']
                 ,""
                 ,
             );






$csv_data=$export_data;
$out = fopen('php://output', 'w');
fputcsv($out, $csv_data,"\t");
fclose($out);






?>
