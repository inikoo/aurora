<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');
$not_found=00;


$first_day_with_data=strtotime("2007-03-24");

$sql="select `Part Status`,`Part SKU`,`Part Valid From`,`Part Valid To` from `Part Dimension` where `Part Key`=1  ";
$resultx=mysql_query($sql);
while($rowx=mysql_fetch_array($resultx, MYSQL_ASSOC)   ){
  




  $part_sku=$rowx['Part SKU'];


  $sql=sprintf("select * from `Inventory Spanshot Fact` where  `Part Sku`=%s   order by `Date` desc limit 1",prepare_mysql($part_sku));
  print $sql;
  
   $result2=mysql_query($sql);
    if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
      $qty_inicio=$row2['Quantity On Hand'];
      $value_inicio=$row2['Value At Cost'];
      $from=strtotime($row2['Date']);
    }


    //  print "------ $value_inicio $qty_inicio  \n";


if($rowx['Part Status']=='In Use'){
    $to=strtotime('today');
  }else{
    $to=strtotime($rowx['Part Valid To']);
    // print "To.:  $to ".$rowx['Part Valid To']."  \n";
  }
 
  
  //  $sql=sprintf("delete from `Inventory Spanshot Fact` where `Part SKU`=%d ",$part_sku);
  //mysql_query($sql);

  if($to<$first_day_with_data){
    print "Skipping $part_sku \r";
    continue;
  }
  if($from>$to){
    print("error  $part_sku ".$rowx['Part Valid From']." ".$rowx['Part Valid To']."   \n   ");
    continue;
  }


 


  $start_date = date("Y-m-d",$from);
  $check_date = $start_date;
  $end_date =date("Y-m-d",$to);

  $i = 0;
  

  $qty_inicio='NULL';
  

  print " $start_date  $check_date\n    ";
  exit;
  while ($check_date != $end_date) {
    $check_date = date ("Y-m-d", strtotime ("+1 day", strtotime($check_date)));

    if(!is_numeric($qty_inicio)){
      $sql=sprintf("select count(*) as num  from `Inventory Transaction Fact` where  `Part Sku`=%s  and DATE(`Date`)=%s and `Inventory Transaction Type` like 'Audit' ",prepare_mysql($part_sku),prepare_mysql($check_date));
    $result3=mysql_query($sql);
    //  print "$sql\n";
    $row2=mysql_fetch_array($result3, MYSQL_ASSOC);
    // print "$check_date ".$row2['num']."\n";
    if($row2['num']==0){

      continue;
    
    }

    }
    

    
    $sql=sprintf("delete from  `Inventory Transaction Fact` where  `Inventory Transaction Type`='Adjust' and `Part Sku`=%s  and DATE(`Date`)=%s ",prepare_mysql($part_sku),prepare_mysql($check_date));
    mysql_query($sql);

    $amount_sold=0;
    $sql=sprintf("select * from `Inventory Transaction Fact` where  `Part Sku`=%s  and DATE(`Date`)=%s order by `Date`",prepare_mysql($part_sku),prepare_mysql($check_date));
    $result3=mysql_query($sql);
    //   print "$sql\n";
     while($row2=mysql_fetch_array($result3, MYSQL_ASSOC)   ){
      $qty=$row2['Inventory Transaction Quantity'];
      if($row2['Inventory Transaction Type']=='Audit'){
	//print "AUDITTT!!!! ";
	$cost=get_cost($part_sku,$check_date);
	if(is_numeric($qty_inicio)){
	  $adjust_qty=$qty-$qty_inicio;
	  $adjust_amount=$adjust_qty*$cost;
	  $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`) values (%s,%s,'Adjust',%s,%s)",prepare_mysql($row2['Date']),prepare_mysql($part_sku),prepare_mysql($adjust_qty),prepare_mysql($adjust_amount));
	  // print "$sql\n";
	  if(!mysql_query($sql))
	    exit("$sql can into insert Inventory Transaction Fact ");
	  $qty_inicio=$qty;
	  $value_inicio+=$adjust_amount;

	}else{
	  $qty_inicio=$qty;
	  $value_inicio=$qty*$cost;

	}

      }else if($row2['Inventory Transaction Type']=='Sale' ){

	//	print " *********SALE** ".." *****\n";
	if(is_numeric($qty_inicio))
	  $qty_inicio+=$row2['Inventory Transaction Quantity'];
	if(is_numeric($value_inicio))
	  $value_inicio+=$row2['Inventory Transaction Amount'];
	$amount_sold+=$row2['Inventory Transaction Amount'];
	
      }else if($row2['Inventory Transaction Type']=='In'){
	if(is_numeric($qty_inicio))
	  $qty_inicio+=$row2['Inventory Transaction Quantity'];
	if(is_numeric($value_inicio))
	  $value_inicio+=$row2['Inventory Transaction Amount'];
      }
     }//end if the day

//

     if(is_numeric($qty_inicio))
        $last_selling_price=$qty_inicio*get_selling_price($part_sku,$check_date);
      else
       $last_selling_price='NULL';
     
     if($qty_inicio<0 or $qty_inicio=='NULL' or !is_numeric($qty_inicio)){
       $qty_inicio='NULL';
       $value_inicio='NULL';
       $last_selling_price='NULL';
     }
     $amount_sold=-1*$amount_sold;
     if(is_numeric($qty_inicio)){
       //   echo "$part_sku  $check_date $qty_inicio $value_inicio $amount_sold $last_selling_price  \n";
       
       $sql=sprintf("insert into `Inventory Spanshot Fact` (`Date`,`Part SKU`,`Location Key`,`Quantity on Hand`,`Value at Cost`,`Sold Amount`,`Value at Latest Selling Price`,`Storing Cost`) values (%s,%s,%s,%.6f,%.2f,%.6f,%.2f,%s)"
		    ,prepare_mysql($check_date)
		    ,$part_sku
		    ,'NULL'
		    ,$qty_inicio
		    ,$value_inicio
		    ,$amount_sold
		    ,$last_selling_price
		    ,'NULL');
       if(!mysql_query($sql))
	 exit( "$sql\n\n Can no create Inventory Spanshot Fact\n ");
       
     }
     


    $i++;
    if ($i > 5000) { die ('Error!'); } 
  }  
  echo "$part_sku  $check_date $qty_inicio   \n";

  


 }




// $days=0;
// while($today<strtotime('now -2 day') and  $days<20000  ){

//   $yesterday=strtotime($_first_date." + $days days ");
//   $days++;
//   //print $_first_date." + $days days \n";
//   $today=strtotime($_first_date." + $days days ");

//   $sql=sprintf("select * from `Part Dimension`  where `Part Valid From`<=%s and `Part Valid To`>=%s  and `Part SKU`=%d   ",prepare_mysql(date("Y-m-d",$today)),prepare_mysql(date("Y-m-d",$today)),$part_sku);
//   // print "$sql\n";
//   $result=mysql_query($sql);
//   $parts_this_day=false;
//   while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//     // print "Part sku ".$row['Part SKU']." ".prepare_mysql(date("Y-m-d",$today));
//     $amount_sold=0;
//     $qty_inicio='NULL';
//     $value_inicio='NULL';
//     $sql=sprintf("select IFNULL(`Quantity On Hand`,'NULL') as qoh,IFNULL(`Value At Cost`,'NULL')as vac   from `Inventory Spanshot Fact` where `Snapshot Period`='Day'  and `Part SKU`=%s  and DATE(`Date`)=%s",prepare_mysql($row['Part SKU']),prepare_mysql(date("Y-m-d",$yesterday)));
//     //  print "$sql\n";
//     $result2=mysql_query($sql);
//     if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
//       $qty_inicio=$row2['qoh'];
//       $value_inicio=$row2['vac'];
//     }
//     //print " INIL $qty_inicio ";
//     $sql=sprintf("delete from  `Inventory Transaction Fact` where  `Inventory Transaction Type`='Adjust' and `Part Sku`=%s  and DATE(`Date`)=%s ",prepare_mysql($row['Part SKU']),prepare_mysql(date("Y-m-d",$today)));
//     mysql_query($sql);


//     $sql=sprintf("select * from `Inventory Transaction Fact` where  `Part Sku`=%s  and DATE(`Date`)=%s order by `Date`",prepare_mysql($row['Part SKU']),prepare_mysql(date("Y-m-d",$today)));
//     $result3=mysql_query($sql);
//     //   print "$sql\n";

//      while($row2=mysql_fetch_array($result3, MYSQL_ASSOC)   ){
//       $qty=$row2['Inventory Transaction Quantity'];
//       if($row2['Inventory Transaction Type']=='Audit'){

// 	//print "AUDITTT!!!! ";

// 	$cost=get_cost($row['Part SKU'],date("Y-m-d",$today));
// 	if(is_numeric($qty_inicio)){
// 	  //create and adjust transiction
	  
	  
// 	  $adjust_qty=$qty-$qty_inicio;
// 	  $adjust_amount=$adjust_qty*$cost;
// 	  $part_sku=$row['Part SKU'];

// 	  $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`) values (%s,%s,'Adjust',%s,%s)",prepare_mysql($row2['Date']),prepare_mysql($part_sku),prepare_mysql($adjust_qty),prepare_mysql($adjust_amount));
// 	  // print "$sql\n";
// 	  if(!mysql_query($sql))
// 	    exit("$sql can into insert Inventory Transaction Fact ");
// 	   $qty_inicio=$qty;
// 	   $value_inicio+=$adjust_amount;

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

//       }else if($row2['Inventory Transaction Type']=='In'){
// 	if(is_numeric($qty_inicio))
// 	  $qty_inicio+=$row2['Inventory Transaction Quantity'];
// 	if(is_numeric($value_inicio))
// 	  $value_inicio+=$row2['Inventory Transaction Amount'];
//       }



//       // print "Q:$qty_inicio  ";
       
//      }

// //     //

//     if(is_numeric($qty_inicio))
//       $last_selling_price=$qty_inicio*get_selling_price($row['Part SKU'],date("Y-m-d",$today));
//     else
//       $last_selling_price='';

//     if($qty_inicio<0 or $qty_inicio=='NULL' or !is_numeric($qty_inicio)){
//       $qty_inicio='NULL';
//       $value_inicio='NULL';
//       $last_selling_price='NULL';
//     }
//     //  print " FIN: $qty_inicio  \n";
    


//     $sql=sprintf("insert into `Inventory Spanshot Fact` (`Snapshot Period`,`Date`,`Part SKU`,`Warehouse Key`,`Location Key`,`Quantity on Hand`,`Value at Cost`,`Sold Amount`,`Value at Latest Selling Price`,`Storing Cost`) values ('Day',%s,%s,%s,%s,%s,%s,%s,%f,%s)"
// 		 ,prepare_mysql(date("Y-m-d",$today))
// 		 ,$row['Part SKU']
// 		 ,'NULL'
// 		 ,'NULL'
// 		 ,$qty_inicio
// 		 ,$value_inicio
// 		 ,$amount_sold
// 		 ,$last_selling_price
// 		 ,'NULL');
//     //  print "$sql\n";



//     if(!mysql_query($sql))
//       exit( "$sql\n\n Can no create Inventory Spanshot Fact\n ");

//   }



//  }
 

// // $sql="select * from `Part Dimension`  ";
// // $result=mysql_query($sql);
// // while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  


// //  }
//  }

function get_cost($part_sku,$date){


  $sql=sprintf(" select AVG(SPD.`Supplier Product Cost` * SPPL.`Supplier Product Units Per Part`) as cost from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%s  and `Supplier Product Valid To`>=%s and  `Supplier Product Valid From`<=%s    ",prepare_mysql($part_sku),prepare_mysql($date),prepare_mysql($date));
  //  print "\n\n\n\n$sql\n";
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

 

function get_selling_price($part_sku,$date){


  $sql=sprintf(" select AVG(PD.`Product Price` * PPL.`Parts Per Product`) as cost from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%s  and `Product Valid To`>=%s and  `Product Valid From`<=%s    ",prepare_mysql($part_sku),prepare_mysql($date),prepare_mysql($date));
  // print "\n\n\n\n$sql\n";
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    if(is_numeric($row['cost']))
      return $row['cost'];
  }


  $sql=sprintf(" select AVG(PD.`Product Price` * PPL.`Parts Per Product`) as cost from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%s  and `Product Valid To`<=%s limit 1 ",prepare_mysql($part_sku),prepare_mysql($date));

  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    if(is_numeric($row['cost']))
      return $row['cost'];
  }

  $sql=sprintf(" select AVG(PD.`Product Price` * PPL.`Parts Per Product`) as cost from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%s  order by  `Product Valid To` desc ",prepare_mysql($part_sku),prepare_mysql($date));
  //   print "\n\n\n\n$sql\n";
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    if(is_numeric($row['cost']))
      return $row['cost'];
  }


  exit("error can no found product last selling  ciost\n");


}









?>

