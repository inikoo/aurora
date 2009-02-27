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

$sql="select * from aw_old.location  group by code" ;
 $result=mysql_query($sql);
 while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
   $code=$row['code'];
   if(preg_match('/^(\d+\-\d+\-\d+)$/i',$code))
     $tipo='storing';
   else
     $tipo='picking';
   
   $data=array(
	       'Location Code'=>$code
	       ,'Location Mainly Used For'=>$tipo
	       ,'Location Warehouse Key'=>1
	       ,'Location Area'=>''
	       );
   $location=new Location('code',$code);
   if(!$location->id)
     $location=new Location('create',$data);
   
   

 }
$sql=sprintf("select * from aw_old.product ");
$result=mysql_query($sql);
while($row2=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product_code=$row2['code'];
  $sql="select * from aw_old.location  where product_id=".$row2['id']." order by tipo" ;
  $result2=mysql_query($sql);
  while($row=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    $location_code=$row['code'];
    $location=new Location('code',$location_code);
    // only work if is one to one relation
    
    $product=new Product('code',$product_code);
    $part_skus=$product->get('Parts SKU');
    $sku=$part_skus[0];
    $sql="update `Inventory Spanshot Fact` set `Location Key`=%d where ``    "
    
  }

 }


// $sql="select `Product Code` as code,`Product Key` as id from `Product Dimension` where `Product Same Code Most Recent`='Yes' ";
// $result=mysql_query($sql);
// while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//   $code=$row['code'];
//   $id=$row['id'];

//       $sql=sprintf("select * from aw_old.product where code like '%s'",$code);
//       $res2 = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
//       if($row2=$res2->fetchRow()) {
// 	$old_stock=$row2['stock']*$row2['units'];
// 	$product=new Product($id);
// 	//	$product->update_location(array('tipo'=>'delete_all'));
	
// 	$sql="select * from aw_old.location  where product_id=".$row2['id']." order by tipo" ;
// 	$rowloc =  mysql_query($sql);
// 	$has_location=false;
// 	$primary=true;
// 	while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
// 	  $has_location=true;
// 	  $loc_code=$rowloc['code'];
// 	  if(!preg_match('/^(\d+[abcbdefgh]\d+|\d+\-\d+\-\d+|UPSTAIRS|Production|canteen)$/i',$loc_code)){
// 	    print "$code $old_stock Wrong location $loc_code\n";
// 	    // add stock to unknown
// 	 if($old_stock>0){
// 	   $data=array(
// 		       'location_name'=>'_UNK',
// 		       'is_primary'=>true,
// 		       'user_id'=>0,
// 		       'can_pick'=>true,
// 		       'tipo'=>'associate_location'
// 		       );
// 	   $_res=$product->update_location($data);
// 	   //   print_r($_res);
// 	   $data=array(
// 		       'p2l_id'=>$product->get('pl2_id',array('id'=>1)),
// 		       'qty'=>$old_stock,
// 		       'msg'=>'Value taken from old database',
// 		       'user_id'=>0,
// 		       'tipo'=>'change_qty'
// 		       );
	   
// 	   $product->update_location($data);
// 	 }elseif($old_stock<0 and false){
	   
	   
// 	   $data=array(
// 		       'location_name'=>'_WHL',
// 		       'is_primary'=>true,
// 		       'user_id'=>0,
// 		       'can_pick'=>true,
// 		       'tipo'=>'associate_location'
// 	       );
// 	   $product->update_location($data);
	   
// 	      $data=array(
// 		 'p2l_id'=>$product->get('pl2_id',array('id'=>2)),
// 		 'qty'=>$old_stock,
// 		 'msg'=>'Value taken from old database',
// 		 'user_id'=>0,
// 		 'tipo'=>'change_qty'
// 		 );

// 	   $product->update_location($data);
// 	 }


//        }else{
// 	 // Check if the location already exist
// 	 $location=new Location('name',$loc_code);
// 	 if($location->id){
// 	   // print "location found in new database\n";
// 	 }else{
// 	   //print "Creating location $loc_code\n";

// 	   if(preg_match('/^(\d+\-\d+\-\d+)$/i',$loc_code))
// 	     $_tipo='storing';
// 	   else
// 	     $_tipo='picking';
// 	   $location=new Location('new',array('name'=>$loc_code,'tipo'=>$_tipo));
	   
// 	   //  exit;
// 	 }
	   
	   
// 	 if($primary){
// 	   //  print "$old_stock units  found in $loc_code\n";
// 	   $data=array(
// 		       'location_name'=>$location->get('name'),
// 		       'is_primary'=>true,
// 		       'user_id'=>0,
// 		       'can_pick'=>true,
// 		       'tipo'=>'associate_location'
// 	       );
// 	   $product->update_location($data);
	   
// 	   $p2l_id=$product->get('pl2_id',array('id'=>$location->id));
	   
	   
// 	   $data=array(
// 		 'p2l_id'=>$p2l_id,
// 		 'qty'=>$old_stock,
// 		 'msg'=>'Value taken from old database',
// 		 'user_id'=>0,
// 		 'tipo'=>'change_qty'
// 		 );

// 	   $product->update_location($data);



// 	 }

// 	 else{
// 	   $data=array(
// 		       'location_name'=>$location->get('name'),
// 		       'is_primary'=>false,
// 		       'user_id'=>0,
// 		       'can_pick'=>false,
// 		       'tipo'=>'associate_location'
// 	       );
// 	   // print_r($data);
// 	   $product->update_location($data);
// 	 }
// 	 $primary=false;
//        }
//      }
       

     
     
//      if(!$has_location){
//         if($old_stock>0){
	  
// 	     $data=array(
// 		       'location_name'=>'_UNK',
// 		       'is_primary'=>true,
// 		       'user_id'=>0,
// 		       'can_pick'=>true,
// 		       'tipo'=>'associate_location'
// 	       );
// 	   $product->update_location($data);
	   
// 	      $data=array(
// 			  'p2l_id'=>$product->get('pl2_id',array('id'=>1)),
// 		 'qty'=>$old_stock,
// 		 'msg'=>'Value taken from old database',
// 		 'user_id'=>0,
// 		 'tipo'=>'change_qty'
// 		 );
// 	      // print_r($data);
// 	      $product->update_location($data);

// 	      //xit("caca\n");
// 	 }elseif($old_stock<0 and false){
	   
	   
// 	   $data=array(
// 		       'location_name'=>'_WHL',
// 		       'is_primary'=>true,
// 		       'user_id'=>0,
// 		       'can_pick'=>true,
// 		       'tipo'=>'associate_location'
// 	       );
// 	   $_res=$product->update_location($data);
// 	   // print_r($_res);
// 	      $data=array(
// 		 'p2l_id'=>$product->get('pl2_id',array('id'=>2)),
// 		 'qty'=>$old_stock,
// 		 'msg'=>'Value taken from old database',
// 		 'user_id'=>0,
// 		 'tipo'=>'change_qty'
// 		 );

// 	   $_res=$product->update_location($data);
// 	   //  print_r($data);
// 	   // print_r($_res);
// 	   //exit;
// 	}
//      }
//   }

    


 

//   //print " $id\r";
//     }



?>