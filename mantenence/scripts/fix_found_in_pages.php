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


$sql='truncate  `Page Store Found In Bridge` ';
mysql_query($sql);

$sql=sprintf("select * from `Page Store Dimension`   ");
$result2a=mysql_query($sql);
while ($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ) {

    $page=new Page('site_code',$row2a['Page Site Key'],$row2a['Page Code']);

    switch ($page->data['Page Store Section']) {
    case('Department Catalogue'):

        $department=new Department( $page->data['Page Parent Key']);

        $sql=sprintf("update `Page Store Dimension` set `Page Parent Code`=%s where `Page Key`=%d",
                     prepare_mysql($department->data['Product Department Code']),
                     $page->data['Page Key']);
        mysql_query($sql);

    case 'Family Catalogue':

        $family=new Family( $page->data['Page Parent Key']);

        $sql=sprintf("update `Page Store Dimension` set `Page Parent Code`=%s where `Page Key`=%d",
                     prepare_mysql($family->data['Product Family Code']),
                     $page->data['Page Key']);
        mysql_query($sql);

        $sql=sprintf("select `Product Family Main Department Key` from  `Product Family Dimension` where `Product Family Key`=%d",
                     $page->data['Page Parent Key']);

        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {
            $department_key=$row['Product Family Main Department Key'];

        } else
            $department_key=0;

        $sql=sprintf('select PS.`Page Key`,`Page URL`,`Page Short Title` from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Store Section`="Department Catalogue" and PS.`Page Parent Key`=%d',
                     $department_key
                    );

        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {
            $found_in_key=$row['Page Key'];
            $sql=sprintf("insert into `Page Store Found In Bridge` values (%d,%d)  ",$page->id,$found_in_key);
            //print "$sql\n";
            mysql_query($sql);
        } 
        
        
         $page->update_see_also();   
        
        
        break;
    default:

        break;
    }




}















?>