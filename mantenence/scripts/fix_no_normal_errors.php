<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.PartLocation.php');

include_once('../../class.SupplierProduct.php');
date_default_timezone_set('UTC');

error_reporting(E_ALL);
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


$sql=sprintf("select * from `Product Dimension`   ");
$result2a=mysql_query($sql);
while ($row=mysql_fetch_array($result2a, MYSQL_ASSOC)   ) {

    $family=new family($row['Product Family Key']);
    $department=new  department($row['Product Main Department Key']);
    $sql=sprintf("update `Product Dimension` set `Product Family Code`=%s, `Product Family Name`=%s ,`Product Main Department Code`=%s, `Product Main Department Name`=%s   where `Product ID`=%d",
                 prepare_mysql($family->data['Product Family Code']),
                 prepare_mysql($family->data['Product Family Name']),
                 prepare_mysql($department->data['Product Department Code']),
                 prepare_mysql($department->data['Product Department Name']),
                 $row['Product ID']
                );
    mysql_query($sql);
   // print "$sql\n";

}



$sql=sprintf("select `Order Transaction Fact Key`,`Product ID` from `Order Transaction Fact`   ");
$result2a=mysql_query($sql);
while ($row=mysql_fetch_array($result2a, MYSQL_ASSOC)   ) {

    $product=new product('pid',$row['Product ID']);
    $sql=sprintf("update `Order Transaction Fact` set `Product Family Key`=%d, `Product Department Key`=%d   where `Order Transaction Fact Key`=%d",
                 $product->data['Product Family Key'],
                 $product->data['Product Main Department Key'],
                  $row['Order Transaction Fact Key']
                );
 mysql_query($sql);
   // print "$sql\n";
   // exit;


}















?>