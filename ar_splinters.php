<?php
require_once 'common.php';

if(!isset($output_type))
  $output_type='ajax';



if (!isset($_REQUEST['tipo'])) {
  if($output_type=='ajax'){
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
  }
  return;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('customers'):
  $results=list_customers();
  break;
  case('products'):
  $results=list_products();
  break;

break;
}



function list_products(){


  global $myconf,$output_type,$user;

  $conf=$_SESSION['state']['home']['splinters']['top_products'];
//print_r($conf);
  $start_from=0;
  
  if(isset( $_REQUEST['nr'])){
     $number_results=$_REQUEST['nr'];
     $_SESSION['state']['home']['splinters']['top_products']['nr']=$number_results;
  }else
     $number_results=$conf['nr'];

  if(isset( $_REQUEST['o'])){
    $order=$_REQUEST['o'];
    $_SESSION['state']['home']['splinters']['top_products']['order']=$order;
  }else
    $order=$conf['order'];
  $order_direction='desc';
   $order_dir='desc';
 
 if(isset( $_REQUEST['period'])){
    $period=$_REQUEST['period'];
    $_SESSION['state']['home']['splinters']['top_products']['period']=$period;
  }else
    $period=$conf['period'];




  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

 
  
      $store=join(',',$user->stores);

  
   
  
   $filter_msg='';
   $wheref='';

     if(!$store)
          $where=sprintf(' and false ');

     else
     $where=sprintf(' and `Product Store Key` in (%s) ',$store);

  
   

   $filtered=0;
   $rtext='';
   $total=$number_results;
   


   $_order=$order;
   $_dir=$order_direction;
  

   if($order=='profits')
     $order='`Product 1 Year Acc Profit`';

   else{   
     switch($period){
case('all'):
 $order='`Product Total Invoiced Amount`';
break;
case('1m'):
 $order='`Product 1 Month Acc Invoiced Amount`';
break;
case('1y'):
$order='`Product 1 Year Acc Invoiced Amount`';
break;
case('1q'):
 $order='`Product 1 Quarter Acc Invoiced Amount`';
break;
 default:
 $order='`Product 1 Year Acc Invoiced Amount`';

}

}
  
   $sql="select  * from `Product Dimension` P  left join `Store Dimension` S on (P.`Product Store Key`=S.`Store Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results"; 
   $adata=array();
   //print $sql;
   $position=1;
  $result=mysql_query($sql);
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){


switch($period){
case('all'):
 $sales=money($data['Product Total Invoiced Amount'],$data['Store Currency Code']);
break;
case('1m'):
 $sales=money($data['Product 1 Month Acc Invoiced Amount'],$data['Store Currency Code']);
break;
case('1y'):
 $sales=money($data['Product 1 Year Acc Invoiced Amount'],$data['Store Currency Code']);
break;
case('1q'):
 $sales=money($data['Product 1 Quarter Acc Invoiced Amount'],$data['Store Currency Code']);
break;
 default:
 $sales=money($data['Product 1 Year Acc Invoiced Amount'],$data['Store Currency Code']);

}

   
    $code="<a href='product.php?pid=".$data['Product ID']."'>".$data['Product Code'].'</a>'; 
    $family="<a href='family.php?id=".$data['Product Family Key']."'>".$data['Product Family Code'].'</a>'; 
    $store="<a href='store.php?id=".$data['Product Store Key']."'>".$data['Store Code'].'</a>'; 

    $adata[]=array(
		   'position'=>'<b>'.$position++.'</b>'
		   ,'code'=>$code
		   ,'family'=>$family
		   ,'store'=>$store
		  ,'description'=>'<b>'.$code.'</b> '.$data['Product Short Description']
,'net_sales'=>$sales
		   );
  }
mysql_free_result($result);




  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,

			 'records_perpage'=>$number_results,
			 'records_order'=>$_order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
  if($output_type=='ajax'){
    echo json_encode($response);
    return;
  }else{
    return $response;
  }

}
function list_customers(){


  global $myconf,$output_type,$user;

  $conf=$_SESSION['state']['home']['splinters']['top_customers'];

  $start_from=0;
  
  if(isset( $_REQUEST['nr'])){
     $number_results=$_REQUEST['nr'];
     $_SESSION['state']['home']['splinters']['top_customers']['nr']=$number_results;
  }else
     $number_results=$conf['nr'];

  if(isset( $_REQUEST['o'])){
    $order=$_REQUEST['o'];
    $_SESSION['state']['home']['splinters']['top_customers']['order']=$order;
  }else
    $order=$conf['order'];
    
    
  $order_direction='desc';
   $order_dir='desc';
 
 if(isset( $_REQUEST['period'])){
    $period=$_REQUEST['period'];
    $_SESSION['state']['home']['splinters']['top_customers']['period']=$period;
  }else
    $period=$conf['period'];


  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
/*
   if(isset( $_REQUEST['store_keys'])    ){
     $store=$_REQUEST['store_keys'];
     $_SESSION['state']['home']['splinters']['top_customers']['store_keys']=$store;
   }else
     $store=$_SESSION['state']['home']['splinters']['top_customers']['store_keys'];

   if($store=='all'){
      $store=join(',',$user->stores);

   }
   */
   $store=$user->stores;
  
  
  
  $store=join(',',$user->stores);

  
   $filter_msg='';
   $wheref='';

     
     $where=sprintf(' where `Customer Store Key` in (%s) and `Customer Orders Invoiced`>0',$store);

  

   $filtered=0;
   $rtext='';
   $total=$number_results;
   


   $_order=$order;
   $_dir=$order_direction;
  

   if($order=='invoices')
     $order='`Invoices`';

   else   
     $order='`Balance`';

  
   $sql="select  `Store Code`,`Customer Type by Activity`,`Customer Last Order Date`,`Customer Main XHTML Telephone`,`Customer Key`,`Customer Name`,`Customer Main Location`,`Customer Main XHTML Email`,`Customer Main Town`,`Customer Main Country First Division`,`Customer Main Delivery Address Postal Code`,`Customer Orders Invoiced` as Invoices , `Customer Net Balance` as Balance  from `Customer Dimension` C  left join `Store Dimension` SD on (C.`Customer Store Key`=SD.`Store Key`)  $where $wheref  group by `Customer Key` order by $order $order_direction limit $start_from,$number_results";
// print $sql;
   $adata=array();
  
  
   $position=1;
  $result=mysql_query($sql);
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){



  


    $id="<a href='customer.php?id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>'; 
    $name="<a href='customer.php?id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>'; 

    $adata[]=array(
		   'position'=>'<b>'.$position++.'</b>',
		   'id'=>$id,
		   'name'=>$name,
		   'store'=>$data['Store Code'],
		   'location'=>$data['Customer Main Location'],
		   //  'orders'=>number($data['Customer Orders']),
		   'invoices'=>$data['Invoices'],
		   'email'=>$data['Customer Main XHTML Email'],
		   'telephone'=>$data['Customer Main XHTML Telephone'],
		   'last_order'=>strftime("%e %b %Y", strtotime($data['Customer Last Order Date'])),
		   // 'total_payments'=>money($data['Customer Net Payments']),
		   'net_balance'=>money($data['Balance']),
		   //'total_refunds'=>money($data['Customer Net Refunds']),
		   //'total_profit'=>money($data['Customer Profit']),
		   //'balance'=>money($data['Customer Outstanding Net Balance']),


		   //'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
		   //'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
		   //'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
		   //'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
		   //'contact_name'=>$data['Customer Main Contact Name'],
		   //'address'=>$data['Customer Main Location'],
		   //'town'=>$data['Customer Main Town'],
		   //'postcode'=>$data['Customer Main Postal Code'],
		   //'region'=>$data['Customer Main Country First Division'],
		   //'country'=>$data['Customer Main Country'],
		   //		   'ship_address'=>$data['customer main ship to header'],
		   //'ship_town'=>$data['Customer Main Delivery Address Town'],
		   //'ship_postcode'>$data['Customer Main Delivery Address Postal Code'],
		   //'ship_region'=>$data['Customer Main Delivery Address Country Region'],
		   //'ship_country'=>$data['Customer Main Delivery Address Country'],
		   'activity'=>$data['Customer Type by Activity']

		   );
  }
mysql_free_result($result);




  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,

			 'records_perpage'=>$number_results,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
  if($output_type=='ajax'){
    echo json_encode($response);
    return;
  }else{
    return $response;
  }

}


?>