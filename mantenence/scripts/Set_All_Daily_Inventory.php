<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Part.php');
include_once('../../classes/SupplierProduct.php');
include_once('../../classes/PartLocation.php');

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

$force_first_day=true;
$first_day_with_data=strtotime("2007-03-24");


$where='';
if(isset($argv[1]) and is_numeric($argv[1])  )
  $where=" where  `Part SKU`=". $argv[1];

$sql="select `Part Status`,`Part SKU`,`Part Valid From`,`Part Valid To`,`Part XHTML Currently Used In` from `Part Dimension` $where  order by `Part SKU` desc";

$resultx=mysql_query($sql);
$counter=1;
while($rowx=mysql_fetch_array($resultx, MYSQL_ASSOC)   ){
  $part= new Part($rowx['Part SKU']);
  $part->load('calculate_stock_history');
 }
//   // $location_key=1;
//   //$pl=new PartLocation(array('LocationPart'=>$location_key."_".$part_sku));
  
//  //  $_from=$pl->first_inventory_transacion();
// //   if(!$_from){
// //     print("$part_sku     No transactions\n ");
// //     continue;
    
// //   }
// //   $from=strtotime($_from);

//   if(isset($argv[2]) and $argv[2]=='today')
//     $min=strtotime('today');
//  else
//    $min=strtotime("2003-06-01 09:00:00");

// //   // print $min;

// //   if($from<$min)
// //    $from=$min;
  


// //   if($rowx['Part Status']=='In Use'){
// //     $to=strtotime('today');
// //   }else{
// //     $to=strtotime($rowx['Part Valid To']);
// //   }
  
  


// //  if($from>$to){
// //      print("error $from $to  $part_sku ".$rowx['Part Valid From']." ".$rowx['Part Valid To']."   \n   ");
// //      continue;
// //  }
 



// //  $from=date("Y-m-d",$from);
// //  $to=date("Y-m-d",$to);


//  $sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where `Part SKU`=%d group by `Location Key` ",$part_sku);
//  $resultxxx=mysql_query($sql);
//  while($rowxxx=mysql_fetch_array($resultxxx, MYSQL_ASSOC)   ){
//    $skip=false;
//    $location_key=$rowxxx['Location Key'];

//    if($location_key==1){
//      if($force_first_day){
       
//        $from=strtotime($rowx['Part Valid From']);
//      }else{
//        $_from=$pl->first_inventory_transacion();
//        if(!$_from)
// 	 $skip=true;
//        $from=strtotime($_from);
//      }


//    }else{
//      $_from=$pl->first_inventory_transacion();
//      if(!$_from)
//        $skip=true;
//      $from=strtotime($_from);
//    }

//    if($from<$min)
//      $from=$min;

//    if($rowx['Part Status']=='In Use'){
//      $to=strtotime('today');
//    }else{
//      $to=strtotime($rowx['Part Valid To']);
//    }
  
   
//    if($from>$to){
//      print("error    $part_sku $location_key  ".$rowx['Part Valid From']." ".$rowx['Part Valid To']."   \n   ");
//      continue;
//    }
 

//    if($skip){
//      print "No trasactions $part_sku $location_key "; 
//      continue;
//    }

//    $from=date("Y-m-d",$from);
//    $to=date("Y-m-d",$to);
//    print "$part_sku $location_key  $from $to\n";
//    $pl=new PartLocation(array('LocationPart'=>$location_key."_".$part_sku));
//    $pl->redo_daily_inventory($from,$to);


//  }


//  }


//   $from=strtotime($rowx['Part Valid From']);
//   if($from<$first_day_with_data){
//     $from=$first_day_with_data;
    
//   }else{
//     $from=strtotime($rowx['Part Valid From']);
//     //   print "From: $from ".$rowx['Part Valid From']."  \n";
//   }

//   if($rowx['Part Status']=='In Use'){
//     $to=strtotime('today');
//   }else{
//     $to=strtotime($rowx['Part Valid To']);
//     // print "To.:  $to ".$rowx['Part Valid To']."  \n";
//   }
 
  
//   $sql=sprintf("delete from `Inventory Spanshot Fact` where `Part SKU`=%d ",$part_sku);
//   mysql_query($sql);

//   if($to<$first_day_with_data){
//     print "Skipping $part_sku \r";
//     continue;
//   }
//   if($from>$to){
//     print("error  $part_sku ".$rowx['Part Valid From']." ".$rowx['Part Valid To']."   \n   ");
//     continue;
//   }

//   $start_date = date("Y-m-d",$from);
//   $check_date = $start_date;
//   $end_date =date("Y-m-d",$to);

//   $i = 0;
  


//   $qty_inicio='NULL';
//   $value_inicio=0;

//    $sql=sprintf("select `Inventory Transaction Quantity` from `Inventory Transaction Fact` where  `Part Sku`=%s  and DATE(`Date`)<%s and `Inventory Transaction Type` in ('Audit','Not Found')  order by `Date` desc limit 1",prepare_mysql($part_sku),prepare_mysql($start_date));
//    //print $sql;
//    $result2=mysql_query($sql);
//     if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
//       $qty_inicio=$row2['Inventory Transaction Quantity'];
//       $cost=get_cost($part_sku,$start_date);
//       $value_inicio=$qty_inicio*$cost;
//     }
  

//   while ($check_date != $end_date) {
//     $check_date = date ("Y-m-d", strtotime ("+1 day", strtotime($check_date)));
    
//   //   if(!is_numeric($qty_inicio)){
// //       $sql=sprintf("select count(*) as num  from `Inventory Transaction Fact` where  `Part Sku`=%s  and DATE(`Date`)=%s and `Inventory Transaction Type`in ('Audit','Not Found')  ",prepare_mysql($part_sku),prepare_mysql($check_date));
// //       $result3=mysql_query($sql);
// //       //  print "$sql\n";
// //       $row2=mysql_fetch_array($result3, MYSQL_ASSOC);
// //       // print "$check_date ".$row2['num']."\n";
// //       if($row2['num']==0){
	
// // 	continue;
	
// //       }
      
// //     }
    

    
//     $sql=sprintf("delete from  `Inventory Transaction Fact` where  `Inventory Transaction Type`='Adjust' and `Part Sku`=%s  and DATE(`Date`)=%s ",prepare_mysql($part_sku),prepare_mysql($check_date));
//     mysql_query($sql);

//     $amount_sold=0;
//     $qty_sold=0;
//     $qty_in=0;
//     $sql=sprintf("select * from `Inventory Transaction Fact` where  `Part Sku`=%s  and DATE(`Date`)=%s order by `Date`",prepare_mysql($part_sku),prepare_mysql($check_date));
//     $result3=mysql_query($sql);
//     //   print "$sql\n";
//      while($row2=mysql_fetch_array($result3, MYSQL_ASSOC)   ){
//       $qty=$row2['Inventory Transaction Quantity'];
//       if($row2['Inventory Transaction Type']=='Audit' or $row2['Inventory Transaction Type']=='Not Found' ){
// 	//print "AUDITTT!!!! ";
// 	$cost=get_cost($part_sku,$check_date);
// 	if(is_numeric($qty_inicio)){
// 	  $adjust_qty=$qty-$qty_inicio;
// 	  $adjust_amount=$adjust_qty*$cost;
// 	  $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`) values (%s,%s,'Adjust',%s,%s)",prepare_mysql($row2['Date']),prepare_mysql($part_sku),prepare_mysql($adjust_qty),prepare_mysql($adjust_amount));
// 	  // print "$sql\n";
// 	  if(!mysql_query($sql))
// 	    exit("$sql can into insert Inventory Transaction Fact ");
// 	  $qty_inicio=$qty;
// 	  $value_inicio+=$adjust_amount;

// 	}else{
// 	  $qty_inicio=$qty;
// 	  $value_inicio=$qty*$cost;

// 	}

//       }else if($row2['Inventory Transaction Type']=='Sale' ){

// 	//	print " *********SALE** ".." *****\n";
// 	if(is_numeric($qty_inicio))
// 	  $qty_inicio+=$row2['Inventory Transaction Quantity'];
// 	if(is_numeric($value_inicio))
// 	  $value_inicio+=$row2['Inventory Transaction Amount'];
// 	$amount_sold+=$row2['Inventory Transaction Amount'];
// 	$qty_sold+=$row2['Inventory Transaction Quantity'];
//       }else if($row2['Inventory Transaction Type']=='In'){
// 	if(is_numeric($qty_inicio))
// 	  $qty_inicio+=$row2['Inventory Transaction Quantity'];
// 	if(is_numeric($value_inicio))
// 	  $value_inicio+=$row2['Inventory Transaction Amount'];
// 	$qty_in+=$row2['Inventory Transaction Quantity'];
//       }
//      }//end if the day

// //

//      if(is_numeric($qty_inicio))
//         $last_selling_price=$qty_inicio*get_selling_price($part_sku,$check_date);
//       else
//        $last_selling_price='NULL';
     
//      if($qty_inicio<0 or $qty_inicio=='NULL' or !is_numeric($qty_inicio)){
//        $qty_inicio='NULL';
//        $value_inicio='NULL';
//        $last_selling_price='NULL';
//      }else{
//         $daysin++;
// 	$qty_inicio=sprintf("%.6f",$qty_inicio);
//      }
//      $amount_sold=-1*$amount_sold;
	
//        //   echo "$part_sku  $check_date $qty_inicio $value_inicio $amount_sold $last_selling_price  \n";

//      $sql=sprintf("insert into `Inventory Spanshot Fact` (`Date`,`Part SKU`,`Location Key`,`Quantity on Hand`,`Value at Cost`,`Sold Amount`,`Value at Latest Selling Price`,`Storing Cost`,`Quantity Sold`,`Quantity In`) values (%s,%s,%s,%s,%.2f,%.6f,%.2f,%s,%f,%f)"
// 		  ,prepare_mysql($check_date)
// 		  ,$part_sku
// 		    ,'NULL'
// 		  ,$qty_inicio
// 		  ,$value_inicio
// 		  ,$amount_sold
// 		  ,$last_selling_price
// 		  ,'NULL'
// 		  ,-$qty_sold
// 		  ,$qty_in
// 		  );
//      if(!mysql_query($sql))
//        exit( "$sql\n\n Can no create Inventory Spanshot Fact\n ");
       
     
     


//     $i++;
//     if ($i > 5000) { die ('Error!'); } 
//   }  
//   $counter++;
//   echo "$counter $part_sku  $check_date $qty_inicio Days in:$daysin   Part: ".$rowx['Part XHTML Currently Used In']."  \n";

  


//  }




// function get_cost($part_sku,$date){


//   $sql=sprintf(" select AVG(SPD.`Supplier Product Cost` * SPPL.`Supplier Product Units Per Part`) as cost from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%s  and `Supplier Product Valid To`>=%s and  `Supplier Product Valid From`<=%s    ",prepare_mysql($part_sku),prepare_mysql($date),prepare_mysql($date));
//   //  print "\n\n\n\n$sql\n";
//   $result=mysql_query($sql);
//   if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//     if(is_numeric($row['cost']))
//       return $row['cost'];
//   }


//   $sql=sprintf(" select AVG(SPD.`Supplier Product Cost` * SPPL.`Supplier Product Units Per Part`) as cost from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%s  and `Supplier Product Valid To`<=%s limit 1 ",prepare_mysql($part_sku),prepare_mysql($date));

//   $result=mysql_query($sql);
//   if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//     if(is_numeric($row['cost']))
//       return $row['cost'];
//   }

//   $sql=sprintf(" select AVG(SPD.`Supplier Product Cost` * SPPL.`Supplier Product Units Per Part`) as cost from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%s  order by  `Supplier Product Valid To` desc ",prepare_mysql($part_sku),prepare_mysql($date));
//   // print "\n\n\n\n$sql\n";
//   $result=mysql_query($sql);
//   if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//     if(is_numeric($row['cost']))
//       return $row['cost'];
//   }


//   exit("error can no found supp ciost\n");


// }

 

// function get_selling_price($part_sku,$date){


//   $sql=sprintf(" select AVG(PD.`Product Price` * PPL.`Parts Per Product`) as cost from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%s  and `Product Valid To`>=%s and  `Product Valid From`<=%s    ",prepare_mysql($part_sku),prepare_mysql($date),prepare_mysql($date));
//   // print "\n\n\n\n$sql\n";
//   $result=mysql_query($sql);
//   if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//     if(is_numeric($row['cost']))
//       return $row['cost'];
//   }


//   $sql=sprintf(" select AVG(PD.`Product Price` * PPL.`Parts Per Product`) as cost from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%s  and `Product Valid To`<=%s limit 1 ",prepare_mysql($part_sku),prepare_mysql($date));

//   $result=mysql_query($sql);
//   if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//     if(is_numeric($row['cost']))
//       return $row['cost'];
//   }

//   $sql=sprintf(" select AVG(PD.`Product Price` * PPL.`Parts Per Product`) as cost from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%s  order by  `Product Valid To` desc ",prepare_mysql($part_sku),prepare_mysql($date));
//   //   print "\n\n\n\n$sql\n";
//   $result=mysql_query($sql);
//   if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//     if(is_numeric($row['cost']))
//       return $row['cost'];
//   }


//   exit("error can no found product last selling  ciost\n");


// }













?>