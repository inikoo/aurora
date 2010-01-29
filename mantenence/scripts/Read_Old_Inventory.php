<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.PartLocation.php');

error_reporting(E_ALL);
error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('Europe/London');
$not_found=00;


$sql=
"INSERT INTO `dw`.`Location Dimension` (
`Location Key` ,
`Location Warehouse Key` ,
`Location Warehouse Area Key` ,

`Location Code` ,
`Location Mainly Used For` ,
`Location Max Weight` ,
`Location Max Volume` ,
`Location Max Slots` ,
`Location Distinct Parts` ,
`Location Has Stock` ,
`Location Stock Value`
)
VALUES (
'1', '1', '1','Unknown', 'Picking', NULL , NULL , NULL , '0', 'Unknown', '0.00'
);";


$loc= new Location(1);
if(!$loc->id)
  mysql_query($sql);


$wa_data=array(
'Warehouse Area Name'=>'Unknown'
,'Warehouse Area Code'=>'Unk'
,'Warehouse Key'=>1
);

$wa=new WarehouseArea('find',$wa_data,'create');


$sql="delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Audit','In','Associate','Disassociate','Move In','Move Out','Adjust','Not Found','Lost','Broken') ";
mysql_query($sql);
$sql="delete  from `Part Location Dimension`  ";
mysql_query($sql);
$sql="delete  from `Location Dimension` where `Location Key`>1 ";
mysql_query($sql);

print "Getting data from the oold database\n";

$sql="select code,product_id,aw_old.in_out.date,aw_old.in_out.tipo,aw_old.in_out.quantity ,aw_old.in_out.notes from aw_old.in_out left join aw_old.product on (product.id=product_id) where product.code is not null and (aw_old.in_out.tipo=2 or aw_old.in_out.tipo=1)   order by product.id,date ";
$result=mysql_query($sql);

while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  
  $date=$row['date'];
  $code=$row['code'];
  //   print $sql;
 
  $tipo=$row['tipo'];
 print $row['product_id']." $code     $tipo           \r";
  $qty=$row['quantity'];
  $notes=$row['notes'];
  $sql=sprintf("select `Product ID` from `Product Dimension` P where   `Product Code`=%s and `Product Valid From`<=%s and `Product Valid To`>=%s order by `Product Valid To` desc ",prepare_mysql($code),prepare_mysql($date),prepare_mysql($date));
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
    
    $part=new Part($part_sku);
    $cost_per_part=$part->get_unit_cost($date);
    //$sp_id=get_sp_id($part_sku,$date);
    //$sp=new SupplierProduct('')
    //print "$code $date $part_sku\n "; 
    
    if($tipo==2){

      $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%s,'Audit',%s,%s,%s,'','Normal')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
      //       print "A: $sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transaction Fact ");
    }else{
      
      
      $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%s,'In',%s,%s,%s,'','Normal')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
      // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transaction Fact ");


    }
    
    continue;
  }
  // if the audit is ager the last 





  $sql=sprintf("select `Product ID` from `Product Dimension` P where   `Product Code`=%s and `Product Valid To`<=%s order by `Product Valid To` desc ",prepare_mysql($code),prepare_mysql($date));
  $result2=mysql_query($sql);

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
    $part=new Part($part_sku);
    $cost_per_part=$part->get_unit_cost($date);
    
    // $cost_per_part=get_cost($part_sku,$date);
    //$sp_id=get_sp_id($part_sku,$date);
   

    if($tipo==2){

      $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`) values (%s,%s,'Audit',%s,%s,%s,'')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
      // print "$sql\n";
      //print "B: $sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transaction Fact ");
    }else{
      
      
      $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`note`,`Metadata`) values (%s,%s,'In',%s,%s,%s,'')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
      // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transaction Fact ");


    }
    
    continue;
  }
  
  
  //extend range guess cust value


  $product=new Product('code_store',$code,1);
  if($product->id){
    $parts=$product->get('Parts SKU');



    if(count($parts)>=1){
      $part=new Part($parts[0]);
      if($part->sku){
	//print_r($part);
	$part->update_valid_dates($date);
	$part_sku=$part->sku;
	
	$cost_per_part=$part->get("Unit Cost",$date);
	$parts_per_product=$part->items_per_product($product->pid);
	
       if($tipo==2){

      $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`) values (%s,%s,'Audit',%s,%s,%s,'')",
      prepare_mysql($date),
      prepare_mysql($part_sku),
      prepare_mysql($qty*$parts_per_product),
      prepare_mysql($cost_per_part*$qty*$parts_per_product),
      prepare_mysql($notes,false));
      // print "$sql\n";
       //print "B: $sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transaction Fact ");
    }else{
      
      $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`note`,`Metadata`) values (%s,%s,'In',%s,%s,%s,'')",
      prepare_mysql($date),
      prepare_mysql($part_sku),
      prepare_mysql($qty*$parts_per_product),
      prepare_mysql($cost_per_part*$qty*$parts_per_product),
      prepare_mysql($notes,false));
      // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transaction Fact ");


    }
    
    continue;
      }

    }
    
  }




}
mysql_free_result($result);

print "                \rCleaning old data\n";

$sql="delete  from `Inventory Transaction Fact`  where `Inventory Transaction Type`='Not Found' ";
mysql_query($sql);
$sql="delete  from `Part Location Dimension`  ";
mysql_query($sql);

//$sql="delete  from `Part Location Dimension`  ";
//mysql_query($sql);


// $sql="select `No Shipped Due Out of Stock`,`Invoice Date`,`Product ID` from `Order Transaction Fact` OTF left join `Product Dimension` PD  on  (PD.`Product Key`=OTF.`Product Key`)  where `No Shipped Due Out of Stock`>0;";
// $resultx=mysql_query($sql);

// while($rowx=mysql_fetch_array($resultx, MYSQL_ASSOC)   ){
//   $product_id=$rowx['Product ID'];
//   $notes='';

//   $sql=sprintf(" select `Part SKU` from  `Product Part List`  where `Product ID`=%d and `Product Part Valid From`<%s  and `Product Part Valid To`>%s ",$product_id,prepare_mysql($rowx['Invoice Date']),prepare_mysql($rowx['Invoice Date']));
//   $result=mysql_query($sql);
//   while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//     $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`) values (%s,%s,'Not Found',%s,%s,%s,'')",prepare_mysql($rowx['Invoice Date']),prepare_mysql($row['Part SKU']),0,0,prepare_mysql($notes));
//     // print "$sql\n";
//    //    if(!mysql_query($sql))
// // 	exit("$sql can into insert Inventory Transaction Fact ");

//   }

//  }

print "Wrpaping\n";
// Wrap the transactions
 $sql="delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Associate','Disassociate') ";
 mysql_query($sql);



$sql=sprintf("select `Part SKU` from `Inventory Transaction Fact` group by `Part SKU` ");
$result=mysql_query($sql);
//print $sql;
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $sku=$row['Part SKU'];
  print "$sku       \r";
  
  $sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d  and `Inventory Transaction Type` in ('Audit','Not Found','Sale') order by `Date`  ",$sku);
  $result2=mysql_query($sql);
  // print "$sql\n";
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    
    $date=date("Y-m-d H:i:s",strtotime($row2['Date']." -1 second"));
    $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%d,'Associate',%s,%s,%s,'','Admin')"
		 ,prepare_mysql($date)
		  ,$sku
		 ,0
		 ,0
		 ,"''");
    // print "$sql\n";
    if(!mysql_query($sql))
      exit("$sql can into insert Inventory Transaction Fact star");
  }


  $part=new Part('sku',$sku);
  if($part->data['Part Status']=='Not In Use'){
    
    
    $sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part Sku`=%d  and `Inventory Transaction Type` in ('Audit','Not Found')  order by `Date` desc  ",$sku);
    $result2=mysql_query($sql);
    if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
      $date=date("Y-m-d H:i:s",strtotime($row2['Date']." +1 second"));
      $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`) values (%s,%d,'Disassociate',%s,%s,%s,'')"
		   ,prepare_mysql($date)
		  ,$sku
		   ,0
		   ,0
		   ,"''");
    // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql can into insert Inventory Transaction Fact star");
    }
  }
}


print "creating part-locations\n";

$sql=sprintf("select * from `Inventory Transaction Fact` where `Inventory Transaction Type`='Associate'   ");
//print $sql;
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  $date=$row['Date'];
  $sku=$row['Part SKU'];
  $location_key=$row['Location Key'];
  
  $sql=sprintf("select * from `Inventory Transaction Fact` where `Inventory Transaction Type`='Disassociate'  and `Date`>%s  and `Part SKU`=%d and `Location Key`=%d "
	       ,prepare_mysql($date)
	       ,$sku
	       ,$location_key
	       );
  //print "$sql\n";
  $res2=mysql_query($sql);
  $do_it=true;
  if($row2=mysql_fetch_array($res2)){
    $do_it=false;

  }
  if($do_it){
    //print "adding $sku $location_key\n";
    $part_location=new PartLocation('find',array('Part SKU'=>$sku,'Location Key'=>$location_key),'create');
  }

}
mysql_free_result($res);

/*   print "creation locations"; */
  
/*   $sql="select * from `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Associate') order by Date "; */
/* $result=mysql_query($sql); */
/* while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){ */

  
  
/*     $pl=new PartLocation('find', */
/* 			 array('Location Key'=>$row['Location Key'],'Part SKU'=>$row['Part SKU']),'create'); */




/*  } */


  

  
function get_sp_id($part_sku,$date){
  $sql=sprintf(" select `Supplier Product ID` from  `Supplier Product Part List`   where `Part SKU`=%s  and `Supplier Product Part Valid To`>=%s and  `Supplier Product Part Valid From`<=%s    ",prepare_mysql($part_sku),prepare_mysql($date),prepare_mysql($date));
   // print "\n\n\n\n$sql\n";
  $result=mysql_query($sql);
  $num_rows = mysql_num_rows($result);
  if($num_rows!=1)
    exit("$num rows $sql more than one/zero  sp per part");
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    return $row['Supplier Product ID'];
  }

}








?>

