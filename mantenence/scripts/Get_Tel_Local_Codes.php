<?php
/*
 Script: Get_Tel_Local_Codes
 This script read the UK telephone codes from BT web site

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';

mysql_set_charset('utf8');

require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');
$_SESSION['lang']=1;

$software='Get_Orders_DB.php';
$version='V 1.0';


for($i=0;$i<10;$i++){
  $code='02'.$i;
  get_uk_codes($code);

}
for($i=0;$i<100;$i++){
  $code='01'.sprintf("%02d",$i);
  get_uk_codes($code);

}

for($i=0;$i<1000;$i++){
  $code='01'.sprintf("%03d",$i);
  get_uk_codes($code);
}

for($i=0;$i<10000;$i++){
  $code='01'.sprintf("%04d",$i);
  get_uk_codes($code);
}


/* for($i=0;$i<10000;$i++){ */
/*   $code='01'.sprintf("%04d",$i); */
/*   print "$code\n"; */
/* } */


function get_uk_codes($code){



$country_code='GBR';
$page = file_get_contents("http://www.thephonebook.bt.com/publisha.content/en/search/uk_codes/search.publisha?Search=$code");
//print $page;

if(preg_match("/could not be found/",$page))
   print "$code code not found\n";
elseif(preg_match("/<span>The location for<\/span> $code <span>is<\/span>.*\n/",$page,$match)){
  $loc=_trim(preg_replace('/.*<\/span>/','',$match[0]));
  $loc=_trim(preg_replace('/<\/div>.*/','',$loc));
  $code=preg_replace('/^0/','',$code);
  print "$code code is in $loc\n";
  $sql=sprintf("INSERT INTO `dw`.`Telephone Local Code` (
`Telephone Local Code` ,
`Telephone Local Code Location` ,
`Telephone Local Code Country Code`
)
VALUES (%s, %s, %s);"
	       ,prepare_mysql($code)
	       ,prepare_mysql($loc)
	       ,prepare_mysql($country_code)
	       );
  mysql_query($sql);
  
}elseif(preg_match_all("/<div>\d+. <span>The location for<\/span> $code\d* <span>is<\/span>.*\n/",$page,$match)){

  foreach($match[0] as $_match){
    $loc=_trim(preg_replace('/.*<\/span>/','',$_match));
    $loc=_trim(preg_replace('/<\/div>.*/','',$loc));

    $code=preg_replace('/.*for<\/span>/','',$_match);
    $code=_trim(preg_replace('/<span>.*/','',$code));
    $code=preg_replace('/^0/','',$code);
    print "$code code is in $loc\n";
    $sql=sprintf("INSERT INTO `dw`.`Telephone Local Code` (`Telephone Local Code` ,`Telephone Local Code Location` ,`Telephone Local Code Country Code`) VALUES (%s, %s, %s);"
	       ,prepare_mysql($code)
		 ,prepare_mysql($loc)
		 ,prepare_mysql($country_code)
		 );
    mysql_query($sql);


  }
  


}else
    print "$code can not interpret result\n";

}