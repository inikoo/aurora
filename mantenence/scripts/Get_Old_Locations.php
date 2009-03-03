<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Part.php');
include_once('../../classes/SupplierProduct.php');
include_once('../../classes/Location.php');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');

// $sql="select * from aw_old.location  group by code" ;
//  $result=mysql_query($sql);
//  while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//    $code=$row['code'];
//    if(preg_match('/^(\d+\-\d+\-\d+)$/i',$code))
//      $tipo='storing';
//    else
//      $tipo='picking';
   
//    $data=array(
// 	       'Location Code'=>$code
// 	       ,'Location Mainly Used For'=>$tipo
// 	       ,'Location Warehouse Key'=>1
// 	       ,'Location Area'=>''
// 	       );
//    $location=new Location('code',$code);
//    if(!$location->id)
//      $location=new Location('create',$data);
   
   

//  }
$sql=sprintf("select * from aw_old.product ");
$result=mysql_query($sql);
while($row2=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product_code=$row2['code'];
  $sql="select * from aw_old.location  where product_id=".$row2['id']."  and code like '111-01'   order by tipo" ;
  $result2xxx=mysql_query($sql);
  $primary=true;

  


  while($row=mysql_fetch_array($result2xxx, MYSQL_ASSOC)   ){
    
     $location_code=$row['code'];


     $used_for='Picking';
     if(preg_match('/\d\-\d+\-\d/',$location_code))
       $used_for='Storing';
     $location=new Location('code',$location_code);
     if(!$location->id){
       
       $location=new Location('create',array(
					     'Location Warehouse Key'=>1
					     ,'Location Area'=>''
				    ,'Location Code'=>$location_code
					     ,'Location Mainly Used For'=>$used_for
					     ));
     }
     //     // only work if is one to one relation
     


     $product=new Product('code',$product_code);
     if($product->id and $location->id){

       $part_skus=$product->get('Parts SKU');
       if(count($part_skus)!=1){
	 print_r($product->data);
	 exit();
       }


      
       $sku=$part_skus[0];

       print $row['code']." $product_code  $sku \n";

       if($primary){
 	$sql=sprintf("update `Inventory Spanshot Fact` set `Location Key`=%d where `Date`=%s and `Part SKU`=%d "
 		     ,$location->id
 		     ,prepare_mysql(date("Y-m-d"))
 		   ,$sku
 		     );
 	//print "$sql\n";
	if(!mysql_query($sql))
 	  exit("$sql\n Error con no update pick location\n");
	//	print "$sql\n";
	$location->load('parts_data');
//       //print_r($location->data);
 	$primary=false;
       }
      
     }
    
  }

 }

$location=new Location('id',1);
$location->load('parts_data');

?>