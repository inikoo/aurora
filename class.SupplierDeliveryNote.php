<?php
include_once('class.DB_Table.php');


class SupplierDeliveryNote extends DB_Table{
 
  function SupplierDeliveryNote($arg1=false,$arg2=false,$arg3=false) {

    $this->table_name='Supplier Delivery Note';
    $this->ignore_fields=array('Supplier Delivery Note Key');
    

    if(is_string($arg1)){
      if(preg_match('/new|create/i',$arg1)){
	$this->find($arg2,'create');
	return;
      }
      if(preg_match('/find/i',$arg1)){
	$this->find($arg2,$arg3);
	return;
      }


    }



    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return;
    }
    $this->get_data($arg1,$arg2);

  }


  function find($raw_data,$options){
     if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
      }
    }
    
    $this->found=false;
    $this->found_key=false;
     $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }

    $data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$data))
	$data[$key]=_trim($value);
    }

    if($data['Supplier Delivery Note Supplier Key'] and $data['Supplier Delivery Note Public ID']){
      $sql=sprintf("select `Supplier Delivery Note Key` from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Public ID`=%s  and `Supplier Delivery Note Supplier Key`=%d "
		   ,prepare_mysql($data['Supplier Delivery Note Public ID'])
		   ,$data['Supplier Delivery Note Supplier Key']
		   ); 

      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->found=true;
	$this->found_key=$row['Supplier Delivery Note Key'];
    }
    }

    if($this->found_key){
      $this->get_data('id',$this->found_key);
    }

    if($create and !$this->found_key){

      $this->create($data);
      
    }


  }


  function create($data){
    
    if($data['Supplier Delivery Note Public ID']==''){
      $this->error=true;
      $this->msg='NO public id';
      return;
    }
    $supplier=new Supplier($data['Supplier Delivery Note Supplier Key']);
    
     if(!$supplier->id){
      $this->error=true;
      $this->msg='wrong supplier';
      return;
     }
    


    //print_r($data);
    $data['Supplier Delivery Note Creation Date']=date('Y-m-d H:i:s');
    $data['Supplier Delivery Note Last Updated Date']=date('Y-m-d H:i:s');

    
    $data['Supplier Delivery Note File As']=$this->get_file_as($data['Supplier Delivery Note Public ID']);
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
    $sql=sprintf("insert into `Supplier Delivery Note Dimension` %s %s",$keys,$values);

    //  print($sql);

    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->get_data('id',$this->id);
    }else
      exit(" error can no create supplider delivery note");


  }

 function get_data($key,$id){
    if($key=='id'){
      $sql=sprintf("select * from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Key`=%d",$id);
      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->id=$this->data['Supplier Delivery Note Key'];
      }
    }elseif($key=='public id' or $key=='public_id'){
      $sql=sprintf("select * from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Public ID`=%s",prepare_mysql($id));
      $result=mysql_query($sql);
      print "$sql\n";
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->id=$this->data['Supplier Delivery Note Key'];
      }
    }
 }

function get($key=''){

   if (array_key_exists ( $key, $this->data ))
      return $this->data [$key];
	 
   if($key=='Number Items')
     return number($this->data ['Supplier Delivery Note Number Items']);
   if (preg_match('/^(Total|Items|(Shipping |Charges )?Net).*(Amount)$/',$key)) {
     $amount='Supplier Delivery Note '.$key;
     return money($this->data[$amount]);
   }	
   if (preg_match('/Date$/',$key)) {
     $date='Supplier Delivery Note '.$key;
     return strftime("%e-%b-%Y %H:%M",strtotime($this->data[$date]));
   }	
   
      


    if(array_key_exists($key,$this->data))
      return $this->data[$key];
    
}


  function add_order_transaction($data) {
  
    include_once('class.TaxCategory.php');
    $tax_category=new TaxCategory($data['tax_code']);
    $tax_amount=$tax_category->calculate_tax($data ['amount']);


    if($this->data['Supplier Delivery Note Current Dispatch State']=='In Process'){

      if($data ['qty']==0){
	 $sql=sprintf("delete from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d and `Supplier Product Key`=%d ",$this->id,$data ['Supplier Product Key']);
	  mysql_query($sql);
      }else{


      $sql=sprintf("select `Supplier Delivery Note Line` from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d and `Supplier Product Key`=%d ",$this->id,$data ['Supplier Product Key']);
      $res=mysql_query($sql);
      if($row=mysql_fetch_array($res)){
	 $sql = sprintf ( "update`Purchase Order Transaction Fact` set  `Supplier Delivery Note Quantity`=%f,`Supplier Delivery Note Last Updated Date`=%s,`Supplier Delivery Note Net Amount`=%f ,`Supplier Delivery Note Tax Amount`=%f   where `Supplier Delivery Note Key`=%d and `Supplier Delivery Note Line`=%d "
			  ,$data ['qty']
			  ,prepare_mysql ( $data ['date'] )
			  , $data ['amount']
			  , $tax_amount
			  ,$this->id
			  ,$row['Supplier Delivery Note Line']
			  );
	 //	print "$sql";
	 mysql_query($sql);
	 
      }else{
	$sql = sprintf ( "insert into `Purchase Order Transaction Fact` (`Supplier Delivery Note Tax Code`,`Currency Code`,`Supplier Delivery Note Last Updated Date`,`Supplier Product Key`,`Supplier Delivery Note Current Dispatching State`,`Supplier Key`,`Supplier Delivery Note Key`,`Supplier Delivery Note Line`,`Supplier Delivery Note Quantity`,`Supplier Delivery Note Quantity Type`,`Supplier Delivery Note Net Amount`,`Supplier Delivery Note Tax Amount`) values (%s,%s,%s,%d,  %s    ,%d,%d,%d, %.6f,%s,%.2f,%.2f)   "
			 , prepare_mysql ( $data['tax_code'] )
			 , prepare_mysql ( $this->data ['Supplier Delivery Note Currency Code'] )
			 , prepare_mysql ( $data ['date'] )
			 , $data ['Supplier Product Key']

			 , prepare_mysql ( $data ['Current Dispatching State'] )

			 , $this->data['Supplier Delivery Note Supplier Key' ] 
			 , $this->data ['Supplier Delivery Note Key']
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

  


    return array('to_charge'=>money($data ['amount'],$this->data['Supplier Delivery Note Currency Code']),'qty'=>$data ['qty']);
		
    //  print "$sql\n";


  }
	

	
 function get_next_line_number(){
    
    $sql=sprintf("select MAX(`Supplier Delivery Note Line`) as max_line from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d ",$this->id);
    $res=mysql_query($sql);
    
    $line_number=1;
    if($row=mysql_fetch_array($res))
      $line_number=(int) $row['max_line']+1;
    return $line_number;
    
    
  }


 function get_next_public_id($supplier_key){
   $supplier=new Supplier($supplier_key);
   $code=$supplier->data['Supplier Code'];

   $sql=sprintf("select `Supplier Delivery Note Public ID` from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Supplier Key`=%d order by REPLACE(`Supplier Delivery Note Public ID`,%s,'') desc limit 1",$supplier_key,prepare_mysql($code));
   $res=mysql_query($sql);
   
   $line_number=1;
   if($row=mysql_fetch_array($res))
     $line_number= (int) preg_replace('/[^\d]/','',$row['Supplier Delivery Note Public ID'])+1;
   
   return sprintf('%s%04d',$code,$line_number);
   
 }

 function get_file_as($name){

   return $name;
 }


 function update_item_totals_from_order_transactions(){




   $sql = "select count(Distinct `Supplier Product Key`) as num_items ,sum(`Supplier Delivery Note Net Amount`) as net, sum(`Supplier Delivery Note Tax Amount`) as tax,  sum(`Supplier Delivery Note Shipping Amount`) as shipping from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=" . $this->id;
   //print "$sql\n";
   $result = mysql_query ( $sql );
   if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
     //	  $total = $row ['gross'] + $row ['tax'] + $row ['shipping']  - $row ['discount'] + $this->data ['Order Items Adjust Amount'];
     
	  $this->data ['Supplier Delivery Note Items Net Amount'] = $row ['net'];
	  $this->data ['Supplier Delivery Note Number Items'] = $row ['num_items'];

	  
	  $sql = sprintf ( "update `Supplier Delivery Note Dimension` set `Supplier Delivery Note Number Items`=%d , `Supplier Delivery Note Items Net Amount`=%.2f , `Supplier Delivery Note Items Tax Amount`=%.2f where  `Supplier Delivery Note Key`=%d "
			   , $this->data ['Supplier Delivery Note Number Items']
			   , $this->data ['Supplier Delivery Note Items Net Amount']
			   , $this->data ['Supplier Delivery Note Items Tax Amount']
			   
			   
			   , $this->id);
	  

	  //exit;
	  mysql_query ( $sql );				
	  
	  
   }  
	    
	
 }

 function get_number_items(){
   $num_items=0;
   $sql=sprintf("select count(Distinct `Supplier Product Key`) as num_items  from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d",$this->id);
   $result = mysql_query ( $sql );
   if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
     $num_items=$row['num_items'];
   }

   return $num_items;
 }

 



 function update_totals_from_order_transactions($force_total=false){
  

    if(!$force_total)
      $force_total=array();
    

   
    $this->data ['Supplier Delivery Note Total Tax Amount'] = $this->data ['Supplier Delivery Note Items Tax Amount'] + $this->data ['Supplier Delivery Note Shipping Tax Amount']+  $this->data ['Supplier Delivery Note Charges Tax Amount']+  $this->data ['Supplier Delivery Note Tax Credited Amount'];
    $this->data ['Supplier Delivery Note Total Net Amount']=$this->data ['Supplier Delivery Note Items Net Amount']+  $this->data ['Supplier Delivery Note Shipping Net Amount']+  $this->data ['Supplier Delivery Note Charges Net Amount']+  $this->data ['Supplier Delivery Note Net Credited Amount'];
    
    $this->data ['Supplier Delivery Note Total Amount'] = $this->data ['Supplier Delivery Note Total Tax Amount'] + $this->data ['Supplier Delivery Note Total Net Amount'];
    $this->data ['Supplier Delivery Note Total To Pay Amount'] = $this->data ['Supplier Delivery Note Total Amount'];
    $sql = sprintf ( "update `Supplier Delivery Note Dimension` set `Supplier Delivery Note Total Net Amount`=%.2f ,`Supplier Delivery Note Total Tax Amount`=%.2f ,`Supplier Delivery Note Shipping Net Amount`=%.2f ,`Supplier Delivery Note Shipping Tax Amount`=%.2f ,`Supplier Delivery Note Charges Net Amount`=%.2f ,`Supplier Delivery Note Charges Tax Amount`=%.2f ,`Supplier Delivery Note Total Amount`=%.2f , `Supplier Delivery Note Total To Pay Amount`=%.2f  where  `Supplier Delivery Note Key`=%d "
		     , $this->data ['Supplier Delivery Note Total Net Amount']	  
		     , $this->data ['Supplier Delivery Note Total Tax Amount']
		     , $this->data ['Supplier Delivery Note Shipping Net Amount']
		     , $this->data ['Supplier Delivery Note Shipping Tax Amount']
		     
		     , $this->data ['Supplier Delivery Note Charges Net Amount']
		     , $this->data ['Supplier Delivery Note Charges Tax Amount']
		     
		     , $this->data ['Supplier Delivery Note Total Amount']
		     , $this->data ['Supplier Delivery Note Total To Pay Amount']
		     , $this->data ['Supplier Delivery Note Key'] 
		     );
    
    
	  //exit;
    
    
    if (! mysql_query ( $sql ))
      exit ( "$sql eroro2 con no update totals" );



	
  }



 function delete(){

   if($this->data['Supplier Delivery Note Current Dispatch State']=='In Process'){
     $sql=sprintf("delete from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Key`=%d",$this->id);
     mysql_query($sql);
     $sql=sprintf("delete from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d",$this->id);
     mysql_query($sql);
   }else{
     $this->error=true;
     $this->msg='Can not deleted submitted supplier delivery notes';
   }
 }


function receive($data){


  foreach($data as $key=>$value){
    if(array_key_exists($key,$this->data)){
      $this->data[$key]=$value;
    }

  }

  $sql=sprintf("update `Supplier Delivery Note Dimension` set `Supplier Delivery Note Submitted Date`=%s,`Supplier Delivery Note Estimated Receiving Date`=%s,`Supplier Delivery Note Main Source Type`=%s,`Supplier Delivery Note Main Buyer Key`=%s,`Supplier Delivery Note Main Buyer Name`=%s,`Supplier Delivery Note Current Dispatch State`='Submitted'   where `Supplier Delivery Note Key`=%d"
	       ,prepare_mysql($data['Supplier Delivery Note Submitted Date'])
	       ,prepare_mysql($data['Supplier Delivery Note Estimated Receiving Date'])
	       ,prepare_mysql($data['Supplier Delivery Note Main Source Type'])
	       ,prepare_mysql($data['Supplier Delivery Note Main Buyer Key'])
	       ,prepare_mysql($data['Supplier Delivery Note Main Buyer Name'])
	       ,$this->id);
  
  
  mysql_query($sql);

  $sql=sprintf("update `Purchase Order Transaction Fact` set  `Supplier Delivery Note Last Updated Date`=%s `Supplier Delivery Note Current Dispatching State`='Submitted'  where `Supplier Delivery Note Key`=%d",prepare_mysql($data['Supplier Delivery Note Submitted Date']),$this->id);
   mysql_query($sql);


   $sql=sprintf("select `Supplier Product Key`,`Supplier Delivery Note Quantity` from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d",$this->id);
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     $supplier_product=new SupplierProduct('key',$row['Supplier Product Key']);
     $products=$supplier_product->get_products();
     foreach($products as $product){
       $product=new Product('pid',$product['Product ID']);
       $product->update_next_supplier_shippment();
       
     }
     
   }

}











}
?>