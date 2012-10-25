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

$sql=sprintf("select * from `Deal Dimension` ");
$result2a=mysql_query($sql);
while ($row=mysql_fetch_array($result2a, MYSQL_ASSOC)   ) {

$deal=new Deal($row['Deal Key']);
$deal->update_number_metadeals();

}
exit;

// Maybe the rest is ok to do

$sql="delete from `Deal Dimension` where `Deal Code` like '%BOGOF%'   or `Deal Code` like '%FShip%' or  `Deal Code` like '%Vol%'";
mysql_query($sql);

$sql="update `Deal Dimension` set `Deal Terms Object`='Family'  where `Deal Code` like '%Vol%'   or `Deal Code` like '%BOGOF%' ";
mysql_query($sql);

$sql=sprintf("select * from `Deal Metadata Dimension` ");
$result2a=mysql_query($sql);
while ($row=mysql_fetch_array($result2a, MYSQL_ASSOC)   ) {

    $store=new Store($row['Store Key']);
    $deal_key=false;
    if ($row['Deal Metadata Terms Type']=='Order Interval') {

        $sql=sprintf("select `Deal Key` from `Deal Dimension` where `Deal Code`='Oro'");
        $result=mysql_query($sql);
        if ($row2=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

            $deal_key=$row2['Deal Key'];

        }

    }
    elseif($row['Deal Metadata Terms Type']=='Family Quantity Ordered' and $row['Deal Metadata Allowance Type']=='Percentage Off' and  $row['Deal Metadata Trigger']=='Family') {
// need to create the Deal;

        $family=new Family($row['Deal Metadata Trigger Key']);
        $sql=sprintf("insert into `Deal Dimension` (`Deal Code`,`Store Key`,`Deal Name`,`Deal Description`) values (%s,%d,%s,%s)",
                     prepare_mysql($store->data['Store Code'].'.Vol.'.$family->data['Product Family Code']),
                     $store->id,
                     prepare_mysql($family->data['Product Family Code'].' Volumen Discount'),
                     prepare_mysql(sprintf("%s when order more than %d picks in %s family",$row['Deal Metadata Allowance Description'],$row['Deal Metadata Terms'],$family->data['Product Family Code']))


                    );
        mysql_query($sql);
        $deal_key=mysql_insert_id();
//print "$sql\n";

    }
    elseif($row['Deal Metadata Allowance Target']=='Shipping') {
        $sql=sprintf("delete from `Deal Metadata Dimension` where `Deal Metadata Key`=%d",$row['Deal Metadata Key']);
        mysql_query($sql);



    }
    elseif($row['Deal Metadata Terms Type']=='Product Quantity Ordered' and $row['Deal Metadata Allowance Description']=='get 1 free' and  $row['Deal Metadata Trigger']=='Family') {


        $family=new Family($row['Deal Metadata Trigger Key']);
        $sql=sprintf("insert into `Deal Dimension` (`Deal Code`,`Store Key`,`Deal Name`,`Deal Description`) values (%s,%d,%s,%s)",
                     prepare_mysql($store->data['Store Code'].'.BOGOF.'.$family->data['Product Family Code']),
                     $store->id,
                     prepare_mysql($family->data['Product Family Code'].' BOGOF'),
                     prepare_mysql(sprintf("Buy one Get one Free when order any product in %s family",$family->data['Product Family Code']))


                    );
        mysql_query($sql);
        $deal_key=mysql_insert_id();
//print "$sql\n";

    }

    if ($deal_key) {
        $sql=sprintf("update `Deal Metadata Dimension` set `Deal Key`=%d where `Deal Metadata Key`=%d",
                     $deal_key,
                     $row['Deal Metadata Key']
                    );
        mysql_query($sql);
    }

}















?>