<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
include_once('../../class.Customer.php');

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

//$sql="select * from kbase.`Country Dimension`";
//$result=mysql_query($sql);
//while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//print "cp ../../examples/_countries/".strtolower(preg_replace('/\s/','_',$row['Country Name']))."/ammap_data.xml ".$row['Country Code'].".xml\n";
//}
//exit;

$sql="select * from `Telecom Dimension` order by `Telecom Key` desc  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
 
   $tel=new Telecom($row['Telecom Key']);
$tel->update_parents(false);
print $tel->id."\t\t\r";
continue;

$address_keys=$tel->get_parent_keys('Address');



if(count($address_keys)==1){
$tmp=array_pop($address_keys);
$address=new Address($tmp['Subject Key']);
if(!$address->id){
print "address ".$tmp['Subject Key']." in tel ".$tel->id." dont exists\n";
continue;
}


//print $address->data['Address Country Code']."\n";
//print_r($tel->data);

$_number=$tel->data['Telecom Plain Number'];
$number=$tel->data['Telecom Plain Number'];

switch($address->data['Address Country Code']){
case 'FRA':

$formated_number=$number;

print " $formated_number\t $_number  $number \t\t ".$tel->display('xhtml')."\n";
break;
case 'IRL':
continue;
$number=preg_replace('/^0{1,2}353/','353',$number);
//$number=preg_replace('/'.$tel->data['Telecom Number'].'$/',' '.$tel->data['Telecom Number'],$number);
$number=_trim($number);



if(strlen($number)>=14 and preg_match('/^353100353/',$number)){
$number=preg_replace('/^353100353/','353',$number);
}elseif(strlen($number)>=14 and preg_match('/^35300353/',$number)){
$number=preg_replace('/^35300353/','353',$number);
}elseif(strlen($number)>=15 and preg_match('/^3530353/',$number)){
$number=preg_replace('/^3530353/','353',$number);
}elseif(strlen($number)>=14 and preg_match('/^3534400353/',$number)){
$number=preg_replace('/^3534400353/','353',$number);
}elseif(strlen($number)>=15 and preg_match('/^353440/',$number)){
$number=preg_replace('/^353440/','353',$number);
}elseif(strlen($number)>=9 and preg_match('/^8/',$number)){
$number=preg_replace('/^8/','3538',$number);
}  

$formated_number=$number;
if(preg_match('/^3530/',$number)){

$formated_number=preg_replace('/^3530/','+353 (0) ',$number);
}elseif(preg_match('/^353(1|2|3|4|5|6|7|8|9)/',$number)){

$formated_number=preg_replace('/^353/','+353 (0) ',$number);
}elseif(preg_match('/^0(1|2|3|4|5|6|7|8|9)/',$number)){
$formated_number=preg_replace('/^0/','+353 (0) ',$number);
}
$formated_number=preg_replace('/'.$tel->data['Telecom Number'].'$/',' '.$tel->data['Telecom Number'],$formated_number);
$formated_number=preg_replace('/\s+/',' ',_trim($formated_number));

print " $formated_number\t $_number  $number \t\t ".$tel->display('xhtml')."\n";
$tel->update_number($formated_number,$address->data['Address Country Code']);

break;
case 'GBR':
continue;


if(strlen($number)==10 and preg_match('/^7/',$number)){
$number=preg_replace('/^7/','4407',$number);
}elseif(strlen($number)>=14 and preg_match('/^44044/',$number)){
$number=preg_replace('/^44044/','440',$number);
}elseif(strlen($number)>=14 and preg_match('/^44010/',$number)){
$number=preg_replace('/^44010/','440',$number);
}

$number=preg_replace('/^0{1,2}44/','44',$number);
$number=preg_replace('/^0{1,2}490/','+49 (0)',$number);
$number=preg_replace('/^0{1,2}49/','+49 (0)',$number);




$number=preg_replace('/'.$tel->data['Telecom Number'].'$/',' '.$tel->data['Telecom Number'],$number);

$number=preg_replace('/^448/','08',$number);
$number=preg_replace('/^4408/','08',$number);

$number=_trim($number);

$formated_number=$number;
if(preg_match('/^440/',$number)){

$formated_number=preg_replace('/^440/','+44 (0) ',$number);
}elseif(preg_match('/^0(1|2|7)/',$number)){
$formated_number=preg_replace('/^0/','+44 (0) ',$number);
}
$formated_number=preg_replace('/\s+/',' ',_trim($formated_number));
print " $formated_number\t $_number  \t\t ".$tel->display('xhtml')."\n";
$tel->update_number($formated_number,$address->data['Address Country Code']);

break;

}


}
//$tel->update_parents(false);

 // print $tel->id."\t\t\r";
 }
 





?>