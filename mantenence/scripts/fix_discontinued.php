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

$sql=sprintf("select `Product Code` from `Product Dimension` where `Product Code` like '%%-bonus' and `Product Store Key`=1  ");
$result2a=mysql_query($sql);
while ($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ) {

   


    $product=new Product('code_store',$row2a['Product Code'],1);
    if ($product->id) {
        $current_part_skus=$product->get_current_part_skus();


        foreach($current_part_skus as $_part_sku) {
            $part=new Part($_part_sku);
            //$part->update_status('Not In Use');
            
            $supplier_products=$part->get_supplier_products();
            
            foreach($supplier_products as $supplier_product){
                $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Status`='Not In Use' where `Supplier Product ID`=%d",
                $supplier_product['Supplier Product ID']
                );
                mysql_query($sql);
                //print "$sql\n";
                $sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part In Use`='No' where `Supplier Product Part Key`=%d",
                $supplier_product['Supplier Product Part Key']
                );
                mysql_query($sql);
              //  print "$sql\n";
                
            }
            
            $part->update_availability();
            
             if($part->data['Part Current Stock']<=0){
    
    $part->update_status('Not In Use');
    }else{
    
    
    }
            
            
        }
    }




 




}






?>