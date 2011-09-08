<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.Location.php');
include_once('../../class.PartLocation.php');


error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');






$sql=sprintf("select * from aw_old.location group by code;  ");

$result=mysql_query($sql);
while ($row2=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
$location_code=_trim($row2['code']);

//if (!preg_match('/^\d+[a-z]\d+$/i',$location_code))
//continue;
if (!preg_match('/^(\d+\-\d+\-\d+|\d+[a-z]+\d+|[a-z]\-[a-z]\d|[a-z]{2}\d|\d{3\-\d{2}}|\d{1,2}\-\d{1,2}[a-z]\d|\d{1,3}\-\d{1,3})$/i',$location_code)

or !preg_match('/^\d+[a-z]$/',$location_code)

){
print "$location_code\n";
continue;
}
//print "$location_code\n";



if (preg_match('/^\d+\-\d+\-\d+$/',$location_code))
            $used_for='Storing';
  else
  $used_for='Picking';
    
        // $location=new Location('code',$location_code);
        //  if(!$location->id){
        $location_data=array(
                           'Location Warehouse Key'=>1,
                           'Location Warehouse Area Key'=>1,
                           'Location Code'=>$location_code,
                           'Location Mainly Used For'=>$used_for
                       );
            
        $location=new Location('find',$location_data,'create');
}
mysql_free_result($result);




?>