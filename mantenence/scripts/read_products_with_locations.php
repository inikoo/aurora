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





$sql=sprintf("select * from aw_old.product    order by code   ");

//$sql=sprintf("select * from aw_old.product  order by code   ");
$result=mysql_query($sql);
while ($row2=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
    $product_code=$row2['code'];
    $stock_old_db=$row2['stock'];
    print $row2['id']." $product_code \n";
    $sql="select * from aw_old.location  where product_id=".$row2['id']."    " ;
    $result2xxx=mysql_query($sql);
    $primary=true;




    while ($row=mysql_fetch_array($result2xxx, MYSQL_ASSOC)   ) {
        $location_code=$row['code'];
        //  print "$product_code $location_code\n";

        $used_for='Picking';
        if (preg_match('/\d+\-\d+\-\d+/',$location_code))
            $used_for='Storing';
        // $location=new Location('code',$location_code);
        //  if(!$location->id){
        $location_data=array(
                           'Location Warehouse Key'=>1,
                           'Location Warehouse Area Key'=>1,
                           'Location Code'=>$location_code,
                           'Location Mainly Used For'=>$used_for
                       );
        $location=new Location('find',$location_data,'create');


        //}
        //     // only work if is one to one relation



        $product=new Product('code_store',$product_code,1);
        if ($product->data['Product Record Type']=='Historic')
            continue;
        //  print "Product  ".$product->data['Product Record Type']." \n";


        if ($product->id and $location->id) {

            $part_skus=$product->get_current_part_list();
            if (count($part_skus)!=1) {


                if ($product->code=='SG-mix' or $product->code=='PI-09' ) {

                    continue;
                } else {

                    print"Product has more than ne sku\n";
                    print_r($part_skus);
                    print $product->code."\n";
                     print $product->pid."\n";
                    exit('error');
                }
            }

                
           // continue;
            $tmp=array_pop($part_skus);
            $sku=$tmp['Part SKU'];
            print "P: $product_code $location_code $used_for Stock: $stock_old_db\n";


            if ($used_for=='Picking') {
                print "PRIMARY Loc Name:".$row['code']." $product_code  LOC: ".$location->id." SKU: $sku \n";

                //wrap it again

                // print "wraping sku $sku\n";
                // wrap_it($sku);



                $part= new Part($sku);
                //	 $part->load('calculate_stock_history','last');


                print "--------------\n";
                $associated=$part->get('Current Associated Locations');
                $num_associated=count($associated);
                print_r($associated);
                print "Num associated $num_associated\n";


                $part_location=new PartLocation('find',array('Part SKU'=>$sku,'Location Key'=>$location->id),'create');
                $part_location->update_can_pick('Yes');
                $note='xxx';
                foreach($associated as  $key=>$location_key) {
                    $part_location=new PartLocation($sku.'_'.$location_key);
                    $data=array(
                              'user key'=>0,
                              'note_out'=>'',
                              'note_associate'=>'',
                              'note_in'=>$note,

                              'Destination Key'=>$location->id,
                              'Quantity To Move'=>'all'
                          );
                    $part_location->move_stock($data);
                    print "Message:".$part_location->msg."\n";
                }


                //exit("debug 124\n");

            } else {
                print "STORING ".$row['code']." $product_code  LOC: ".$location->id." SKU: $sku \n";
                $part_location=new PartLocation('find',array('Part SKU'=>$sku,'Location Key'=>$location->id),'create');

                //$part_location->associate();


// $location->load('parts_data');
            }
            $primary=false;


        }
  
    }
    mysql_free_result($result2xxx);

}
mysql_free_result($result);


?>