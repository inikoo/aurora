<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Customer.php');


require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../myconf/conf.php';

require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  

require_once '../../myconf/conf.php';           
date_default_timezone_set('UTC');


$sql="select date_index,tipo,customer_id,id,date_creation,date_processed,date_invoiced,date_dispatched from orden ";
//print $sql;
$res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $id=$row['id'];
  $customer_id=$row['customer_id'];
  
  $date_creation=$row['date_creation'];
  $date_processed=$row['date_processed'];
  $date_invoiced=$row['date_invoiced'];
  $date_dispatched=$row['date_dispatched'];
  $date_index=$row['date_index'];
  $tipo=$row['tipo'];

  $customer=new Customer($customer_id);
  if($customer->id){
    
    if($tipo<=3){
      if($date_creation!='' and $date_creation!=$date_processed){
	$data=array(
		    'action'=>'creation',
		    'date'=>$date_creation,
		    'order_id'=>$id,
		    'display'=>'details'
		  );
      $customer->save_history('order','','',$data);
    }
    
    if($date_processed!=''){
      if($tipo==2 or $tipo==3)
	$details='details';
      else
	$details='normal';
      $data=array(
		  'action'=>'processed',
		  'date'=>$date_processed,
		  'order_id'=>$id,
		  'display'=>$details
		  );
      // print_r($data);
       $customer->save_history('order','','',$data);
       //print $customer->msg;
       // exit;
    }

    if($date_invoiced!=''){
      $data=array(
		  'action'=>'invoiced',
		  'date'=>$date_invoiced,
		  'order_id'=>$id
		  );
       $customer->save_history('order','','',$data);
    }
    
    if($tipo==3){
      $data=array(
		  'action'=>'cancelled',
		  'date'=>$date_index,
		  'order_id'=>$id
		  );
       $customer->save_history('order','','',$data);
      
    }
    }

    if($tipo==4){
      $data=array(
		  'action'=>'sample',
		  'date'=>$date_index,
		  'order_id'=>$id
		  );
       $customer->save_history('order','','',$data);

    }

    if($tipo==4){
      $data=array(
		  'action'=>'sample',
		  'date'=>$date_index,
		  'order_id'=>$id
		  );
       $customer->save_history('order','','',$data);

    }
   if($tipo==5){
      $data=array(
		  'action'=>'donation',
		  'date'=>$date_index,
		  'order_id'=>$id
		  );
       $customer->save_history('order','','',$data);
    }
    if($tipo==6){
      $data=array(
		  'action'=>'replacement',
		  'date'=>$date_index,
		  'order_id'=>$id
		  );
       $customer->save_history('order','','',$data);
    }
    if($tipo==7){
      $data=array(
		  'action'=>'shortages',
		  'date'=>$date_index,
		  'order_id'=>$id
		  );
       $customer->save_history('order','','',$data);
    }
    if($tipo==8){
      $data=array(
		  'action'=>'followup',
		  'date'=>$date_index,
		  'order_id'=>$id
		  );
       $customer->save_history('order','','',$data);
    }



  }else{
    print "Error customer not found customer_id:$customer_id order id:$id";
  }

  
 }

?>