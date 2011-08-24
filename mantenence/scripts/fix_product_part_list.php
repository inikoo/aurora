<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
date_default_timezone_set('UTC');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

//  $this->update_historic_sales_data();
 //     $this->update_sales_data();
  //    $this->update_same_code_sales_data();


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
//$stores=array(1,2,3);


 $sql="select `Product ID` from `Product Dimension` ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){
 $product=new Product('pid',$row['Product ID']);
 
 
 $current_part_skus=$product->get_current_part_skus();
if(count($current_part_skus)==0){

    
    $sql=sprintf("select *from `Product Part Dimension` where `Product ID`=%d  order by `Product Part Valid From`  desc limit 1 "
                     ,$product->pid
                    
                    );
        
        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc ($res)) {
                $product->set_part_list_as_current($row['Product Part Key']); 
                $current_part_skus=$product->get_current_part_skus();
                            print $product->pid."\t\t  ".$product->data['Product Store Key']."  ".$product->data['Product Code']."  fixed\n";

               // print_r($current_part_skus);
        }else{
     
           $uk_product=new Product('code_store',$product->data['Product Code'],1);
                $parts=$uk_product->get('Parts SKU');


                if (isset($parts[0])) {
                    // print "found part \n";
                    $part=new Part('sku',$parts[0]);


                    $part_list[]=array(

                                     'Part SKU'=>$part->get('Part SKU'),

                                     'Parts Per Product'=>1,
                                     'Product Part Type'=>'Simple'

                                 );
                    $product_part_header=array(
                                             'Product Part Valid From'=>date('Y-m-d H:i:s'),
                                             'Product Part Valid To'=>date('Y-m-d H:i:s'),
                                             'Product Part Most Recent'=>'Yes',
                                             'Product Part Type'=>'Simple'

                                         );


                    $product->new_historic_part_list($product_part_header,$part_list);


                } else {
                            print $product->pid."\t\t  ".$product->data['Product Store Key']."  ".$product->data['Product Code']."   no part list in uk!!! fix it\n";

                }
     
     
     }
}
 
 $product->update_parts();
 

    
   // print $row['Product ID']."\t\t ".$product->data['Product Code']." \r";

}


 



?>
