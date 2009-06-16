<?
/*
 File: Invoice.php 

 This file contains the Invoice Class

 Each invoice has to be associated with a contact if no contac data is provided when the Invoice is created an anonimous contact will be created as well. 
 

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('DB_Table.php');

include_once('Order.php');

/* class: Invoice
 Class to manage the *Invoice Dimension* table
*/



class Invoice extends DB_Table {

  /*
   Constructor: Invoice
   Initializes the class, trigger  Search/Load/Create for the data set

   If first argument is find it will try to match the data or create if not found 
     
   Parameters:
   arg1 -    Tag for the Search/Load/Create Options *or* the Contact Key for a simple object key search
   arg2 -    (optional) Data used to search or create the object

   Returns:
   void
       
   Example:
   (start example)
   // Load data from `Invoice Dimension` table where  `Invoice Key`=3
   $key=3;
   $invoice = New Invoice($key); 
       
   // Load data from `Invoice Dimension` table where  `Invoice`='raul@gmail.com'
   $invoice = New Invoice('raul@gmail.com'); 
       
   

  */
  function Invoice($arg1=false,$arg2=false) {
    
    $this->table_name='Invoice';
    $this->ignore_fields=array('Invoice Key');


    if(!$arg1 and !$arg2){
      $this->error=true;
      $this->msg='No data provided';
      return;
    }
    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return;
    }
    if ($arg1=='new'){
      $this->create($arg2);
      return;
    }
    if(preg_match('/find/i',$arg1)){
      $this->find($arg2,$arg1);
      return;
    }
    $this->get_data($arg1,$arg2);
  }
  /*
   Method: get_data
   Load the data from the database

   See Also:
   <find>
  */
  function get_data($tipo,$tag){
    if($tipo=='id')
      $sql=sprintf("select * from `Invoice Dimension` where  `Invoice Key`=%d",$tag);
    elseif($tipo=='public_id' )
      $sql=sprintf("select * from `Invoice Dimension` where  `Invoice Public ID`=%s",prepare_mysql($tag));
    else
      return;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Invoice Key'];
  }
 

  /*
   Method: find
   Given a set of invoice components try to find it on the database updating properties, if not found creates a new record

   Parmaters:
   $raw_data - associative array with the invoice data (DB fields as keys)
   $options - string 
   
   auto - the method will update/create the invoice with out asking for instructions 
   create|update - methos will create or update the invoice with the data provided
   

  */

  private function find($raw_data,$options=''){

  }


/*Method: create
 Creates a new invoice record

*/
protected function create($data,$options=''){
  

  if(!$data){
    $this->new=false;
    $this->msg.=" Error no invoice data";
    $this->error=true;
    if(preg_match('/exit on errors/',$options))
      exit($this->msg);
    return false;
  }
    
  

  global $myconf;
    
  $this->data=$this->base_data();
  foreach($data as $key=>$value){
    if(array_key_exists($key,$this->data))
      $this->data[$key]=$value;
  }
    


 
  




  if(mysql_query($sql)){
    $this->id = mysql_insert_id();
    $this->get_data('id',$this->id);
    $this->new=true;
      
    $this->msg=_('New Invoice');




    


    return true;
  }else{
    $this->new=false;
    $this->error=true;
    $this->msg=_('Error can not create invoice');
    if(preg_match('/exit on errors/',$options)){
      print "Error can not create invoice;\n";exit;
    }
  }
     
     
}

function get($key){
  
  switch($key){ 
  case('Items Gross Amount'): 
  case('Items Discount Amount'): 
  case('Items Net Amount'): 
  case('Items Tax Amount'): 
  case('Refund Net Amount'): 
  case('Charges Net Amount'): 
  case('Shipping Net Amount'): 

    return money($this->data['Invoice '.$key]);
  } 
  
  
  if(isset($this->data[$key]))
    return $this->data[$key];
   
  return false;
}



 /*Function: update_field_switcher
  */

protected function update_field_switcher($field,$value,$options=''){

  switch($field){
  default:
    $this->update_field($field,$value,$options);
  }
  
}






function display($tipo='xml'){



  switch($tipo){

  default:
    return 'todo';
    
  }
   
  
}





}

?>