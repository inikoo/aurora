<?php
/*
 File: customer_csv.php 

 Customer CSV data for export proprces

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2010, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
if(!$user->can_view('orders')){
  exit();
}

 
      $conf=$_SESSION['state']['orders']['table'];
 
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
 else{
  
     $from=$_SESSION['state']['orders']['from'];
 }

  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else{
 
      $to=$_SESSION['state']['orders']['to'];
  }

   if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
   else{
    
       $view=$_SESSION['state']['orders']['view'];

   }
  
 
  if(isset( $_REQUEST['dispatch']))
    $dispatch=$_REQUEST['dispatch'];
   else{
     $dispatch=$conf['dispatch'];
   }
  if(isset( $_REQUEST['order_type']))
    $order_type=$_REQUEST['order_type'];
   else{
     $order_type=$conf['order_type'];
   }
  if(isset( $_REQUEST['paid']))
    $paid=$_REQUEST['paid'];
   else{
     $paid=$conf['paid'];
   }




  


     if(isset( $_REQUEST['store_id'])    ){
       $store=$_REQUEST['store_id'];
       $_SESSION['state']['orders']['store']=$store;
     }else
       $store=$_SESSION['state']['orders']['store'];
     
     
  
 
 $date_interval=prepare_mysql_dates($from,$to,'`Order Date`','only_dates');
     if($date_interval['error']){
       $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
     }else{
       $_SESSION['state']['orders']['from']=$date_interval['from'];
       $_SESSION['state']['orders']['to']=$date_interval['to'];
 }



 if(is_numeric($store)){
     $where.=sprintf(' and `Order Store Key`=%d ',$store);
   }

   $where.=$date_interval['mysql'];
   

   if($dispatch!=''){
     $dipatch_types=preg_split('/,/',$dispatch);
     $valid_dispatch_types=array(
				 'in_process'=>",'In Process','Ready to Pick','Picking','Ready to Pack','Ready to Ship','Packing'"
				 ,'cancelled'=>",'Cancelled'"
				 ,'dispatched'=>",'Dispatched'"
				 ,'unknown'=>"',Unknown'"
				 );
     $_where='';
     foreach($dipatch_types as $dipatch_type){
       if(array_key_exists($dipatch_type,$valid_dispatch_types))
        $_where.=$valid_dispatch_types[$dipatch_type];
     }
     $_where=preg_replace('/^,/','',$_where);
     if($_where!=''){
       $where.=' and `Order Current Dispatch State` in ('.$_where.')';
     }else
       $_SESSION['state']['orders']['table']['dispatched']='';
   }


   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Order Last Updated Date`))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Order Last Updated Date`))>=".$f_value."    ";
   elseif($f_field=='customer_name' and $f_value!='')
    $wheref.=" and  `Order Customer Name` like '".addslashes($f_value)."%'";
   elseif($f_field=='public_id' and $f_value!='')
    $wheref.=" and  `Order Public ID` like '".addslashes($f_value)."%'";


  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  `Order Total Amount`<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  `Order Total Amount`>=".$f_value."    ";
   


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"orders.csv\"");
//$out = fopen('php://output', 'w');
$csv='';
$sql="select `Order Total Net Amount`,`Order Total Tax Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,`Order Key`,`Order Public ID`,`Order Customer Key`,`Order Customer Name`,`Order Last Updated Date`,`Order Date`,`Order Total Amount` ,`Order Current XHTML Payment State` from `Order Dimension`  $where $wheref   ";
  //  print $sql;
  global $myconf;

   $data=array();
   $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $order_id=sprintf('<a href="order.php?id=%d">%s</a>',$row['Order Key'],$row['Order Public ID']);
     $customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Order Customer Key'],$row['Order Customer Name']);
     $state=$row['Order Current XHTML Payment State'];
     if($row ['Order Type'] != 'Order')
       $state.=' ('.$row ['Order Type'].')';
     $data=array(
		   'id'=>$row['Order Public ID'],
		   'customer'=>$row['Order Customer Name'],
		   'date'=>strftime("%d/%m/%Y %H:%M", strtotime($row['Order Date'])),
		   'state'=>$state,
		   'order_type'=>$row['Order Type'],
		   'total_net'=>money($row['Order Total Net Amount']),
		   'total_tax'=>money($row['Order Total Tax Amount']),
		   'total_amount'=>money($row['Order Total Amount'])

		   );
	$_csv='';
	foreach($data as $key=>$value){
$_csv.="\t".$value;
}

$csv.=preg_replace('/^\t/','',$_csv)."\n";
		  // fputcsv($out, $data);
   }
mysql_free_result($res);


print chr(255).chr(254).mb_convert_encoding( $csv, 'UTF-16LE', 'UTF-8'); 






//fclose($out);






?>