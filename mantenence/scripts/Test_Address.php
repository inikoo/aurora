<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../conf/dns.php');
include_once('../../class.Address.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');



$row = 0;
$handle = fopen("test_address.txt", "r");
while (($csv_data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
    $num = count($csv_data);
    // echo "Record $row\n";

    $address_data[$row]['Address Line 1']=$csv_data[0];
    $address_data[$row]['Address Line 2']=$csv_data[1];
    $address_data[$row]['Address Line 3']=$csv_data[2];
    $address_data[$row]['Address Town']=$csv_data[3];
    $address_data[$row]['Address Country First Division']=$csv_data[4];
    $address_data[$row]['Address Postal Code']=$csv_data[5];
    $address_data[$row]['Address Country']=$csv_data[6];
    $row++;
    //print_r($address_data);
}
fclose($handle);

foreach ($address_data as $key=>$data){

  if($key>=0){
    $address=Address::prepare_3line($data);
    print "$key ********\n";
    print_r($data);
    print_r($address);
  }
}



?>