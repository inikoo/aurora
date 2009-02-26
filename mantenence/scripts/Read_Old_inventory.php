<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Part.php');
include_once('../../classes/SupplierProduct.php');
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
$not_found=00;

$sql="delete from  `Inventory Transition Fact` where (`Inventory Transaction Type`='Audit' or `Inventory Transaction Type`='In' ) ";
mysql_query($sql);

$sql="select code,product_id,aw_old.in_out.date,aw_old.in_out.tipo,aw_old.in_out.quantity ,aw_old.in_out.notes from aw_old.in_out left join aw_old.product on (product.id=product_id) where product.code is not null and (aw_old.in_out.tipo=2 or aw_old.in_out.tipo=1)   order by product.id,date ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  
  $date=$row['date'];
  $code=$row['code'];
  $tipo=$row['tipo'];
  $qty=$row['quantity'];
  $notes=$row['notes'];
  $sql=sprintf("select `Product ID` from `Product Dimension` P where   `Product Code`=%s and `Product Same ID Valid From`<=%s and `Product Same ID Valid To`>=%s order by `Product Same ID Valid To` desc ",prepare_mysql($code),prepare_mysql($date),prepare_mysql($date));
  $result2=mysql_query($sql);
  // print "$sql\n";
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    $product_ID=$row2['Product ID'];

    $sql=sprintf("select `Part SKU`,`Parts Per Product` from `Product Part List` where `Product ID`=%s  ",prepare_mysql($product_ID));
    // print "$sql\n";
    $result3=mysql_query($sql);
    $num = mysql_num_rows($result3);
    if($num!=1)
      exit ("no ideal product");
    
    if($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ){
      $part_sku=$row3['Part SKU'];
      $parts_per_product=$row3['Parts Per Product'];
    }
    
    
    $cost_per_part=get_cost($part_sku,$date);
    
   
    //print "$code $date $part_sku\n "; 
    
    if($tipo==2){

      $sql=sprintf("insert into `Inventory Transition Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Transaction Metadata`) values (%s,%s,'Audit',%s,%s,%s)",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
      // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transition Fact ");
    }else{
      
      
      $sql=sprintf("insert into `Inventory Transition Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Transaction Metadata`) values (%s,%s,'In',%s,%s,%s)",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
      // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transition Fact ");


    }
    
    continue;
  }
  // if the audit is ager the last 



  $sql=sprintf("select `Product ID` from `Product Dimension` P where   `Product Code`=%s and `Product Same ID Valid To`<=%s order by `Product Same ID Valid To` desc ",prepare_mysql($code),prepare_mysql($date),prepare_mysql($date));
  $result2=mysql_query($sql);
  // print "$sql\n";
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){

    $product_ID=$row2['Product ID'];

    $sql=sprintf("select `Part SKU`,`Parts Per Product` from `Product Part List` where `Product ID`=%s  ",prepare_mysql($product_ID));
    // print "$sql\n";
    $result3=mysql_query($sql);
    $num = mysql_num_rows($result3);
    if($num!=1)
      exit ("no ideal product");
    
    if($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ){
      $part_sku=$row3['Part SKU'];
      $parts_per_product=$row3['Parts Per Product'];
    }
    
    
    $cost_per_part=get_cost($part_sku,$date);
    
   

    if($tipo==2){

      $sql=sprintf("insert into `Inventory Transition Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Transaction Metadata`) values (%s,%s,'Audit',%s,%s,%s)",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
      // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transition Fact ");
    }else{
      
      
      $sql=sprintf("insert into `Inventory Transition Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Transaction Metadata`) values (%s,%s,'In',%s,%s,%s)",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
      // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transition Fact ");


    }
    
    continue;
  }








 }











  







function get_cost($part_sku,$date){


   $sql=sprintf(" select AVG(SPD.`Supplier Product Cost` * SPPL.`Supplier Product Units Per Part`) as cost from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%s  and `Supplier Product Valid To`>=%s and  `Supplier Product Valid From`<=%s    ",prepare_mysql($part_sku),prepare_mysql($date),prepare_mysql($date));
   // print "\n\n\n\n$sql\n";
   $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    if(is_numeric($row['cost']))
    return $row['cost'];
  }


  $sql=sprintf(" select AVG(SPD.`Supplier Product Cost` * SPPL.`Supplier Product Units Per Part`) as cost from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%s  and `Supplier Product Valid To`<=%s limit 1 ",prepare_mysql($part_sku),prepare_mysql($date));

 $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    if(is_numeric($row['cost']))
      return $row['cost'];
  }

 $sql=sprintf(" select AVG(SPD.`Supplier Product Cost` * SPPL.`Supplier Product Units Per Part`) as cost from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%s  order by  `Supplier Product Valid To` desc ",prepare_mysql($part_sku),prepare_mysql($date));
 // print "\n\n\n\n$sql\n";
 $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    if(is_numeric($row['cost']))
      return $row['cost'];
  }


  exit("error can no found supp ciost\n");


}

$sql="delete  from `Inventory Transition Fact`  where `Inventory Transaction Type`='Not Found' ";
mysql_query($sql);


$sql="select `No Shipped Due Out of Stock`,`Invoice Date`,`Product ID` from `Order Transaction Fact` OTF left join `Product Dimension` PD  on  (PD.`Product Key`=OTF.`Product Key`)  where `No Shipped Due Out of Stock`>0;";
$resultx=mysql_query($sql);
while($rowx=mysql_fetch_array($resultx, MYSQL_ASSOC)   ){
  $product_id=$rowx['Product ID'];
  $notes='';

  $sql=sprintf(" select `Part SKU` from  `Product Part List`  where `Product ID`=%d and `Product Part Valid From`<%s  and `Product Part Valid To`>%s ",$product_id,prepare_mysql($rowx['Invoice Date']),prepare_mysql($rowx['Invoice Date']));
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $sql=sprintf("insert into `Inventory Transition Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Transaction Metadata`) values (%s,%s,'Not Found',%s,%s,%s)",prepare_mysql($rowx['Invoice Date']),prepare_mysql($row['Part SKU']),0,0,prepare_mysql($notes));
    // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transition Fact ");

  }

 }

  


?>

