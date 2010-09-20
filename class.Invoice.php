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
$this->update_customer=true;

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
      $this->create($arg2);
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


function create_refund($invoice_data){

   $this->data=$this->base_data();
   $this->data ['Invoice Title']='Refund';

if(!isset($invoice_data['Invoice Date'])   ){
    $this->data ['Invoice Date']=date("Y-m-d H:i:s");
}

    $this->set_data_from_customer($invoice_data['Invoice Customer Key'],$invoice_data['Invoice Store Key']);
    foreach($invoice_data as $key=>$value) {
        if (array_key_exists($key,$this->data)) {
            $this->data[$key]=_trim($value);
        }
    }
    $this->data['Invoice File As']=$this->prepare_file_as($this->data['Invoice Public ID']);
    $this->create_header ();
   

     

  
 if(isset( $invoice_data['Order Key']) and $invoice_data['Order Key']){

            $sql = sprintf ( "insert into `Order Invoice Bridge` values (%d,%d)", $invoice_data['Order Key'], $this->id );
            mysql_query ( $sql );
            $this->update_xhtml_orders();
           
      
      
      }
      
      $this->update_xhtml_orders();
}

protected function create_refund_old($invoice_data,$transacions_data,$order,$options='') {

  

    $this->data ['Invoice Items Gross Amount'] =0;
    $this->data ['Invoice Items Discount Amount'] =0;
    
    $this->data ['Invoice Title']='Refund';
    $this->data ['Invoice Date'] = $invoice_data ['Invoice Date'];

    $this->data ['Invoice Public ID'] = $invoice_data ['Invoice Public ID'];
    $this->data ['Invoice File As'] = $this->prepare_file_as($invoice_data ['Invoice Public ID']);
    
    //$this->data ['Invoice Store Key'] = $order->data ['Order Store Key'];
    

   
    if ( $order->data ['Order For']!='')
        $this->data ['Invoice For'] = $order->data ['Order For'];

    if ($this->data ['Invoice Main Source Type']=='')
        $this->data ['Invoice Main Source Type']='Unknown';

    $this->data ['Invoice XHTML Address'] = '';
    $this->data ['Invoice Customer Contact Name'] = '';
    $this->data ['Invoice Billing Country 2 Alpha Code']='XX';
    $this->data ['Invoice Customer Key'] = $order->data ['Order Customer Key'];
    $this->data ['Invoice Customer Name'] = $order->data ['Order Customer Name'];

    $customer=new customer('id',$this->data ['Invoice Customer Key']);

    if ($customer->id) {
        if ($customer->data['Customer Type']=='Company') {
            //TODO  not include if is a fuzzy contact name
            $this->data ['Invoice Customer Contact Name'] =$customer -> data['Customer Main Contact Name'];

        }

        $this->data ['Invoice XHTML Address'] = $customer -> data['Customer Main XHTML Address'];
        $this->data ['Invoice Billing Country 2 Alpha Code']=$customer -> data['Customer Main Country 2 Alpha Code'];

    }



    $this->data ['Invoice XHTML Ship Tos'] = '';
    $this->data ['Invoice Shipping Net Amount'] = $invoice_data ['Invoice Shipping Net Amount'];
    $this->data ['Invoice Charges Net Amount'] = $invoice_data ['Invoice Charges Net Amount'];


    if (isset($myconf['tax_rates'][$invoice_data ['Invoice Tax Code']]))
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

        if ($order->id) {
            $order_date = prepare_mysql ( $order->data ['Order Date'] );
            $order_key=$order->id;
        } else {
            $order_date = 'NULL';
            $order_key=0;
        }
        $sql = sprintf ( "insert into `Order No Product Transaction Fact` (`Order Date`,`Invoice Date`,`Order Key`,`Invoice Key`,`Transaction Type`,`Transaction Description`,`Transaction Net Amount`,`Transaction Tax Amount`,`Currency Code`,`Currency Exchange`,`Metadata`) values  (%s,%s,%s,%s,'Refund',%s,%.2f,%.2f,%s,%f,%s)"
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
            exit ( "Eerror $sql can not update order trwansiocion facrt after invoice" );
    }


}




  
 
protected function create($invoice_data) {
    $this->data=$this->base_data();
    $this->set_data_from_customer($invoice_data['Invoice Customer Key'],$invoice_data['Invoice Store Key']);
    foreach($invoice_data as $key=>$value) {
        if (array_key_exists($key,$this->data)) {
            $this->data[$key]=_trim($value);
        }
    }
    $this->data['Invoice File As']=$this->prepare_file_as($this->data['Invoice Public ID']);
    
            $this->data ['Invoice Currency Exchange']=1;
        $sql=sprintf("select `Corporation Currency` from `Corporation Dimension`");
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $corporation_currency_code=$row['Corporation Currency'];
        } else
            $corporation_currency_code='GBP';
        if ($this->data ['Invoice Currency']!=$corporation_currency_code) {
            $currency_exchange = new CurrencyExchange($this->data ['Invoice Currency'].$corporation_currency_code,$this->data['Invoice Date']);
            $exchange= $currency_exchange->get_exchange();
            $this->data ['Invoice Currency Exchange']=$exchange;
        }
    
    
    $this->create_header ();
    $delivery_notes_ids=array();
    foreach(preg_split('/\,/',$invoice_data['Delivery Note Keys']) as $dn_key) {
        $delivery_notes_ids[$dn_key]=$dn_key;
    }

    $dn_keys=join(',',$delivery_notes_ids);

    $tax_category=$this->data['Invoice Tax Code'];
    $sql=sprintf('select `Transaction Tax Rate`,`Order Quantity`,`Delivery Note Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Fact Key`,`Product Key`,`Delivery Note Quantity` from `Order Transaction Fact` where `Delivery Note Key` in (%s) and ISNULL(`Invoice Key`)  '
    ,$dn_keys);
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        if ($row['Order Quantity']!=0) {
            $factor_actually_packed=$row['Delivery Note Quantity']/$row['Order Quantity'];
        } else {
            $factor_actually_packed=0;
        }
        $sql=sprintf("update `Order Transaction Fact` set `Invoice Date`=%s,`Invoice Currency Code`=%s,`Invoice Key`=%d,`Invoice Public ID`=%s,`Invoice Quantity`=%f,`Invoice Transaction Gross Amount`=%.2f,`Invoice Transaction Total Discount Amount`=%.2f,`Invoice Transaction Item Tax Amount`=%.2f where `Order Transaction Fact Key`=%d",
                     prepare_mysql($this->data['Invoice Date']),
                     prepare_mysql($this->data['Invoice Currency']),
                     $this->id,
                     prepare_mysql($this->data['Invoice Public ID']),
                     $row['Delivery Note Quantity'],

                     $row['Order Transaction Gross Amount']*$factor_actually_packed,
                     $row['Order Transaction Total Discount Amount']*$factor_actually_packed,
                     ($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'])*$factor_actually_packed*$row['Transaction Tax Rate'],
                     $row['Order Transaction Fact Key']
                    );
        mysql_query($sql);
       // print $sql;
    }

$sql=sprintf('select *  from `Order No Product Transaction Fact` where `Delivery Note Key` in (%s) and ISNULL(`Invoice Key`)  '
    ,$dn_keys);
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
     
        $sql=sprintf("update `Order No Product Transaction Fact` set `Invoice Date`=%s,`Invoice Key`=%d,`Transaction Invoice Net Amount`=%.2f,`Transaction Invoice Tax Amount`=%.2f,`Transaction Outstandind Net Amount Balance`=%.2f,`Transaction Outstandind Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d",
                     prepare_mysql($this->data['Invoice Date']),
                     $this->id,
                    $row['Transaction Net Amount'],
                     $row['Transaction Tax Amount'],
                      $row['Transaction Net Amount'],
                     $row['Transaction Tax Amount'],
                     $row['Order No Product Transaction Fact Key']
                    );
        mysql_query($sql);
    //  print $sql;
    }








  foreach($this->get_delivery_notes_objects() as $key=>$dn) {
            $sql = sprintf ( "insert into `Invoice Delivery Note Bridge` values (%d,%d)",  $this->id,$key);
            mysql_query ( $sql );
            $this->update_xhtml_delivery_notes();
            $dn->update_xhtml_invoices();
        }
 
foreach($this->get_orders_objects() as $key=>$order) {
            $sql = sprintf ( "insert into `Order Invoice Bridge` values (%d,%d)", $key, $this->id );
            mysql_query ( $sql );
            $this->update_xhtml_orders();
            $order->update_xhtml_invoices();
        }


  $this->update_totals();



}
  
function get_tax_rate($item) {
    $rate=0;
    switch ($item) {
    case 'shipping':
        $sql=sprintf("select `Tax Category Rate` from `Tax Category Dimension` where `Tax Category Code`=%s",
                     prepare_mysql($this->data['Invoice Tax Shipping Code'])
                    );
        $res=mysql_query($sql);
        
        $rate=0;
        if ($row=mysql_fetch_assoc($res)) {
            $rate=$row['Tax Category Rate'];
        }
      
       
        break;
    case('charges'):
        $sql=sprintf("select `Tax Category Rate` from `Tax Category Dimension` where `Tax Category Code`=%s",
                     prepare_mysql($this->data['Invoice Tax Charges Code'])
                    );
        $res=mysql_query($sql);
        $rate=0;
        if ($row=mysql_fetch_assoc($res)) {
            $rate=$row['Tax Category Rate'];
        }
       
        break;
    default:
        if (is_numeric($item)) {
            $sql=sprintf("select `Transaction Tax Code`,`Transaction Tax Rate`from `Order Transaction Fact` where `Order Transaction Fact Key`=%s",
                         $item
                        );
            $res2=mysql_query($sql);
            if ($row2['Transaction Tax Code']=='UNK') {
                $rate=$row2['Transaction Tax Rate'];
            } else {
                $rate=0;
                if ($row2=mysql_fetch_assoc($res2)) {

                    $sql=sprintf("select `Tax Category Rate` from `Tax Category Dimension` where `Tax Category Code`=%s",
                                 prepare_mysql($row2['Transaction Tax Code'])
                                );
                    $res=mysql_query($sql);
                    $rate=0;
                    if ($row=mysql_fetch_assoc($res)) {
                        $rate=$row['Tax Category Rate'];
                    }
                }
            }
        }
        break;
    }
   
 return $rate;
}
  
  
function update_totals() {
    $shipping_net=0;
    $shipping_tax=0;
    $charges_net=0;
    $charges_tax=0;
    
    $items_gross=0;
    $items_discounts=0;
    $items_net=0;
    $items_tax=0;
    $items_refund_net=0;
    $items_refund_tax=0;
    $items_net_outstanding_balance=0;
    $items_tax_outstanding_balance=0;
    $items_refund_net_outstanding_balance=0;
    $items_refund_tax_outstanding_balance=0;
    
    $adjust_tax=0;
    $adjust_net=0;
    
    
    $sql = sprintf("select `Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Product Code`,`Invoice Transaction Outstanding Net Balance`,`Invoice Transaction Outstanding Tax Balance`,`Invoice Transaction Outstanding Refund Net Balance`,`Invoice Transaction Outstanding Refund Tax Balance`,`Invoice Transaction Net Refund Amount`,`Invoice Transaction Tax Refund Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Invoice Transaction Charges Amount`,`Invoice Transaction Charges Tax Amount`,`Invoice Transaction Shipping Amount`,`Invoice Transaction Shipping Tax Amount`,`Order Transaction Fact Key`,`Invoice Transaction Shipping Tax Amount`,`Invoice Transaction Charges Tax Amount`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as item_net ,`Invoice Transaction Item Tax Amount` 
    from `Order Transaction Fact` left join `Product History Dimension` PH on (`Order Transaction Fact`.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`) where `Invoice Key`=%d  order by `Product Code` " ,
    $this->data ['Invoice Key']);
    
$sql = sprintf("select `Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Outstanding Net Balance`,`Invoice Transaction Outstanding Tax Balance`,`Invoice Transaction Outstanding Refund Net Balance`,`Invoice Transaction Outstanding Refund Tax Balance`,`Invoice Transaction Net Refund Amount`,`Invoice Transaction Tax Refund Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Invoice Transaction Charges Amount`,`Invoice Transaction Charges Tax Amount`,`Invoice Transaction Shipping Amount`,`Invoice Transaction Shipping Tax Amount`,`Order Transaction Fact Key`,`Invoice Transaction Shipping Tax Amount`,`Invoice Transaction Charges Tax Amount`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as item_net ,`Invoice Transaction Item Tax Amount` 
    from `Order Transaction Fact` where `Invoice Key`=%d   " ,
    $this->data ['Invoice Key']);
    
  // print $sql;
 //  print "$\n";
   $counter=0;
   $result = mysql_query ( $sql );
    while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
       $counter++;
        $items_net+=$row['item_net'];
        $items_tax+=$row['Invoice Transaction Item Tax Amount'];
        $items_net_outstanding_balance+=$row['Invoice Transaction Outstanding Net Balance'];
        $items_tax_outstanding_balance+=$row['Invoice Transaction Outstanding Tax Balance'];
        //$items_refund_net+=$row['Invoice Transaction Net Refund Amount'];
        //$items_refund_tax+=$row['Invoice Transaction Tax Refund Amount'];
        //$items_refund_net_outstanding_balance+=$row['Invoice Transaction Outstanding Refund Net Balance'];
        //$items_refund_tax_outstanding_balance+=$row['Invoice Transaction Outstanding Refund Tax Balance'];
        $items_gross+=$row['Invoice Transaction Gross Amount'];
        $items_discounts+=$row['Invoice Transaction Total Discount Amount'];
    //     print "Items net:".$row['Product Code']."   $items_net : ".$row['item_net']." ".$counter."\n";
    }

$sql=sprintf("select * from `Order No Product Transaction Fact` where `Invoice Key`=%d",$this->id);
//print "$sql\n";
 $result = mysql_query ( $sql );
    while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
    if($row['Transaction Type']=='Shipping'){
       $shipping_net+=$row['Transaction Invoice Net Amount'];
       $shipping_tax+=$row['Transaction Invoice Tax Amount'];
    }else if($row['Transaction Type']=='Charges'){
       $charges_net+=$row['Transaction Invoice Net Amount'];
       $charges_tax+=$row['Transaction Invoice Tax Amount'];
    }else if($row['Transaction Type']=='Adjust'){
       $adjust_net+=$row['Transaction Invoice Net Amount'];
       $adjust_tax+=$row['Transaction Invoice Tax Amount'];
    }else if($row['Transaction Type']=='Credit'){

       $items_refund_net+=$row['Transaction Invoice Net Amount'];
       $items_refund_tax+=$row['Transaction Invoice Tax Amount'];
       $items_refund_net_outstanding_balance+=$row['Transaction Outstandind Net Amount Balance'];
       $items_refund_tax_outstanding_balance+=$row['Transaction Outstandind Tax Amount Balance'];
       
    }else{
    
    
    }
    }
$this->data['Invoice Total Net Adjust Amount']= $adjust_net;
$this->data['Invoice Total Tax Adjust Amount']= $adjust_tax;
$this->data['Invoice Total Adjust Amount']= $adjust_tax+$adjust_net;
$this->data['Invoice Refund Net Amount']=$items_refund_net;
$this->data['Invoice Refund Tax Amount']=$items_refund_tax;

    $this->data['Invoice Shipping Tax Amount']= $shipping_tax;
    $this->data['Invoice Shipping Net Amount']= $shipping_net;
    $this->data['Invoice Charges Tax Amount']= $charges_tax;
    $this->data['Invoice Charges Net Amount']= $charges_net;
    $this->data['Invoice Items Tax Amount']= $items_tax;
    $this->data['Invoice Items Net Amount']= $items_net;
    $this->data['Invoice Items Gross Amount']=$items_gross;
    $this->data['Invoice Items Discount Amount']=$items_discounts;
    $this->data['Invoice Total Net Amount']=$this->data['Invoice Refund Net Amount']+$this->data['Invoice Total Net Adjust Amount']+$this->data['Invoice Shipping Net Amount']+$this->data['Invoice Items Net Amount']+$this->data['Invoice Charges Net Amount'];
    $this->data['Invoice Total Tax Amount']=$this->data['Invoice Refund Tax Amount']+$this->data['Invoice Total Tax Adjust Amount']+$this->data['Invoice Shipping Tax Amount']+$this->data['Invoice Items Tax Amount']+$this->data['Invoice Charges Tax Amount'];
   
  // print $this->data['Invoice Shipping Net Amount']."zz\n";
   $this->data['Invoice Outstanding Net Balance']=$items_net_outstanding_balance+$items_refund_net_outstanding_balance;
    $this->data['Invoice Outstanding Tax Balance']=$items_tax_outstanding_balance+$items_refund_tax_outstanding_balance;

    $this->data['Invoice Total Amount']=$this->data['Invoice Total Net Amount']+$this->data['Invoice Total Tax Amount'];
    $sql=sprintf("update  `Invoice Dimension` set `Invoice Refund Net Amount`=%f,`Invoice Refund Tax Amount`=%f,`Invoice Total Net Adjust Amount`=%f,`Invoice Total Tax Adjust Amount`=%f,`Invoice Total Adjust Amount`=%f,`Invoice Outstanding Net Balance`=%f,`Invoice Outstanding Tax Balance`=%f,`Invoice Items Gross Amount`=%f,`Invoice Items Discount Amount`=%f ,`Invoice Items Net Amount`=%f,`Invoice Shipping Net Amount`=%f ,`Invoice Charges Net Amount`=%f ,`Invoice Total Net Amount`=%f ,`Invoice Items Tax Amount`=%f ,`Invoice Shipping Tax Amount`=%f,`Invoice Charges Tax Amount`=%f ,`Invoice Total Tax Amount`=%f,`Invoice Total Amount`=%f where `Invoice Key`=%d",
               $this->data['Invoice Refund Net Amount'],
                $this->data['Invoice Refund Tax Amount'],
               $this->data['Invoice Total Net Adjust Amount'],
                  $this->data['Invoice Total Tax Adjust Amount'],
                 $this->data['Invoice Total Adjust Amount'],
                 $this->data['Invoice Outstanding Net Balance'],
                 $this->data['Invoice Outstanding Tax Balance'],
                 $this->data['Invoice Items Gross Amount'],
                 $this->data['Invoice Items Discount Amount'],
                 $this->data['Invoice Items Net Amount'],
                 $this->data['Invoice Shipping Net Amount'],
                 $this->data['Invoice Charges Net Amount'],
                 $this->data['Invoice Total Net Amount'],
                 $this->data['Invoice Items Tax Amount'],
                 $this->data['Invoice Shipping Tax Amount'],
                 $this->data['Invoice Charges Tax Amount'],
                 $this->data['Invoice Total Tax Amount'],
                 $this->data['Invoice Total Amount'],


                 $this->id
                );
    mysql_query($sql);
//print "\n$sql\n";
//print "\n$sql\n";
}
function update_refund_totals() {
    $shipping_net=0;
    $shipping_tax=0;
    $charges_net=0;
    $charges_tax=0;
    
    $items_gross=0;
    $items_discounts=0;
    $items_net=0;
    $items_tax=0;
    $items_refund_net=0;
    $items_refund_tax=0;
    $items_net_outstanding_balance=0;
    $items_tax_outstanding_balance=0;
    $items_refund_net_outstanding_balance=0;
    $items_refund_tax_outstanding_balance=0;
    $sql = sprintf("select `Invoice Transaction Outstanding Net Balance`,`Invoice Transaction Outstanding Tax Balance`,`Invoice Transaction Outstanding Refund Net Balance`,`Invoice Transaction Outstanding Refund Tax Balance`,`Invoice Transaction Net Refund Amount`,`Invoice Transaction Tax Refund Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Invoice Transaction Charges Amount`,`Invoice Transaction Charges Tax Amount`,`Invoice Transaction Shipping Amount`,`Invoice Transaction Shipping Tax Amount`,`Order Transaction Fact Key`,`Invoice Transaction Shipping Tax Amount`,`Invoice Transaction Charges Tax Amount`,(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as item_net ,(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`)*`Transaction Tax Rate` as tax_item from `Order Transaction Fact` where `Refund Key`=%d" ,
    $this->data ['Invoice Key']);
   //print $sql;
   $result = mysql_query ( $sql );
    while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
      
        $items_refund_net+=$row['Invoice Transaction Net Refund Amount'];
        $items_refund_tax+=$row['Invoice Transaction Tax Refund Amount'];
        $items_refund_net_outstanding_balance+=$row['Invoice Transaction Outstanding Refund Net Balance'];
        $items_refund_tax_outstanding_balance+=$row['Invoice Transaction Outstanding Refund Tax Balance'];
    
    }
//print "::::$items_refund_net--->  \n";

 $sql = sprintf("select * from `Order No Product Transaction Fact` where `Refund Key`=%d" ,
    $this->data ['Invoice Key']);
   $result = mysql_query ( $sql );
    while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
        $items_refund_net+=$row['Transaction Invoice Net Amount'];
        $items_refund_tax+=$row['Transaction Invoice Tax Amount'];
        $items_refund_net_outstanding_balance+=$row['Transaction Outstandind Net Amount Balance'];
        $items_refund_tax_outstanding_balance+=$row['Transaction Outstandind Tax Amount Balance'];
    
    }
   
    $this->data['Invoice Items Tax Amount']= $items_refund_tax;
    $this->data['Invoice Items Net Amount']= $items_refund_net;
    $this->data['Invoice Items Gross Amount']=$items_refund_net;
   
    $this->data['Invoice Total Net Amount']=$this->data['Invoice Shipping Net Amount']+$this->data['Invoice Items Net Amount']+$this->data['Invoice Charges Net Amount'];
    $this->data['Invoice Total Tax Amount']=$this->data['Invoice Shipping Tax Amount']+$this->data['Invoice Items Tax Amount']+$this->data['Invoice Charges Tax Amount'];
    $this->data['Invoice Outstanding Net Balance']=$items_net_outstanding_balance+$items_refund_net_outstanding_balance;
    $this->data['Invoice Outstanding Tax Balance']=$items_tax_outstanding_balance+$items_refund_tax_outstanding_balance;

    $this->data['Invoice Total Amount']=$this->data['Invoice Total Net Amount']+$this->data['Invoice Total Tax Amount'];
    $sql=sprintf("update  `Invoice Dimension` set `Invoice Outstanding Net Balance`=%f,`Invoice Outstanding Tax Balance`=%f,`Invoice Items Gross Amount`=%f,`Invoice Items Discount Amount`=%f ,`Invoice Items Net Amount`=%f,`Invoice Shipping Net Amount`=%f ,`Invoice Charges Net Amount`=%f ,`Invoice Total Net Amount`=%f ,`Invoice Items Tax Amount`=%f ,`Invoice Shipping Tax Amount`=%f,`Invoice Charges Tax Amount`=%f ,`Invoice Total Tax Amount`=%f,`Invoice Total Amount`=%f where `Invoice Key`=%d",
                 $this->data['Invoice Outstanding Net Balance'],
                 $this->data['Invoice Outstanding Tax Balance'],
                 $this->data['Invoice Items Gross Amount'],
                 $this->data['Invoice Items Discount Amount'],
                 $this->data['Invoice Items Net Amount'],
                 $this->data['Invoice Shipping Net Amount'],
                 $this->data['Invoice Charges Net Amount'],
                 $this->data['Invoice Total Net Amount'],
                 $this->data['Invoice Items Tax Amount'],
                 $this->data['Invoice Shipping Tax Amount'],
                 $this->data['Invoice Charges Tax Amount'],
                 $this->data['Invoice Total Tax Amount'],
                 $this->data['Invoice Total Amount'],


                 $this->id
                );
    mysql_query($sql);

//print "\n$sql\n";
}
function update_shipping($amount) {

    if ($amount==$this->data['Invoice Shipping Net Amount']) {
        $this->msg='Nothing to change';
        return;
    }
    $this->data['Invoice Shipping Net Amount']=$amount;
    $this->data['Invoice Shipping Tax Amount']=$amount*($this->get_tax_rate('shipping'));
    $old_shipping_data=array();
    $sql=sprintf("select * from `Order No Product Transaction Fact` where `Transaction Type`='Shipping' and `Invoice Key`=%d  ",$this->id);
    $result = mysql_query ( $sql );
    $old_total=0;
    while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
        $old_shipping_data[$row['Order No Product Transaction Fact Key']]=array(
                    'amount'=>$row['Transaction Net Amount'],
                    'Order No Product Transaction Fact Key'=>$row['Order No Product Transaction Fact Key']
                );
    }
    if ($old_total!=0) {
        foreach($old_shipping_data as $key=> $shipping_data) {
            $old_shipping_data[$key]['factor']=$shipping_data['amount']/$old_total;
        }
    } else {
        foreach($old_shipping_data as $key=> $shipping_data) {
            $old_shipping_data[$key]['factor']=1.0/count($old_shipping_data);
        }
    }
    if (count($old_shipping_data)==0) {
    
            $sql=sprintf("insert into `Order No Product Transaction Fact` (`Invoice Key`,`Invoice Date`,`Transaction Type`,`Transaction Description`,`Tax Category Code`,`Transaction Invoice Net Amount`,`Transaction Invoice Tax Amount`,`Transaction Outstandind Net Amount Balance`,`Transaction Outstandind Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)  
        values (%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
                     $this->id,
                     prepare_mysql($this->data['Invoice Date']),
                     prepare_mysql('Shipping'),
                
                     prepare_mysql('Shipping'),
                     $this->data['Invoice Tax Shipping Code'],
                     $this->data['Invoice Shipping Net Amount'],
                     $this->data['Invoice Shipping Tax Amount'],
                     $this->data['Invoice Shipping Net Amount'],
                     $this->data['Invoice Shipping Tax Amount'],
                     prepare_mysql($this->data['Invoice Currency']),
                     $this->data['Invoice Currency Exchange'],
                     prepare_mysql($this->data['Invoice Metadata'])
                    );

        
        mysql_query($sql);
   // print "$sql\n";
    
    } elseif(count($old_shipping_data)==1) {
        $_tmp=array_pop($old_shipping_data);
        $sql=sprintf("update  `Order No Product Transaction Fact` set `Transaction Invoice Net Amount`=%f,`Transaction Invoice Tax Amount`=%f,`Transaction Outstandind Net Amount Balance`=%f,`Transaction Outstandind Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d",
                     $this->data['Invoice Shipping Net Amount'],
                     $this->data['Invoice Shipping Tax Amount'],
                     $this->data['Invoice Shipping Net Amount'],
                     $this->data['Invoice Shipping Tax Amount'],
                     $_tmp['Order No Product Transaction Fact Key']
                    );
        mysql_query($sql);

    }
    else {
        foreach($old_shipping_data as $shipping_data) {
            $net=$this->data['Invoice Shipping Net Amount']*$shipping_data['factor'];
            $tax=$this->data['Invoice Shipping Tax Amount']*$shipping_data['factor'];
            $sql=sprintf("update  `Order No Product Transaction Fact` set `Transaction Invoice Net Amount`=%f,`Transaction Invoice Tax Amount`=%f,`Transaction Outstandind Net Amount Balance`=%f,`Transaction Outstandind Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d",
                         $net,
                         $tax,
                         $net,
                         $tax,
                         $_tmp['Order No Product Transaction Fact Key']
                        );
            mysql_query($sql);

        }

    }




    $sql=sprintf("update `Invoice Dimension` set `Invoice Shipping Net Amount`=%f,`Invoice Shipping Tax Amount`=%f where `Invoice Key`=%d",
                 $this->data['Invoice Shipping Net Amount'],
                 $this->data['Invoice Shipping Tax Amount'],
                 $this->id
                );
    mysql_query($sql);



    $sql = "select `Order Transaction Fact Key`,`Estimated Weight` from `Order Transaction Fact` where `Invoice Key`=" . $this->data ['Invoice Key'];
    $result = mysql_query ( $sql );
    $total_weight = 0;
    $weight_factor = array ();


    $items = 0;
    while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
        $items ++;
        $weight = $row ['Estimated Weight'];
        $total_weight += $weight;
        $weight_factor [$row ['Order Transaction Fact Key']] = $weight;
    }
    if ($items==0)
        return;
    foreach ( $weight_factor as $line_number => $factor ) {
        if ($total_weight == 0) {
            $shipping = $this->data ['Invoice Shipping Net Amount'] * $factor / $items;
            $shipping_tax=$this->data ['Invoice Shipping Tax Amount'] * $factor / $items;
        } else {
            $shipping = $this->data ['Invoice Shipping Net Amount'] * $factor / $total_weight;
            $shipping_tax=$this->data ['Invoice Shipping Tax Amount'] * $factor / $total_weight;
        }



        $sql = sprintf ( "update `Order Transaction Fact` set `Invoice Transaction Shipping Amount`=%.4f, `Invoice Transaction Shipping Tax Amount`=%.6f where `Order Transaction Fact Key`=%d ",
                         $shipping ,
                         $shipping_tax,
                         $line_number
                       );
        mysql_query ( $sql );
    }

    $this->update_totals();

}

  function update_charges($charge_data){
  
  $amount=$charge_data['Transaction Invoice Net Amount'];
   if ($amount==$this->data['Invoice Charges Net Amount']) {
        $this->msg='Nothing to change';
        return;
    }
    $this->data['Invoice Charges Net Amount']=$amount;
    $this->data['Invoice Charges Tax Amount']=$amount*($this->get_tax_rate('charges'));
    $old_charges_data=array();
    $sql=sprintf("select * from `Order No Product Transaction Fact` where `Transaction Type`='Charges' and `Invoice Key`=%d  ",$this->id);
    $result = mysql_query ( $sql );
    $old_total=0;
    while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
        $old_charges_data[$row['Order No Product Transaction Fact Key']]=array(
                    'amount'=>$row['Transaction Net Amount'],
                    'Order No Product Transaction Fact Key'=>$row['Order No Product Transaction Fact Key']
                );
    }
    if ($old_total!=0) {
        foreach($old_charges_data as $key=> $charges_data) {
            $old_charges_data[$key]['factor']=$charges_data['amount']/$old_total;
        }
    } else {
        foreach($old_charges_data as $key=> $charges_data) {
            $old_charges_data[$key]['factor']=1.0/count($old_charges_data);
        }
    }
    
  
    
    if (count($old_charges_data)==0) {
        

        $sql=sprintf("insert into `Order No Product Transaction Fact` (`Invoice Key`,`Invoice Date`,`Transaction Type`,`Transaction Description`,`Transaction Invoice Net Amount`,`Tax Category Code`,`Transaction Invoice Tax Amount`,`Transaction Outstandind Net Amount Balance`,`Transaction Outstandind Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)  
        values (%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
                     $this->id,
                     prepare_mysql($this->data['Invoice Date']),
                     prepare_mysql('Charges'),
                
                     prepare_mysql($charge_data['Transaction Description']),
                     $this->data['Invoice Charges Net Amount'],
                     prepare_mysql($this->data['Invoice Tax Charges Code']),
                     $this->data['Invoice Charges Tax Amount'],
                     $this->data['Invoice Charges Net Amount'],
                     $this->data['Invoice Charges Tax Amount'],
                     prepare_mysql($this->data['Invoice Currency']),
                     $this->data['Invoice Currency Exchange'],
                     prepare_mysql($this->data['Invoice Metadata'])
                    );

        
        mysql_query($sql);
    
    
    } elseif(count($old_charges_data)==1) {
        $_tmp=array_pop($old_charges_data);
        $sql=sprintf("update  `Order No Product Transaction Fact` set `Transaction Invoice Net Amount`=%f,`Transaction Invoice Tax Amount`=%f,`Transaction Outstandind Net Amount Balance`=%f,`Transaction Outstandind Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d",
                     $this->data['Invoice Charges Net Amount'],
                     $this->data['Invoice Charges Tax Amount'],
                     $this->data['Invoice Charges Net Amount'],
                     $this->data['Invoice Charges Tax Amount'],
                     $_tmp['Order No Product Transaction Fact Key']
                    );
        mysql_query($sql);

    }
    else {
        foreach($old_charges_data as $charges_data) {
            $net=$this->data['Invoice Charges Net Amount']*$charges_data['factor'];
            $tax=$this->data['Invoice Charges Tax Amount']*$charges_data['factor'];
            $sql=sprintf("update  `Order No Product Transaction Fact` set `Transaction Invoice Net Amount`=%f,`Transaction Invoice Tax Amount`=%f,`Transaction Outstandind Net Amount Balance`=%f,`Transaction Outstandind Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d",
                         $net,
                         $tax,
                         $net,
                         $tax,
                         $_tmp['Order No Product Transaction Fact Key']
                        );
            mysql_query($sql);

        }

    }


  $sql=sprintf("update `Invoice Dimension` set `Invoice Charges Net Amount`=%f,`Invoice Charges Tax Amount`=%f where `Invoice Key`=%d",
$this->data['Invoice Charges Net Amount'],
$this->data['Invoice Charges Tax Amount'],
$this->id
);
 mysql_query($sql);
 
  $sql = "select `Order Transaction Fact Key`,`Order Transaction Gross Amount` from `Order Transaction Fact` where `Invoice Key`=" . $this->data ['Invoice Key'];
    $result = mysql_query ( $sql );
    
 $total_charge = 0;
   $charge_factor = array ();

    $items = 0;
    while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
        $items ++;
        $charge = $row ['Order Transaction Gross Amount'];
     $total_charge += $charge;
     $charge_factor [$row ['Order Transaction Fact Key']] = $charge;
    }
    if ($items==0)
        return;
  
    foreach ( $charge_factor as $line_number => $factor ) {
    if ($total_charge == 0) {
        $charges = $this->data ['Invoice Charges Net Amount'] * $factor / $items;
         $charge_tax=$this->data ['Invoice Charges Tax Amount'] * $factor / $items;
    } else {
        $charges = $this->data ['Invoice Charges Net Amount'] * $factor / $total_charge;
        $charge_tax=$this->data ['Invoice Charges Tax Amount'] * $factor / $total_charge;

    }
    
  

        $sql = sprintf ( "update `Order Transaction Fact` set `Invoice Transaction Charges Amount`=%.4f, `Invoice Transaction Charges Tax Amount`=%.6f where `Order Transaction Fact Key`=%d ",
                         $charges ,
                         $charge_tax,
                         $line_number
                       );
        mysql_query ( $sql );
    }
  $this->update_totals();  
 }
  
  
/*
function update_total_amount(){
$this->data['Invoice Total Amount']=$this->data['Invoice Total Net Amount']+$this->data['Invoice Total Tax Amount'];
$sql=sprintf("update `Invoice Dimension` set `Invoice Total Amount`=%f where `Invoice Key`=%d",
$this->data['Invoice Total Amount'],
$this->id
}
     
function update_taxes(){
$sql=sprintf("select `Tax Category Rate` from `Tax Category Code` where `Tax Category Code`=%s ",
$this->data['']
);
$this->data['Invoice Shipping Tax Amount']=$this->data['Invoice Shipping Net Amount']*$tax_rate;


$this->update_total_amount();
}
     
function update_shipping_amount($amount){

$sql=sprintf("update `Invoice Dimension` set `Invoice Shipping Net Amount`=%f,`Invoice Shipping Tax Amount`=%f,`Invoice Total Net Amount`=%f,`Invoice Total Tax Amount`=%f where `Invoice Key`=%d",
$this->data['Invoice Shipping Net Amount'],
$this->data['Invoice Shipping Tax Amount'],
$this->data['Invoice Total Net Amount'],
$this->data['Invoice Total Tax Amount'],
$this->id,

);

}
*/
function create_header() {
  
  //calculate the order total
  $this->data ['Invoice Gross Amount'] = 0;
  $this->data ['Invoice Discount Amount'] = 0;
  
  
  if(!isset($this->data ['Invoice Billing Country 2 Alpha Code']))
    $this->data ['Invoice Billing Country 2 Alpha Code']='XX';
  if(!isset($this->data ['Invoice Delivery Country 2 Alpha Code']))
    $this->data ['Invoice Delivery Country 2 Alpha Code']='XX';
  
  $sql = sprintf ( "insert into `Invoice Dimension` (`Invoice Tax Shipping Code`,`Invoice Tax Charges Code`,`Invoice Customer Contact Name`,`Invoice Currency`,`Invoice Currency Exchange`,`Invoice For`,`Invoice Date`,`Invoice Public ID`,`Invoice File As`,`Invoice Store Key`,`Invoice Store Code`,`Invoice Main Source Type`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice XHTML Ship Tos`,`Invoice Items Gross Amount`,`Invoice Items Discount Amount`,`Invoice Shipping Net Amount`,`Invoice Charges Net Amount`,`Invoice Total Tax Amount`,`Invoice Refund Net Amount`,`Invoice Refund Tax Amount`,`Invoice Total Amount`,`Invoice Metadata`,`Invoice XHTML Address`,`Invoice XHTML Orders`,`Invoice XHTML Delivery Notes`,`Invoice XHTML Store`,`Invoice Has Been Paid In Full`,`Invoice Main Payment Method`,`Invoice Shipping Tax Amount`,`Invoice Charges Tax Amount`,`Invoice XHTML Processed By`,`Invoice XHTML Charged By`,`Invoice Processed By Key`,`Invoice Charged By Key`,`Invoice Billing Country 2 Alpha Code`,`Invoice Delivery Country 2 Alpha Code`,`Invoice Dispatching Lag`,`Invoice Taxable`,`Invoice Tax Code`,`Invoice Title`) values 
  (%s,%s,%s,%s,%f,%s,%s,%s,%s,%s,%s,%s,%s,%s,  %s,%.2f,%.2f,%.2f,%.2f,%.2f,%.2f,%.2f,%.2f,   %s,%s,%s,'%s',%s,%s,%s,%.2f,%.2f,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		   , prepare_mysql ( $this->data ['Invoice Tax Shipping Code'] )
		   , prepare_mysql ( $this->data ['Invoice Tax Charges Code'] )
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



function update_field_switcher($field,$value,$options='') {

    switch ($field) {
    case('Invoice Shipping Net Amount'):
    $this->update_shipping($value);
    break;
     case('Invoice Charges Net Amount'):
    $this->update_charges($value);
    break;
  case('Invoice XHTML Orders'):
       $this->update_xhtml_orders();
            break;
             case('Invoice XHTML Delivery Notes'):
       $this->update_xhtml_delivery_notes();
            break;
    default:
        $base_data=$this->base_data();
        if (array_key_exists($field,$base_data)) {
            if ($value!=$this->data[$field]) {
                $this->update_field($field,$value,$options);
            }
        }
    }
}



function update_xhtml_orders() {
$prefix='';
    $this->data ['Invoice XHTML Orders'] ='';
    $sql=sprintf("select O.`Order Key`,`Order Public ID` from `Order Invoice Bridge` B left join `Order Dimension` O on (O.`Order Key`=B.`Order Key`) where `Invoice Key`=%d",
    $this->id);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)) {
        $this->data ['Invoice XHTML Orders'] .= sprintf ( '%s <a href="order.php?id=%d">%s</a>, ', $prefix, $row['Order Key'], $row['Order Public ID'] );
    }
    $this->data ['Invoice XHTML Orders'] =_trim(preg_replace('/\, $/','',$this->data ['Invoice XHTML Orders']));
   
    $sql=sprintf("update `Invoice Dimension` set `Invoice XHTML Orders`=%s where `Invoice Key`=%d "
                 ,prepare_mysql($this->data['Invoice XHTML Orders'])
                 ,$this->id
                );
    mysql_query($sql);
}

function update_xhtml_delivery_notes() {
$prefix='';
    $this->data ['Invoice XHTML Delivery Notes'] ='';
    foreach($this->get_delivery_notes_objects() as $delivery_note) {
        $this->data ['Invoice XHTML Delivery Notes'] .= sprintf ( '%s <a href="delivery_note.php?id=%d">%s</a>, ', $prefix, $delivery_note->data ['Delivery Note Key'], $delivery_note->data ['Delivery Note ID'] );
    }
    $this->data ['Invoice XHTML Delivery Notes'] =_trim(preg_replace('/\, $/','',$this->data ['Invoice XHTML Delivery Notes']));
 
    $sql=sprintf("update `Invoice Dimension` set `Invoice XHTML Delivery Notes`=%s where `Invoice Key`=%d "
                 ,prepare_mysql($this->data['Invoice XHTML Delivery Notes'])
                 ,$this->id
                );
    mysql_query($sql);
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
  case('Total Net Adjust Amount'):
    case('Total Tax Adjust Amount'):

    return money($this->data['Invoice '.$key],$this->data['Invoice Currency']);
  } 
  
  
  if(isset($this->data[$key]))
    return $this->data[$key];
   
  return false;
}



 /*Function: update_field_switcher
  */


function display($tipo='xml'){



  switch($tipo){

  default:
    return 'todo';
    
  }
  
  
}

 function distribute_costs_old() {
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



function get_orders_ids(){
$sql=sprintf("select `Order Key` from `Order Transaction Fact` where `Invoice Key`=%d  or  `Refund Key`=%d  group by `Order Key`",$this->id,$this->id);
	   
            $res = mysql_query ( $sql );
            $orders=array();
            while ($row = mysql_fetch_array ( $res, MYSQL_ASSOC )) {
                if ($row['Order Key']) {
                    $orders[$row['Order Key']]=$row['Order Key'];
                }

            }
            return $orders;

}
function get_orders_objects(){
$orders=array();
$orders_ids=$this->get_orders_ids();
foreach ($orders_ids as $order_id) {
    $orders[$order_id]=new Order($order_id);
}
return $orders;
}
function get_delivery_notes_ids(){
$sql=sprintf("select `Delivery Note Key` from `Order Transaction Fact` where `Invoice Key`=%d  or  `Refund Key`=%d  group by `Delivery Note Key`",$this->id,$this->id);
	   
            $res = mysql_query ( $sql );
            $delivery_notes=array();
            while ($row = mysql_fetch_array ( $res, MYSQL_ASSOC )) {
                if ($row['Delivery Note Key']) {
                    $delivery_notes[$row['Delivery Note Key']]=$row['Delivery Note Key'];
                }

            }
            return $delivery_notes;

}
function get_delivery_notes_objects(){
$delivery_notes=array();
$delivery_notes_ids=$this->get_delivery_notes_ids();
foreach ($delivery_notes_ids as $order_id) {
    $delivery_notes[$order_id]=new DeliveryNote($order_id);
}
return $delivery_notes;
}








 
 function pay_full_amount($data){
 $this->data['Invoice Paid Date']=$data['Invoice Paid Date'];
   $sql = sprintf ( "update  `Order Transaction Fact`  set `Payment Method`=%s,`Invoice Transaction Outstanding Net Balance`=0,`Invoice Transaction Outstanding Tax Balance`=0,`Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s,`Invoice Transaction Outstanding Net Balance`=0,`Invoice Transaction Outstanding Tax Balance`=0 ,`Invoice Transaction Outstanding Tax Balance`=0 where `Invoice Key`=%d and `Consolidated`='No' "
   ,prepare_mysql($data['Payment Method'])
   ,prepare_mysql($this->data['Invoice Paid Date'])
   ,$this->id);
    //print "$sql\n";
   mysql_query ( $sql );

     $sql=sprintf("update `Invoice Dimension`  set `Invoice Main Payment Method`=%s,`Invoice Paid Date`=%s ,`Invoice Paid`='Yes',`Invoice Has Been Paid In Full`='Yes' where `Invoice Key`=%d"
		,prepare_mysql($this->data['Invoice Main Payment Method'])
		,prepare_mysql($this->data['Invoice Paid Date'])

		,$this->id);
   mysql_query ( $sql );

  
 
 }
 
 function pay($tipo='full', $data){

if(!array_key_exists('Invoice Paid Date',$data) or !$data['Invoice Paid Date']  ){
$data['Invoice Paid Date']=date('Y-m-d H:i:s');
}

   if($tipo=='full'){
    $this->pay_full_amount($data);
   }else{
   
   }
   foreach($this->get_orders_objects() as $key=>$order) {
             $order->update_payment_state();
        }

   foreach($this->get_orders_objects() as $order){
  
    $order->update_no_normal_totals();
   }
  
 }


 function get_totals_old($force_values=false){



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
 
 

function add_credit_no_product_transaction($credit_transaction_data) {


$order=new Order($credit_transaction_data['Order Key']);
if($order->id){

$order_date=$order->data['Order Date'];
}else{
$order_date='';

}

    $sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Invoice Key`,`Invoice Date`,`Transaction Type`,`Transaction Description`,`Transaction Invoice Net Amount`,`Tax Category Code`,`Transaction Invoice Tax Amount`,`Transaction Outstandind Net Amount Balance`,`Transaction Outstandind Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)   values (%s,%s,%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
                 prepare_mysql($credit_transaction_data['Order Key']),
                 prepare_mysql($order_date),
                 $this->id,
                 prepare_mysql($this->data['Invoice Date']),
                 prepare_mysql('Credit'),
                 prepare_mysql($credit_transaction_data['Transaction Description']),
                 $credit_transaction_data['Transaction Invoice Net Amount'],
                 prepare_mysql($credit_transaction_data['Tax Category Code']),
                 $credit_transaction_data['Transaction Invoice Tax Amount'],
                 $credit_transaction_data['Transaction Invoice Net Amount'],
                 $credit_transaction_data['Transaction Invoice Tax Amount'],
                 prepare_mysql($this->data['Invoice Currency']),
                 $this->data['Invoice Currency Exchange'],
                 prepare_mysql($credit_transaction_data['Metadata'])
                );
    mysql_query($sql);
    $this->update_refund_totals();
}

function add_refund_no_product_transaction($refund_transaction_data) {

    $sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Refund Key`,`Refund Date`,`Transaction Type`,`Transaction Description`,`Transaction Invoice Net Amount`,`Tax Category Code`,`Transaction Invoice Tax Amount`,`Transaction Outstandind Net Amount Balance`,`Transaction Outstandind Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)   values (%s,%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
                 prepare_mysql($refund_transaction_data['Order Key']),
                 $this->id,
                 prepare_mysql($this->data['Invoice Date']),
                 prepare_mysql('Refund'),
                 prepare_mysql($refund_transaction_data['Transaction Description']),
                 $refund_transaction_data['Transaction Invoice Net Amount'],
                 prepare_mysql($refund_transaction_data['Tax Category Code']),
                 $refund_transaction_data['Transaction Invoice Tax Amount'],
                 $refund_transaction_data['Transaction Invoice Net Amount'],
                 $refund_transaction_data['Transaction Invoice Tax Amount'],
                 prepare_mysql($this->data['Invoice Currency']),
                 $this->data['Invoice Currency Exchange'],
                 prepare_mysql($this->data['Invoice Metadata'])
                );
    mysql_query($sql);
    $this->update_refund_totals();
}
 
 function add_refund_transaction($refund_transaction_data){
 
 $sql=sprintf("update `Order Transaction Fact` set `Refund Metadata`=%s,`Refund Key`=%d, `Invoice Transaction Net Refund Amount`=%f, `Invoice Transaction Tax Refund Amount`=%f  ,`Invoice Transaction Outstanding Refund Net Balance`=%f ,`Invoice Transaction Outstanding Refund Tax Balance`=%f where `Order Transaction Fact Key`=%d ",
 prepare_mysql($refund_transaction_data['Refund Metadata']),
 $this->id,
  $refund_transaction_data['Invoice Transaction Net Refund Amount'],
   $refund_transaction_data['Invoice Transaction Tax Refund Amount'],
    $refund_transaction_data['Invoice Transaction Net Refund Amount'],
   $refund_transaction_data['Invoice Transaction Tax Refund Amount'],
 $refund_transaction_data['Order Transaction Fact Key']
 
 );
 mysql_query($sql);
 //print $sql;
 $this->update_refund_totals();
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
 
 
 
         function set_data_from_customer($customer_key,$store_key=false) {


            $customer=new Customer($customer_key);
            if (!$customer->id) {
                $customer= new Customer('create anonymous');
            } else
                $store_key=$customer->data['Customer Store Key'];



            $this->data['Invoice Customer Name']=$customer->get('Customer Name');
            $this->data['Invoice Customer Contact Name']=$customer->get('Customer Main Contact Name');
            
            $billing_address=new Address($customer->get_principal_billing_address_key());
            
            $this->data['Invoice XHTML Address']=$billing_address->display('xhtml');
            $this->data['Invoice Billing Country 2 Alpha Code']=$billing_address->get('Address Country 2 Alpha Code');
           
            $this->data['Invoice For Partner']=$customer->get('Customer Is Partner');
             $this->data['Invoice For']='Customer';
             if($customer->get('Customer Is Partner')=='Yes')
            $this->data['Invoice For']='Partner';
             if($customer->get('Customer Staff')=='Yes')
              $this->data['Invoice For']='Staff';
              $this->data['Invoice Main Payment Method']=$customer->get('Customer Last Payment Method');
              
            
            $this->set_data_from_store($store_key);



         


        }
        function set_data_from_store($store_key) {
            $store=new Store($store_key);
            if (!$store->id) {
                $this->error=true;
                return;
            }


         
         $this->data['Invoice Currency']=$store->data['Store Currency Code'];
$this->data['Invoice Store Code']=$store->data['Store Code'];
$this->data['Invoice XHTML Store']=sprintf("<a href='store.php?id=%d'>%s</a>",$store->id,$store->get('Store Name'));


/*

 $store=new Store($this->data ['Invoice Store Key']);

    $this->data ['Invoice Store Code'] = $store->data ['Store Code'];
    if (isset($invoice_data['Invoice Currency'])) {
        $this->data['Invoice Currency']=$invoice_data['Invoice Currency'];
    } else {
        $this->data['Invoice Currency']=$store->data['Store Currency Code'];
    }

    if ($myconf['currency_code']!=$this->data['Invoice Currency']) {
        //print $myconf['currency_code'].' -> '.$this->data['Invoice Currency']."\n";
        if (isset($invoice_data['Invoice Currency Exchange'])) {
            $this->data['Invoice Currency Exchange']=$invoice_data['Invoice Currency Exchange'];
        } else {
            $exchange=1;


            $sql=sprintf("select `Exchange` from `History Currency Exchange Dimension` where `Currency Pair`='EURGBP' and `Date`=DATE(%s)"
                         ,prepare_mysql($this->data ['Invoice Date'] ));

            $res=mysql_query($sql);
            if ($row2=mysql_fetch_array($res, MYSQL_ASSOC)) {
                $exchange=$row2['Exchange'];

            }
            $this->data['Invoice Currency Exchange']=$exchange;


        }

    } else
        $this->data['Invoice Currency Exchange']=1;


    $this->data ['Invoice XHTML Store'] = $order->data ['Order XHTML Store'];
    $this->data ['Invoice Main Source Type'] = $order->data ['Order Main Source Type'];
    $this->data ['Invoice For'] ='Customer';

*/



        }
 
 
 function prepare_file_as($number){

$number=strtolower($number);
if(preg_match("/^\d+/",$number,$match)){
$part_number=$match[0];
$file_as=preg_replace('/^\d+/',sprintf("%012d",$part_number),$number);

}elseif(preg_match("/\d+$/",$number,$match)){
$part_number=$match[0];
$file_as=preg_replace('/\d+$/',sprintf("%012d",$part_number),$number);

}else{
$file_as=$number;
}

return $file_as;
}
 
 
}

?>