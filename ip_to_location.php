<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once 'app_files/db/dns.php';


error_reporting(E_ALL);



date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once 'common_functions.php';
require_once 'common_detect_agent.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once 'conf/conf.php';

//Database Extraction Script
//Populating IP Geo Dimension in kbase
/*
for($i=0; $i<=255; $i++){
	$table="ip4_".$i;
	$sql=sprintf("select * from host_ip_test.`$table`");
	$result=mysql_query($sql);
	while($row=mysql_fetch_assoc($result)){
		$sql=sprintf("select * from host_ip_test.`countries` where `id`=%d", $row['country']);
		$result1=mysql_query($sql);
		$row1=mysql_fetch_assoc($result1);
		
		$country=$row1['name'];
		$code=$row1['code'];

		$sql=sprintf("insert into kbase.`IP Geo Dimension` (`IP Geo A`, `IP Geo B`, `IP Geo C`, `Country`, `Country Code`) values (%d, %d, %d, '%s', '%s')", $i, $row['b'], $row['c'], $country, $code);
		//print $sql; exit;
		mysql_query($sql);
	}
}
*/


if(isset($_REQUEST['ip']))
	$ip=$_REQUEST['ip'];
else{
	print 'No IP given';
	exit;
}



$octet=explode(".", $ip);

$valid_ip=true;

if(count($octet)!=4)
	$valid_ip&=false;

foreach($octet as $o){
	if($o > 255)
		$valid_ip&=false;
	else if($o < 0)
		$valid_ip&=false;
}

if(!$valid_ip){
	print "Ivalud IP address: $ip";
	exit;
}



$sql=sprintf("select * from kbase.`IP Geo Dimension` where `IP Geo A`=%d and `IP Geo B`=%d and `IP Geo C`=%d ", $octet[0], $octet[1], $octet[2]);

$result=mysql_query($sql);

if($row=mysql_fetch_assoc($result)){
	//print_r($row);
	$country=$row['Country'];
}
else{
	//print "Not found";	
	$country='UNK';

}

print $country;
return $country;


?>