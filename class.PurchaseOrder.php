<?php
include_once('class.DB_Table.php');


class PurchaseOrder extends DB_Table{
 
  function PurchaseOrder($arg1=false,$arg2=false) {

    $this->table_name='Purchase Order';
    $this->ignore_fields=array('Purchase Order Key');
    

    if(is_string($arg1)){
    if(preg_match('/new|create/i',$arg1)){
      $this->create_order($arg2);
      return;
    }
    }
    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return;
    }
    $this->get_data($arg1,$arg2);

  }



  function create_order($data){
    
    //print_r($data);
    $data['Purchase Order Creation Date']=date('Y-m-d H:i:s');
    $data['Purchase Order Last Updated Date']=date('Y-m-d H:i:s');
    $data['Purchase Order Public ID']=$this->get_next_public_id($data['Purchase Order Supplier Key']);
    $data['Purchase Order File As']=$this->get_file_as($data['Purchase Order Public ID']);
    $base_data=$this->base_data();

    foreach($data as $key=>$value){
      if(array_key_exists($key,$base_data))
	$base_data[$key]=_trim($value);
    }
    //  print_r($base_data);
         
    
    $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";

      if(preg_match('/XHTML/',$key))
	$values.="'".addslashes($value)."',";
      else
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Purchase Order Dimension` %s %s",$keys,$values);

    //  print($sql);

    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->get_data('id',$this->id);
    }else
      exit(" error can no create purchse order");


  }

 function get_data($key,$id){
    if($key=='id'){
      $sql=sprintf("select * from `Purchase Order Dimension` where `Purchase Order Key`=%d",$id);
      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->id=$this->data['Purchase Order Key'];
      }
    }elseif($key=='public id' or $key=='public_id'){
      $sql=sprintf("select * from `Purchase Order Dimension` where `Purchase Order Public ID`=%s",prepare_mysql($id));
      $result=mysql_query($sql);
      print "$sql\n";
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->id=$this->data['Purchase Order Key'];
      }
    }
 }

function get($key=''){

   if (array_key_exists ( $key, $this->data ))
      return $this->data [$key];
	 
   if($key=='Number Items')
     return number($this->data ['Purchase Order Number Items']);
   if (preg_match('/^(Total|Items|(Shipping |Charges )?Net).*(Amount)$/',$key)) {
     $amount='Purchase Order '.$key;
     return money($this->data[$amount]);
   }	
   if (preg_match('/Date$/',$key)) {
     $date='Purchase Order '.$key;
     return strftime("%e-%b-%Y %H:%M",strtotime($this->data[$date]));
   }	
   
      


    if(array_key_exists($key,$this->data))
      return $this->data[$key];
    
}


  function add_order_transaction($data) {
  
    include_once('class.TaxCategory.php');
    $tax_category=new TaxCategory($data['tax_code']);
    $tax_amount=$tax_category->calculate_tax($data ['amount']);


    if($this->data['Purchase Order Current Dispatch State']=='In Process'){

      if($data ['qty']==0){
	 $sql=sprintf("delete from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d and `Supplier Product Key`=%d ",$this->id,$data ['Supplier Product Key']);
	  mysql_query($sql);
      }else{


      $sql=sprintf("select `Purchase Order Line` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d and `Supplier Product Key`=%d ",$this->id,$data ['Supplier Product Key']);
      $res=mysql_query($sql);
      if($row=mysql_fetch_array($res)){
	 $sql = sprintf ( "update`Purchase Order Transaction Fact` set  `Purchase Order Quantity`=%f,`Purchase Order Last Updated Date`=%s,`Purchase Order Net Amount`=%f ,`Purchase Order Tax Amount`=%f   where `Purchase Order Key`=%d and `Purchase Order Line`=%d "
			  ,$data ['qty']
			  ,prepare_mysql ( $data ['date'] )
			  , $data ['amount']
			  , $tax_amount
			  ,$this->id
			  ,$row['Purchase Order Line']
			  );
	 //	print "$sql";
	 mysql_query($sql);
	 
      }else{
	$sql = sprintf ( "insert into `Purchase Order Transaction Fact` (`Purchase Order Tax Code`,`Currency Code`,`Purchase Order Last Updated Date`,`Supplier Product Key`,`Purchase Order Current Dispatching State`,`Supplier Key`,`Purchase Order Key`,`Purchase Order Line`,`Purchase Order Quantity`,`Purchase Order Quantity Type`,`Purchase Order Net Amount`,`Purchase Order Tax Amount`) values (%s,%s,%s,%d,  %s    ,%d,%d,%d, %.6f,%s,%.2f,%.2f)   "
			 , prepare_mysql ( $data['tax_code'] )
			 , prepare_mysql ( $this->data ['Purchase Order Currency Code'] )
			 , prepare_mysql ( $data ['date'] )
			 , $data ['Supplier Product Key']

			 , prepare_mysql ( $data ['Current Dispatching State'] )

			 , $this->data['Purchase Order Supplier Key' ] 
			 , $this->data ['Purchase Order Key']
			 , $data ['line_number']

			 , $data ['qty']
			 , prepare_mysql ( $data ['qty_type'] )
			 , $data ['amount']
			 , $tax_amount
			 
		  );
	//	print "$sql";
	mysql_query($sql);
      }
      }

    }else{


   
		
    }

  


    return array('to_charge'=>money($data ['amount'],$this->data['Purchase Order Currency Code']),'qty'=>$data ['qty']);
		
    //  print "$sql\n";


  }
	

	
 function get_next_line_number(){
    
    $sql=sprintf("select MAX(`Purchase Order Line`) as max_line from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d ",$this->id);
    $res=mysql_query($sql);
    
    $line_number=1;
    if($row=mysql_fetch_array($res))
      $line_number=(int) $row['max_line']+1;
    return $line_number;
    
    
  }


 function get_next_public_id($supplier_key){
   $supplier=new Supplier($supplier_key);
   $code=$supplier->data['Supplier Code'];

   $sql=sprintf("select `Purchase Order Public ID` from `Purchase Order Dimension` where `Purchase Order Supplier Key`=%d order by REPLACE(`Purchase Order Public ID`,%s,'') desc limit 1",$supplier_key,prepare_mysql($code));
   $res=mysql_query($sql);
   
   $line_number=1;
   if($row=mysql_fetch_array($res))
     $line_number= (int) preg_replace('/[^\d]/','',$row['Purchase Order Public ID'])+1;
   
   return sprintf('%s%04d',$code,$line_number);
   
 }

 function get_file_as($name){

   return $name;
 }


 function update_item_totals_from_order_transactions(){




   $sql = "select count(Distinct `Supplier Product Key`) as num_items ,sum(`Purchase Order Net Amount`) as net, sum(`Purchase Order Tax Amount`) as tax,  sum(`Purchase Order Shipping Amount`) as shipping from `Purchase Order Transaction Fact` where `Purchase Order Key`=" . $this->id;
   //print "$sql\n";
   $result = mysql_query ( $sql );
   if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
     //	  $total = $row ['gross'] + $row ['tax'] + $row ['shipping']  - $row ['discount'] + $this->data ['Order Items Adjust Amount'];
     
	  $this->data ['Purchase Order Items Net Amount'] = $row ['net'];
	  $this->data ['Purchase Order Number Items'] = $row ['num_items'];

	  
	  $sql = sprintf ( "update `Purchase Order Dimension` set `Purchase Order Number Items`=%d , `Purchase Order Items Net Amount`=%.2f , `Purchase Order Items Tax Amount`=%.2f where  `Purchase Order Key`=%d "
			   , $this->data ['Purchase Order Number Items']
			   , $this->data ['Purchase Order Items Net Amount']
			   , $this->data ['Purchase Order Items Tax Amount']
			   
			   
			   , $this->id);
	  

	  //exit;
	  mysql_query ( $sql );				
	  
	  
   }  
	    
	
 }

 function get_number_items(){
   $num_items=0;
   $sql=sprintf("select count(Distinct `Supplier Product Key`) as num_items  from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d",$this->id);
   $result = mysql_query ( $sql );
   if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
     $num_items=$row['num_items'];
   }

   return $num_items;
 }

 



 function update_totals_from_order_transactions($force_total=false){
  

    if(!$force_total)
      $force_total=array();
    

   
    $this->data ['Purchase Order Total Tax Amount'] = $this->data ['Purchase Order Items Tax Amount'] + $this->data ['Purchase Order Shipping Tax Amount']+  $this->data ['Purchase Order Charges Tax Amount']+  $this->data ['Purchase Order Tax Credited Amount'];
    $this->data ['Purchase Order Total Net Amount']=$this->data ['Purchase Order Items Net Amount']+  $this->data ['Purchase Order Shipping Net Amount']+  $this->data ['Purchase Order Charges Net Amount']+  $this->data ['Purchase Order Net Credited Amount'];
    
    $this->data ['Purchase Order Total Amount'] = $this->data ['Purchase Order Total Tax Amount'] + $this->data ['Purchase Order Total Net Amount'];
    $this->data ['Purchase Order Total To Pay Amount'] = $this->data ['Purchase Order Total Amount'];
    $sql = sprintf ( "update `Purchase Order Dimension` set `Purchase Order Total Net Amount`=%.2f ,`Purchase Order Total Tax Amount`=%.2f ,`Purchase Order Shipping Net Amount`=%.2f ,`Purchase Order Shipping Tax Amount`=%.2f ,`Purchase Order Charges Net Amount`=%.2f ,`Purchase Order Charges Tax Amount`=%.2f ,`Purchase Order Total Amount`=%.2f , `Purchase Order Total To Pay Amount`=%.2f  where  `Purchase Order Key`=%d "
		     , $this->data ['Purchase Order Total Net Amount']	  
		     , $this->data ['Purchase Order Total Tax Amount']
		     , $this->data ['Purchase Order Shipping Net Amount']
		     , $this->data ['Purchase Order Shipping Tax Amount']
		     
		     , $this->data ['Purchase Order Charges Net Amount']
		     , $this->data ['Purchase Order Charges Tax Amount']
		     
		     , $this->data ['Purchase Order Total Amount']
		     , $this->data ['Purchase Order Total To Pay Amount']
		     , $this->data ['Purchase Order Key'] 
		     );
    
    
	  //exit;
    
    
    if (! mysql_query ( $sql ))
      exit ( "$sql eroro2 con no update totals" );



	
  }



 function delete(){

   if($this->data['Purchase Order Current Dispatch State']=='In Process'){
     $sql=sprintf("delete from `Purchase Order Dimension` where `Purchase Order Key`=%d",$this->id);
     mysql_query($sql);
     $sql=sprintf("delete from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d",$this->id);
     mysql_query($sql);
   }else{
     $this->error=true;
     $this->msg='Can not deleted submitted purchase orders';
   }
 }


function submit($data){

  $date=$data['Purchase Order Submitted Date'];
  
  
  $sql=sprintf("update `Purchase Order Dimension` set `Purchase Order Submitted Date`=%s,`Purchase Order Current Dispatch State`='Submitted'   where `Purchase Order Key`=%d",prepare_mysql($date),$this->id);
  mysql_query($sql);

  $sql=sprintf("update `Purchase Order Transaction Fact` set  `Purchase Order Last Updated Date`=%s `Purchase Order Current Dispatching State`='Submitted'  where `Purchase Order Key`=%d",prepare_mysql($date),$this->id);
   mysql_query($sql);
}


}
?>