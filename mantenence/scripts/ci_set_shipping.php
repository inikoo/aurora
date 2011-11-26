<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Deal.php');
include_once('../../class.Charge.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Shipping.php');

include_once('../../class.Part.php');
include_once('../../class.Warehouse.php');
include_once('../../class.Node.php');
include_once('../../class.Shipping.php');
include_once('../../class.SupplierProduct.php');

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


$codigos=array();
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$max_cost=-1;
$sql="select `World Region`  from kbase.`Country Dimension` group by `World Region`  ";
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
    $world_region=$row['World Region'];
    //$countries=$row['countries'];
    $wr_key=$row['World Region'];



    $data=array('Shipping Type'=>'Normal',
                'Shipping Destination Type'=>'World Region',
                'Shipping Destination Code'=>$wr_key,
                'Shipping Price Method'=>'On Request',
                'Shipping Allowance Metadata'=>''
               );

    $shipping=new Shipping('find create',$data);

    //print "$world_region $cost $max_cost\n";
}





$sql="select *  from kbase.`Country Dimension`   ";
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {


    if ($row['Country Code']=='ESP') {
            $cost='0,250,10;250,,0';
        $data=array('Shipping Type'=>'Normal',
        'Shipping Destination Type'=>'Country',
        'Shipping Destination Code'=>$row['Country Code'],
        'Shipping Price Method'=>'Step Order Items Gross Amount',
        'Shipping Metadata'=>$cost
        );

        $shipping=new Shipping('find create',$data);

    } else {
        $data=array('Shipping Type'=>'Normal',
        'Shipping Destination Type'=>'Country',
        'Shipping Destination Code'=>$row['Country Code'],
        'Shipping Price Method'=>'On Request');

       $shipping=new Shipping('find create',$data);

    }


}


?>