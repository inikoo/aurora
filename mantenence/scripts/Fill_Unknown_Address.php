<?php
/*
 Script: Fill_Unknown_Address.php
 This script creates the dummy unknown address needed when fuzzy data is inputed 

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

//include("../../external_libs/adminpro/adminpro_config.php");
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Country.php');
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





$software='Fill_Unknown_Address.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$sql="insert into `Address Dimension` (`Address Location`,`Fuzzy Address`,`Address Data Creation`,`Address Fuzzy Type`) values (".prepare_mysql('<img src="art/flags/un.png" alt="?" title="UNK"/>').",1,NOW(),'All')";
mysql_query($sql);

$sql="SELECT * FROM dw.`Country Dimension` C where `Country key`!=244 group by `World Region` order by `World Region`  ";

$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  
  $country_id=$row['Country Key'];
  $country=new Country('id',$country_id);
  
  if(preg_match('/^(cental|south|north|east|west|australia|Micron|central|Melanes|polyne)/i',$country->data['World Region']))
    $xhtml_address='Somewhere in '.$country->data['World Region'];
  else
    $xhtml_address='Somewhere in the '.$country->data['World Region'];
  $sql=sprintf("insert into `Address Dimension` (`Address Location`,`Fuzzy Address`,`Address Data Creation`,`Address World Region`,`Address Continent`,`XHTML Address`,`Address Fuzzy Type`) values (".prepare_mysql('<img src="art/flags/un.png" alt="?" title="UNK"/>').",1,NOW(),%s,%s,%s,'World Region')"
	       
	       ,prepare_mysql($country->data['World Region'])
	       ,prepare_mysql($country->data['Continent'])
	       ,prepare_mysql($xhtml_address)
	       );
  mysql_query($sql);
  //      print "$sql\n";
  //print_r($country->data);
 }


$sql="SELECT * FROM dw.`Country Dimension` C where `Country key`!=244 order by `Country Code`  ";


$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  
  $country_id=$row['Country Key'];
  $country=new Country('id',$country_id);

  
  $sql=sprintf("insert into `Address Dimension` (`Address Location`,`Fuzzy Address`,`Address Data Creation`,`Address Country Key`,`Address Country Name`,`Address Country Code`,`Address Country 2 Alpha Code`,`Address World Region`,`Address Continent`,`XHTML Address`,`Address Fuzzy Type`) values (%s,1,NOW(),%d,%s,%s,%s,%s,%s,%s,'Country')",
	       prepare_mysql(
			     '<img src="art/flags/'.
			     strtolower($country->data['Country 2 Alpha Code']).'.png" alt="'.
			     addslashes($country->data['Country Name']).'" title="'.$country->data['Country Code'].'"/>')
	       ,$country->data['Country Key']
	       ,prepare_mysql($country->data['Country Name'])
	       ,prepare_mysql($country->data['Country Code'])
	       ,prepare_mysql($country->data['Country 2 Alpha Code'])
	       ,prepare_mysql($country->data['World Region'])
	       ,prepare_mysql($country->data['Continent'])
	       ,prepare_mysql('Somewhere in '.$country->data['Country Name'])
	       );
  mysql_query($sql);
  //print "$sql\n";
  //print_r($country->data);
 }



?>