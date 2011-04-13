<?php
include_once('common.php');
include_once('class.Customer.php');


if (!isset($_REQUEST['id']) or !isset($_REQUEST['tipo'])) {
    exit;
}
$customer=new Customer($_REQUEST['id']);
if (!$customer->id) {
    exit('err');
}

$address_data="var Address_Data= new Object;";
switch ($_REQUEST['tipo']) {
 case('customer'); 
 
 
 print "var customer_id=".$customer->id.";";
 $addresses=$customer->get_address_keys();

$address_data.="\n";
foreach($addresses as $index) {
    // $address->set_scope('Customer',$cust);

    $address=new Address($index);


    $type="[";
    foreach($address->get('Type') as $_type) {
        $type.=prepare_mysql($_type,false).",";
    }
    $type.="]";
    $type=preg_replace('/,]$/',']',$type);

    $function="[";
    foreach($address->get('Function') as $value) {
        $function.=prepare_mysql($value,false).",";
    }
    $function.="]";
    $function=preg_replace('/,]$/',']',$function);


    $address_data.="\n".sprintf('Address_Data[%d]={"key":%d,"telephone":%s, "country":%s,"country_code":%s,"country_d1":%s,"country_d2":%s,"town":%s,"postal_code":%s,"town_d1":%s,"town_d2":%s,"fuzzy":%s,"street":%s,"building":%s,"internal":%s,"type":%s,"description":%s,"function":%s}; ',

                                $address->id
                                ,$address->id
                                 ,prepare_mysql($address->get_formated_principal_telephone(),false)
                                ,prepare_mysql($address->data['Address Country Name'],false)
                                ,prepare_mysql($address->data['Address Country Code'],false)
                                ,prepare_mysql($address->data['Address Country First Division'],false)
                                ,prepare_mysql($address->data['Address Country Second Division'],false)
                                ,prepare_mysql($address->data['Address Town'],false)
                                ,prepare_mysql($address->data['Address Postal Code'],false)
                                ,prepare_mysql($address->data['Address Town First Division'],false)
                                ,prepare_mysql($address->data['Address Town Second Division'],false)
                                ,prepare_mysql($address->data['Address Fuzzy'],false)
                                ,prepare_mysql($address->display('street',false),false)
                                ,prepare_mysql($address->data['Address Building'],false)
                                ,prepare_mysql($address->data['Address Internal'],false)
                                ,$type
                                ,prepare_mysql($address->data['Address Description'],false)
                                ,$function

                               );
    $address_data.="\n";




}
print $address_data;

}




?>

