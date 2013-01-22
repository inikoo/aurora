<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;

$sql="select * from `Supplier Dimension`";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

    $supplier=new Supplier($row['Supplier Key']);


    $sql=sprintf("select min(`Date`) as date from  `Inventory Transaction Fact` ITF left join `Supplier Product Dimension` SPD on (ITF.`Supplier Product ID`=SPD.`Supplier Product ID`) where `Supplier Key`=%d " ,
                 $supplier->id

                );
    //print "$sql\n";
    $result2=mysql_query($sql);


    if ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {

      $date=$row2['date'];
      if($date=='')
        $date=date("Y-m-d H:i:s");
      
    }else{
    $date=date("Y-m-d H:i:s");
    }
    
      $sql=sprintf("update `Supplier Dimension` set `Supplier Valid From`=%s where `Supplier Key`=%d ",

                     prepare_mysql($date),
                     $supplier->id

                    );

        mysql_query($sql);
        print "$sql\n";
    

}


?>