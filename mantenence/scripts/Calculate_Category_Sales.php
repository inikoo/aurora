<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Category.php');
include_once('../../class.Node.php');

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

global $myconf;


$category=new Category(201);

$category->update_up_today();

exit;

/* $sql="select * from `Product Dimension` where `Product Store Key`=1"; */
/* $result=mysql_query($sql); */
/* while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){ */
/*   $product=new Product('pid',$row['Product ID']); */
/*   $product->load_categories(); */
/*   $code=$row['Product Code']; */
/*   $sql=sprintf("select `Product ID` from `Product Dimension` where `Product Store Key` in (2,3) and `Product Code`=%s ",prepare_mysql($code)); */
/*   //print "$sql\n"; */
/*   //print_r($product->categories); */
/*   $result2=mysql_query($sql); */
/*   while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){ */
/*     $_product=new Product('pid',$row2['Product ID']); */
/*     foreach($product->categories as $key=>$value  ){ */
/*       print $_product->data['Product Name']." $key\n"; */
/*       $_product->add_category($key); */
/*     } */
/*   } */
  
/* } */
/* $sql="select * from `Category Dimension`"; */
/* $result=mysql_query($sql); */
/* while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){ */
/*  $category=new Category($row['Category Key']); */
/*  $category->load('product_data'); */
/* } */











$nodes=new Nodes('`Category Dimension`');
//$data=array('`Category Code`'=>'Jewellery');
//$nodes->add_new(1 , $data);
//$data=array('`Category Code`'=>'Shop Accesories');
//$nodes->add_new(1 , $data);

$sql="delete from `Category Bridge` where `Subject`='Product' ";
mysql_query($sql);
$sql="delete from `Product Category Dimension`";
$result=mysql_query($sql);

$sql="select * from `Category Dimension`";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $sql="select * from `Store Dimension`";
  $result2=mysql_query($sql);
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    $sql=sprintf("insert into `Product Category Dimension` (`Product Category Key`,`Product Category Store Key`,`Product Category Currency Code`) values (%d,%d,%s)"
		 ,$row['Category Key']
		
		 ,$row2['Store Key']
		 ,prepare_mysql($row2['Store Currency Code'])
		 );
    mysql_query($sql);
   
  }

}



//make defaults;
$sql="select * from `Product Dimension`";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product= new Product('pid',$row['Product ID']);
  $sql2="select * from `Category Dimension` where `Category Default`='Yes' and `Category Subject`='Product'";
  $result2=mysql_query($sql2);
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    $product->add_category($row2['Category Key']);
  }
  
}


//use things
$sql="select `Product ID` from `Product Dimension` where ( `Product Name` like '%candle%' and `Product Name` not  like '%holder%'  ) or `Product Main Department Name` like '%candle%' and `Product Store Key`=1  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->add_category(5);
}
$sql="select `Product Main Department Name`,`Product Code`,`Product Name`,`Product ID` from `Product Dimension` where( ( `Product Name` like '%incense%' and `Product Name` not  like '%holder%'  and `Product Name` not  like '%burner%' ) or `Product Main Department Name` like '%incense%' and `Product Family Code` not in ('ish','fo','fob')) and `Product Store Key`=1  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->add_category(7);
}

$sql="select `Product ID` from `Product Dimension` where (( `Product Name` like '%soap %' or  `Product Name` like '%soaps %'  or  `Product Name` like '% soap' or `Product Name` like '%shampoo%' ) and `Product Name` not  like '%soapstone%'  )  and `Product Store Key`=1   ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->add_category(6);
}

$sql="select `Product Code`,`Product Name`,`Product ID` from `Product Dimension` where (( `Product Name` like '%wraping%' or  `Product Name` like '%carrier%'  or  `Product Name` like '%display stand%' or `Product Name` like '% stand' )   or `Product Family Code`  in ('rds','shop')   ) and `Product Store Key`=1   ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->add_category(26);
}

$sql="select `Product ID` from `Product Dimension` where  `Product Main Department Name` like '%jewellery%'   and `Product Store Key`=1   ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->add_category(25);
}





$sql="select `Product ID` from `Product Dimension` where (( `Product Name` like '%oil burner%' or  `Product Name` like '%energy%'  or  `Product Name` like '%holistic%'  or  `Product Name` like '%relaxing%'   or  `Product Name` like '%pendulum%' or `Product Name` like '%fragance%'  or `Product Name` like '%chakra%'  ) or  `Product Main Department Name` like '%aromat%'  or  `Product Main Department Name` like '%relaxing%' )  and `Product Store Key`=1   ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->add_category(8);
}


$sql="select `Product ID` from `Product Dimension` where ( ( `Product Name` like '%figure%' or  `Product Name` like '%wall%'   ) or  `Product Main Department Name` like '%collectables%' )  and `Product Store Key`=1   ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->add_category(10);
}
$sql="select `Product ID` from `Product Dimension` where (  `Product Main Department Name` like '%bath%' )  and `Product Store Key`=1   ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->add_category(9);
}


//wood things
$sql="select `Product ID` from `Product Dimension` where `Product Name` like '%wood%' or `Product Main Department Name` like '%wood%' and `Product Store Key`=1  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->add_category(12);
}











$sql="select * from `Product Dimension` where `Product Store Key`=1";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->load_categories();
  $code=$row['Product Code'];
  $sql=sprintf("select `Product ID` from `Product Dimension` where `Product Store Key` in (2,3) and `Product Code`=%s ",prepare_mysql($code));
  $result2=mysql_query($sql);
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    $_product=new Product('pid',$row2['Product ID']);
    foreach($product->categories as $key=>$value  )
      $_product->add_category($key);
    
  }
  
}
$sql="select * from `Category Dimension`";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
 $category=new Category($row['Category Key']);
 $category->load('product_data');
}

exit;



$sql="select * from `Category Dimension`";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $sql="select * from `Store Dimension`";
  $result2=mysql_query($sql);
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    $sql=sprintf("insert into `Product Category Dimension` (`Product Category Key`,`Product Category Store Key`,`Product Category Currency Code`) values (%d,%d,%s)"
		 ,$row['Category Key']
		
		 ,$row2['Store Key']
		 ,prepare_mysql($row2['Store Currency Code'])
		 );
    mysql_query($sql);
   
  }

  
  $category=new Category($row['Category Key']);

  $category->load('sales');
} 


  //  $category->load('products_info');
  //print $category->id."\r";

mysql_free_result($result);


?>