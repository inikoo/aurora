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
 $sql=sprintf("select DATE(`Date`) as Date from `Inventory Transaction Fact` where  `Part Sku`=%d and (`Inventory Transaction Type`='Audit' or `Inventory Transaction Type`='Not Found')  order by `Date`",$this->part_sku);
 $result=mysql_query($sql);
 // print $sql;
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
     $unitary_price='';
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
       $sql=sprintf(" select AVG(SPD.`Supplier Product Cost` * SPPL.`Supplier Product Units Per Part`) as cost from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%d  and `Supplier Product Part Most Recent`='Yes'    ",$this->part_sku);
       $result=mysql_query($sql);
       if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	 $unitary_price=$row['cost'];
	   
       }
       

     }

     if(!is_numeric($user_id) or $user_id<0)
       $user_id='NULL';
     $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%f,%.2f,%s,%s,%s)"
		  ,$this->part_sku
		  ,$this->location_key
		  ,"'Audit'"
		  ,$qty
		  ,$qty*$unitary_price
		  ,$user_id
		  ,prepare_mysql($note)
		  ,prepare_mysql($date)
		  );
     if(!mysql_query($sql))
       print "Error can not audit liocatun";
     // print "$sql\n";

     }
   $this->redo_daily_inventory($_date,'');

 }


 function redo_daily_inventory($from,$to=''){
   $daysin=0;

  $uptodate=false;
   $from=strtotime($from);
   if($to==''){
     $to=strtotime('now');
     $uptodate=true;
   }else
     $to=strtotime($to);

   $start_date = date("Y-m-d",$from);
   $day_before_date = date ("Y-m-d", strtotime ("-1 day", strtotime($from)));
   $check_date = $start_date;
   $end_date =date("Y-m-d",$to);
   $i = 0;
   

   // print "$start_date $end_date \n"; 

   $qty_inicio='NULL';
   $value_inicio='NULL';
   
   $sql=sprintf("delete from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d and (`Date`>=%s or `Date`<=%s) "
		,$this->part_sku
		,$this->location_key
		,prepare_mysql($start_date)
		,prepare_mysql($end_date)
		);

   mysql_query($sql);
   //print $sql;


 $sql=sprintf("select `Value At Cost`,`Quantity On Hand` from `Inventory Spanshot Fact` where  `Part SKU`=%d  and `Location Key`=%d  and `Date`=%s ",$this->part_sku,$this->location_key,prepare_mysql($day_before_date));
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       $value_inicio=$row['Value At Cost'];
       $qty_inicio=$row['Quantity On Hand'];
     }
     if(!is_numeric($qty_inicio)){
       $sql=sprintf("select `Inventory Transaction Quantity`,`Inventory Transaction Amount` from `Inventory Transaction Fact` where  `Part Sku`=%d and  `Location Key`=%d  and DATE(`Date`)<%s and `Inventory Transaction Type` in ('Audit','Not Found')  order by `Date` desc limit 1"
		    
		    ,$this->part_sku
		    ,$this->location_key
		    ,prepare_mysql($start_date));
       
       $result2=mysql_query($sql);
       if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
	 $qty_inicio=$row2['Inventory Transaction Quantity'];  
	 $value_inicio=$row2['Inventory Transaction Amount'];
       }
     }

     //print " $check_date $end_date  $qty_inicio  $value_inicio  ";

     while (strtotime($check_date) <=strtotime( $end_date) ) {

   $sql=sprintf("delete from  `Inventory Transaction Fact` where  `Inventory Transaction Type`='Adjust' and `Part Sku`=%d   and  `Location Key`=%d and DATE(`Date`)=%s "
		,$this->part_sku
		,$this->location_key
		,prepare_mysql($check_date));
   mysql_query($sql);
     $amount_sold=0;
    $qty_sold=0;
    $qty_in=0;
    $sql=sprintf("select * from `Inventory Transaction Fact` where  `Part Sku`=%d   and  `Location Key`=%d  and DATE(`Date`)=%s order by `Date`"
		 ,$this->part_sku
		 ,$this->location_key
		 ,prepare_mysql($check_date));
    
    $result3=mysql_query($sql);
    // print "$sql\n";
    //print "$check_date\n";
     while($row2=mysql_fetch_array($result3, MYSQL_ASSOC)   ){
      $qty=$row2['Inventory Transaction Quantity'];
      if($row2['Inventory Transaction Type']=='Audit' or $row2['Inventory Transaction Type']=='Not Found' ){
	//print "AUDITTT!!!! ";
		
	if(is_numeric($qty_inicio)){
	  if($qty_inicio==0)
	    $cost=$this->get_cost($this->part_sku,$check_date);
	  else
	    $cost=$value_inicio/$qty_inicio;

	  $adjust_qty=$qty-$qty_inicio;
	  $adjust_amount=$adjust_qty*$cost;
	  $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`) values (%s,%d,%d,'Adjust',%s,%s)",prepare_mysql($row2['Date'])
		       ,$this->part_sku
		       ,$this->location_key
		       ,prepare_mysql($adjust_qty),prepare_mysql($adjust_amount));
	  // print "$sql\n";
	  if(!mysql_query($sql))
	    exit("$sql can into insert Inventory Transaction Fact ");
	  $qty_inicio=$qty;
	  $value_inicio+=$adjust_amount;

	}else{
	  $cost=$this->get_cost($this->part_sku,$check_date);
	  $qty_inicio=$qty;
	  $value_inicio=$qty*$cost;

	}

      }else if($row2['Inventory Transaction Type']=='Sale' ){

	//	print " *********SALE** ".." *****\n";

	if(is_numeric($value_inicio) and is_numeric($qty_inicio) and $qty_inicio>$row2['Inventory Transaction Quantity'] and $qty_inicio>0){
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
        $last_selling_price=$qty_inicio*$this->get_selling_price($this->part_sku,$check_date);
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
	
       //   echo "$this->part_sku  $check_date $qty_inicio $value_inicio $amount_sold $last_selling_price  \n";

     $sql=sprintf("insert into `Inventory Spanshot Fact` (`Date`,`Part SKU`,`Location Key`,`Quantity on Hand`,`Value at Cost`,`Sold Amount`,`Value at Latest Selling Price`,`Storing Cost`,`Quantity Sold`,`Quantity In`) values (%s,%d,%d,%s,%.2f,%.6f,%.2f,%s,%f,%f)"
		  ,prepare_mysql($check_date)
		  ,$this->part_sku
		  ,$this->location_key
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
       
     
     //print "$sql\n";


    $i++;
    if ($i > 1825) { die ('Error!'); } 


    $check_date = date ("Y-m-d", strtotime ("+1 day", strtotime($check_date)));

 }



 if($uptodate){
   $part=new Part('sku',$this->part_sku);
   $part->load('stock');
 }

 }




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


 function create($data){
   $user_id=$data['user key'];
   if(isset($data['date']))
     $date=$data['date'];
   else
     $date=date("Y-m-d H:i:s");
   $note=$data['note'];
   $sql=sprintf("select * from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d and `Date`=%s ",
		$this->part_sku
		,$this->location_key
		,prepare_date($date)
		);
   $result=mysql_query($sql);
   $num_rows = mysql_num_rows($result);
   
   if($num_rows==0){

      if(!is_numeric($user_id) or $user_id<0)
	$user_id='NULL';
      
      $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%f,%.2f,%s,%s,%s)"
		   ,$this->part_sku
		   ,$this->location_key
		   ,"'Audit'"
		   ,0
		   ,0
		   ,$user_id
		   ,$note
		   ,prepare_mysql($date)
		  );
      prepare_mysql($sql);
      print "$sql\n";
      $this->redo_daily_inventory($_date,'');




   }
 }


 function destroy($data){

   $user_id=$data['user key'];
   $note=$data['note'];
   $options=$data['options'];

   

   if(!is_numeric($user_id) or $user_id<0)
       $user_id='NULL';

   if(isset($data['date']))
     $date=$data['date'];
   else
     $date=date("Y-m-d H:i:s");

   $_date=date("Y-m-d",strtotime($date));

   $sql=sprintf("select * from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d and `Date`=%s ",
		$this->part_sku
		,$this->location_key
		,prepare_date($_date)
		);
   $result=mysql_query($sql);
   $num_rows = mysql_num_rows($result);
   
   if($num_rows==1){
     $result=mysql_query($sql);
     $row=mysql_fetch_array($result, MYSQL_ASSOC);
     $qty=$row['Quantity On Hand'];
     $value=$row['Location Stock Value'];

     
     $sql=sprintf("select * from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=1 and `Date`=%s ",
		$this->part_sku
		  ,prepare_date($date)
		  );
     $result2=mysql_query($sql);
     $num_rows = mysql_num_rows($result2);
     
     if($num_rows==1){
       $row2=mysql_fetch_array($result2, MYSQL_ASSOC);
       $_qty=$row2['Quantity On Hand'];
       $_value=$row2['Value At Cost'];
     
       $__qty='NULL';
       $__value='NULL';
       if(is_numeric($_qty) and is_numeric($qty)){
	 $__qty=$_qty+$qty;
	 if(is_numeric($_value) and is_numeric($value)){
	   $__value=$_value+$value;
	 }
       }
       
        $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%s,%s,%s,%s,%s)"
		  ,$this->part_sku
		  ,0
		  ,"'Audit'"
		  ,$__qty
		  ,$__value
		  ,$user_id
		  ,$note
		  ,prepare_mysql($date)
		  );
	prepare_mysql($sql);
        $this->redo_daily_inventory($_date,'');


       
     }else{

       $__qty='NULL';
       $__value='NULL';
       if(is_numeric($qty)){
	 $__qty=$qty;
	 if(is_numeric($value)){
	   $__value=$value;
	 }
       }

         $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%s,%s,%s,%s,%s)"
		  ,$this->part_sku
		  ,0
		  ,"'Audit'"
		  ,$__qty
		  ,$__value
		  ,$user_id
		  ,$note
		  ,prepare_mysql($date)
		  );
	prepare_mysql($sql);
        $this->redo_daily_inventory($_date,''); 



     }


   }
 }




}
?>