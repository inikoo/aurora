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
error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();

//Coca-cola avoid it!, what about today (i can use one of that face mask to avoid infection), but i couldnt make it until 19:45 odeon or 20:20 showroom, if not  sat or sun at around 5  

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  


require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           

global $myconf;

$myFile = "currency_codes";
$fh = fopen($myFile, 'r');
$theData = fread($fh, filesize($myFile));
fclose($fh);
$data_lines=preg_split('/\n/',$theData);
$codes=array();
foreach($data_lines as $line){
$code='';
if(preg_match('/^[A-Z]{3}/',$line,$match)){
    $code=$match[0];
    $line=preg_replace("/^$code/",'',$line);
    $line=_trim($line);
    $notes='';
    if(preg_match('/\(.*\)$/',$line,$match)){
      $notes=$match[0];
      $line=preg_replace("/(.*\)$/",'',$line);
    }
    preg_split('/,/',$lines)
    
    $codes[$code]=array('Country'=>$country,'Name'=>$name,'Notes'=>$notes);
    
}
}
print_r($codes);


?>