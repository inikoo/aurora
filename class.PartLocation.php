<?php
/*
 File: PartLocation.php 

 This file contains the PartLocation Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('class.Part.php');
include_once('class.Location.php');

class PartLocation extends DB_Table{
  
  var $ok=false;
  var $event_order=0;

  function PartLocation($arg1=false,$arg2=false,$arg3=false) {
    
    $this->table_name='Part Location';
 


    if(is_array($arg1)){
      $data=$arg1;
      if(isset($data['LocationPart'])){
	$tmp=split("_",$data['LocationPart']);
	$this->location_key=$tmp[1];
	$this->part_sku=$tmp[2];
	
      }else{
	print "---- $data   --------\n";
	$this->location_key=$data['Location Key'];
	$this->part_sku=$data['Part SKU'];
      }
      $this->date=date("Y-m-d");
    }else{
      
      if($arg1=='find'){
	$this->find($arg2,$arg3);
	return;
      }elseif(is_numeric($arg1) and is_numeric($arg2)){
	$this->part_sku=$arg1;
	$this->location_key=$arg2;
	$this->get_data();
	return;

      }else{


      $tmp=preg_split("/\_/",$arg1);
      if(count($tmp)==2){
	$this->part_sku=$tmp[0];
	$this->location_key=$tmp[1];
	$this->get_data();
      }
      return;
      }
 }
  

  }
/*
   Method: find
   Find Part  Location  Paair with similar data
  */   
  
  function find($raw_data,$options){
    if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
		    
      }
    }
   
    
    $this->found=false;
    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }
    
    $data=$this->base_data();
    foreach($raw_data as $key=>$val){
      $_key=$key;
      $data[$_key]=$val;
    }

    $this->location=New Location($data['Location Key']);
    if(!$this->location->id)
      $this->location=New Location(1);
    $this->location_key=$this->location->id;

    $this->part=New Part($data['Part SKU']);
    if(!$this->part->id){
      $this->error=true;
      $this->msg=_('Part not found');
    }else
      $this->part_sku=$this->part->sku;

    $sql=sprintf("select `Location Key`,`Part SKU` from `Part Location Dimension` where `Part SKU`=%d and `Location Key`=%d"
		 ,$this->part_sku
		 ,$this->location_key
		 );
    $res=mysql_query($sql);
    //print $sql;
   
    if($row=mysql_fetch_array($res)){
      $this->found=true;
      $this->get_data();
    }

    

    if($create and !$this->found)
      $this->create($data,$options);
    
     if($update and $this->found)
	$this->update($data,$options);
  



  }


  function get_data(){
    $this->current=false;
    $sql=sprintf("select * from `Part Location Dimension` where `Part SKU`=%d and `Location Key`=%d",$this->part_sku,$this->location_key);
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->ok=true;
      $this->current=true;
    
    }

    $this->part=New Part($this->part_sku);
    $this->location=New Location($this->location_key);

  }

  function last_inventory_date(){
    $sql=sprintf("select `Date` from `Inventory Spanshot Fact` where  `Part Sku`=%d   order by `Date` desc limit 1",$this->part_sku);
    //   print $sql;
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
      return $row['Date'];
    }else
      return false;

  }





  function first_inventory_transacion(){
    $sql=sprintf("select DATE(`Date`) as Date from `Inventory Transaction Fact` 
    		where  `Part Sku`=%d and (`Inventory Transaction Type`='Associate' )  order by `Date`",$this->part_sku);
    $result=mysql_query($sql);
    // print $sql;
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
      return $row['Date'];
    }else
      return false;

  }
function last_inventory_audit(){
  $sql=sprintf("select DATE(`Date`) as Date from `Inventory Transaction Fact` where  `Part Sku`=%d and  `Location Key`=%d and (`Inventory Transaction Type`='Audit' or `Inventory Transaction Type`='Not Found' )  order by `Date` desc",$this->part_sku,$this->location_key);
    $result=mysql_query($sql);
    //print $sql;
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
      return $row['Date'];
    }else
      return false;

  }

function update_can_pick($value){


  // Note:Inverse Translation InvT
  if(preg_match('/^(yes|si)$/i',$value))
    $value='Yes';
  else
    $value='No';
  $sql=sprintf("update `Part Location Dimension` set `Can Pick`=%s ,`Last Updated`=NOW() where `Part SKU`=%d and `Location Key`=%d "
	       ,prepare_mysql($value)
		 ,$this->part_sku
		 ,$this->location_key
		 );
  if(mysql_query($sql)){
    $this->updated=true;
    $this->data['Can Pick']=$value;
  }
}


function audit($qty){
    
    if(!is_numeric($qty) or $qty<0){
      $this->error=true;
      $this->msg=_('Quantity On Hand should be a number');
    }
    
    $old_qty=$this->data['Quantity On Hand'];
    $old_value=$this->data['Stock Value'];

    $unit_cost=$this->get_unit_value(); 
    $value=$qty*$unit_cost;
    if(is_numeric($old_value) and   $old_value>=0){
      $qty_change=$qty-$old_qty;
      $value_change=$value-$old_value;
    }elseif($this->data['Negative Discrepancy']!=0){
      $qty_change=$qty+$this->data['Negative Discrepancy'];
      $value_change=$value+$this->data['Negative Discrepancy Value'];
    }else{
      $qty_change=$qty;
      $value_change=$value;
    
    }

    
    $sql=sprintf("update `Part Location Dimension` set `Quantity On Hand`=%f ,`Stock Value`=%f, `Last Updated`=NOW(),`Negative Discrepancy`=0,`Negative Discrepancy Value`=0  where `Part SKU`=%d and `Location Key`=%d "
		 ,$qty
		 ,$value
		 ,$this->part_sku
		 ,$this->location_key
		 );
    if(mysql_query($sql)){
      $this->updated=true;
      $this->data['Quantity On Hand']=$qty;
      $this->data['Stock Value']=$value;
      
      $this->part->load('stock');

      
      $details=sprintf("SKU%d4",$this->part_sku).' '._('adjust due to audit in').' '.$this->location->data['Location Code'].': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
      
      $this->event_order++;
      $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Event Order`) values (%d,%d,%s,%f,%.2f,%s,%s,%s,%d)"
		   ,$this->part_sku
		   ,$this->location_key
		   ,"'Adjust'"
		   ,$qty_change
		   ,$value_change
		   ,$this->editor['Author Key']
		   ,prepare_mysql($details,false)
		   ,prepare_mysql($this->editor['Date'])
		   ,$this->event_order
		   );
      if(!mysql_query($sql))
	print "Error can not audit liocation";

      $details=_('Audit').', '.sprintf("SKU%d4",$this->part_sku).' '._('stock in').' '.$this->location->data['Location Code'].' '._('set to').': '.number($qty);
      $this->event_order++;
      $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Event Order`) values (%d,%d,%s,%f,%.2f,%s,%s,%s,%d)"
		   ,$this->part_sku
		   ,$this->location_key
		   ,"'Audit'"
		   ,$qty
		   ,$value
		   ,$this->editor['Author Key']
		   ,prepare_mysql($details,false)
		   ,prepare_mysql($this->editor['Date'])
		   ,$this->event_order
		   );
      if(!mysql_query($sql))
	print "Error can not audit liocation";
      // print "$sql\n";

    }



  
    

  /*   $qty=$data['qty']; */
/*     $user_id=$data['user key']; */
/*     $note=$data['note']; */
/*  //   $options=$data['options']; */
/*     $date=$data['date']; */

/*     if($date==''){ */
/*       $date=date("Y-m-d H:i:s"); */

/*       if(preg_match('/force_update/',$date)){ */
/* 	$from=$this->last_inventory_date(); */
/* 	if(!$from){ */
/* 	  $from=$this->first_inventory_transacion(); */
/* 	} */
/* 	if($from){ */
/* 	  $this->redo_daily_inventory($from,''); */
/* 	} */
       
/*       } */
/*       $unitary_price=''; */
/*       $_date=date("Y-m-d",strtotime($date)); */
/*       $sql=sprintf("select `Value At Cost`,`Quantity On Hand` from `Inventory Spanshot Fact` where  `Part SKU`=%d  and `Location Key`=%d  and `Date`=%s ",$this->part_sku,$this->location_key,prepare_mysql($_date)); */
/*       $result=mysql_query($sql); */
/*       if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){ */
/* 	$old_value=$row['Value At Cost']; */
/* 	$old_qty=$row['Quantity On Hand']; */
/* 	if(is_numeric($old_qty)){ */
/* 	  $ok=true; */
/* 	  if($old_qty>0) */
/* 	    $unitary_price=$old_value/$old_qty; */


/* 	}else{ */
/* 	  $unitary_price=''; */
/* 	  $ok=false; */
/* 	}        */


/*       } */
     
/*       if(!is_numeric($unitary_price)){ */
/* 	$sql=sprintf(" select AVG(SPD.`Supplier Product Cost` * SPPL.`Supplier Product Units Per Part`) as cost from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%d  and `Supplier Product Part Most Recent`='Yes'    ",$this->part_sku); */
/* 	$result=mysql_query($sql); */
/* 	if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){ */
/* 	  $unitary_price=$row['cost']; */
	   
/* 	} */
       

/*       } */

/*       if(!is_numeric($user_id) or $user_id<0) */
/* 	$user_id='NULL'; */
/*       $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%f,%.2f,%s,%s,%s)" */
/* 		   ,$this->part_sku */
/* 		   ,$this->location_key */
/* 		   ,"'Audit'" */
/* 		   ,$qty */
/* 		   ,$qty*$unitary_price */
/* 		   ,$user_id */
/* 		   ,prepare_mysql($note) */
/* 		   ,prepare_mysql($date) */
/* 		   ); */
/*       if(!mysql_query($sql)) */
/* 	print "Error can not audit liocatun"; */
/*       // print "$sql\n"; */

/*     } */
/*     $this->redo_daily_inventory($_date,''); */

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
    $day_before_date = date ("Y-m-d", strtotime ($start_date."-1 day", strtotime($from)));
    $check_date = $start_date;
    $end_date =date("Y-m-d",$to);
    if($end_date=date("Y-m-d"))
      $uptodate=true;
    $i = 0;
   
   
    print sprintf("z Calculating inventory for part %s in location %s from %s to %s\n",$this->part_sku,$this->location_key,$start_date,$end_date);

    $qty_inicio='NULL';
    $value_inicio='NULL';
   


    $sql=sprintf("delete from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d and (`Date`>=%s and `Date`<=%s) "
		 ,$this->part_sku
		 ,$this->location_key
		 ,prepare_mysql($start_date)
		 ,prepare_mysql($end_date)
		 );
   
    mysql_query($sql);
    //print $sql;
   
   
    $sql=sprintf("select `Value At Cost`,`Quantity On Hand` from `Inventory Spanshot Fact` where  `Part SKU`=%d  and `Location Key`=%d  and `Date`=%s ",$this->part_sku,$this->location_key,prepare_mysql($day_before_date));
    //print $sql;
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
      $value_inicio=$row['Value At Cost'];
      $qty_inicio=$row['Quantity On Hand'];
    }

    //print $qty_inicio;
 //    if(!is_numeric($qty_inicio)){
//       $sql=sprintf("select `Inventory Transaction Quantity`,`Inventory Transaction Amount` from `Inventory Transaction Fact` where  `Part Sku`=%d and  `Location Key`=%d  and DATE(`Date`)<%s and `Inventory Transaction Type` in ('Audit','Not Found')  order by `Date` desc limit 1"
		    
// 		   ,$this->part_sku
// 		   ,$this->location_key
// 		   ,prepare_mysql($start_date));
       
//       $result2=mysql_query($sql);
//       if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
// 	$qty_inicio=$row2['Inventory Transaction Quantity'];  
// 	$value_inicio=$row2['Inventory Transaction Amount'];
//       }
//     }
     

    $associated=false;
    $sql=sprintf("select `Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part Sku`=%d and  `Location Key`=%d  and DATE(`Date`)<%s and `Inventory Transaction Type` in ('Associate','Disassociate')  order by `Date` desc limit 1"
		  
		 ,$this->part_sku
		 ,$this->location_key
		 ,prepare_mysql($start_date));
       
    $result2=mysql_query($sql);
    if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
      if($row2['Inventory Transaction Type']=='Associate')  
	$associated=true;;
    }





    //print "Inicios $check_date $end_date  Q: $qty_inicio  V:$value_inicio  \n";
     $neg_discrepancy=0;
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
      //   print "  $qty_inicio   $sql\n";
      //print "$check_date\n";
      while($row2=mysql_fetch_array($result3, MYSQL_ASSOC)   ){
	//print $row2['Inventory Transaction Type']." $associated\n";
	$qty=$row2['Inventory Transaction Quantity'];

	if($row2['Inventory Transaction Type']=='Associate' ){
	  $associated=true;
	}elseif($row2['Inventory Transaction Type']=='Disassociate' ){

	  //	  print "*********** Disasciote\n";
	  $associated=false;

	   
	}elseif($row2['Inventory Transaction Type']=='Audit' or $row2['Inventory Transaction Type']=='Not Found' ){
	  //print "AUDITTT!!!! ";
	  $neg_discrepancy=0;
	  if(!$associated)
	    continue;
	  if(is_numeric($qty_inicio)){
	    if($qty_inicio==0)
	      $cost=$this->part->get_unit_cost($check_date);
	    else
	      $cost=$value_inicio/$qty_inicio;
	  
	    $adjust_qty=$qty-$qty_inicio;
	    $adjust_amount=$adjust_qty*$cost;
	    $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`) values (%s,%d,%d,'Adjust',%s,%s)",prepare_mysql($row2['Date'])
			 ,$this->part_sku
			 ,$this->location_key
			 ,prepare_mysql($adjust_qty),prepare_mysql($adjust_amount));
	    //print "$sql\n";
	    if(!mysql_query($sql))
	      exit("$sql can into insert Inventory Transaction Fact ");
	    $qty_inicio=$qty;
	    $value_inicio+=$adjust_amount;

	  }else{
	    // print_r($this);
	    $cost=$this->part->get_unit_cost($check_date);
	    $qty_inicio=$qty;
	    $value_inicio=$qty*$cost;

	  }

	}else if($row2['Inventory Transaction Type']=='Sale'  ){
	  if(!$associated)
	    continue;
	  //print " *********SALE** ".$qty_inicio." *****\n";
	  
	  if(is_numeric($qty_inicio) and $qty_inicio>$row2['Inventory Transaction Quantity']){
	    $neg_discrepancy=$qty_inicio-$row2['Inventory Transaction Quantity'];
	    
	  }else if($qty_inicio=='NULL')
	    $neg_discrepancy-=$row2['Inventory Transaction Quantity'];
	  else
	    $neg_discrepancy=0;



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
	}else if($row2['Inventory Transaction Type']=='Move Out'  ){
	  if(!$associated)
	    continue;
	  
	  if(is_numeric($qty_inicio) and $qty_inicio>$row2['Inventory Transaction Quantity']){
	    $neg_discrepancy=$qty_inicio+$row2['Inventory Transaction Quantity'];
	    
	  }else if($qty_inicio=='NULL')
	    $neg_discrepancy+=$row2['Inventory Transaction Quantity'];
	  else
	    $neg_discrepancy=0;


	  if(is_numeric($value_inicio) and is_numeric($qty_inicio) and $qty_inicio>$row2['Inventory Transaction Quantity'] and $qty_inicio>0){
	    $cost=$value_inicio/$qty_inicio;
	    $qty_inicio+=$row2['Inventory Transaction Quantity'];
	    $value_inicio+=$cost*$row2['Inventory Transaction Quantity'];
	    //print " ***OUT ****** $cost  $qty_inicio  $value_inicio  *****\n";
	    

	  }else{
	    $qty_inicio='NULL';
	    $value_inicio='NULL';
	  }
	


	}else if($row2['Inventory Transaction Type']=='In'){
	  if(!$associated)
	    continue;

	  if(!is_numeric($qty_inicio))
	    $neg_discrepancy=-$row2['Inventory Transaction Quantity'];
	  else
	    $neg_discrepancy=0;




	  if(is_numeric($qty_inicio))
	    $qty_inicio+=$row2['Inventory Transaction Quantity'];
	  if(is_numeric($value_inicio))
	    $value_inicio+=$row2['Inventory Transaction Amount'];
	  $qty_in+=$row2['Inventory Transaction Quantity'];
	}else if($row2['Inventory Transaction Type']=='Move In'){
	  
	  //	  print " ***IN  ******  $qty_inicio  $value_inicio  *****\n";
	  if(!$associated)
	    continue;


	  if(is_numeric($qty_inicio))
	    $qty_inicio+=$row2['Inventory Transaction Quantity'];
	  if(is_numeric($value_inicio))
	    $value_inicio+=$row2['Inventory Transaction Amount'];

	  
	  // print " ***IN  ******  $qty_inicio  $value_inicio  *****\n";


	}




      }//end if the day


     
      //
      if($associated){

	if(is_numeric($qty_inicio))
	  $last_selling_price=$qty_inicio*$this->get_selling_price($this->part_sku,$check_date);
	else
	  $last_selling_price='NULL';
	
	

	if(    !is_numeric($qty_inicio)   or   $qty_inicio<0  ){
	//	if($qty_inicio<0 or $qty_inicio=='NULL' or !is_numeric($qty_inicio)){
	  $qty_inicio='NULL';
	  $value_inicio='NULL';
	  $last_selling_price='NULL';
	}else{
	  $daysin++;
	  $qty_inicio=sprintf("%.6f",$qty_inicio);
	  $value_inicio=sprintf("%.2f",$value_inicio);
	}
	$amount_sold=-1*$amount_sold;
	

	//	print "-----  $check_date $qty_inicio   \n";
	//   echo "$this->part_sku  $check_date $qty_inicio $value_inicio $amount_sold $last_selling_price  \n";

	$sql=sprintf("insert into `Inventory Spanshot Fact` (`Date`,`Part SKU`,`Location Key`,`Quantity on Hand`,`Value at Cost`,`Sold Amount`,`Value at Latest Selling Price`,`Storing Cost`,`Quantity Sold`,`Quantity In`) values (%s,%d,%d,%s,%s,%.6f,%.2f,%s,%f,%f)"
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
       
     
	//	print "$sql\n";
      }

      $i++;
      if ($i > 7000) { die ('Error!'); } 


      $check_date = date ("Y-m-d", strtotime ("+1 day", strtotime($check_date)));

    }



    if($uptodate and $associated){

     

       PRINT "Negative discrepancey: $neg_discrepancy\n";
      if($neg_discrepancy!=0)
	$neg_discrepancy_value= $neg_discrepancy*$this->part->get_unit_cost();
      else
	$neg_discrepancy_value=0;
      

      if($this->current){
	$sql=sprintf("update `Part Location Dimension` set `Quantity on Hand`=%s ,`Stock Value`=%s ,`Last Updated`=NOW() ,`Negative Discrepancy`=%f ,`Negative Discrepancy Value`=%f where `Part SKU`=%d and `Location Key`=%d ",$qty_inicio,$value_inicio,$neg_discrepancy,$neg_discrepancy_value,$this->part_sku,$this->location_key);
	//	print $sql;
	if(!mysql_query($sql))
	  print "error can no uopdate part location dimensiom $sql";
      }else{
	$location=new Location($this->location_key);
	if($location->data['Location Mainly Used For']=='Picking')
	  $can_pick='Yes';
	else
	  $can_pick='No';

	if($location->id==1)
	  $can_pick='Yes';

	$sql=sprintf("insert into `Part Location Dimension` (`Quantity on Hand`,`Stock Value`,`Last Updated`,`Part SKU`,`Location Key`,`Can Pick`,`Negative Discrepancy`,`Negative Discrepancy Value`) values (%s,%s,NOW(),%d,%d,%s,%f,%f)",$qty_inicio,$value_inicio,$this->part_sku,$this->location_key,prepare_mysql($can_pick),$neg_discrepancy,$neg_discrepancy_value);
	//	print "$sql\n";
       	if(!mysql_query($sql))
	  print "error can no insert part location dimensiom $sql";
      }
      //$part=new Part('sku',$this->part_sku);
      //$part->load('stock');
    }else{
      
      //  print "------------".$this->current."---------------------------";
      if($this->current){
	$sql=sprintf("delete from  `Part Location Dimension` where `Part SKU`=%d and `Location Key`=%d ",$this->part_sku,$this->location_key);
	//print $sql;
	mysql_query($sql);
      }
    }
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

    // print_r($data);

    $this->data=$this->base_data();
    foreach($data as $key=>$value){
      if(array_key_exists($key,$this->data))
	  $this->data[$key]=_trim($value);
    }
    $keys='(';$values='values(';
    foreach($this->data as $key=>$value){
      $keys.="`$key`,";
      $_mode=true;
      if($key=='Last Updated')
	$values.='NOW(),';
      else
	$values.=prepare_mysql($value,$_mode).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Part Location Dimension` %s %s",$keys,$values);
      
    if(mysql_query($sql)){
      $this->id= mysql_insert_id();
      $this->new=true;
      $this->get_data('id',$this->id);
      $note=_('Part added to location');
      $details=_('Part')." SKU".sprintf("%05d",$this->part->sku)." "._('associated with location').": ".$this->location->data['Location Code'];
     

      //$date=date("Y-m-d H:i:s");
      $this->event_order++;

       $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Event Order`) values (%d,%d,%s,%f,%.2f,%s,%s,%s,%s)"
		    ,$this->part_sku
		    ,$this->location_key
		    ,"'Associate'"
		    ,0
		    ,0
		    ,$this->editor['Author Key']
		    ,prepare_mysql($details)
		    ,prepare_mysql($this->editor['Date'])
		    ,$this->event_order
		    );
       mysql_query($sql);
      
       
       $part=new Part($this->part_sku);
       $part->load('locations');
       $location=new Location($this->location_key);
       $location->load('parts');
       
    }else{
      exit($sql);
    }

  }
function create_inventory_spanshopt($data){
    
    if(!isset($data['user key']))
      $user_id='NULL';
    else
      $user_id=$data['user key'];
    if(!is_numeric($user_id) or $user_id<0)
      $user_id='NULL';


    if(isset($data['date']) and $data['date']!='')
      $date=$data['date'];
    else
      $date=date("Y-m-d H:i:s");

    if(isset($data['note']))
      $note=$data['note'];
    else
      $note='NULL';

    if(isset($data['options']))
      $options=$data['options'];
    
    $sql=sprintf("select * from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d and `Date`=%s ",
		 $this->part_sku
		 ,$this->location_key
		 ,prepare_mysql($date)
		 );
    $result=mysql_query($sql);
    $num_rows = mysql_num_rows($result);
   
    if($num_rows==0){

      if(!is_numeric($user_id) or $user_id<0)
	$user_id='NULL';
      
      $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%f,%.2f,%s,%s,%s)"
		   ,$this->part_sku
		   ,$this->location_key
		   ,"'Associate'"
		   ,0
		   ,0
		   ,$user_id
		    ,addslashes($note)
		   ,prepare_mysql($date)
		   );
      mysql_query($sql);
      
      if(!preg_match('/unknown/i',$options)){
      $date=date("Y-m-d H:i:s",strtotime($date." +1 second"));
      $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%f,%.2f,%s,%s,%s)"
		   ,$this->part_sku
		   ,$this->location_key
		   ,"'Audit'"
		   ,0
		   ,0
		    ,$this->editor['Author Key']
		   ,addslashes($note)
		   ,prepare_mysql($date)
		   );
      mysql_query($sql);
      }
      //print "$sql\n";
      $_date=date("Y-m-d",strtotime($date));
      $this->redo_daily_inventory($_date,'');




    }
  }
function delete(){
    $this->deleted=false;
    if( is_numeric($this->data['Quantity On Hand']) and  $this->data['Quantity On Hand']>0){
      $this->deleted_msg=_('There is still stock in this location');
      return;
    }
    $sql=sprintf("delete from `Part Location Dimension` where  `Part SKU`=%d and `Location Key`=%d"
		 ,$this->part_sku
		 ,$this->location_key
		 );
    if(mysql_query($sql)){
            $details=_('Part')." SKU".sprintf("%05d",$this->part->sku)." "._('disassociated from location').": ".$this->location->data['Location Code'];
	    
	    $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%s,%s,%s,%s,%s)"
			 ,$this->part_sku
			 ,$this->location_key
			 ,"'Disassociate'"
			 ,0
			 ,0
			 ,$this->editor['Author Key']
			 ,prepare_mysql($details)
		     ,prepare_mysql($this->editor['Date'])
		     );
	mysql_query($sql);

      
      $this->deleted=true;
      $this->deleted_msg=_('Part no longer ssociated with location');
 

    }

 }
function destroy($data){

    $user_id=$data['user key'];
    $note=$data['note'];

    if(isset($data['options']))
      $options=$data['options'];
    else
      $options='';
   
    
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
		 ,prepare_mysql($_date)
		 );
    $result=mysql_query($sql);
    $num_rows = mysql_num_rows($result);
    //print "$sql $num_rows\n" ;
    if($num_rows==1){
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result, MYSQL_ASSOC);
      $qty=$row['Quantity On Hand'];
      $value=$row['Value At Cost'];
      


      if(!is_numeric($qty) or  $qty==0){
	$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%s,%s,%s,%s,%s)"
		     ,$this->part_sku
		     ,$this->location_key
		     ,"'Disassociate'"
		     ,0
		     ,0
		     ,$user_id
		     ,prepare_mysql($note)
		     ,prepare_mysql($date)
		     );
	mysql_query($sql);
	//	print $sql;
	$part=new Part($this->part_sku);
       
	$part->load('calculate_stock_history','last');
	$part->load('stock');
	return;


      }else if(is_numeric($qty) and $qty>0){
	$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%s,%s,%s,%s,%s)"
		     ,$this->part_sku
		     ,$this->location_key
		     ,"'Move Out'"
		     ,$qty
		     ,$value
		     ,$user_id
		     ,prepare_mysql($note)
		     ,prepare_mysql($date)
		     );
	mysql_query($sql);
	$__date=date("Y-m-d H:i:s",strtotime("$date +1 second"));
	 
	$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%s,%s,%s,%s,%s)"
		     ,$this->part_sku
		     ,$this->location_key
		     ,"'Disassociate'"
		     ,0
		     ,0
		     ,$user_id
		     ,prepare_mysql($note)
		     ,prepare_mysql($__date)
		     );
	mysql_query($sql);
	

	 

	 
	$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%s,%s,%s,%s,%s)"
		     ,$this->part_sku
		     ,1
		     ,"'Move In'"
		     ,$qty
		     ,$value
		     ,$user_id
		     ,prepare_mysql($note)
		     ,prepare_mysql($date)
		     );
	mysql_query($sql);

	//	$unk=new PartLocation('1_'.$this->part_sku);
       	$part=new Part($this->part_sku);
	$part->load('calculate_stock_history','last');
	$part->load('stock');

      }
    }else{
      //close it any way
      $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%s,%s,%s,%s,%s)"
		     ,$this->part_sku
		   ,$this->location_key
		   ,"'Disassociate'"
		     ,0
		     ,0
		     ,$user_id
		   ,prepare_mysql($note)
		     ,prepare_mysql($date)
		     );
	mysql_query($sql);
	$part=new Part($this->part_sku);
	$part->load('calculate_stock_history','last');
	$part->load('stock');
	return;



      }

   
    
       
  }
function get_unit_value(){


    $old_qty=$this->data['Quantity On Hand'];
    $old_value=$this->data['Stock Value'];
        
    if(is_numeric($old_value) and   $old_value>=0 and is_numeric($old_qty) and   $old_qty>0   ){
      return $old_value/$old_qty;
    }elseif($this->data['Negative Discrepancy']!=0){
      $qty_change=$qty+$this->data['Negative Discrepancy'];
      if(is_numeric($this->data['Negative Discrepancy Value']) and   $this->data['Negative Discrepancy Value']<=0)
	return $this->data['Negative Discrepancy Value']/$this->data['Negative Discrepancy'];
      else
	return $this->part->get('Unit Cost',$this->editor['Date']);
    
   
      
    }else{

      return $this->part->get('Unit Cost',$this->editor['Date']);
     
    
    }
    
    

}


function add_stock($data){
   $this->stock_transfer(array(
			       'Quantity'=>$data['Quantity']
			       ,'Transaction Type'=>'Supplier Delivery'
			       ,'Destination'=>$this->location_key
			       ,'Origin'=>$data['Origin']
			       ));

}

function move_stock($data){

  if($this->error){
    $this->msg=_('Unknown error');
        return;  
  }

  if($data['Quantity To Move']=='all'){
    $data['Quantity To Move']=$this->data['Quantity On Hand'];

  }


  if(!is_numeric($this->data['Quantity On Hand'])){
    $this->error=true;
    $this->msg=_('Unknown stock in this location');
        return;    
  }
   if($this->data['Quantity On Hand']<$data['Quantity To Move']){
        $this->error=true;
        $this->msg=_('To Move Quantity greater than the stock on the location');
        return;    
    }
   
   if($data['Destination Key']==$this->location_key){
     $this->error=true;
     $this->msg=_('Destination location is the same as this one');
     return;   
   }
   
   $destination_data=array('Location Key'=>$data['Destination Key'],'Part SKU'=>$this->part_sku,'editor'=>$this->editor);
   $destination=new PartLocation('find',$destination_data,'create');
   
   if(!is_numeric($destination->data['Quantity On Hand'])){
     $this->error=true;
     $this->msg=_('Unknown stock in the destination location');
     return;    
   }

   $this->stock_transfer(array(
			       'Quantity'=>-$data['Quantity To Move']
			       ,'Transaction Type'=>'Move Out'
			       ,'Destination'=>$destination->location->data['Location Code']
			       ));
   if($this->error){
     return;  
  }

   $destination->stock_transfer(array(
				      'Quantity'=>$data['Quantity To Move']
				      ,'Transaction Type'=>'Move In'
				      ,'Origin'=>$this->location->data['Location Code']
				      ));
  
   $this->location->load('parts');
   $destination->location->load('parts');

}

function set_stock_as_lost($data){

if(!is_numeric($this->data['Quantity On Hand'])){
        $this->error;
        $this->msg=_('Unknown stock in the location');
        return;    
    }

    if($this->data['Quantity On Hand']<$data['Lost Quantity']){
        $this->error;
        $this->msg=_('Lost Quantity greater than the stock on the location');
        return;    
    }

    $qty=$data['Lost Quantity']*-1;
    
    $_data=array(
		 'Quantity'=>$qty
		 ,'Transaction Type'=>'Lost'
		 ,'Reason'=>$data['Reason']
		 ,'Action'=>$data['Action']
		 );
    //print_r($_data);
    
    $this->stock_transfer($_data);

}

function stock_transfer($data){
  
 

    $qty=$data['Quantity'];
    $transaction_type=$data['Transaction Type'];
  
    if(is_numeric($this->data['Quantity On Hand'])){
      $old_qty=$this->data['Quantity On Hand'];
      $old_value=$this->data['Stock Value'];
    }else{
      $old_qty=$this->data['Negative Discrepancy'];
      $old_value=$this->data['Negative Discrepancy Value'];
    
    }
    $unit_value=$this->get_unit_value();
    $new_qty=$old_qty+$qty;
    $new_value=$new_qty*$unit_value;
    
    if($new_qty>=0){
    $sql=sprintf("update `Part Location Dimension` set `Quantity On Hand`=%f ,`Stock Value`=%f, `Last Updated`=NOW() ,`Negative Discrepancy`=0,`Negative Discrepancy Value`=0  where `Part SKU`=%d and `Location Key`=%d "
		 ,$new_qty
		 ,$new_value
		 ,$this->part_sku
		 ,$this->location_key
		 );


	}else{
	$sql=sprintf("update `Part Location Dimension` set `Quantity On Hand`=NULL ,`Stock Value`=NULL, `Last Updated`=NOW() ,`Negative Discrepancy`=%f,`Negative Discrepancy Value`=%f  where `Part SKU`=%d and `Location Key`=%d "
		 ,$new_qty
		 ,$new_value
		 ,$this->part_sku
		 ,$this->location_key
		 );
	
	


	}
    mysql_query($sql);
    $this->get_data();

    
    
    $qty_change=$qty;
    $value_change=$qty_change*$unit_value;
    
    $details='';
    
    switch($transaction_type){
    
    
    case('Lost'):
      $tmp=$data['Reason'].', '.$data['Action'];
      $tmp=preg_replace('/, $/','',$tmp);
      if(preg_match('/^\s*,\s*$/',$tmp))
	$tmp='';
      else
	$tmp=' '.$tmp;
      
        $details=number(-$qty).'x '.sprintf("SKU%04d",$this->part_sku).' '._('lost from').' '.$this->location->data['Location Code'].$tmp.': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
	break;   
    case('Move Out'):
      $details=number(-$qty).'x '.sprintf("SKU%04d",$this->part_sku).' '._('move out from').' '.$this->location->data['Location Code'].' '._('to').' '.$data['Destination'].': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
      break;
    case('Move In'):
      $details=number($qty).'x '.sprintf("SKU%04d",$this->part_sku).' '._('move in to').' '.$this->location->data['Location Code'].' '._('from').' '.$data['Origin'].': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
      
      break;
    case('Supplier Delivery'):

      

      $details=number($qty).'x '.sprintf("SKU%04d",$this->part_sku).' '._('received in').' '.$this->location->data['Location Code'].' '._('from').' '.$data['Origin'].': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
    }

    
    $editor=$this->get_editor_data();

    $this->event_order++;
    $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Event Order`) values (%d,%d,%s,%f,%.2f,%s,%s,%s,%d)"
		 ,$this->part_sku
		 ,$this->location_key
		 ,prepare_mysql($transaction_type)
		 ,$qty_change
		   ,$value_change
		 ,$this->editor['Author Key']
		 ,prepare_mysql($details,false)
		 ,prepare_mysql($editor['Date'])
		 ,$this->event_order
		 );
    
    //print_r($this->editor);
      mysql_query($sql);
      


//     $move_to=$data['move_to'];
//     $user_id=$data['user key'];
//     $note_associate='';
//     if(isset($data['note_associate']))
//       $note_associate=$data['note_associate'];
//     $note_out='';
//     if(isset($data['note_out']))
//       $note_out=$data['note_out'];
//     $note_in='';
//     if(isset($data['note_in']))
//       $note_in=$data['note_in'];
//     
//     $qty=$data['qty'];
// 
// 
//     if((!is_numeric($qty) ) and $qty!='all'   )
//       return;
// 
// 
//     if(!is_numeric($user_id) or $user_id<0)
//       $user_id='NULL';
//     
//     if(isset($data['date']) and $data['date']!='')
//       $date=$data['date'];
//     else
//       $date=date("Y-m-d H:i:s");
//     
//     $_date=date("Y-m-d",strtotime($date));
//     
//     $sql=sprintf("select * from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d and `Date`=%s ",
// 		 $this->part_sku
// 		 ,$this->location_key
// 		 ,prepare_mysql($_date)
// 		 );
// 
//     
//     $result=mysql_query($sql);
//     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//    
//       //       if(!is_numeric($row['Quantity On Hand']) or $row['Quantity On Hand']==0   )
//       //	return;
// 
//       if($qty=='all'){
// 	$qty=$row['Quantity On Hand'];
//       }elseif($row['Quantity On Hand']>$qty)
// 	 $qty=$row['Quantity On Hand'];
//       
//       if(!is_numeric($qty)  )
// 	$qty='NULL';
// 
// 
// 
//       if(!is_numeric($row['Value At Cost'])  or !is_numeric($qty)  )
// 	$value='NULL';
//       else{
// 	if($qty==0)
// 	  $value=0;
// 	else
// 	  $value=sprintf("%.2f",$row['Value At Cost']*$qty/$row['Quantity On Hand']);
// 
//       }
//       
//       
//       if($qty>0 and is_numeric($qty) ){
//     
//       	$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%s,%s,%s,%s,%s)"
// 		     ,$this->part_sku
// 		     ,$this->location_key
// 		     ,"'Move Out'"
// 		     ,-$qty
// 		     ,-$value
// 		     ,$user_id
// 		     ,prepare_mysql($note_out,false)
// 		     ,prepare_mysql($date)
// 		     );
// 	if(!mysql_query($sql))
// 	  print "Error   $sql\n";
// 	
// 	$_loc=new Location($this->location_key);
// 	$_loc->load('parts_data');
// 	
//       }
// 
//       $__date=date("Y-m-d H:i:s",strtotime($date." -1 second"));
//       $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`History Type`) values (%d,%d,%s,%s,%s,%s,%s,%s,'Detail')"
// 		   ,$this->part_sku
// 		   ,$move_to
// 		   ,"'Associate'"
// 		   ,0
// 		   ,0
// 		   ,$user_id
// 		   ,prepare_mysql($note_associate,false)
// 		   ,prepare_mysql($__date)
// 		   );
// 
//       //print_r($data);
//       //print "$sql\n";
//       if(!mysql_query($sql))
// 	print "Error $sql\n";
//     
//     
// 
// 
//       $__date=date("Y-m-d H:i:s",strtotime($date." +0 second"));
//       $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`History Type`) values (%d,%d,%s,%s,%s,%s,%s,%s,'Admin')"
// 		   ,$this->part_sku
// 		   ,$move_to
// 		   ,"'Audit'"
// 		   ,0
// 		   ,0
// 		   ,$user_id
// 		   ,"''"
// 		   ,prepare_mysql($__date)
// 		   );
//       if(!mysql_query($sql))
// 	print "Error $sql\n";
//  
//       if($qty>0 and is_numeric($qty) ){
// 	$__date=date("Y-m-d H:i:s",strtotime($date." +1 second"));
// 	$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%s,%s,%s,%s,%s)"
// 		     ,$this->part_sku
// 		     ,$move_to
// 		     ,"'Move In'"
// 		     ,$qty
// 		     ,$value
// 		     ,$user_id
// 		     ,prepare_mysql($note_in,false)
// 		     ,prepare_mysql($__date)
// 		     );
// 	
// 	if(!mysql_query($sql))
// 	  print "Error $sql\n";
//       }	
// 	$_loc=new Location($move_to);
// 	$_loc->load('parts_data');
// 
//     }
//     
//     $part=new Part($this->part_sku);
//     $part->load('calculate_stock_history','last');

   
 
  }


  function associate($data=false){
    
    $base_data=array('date'=>date('Y-m-d H:i:s'),'note'=>'','metadata'=>'','history_type'=>'Admin');
    if(is_array($data)){
      foreach($data as $key=>$val){
	$base_data[$key]=$val;
      }
    }
    $sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%d,%d,'Associate',0,0,%s,%s,%s)"
		 ,prepare_mysql($base_data['date'])
		 ,$this->part_sku
		 ,$this->location_key
		 ,prepare_mysql($base_data['note'],false)
		 ,prepare_mysql($base_data['metadata'],false)	
		 ,prepare_mysql($base_data['history_type'],false)	

		 );
    //print_r($base_data);
    // print "$sql\n";
    // exit;
    if(!mysql_query($sql))
      exit("$sql can into insert Inventory Transaction Fact star AA");
  }

function update_field_switcher($field,$value,$options=''){
  switch($field){
  case('Quantity On Hand'):
    $this->audit($value);
    break;
  case('Can Pick'):
    $this->update_can_pick($value);
    break;

    
  }   
 }


  }
  ?>