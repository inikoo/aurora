<?php
include_once('class.DB_Table.php');
include_once('class.Supplier.php');


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
    

    $supplier=new Supplier($data['Purchase Order Supplier Key']);
    if(!$supplier->id){
      $this->error=true;
      $this->msg='Error supplier not found';
      return;
    }

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
      $supplier->update_orders();
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


    if(array_key_exists($key,$this->data))
      return $this->data[$key];
    


 switch ($key) {
 
     case 'Estimated Receiving Date For Edition':
     if($this->data['Purchase Order Estimated Receiving Date']!='')
         return strftime("%d-%m-%Y",strtotime($this->data['Purchase Order Estimated Receiving Date']));
         else
         return '';
         
         break;
     default:
      
         break;
 }
 
 
   if($key=='Number Items')
     return number($this->data ['Purchase Order Number Items']);
   if (preg_match('/^(Total|Items|(Shipping |Charges )?Net).*(Amount)$/',$key)) {
     $amount='Purchase Order '.$key;
     return money($this->data[$amount]);
   }	
   
   
   if (preg_match('/Date$/',$key)) {
     $date='Purchase Order '.$key;
     if($key=='Estimated Receiving Date')
          return strftime("%e-%b-%Y",strtotime($this->data[$date]));
else
     return strftime("%e-%b-%Y %H:%M",strtotime($this->data[$date]));
   }	
   
      


	

}


  function add_order_transaction($data) {
  
    include_once('class.TaxCategory.php');
    $tax_category=new TaxCategory($data['tax_code']);
    $tax_amount=$tax_category->calculate_tax($data ['amount']);


    if($this->data['Purchase Order Current Dispatch State']=='In Process'){
      
      if($data ['qty']==0){
	 $sql=sprintf("delete from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d and `Supplier Product ID`=%d ",$this->id,$data ['Supplier Product ID']);
	 mysql_query($sql);
      }else{
	

	$sql=sprintf("select `Purchase Order Transaction Fact Key` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d and `Supplier Product Historic Key`=%d ",$this->id,$data ['Supplier Product Historic Key']);
	$res=mysql_query($sql);
	if($row=mysql_fetch_array($res)){
	  $sql = sprintf ( "update`Purchase Order Transaction Fact` set  `Purchase Order Quantity`=%f, `Purchase Order Quantity Type`=%s,`Purchase Order Last Updated Date`=%s,`Purchase Order Net Amount`=%f ,`Purchase Order Tax Amount`=%f   where `Purchase Order Key`=%d and `Purchase Order Transaction Fact Key`=%d "
			   ,$data ['qty']
			   ,prepare_mysql ( $data ['qty_type'])
			   ,prepare_mysql ( $data ['date'] )
			   , $data ['amount']
			  , $tax_amount
			   ,$this->id
			   ,$row['Purchase Order Transaction Fact Key']
			   );
	  //	print "$sql";
	  mysql_query($sql);
	  
	}else{
	  $sql = sprintf ( "insert into `Purchase Order Transaction Fact` (`Supplier Product Historic Key`,`Purchase Order Tax Code`,`Currency Code`,`Purchase Order Last Updated Date`,`Supplier Product ID`,`Purchase Order Current Dispatching State`,`Supplier Key`,`Purchase Order Key`,`Purchase Order Quantity`,`Purchase Order Quantity Type`,`Purchase Order Net Amount`,`Purchase Order Tax Amount`) values (%d,%s,%s,%s,%d,  %s    ,%d,%d, %.6f,%s,%.2f,%.2f)   "
			   , $data ['Supplier Product Historic Key']
			   , prepare_mysql ( $data['tax_code'] )
			   , prepare_mysql ( $this->data ['Purchase Order Currency Code'] )
			   , prepare_mysql ( $data ['date'] )
			   , $data ['Supplier Product ID']

			   , prepare_mysql ( $data ['Current Dispatching State'] )
			   
			   , $this->data['Purchase Order Supplier Key' ] 
			   , $this->data ['Purchase Order Key']
			 
			   
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

 //   if($this->data['Purchase Order Current Dispatch State']=='In Process' or $this->data['Purchase Order Current Dispatch State']=='Submitted'){
 //     $supplier=new Supplier('id',$this->data['Purchase Order Supplier Key' ]);
 //     $supplier->normalize_purchase_orders();
 //   }
    return array('to_charge'=>money($data ['amount'],$this->data['Purchase Order Currency Code']),'qty'=>$data ['qty']);
		
    //  print "$sql\n";


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




   $sql = "select count(Distinct `Supplier Product ID`) as num_items ,sum(`Purchase Order Net Amount`) as net, sum(`Purchase Order Tax Amount`) as tax,  sum(`Purchase Order Shipping Amount`) as shipping from `Purchase Order Transaction Fact` where `Purchase Order Key`=" . $this->id;
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
   $sql=sprintf("select count(Distinct `Supplier Product ID`) as num_items  from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d",$this->id);
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

  foreach($data as $key=>$value){
    if(array_key_exists($key,$this->data)){
      $this->data[$key]=$value;
    }

  }

  $sql=sprintf("update `Purchase Order Dimension` set `Purchase Order Submitted Date`=%s,`Purchase Order Estimated Receiving Date`=%s,`Purchase Order Main Source Type`=%s,`Purchase Order Main Buyer Key`=%s,`Purchase Order Main Buyer Name`=%s,`Purchase Order Current Dispatch State`='Submitted'   where `Purchase Order Key`=%d"
	       ,prepare_mysql($data['Purchase Order Submitted Date'])
	       ,prepare_mysql($data['Purchase Order Estimated Receiving Date'])
	       ,prepare_mysql($data['Purchase Order Main Source Type'])
	       ,prepare_mysql($data['Purchase Order Main Buyer Key'])
	       ,prepare_mysql($data['Purchase Order Main Buyer Name'])
	       ,$this->id);
  
  
  mysql_query($sql);

  $sql=sprintf("update `Purchase Order Transaction Fact` set  `Purchase Order Last Updated Date`=%s ,`Purchase Order Current Dispatching State`='Submitted'  where `Purchase Order Key`=%d",prepare_mysql($data['Purchase Order Submitted Date']),$this->id);
   mysql_query($sql);


  $this->update_affected_products();

}

function update_affected_products(){
  $sql=sprintf("select `Supplier Product ID`,`Purchase Order Quantity` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d",$this->id);
  $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     $supplier_product=new SupplierProduct('key',$row['Supplier Product ID']);
     $products=$supplier_product->get_products();
     foreach($products as $product){
       $product=new Product('pid',$product['Product ID']);
       $product->update_next_supplier_shippment();
       
     }
     
   }
}


function update_state(){

$cancelled=0;
$in_process=0;
$submitted=0;
$in_delivery_note=0;
$items=0;
$deliver_note_keys=array();
$sql=sprintf("select `Supplier Delivery Note Key`,`Supplier Invoice Key`,`Purchase Order Current Dispatching State` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d   ",$this->id);
 $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
    if($row['Supplier Delivery Note Key']!=0  and $row['Purchase Order Current Dispatching State']=='Found in Delivery Note'){
        $deliver_note_keys[$row['Supplier Delivery Note Key']]=1;
    }
    $items++;
    if($row['Purchase Order Current Dispatching State']=='Cancelled')
      $cancelled++;
    if($row['Purchase Order Current Dispatching State']=='Submitted')
      $submitted++;  
        if($row['Purchase Order Current Dispatching State']=='In Process')
      $in_process++;  
    if($row['Purchase Order Current Dispatching State']=='Found in Delivery Note')
      $in_delivery_note++;  

   }
//  print_r($deliver_note_keys);
//   print "xx i:$items  c:$cancelled  d: $in_delivery_note kk: ".count($deliver_note_keys)." \n";

   
if($items==0 ){
    $status='In Process';
$xhtml_state='In Process';
}if($items==$cancelled){
    $status='Cancelled';
    $xhtml_state=_('Cancelled');

}elseif($in_delivery_note==0 and $submitted==0  ){
    $status='In Process';
$xhtml_state=_('In Process');

}elseif($in_delivery_note==0  ){
    $status='Submitted';
$xhtml_state=_('Submitted');

}else{
//print "xxx  $in_delivery_note\n";

if(count($deliver_note_keys)>0  and  $in_delivery_note>0){
      if($in_delivery_note==($items-$cancelled)){
      $status='Matched With DN';
      $xhtml_state='';
      foreach($deliver_note_keys as $dn_key){
        $supplier_dn=new SupplierDeliveryNote($dn_key);
        if($supplier_dn->id){
        $xhtml_state.=sprintf(',<a href="supplier_dn.php?id=%d">%s</a>',$supplier_dn->id,$supplier_dn->data['Supplier Delivery Note Public ID']);
        }
        
      }
      $xhtml_state=preg_replace('/^\,/',_('Matched With DN')." ",$xhtml_state);
      
      }else{
       $status='Partially Matched With DN';
        $xhtml_state='';
      foreach($deliver_note_keys as $dn_key){
        $supplier_dn=new SupplierDeliveryNote($dn_key);
        if($supplier_dn->id){
        $xhtml_state.=sprintf(',<a href="supplier_dn.php?id=%d">%s</a>',$supplier_dn->id,$supplier_dn->data['Supplier Delivery Note Public ID']);
        }
        
      }
      $xhtml_state=preg_replace('/^\,/',_('Matched With DN')." ",$xhtml_state);
       
      }







}else{
    $status='Submitted';
$xhtml_state=_('Submitted')." (*)";


}
  }
//   print $status;
 $this->update(
    array(
    'Purchase Order Current Dispatch State'=>$status,
    'Purchase Order Current XHTML State'=>$xhtml_state
    )
    
    );
 
 
   
}

function cancel($data){
 foreach($data as $key=>$value){
    if(array_key_exists($key,$this->data)){
      $this->data[$key]=$value;
    }

  }

 $sql=sprintf("update `Purchase Order Dimension` set `Purchase Order Cancelled Date`=%s,`Purchase Order Cancel Note`=%s, `Purchase Order Current Dispatch State`='Cancelled'   where `Purchase Order Key`=%d"
	   ,prepare_mysql($this->data['Purchase Order Cancelled Date'])
	   	,prepare_mysql($this->data['Purchase Order Cancel Note'],false)
	       ,$this->id);
  mysql_query($sql);
//print $sql;
  $sql=sprintf("update `Purchase Order Transaction Fact` set  `Purchase Order Last Updated Date`=%s `Purchase Order Current Dispatching State`='Cancelled'  where `Purchase Order Key`=%d"
  ,prepare_mysql($data['Purchase Order Cancelled Date'])
  ,$this->id
  );
   mysql_query($sql);

 $this->update_affected_products();

  

}


function update_estimated_receiving_date($date){

$date_data=prepare_mysql_datetime($date,'date');

if($date_data['ok']){
$this->update_field('Purchase Order Estimated Receiving Date',$date_data['mysql_date']);
if($this->updated){
$this->new_value=strftime("%e-%b-%Y",strtotime($this->new_value));
 $this->update_affected_products();
}
}else{
$error=true;
   $this->msg=$date_data['status']; 

}


}

 function update_field_switcher($field,$value,$options=''){
switch ($field) {
    case 'Purchase Order Estimated Receiving Date':
        $this->update_estimated_receiving_date($value);
        break;
    default:
    


   $base_data=$this->base_data();

   
   
   if(array_key_exists($field,$base_data)) {
     if ($value!=$this->data[$field]) {
       
       $this->update_field($field,$value,$options);
     }
   }
   
   break;
   }

   
   
 }



}
?>