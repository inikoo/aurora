<?
include_once('Part.php');
include_once('Location.php');

class PartLocation{
  
 function __construct($data=false) {
   
   if(isset($data['LocationPart'])){
     $tmp=split("_",$data['LocationPart']);
     $this->location_key=$tmp[0];
     $this->part_sku=$tmp[1];
     
   }else{
     $this->location_key=$data('Location Key');
     $this->part_sku=$data('Part SKU');
   }
   $this->date=date("Y-m-d");
 
 }

 function last_inventory_date(){
 $sql=sprintf("select `Date` from `Inventory Spanshot Fact` where  `Part Sku`=%d   order by `Date` desc ",$this->part_sku);
 $result=mysql_query($sql);
 if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
   return $row['Date'];
 }else
   return false;

 }

 function first_inventory_transacion(){
 $sql=sprintf("select DATE(`Date`) from `Inventory Transaction Fact` where  `Part Sku`=%d and (`Inventory Transaction Type`='Adjust' or `Inventory Transaction Type`='Not Found')  order by `Date`",$this->part_sku);
 $result=mysql_query($sql);
 if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
   return $row['Date'];
 }else
   return false;

 }



 function audit($data){

   $qty=$data['qty'];
   $user_id=$data['user key'];
   $note=$data['note'];
   $options=$data['options'];
   $date=$data['date'];

   if($date==''){
     $date=date("Y-m-d H:i:s");

     if(preg_match('/force_update/',$date)){
       $from=$this->last_inventory_date();
       if(!$from){
	 $from=$this->first_inventory_transacion();
       }
       if($from){
	 $this->redo_daily_inventory($from,'');
       }
       
     }

     $_date=date("Y-m-d",strtotime($date));
     $sql=sprintf("select `Value At Cost`,`Quantity On Hand` from `Inventory Spanshot Fact` where  `Part SKU`=%d  and `Location Key`=%d  and `Date`=%s ",$this->part_sku,$this->location_key,prepare_mysql($_date));

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       $old_value=$row['Value At Cost'];
       $old_qty=$row['Quantity On Hand'];
       if(is_numeric($old_qty)){
	 $ok=true;
	 if($old_qty>0)
	   $unitary_price=$old_value/$old_qty;


       }else{
	 $unitary_price='';
	 $ok=false;
       }       


     }
     
     if(!is_numeric($unitary_price)){
       $sql=sprintf(" select AVG(SPD.`Supplier Product Cost` * SPPL.`Supplier Product Units Per Part`) as cost from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%d  and `Supplier Product Part Most Recent`='Yes'    ",$part_sku);
       $result=mysql_query($sql);
       if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	 $unitary_price=$row['cost'];
	   
       }
       

     }

     if(!is_numeric($user_id) or $user_id<0)
       $user_id='NULL';
     $sql=sprintf("insert into `Inventory Transition Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`) values (%d,%d,%s,%f,%.2f,%s,%s)"
		  ,$this->part_sku
		  ,$this->location_key
		  ,"'Audit'"
		  ,$qty
		  ,$qty*$unitary_price
		  ,$user_id
		  ,$note
		  );
     //prepare_mysql($sql);
     print "$sql\n";

     }
   $this->redo_daily_inventory($_date,'');

 }


 function redo_daily_inventory($from,$to=''){
   $uptodate=false;
   $from=strtotime($from);
   if($to==''){
     $to=strtotime('now');
     $uptodate=true;
   }else
     $to=srttotime($to);

   $start_date = date("Y-m-d",$from);
   $check_date = $start_date;
   $end_date =date("Y-m-d",$to);
   $i = 0;
   
   $qty_inicio='NULL';
   $value_inicio=0;
   
   $sql=sprintf("select `Inventory Transaction Quantity`,`Inventory Transaction Amount` from `Inventory Transition Fact` where  `Part Sku`=%d  and DATE(`Date`)<%s and `Inventory Transaction Type` in ('Audit','Not Found')  order by `Date` desc limit 1"

		,$this->part_sku,prepare_mysql($start_date));

   $result2=mysql_query($sql);
    if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
      $qty_inicio=$row2['Inventory Transaction Quantity'];  
      $value_inicio=$row2['Inventory Transaction Amount'];
    }
 while ($check_date != $end_date) {
   $check_date = date ("Y-m-d", strtotime ("+1 day", strtotime($check_date)));
   $sql=sprintf("delete from  `Inventory Transition Fact` where  `Inventory Transaction Type`='Adjust' and `Part Sku`=%s  and DATE(`Date`)=%s ",prepare_mysql($part_sku),prepare_mysql($check_date));
   mysql_query($sql);
     $amount_sold=0;
    $qty_sold=0;
    $qty_in=0;
    $sql=sprintf("select * from `Inventory Transition Fact` where  `Part Sku`=%s  and DATE(`Date`)=%s order by `Date`",prepare_mysql($part_sku),prepare_mysql($check_date));
    $result3=mysql_query($sql);
    //   print "$sql\n";
     while($row2=mysql_fetch_array($result3, MYSQL_ASSOC)   ){
      $qty=$row2['Inventory Transaction Quantity'];
      if($row2['Inventory Transaction Type']=='Audit' or $row2['Inventory Transaction Type']=='Not Found' ){
	//print "AUDITTT!!!! ";
	
	if(is_numeric($qty_inicio)){
	  if($qty_inicio==0)
	    $cost=get_cost($part_sku,$check_date);
	  else
	    $cost=$value_inicio/$qty_inicio;

	  $adjust_qty=$qty-$qty_inicio;
	  $adjust_amount=$adjust_qty*$cost;
	  $sql=sprintf("insert into `Inventory Transition Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`) values (%s,%s,'Adjust',%s,%s)",prepare_mysql($row2['Date']),prepare_mysql($part_sku),prepare_mysql($adjust_qty),prepare_mysql($adjust_amount));
	  // print "$sql\n";
	  if(!mysql_query($sql))
	    exit("$sql can into insert Inventory Transition Fact ");
	  $qty_inicio=$qty;
	  $value_inicio+=$adjust_amount;

	}else{
	  $cost=get_cost($part_sku,$check_date);
	  $qty_inicio=$qty;
	  $value_inicio=$qty*$cost;

	}

      }else if($row2['Inventory Transaction Type']=='Sale' ){

	//	print " *********SALE** ".." *****\n";

	if(is_numeric($value_inicio) and is_numeric($qty_inicio) and $qty_inicio>$row2['Inventory Transaction Quantity']){
	  $cost=$value_inicio/$qty_inicio;



	  $qty_inicio+=$row2['Inventory Transaction Quantity'];
	  $value_inicio+=$cost*$row2['Inventory Transaction Quantity'];
	}else{
	  $qty_inicio='NULL';
	  $value_inicio='NULL';
	}
	

	$amount_sold+=$row2['Inventory Transaction Amount'];
	$qty_sold+=$row2['Inventory Transaction Quantity'];
      }else if($row2['Inventory Transaction Type']=='In'){
	if(is_numeric($qty_inicio))
	  $qty_inicio+=$row2['Inventory Transaction Quantity'];
	if(is_numeric($value_inicio))
	  $value_inicio+=$row2['Inventory Transaction Amount'];
	$qty_in+=$row2['Inventory Transaction Quantity'];
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
     }else{
        $daysin++;
	$qty_inicio=sprintf("%.6f",$qty_inicio);
     }
     $amount_sold=-1*$amount_sold;
	
       //   echo "$part_sku  $check_date $qty_inicio $value_inicio $amount_sold $last_selling_price  \n";

     $sql=sprintf("insert into `Inventory Spanshot Fact` (`Date`,`Part SKU`,`Location Key`,`Quantity on Hand`,`Value at Cost`,`Sold Amount`,`Value at Latest Selling Price`,`Storing Cost`,`Quantity Sold`,`Quantity In`) values (%s,%s,%s,%s,%.2f,%.6f,%.2f,%s,%f,%f)"
		  ,prepare_mysql($check_date)
		  ,$part_sku
		  ,1
		  ,$qty_inicio
		  ,$value_inicio
		  ,$amount_sold
		  ,$last_selling_price
		  ,'NULL'
		  ,-$qty_sold
		  ,$qty_in
		  );
     if(!mysql_query($sql))
       exit( "$sql\n\n Can no create Inventory Spanshot Fact\n ");
       
     
     


    $i++;
    if ($i > 50000) { die ('Error!'); } 
 }

 if($uptodate){
   $part=new Part('sku',$this->part_sku);
   $part->load('stock');
 }

 }


}
?>