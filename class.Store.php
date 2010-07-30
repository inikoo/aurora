<?php
/*
  File: Company.php 

  This file contains the Company Class

  About: 
  Autor: Raul Perusquia <rulovico@gmail.com>
 
  Copyright (c) 2009, Kaktus 
 
  Version 2.0
*/

/* class: Store
   Class to manage the *Company Dimension* table
*/

include_once('class.DB_Table.php');

class Store extends DB_Table{

  // Integer: id
  // Record Id
 
  /*
    Constructor: Store
     
    Initializes the class, Search/Load or Create for the data set 
     
    Parameters:
    arg1 -    (optional) Could be the tag for the Search Options or the Store Key for a simple object key search
    arg2 -    (optional) data used to search or create the object

    Returns:
    void
       
    Example:
    (start example)
    // Load data from `Store Dimension` table where  `Store Key`=3
    $key=3;
    $company = New Store($key); 

    // Insert row to `Store Dimension` table
    $data=array();
    $company = New Store('new',$data); 
       

    (end example)

  */

  function Store($a1,$a2=false,$a3=false) {
    $this->table_name='Store';
    $this->ignore_fields=array(
			       'Store Key',
			       'Store Departments',
			       'Store Families',
			       'Store For Sale Products',
			       'Store In Process Products',
			       'Store Not For Sale Products',
			       'Store Discontinued Products',
			       'Store Unknown Sales State Products',
			       'Store Surplus Availability Products',
			       'Store Optimal Availability Products',
			       'Store Low Availability Products',
			       'Store Critical Availability Products',
			       'Store Out Of Stock Products',
			       'Store Unknown Stock Products',
			       'Store Total Invoiced Gross Amount',
			       'Store Total Invoiced Discount Amount',
			       'Store Total Invoiced Amount',
			       'Store Total Profit',
			       'Store Total Quantity Ordered',
			       'Store Total Quantity Invoiced',
			       'Store Total Quantity Delivere',
			       'Store Total Days On Sale',
			       'Store Total Days Available',
			       'Store 1 Year Acc Invoiced Gross Amount',
			       'Store 1 Year Acc Invoiced Discount Amount',
			       'Store 1 Year Acc Invoiced Amount',
			       'Store 1 Year Acc Profit',
			       'Store 1 Year Acc Quantity Ordered',
			       'Store 1 Year Acc Quantity Invoiced',
			       'Store 1 Year Acc Quantity Delivere',
			       'Store 1 Year Acc Days On Sale',
			       'Store 1 Year Acc Days Available',
			       'Store 1 Quarter Acc Invoiced Gross Amount',
			       'Store 1 Quarter Acc Invoiced Discount Amount',
			       'Store 1 Quarter Acc Invoiced Amount',
			       'Store 1 Quarter Acc Profit',
			       'Store 1 Quarter Acc Quantity Ordered',
			       'Store 1 Quarter Acc Quantity Invoiced',
			       'Store 1 Quarter Acc Quantity Delivere',
			       'Store 1 Quarter Acc Days On Sale',
			       'Store 1 Quarter Acc Days Available',
			       'Store 1 Month Acc Invoiced Gross Amount',
			       'Store 1 Month Acc Invoiced Discount Amount',
			       'Store 1 Month Acc Invoiced Amount',
			       'Store 1 Month Acc Profit',
			       'Store 1 Month Acc Quantity Ordered',
			       'Store 1 Month Acc Quantity Invoiced',
			       'Store 1 Month Acc Quantity Delivere',
			       'Store 1 Month Acc Days On Sale',
			       'Store 1 Month Acc Days Available',
			       'Store 1 Week Acc Invoiced Gross Amount',
			       'Store 1 Week Acc Invoiced Discount Amount',
			       'Store 1 Week Acc Invoiced Amount',
			       'Store 1 Week Acc Profit',
			       'Store 1 Week Acc Quantity Ordered',
			       'Store 1 Week Acc Quantity Invoiced',
			       'Store 1 Week Acc Quantity Delivere',
			       'Store 1 Week Acc Days On Sale',
			       'Store 1 Week Acc Days Available',
			       'Store Total Quantity Delivered',
			       'Store 1 Year Acc Quantity Delivered',
			       'Store 1 Month Acc Quantity Delivered',
			       'Store 1 Quarter Acc Quantity Delivered',
			       'Store 1 Week Acc Quantity Delivered'


			       );
    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }elseif($a1=='find'){
      $this->find($a2,$a3);
    
    }else
       $this->get_data($a1,$a2);

  }
  
  // function get_unknown(){
  //   $sql=sprintf("select * from `Store Dimension` where `Store Type`='unknown'");
  //   $result=mysql_query($sql);
  //   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
  //     $this->id=$this->data['Store Key'];
  // }





  /*
    Function: data
    Obtiene los datos de la tabla Store Dimension de acuerdo al Id o al codigo de registro.
  */
  // JFA

  function get_data($tipo,$tag){

    if($tipo=='id')
      $sql=sprintf("select * from `Store Dimension` where `Store Key`=%d",$tag);
    elseif($tipo=='code')
      $sql=sprintf("select * from `Store Dimension` where `Store Code`=%s",prepare_mysql($tag));
    else
      return;

    // print $sql;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Store Key'];
    

  }

  /*
    Function: find
    Busca el producto
  */
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
    

    //    print_r($raw_data);

    if($data['Store Code']=='' ){
      $this->error=true;
      $this->msg='Store code empty';
      return;
    }

    if($data['Store Name']=='')
      $data['Store Name']=$data['Store Code'];
    

    $sql=sprintf("select * from `Store Dimension` where `Store Code`=%s  "
		 ,prepare_mysql($data['Store Code'])
		 ); 

    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->found=true;
      $this->found_key=$row['Store Key'];
    }
   
   
    if($create and !$this->found){
      $this->create($data);
      return;
    }
    if($this->found)
      $this->get_data('id',$this->found_key);
    
    if($update and $this->found){

    }

  }


  /*
    Function: get
    Obtiene datos del producto de acuerdo al codigo de producto, al tipo de producto o la totalidad de productos (esto en base al criterio de seleccion)
  */
  // JFA
 
  function get($key=''){

    if(isset($this->data[$key]))
      return $this->data[$key];
    



    if (preg_match('/^(Total|1).*(Amount|Profit)$/',$key)) {

      $amount='Store '.$key;

      return money($this->data[$amount]);
    }
    if (preg_match('/^(Total|1).*(Quantity (Ordered|Invoiced|Delivered|)|Invoices|Pending Orders|Customers|Customer Contacts)$/',$key) or preg_match('/^(Active Customers)$/',$key)) {

      $amount='Store '.$key;

      return number($this->data[$amount]);
    }

    switch($key){
    case('code'):
      return $this->data['Store Code'];
      break;
    case('type'):
      return $this->data['Store Type'];
      break;
    case('Total Products'):
      return $this->data['Store For Sale Products']+$this->data['Store In Process Products']+$this->data['Store Not For Sale Products']+$this->data['Store Discontinued Products']+$this->data['Store Unknown Sales State Products'];
      break;
    case('For Sale Products'):
      return number($this->data['Store For Sale Products']);
      break;
    case('For Public Sale Products'):
      return number($this->data['Store For Public Sale Products']);
      break;
    case('Families'):
      return number($this->data['Store Families']);
      break;
    case('Departments'):
      return number($this->data['Store Departments']);
      break;
    }
    $_key=ucfirst($key);
    if(isset($this->data[$_key]))
      return $this->data[$_key];
  
  }

  /*
    Function: delete
    Elimina registros de la tabla Store Dimension en base al valor del campo store key, siempre y cuando no haya productos
  */
  // JFA

  function delete(){
    $this->deleted=false;
    $this->load('products_info');

    if($this->get('Total Products')==0){
      $sql=sprintf("delete from `Store Dimension` where `Store Key`=%d",$this->id);
      if(mysql_query($sql)){

	$this->deleted=true;
	  
      }else{

	$this->msg=_('Error: can not delete store');
	return;
      }     

      $this->deleted=true;
    }else{
      $this->msg=_('Store can not be deleted because it has some products');

    }
  }


  /*
    Method: load
    Obtiene registros de las tablas Product Dimension, Product Family Dimension, Product Department Dimension, y actualiza datos de Store Dimension, de acuerdo a la categoria indicada.
  */
  // JFA


  function load($tipo,$args=false){
    switch($tipo){
 



    case('families'):
      $sql=sprintf("select * from `Product Family Dimension`  where  `Product Family Store Key`=%d",$this->id);
      //  print $sql;

      $this->families=array();
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->families[$row['family key']]=$row;
      }
      break;
  
    case('sales'):
     
      $this->update_store_sales();
      $this->update_sales_default_currency();
   
     
      break;
    case('products_info'):
      $this->update_product_data();
      $this->update_families();
      break;
    }
   
  }

  /*
    Function: update
    Funcion que permite actualizar el nombre o el codigo en la tabla store dimension, cuidando que no se duplique el valor del codigo o el nombre en dicha tabla
  */
  // JFA

  function update($key,$a1=false,$a2=false){
    $this->updated=false;
    $this->msg='Nothing to change';
   
    switch($key){
    case('code'):

      if(_trim($a1)==$this->data['Store Code']){
	$this->updated=true;
	$this->new_value=$a1;
	return;
       
      }

      if($a1==''){
	$this->msg=_('Error: Wrong code (empty)');
	return;
      }

      if(!(strtolower($a1)==strtolower($this->data['Store Code']) and $a1!=$this->data['Store Code'])){
	$sql=sprintf("select count(*) as num from `Store Dimension` where  `Store Code`=%s COLLATE utf8_general_ci  "
		     ,prepare_mysql($a1)
		     );
	$res=mysql_query($sql);
	$row=mysql_fetch_array($res);
	if($row['num']>0){
	  $this->msg=_("Error: There is another store with the same code");
	  return;
	}
      }
      $old_value=$this->get('Store Code');
      $sql=sprintf("update `Store Dimension` set `Store Code`=%s where `Store Key`=%d  "
		   ,prepare_mysql($a1)
		   ,$this->id
		   );
      if(mysql_query($sql)){
	$this->msg=_('Store code updated');
	$this->updated=true;$this->new_value=$a1;
	$this->data['Store Code']=$a1;

	

	$this->add_history(array(
				 'Indirect Object'=>'Store Code'
				 ,'History Abstract'=>_('Store Code Changed').' ('.$this->get('Store Code').')'
				 ,'History Details'=>_('Store')." ".$this->data['Store Name']." "._('changed code from').' '.$old_value." "._('to').' '. $this->get('Store Code')
				 ));
	

	
	
	
      }else{
	$this->msg=_("Error: Store code could not be updated");

	$this->updated=false;
	
      }
      break;	
      
      case('slogan'):
      $this->update_field('Store Slogan',$a1);
      break;
         case('url'):
      $this->update_field('Store URL',$a1);
      break;
      
       case('contact'):
      $this->update_field('Store Contact',$a1);
      break; 
          case('email'):
      $this->update_field('Store Email',$a1);
      break; 
          case('telephone'):
      $this->update_field('Store Telephone',$a1);
      break; 
             case('fax'):
      $this->update_field('Store Fax',$a1);
      break; 
    case('name'):
     
      if(_trim($a1)==$this->data['Store Name']){
	$this->updated=true;
	$this->new_value=$a1;
	return;
       
      }
     
      if($a1==''){
	$this->msg=_('Error: Wrong name (empty)');
	return;
      }

      if(!(strtolower($a1)==strtolower($this->data['Store Name']) and $a1!=$this->data['Store Name'])){

	$sql=sprintf("select count(*) as num from `Store Dimension` where `Store Name`=%s COLLATE utf8_general_ci"
		     ,prepare_mysql($a1)
		     );
       
	$res=mysql_query($sql);
	$row=mysql_fetch_array($res);
	if($row['num']>0){
	  $this->msg=_("Error: Another store with the same name");
	  return;
	}
      }
      $old_value=$this->get('Store Name');
      $sql=sprintf("update `Store Dimension` set `Store Name`=%s where `Store Key`=%d "
		   ,prepare_mysql($a1)
		   ,$this->id
		   );
      if(mysql_query($sql)){
	$this->msg=_('Store name updated');
	$this->updated=true;$this->new_value=$a1;
	$this->data['Store Name']=$a1;

	$this->add_history(array(
				 'Indirect Object'=>'Store Name'
				 ,'History Abstract'=>_('Store Name Changed').' ('.$this->get('Store Name').')' 
				 ,'History Details'=>_('Store')." ("._('Code').":".$this->get('Store Code').") "._('name changed from').' '.$old_value." "._('to').' '. $this->get('Store Name')
				 ));



	
	
      }else{
	$this->msg=_("Error: Store name could not be updated");

	$this->updated=false;
	
      }
      break;	


    }


  }

  /*
    Function: create
    Funcion que permite grabar el nombre y codigo en la tabla store dimension, evitando duplicar el valor de codigo y el nombre en dicha tabla
  */
  // JFA
  function create($data){


   


    /*  if(!isset($data['Store Code'])){ */
    /*      $this->msg=_("Error: No store code provided"); */
    /*      return; */
    /*    } */

    /*    if($data['Store Code']=='' ){ */
    /*      $this->msg=_("Error: Wrong store code"); */
    /*      return; */
    /*    } */

    /*    if(!isset($data['Store Name'])){ */
    /*      $data['Store Name']=$data['Store Code']; */
    /*       $this->msg=_("Warning: No store name"); */
    /*    } */


    /*    $sql=sprintf("select count(*) as num from `Store Dimension` where `Store Code`=%s " */
    /* 		,prepare_mysql($data['Store Code']) */
    /* 		); */
    /*    $res=mysql_query($sql); */
    /*    $row=mysql_fetch_array($res); */
    /*    if($row['num']>0){ */
    /*      $this->msg=_("Error: Another store with the same code"); */
    /*      return; */
     
    /*    } */
   
    /*    $sql=sprintf("select count(*) as num from `Store Dimension` where `Store Name`=%s " */
    /* 		,prepare_mysql($data['Store Name']) */
    /* 		); */
    /*    $res=mysql_query($sql); */
    /*    $row=mysql_fetch_array($res); */
    /*    if($row['num']>0){ */
    /*      $this->msg=_("Warning: Wrong another store with the same name"); */

     
    /*    } */



    /*    $sql=sprintf("insert into `Store Dimension` (`Store Code`,`Store Name`) values (%s,%s)" */
    /* 		,prepare_mysql($data['Store Code']) */
    /* 		,prepare_mysql($data['Store Name']) */
    /* 		); */
    $this->new=false;
    $basedata=$this->base_data();
   
    foreach($data as $key=>$value){
      if(array_key_exists($key,$basedata))
	$basedata[$key]=_trim($value);
    }

    $keys='(';$values='values(';
    foreach($basedata as $key=>$value){
      $keys.="`$key`,";
      if(preg_match('/Store Email|Store Telephone|Store Telephone|Slogan|URL|Fax/i',$key))
	$values.=prepare_mysql($value,false).",";
      else
	$values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Store Dimension` %s %s",$keys,$values);

    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->msg=_("Store Added");
      $this->get_data('id',$this->id);
      $this->new=true;
 $sql="insert into `User Right Scope Bridge` values(1,'Store',".$this->id.");";
      mysql_query($sql);

      $sql="insert into `Store Default Currency` (`Store Key`) values(".$this->id.");";
      mysql_query($sql);

	$this->add_history(array(
				 'Action'=>'created'
				 ,'History Abstract'=>_('Store Created')
				 ,'History Details'=>_('Store')." ".$this->data['Store Name']." (".$this->get('Store Code').") "._('Created')
				 ));

      return;
    }else{
   // print $sql;
      $this->msg=_(" Error can not create store");

    }

  }
 
 
  function update_product_data(){

    $availability='No Applicable';
    $sales_type='No Applicable';
    $in_process=0;
    $public_sale=0;
    $private_sale=0;
    $discontinued=0;
    $not_for_sale=0;
    $sale_unknown=0;
    $availability_optimal=0;
    $availability_low=0;
    $availability_critical=0;
    $availability_outofstock=0;
    $availability_unknown=0;
    $availability_surplus=0;
$new=0;




$sql=sprintf("select sum(if(`Product Record Type`='New',1,0)) as new,sum(if(`Product Record Type`='In process',1,0)) as in_process,sum(if(`Product Sales Type`='Unknown',1,0)) as sale_unknown, sum(if(`Product Record Type`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales Type`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales Type`='Public Sale',1,0)) as public_sale,sum(if(`Product Sales Type`='Private Sale',1,0)) as private_sale,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` where `Product Store Key`=%d",$this->id);
   
//print $sql; 
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
            $new=$row['new'];

      $in_process=$row['in_process'];
      $public_sale=$row['public_sale'];
      $private_sale=$row['private_sale'];
      $discontinued=$row['discontinued'];
      $not_for_sale=$row['not_for_sale'];
      $sale_unknown=$row['sale_unknown'];
      $availability_optimal=$row['availability_optimal'];
      $availability_low=$row['availability_low'];
      $availability_critical=$row['availability_critical'];
      $availability_outofstock=$row['availability_outofstock'];
      $availability_unknown=$row['availability_unknown'];
      $availability_surplus=$row['availability_surplus'];
    }

  $sql=sprintf("update `Store Dimension` set `Store In Process Products`=%d,`Store For Public Sale Products`=%d, `Store For Private Sale Products`=%d ,`Store Discontinued Products`=%d ,`Store Not For Sale Products`=%d ,`Store Unknown Sales State Products`=%d, `Store Optimal Availability Products`=%d , `Store Low Availability Products`=%d ,`Store Critical Availability Products`=%d ,`Store Out Of Stock Products`=%d,`Store Unknown Stock Products`=%d ,`Store Surplus Availability Products`=%d ,`Store New Products`=%d where `Store Key`=%d  ",
	      $in_process,
	       $public_sale,
	       $private_sale,
	       $discontinued,
	       $not_for_sale,
	       $sale_unknown,
	       $availability_optimal,
	       $availability_low,
	       $availability_critical,
	       $availability_outofstock,
	       $availability_unknown,
	       $availability_surplus,
	       $new,
		   $this->id
		   );
  // print "$sql\n";
      mysql_query($sql);
      
      
      
      
      
  }
 
 function update_customers_data(){
 

 
     $sql=sprintf("select count(*) as num ,sum(IF(`Customer Orders`>0,1,0)) as customers,sum(IF(`New Served Customer`='Yes',1,0)) as new_served,sum(IF(`New Customer`='Yes',1,0)) as new_contact,sum(IF(`Active Customer`='Yes',1,0)) as active, sum(IF(`Customer Type by Activity`='Inactive',1,0)) as lost  from   `Customer Dimension` where `Customer Store Key`=%d",$this->id);
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data['Store Total Customer Contacts']=$row['num'];
      $this->data['Store New Customer Contacts']=$row['new_contact'];
      $this->data['Store Total Customers']=$row['customers'];
      $this->data['Store Active Customers']=$row['active'];
      $this->data['Store New Customers']=$row['new_served'];
      $this->data['Store Lost Customers']=$row['num'];
     }else{
         $this->data['Store Total Customer Contacts']=0;
      $this->data['Store New Customer Contacts']=0;
      $this->data['Store Total Customers']=0;
      $this->data['Store Active Customers']=0;
      $this->data['Store New Customers']=0;
      $this->data['Store Lost Customers']=0;
     
     }
 
  $sql=sprintf("update `Store Dimension` set `Store Total Customer Contacts`=%d , `Store New Customer Contacts`=%d ,`Store Total Customers`=%d,`Store Active Customers`=%d,`Store New Customers`=%d , `Store Lost Customers`=%d where `Store Key`=%d  ",
		  $this->data['Store Total Customer Contacts'],
      $this->data['Store New Customer Contacts'],
      $this->data['Store Total Customers'],
      $this->data['Store Active Customers'],
      $this->data['Store New Customers'],
      $this->data['Store Lost Customers']
	   
		 ,$this->id
		 );
    mysql_query($sql);
 
 
 }
 
 
  function update_families(){
    $sql=sprintf("select count(*) as num from `Product Family Dimension`  where `Product Family Record Type` in ('New','Normal','Discontinuing') and  `Product Family Store Key`=%d",$this->id);
    //  print $sql;
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data['Store Families']=$row['num'];
    }

  
    $sql=sprintf("update `Store Dimension` set `Store Families`=%d  where `Store Key`=%d  ",
		 $this->data['Store Families']
	   
		 ,$this->id
		 );
    //  print "$sql\n";exit;
    mysql_query($sql);
 
  }
 
  function update_departments(){
 
    $sql=sprintf("select count(*) as num from `Product Department Dimension`  where  `Product Department Store Key`=%d",$this->id);
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data['Store Departments']=$row['num'];
    }
  
    $sql=sprintf("update `Store Dimension` set `Store Departments`=%d  where `Store Key`=%d  ",
	    
		 $this->data['Store Departments']
		 ,$this->id
		 );
    //print "$sql\n";
    mysql_query($sql);
 
  }
  function update_store_sales(){
    $on_sale_days=0;
     
    $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as tto, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Store Key`=".$this->id;

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
    //$sql="select sum(`Product Total Invoiced Amount`) as net,sum(`Product Total Invoiced Gross Amount`) as gross,sum(`Product Total Invoiced Discount Amount`) as disc, sum(`Product Total Profit`)as profit ,sum(`Product Total Quantity Delivered`) as delivered,sum(`Product Total Quantity Ordered`) as ordered,sum(`Product Total Quantity Invoiced`) as invoiced  from `Product Dimension` as P where `Product Store Key`=".$this->id;

    $sql="select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`   where  `Current Dispatching State` not in ('Unknown','Dispached','Cancelled')  and  `Store Key`=".$this->id;
    $result=mysql_query($sql);
    $pending_orders=0;
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $pending_orders=$row['pending_orders'];
    }
    $sql="select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF   where `Store Key`=".$this->id;


    //print "$sql\n\n";
    // exit;
    $result=mysql_query($sql);
 
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data['Store Total Invoiced Gross Amount']=$row['gross'];
      $this->data['Store Total Invoiced Discount Amount']=$row['disc'];
      $this->data['Store Total Invoiced Amount']=$row['gross']-$row['disc'];

      $this->data['Store Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
      $this->data['Store Total Quantity Ordered']=$row['ordered'];
      $this->data['Store Total Quantity Invoiced']=$row['invoiced'];
      $this->data['Store Total Quantity Delivered']=$row['delivered'];
      $this->data['Store Total Days On Sale']=$on_sale_days;
      $this->data['Store Valid From']=$_from;
      $this->data['Store Valid To']=$_to;
      $this->data['Store Total Customers']=$row['customers'];
      $this->data['Store Total Invoices']=$row['invoices'];
      $this->data['Store Total Pending Orders']=$pending_orders;

      $sql=sprintf("update `Store Dimension` set `Store Total Invoiced Gross Amount`=%s,`Store Total Invoiced Discount Amount`=%s,`Store Total Invoiced Amount`=%s,`Store Total Profit`=%s, `Store Total Quantity Ordered`=%s , `Store Total Quantity Invoiced`=%s,`Store Total Quantity Delivered`=%s ,`Store Total Days On Sale`=%f ,`Store Valid From`=%s,`Store Valid To`=%s ,`Store Total Customers`=%d,`Store Total Invoices`=%d,`Store Total Pending Orders`=%d  where `Store Key`=%d "
		   ,prepare_mysql($this->data['Store Total Invoiced Gross Amount'])
		   ,prepare_mysql($this->data['Store Total Invoiced Discount Amount'])
		   ,prepare_mysql($this->data['Store Total Invoiced Amount'])

		   ,prepare_mysql($this->data['Store Total Profit'])
		   ,prepare_mysql($this->data['Store Total Quantity Ordered'])
		   ,prepare_mysql($this->data['Store Total Quantity Invoiced'])
		   ,prepare_mysql($this->data['Store Total Quantity Delivered'])
		   ,$on_sale_days
		   ,prepare_mysql($this->data['Store Valid From'])
		   ,prepare_mysql($this->data['Store Valid To'])
		   ,$this->data['Store Total Customers']
		   ,$this->data['Store Total Invoices']
		   ,$this->data['Store Total Pending Orders']
		   ,$this->id
		   );
     
      if(!mysql_query($sql))
	exit("$sql\ncan not update dept sales\n");
    }
    // days on sale
     
    $on_sale_days=0;



    $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Store Key`=".$this->id;
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
    $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
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
      

    $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;

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
    $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
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

    $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;
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
    $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
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
    $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Store Key`=".$this->id;
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
    $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross   ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced   from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
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
 


  function update_sales_default_currency(){
    $this->data_default_currency=array();
    $this->data_default_currency['Store DC Total Invoiced Gross Amount']=0;
    $this->data_default_currency['Store DC Total Invoiced Discount Amount']=0;
    $this->data_default_currency['Store DC Total Invoiced Amount']=0;
    $this->data_default_currency['Store DC Total Profit']=0;
    $this->data_default_currency['Store DC 1 Year Acc Invoiced Gross Amount']=0;
    $this->data_default_currency['Store DC 1 Year Acc Invoiced Discount Amount']=0;
    $this->data_default_currency['Store DC 1 Year Acc Invoiced Amount']=0;
    $this->data_default_currency['Store DC 1 Year Acc Profit']=0;
    $this->data_default_currency['Store DC 1 Quarter Acc Invoiced Discount Amount']=0;
    $this->data_default_currency['Store DC 1 Quarter Acc Invoiced Amount']=0;
    $this->data_default_currency['Store DC 1 Quarter Acc Profit']=0;
    $this->data_default_currency['Store DC 1 Month Acc Invoiced Gross Amount']=0;
    $this->data_default_currency['Store DC 1 Month Acc Invoiced Discount Amount']=0;
    $this->data_default_currency['Store DC 1 Month Acc Invoiced Amount']=0;
    $this->data_default_currency['Store DC 1 Month Acc Profit']=0;
    $this->data_default_currency['Store DC 1 Week Acc Invoiced Gross Amount']=0;
    $this->data_default_currency['Store DC 1 Week Acc Invoiced Discount Amount']=0;
    $this->data_default_currency['Store DC 1 Week Acc Invoiced Amount']=0;
    $this->data_default_currency['Store DC 1 Week Acc Profit']=0;



    $sql="select     sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  from `Order Transaction Fact`  OTF   where `Store Key`=".$this->id;
	
	
    //print "$sql\n\n";
    // exit;
    $result=mysql_query($sql);
	
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data_default_currency['Store DC Total Invoiced Gross Amount']=$row['gross'];
      $this->data_default_currency['Store DC Total Invoiced Discount Amount']=$row['disc'];
      $this->data_default_currency['Store DC Total Invoiced Amount']=$row['gross']-$row['disc'];
      $this->data_default_currency['Store DC Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

    }


     
    $sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross 
        ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  
        from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));
	
    $result=mysql_query($sql);

    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data_default_currency['Store DC 1 Year Acc Invoiced Gross Amount']=$row['gross'];
      $this->data_default_currency['Store DC 1 Year Acc Invoiced Discount Amount']=$row['disc'];
      $this->data_default_currency['Store DC 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];
      $this->data_default_currency['Store DC 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

    }
     
    $sql=sprintf("select   sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));
    $result=mysql_query($sql);
 
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data_default_currency['Store DC 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
      $this->data_default_currency['Store DC 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
      $this->data_default_currency['Store DC 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];
      $this->data_default_currency['Store DC 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

    }

    $sql=sprintf("select    sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross  ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc    from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));


    
    $result=mysql_query($sql);
 
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data_default_currency['Store DC 1 Month Acc Invoiced Gross Amount']=$row['gross'];
      $this->data_default_currency['Store DC 1 Month Acc Invoiced Discount Amount']=$row['disc'];
      $this->data_default_currency['Store DC 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];
      $this->data_default_currency['Store DC 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

    }
    $sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross   ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc    from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
    //	print $sql;
    $result=mysql_query($sql);

    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data_default_currency['Store DC 1 Week Acc Invoiced Gross Amount']=$row['gross'];
      $this->data_default_currency['Store DC 1 Week Acc Invoiced Discount Amount']=$row['disc'];
      $this->data_default_currency['Store DC 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
      $this->data_default_currency['Store DC 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
      
    }

    $insert_values='';
    $update_values='';
    foreach($this->data_default_currency as $key=>$value){
      $insert_values.=sprintf(',%.2f',$value);
      $update_values.=sprintf(',`%s`=%.2f',addslashes($key),$value);
    }
    $insert_values=preg_replace('/^,/','',$insert_values);
    $update_values=preg_replace('/^,/','',$update_values);


    $sql=sprintf('Insert into `Store Default Currency` values (%d,%s) ON DUPLICATE KEY UPDATE %s  ',$this->id,$insert_values,$update_values);
    mysql_query($sql);
    //print "$sql\n";



  }

  function create_page($data){
    
    if(array_key_exists('Showcases',$data))
 	    $showcases=$data['Showcases'];
 	else
 	    $showcases['Presentation']=array('Display'=>true,'Type'=>'Template','Contents'=>$this->data['Store Name']);

 	  if(array_key_exists('Showcases',$data))
 	$product_layouts=$data['Product Layouts'];
 	else
 	$product_layouts=array('List'=>array('Display'=>true,'Type'=>'Auto'));
 	
	$showcases_layout=$data['Showcases Layout'];
      $page_data=array(
		       'Page Code'=>'SD_'.$this->data['Store Code']
		       ,'Page Source Template'=>'pages/'.$this->data['Store Code'].'/catalogue'
		       ,'Page URL'=>'catalogue.php?code='.$this->data['Store Code']
		       ,'Page Description'=>'Store Catalogue'
		       ,'Page Title'=>$this->data['Store Name']
		       ,'Page Short Title'=>$this->data['Store Name']
		       ,'Page Store Title'=>$this->data['Store Name']
		       ,'Page Store Subtitle'=>''
		       ,'Page Store Slogan'=>$data['Page Store Slogan']
		       ,'Page Store Resume'=>$data['Page Store Resume']
		       ,'Page Store Showcases'=>$showcases
		       ,'Page Store Showcases Layout'=>$showcases_layout
		       ,'Page Store Product Layouts'=>$product_layouts
		       );
      
      $page_data['Page Store Function']='Store Catalogue';
      $page_data['Page Store Creation Date']=date('Y-m-d H:i:s');
      $page_data['Page Store Last Update Date']=date('Y-m-d H:i:s');
      $page_data['Page Store Last Structural Change Date']=date('Y-m-d H:i:s');
      $page_data['Page Type']='Store';
      $page_data['Page Section']='catalogue';
      $page_data['Page Store Source Type'] ='Dynamic';
      $page_data['Page Store Code']=$this->data['Store Code'];
      $page_data['Page Parent Key']=$this->data['Store Key'];
//print_r($page_data);
      $page=new Page('find',$page_data,'create');
//print_r($page);
      $sql=sprintf("update `Store Dimension` set `Store Page Key`=%d  where `Store Key`=%d",$page->id,$this->id);
  //  print $sql;
    mysql_query($sql);  

	}
 
 function get_page_data(){
  $data=array();
  $sql=sprintf("select * from `Page Store Dimension` PSD left join `Page Dimension` PD on (PSD.`Page Key`=PD.`Page Key`) where PSD.`Page Key`=%d",$this->data['Store Page Key']);
  // print $sql;
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    $data=$row;
  }
  
  return $data;

}
 
}
