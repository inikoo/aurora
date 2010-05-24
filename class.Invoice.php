<?php
/*
 File: Invoice.php 

 This file contains the Invoice Class

 Each invoice has to be associated with a contact if no contac data is provided when the Invoice is created an anonimous contact will be created as well. 
 

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');

include_once('class.Order.php');

include_once('class.DeliveryNote.php');

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
  function Invoice($arg1=false,$arg2=false,$arg3=false,$arg4=false) {
    
    $this->table_name='Invoice';
    $this->ignore_fields=array('Invoice Key');
$this->update_customer=false;

    if(!$arg1 and !$arg2){
      $this->error=true;
      $this->msg='No data provided';
      return;
    }
    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return;
    }
     if (preg_match('/create.*refund/i',$arg1)){
      $this->create_refund($arg2,$arg3,$arg4);
      return;
    }

    if (preg_match('/create|new/i',$arg1)){
      $this->create($arg2,$arg3,$arg4);
      return;
    }
    //   if(preg_match('/find/i',$arg1)){
    //  $this->find($arg2,$arg1);
    //  return;
    // }
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
    //print $sql;

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
/*Method: create_refund
 Creates a new invoice record

*/
  protected function create_refund($invoice_data,$transacions_data,$order,$options=''){

    global $myconf;
    
    $this->data ['Invoice Items Gross Amount'] =0;
    $this->data ['Invoice Items Discount Amount'] =0;
    
    
    $this->data ['Invoice Title']='Refund';
    
$this->data ['Invoice Customer Contact Name'] = $invoice_data ['Invoice Customer Contact Name'];
    $this->data ['Invoice Date'] = $invoice_data ['Invoice Date'];
    $this->data ['Invoice Public ID'] = $invoice_data ['Invoice Public ID'];
    $this->data ['Invoice File As'] = $invoice_data ['Invoice File As'];
    $this->data ['Invoice Store Key'] = $order->data ['Order Store Key'];

    $store=new Store($this->data ['Invoice Store Key']);

    $this->data ['Invoice Store Code'] = $store->data ['Store Code'];
    // print_r($invoice_data);
    if(isset($invoice_data['Invoice Currency'])){
      $this->data['Invoice Currency']=$invoice_data['Invoice Currency'];
    }else{
      $this->data['Invoice Currency']=$store->data['Store Currency Code'];
    }

    if($myconf['currency_code']!=$this->data['Invoice Currency']){
      //print $myconf['currency_code'].' -> '.$this->data['Invoice Currency']."\n";
      if(isset($invoice_data['Invoice Currency Exchange'])){
	$this->data['Invoice Currency Exchange']=$invoice_data['Invoice Currency Exchange'];
      }else{
	$exchange=1;
	
	
	$sql=sprintf("select `Exchange` from `History Currency Exchange Dimension` where `Currency Pair`='EURGBP' and `Date`=DATE(%s)"
		    ,prepare_mysql($this->data ['Invoice Date'] ));
       
	$res=mysql_query($sql);
	if($row2=mysql_fetch_array($res, MYSQL_ASSOC)){
	  $exchange=$row2['Exchange'];
	  
	}
$this->data['Invoice Currency Exchange']=$exchange;


      }

    }else
      $this->data['Invoice Currency Exchange']=1;

    
    $this->data ['Invoice XHTML Store'] = $order->data ['Order XHTML Store'];
    $this->data ['Invoice Main Source Type'] = $order->data ['Order Main Source Type'];
    $this->data ['Invoice For'] ='Customer';
      if( $order->data ['Order For']!='')
	$this->data ['Invoice For'] = $order->data ['Order For'];

    if($this->data ['Invoice Main Source Type']=='')
      $this->data ['Invoice Main Source Type']='Unknown';

    $this->data ['Invoice XHTML Address'] = '';
    $this->data ['Invoice Customer Contact Name'] = '';
    $this->data ['Invoice Billing Country 2 Alpha Code']='XX';
    $this->data ['Invoice Customer Key'] = $order->data ['Order Customer Key'];
    $this->data ['Invoice Customer Name'] = $order->data ['Order Customer Name'];
    
    $customer=new customer('id',$this->data ['Invoice Customer Key']);
    
    //    print_r($customer);exit('en invlice class '.$this->data ['Invoice Customer Key'].'  ');
    if($customer->id){
      if($customer->data['Customer Type']=='Company'){
	//TODO  not include if is a fuzzy contact name
	$this->data ['Invoice Customer Contact Name'] =$customer -> data['Customer Main Contact Name'];

      }

      $this->data ['Invoice XHTML Address'] = $customer -> data['Customer Main XHTML Address'];
      $this->data ['Invoice Billing Country 2 Alpha Code']=$customer -> data['Customer Main Country 2 Alpha Code'];
      
    }



    $this->data ['Invoice XHTML Ship Tos'] = '';
    $this->data ['Invoice Shipping Net Amount'] = $invoice_data ['Invoice Shipping Net Amount'];
    $this->data ['Invoice Charges Net Amount'] = $invoice_data ['Invoice Charges Net Amount'];
    
    
    if(isset($myconf['tax_rates'][$invoice_data ['Invoice Tax Code']]))
      $tax_rate=$myconf['tax_rates'][$invoice_data ['Invoice Tax Code']];
    else
      $tax_rate= $invoice_data ['tax_rate'];
    
    $this->data ['Invoice Shipping Tax Amount'] = $invoice_data ['Invoice Shipping Net Amount'] * ($tax_rate);
    $this->data ['Invoice Charges Tax Amount'] = $invoice_data ['Invoice Charges Net Amount'] * ($tax_rate);
    
    
    $this->data ['Invoice Metadata'] = $order->data ['Order Original Metadata'];
    $this->data ['Invoice Has Been Paid In Full'] = $invoice_data ['Invoice Has Been Paid In Full'];
    $this->data ['Invoice Main Payment Method'] = $invoice_data ['Invoice Main Payment Method'];
    $this->data ['Invoice Total Tax Amount'] = $invoice_data ['Invoice Total Tax Amount'];
    $this->data ['Invoice Refund Net Amount'] = $invoice_data ['Invoice Refund Net Amount'];
    
    $this->data ['Invoice Refund Tax Amount'] = $invoice_data ['Invoice Refund Tax Amount'];
    $this->data ['Invoice Total Amount'] = $invoice_data ['Invoice Total Amount'];
    $this->data ['Invoice Items Net Amount'] = $invoice_data ['Invoice Items Net Amount'];
    $this->data ['Invoice Dispatching Lag'] = $invoice_data ['Invoice Dispatching Lag'];
    $this->data ['Invoice Tax Code'] = $invoice_data['Invoice Tax Code'];
    $this->data ['Invoice Taxable'] = $invoice_data['Invoice Taxable'];
    $this->data ['Invoice XHTML Processed By'] = $invoice_data ['Invoice XHTML Processed By'];
    $this->data ['Invoice XHTML Charged By'] = $invoice_data ['Invoice XHTML Charged By'];
    $this->data ['Invoice Processed By Key'] = $invoice_data ['Invoice Processed By Key'];
    $this->data ['Invoice Charged By Key'] = $invoice_data ['Invoice Charged By Key'];
    


    $this->data ['Invoice XHTML Orders'] ='';
    $this->data ['Invoice XHTML Delivery Notes'] = '';

   
    $this->data ['Invoice Delivery Country 2 Alpha Code']='XX';
    //foreach($this->ship_to as $ship_to){
    //  $this->data ['Invoice Delivery Country 2 Alpha Code']=$ship_to->data['Ship To Country 2 Alpha Code'];
    //  break;
    // }
    
    $this->data ['Invoice XHTML Orders'] ='';
    $this->data ['Invoice XHTML Delivery Notes'] = '';


    $this->create_header ();
    //exit;


    $line_number = 0;
    $amount = 0;
    $discounts = 0;
    
    foreach ( $transacions_data as $data ) {
		  $line_number ++;
			
		  if ($order->id){
		    $order_date = prepare_mysql ( $order->data ['Order Date'] );
		    $order_key=$order->id;
		  }else{
		    $order_date = 'NULL';
		    $order_key=0;
		  }
		    $sql = sprintf ( "insert into `Order No Product Transaction Fact` values  (%s,%s,%s,%s,'Refund',%s,%.2f,%.2f,%s,%f,%s)"
				     , $order_date
				     , prepare_mysql ( $this->data ['Invoice Date'] )
				     , $order_key
				     , prepare_mysql ( $this->data ['Invoice Key'] )
				     , prepare_mysql ( $data ['Description'] )
				     , $data ['Transaction Net Amount']
				     , $data ['Transaction Tax Amount']
				     , prepare_mysql ( $this->data ['Invoice Currency'] )
				     ,$this->data ['Invoice Currency Exchange']
				     , prepare_mysql ( $this->data ['Invoice Metadata'] ) );
		  
		  // print $sql;
		  
		  
		  $amount += $data ['Transaction Net Amount'];
		  $discounts += 0;
			
		  if (! mysql_query ( $sql ))
			  exit ( "$sql can not update order trwansiocion facrt after invoice" );
		}
	

  }


/*Method: create
 Creates a new invoice record

*/
  protected function create($invoice_data,$transacions_data,$order_key,$options=''){
  
   

    $order=new Order($order_key);

    global $myconf;
    

    
    $this->data ['Invoice Title']='Invoice';
    $this->data ['Invoice Items Gross Amount'] =0;
    $this->data ['Invoice Items Discount Amount'] =0;
    
    $this->data ['Invoice Date'] = $invoice_data ['Invoice Date'];
    $this->data ['Invoice Customer Contact Name'] = $invoice_data ['Invoice Customer Contact Name'];
    $this->data ['Invoice Public ID'] = $invoice_data ['Invoice Public ID'];
    $this->data ['Invoice File As'] = $invoice_data ['Invoice File As'];
    $this->data ['Invoice Store Key'] = $order->data ['Order Store Key'];
    $store=new store($this->data ['Invoice Store Key']);
    $this->data ['Invoice Store Code'] = $order->data ['Order Store Code'];
    $this->data ['Invoice XHTML Store'] = $order->data ['Order XHTML Store'];
    // print_r($invoice_data);
      if(isset($invoice_data['Invoice Currency'])){
      $this->data['Invoice Currency']=$invoice_data['Invoice Currency'];
    }else{
      $this->data['Invoice Currency']=$store->data['Store Currency Code'];
    }

    if($myconf['currency_code']!=$this->data['Invoice Currency']){
      if(isset($invoice_data['Invoice Currency Exchange'])){
	$this->data['Invoice Currency Exchange']=$invoice_data['Invoice Currency Exchange'];
      }else{
	$exchange=1;
	$sql=sprintf("select `Exchange` from `History Currency Exchange Dimension` where `Currency Pair`='EURGBP' and `Date`=DATE(%s)"
		    ,prepare_mysql($this->data ['Invoice Date'] ));
	$res=mysql_query($sql);
	if($row=mysql_fetch_array($res, MYSQL_ASSOC)){
	  $exchange=$row['Exchange'];
	  
    }
$this->data['Invoice Currency Exchange']=$exchange;

      }

    }else
      $this->data['Invoice Currency Exchange']=1;

    $this->data ['Invoice Main Source Type'] = $order->data ['Order Main Source Type'];



    $this->data ['Invoice XHTML Address'] = '';
    $this->data ['Invoice Customer Contact Name'] = '';
    $this->data ['Invoice Billing Country 2 Alpha Code']='XX';
    $this->data ['Invoice Customer Key'] = $order->data ['Order Customer Key'];
    $this->data ['Invoice Customer Name'] = $order->data ['Order Customer Name'];
    $this->data ['Invoice For'] ='Customer';
    if( $order->data ['Order For']!='')
      $this->data ['Invoice For'] = $order->data ['Order For'];
    $customer=new customer('id',$this->data ['Invoice Customer Key']);
    //print_r($customer);exit('en invlice class '.$this->data ['Invoice Customer Key'].'  ');

    if($customer->id){
      if($customer->data['Customer Type']=='Company'){
	//TODO  not include if is a fuzzy contact name
	$this->data ['Invoice Customer Contact Name'] =$customer -> data['Customer Main Contact Name'];

      }
      //print_r($customer);
      //print "zzzz".$customer -> data['Customer Main Country 2 Alpha Code']."zzzzzz";
      $this->data ['Invoice XHTML Address'] = $customer -> data['Customer Main XHTML Address'];
      $this->data ['Invoice Billing Country 2 Alpha Code']=$customer -> data['Customer Main Country 2 Alpha Code'];
      
    }



    //print_r($this->data);
    

    $this->data ['Invoice XHTML Ship Tos'] = '';
    $this->data ['Invoice Shipping Net Amount'] = $invoice_data ['Invoice Shipping Net Amount'];
    $this->data ['Invoice Charges Net Amount'] = $invoice_data ['Invoice Charges Net Amount'];
    
    
    if(isset($myconf['tax_rates'][$invoice_data ['Invoice Tax Code']]))
      $tax_rate=$myconf['tax_rates'][$invoice_data ['Invoice Tax Code']];
    else
      $tax_rate= $invoice_data ['tax_rate'];
    
    $this->data ['Invoice Shipping Tax Amount'] = $invoice_data ['Invoice Shipping Net Amount'] * ($tax_rate);
    $this->data ['Invoice Charges Tax Amount'] = $invoice_data ['Invoice Charges Net Amount'] * ($tax_rate);
    
    
    $this->data ['Invoice Metadata'] = $order->data ['Order Original Metadata'];
    $this->data ['Invoice Has Been Paid In Full'] = $invoice_data ['Invoice Has Been Paid In Full'];
    $this->data ['Invoice Main Payment Method'] = $invoice_data ['Invoice Main Payment Method'];
    $this->data ['Invoice Total Tax Amount'] = $invoice_data ['Invoice Total Tax Amount'];
    $this->data ['Invoice Refund Net Amount'] = $invoice_data ['Invoice Refund Net Amount'];
    
    $this->data ['Invoice Refund Tax Amount'] = $invoice_data ['Invoice Refund Tax Amount'];
    $this->data ['Invoice Total Amount'] = $invoice_data ['Invoice Total Amount'];
    $this->data ['Invoice Items Net Amount'] = $invoice_data ['Invoice Items Net Amount'];
    $this->data ['Invoice Dispatching Lag'] = $invoice_data ['Invoice Dispatching Lag'];
    $this->data ['Invoice Tax Code'] = $invoice_data['Invoice Tax Code'];
    $this->data ['Invoice Taxable'] = $invoice_data['Invoice Taxable'];
    $this->data ['Invoice XHTML Processed By'] = $invoice_data ['Invoice XHTML Processed By'];
    $this->data ['Invoice XHTML Charged By'] = $invoice_data ['Invoice XHTML Charged By'];
    $this->data ['Invoice Processed By Key'] = $invoice_data ['Invoice Processed By Key'];
    $this->data ['Invoice Charged By Key'] = $invoice_data ['Invoice Charged By Key'];
    


    $this->data ['Invoice XHTML Orders'] ='';
    $this->data ['Invoice XHTML Delivery Notes'] = '';

   
    $this->data ['Invoice Delivery Country 2 Alpha Code']='XX';
    //foreach($this->ship_to as $ship_to){
    //  $this->data ['Invoice Delivery Country 2 Alpha Code']=$ship_to->data['Ship To Country 2 Alpha Code'];
    //  break;
    // }
    
    $this->data ['Invoice XHTML Orders'] ='';
    $this->data ['Invoice XHTML Delivery Notes'] = '';

    //print_r($this->data);
    $this->create_header ();
    
    // link to order
   /*  $sql = sprintf ( "insert into `Order Invoice Bridge` values (%d,%d)", $order->data ['Order Key'], $this->data ['Invoice Key'] ); */
/*     if (! mysql_query ( $sql )) */
/*       exit ( "Errro can no insert order inv bridge" ); */
    
    // if (is_numeric ( $order->data ['Delivery Note Key'] )) {
    //  $sql = sprintf ( "insert into `Invoice Delivery Note Bridge` values (%d,%d)", $this->data ['Invoice Key'], $dn->data ['Delivery Note Key'] );
    //  if (! mysql_query ( $sql ))
    //	exit ( "error 3985203rnw0rnfd in order.php\n" );
    // }
    
    $line_number = 0;
    $amount = 0;
    $discounts = 0;
    
    //TODO
    //Ship t key
    $ship_to_key=0;
    
    //print_r($transacions_data);
    
    foreach ( $transacions_data as $data ) {
      $line_number ++;
      
      $sql = sprintf ( "update  `Order Transaction Fact`  set `Current Payment State`=%s,`Invoice Date`=%s,`Order Last Updated Date`=%s, `Invoice Public ID`=%s,`Invoice Line`=%d
      ,`Invoice Quantity`=%s ,`Ship To Key`=%s ,`Invoice Transaction Gross Amount`=%.2f,`Invoice Transaction Total Discount Amount`=%.2f ,`Consolidated`='No'
      
      ,`Invoice Transaction Total Tax Amount`=%.4f
      , `Transaction Tax Rate`=%f ,`Transaction Tax Code`=%s,`Invoice Transaction Outstanding Net Balance`=%.2f,`Invoice Transaction Outstanding Tax Balance`=%.2f ,`Invoice Key`=%d ,`Invoice Currency Code`=%s,`Invoice Currency Exchange Rate`=%f where `Order Key`=%d and  `Order Line`=%d"
		       , prepare_mysql ( 'Waiting Payment' )
		       , prepare_mysql ( $this->data ['Invoice Date'] )
		       , prepare_mysql ( $this->data ['Invoice Date'] )
		       , prepare_mysql ( $this->data ['Invoice Public ID'] )
		       , $line_number
		       , prepare_mysql ( $data ['invoice qty'] )
		       , prepare_mysql ( $ship_to_key )
		       , $data ['gross amount']
		       , $data ['discount amount']
		       
		       , $data ['tax amount']
		       , $data ['tax rate']
		        , prepare_mysql($data ['tax code'])
		       , $data ['gross amount']-$data ['discount amount']
		       , $data ['tax amount']
		       , $this->data ['Invoice Key']
		       , prepare_mysql ( $this->data ['Invoice Currency'] )
		       , $this->data ['Invoice Currency Exchange']
		       , $order->data ['Order Key']
		       , $line_number );
      
      $amount += $data ['gross amount'];
      $discounts += $data ['discount amount'];
      //print "$sql\n\n\n";
      if (! mysql_query ( $sql ))
	exit ( "$sql\n can not update order trwansiocion 11 facrt after invoice" );
    }
    //exit;
    //addign indovdual product costs
    
    $this->distribute_costs();
    
    $this->load('dns');
    $this->load('orders');
    
    // Make bridges

    $sql = sprintf ( "delete from  `Invoice Delivery Note Bridge` where `Invoice Key`=%d",$this->id);
    mysql_query ( $sql );
    foreach($this->delivery_notes as $key=>$dn){
      $sql = sprintf ( "insert into `Invoice Delivery Note Bridge` values (%d,%d)", $this->data ['Invoice Key'], $key );
      mysql_query ( $sql );
      $dn->update('Delivery Note XHTML Invoices');
    }
    $sql = sprintf ( "delete from  `Order Invoice Bridge` where `Invoice Key`=%d",$this->id);
    mysql_query ( $sql );
    foreach($this->orders as $key=>$ord){
      $sql = sprintf ( "insert into `Order Invoice Bridge` values (%d,%d)", $key, $this->data ['Invoice Key']);
      mysql_query ( $sql );
    }


      $sql = sprintf ( "update `Invoice Dimension` set `Invoice XHTML Delivery Notes`=%s,`Invoice XHTML Ship Tos`=%s,`Invoice XHTML Orders`=%s,`Invoice Delivery Country 2 Alpha Code`=%s where `Invoice Key`=%d"
	
		     , prepare_mysql($this->data ['Invoice XHTML Delivery Notes'])
		     , prepare_mysql($this->data ['Invoice XHTML Ship Tos'])
		     , prepare_mysql($this->data ['Invoice XHTML Orders'])
		     , prepare_mysql($this->data ['Invoice Delivery Country 2 Alpha Code'])

		     , $this->data ['Invoice Key'] 
		     );
      // print $sql;
      mysql_query($sql);

      
      $this->get_data('id',$this->data ['Invoice Key'] );
      // print_r($this);

    foreach($this->orders as $key=>$order){
      $order->update_product_sales();
      $order->update_totals('save');
      $order->update_invoices('save');
      $customer=new Customer($order->data['Order Customer Key']);
      
      
      if($this->update_customer){
      $customer->update_orders();
      
      $customer->update_no_normal_data();
      $customer->update_activity();
      }
    }
    
    
		
  }    
  
  
 
  
  
     


function create_header() {
  
  //calculate the order total
  $this->data ['Invoice Gross Amount'] = 0;
  $this->data ['Invoice Discount Amount'] = 0;
  
  
  if(!isset($this->data ['Invoice Billing Country 2 Alpha Code']))
    $this->data ['Invoice Billing Country 2 Alpha Code']='XX';
  if(!isset($this->data ['Invoice Delivery Country 2 Alpha Code']))
    $this->data ['Invoice Delivery Country 2 Alpha Code']='XX';
  // print_r($this->data);
  
  $sql = sprintf ( "insert into `Invoice Dimension` (`Invoice Customer Contact Name`,`Invoice Currency`,`Invoice Currency Exchange`,`Invoice For`,`Invoice Date`,`Invoice Public ID`,`Invoice File As`,`Invoice Store Key`,`Invoice Store Code`,`Invoice Main Source Type`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice XHTML Ship Tos`,`Invoice Items Gross Amount`,`Invoice Items Discount Amount`,`Invoice Shipping Net Amount`,`Invoice Charges Net Amount`,`Invoice Total Tax Amount`,`Invoice Refund Net Amount`,`Invoice Refund Tax Amount`,`Invoice Total Amount`,`Invoice Metadata`,`Invoice XHTML Address`,`Invoice XHTML Orders`,`Invoice XHTML Delivery Notes`,`Invoice XHTML Store`,`Invoice Has Been Paid In Full`,`Invoice Main Payment Method`,`Invoice Shipping Tax Amount`,`Invoice Charges Tax Amount`,`Invoice XHTML Processed By`,`Invoice XHTML Charged By`,`Invoice Processed By Key`,`Invoice Charged By Key`,`Invoice Billing Country 2 Alpha Code`,`Invoice Delivery Country 2 Alpha Code`,`Invoice Dispatching Lag`,`Invoice Taxable`,`Invoice Tax Code`,`Invoice Title`) values (%s,%s,%f,%s,%s,%s,%s,%s,%s,%s,%s,%s,  %s,%.2f,%.2f,%.2f,%.2f,%.2f,%.2f,%.2f,%.2f,   %s,%s,%s,'%s',%s,%s,%s,%.2f,%.2f,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		   , prepare_mysql ( $this->data ['Invoice Customer Contact Name'],false)
		   , prepare_mysql ( $this->data ['Invoice Currency'] )
		   , $this->data ['Invoice Currency Exchange']
		   , prepare_mysql ( $this->data ['Invoice For'] )

		   , prepare_mysql ( $this->data ['Invoice Date'] )
		   , prepare_mysql ( $this->data ['Invoice Public ID'] ), prepare_mysql ( $this->data ['Invoice File As'] ), prepare_mysql ( $this->data ['Invoice Store Key'] ), prepare_mysql ( $this->data ['Invoice Store Code'] ), prepare_mysql ( $this->data ['Invoice Main Source Type'] ), prepare_mysql ( $this->data ['Invoice Customer Key'] ), prepare_mysql ( $this->data ['Invoice Customer Name'] ,false), prepare_mysql ( $this->data ['Invoice XHTML Ship Tos'] ), $this->data ['Invoice Items Gross Amount'], $this->data ['Invoice Items Discount Amount'], $this->data ['Invoice Shipping Net Amount'], $this->data ['Invoice Charges Net Amount'], $this->data ['Invoice Total Tax Amount']
		   , $this->data ['Invoice Refund Net Amount'], $this->data ['Invoice Refund Tax Amount']
		   , $this->data ['Invoice Total Amount'], prepare_mysql ( $this->data ['Invoice Metadata'] ), prepare_mysql ( $this->data ['Invoice XHTML Address'] ), prepare_mysql ( $this->data ['Invoice XHTML Orders'] ), addslashes ( $this->data ['Invoice XHTML Delivery Notes'] ), prepare_mysql ( $this->data ['Invoice XHTML Store'] ), prepare_mysql ( $this->data ['Invoice Has Been Paid In Full'] ), prepare_mysql ( $this->data ['Invoice Main Payment Method'] ), $this->data ['Invoice Shipping Tax Amount'], $this->data ['Invoice Charges Tax Amount'], prepare_mysql ( $this->data ['Invoice XHTML Processed By'] ), prepare_mysql ( $this->data ['Invoice XHTML Charged By'] ), prepare_mysql ( $this->data ['Invoice Processed By Key'] )
		   , prepare_mysql ( $this->data ['Invoice Charged By Key'] ) 
		   , prepare_mysql ( $this->data ['Invoice Billing Country 2 Alpha Code'] ) 
		   , prepare_mysql ( $this->data ['Invoice Delivery Country 2 Alpha Code'] ) 
		   , prepare_mysql ( $this->data ['Invoice Dispatching Lag'] ) 
		   , prepare_mysql ( $this->data ['Invoice Taxable'] ) 
		   , prepare_mysql ( $this->data ['Invoice Tax Code'] ) 
		   , prepare_mysql ($this->data ['Invoice Title'])
		   );
  


  if (mysql_query ( $sql )) {
    
    $this->data ['Invoice Key'] = mysql_insert_id ();
    $this->id=$this->data ['Invoice Key'];
  } else {
    
    print "$sql Error can not create order header";
    exit ();
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
   case('Total Net Amount'):
  case('Total Tax Amount'):
  case('Total Amount'):
    return money($this->data['Invoice '.$key],$this->data['Invoice Currency']);
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

 function distribute_costs() {
   $sql = "select * from `Order Transaction Fact` where `Invoice Key`=" . $this->data ['Invoice Key'];
   $result = mysql_query ( $sql );
   $total_weight = 0;
   $weight_factor = array ();
   $total_charge = 0;
   $charge_factor = array ();
   $items = 0;
   while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
     $items ++;
     $weight = $row ['Estimated Weight'];
     $total_weight += $weight;
     $weight_factor [$row ['Invoice Line']] = $weight;
     
     $charge = $row ['Order Transaction Gross Amount'];
     $total_charge += $charge;
     $charge_factor [$row ['Invoice Line']] = $charge;
     
   }
   if ($items == 0)
     return;
   //	print_r($weight_factor);
   //print_r($charge_factor);
   
   $shipping_tax_rate=0;
    if($this->data ['Invoice Shipping Net Amount']!=0)
   $shipping_tax_rate=$this->data ['Invoice Shipping Tax Amount']/$this->data ['Invoice Shipping Net Amount'];

   //print $this->data['Invoice Shipping Net Amount'];
   foreach ( $weight_factor as $line_number => $factor ) {
     if ($total_weight == 0){
       $shipping = $this->data ['Invoice Shipping Net Amount'] * $factor / $items;
     $shipping_tax=$shipping*$shipping_tax_rate;
     }else{
       $shipping = $this->data ['Invoice Shipping Net Amount'] * $factor / $total_weight;
        $shipping_tax=$shipping*$shipping_tax_rate;
    }
    
    
    $sql = sprintf ( "update `Order Transaction Fact` set `Invoice Transaction Shipping Amount`=%.4f, `Invoice Transaction Shipping Tax Amount`=%.6f where `Invoice Key`=%d and  `Invoice Line`=%d "
    , $shipping ,$shipping_tax,$this->data ['Invoice Key'], $line_number );
     if (! mysql_query ( $sql ))
				exit ( "$sql error dfsdfs doerde.pgp" );
   }
  // $total_tax = $this->data ['Invoice Items Tax Amount'] + $this->data ['Invoice Shipping Tax Amount'] + $this->data ['Invoice Charges Tax Amount'];
   
$charge_tax_rate=0;
    if($this->data ['Invoice Charges Net Amount']!=0)
   $charge_tax_rate=$this->data ['Invoice Charges Tax Amount']/$this->data ['Invoice Charges Net Amount'];

   //	print_r($this->data);
   //print "=========================$total_tax ====$total_charge ===========\n";
   foreach ( $charge_factor as $line_number => $factor ) {
    if ($total_charge == 0) {
        $charges = $this->data ['Invoice Charges Net Amount'] * $factor / $items;
         $charge_tax=$charge_tax_rate*$charges;
    } else {
        $charges = $this->data ['Invoice Charges Net Amount'] * $factor / $total_charge;
        $charge_tax=$charge_tax_rate*$charges;

    }
    $sql = sprintf ( "update `Order Transaction Fact` set `Invoice Transaction Charges Amount`=%.4f ,`Invoice Transaction Charges Tax Amount`=%.6f  where `Invoice Key`=%d and  `Invoice Line`=%d "
    , $charges, $charge_tax, $this->data ['Invoice Key'], $line_number );
    if (! mysql_query ( $sql ))
        exit ( "$sql error dfsdfs 2 doerde.pgp" );
}
 
  $sql = "update `Order Transaction Fact`  set `Invoice Transaction Total Tax Amount`=(IFNULL(`Transaction Tax Rate`,0)*(IFNULL(`Invoice Transaction Gross Amount`,0)-IFNULL(`Invoice Transaction Total Discount Amount`,0)))+IFNULL(`Invoice Transaction Shipping Tax Amount`,0)+IFNULL(`Invoice Transaction Charges Tax Amount`,0)   where `Invoice Key`=" . $this->data ['Invoice Key'];
   if(! mysql_query ( $sql )){
    exit ( "$sql error dfsdfs 2 doerde.pgp  inv class aaa" );
   }
 
 }

 function load($key,$args=false){
global $myconf;
   switch($key){
   case('delivery_notes'):
   case('dns'):
     $sql=sprintf("select `Delivery Note Key` from `Order Transaction Fact` where `Invoice Key`=%d group by `Delivery Note Key`",$this->id);
     $res = mysql_query ( $sql );
     $this->delivery_notes=array();
     while ($row = mysql_fetch_array ( $res, MYSQL_ASSOC )) {
       if($row['Delivery Note Key']){
	 $dn=new DeliveryNote($row['Delivery Note Key']);
	 $this->delivery_notes[$row['Delivery Note Key']]=$dn;
       }

     }
          //update no normal fields
     $this->data ['Invoice XHTML Delivery Notes'] ='';
     $this->data ['Invoice XHTML Ship Tos'] = '';
     $this->ship_tos=array();
     $w=0;
     $this->data ['Invoice Delivery Country 2 Alpha Code']=false;
     foreach($this->delivery_notes as $dn){
       $this->data ['Invoice XHTML Delivery Notes'] .= sprintf ( '%s <a href="dn.php?id=%d">%s</a>, ', $myconf['dn_id_prefix'], $dn->data ['Delivery Note Key'], $dn->data ['Delivery Note ID'] );
       $this->ship_tos[ $dn->data ['Delivery Note Ship To Key']]=$dn->data ['Delivery Note XHTML Ship To'];
       if(!$this->data ['Invoice Delivery Country 2 Alpha Code'] or $dn->data ['Delivery Note Weight']>$w ){
	 $this->data ['Invoice Delivery Country 2 Alpha Code']=$dn->data ['Delivery Note Country 2 Alpha Code'];;
	 $w=$dn->data ['Delivery Note Weight'];
       }
       }       
     $this->data ['Invoice XHTML Delivery Notes'] =_trim(preg_replace('/\, $/','',$this->data ['Invoice XHTML Delivery Notes']));
     //$where_dns=preg_replace('/\,$/',')',$where_dns);
     
     foreach($this->ship_tos as $ship_to){
       
       $this->data ['Invoice XHTML Ship Tos'] .=$ship_to."<br/>";
     }
      $this->data ['Invoice XHTML Ship Tos'] =_trim(preg_replace('/\<br\/\>$/','',$this->data ['Invoice XHTML Ship Tos']));
      

      //get ship tos
      break;
   case('orders'):

     $sql=sprintf("select `Order Key` from `Order Transaction Fact` where `Invoice Key`=%d group by `Order Key`",$this->id);
     $res = mysql_query ( $sql );
     $this->orders=array();
     while ($row = mysql_fetch_array ( $res, MYSQL_ASSOC )) {
       if($row['Order Key']){
	 $this->orders[$row['Order Key']]=new Order($row['Order Key']);
       }
       
     }
     $this->data ['Invoice XHTML Orders'] ='';
      foreach($this->orders as $order){
       $this->data ['Invoice XHTML Orders'] .= sprintf ( '%s <a href="order.php?id=%d">%s</a>, ', $myconf['order_id_prefix'], $order->data ['Order Key'], $order->data ['Order Public ID'] );
     }     
      $this->data ['Invoice XHTML Orders'] =_trim(preg_replace('/\, $/','',$this->data ['Invoice XHTML Orders']));

     break;  

 }
     


 }
 /*
   function: pay
   Pay invoice
  */

 function pay($tipo='full',   $force_values=false){

   
   
   
   $sql = sprintf ( "update  `Order Transaction Fact`  set `Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s,`Invoice Transaction Outstanding Net Balance`=0,`Invoice Transaction Outstanding Tax Balance`=0 ,`Invoice Transaction Outstanding Tax Balance`=0 where `Invoice Key`=%d and `Consolidated`='No' ",prepare_mysql($this->data['Invoice Paid Date']),$this->id);
   // print $sql;
   mysql_query ( $sql );

   

   //print_r($force_values);
   $this->get_totals($force_values);
   $sql=sprintf("update `Invoice Dimension`  set `Invoice Main Payment Method`=%s,`Invoice Paid Date`=%s ,`Invoice Paid`='Yes',`Invoice Has Been Paid In Full`='Yes' where `Invoice Key`=%d"
		,prepare_mysql($this->data['Invoice Main Payment Method'])
		,prepare_mysql($this->data['Invoice Paid Date'])

		,$this->id);
   mysql_query ( $sql );
   $this->load('orders');
   foreach($this->orders as $key=>$order){
      $order->update_product_sales();
      $order->update_totals('save');
      $customer=new Customer($order->data['Order Customer Key']);
      if($this->update_customer){
      $customer->update_orders();
      $customer->update_no_normal_data();
      }
   }
   //   exit;
 //  print $sql;
 }


 function get_totals($force_values=false){



  // get refunds
   $ref_net=0;
   $ref_tax=0;
   $sql = "select sum(`Transaction Net Amount`) as net,sum(`Transaction Tax Amount`) as  tax  from `Order No Product Transaction Fact`  where  `Invoice Key`=" . $this->data ['Invoice Key'];
   //print $sql;
   $result = mysql_query ( $sql );
   if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
     $ref_net=$row['net'];
     $ref_tax=$row['tax'];
   }

   
   $sql = "select sum(`Invoice Transaction Gross Amount`) as gross,sum(`Invoice Transaction Total Discount Amount`) as discount  ,sum(`Invoice Transaction Total Tax Amount`) as tax,sum(`Invoice Transaction Net Refund Amount`) as ref_net,sum(`Invoice Transaction Tax Refund Amount`) as ref_tax,sum(`Invoice Transaction Outstanding Net Balance`) as ob_net ,sum(`Invoice Transaction Outstanding Tax Balance`) as ob_tax ,sum(`Invoice Transaction Outstanding Refund Net Balance`) as ref_ob_net ,sum(`Invoice Transaction Outstanding Refund Tax Balance`) as ref_ob_tax  from `Order Transaction Fact`  where  `Invoice Key`=" . $this->data ['Invoice Key'];
   //print "$sql\n";
   $result = mysql_query ( $sql );
   if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
     $amount=$row['gross'];
     $discounts=$row['discount'];
     $tax=$row['tax'];
   }
   
   
   
   $this->data ['Invoice Gross Amount'] = $amount;
   $this->data ['Invoice Discount Amount'] = $discounts;
   $net = $amount - $discounts;
   
   if(isset($force_values['Invoice Items Net Amount']))
     $this->data ['Invoice Items Net Adjust Amount']=$force_values['Invoice Items Net Amount']-$net;
   else
     $this->data ['Invoice Items Net Adjust Amount']=0;





   $this->data ['Invoice Items Net Amount']=$this->data ['Invoice Gross Amount'] - $this->data ['Invoice Discount Amount'] + $this->data ['Invoice Items Net Adjust Amount'];

   $total_net=$this->data ['Invoice Items Net Amount']+$this->data ['Invoice Shipping Net Amount']+$this->data ['Invoice Charges Net Amount'];


   if(isset($force_values['Invoice Total Net Amount']))
     $this->data ['Invoice Total Net Adjust Amount']=$force_values['Invoice Total Net Amount']-$total_net-$ref_net;
   else
     $this->data ['Invoice Total Net Adjust Amount']=0;

    if(isset($force_values['Invoice Total Tax Amount']))
     $this->data ['Invoice Total Tax Adjust Amount']=$force_values['Invoice Total Tax Amount']-$tax-$ref_tax;
   else
     $this->data ['Invoice Total Tax Adjust Amount']=0;

    $this->data ['Invoice Items Tax Amount'] =$tax;
    //print_r($force_values);
    //print "aqui 123 tax items $tax \n";
   // print $this->data ['Invoice Total Net Adjust Amount']."\n";
   $this->data ['Invoice Total Net Amount'] = $total_net+$ref_net+$this->data ['Invoice Total Net Adjust Amount'];
   $this->distribute_costs ();
   
   $this->data ['Invoice Total Tax Amount'] = $tax+$ref_tax+$this->data ['Invoice Total Tax Adjust Amount'];
   

   $this->data ['Invoice Total Amount'] = $this->data ['Invoice Total Tax Amount']+$this->data ['Invoice Total Net Amount'];
   

  if(isset($force_values['Invoice Total Amount']))
     $this->data ['Invoice Total Adjust Amount']=$force_values['Invoice Total Amount']-$this->data ['Invoice Total Amount'];
   else
     $this->data ['Invoice Total Adjust Amount']=0;


  $total_costs=0;
  $sql=sprintf("select ifnull(sum(`Cost Supplier`/`Invoice Currency Exchange Rate`),0) as `Cost Supplier`  ,ifnull(sum(`Cost Manufacure`/`Invoice Currency Exchange Rate`),0) as `Cost Manufacure` ,ifnull(sum(`Cost Storing`/`Invoice Currency Exchange Rate`),0) as `Cost Storing`,ifnull(sum(`Cost Handing`/`Invoice Currency Exchange Rate`),0)  as  `Cost Handing`,ifnull(sum(`Cost Shipping`/`Invoice Currency Exchange Rate`),0) as `Cost Shipping` from `Order Transaction Fact` where `Invoice Key`=%d",$this->id);

  $result = mysql_query ( $sql );
   if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
     $total_costs=$row['Cost Supplier']+$row['Cost Manufacure']+$row['Cost Storing']+$row['Cost Handing']+$row['Cost Shipping'];
 
   }
   $this->data ['Invoice Total Profit']= $this->data ['Invoice Total Net Amount']- $this->data ['Invoice Refund Net Amount']-$total_costs;



   //print "$total_net ".$this->data ['Invoice Total Net Adjust Amount']."\n";
   $sql = sprintf ( "update `Invoice Dimension` set `Invoice Items Net Amount`=%.2f ,`Invoice Items Net Adjust Amount`=%.2f ,`Invoice Total Net Adjust Amount`=%.2f , `Invoice Items Gross Amount`=%.2f ,`Invoice Items Discount Amount`=%.2f  ,`Invoice Total Net Amount`=%.2f,`Invoice Items Tax Amount`=%.2f,`Invoice Refund Net Amount`=%.2f,`Invoice Refund Tax Amount`=%.2f,`Invoice Total Tax Adjust Amount`=%.2f, `Invoice Total Tax Amount`=%.2f,`Invoice Total Amount`=%.2f,`Invoice Total Adjust Amount`=%.2f,`Invoice Total Profit`=%.2f  where `Invoice Key`=%d"
		    , $this->data ['Invoice Items Net Amount']
		    , $this->data ['Invoice Items Net Adjust Amount']
		    , $this->data ['Invoice Total Net Adjust Amount']
		    , $amount
		    , $discounts
		    , $this->data ['Invoice Total Net Amount']
		    , $this->data ['Invoice Items Tax Amount']
		    , $ref_net
		    , $ref_tax
		    ,$this->data ['Invoice Total Tax Adjust Amount']

		    ,$this->data ['Invoice Total Tax Amount']
		    ,$this->data ['Invoice Total Amount']
		    ,$this->data ['Invoice Total Adjust Amount']
		    ,$this->data ['Invoice Total Profit']

		    , $this->data ['Invoice Key'] 
		     );
   // print "$sql\n";
   if (! mysql_query ( $sql ))
      exit ( "$sql\n xcan not update invoice dimension after invccc\n" );

 }
 /*
function: categorize
Assig a category inside rhe store to the invoice 
 */

 function categorize($args=''){
   $store=new store($this->data['Invoice Store Key']);
   
   
   
   if($store->id==1){
     $this->data['Invoice Category']=$store->data['Store Code'].'-'.$store->data['Store Home Country Short Name'];
     $this->data['Invoice Category Key']=2;
     if($this->data['Invoice Billing Country 2 Alpha Code']!=$store->data['Store Home Country Code 2 Alpha']){
       $this->data['Invoice Category']=$store->data['Store Code'].'-Export';
       $this->data['Invoice Category Key']=4;
       
     }
     if($this->data['Invoice For']=='Staff'){
       $this->data['Invoice Category']=$store->data['Store Code'].'-Staff';
       $this->data['Invoice Category Key']=3;
       
     }
     if($this->data['Invoice For']=='Partner'){
       $this->data['Invoice Category']=$store->data['Store Code'].'-Partner';
       $this->data['Invoice Category Key']=5;

     }

   }else if($store->id==2){
      $this->data['Invoice Category']=$store->data['Store Code'].'-All';
      $this->data['Invoice Category Key']=7;
	     

   }elseif($store->id==3){
     $this->data['Invoice Category']=$store->data['Store Code'].'-All';
     $this->data['Invoice Category Key']=9;

   }
   if(!preg_match('/nosave|no_save/i',$args)){

     $sql = sprintf ( "update `Invoice Dimension` set `Invoice Category`=%s ,`Invoice Category Key`=%d  where `Invoice Key`=%d"
		      , prepare_mysql($this->data['Invoice Category'])
		      , $this->data ['Invoice Category Key'] 
		      , $this->data ['Invoice Key'] 
		      );
     if (! mysql_query ( $sql ))
       exit ( "$sql\n xcan not update invoice dimension after cat\n" );
     
   }

 }
 
 
 
 
 function add_tax_item($code='UNK',$amount=0,$is_base='Yes'){
 $sql=sprintf("insert into `Invoice Tax Bridge` values (%d,%s,%.2f,%s)"
    ,$this->id
    ,prepare_mysql($code)
    ,$amount
    ,prepare_mysql($is_base)
    );
 mysql_query($sql);
 }
 
 
}

?>