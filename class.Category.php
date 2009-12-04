<?php
/*
 File: Category.php

 This file contains the Category Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/
include_once('class.DB_Table.php');

class Category extends DB_Table {
  
  function Category($a1,$a2=false) {
    
    $this->table_name='Category';
    $this->ignore_fields=array('Category Key');
    
    if (is_numeric($a1) and !$a2) {
      $this->get_data('id',$a1);
    } else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
      $this->find($a2,'create');
      
    } elseif(preg_match('/find/i',$a1))
            $this->find($a2,$a1);
    else
      $this->get_data($a1,$a2);

    }

  function get_data($tipo,$tag) {
    
    $sql=sprintf("select * from `Category Dimension` where `Category Key`=%d",$tag);
    $result=mysql_query($sql);
    
    if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
      $this->id=$this->data['Category Key'];
    }
  }
  
  
  function find($raw_data,$options) {
    
    if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
      foreach($raw_data['editor'] as $key=>$value) {
	if (array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
	
      }
    }
    
    $this->candidate=array();
    $this->found=false;
    $this->found_key=0;
    $create='';
    $update='';
    if (preg_match('/create/i',$options)) {
      $create='create';
    }
    if (preg_match('/update/i',$options)) {
            $update='update';
    }
    
    $data=$this->base_data();
    foreach($raw_data as $key=>$value) {

      if (array_key_exists($key,$data))
	$data[$key]=$value;
      
    }
    $fields=array();
    foreach($data as $key=>$value){
      if(!($key=='Category Begin Date' 
	   or $key=='Category Expiration Date' 
	   or $key=='Category Terms Metadata' 
	     or $key=='Category Metadata'   ))
        $fields[]=$key;
    }
       
    $sql="select `Category Key` from `Category Dimension` where  true ";
    //print_r($fields);
    foreach($fields as $field) {
      $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
    }
    
        $result=mysql_query($sql);
        $num_results=mysql_num_rows($result);
        if ($num_results==1) {
	  $row=mysql_fetch_array($result, MYSQL_ASSOC);
	  $this->found=true;
	  $this->found_key=$row['Category Key'];
          
        }
        if($this->found){
	  $this->get_data('id',$this->found_key);
        }
        
        if($create and !$this->found){
        $this->create($data);
        
        }


    }



    function create($data) {


      if($data['Category Trigger Key']=='')
	$data['Category Trigger Key']=0;
      
      $data['Category Metadata']=Category::parse_category_metadata($data['Category Type'],$data['Category Description']);
      $data['Category Terms Metadata']=Deal::parse_term_metadata($data['Category Terms Type'],$data['Category Terms Description']);


     
        //print_r($data);

        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";
            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Category Dimension` %s %s",$keys,$values);
        // print "$sql\n";
        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id',$this->id);
        } else {
            print "Error can not create category  $sql\n";
            exit;

        }
    }

    function get($key='') {
    
        if (isset($this->data[$key]))
            return $this->data[$key];

        switch ($key) {

        }

        return false;
    }

    function load($key,$args=''){
      switch($key){
      case('sales'):
	$this->update_sales();
	break;
	
      case('product_data'):
	$this->update_product_data();
	break;
      }
    }
    

    function update_sales(){
      $sql="select * from `Store Dimension`";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$this->update_sales_store($row['Store Key']);
      }
      mysql_free_result($result);
    }



 function update_product_data(){
      $sql="select * from `Store Dimension`";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$this->update_store_product_data($row['Store Key']);
      }
      mysql_free_result($result);
    }

    function update_sales_store($store_key){
      // print_r($this->data);

      if($this->data['Category Subject']!='Product')
	return;

      
  $on_sale_days=0;
     
  $sql=sprintf("select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as tto, sum(if(`Product Sales State`='For Sale',1,0)) as for_sale   from `Product Dimension` as P left join `Category Bridge` as B on (B.`Subject Key`=P.`Product ID`)  where `Subject`='Product' and `Category Key`=%d and `Product Store Key`=%d",$this->id,$store_key);
  //print "$sql\n";
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $from=strtotime($row['ffrom']);
      $_from=date("Y-m-d H:i:s",$from);
      if($row['for_sale']>0){
	$to=strtotime('today');
	$_to=date("Y-m-d H:i:s");
      }else{
	$to=strtotime($row['tto']);
	$_to=date("Y-m-d H:i:s",$to);
      }
      $on_sale_days=($to-$from)/ (60 * 60 * 24);

      if($row['prods']==0)
	$on_sale_days=0;

    }
 
    $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact` OTF left join `Product History Dimension` PH on (PH.`Product Key`=OTF.`Product Key`)  left join `Category Bridge` B  on  (B.`Subject Key`=PH.`Product ID`)   where `Subject`='Product' and  `Current Dispatching State` not in ('Unknown','Dispached','Cancelled')  and  `Category Key`=%d and `Store Key`=%d",$this->id,$store_key);
    //print $sql;
    $result=mysql_query($sql);
    $pending_orders=0;
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $pending_orders=$row['pending_orders'];
    }
    $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF   left join `Product History Dimension` PH on (PH.`Product Key`=OTF.`Product Key`)   left join `Category Bridge` B  on  (B.`Subject Key`=PH.`Product ID`)  where `Subject`='Product' and `Category Key`=%d and `Store Key`=%d",$this->id,$store_key);


    //print "$sql\n\n";
    // exit;
    $result=mysql_query($sql);
 
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data['Product Category Total Invoiced Gross Amount']=$row['gross'];
      $this->data['Product Category Total Invoiced Discount Amount']=$row['disc'];
      $this->data['Product Category Total Invoiced Amount']=$row['gross']-$row['disc'];

      $this->data['Product Category Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
      $this->data['Product Category Total Quantity Ordered']=$row['ordered'];
      $this->data['Product Category Total Quantity Invoiced']=$row['invoiced'];
      $this->data['Product Category Total Quantity Delivered']=$row['delivered'];
      $this->data['Product Category Total Days On Sale']=$on_sale_days;
      $this->data['Product Category Valid From']=$_from;
      $this->data['Product Category Valid To']=$_to;
      $this->data['Product Category Total Customers']=$row['customers'];
      $this->data['Product Category Total Invoices']=$row['invoices'];
      $this->data['Product Category Total Pending Orders']=$pending_orders;

      $sql=sprintf("update `Product Category Dimension` set `Product Category Total Invoiced Gross Amount`=%s,`Product Category Total Invoiced Discount Amount`=%s,`Product Category Total Invoiced Amount`=%s,`Product Category Total Profit`=%s, `Product Category Total Quantity Ordered`=%s , `Product Category Total Quantity Invoiced`=%s,`Product Category Total Quantity Delivered`=%s ,`Product Category Total Days On Sale`=%f ,`Product Category Valid From`=%s,`Product Category Valid To`=%s ,`Product Category Total Customers`=%d,`Product Category Total Invoices`=%d,`Product Category Total Pending Orders`=%d  where `Product Category Key`=%d and `Product Category Store Key`=%d  "
		   ,prepare_mysql($this->data['Product Category Total Invoiced Gross Amount'])
		   ,prepare_mysql($this->data['Product Category Total Invoiced Discount Amount'])
		   ,prepare_mysql($this->data['Product Category Total Invoiced Amount'])

		   ,prepare_mysql($this->data['Product Category Total Profit'])
		   ,prepare_mysql($this->data['Product Category Total Quantity Ordered'])
		   ,prepare_mysql($this->data['Product Category Total Quantity Invoiced'])
		   ,prepare_mysql($this->data['Product Category Total Quantity Delivered'])
		   ,$on_sale_days
		   ,prepare_mysql($this->data['Product Category Valid From'])
		   ,prepare_mysql($this->data['Product Category Valid To'])
		   ,$this->data['Product Category Total Customers']
		   ,$this->data['Product Category Total Invoices']
		   ,$this->data['Product Category Total Pending Orders']
		   ,$this->id
		   ,$store_key
		   );
      //  print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql\ncan not update dept sales\n");
    }
    // days on sale
     

    return;

    $on_sale_days=0;



    $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For Sale',1,0)) as for_sale   from  `Product Dimension` as P left join `Product Category Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Category Key`=".$this->id;
    // print "$sql\n\n";
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      if($row['prods']==0)
	$on_sale_days=0;
      else{
	

	if($row['for_sale']>0)
	  $to=strtotime('today');
	else
	  $to=strtotime($row['to']);
	// print "*** ".$row['to']." T:$to  ".strtotime('today')."  ".strtotime('today -1 year')."  \n";
	// print "*** T:$to   ".strtotime('today -1 year')."  \n";
	if($to>strtotime('today -1 year')){
	  //print "caca";
	  $from=strtotime($row['ffrom']);
	  if($from<strtotime('today -1 year'))
	    $from=strtotime('today -1 year');
	    
	  //	    print "*** T:$to F:$from\n";
	  $on_sale_days=($to-$from)/ (60 * 60 * 24);
	}else{
	  //   print "pipi";
	  $on_sale_days=0;

	}
      }
    }



    //$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
    $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispached','Cancelled') 
        and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));
      
    $result=mysql_query($sql);
    $pending_orders=0;
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $pending_orders=$row['pending_orders'];
    }
    $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
        ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  
        from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));
	
    $result=mysql_query($sql);

    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data['Store 1 Year Acc Invoiced Gross Amount']=$row['gross'];
      $this->data['Store 1 Year Acc Invoiced Discount Amount']=$row['disc'];
      $this->data['Store 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];
	      
      $this->data['Store 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
      $this->data['Store 1 Year Acc Quantity Ordered']=$row['ordered'];
      $this->data['Store 1 Year Acc Quantity Invoiced']=$row['invoiced'];
      $this->data['Store 1 Year Acc Quantity Delivered']=$row['delivered'];
      $this->data['Store 1 Year Acc Customers']=$row['customers'];
      $this->data['Store 1 Year Acc Invoices']=$row['invoices'];
      $this->data['Store 1 Year Acc Pending Orders']=$pending_orders;
        
      $sql=sprintf("update `Store Dimension` set `Store 1 Year Acc Invoiced Gross Amount`=%s,`Store 1 Year Acc Invoiced Discount Amount`=%s,`Store 1 Year Acc Invoiced Amount`=%s,`Store 1 Year Acc Profit`=%s, `Store 1 Year Acc Quantity Ordered`=%s , `Store 1 Year Acc Quantity Invoiced`=%s,`Store 1 Year Acc Quantity Delivered`=%s ,`Store 1 Year Acc Days On Sale`=%f ,`Store 1 Year Acc Customers`=%d,`Store 1 Year Acc Invoices`=%d,`Store 1 Year Acc Pending Orders`=%d   where `Store Key`=%d "
		   ,prepare_mysql($this->data['Store 1 Year Acc Invoiced Gross Amount'])
		   ,prepare_mysql($this->data['Store 1 Year Acc Invoiced Discount Amount'])
		   ,prepare_mysql($this->data['Store 1 Year Acc Invoiced Amount'])

		   ,prepare_mysql($this->data['Store 1 Year Acc Profit'])
		   ,prepare_mysql($this->data['Store 1 Year Acc Quantity Ordered'])
		   ,prepare_mysql($this->data['Store 1 Year Acc Quantity Invoiced'])
		   ,prepare_mysql($this->data['Store 1 Year Acc Quantity Delivered'])
		   ,$on_sale_days
		   ,$this->data['Store 1 Year Acc Customers']
		   ,$this->data['Store 1 Year Acc Invoices']
		   ,$this->data['Store 1 Year Acc Pending Orders']
		   ,$this->id
		   );
      //  print "$sql\n";
 

      if(!mysql_query($sql))
	exit("$sql\ncan not update dept sales\n");
    }
    // exit;
    $on_sale_days=0;
      

    $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;

    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      if($row['prods']==0)
	$on_sale_days=0;
      else{
	

	if($row['for_sale']>0)
	  $to=strtotime('today');
	else
	  $to=strtotime($row['to']);
	if($to>strtotime('today -3 month')){
	    
	  $from=strtotime($row['ffrom']);
	  if($from<strtotime('today -3 month'))
	    $from=strtotime('today -3 month');
	    
	    
	  $on_sale_days=($to-$from)/ (60 * 60 * 24);
	}else
	  $on_sale_days=0;
      }
    }

    //$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
    $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispached','Cancelled') 
        and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));
      
    $result=mysql_query($sql);
    $pending_orders=0;
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $pending_orders=$row['pending_orders'];
    }
    $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
        ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  
        from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));



    $result=mysql_query($sql);
 
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data['Store 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
      $this->data['Store 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
      $this->data['Store 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];
	      
      $this->data['Store 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
      $this->data['Store 1 Quarter Acc Quantity Ordered']=$row['ordered'];
      $this->data['Store 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
      $this->data['Store 1 Quarter Acc Quantity Delivered']=$row['delivered'];
      $this->data['Store 1 Quarter Acc Customers']=$row['customers'];
      $this->data['Store 1 Quarter Acc Invoices']=$row['invoices'];
      $this->data['Store 1 Quarter Acc Pending Orders']=$pending_orders;      

        
      $sql=sprintf("update `Store Dimension` set `Store 1 Quarter Acc Invoiced Gross Amount`=%s,`Store 1 Quarter Acc Invoiced Discount Amount`=%s,`Store 1 Quarter Acc Invoiced Amount`=%s,`Store 1 Quarter Acc Profit`=%s, `Store 1 Quarter Acc Quantity Ordered`=%s , `Store 1 Quarter Acc Quantity Invoiced`=%s,`Store 1 Quarter Acc Quantity Delivered`=%s  ,`Store 1 Quarter Acc Days On Sale`=%f ,`Store 1 Quarter Acc Customers`=%d,`Store 1 Quarter Acc Invoices`=%d,`Store 1 Quarter Acc Pending Orders`=%d   where `Store Key`=%d "
		   ,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Gross Amount'])
		   ,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Discount Amount'])
		   ,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Amount'])

		   ,prepare_mysql($this->data['Store 1 Quarter Acc Profit'])
		   ,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Ordered'])
		   ,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Invoiced'])
		   ,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Delivered'])
		   ,$on_sale_days
		   ,$this->data['Store 1 Quarter Acc Customers']
		   ,$this->data['Store 1 Quarter Acc Invoices']
		   ,$this->data['Store 1 Quarter Acc Pending Orders']
		   ,$this->id
		   );
      // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql\ncan not update dept sales\n");
    }

    $on_sale_days=0;

    $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      if($row['prods']==0)
	$on_sale_days=0;
      else{
	

	if($row['for_sale']>0)
	  $to=strtotime('today');
	else
	  $to=strtotime($row['to']);
	if($to>strtotime('today -1 month')){
	    
	  $from=strtotime($row['ffrom']);
	  if($from<strtotime('today -1 month'))
	    $from=strtotime('today -1 month');
	    
	    
	  $on_sale_days=($to-$from)/ (60 * 60 * 24);
	}else
	  $on_sale_days=0;
      }
    }

    //$sql="select  sum(`Product 1 Month Acc Invoiced Amount`) as net,sum(`Product 1 Month Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Month Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Month Acc Profit`)as profit ,sum(`Product 1 Month Acc Quantity Delivered`) as delivered,sum(`Product 1 Month Acc Quantity Ordered`) as ordered,sum(`Product 1 Month Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
    $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispached','Cancelled') 
        and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));
      
    $result=mysql_query($sql);
    $pending_orders=0;
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $pending_orders=$row['pending_orders'];
    }
    $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
        ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  
        from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));


    
    $result=mysql_query($sql);
 
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data['Store 1 Month Acc Invoiced Gross Amount']=$row['gross'];
      $this->data['Store 1 Month Acc Invoiced Discount Amount']=$row['disc'];
      $this->data['Store 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];

      $this->data['Store 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
      $this->data['Store 1 Month Acc Quantity Ordered']=$row['ordered'];
      $this->data['Store 1 Month Acc Quantity Invoiced']=$row['invoiced'];
      $this->data['Store 1 Month Acc Quantity Delivered']=$row['delivered'];
      $this->data['Store 1 Month Acc Customers']=$row['customers'];
      $this->data['Store 1 Month Acc Invoices']=$row['invoices'];
      $this->data['Store 1 Month Acc Pending Orders']=$pending_orders;
        
      $sql=sprintf("update `Store Dimension` set `Store 1 Month Acc Invoiced Gross Amount`=%s,`Store 1 Month Acc Invoiced Discount Amount`=%s,`Store 1 Month Acc Invoiced Amount`=%s,`Store 1 Month Acc Profit`=%s, `Store 1 Month Acc Quantity Ordered`=%s , `Store 1 Month Acc Quantity Invoiced`=%s,`Store 1 Month Acc Quantity Delivered`=%s  ,`Store 1 Month Acc Days On Sale`=%f ,`Store 1 Month Acc Customers`=%d,`Store 1 Month Acc Invoices`=%d,`Store 1 Month Acc Pending Orders`=%d   where `Store Key`=%d "
		   ,prepare_mysql($this->data['Store 1 Month Acc Invoiced Gross Amount'])
		   ,prepare_mysql($this->data['Store 1 Month Acc Invoiced Discount Amount'])
		   ,prepare_mysql($this->data['Store 1 Month Acc Invoiced Amount'])

		   ,prepare_mysql($this->data['Store 1 Month Acc Profit'])
		   ,prepare_mysql($this->data['Store 1 Month Acc Quantity Ordered'])
		   ,prepare_mysql($this->data['Store 1 Month Acc Quantity Invoiced'])
		   ,prepare_mysql($this->data['Store 1 Month Acc Quantity Delivered'])
		   ,$on_sale_days
		   ,$this->data['Store 1 Month Acc Customers']
		   ,$this->data['Store 1 Month Acc Invoices']
		   ,$this->data['Store 1 Month Acc Pending Orders']
		   ,$this->id
		   );
      // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql\ncan not update dept sales\n");
    }

    $on_sale_days=0;
    $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Store Key`=".$this->id;
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      if($row['prods']==0)
	$on_sale_days=0;
      else{
	

	if($row['for_sale']>0)
	  $to=strtotime('today');
	else
	  $to=strtotime($row['to']);
	if($to>strtotime('today -1 week')){
	    
	  $from=strtotime($row['ffrom']);
	  if($from<strtotime('today -1 week'))
	    $from=strtotime('today -1 week');
	    
	    
	  $on_sale_days=($to-$from)/ (60 * 60 * 24);
	}else
	  $on_sale_days=0;
      }
    }

 
    //$sql="select sum(`Product 1 Week Acc Invoiced Amount`) as net,sum(`Product 1 Week Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Week Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Week Acc Profit`)as profit ,sum(`Product 1 Week Acc Quantity Delivered`) as delivered,sum(`Product 1 Week Acc Quantity Ordered`) as ordered,sum(`Product 1 Week Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Store Key`=".$this->id;

    $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispached','Cancelled') 
        and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
      
    $result=mysql_query($sql);
    $pending_orders=0;
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $pending_orders=$row['pending_orders'];
    }
    $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross   ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced   from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
    //	print $sql;
    $result=mysql_query($sql);

    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data['Store 1 Week Acc Invoiced Gross Amount']=$row['gross'];
      $this->data['Store 1 Week Acc Invoiced Discount Amount']=$row['disc'];
      $this->data['Store 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
      $this->data['Store 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
      $this->data['Store 1 Week Acc Quantity Ordered']=$row['ordered'];
      $this->data['Store 1 Week Acc Quantity Invoiced']=$row['invoiced'];
      $this->data['Store 1 Week Acc Quantity Delivered']=$row['delivered'];

      $this->data['Store 1 Week Acc Customers']=$row['customers'];
      $this->data['Store 1 Week Acc Invoices']=$row['invoices'];
      $this->data['Store 1 Week Acc Pending Orders']=$pending_orders;
        
      $sql=sprintf("update `Store Dimension` set `Store 1 Week Acc Invoiced Gross Amount`=%s,`Store 1 Week Acc Invoiced Discount Amount`=%s,`Store 1 Week Acc Invoiced Amount`=%s,`Store 1 Week Acc Profit`=%s, `Store 1 Week Acc Quantity Ordered`=%s , `Store 1 Week Acc Quantity Invoiced`=%s,`Store 1 Week Acc Quantity Delivered`=%s ,`Store 1 Week Acc Days On Sale`=%f  ,`Store 1 Week Acc Customers`=%d,`Store 1 Week Acc Invoices`=%d,`Store 1 Week Acc Pending Orders`=%d   where `Store Key`=%d "
		   ,prepare_mysql($this->data['Store 1 Week Acc Invoiced Gross Amount'])
		   ,prepare_mysql($this->data['Store 1 Week Acc Invoiced Discount Amount'])
		   ,prepare_mysql($this->data['Store 1 Week Acc Invoiced Amount'])
		   ,prepare_mysql($this->data['Store 1 Week Acc Profit'])
		   ,prepare_mysql($this->data['Store 1 Week Acc Quantity Ordered'])
		   ,prepare_mysql($this->data['Store 1 Week Acc Quantity Invoiced'])
		   ,prepare_mysql($this->data['Store 1 Week Acc Quantity Delivered'])
		   ,$on_sale_days
		   ,$this->data['Store 1 Week Acc Customers']
		   ,$this->data['Store 1 Week Acc Invoices']
		   ,$this->data['Store 1 Week Acc Pending Orders']
		   ,$this->id
		   );
      // print "$sql\n";
      if(!mysql_query($sql))
	exit("$sql\ncan not update dept sales\n");
     
    }

     

    }
 
   
 function update_store_product_data($store_key){

      if($this->data['Category Subject']!='Product')
	return;


   $sql=sprintf("select sum(if(`Product Record Type`='In process',1,0)) as in_process,sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For Sale',1,0)) as for_sale,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension`  left join `Category Bridge` on (`Subject Key`=`Product ID`)  where `Subject`='Product' and   `Product Store Key`=%d and `Category Key`=%d",$store_key,$this->id);
   // print "$sql\n\n\n";
   
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

      $sql=sprintf("update `Product Category Dimension` set `Product Category In Process Products`=%d,`Product Category For Sale Products`=%d ,`Product Category Discontinued Products`=%d ,`Product Category Not For Sale Products`=%d ,`Product Category Unknown Sales State Products`=%d, `Product Category Optimal Availability Products`=%d , `Product Category Low Availability Products`=%d ,`Product Category Critical Availability Products`=%d ,`Product Category Out Of Stock Products`=%d,`Product Category Unknown Stock Products`=%d ,`Product Category Surplus Availability Products`=%d where `Product Category Store Key`=%d and `Product Category Key`=%d  ",
		   $row['in_process'],
		   $row['for_sale'],
		   $row['discontinued'],
		   $row['not_for_sale'],
		   $row['sale_unknown'],
		   $row['availability_optimal'],
		   $row['availability_low'],
		   $row['availability_critical'],
		   $row['availability_outofstock'],
		   $row['availability_unknown'],
		   $row['availability_surplus'],
		   $store_key,
		   $this->id
		   );
      //print "$sql\n";exit;
      mysql_query($sql);
      $this->get_data('id',$this->id);
    
    }

 
  }

}