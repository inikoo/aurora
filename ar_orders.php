<?php
/*
 File: ar_orders.php 

 Ajax Server Anchor for the Order Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyrigh (c) 2009, Kaktus 
 
 Version 2.0
*/
require_once 'common.php';
require_once 'class.Order.php';
require_once 'class.Invoice.php';

//require_once 'ar_common.php';




if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){
case('transactions_in_dn'):
list_transactions_in_dn();
break;
case('transactions_to_pick'):
list_transactions_to_pick();
break;
case('transactions_in_warehouse'):
list_transactions_in_warehouse();
break;
 case('create_po'):
   $po=new Order('po',array('supplier_id'=>$_SESSION['state']['supplier']['id']));
   if(is_numeric($po->id)){
     $response= array('state'=>200,'id'=>$po->id);

   }else
     $response= array('state'=>400,'id'=>_("Error: Purchase order could 't be created"));
     echo json_encode($response);  
   break;
 case('plot_month_outofstock_money'):
 case('plot_month_outofstock'):

   if(isset($_REQUEST['from']))
     $from=$_REQUEST['from'];
   else
     $from=date("d-m-Y",strtotime('-1 year') );
   if(isset($_REQUEST['to']))
     $to=$_REQUEST['to'];
   else
     $to=date("d-m-Y",strtotime('now') );
   $_from=$from;
   $_to=$to;

   $int=prepare_mysql_dates($_from,$_to,'date_index','date only,complete months');
   // make the structure of the months
   $data=date_base($_from,$_to,'m','complete months');
   if($tipo=='plot_month_outofstock'){

    $sql=sprintf("select count(DISTINCT  product_id) as products_total ,sum(dispatched) as dispatched, substring(date_index, 1,7) AS dd from transaction left join orden on (order_id=orden.id) where partner=0  %s group by dd;",$int[0]);

    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $data_all[$row['dd']]=array('d_products'=>$row['products_total'],'picks'=>$row['dispatched']);
      
    }
   }
    $sql=sprintf("select count(DISTINCT  product_id) as products,sum(qty) as qty, substring(date_index, 1,7) AS dd,sum(qty*price) as e_cost from outofstock left join orden on (order_id=orden.id) left join product on (product_id=product.id) where  partner=0  %s  group by dd   ",$int[0]);
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $data_outstock[$row['dd']]=array('d_products'=>$row['products'],'picks'=>$row['qty'],'e_cost'=>$row['e_cost']);
    }
    
    foreach($data as $key=>$value){
      $total_products=0;
      $outstock_products=0;
      $total_picks=0;
      $outstock_picks=0;
      $e_cost=0;
      if(isset($data_all[$key])){
	 $total_products=$data_all[$key]['d_products'];
	 $total_picks=$data_all[$key]['picks'];
      }
      if(isset($data_outstock[$key])){
	$outstock_products=$data_outstock[$key]['d_products'];
	$outstock_picks=$data_outstock[$key]['picks'];
	$e_cost=$data_outstock[$key]['e_cost'];
      }

      $per_prods=percentage($outstock_products,$total_products,2,'0','' );
      $per_picks=percentage($outstock_picks,$total_picks,2,'0','' );

      $_data[]=array(
		     'per_product_outstock'=>(float) $per_prods,
			'per_picks_outstock'=>(float) $per_picks,
			'e_cost'=>money($e_cost),
			'date'=>$key,
			'tip_per_product_outstock'=>_('Out of Stock Products')."\n".$per_prods.'% ('.number($outstock_products).' '._('of').' '.number($total_products).')',
			'tip_per_picks_outstock'=>_('Out of Stock Picks')."\n".$per_picks."%\n(".number($outstock_picks).' '._('of').' '.number($total_picks).")\n"._('Estimated Value')."\n@"._('Current Sale Price')."\n".money($e_cost)

		     );
    }
$response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$_data,
			 )
		   );

   echo json_encode($response);
   
   
   break;





case('changesalesplot'):
   if(isset($_REQUEST['value'])){
     $value=$_REQUEST['value'];
     $_SESSION['views']['sales_plot']="$value";

   }
 case('proinvoice'):
   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$_SESSION['tables']['proinvoice_list'][3];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$_SESSION['tables']['proinvoice_list'][2];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['proinvoice_list'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['proinvoice_list'][1];
   
   
   


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   


   

    if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$_SESSION['tables']['proinvoice_list'][4];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$_SESSION['tables']['proinvoice_list'][5];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$_SESSION['tables']['proinvoice_list'][6];



  $_SESSION['tables']['proinvoice_list']=array($order,$order_direction,$number_results,$start_from,$where,$f_field,$f_value);

  $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
  elseif(($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


   

   
   $sql="select count(*) as total from orden   $where $wheref ";
   // print "$sql";
   $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
  }else{
    
      $sql="select count(*) as total from orden  $where";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$filtered=$row['total']-$total;
      }
      
  }
  
  

  $sql="select UNIX_TIMESTAMP(date_index) as date_index ,public_id,customer_name,id,customer_id,total,titulo,tipo,TO_DAYS(NOW())-TO_DAYS(date_index) as desde from orden  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  //  print $sql;
   $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $data[]=array(
		   'id'=>$row['id'],
		   'public_id'=>$row['public_id'],
		   'customer_name'=>$row['customer_name'],
		   'customer_id'=>$row['customer_id'],
		   //		   'date_index'=>$row['date_index'],
		   'date_index'=> strftime("%A %e %B %Y", strtotime('@'.$row['date_index'])),
		   'total'=>money($row['total']),
		   'titulo'=>$_order_tipo[$row['tipo']],
		   'tipo'=>$row['tipo'],
		   'desde'=>$row['desde']
		   //		   'file'=>$row['original_file']
		   );
   }

   
   if($total==0){
     $rtext=_('No orders are outstanding').'.';
   }else if($total<$number_results){
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   }else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;
 case('report_orders'):
   $_REQUEST['saveto']='report_sales';
 case('orders'):
   if(!$user->can_view('orders'))
     exit();
list_orders();

 
   break;


case('report_invoices'):
  $_REQUEST['saveto']='report_sales';

 case('invoices'):
   if(!$user->can_view('orders'))
  exit();
    
    list_invoices();
   
   break;
 
 case('dn'):
if(!$user->can_view('orders'))
  exit();
    
    list_delivery_notes();
    
 
   break;
 case('po_supplier'):
   
   if(!$user->can_view('purchase orders'))
     exit();
     
     list_purchase_orders_of_supplier();
     

   break;


 

 case('outofstock'):
   
   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['order_id'];

   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['transaction_list'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['transaction_list'][1];
   
   
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $_SESSION['tables']['transaction_list']=array($order,$order_direction);
   
   $where=' where order_id='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   
   $sql="select * from outofstock left join product on (product.id=product_id)  $where order by code ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   //      print $sql;
   $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     



     $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>$row['product_id']
		   ,'code'=>$row['code']
		   ,'description'=>number($row['units']).'x '.$row['description']
		   //'ordered'=>$row['ordered'],
		   ,'qty'=>number($row['qty'],2)


		   );
   }


    

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data

			 )
		   );
   echo json_encode($response);
   break;
   case('transactions_cancelled'):
   transactions_cancelled();
   break;
 case('transactions_to_process'):
   
   if(isset( $_REQUEST['show_all']) and preg_match('/^(yes|no)$/',$_REQUEST['show_all'])  ){
    
     if($_REQUEST['show_all']=='yes')
       $show_all=true;
     else
       $show_all=false;
     $_SESSION['state']['order']['show_all']=$show_all;
   }else
     $show_all=$_SESSION['state']['order']['show_all'];
  
   if($show_all)
     products_to_sell();
   else
     transactions_to_process();
  
   break;
 case('transactions_invoice'):
   list_transactions_in_invoice();
   break;
   case('transactions_refund'):
   list_transactions_in_refund();
   break;
 case('transactions'):
   list_transactions();
   break;
 case('withproduct'):
      $can_see_customers=$user->can_view('customers');
 list_orders_with_product( $can_see_customers);

   break;
 case('withcustomerproduct'):
   
     list_customers_who_order_product();
   break;
 case('withcustomer'):
 list_orders_with_customer();
 break;
 default:
   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }


function list_orders(){
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $conf=$_SESSION['state']['report']['sales'];
    else
      $conf=$_SESSION['state']['orders']['table'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
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
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
     $from=$conf['from'];
   else
     $from=$_SESSION['state']['orders']['from'];
 }

  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else{
    if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $to=$conf['to'];
    else
      $to=$_SESSION['state']['orders']['to'];
  }

   if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
   else{
     if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
       $view=$conf['view'];
     else
       $view=$_SESSION['state']['orders']['view'];

   }
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

 
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




   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

        
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales'){
     
     $_SESSION['state']['report']['sales']['order']=$order;
     $_SESSION['state']['report']['sales']['order_dir']=$order_direction;
     $_SESSION['state']['report']['sales']['nr']=$number_results;
     $_SESSION['state']['report']['sales']['sf']=$start_from;
     $_SESSION['state']['report']['sales']['where']=$where;
     $_SESSION['state']['report']['sales']['f_field']=$f_field;
     $_SESSION['state']['report']['sales']['f_value']=$f_value;
     $_SESSION['state']['report']['sales']['to']=$to;
     $_SESSION['state']['report']['sales']['from']=$from;
     $date_interval=prepare_mysql_dates($from,$to,'`Order Date`','only_dates');
     
   }else{


     if(isset( $_REQUEST['store_id'])    ){
       $store=$_REQUEST['store_id'];
       $_SESSION['state']['orders']['store']=$store;
     }else
       $store=$_SESSION['state']['orders']['store'];
     
     
     $_SESSION['state']['orders']['table']=array(
						 'order'=>$order,
						 'order_dir'=>$order_direction,
						 'nr'=>$number_results,
						 'sf'=>$start_from,
						 'where'=>$where,
						 'f_field'=>$f_field,
						 'f_value'=>$f_value,
						 'dispatch'=>$dispatch,
						 'paid'=>$paid,
						 'order_type'=>$order_type

						 );
     $_SESSION['state']['orders']['view']=$view;
     $date_interval=prepare_mysql_dates($from,$to,'`Order Date`','only_dates');
     if($date_interval['error']){
       $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
     }else{
       $_SESSION['state']['orders']['from']=$date_interval['from'];
       $_SESSION['state']['orders']['to']=$date_interval['to'];
     }
   }


 if(is_numeric($store)){
     $where.=sprintf(' and `Order Store Key`=%d ',$store);
   }

   $where.=$date_interval['mysql'];
   

   
     $dipatch_types=preg_split('/,/',$dispatch);
     if(!array_key_exists('all_orders',$dipatch_types)){
      $valid_dispatch_types=array(
				 'in_process'=>",'Submited','In Process','Ready to Pick','Picking','Ready to Pack','Ready to Ship','Packing'"
				 ,'cancelled'=>",'Cancelled'"
				 ,'dispatched'=>",'Dispatched'"
				  ,'suspended'=>",'Suspended'"
				 ,'unknown'=>"'Unknown'"
				 );
     $_where='';
     foreach($dipatch_types as $dipatch_type){
       if(array_key_exists($dipatch_type,$valid_dispatch_types))
        $_where.=$valid_dispatch_types[$dipatch_type];
     }
     $_where=preg_replace('/^,/','',$_where);
     if($_where!=''){
       $where.=' and `Order Current Dispatch State` in ('.$_where.')';
     }else{
        $_SESSION['state']['orders']['table']['dispatch']='all_orders';
     }
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
   


   

   
  $sql="select count(*) as total from `Order Dimension`   $where $wheref ";
  //print $sql ;
   $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Order Dimension`  $where";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$total_records=$row['total'];
	$filtered=$total_records-$total;
      }
      
  }
  mysql_free_result($result);

  $rtext=$total_records." ".ngettext('order','orders',$total_records);

  if($total_records>$number_results)
    $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
  else
    $rtext_rpp=sprintf("Showing all orders");

  $filter_msg='';

     switch($f_field){
     case('public_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
       break;
     case('customer_name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with customer')." <b>".$f_value."*</b>)";
       break;  
     case('minvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
       break;  
   case('maxvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>)";
       break;  
 case('max'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
       break;  
     }



   
   $_order=$order;
   $_dir=$order_direction;

   
   if($order=='date')
     $order='`Order Date`';
   else if($order=='last_date')
     $order='`Order Last Updated Date`';
   else if($order=='id')
     $order='`Order File As`';
   else if($order=='state'){
  // if($order_direction=='desc')
//     $order='`Order Current Dispatch State`,`Order Current Payment State`';
//else
//$order='`Order Current Payment State`,`Order Current Dispatch State`';
$order='`Order Current XHTML State`';
}
   else if($order=='total_amount')
     $order='`Order Balance Total Amount`';
else if($order=='customer')
     $order='`Order Customer Name`';

  $sql="select `Order Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,`Order Key`,`Order Public ID`,`Order Customer Key`,`Order Customer Name`,`Order Last Updated Date`,`Order Date`,`Order Total Amount` ,`Order Current XHTML State` from `Order Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  //  print $sql;
  global $myconf;

   $data=array();

   $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $order_id=sprintf('<a href="order.php?id=%d">%s</a>',$row['Order Key'],$row['Order Public ID']);
     $customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Order Customer Key'],$row['Order Customer Name']);
     $state=$row['Order Current XHTML State'];
     if($row ['Order Type'] != 'Order')
       $state.=' ('.$row ['Order Type'].')';
       
       if($row['Order Balance Total Amount']!=$row['Order Total Amount']){
       $mark='<span style="color:red">*</span>';
       }else{
       $mark='<span style="visibility:hidden">*</span>';
       }
       
       
     $data[]=array(
		   'id'=>$order_id,
		   'customer'=>$customer,
		   'date'=>strftime("%e %b %y %H:%M", strtotime($row['Order Date'])),
		   'last_date'=>strftime("%e %b %y %H:%M", strtotime($row['Order Last Updated Date'])),
		   'total_amount'=>money($row['Order Balance Total Amount'],$row['Order Currency']).$mark,
		   'state'=>$state
		   );
   }
mysql_free_result($res);
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,

			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_transactions(){
  if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];

 //   if(isset( $_REQUEST['o']))
//      $order=$_REQUEST['o'];
//    else
//      $order=$_SESSION['tables']['transaction_list'][0];
//    if(isset( $_REQUEST['od']))
//      $order_dir=$_REQUEST['od'];
//    else
//      $order_dir=$_SESSION['tables']['transaction_list'][1];
   
   
//    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

//    $_SESSION['tables']['transaction_list']=array($order,$order_direction);
   
   $where=' where order_id='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   
   $sql="select weight, p.price as price,concat(100000+p.group_id,p.ncode) as display_order,p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where  and dispatched> 0 and (charge!=0 or discount!=1)    ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   //     print $sql;
   $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $discount='';
     $ndiscount=0;
     $cost='';
     $ncost=0;
     if($row['charge']==0 or $row['discount']==1)
       $outer_price=$row['price'];
     else{
       $outer_price=$row['charge']/((1-$row['discount'])*$row['dispatched'] );
       $ncost=$outer_price;///$row['dispatched'];
       //       $$row['dispatched']/$row['o']

       $cost= money($ncost);
       if($row['discount']>0){
	 $ndiscount=$row['discount']* $row['charge'] ;
	 $discount='('.number(100*$row['discount'],0).'%) '.money(  $ndiscount);
       }
     }


     



     $total_charged+=$row['charge'];
     $total_discounts+=$ndiscount;
     $total_picks+=$row['dispatched'];
     $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>$row['product_id']
		   ,'code'=>$row['code']
		   ,'description'=>number($row['units']).'x '.$row['description'].' @ '.$cost
		   ,  'cost'=>$cost
		   //'ordered'=>$row['ordered'],
		   ,'dispatched'=>number($row['dispatched'],2)
		   ,'charge'=>money($row['charge'])
		   ,'discount'=>$discount

		   );
   }
   mysql_free_result($result);




   // todo transactions
 $sql="select * from todo_transaction  $where and (bonus=0 and  discount!=1)";

   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
 // print $sql;
   $result=mysql_query($sql);

   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){

     $charged=($row['ordered']-$row['reorder']) * $row['price']*(1-$row['discount']);
     $total_charged+=$charged;
     $total_discounts+=$ndiscount;
     $pick=number($row['ordered']-$row['reorder']);
     $total_picks+=$pick;

     $discount='';
     $ndiscount=0;
     $cost='';
     $ncost=0;
     if($charged==0 or $row['discount']==1)
       $outer_price=$row['price'];
     else{
       $outer_price=$charged/((1-$row['discount'])*$pick );
       $ncost=$outer_price*$pick;
       $cost= money($ncost);
       if($row['discount']>0){
	 $ndiscount=$row['discount']* $charged;
	 $discount='('.number(100*$row['discount'],0).'%'._('off').') '.$myconf['currency_symbol'].money($ndiscount);
       }
     }











       $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>-1
		   ,'code'=>$row['code']
		   ,'description'=>$row['description'].' @ '.money($row['price'])
		   ,  'cost'=>money($row['price'] )
		   //'ordered'=>$row['ordered'],
		   ,'dispatched'=>number($pick)
		   ,'charge'=>money($charged)
		   ,'discount'=>$discount
		   //'promotion_id'=>$row['promotion_id']
		   );

   }

   mysql_free_result($result);

   // todo transactions
 $sql="select * from todo_transaction  $where and (bonus!=0 or  discount=1)";

   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
 //  print $sql;
   $result=mysql_query($sql);

   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     
     $pick=$row['bonus']+$row['ordered']-$row['reorder'];

     $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>-1
		   ,'code'=>$row['code']
		   ,'description'=>$row['description'].' ('._('Free Bonus').')'
		   ,  'cost'=>''
		   //'ordered'=>$row['ordered'],
		   ,'dispatched'=>number($pick)
		   ,'charge'=>''
		   ,'discount'=>''
		   //'promotion_id'=>$row['promotion_id']
		   );

   }










  $sql="select promotion,bonus.id as id,product_id,p.units as units,concat(100000+p.group_id,p.ncode) as display_order,p.code as code,p.description as description, qty from bonus   left join product as p on (product_id=p.id) $where";

   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
  //  print $sql;
   $result=mysql_query($sql);

   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){

     $tipo_discount=_('Free Bonus');
     if($row['promotion'])
       $tipo_discount=_('First Order Bonus');
     $data[]=array(
		   'id'=>$row['id']
		   ,'product_id'=>$row['product_id']
		   ,'code'=>$row['code']
		   ,'description'=>number($row['units']).'x '.$row['description'].' ('.$tipo_discount.')'
		   ,  'cost'=>''
		   //'ordered'=>$row['ordered'],
		   ,'dispatched'=>number($row['qty'])
		   ,'charge'=>''
		   ,'discount'=>''
		   //'promotion_id'=>$row['promotion_id']
		   );

   }
   mysql_free_result($result);
   $data[]=array(
		 'id'=>0
		 ,'product_id'=>''
		 ,'code'=>_('Subtotal')
		 ,'description'=>''
		 ,  'cost'=>''
		 //'ordered'=>$row['ordered'],
		 ,'dispatched'=>number($total_picks)
		 ,'charge'=>money($total_charged)
		 ,'discount'=>money($total_discounts)
		 //'promotion_id'=>$row['promotion_id']
		 );
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_transactions_in_invoice() {

    if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
        $order_id=$_REQUEST['id'];
    else
        $order_id=$_SESSION['state']['invoice']['id'];




    $where=' where `Invoice Quantity`!=0 and  `Invoice Key`='.$order_id;

    $total_charged=0;
    $total_discounts=0;
    $total_picks=0;

    $data=array();
    $sql="select * from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) $where   ";

    //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
    //   print $sql;
    $result=mysql_query($sql);
    $total_gross=0;
    $total_discount=0;
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        //   $total_charged+=$row['charge'];
//      $total_discounts+=$ndiscount;
//      $total_picks+=$row['dispatched'];
        $total_discount+=$row['Invoice Transaction Total Discount Amount'];
        $total_gross+=$row['Invoice Transaction Gross Amount'];
        $code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);

        if ($row['Invoice Transaction Total Discount Amount']==0)
            $discount='';
        else
            $discount=money($row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code']);

        $data[]=array(

                    'code'=>$code
                           ,'description'=>$row['Product XHTML Short Description']
                                          ,'tariff_code'=>$row['Product Tariff Code']
                                                         ,'quantity'=>number($row['Invoice Quantity'])
                                                                     ,'gross'=>money($row['Invoice Transaction Gross Amount'],$row['Invoice Currency Code'])
                                                                              ,'discount'=>$discount
                                                                                          ,'to_charge'=>money($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code'])
                );
    }

    $invoice=new Invoice($order_id);

    if ($invoice->data['Invoice Shipping Net Amount']!=0) {

        $data[]=array(

                    'code'=>''
                           ,'description'=>_('Shipping')
                                          ,'tariff_code'=>''
                                                         ,'quantity'=>''
                                                                     ,'gross'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency'])
                                                                              ,'discount'=>''
                                                                                          ,'to_charge'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency'])
                );

    }
    if ($invoice->data['Invoice Charges Net Amount']!=0) {
        $data[]=array(

                    'code'=>''
                           ,'description'=>_('Charges')
                                          ,'tariff_code'=>''
                                                         ,'quantity'=>''
                                                                     ,'gross'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency'])
                                                                              ,'discount'=>''
                                                                                          ,'to_charge'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency'])
                );
    }
    if ($invoice->data['Invoice Total Tax Amount']!=0) {
        $data[]=array(

                    'code'=>''
                           ,'description'=>_('Tax')
                                          ,'tariff_code'=>''
                                                         ,'quantity'=>''
                                                                     ,'gross'=>money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency'])
                                                                              ,'discount'=>''
                                                                                          ,'to_charge'=>money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency'])
                );
    }

    $data[]=array(

                'code'=>''
                       ,'description'=>_('Total')
                                      ,'tariff_code'=>''
                                                     ,'quantity'=>''
                                                                 ,'gross'=>''
                                                                          ,'discount'=>''
                                                                                      ,'to_charge'=>'<b>'.money($invoice->data['Invoice Total Amount'],$invoice->data['Invoice Currency']).'</b>'
            );

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}
function list_transactions_in_refund() {

    if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
        $order_id=$_REQUEST['id'];
    } else {
        $order_id=$_SESSION['state']['invoice']['id'];
    }
    $where=' where   `Refund Key`='.$order_id;
    $total_charged=0;
    $total_discounts=0;
    $total_picks=0;

    $data=array();
    $sql="select `Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Item Tax Amount`,`Invoice Quantity`,`Invoice Transaction Tax Refund Amount`,`Invoice Currency Code`,`Invoice Transaction Net Refund Amount`,`Product XHTML Short Description`,P.`Product ID`,`Product Code` from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) $where   ";
    $result=mysql_query($sql);
   
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
        $data[]=array(
                    'code'=>$code,
                    'description'=>$row['Product XHTML Short Description'],
                    'charged'=>$row['Invoice Quantity'].'/'.money($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code']).'('.money($row['Invoice Transaction Item Tax Amount'],$row['Invoice Currency Code']).')',
                    'refund_net'=>money($row['Invoice Transaction Net Refund Amount'],$row['Invoice Currency Code']),
                    'refund_tax'=>money($row['Invoice Transaction Tax Refund Amount'],$row['Invoice Currency Code'])
                );
    }
    $sql="select * from `Order No Product Transaction Fact`    $where   ";
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[]=array(
                    'code'=>'',
                    'description'=>$row['Transaction Description'],
                    'refund_net'=>money($row['Transaction Invoice Net Amount'],$row['Currency Code']),
                    'refund_tax'=>money($row['Transaction Invoice Tax Amount'],$row['Currency Code'])

                );
    }


    $invoice=new Invoice($order_id);

    if ($invoice->data['Invoice Shipping Net Amount']!=0) {

        $data[]=array(
                    'code'=>'',
                    'description'=>_('Shipping'),
                    'refund_net'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency'])
                );

    }
    if ($invoice->data['Invoice Charges Net Amount']!=0) {
        $data[]=array(
                    'code'=>'',
                    'gross'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency']),
                    'refund_net'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency'])
                );
    }

    $data[]=array(
                'code'=>'',
                'description'=>_('Total'),
                'refund_net'=>'<b>'.money($invoice->data['Invoice Total Net Amount'],$invoice->data['Invoice Currency']).'</b>',
                'refund_tax'=>'<b>'.money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency']).'</b>'

           );

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data
                                     )
                   );
    echo json_encode($response);
}

function list_transactions_in_dn(){
   
   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['dn']['id'];
   



   $where=' where   `Delivery Note Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   $sql="select `Delivery Note Quantity`,`Product Tariff Code`,`Product Code`, PH.`Product ID` ,`Product XHTML Short Description` from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) $where   ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   //   print $sql;
   $result=mysql_query($sql);
   $total_gross=0;
   $total_discount=0;
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
 
    
    $data[]=array(

		   'code'=>sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code'])
		   ,'description'=>$row['Product XHTML Short Description']
		   ,'tariff_code'=>$row['Product Tariff Code']
		   ,'quantity'=>number($row['Delivery Note Quantity'])
		   
		   );
   }



   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}


function list_customers_who_order_product(){
 $conf=$_SESSION['state']['product']['customers'];

   if(isset( $_REQUEST['code'])){
     $tag=$_REQUEST['code'];
     $mode='code';
   }else if(isset( $_REQUEST['id'])){
     $tag=$_REQUEST['id'];
     $mode='id';
   }else if(isset( $_REQUEST['key'])){
     $tag=$_REQUEST['key'];
     $mode='key';
   }else{
     $tag=$_SESSION['state']['product']['tag'];
     $mode=$_SESSION['state']['product']['mode'];
   }

   if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
    $order=$conf['order'];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
   
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['product']['custumers']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'tag'=>$tag,'mode'=>$mode);
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';

   $table='`Order Transaction Fact` OTF left join `Product History Dimension`  PD on (PD.`Product Key`=OTF.`Product Key`)  ';

   if($mode=='code'){
     $where=$where.sprintf(" and P.`Product Code`=%s ",prepare_mysql($tag));
     $table='`Order Transaction Fact` OTF left join `Product History Dimension` PD  on (PD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)  ';

   }elseif($mode=='pid')
     $where=$where.sprintf(" and PD.`Product ID`=%d ",$tag);
   elseif($mode=='key')
     $where=$where.sprintf(" and PD.`Product Key`=%d ",$tag);


   $wheref="";
   
  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
  elseif($f_field=='customer_name'  and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


   $sql="select count(distinct `Customer Key`) as total from  $table  $where $wheref";
   // print $mode.' '.$sql;
   $res = mysql_query($sql);
   if($row=mysql_fetch_array($res)) {
       $total=$row['total'];
   }
    mysql_free_result($res);
   if($wheref==''){
     $filtered=0;
      $total_records=$total;
   }else{
     $sql="select count(distinct `Customer Key`) as total from  $table  $where      ";

     $res = mysql_query($sql);
     if($row=mysql_fetch_array($res)) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
     }
      mysql_free_result($res);
   }
   $rtext=$total_records." ".ngettext('customer','customers',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
   

   $filter_msg='';
   if($total==0 and $filtered>0){
     switch($f_field){
     case('public_id'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order starting with")." <b>$f_value</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
     case('public_id'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only orders starting with')." <b>$f_value</b>";
       break;
     }
   }

   
   $_order=$order;
   $_dir=$order_direction;

  if($order=='dispatched')
         $order='dispatched';
 elseif($order=='orders')
         $order='orders';
          elseif($order=='charged')
         $order='charged';
          elseif($order=='to_dispatch')
         $order='to_dispatch';
          elseif($order=='dispatched')
         $order='dispatched';
          elseif($order=='nodispatched')
         $order='nodispatched';
  else
     $order='`Customer Name`';


   $sql=sprintf("select   CD.`Customer Key` as customer_id,`Customer Name`,`Customer Main Location`,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`) as charged ,count(distinct `Order Key`) as orders ,sum(`Shipped Quantity`) as dispatched,sum(`Current Manufacturing Quantity`+`Current On Shelf Quantity`+`Current On Box Quantity`) as to_dispatch,sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`) as nodispatched from     `Order Transaction Fact` OTF left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)  left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)       left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)     $where $wheref  group by CD.`Customer Key`    order by $order $order_direction  limit $start_from,$number_results "
		);

   $data=array();
   
  $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


     $data[]=array(
		   'customer'=>sprintf('<a href="customer.php?id=%d"><b>%s</b></a>, %s',$row['customer_id'],$row['Customer Name'],$row['Customer Main Location']),
		   'charged'=>money($row['charged']),
		   'orders'=>number($row['orders']),
		   'to_dispatch'=>number($row['to_dispatch']),
		   'dispatched'=>number($row['dispatched']),
		   'nodispatched'=>number($row['nodispatched'])

		   );
   }
   mysql_free_result($res);

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'sort_key'=>$_order,
			 'rtext'=>$rtext,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_orders_with_customer(){

 
 if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$_SESSION['tables']['order_withcust'][3];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$_SESSION['tables']['order_withcust'][2];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['order_withcust'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['order_withcust'][1];
   

   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $customer_id=$_REQUEST['id'];
   else
     $customer_id=$_SESSION['tables']['order_withcust'][4];


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $_SESSION['tables']['order_withcust']=array($order,$order_direction,$number_results,$start_from,$customer_id);

   $where=sprintf(" where customer_id=%d ",$customer_id);
   $wheref="";
   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
     if($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer'){
       if($_REQUEST['f_value']!='')
	 $wheref=" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
     }
   }
   
  



   
   $sql="select count(*) as total from orden    $where $wheref";
   
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
   mysql_free_result($result);
   if($wheref==''){
     $filtered=0;
   }else{
     $sql="select count(*) as total from orden $where      ";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $filtered=$row['total']-$total;
     }
      mysql_free_result($result);
   }
   

   $sql=sprintf("select tipo,id,public_id,total ,UNIX_TIMESTAMP(date_index) as date_index from orden  $where $wheref     order by $order $order_direction  limit $start_from,$number_results "
		);

   //print "$sql\n";
      $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $data[]=array(
		   'id'=>$row['id'],
		   'public_id'=>$row['public_id'],
		   'date_index'=>$row['date_index'],
		   'date'=> strftime("%A %e %B %Y %H:%I", strtotime('@'.$row['date_index'])),
		   'total'=>money($row['total']),
		   // 'undispatched'=>number($row['undispatched']),
		   'tipo'=>$_order_tipo[$row['tipo']]
		   );
   }
   mysql_free_result($result);
   if($total<$number_results)
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_orders_with_product($can_see_customers=false){

   $conf=$_SESSION['state']['product']['orders'];
if(isset( $_REQUEST['code'])){
     $tag=$_REQUEST['code'];
     $mode='code';
   }else if(isset( $_REQUEST['id'])){
     $tag=$_REQUEST['id'];
     $mode='id';
   }else if(isset( $_REQUEST['key'])){
     $tag=$_REQUEST['key'];
     $mode='key';
   }else{
     $tag=$_SESSION['state']['product']['tag'];
     $mode=$_SESSION['state']['product']['mode'];
   }



if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];

if(!is_numeric($start_from))
  $start_from=0;

   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
    $order=$conf['order'];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$conf['order_dir'];
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
   
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;



   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $_SESSION['state']['product']['orders']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'tag'=>$tag,'mode'=>$mode);
   $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';

   
 

  if($mode=='code')
     $where=$where.sprintf(" and P.`Product Code`=%s ",prepare_mysql($tag));
   elseif($mode=='pid')
     $where=$where.sprintf(" and PD.`Product ID`=%d ",$tag);
   elseif($mode=='key')
     $where=$where.sprintf(" and PD.`Product Key`=%d ",$tag);



   $wheref="";
   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
     if($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer'){
       if($_REQUEST['f_value']!='')
	 $wheref=" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
     }
   }
   
  


   $sql="select count(DISTINCT `Order Key`) as total from `Order Transaction Fact` OTF  left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)  left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)   $where $wheref";
   //print $sql;   
$res = mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
  $total=$row['total'];
   }
   if($wheref==''){
     $filtered=0;  $total_records=$total;
   }else{
     $sql="select count(DISTINCT `Order Key`) as total from `Order Transaction Fact` OTF left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)   left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)  $where      ";
       $res = mysql_query($sql);
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
       }
       
   }

 $rtext=$total_records." ".ngettext('order','orders',$total_records);
  if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
  $filter_msg='';


if($order=='dispatched')
      $order='`Shipped Quantity`';
   elseif($order=='order'){
     $order='';
     $order_direction ='';

  }else{
   $order='`Delivery Note Date`';
  
  }
  
  
   $sql=sprintf("select `Delivery Note XHTML Orders`,`Customer Name`,CD.`Customer Key`,`Delivery Note Date`,sum(`Shipped Quantity`) as dispatched,sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`+`No Shipped Due Other`) as undispatched  from     `Order Transaction Fact` OTF  left join   `Delivery Note Dimension` DND on (OTF.`Delivery Note Key`=DND.`Delivery Note Key`) left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)   left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)    left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)    %s %s  and OTF.`Delivery Note Key`>0  group by OTF.`Delivery Note Key`  order by  $order $order_direction  limit $start_from,$number_results"
		,$where
		,$wheref
		);
   // print $sql;

   $res=mysql_query($sql);
   $data=array();

   while($row= mysql_fetch_array($res, MYSQL_ASSOC) ) {
     if($can_see_customers)
       $customer='<a href="customer.php?id='.$row['Customer Key'].'">'.$row['Customer Name'].'</a>';
     else
       $customer=$myconf['customer_id_prefix'].sprintf("%05d",$row['Customer Key']);
     


     $data[]=array(
		   'order'=>$row['Delivery Note XHTML Orders'],
		   'customer_name'=>$customer,
		   'date'=> strftime("%e %b %y", strtotime($row['Delivery Note Date'])),
		   'dispatched'=>number($row['dispatched']),
		   'undispatched'=>number($row['undispatched'])

		   );
   }

   $response=array('resultset'=>
		   array('state'=>200,
			 
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_delivery_notes(){
   
    $conf=$_SESSION['state']['orders']['dn'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
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
     
     
     if(isset( $_REQUEST['dn_state_type']))
     $state=$_REQUEST['dn_state_type'];
   else
     $state=$conf['dn_state_type'];
     
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
 else{
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
     $from=$conf['from'];
   else
     $from=$_SESSION['state']['orders']['from'];
 }

  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else{
    if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $to=$conf['to'];
    else
      $to=$_SESSION['state']['orders']['to'];
  }

   if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
   else{
     if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
       $view=$conf['view'];
     else
       $view=$_SESSION['state']['orders']['view'];

   }
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

        
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales'){
     
     $_SESSION['state']['report']['sales']['order']=$order;
     $_SESSION['state']['report']['sales']['order_dir']=$order_direction;
     $_SESSION['state']['report']['sales']['nr']=$number_results;
     $_SESSION['state']['report']['sales']['sf']=$start_from;
     $_SESSION['state']['report']['sales']['where']=$where;
     $_SESSION['state']['report']['sales']['f_field']=$f_field;
     $_SESSION['state']['report']['sales']['f_value']=$f_value;
     $_SESSION['state']['report']['sales']['to']=$to;
     $_SESSION['state']['report']['sales']['from']=$from;
     $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
     
   }else{
     if(isset( $_REQUEST['store_id'])    ){
     $store=$_REQUEST['store_id'];
     $_SESSION['state']['orders']['store']=$store;
   }else
     $store=$_SESSION['state']['orders']['store'];

     $_SESSION['state']['orders']['dn']=array('dn_state_type'=>$state,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
     $_SESSION['state']['orders']['view']=$view;
     $date_interval=prepare_mysql_dates($from,$to,'`Delivery Note Date`','only_dates');
     if($date_interval['error']){
       $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
     }else{
       $_SESSION['state']['orders']['from']=$date_interval['from'];
       $_SESSION['state']['orders']['to']=$date_interval['to'];
     }
   }
 if(is_numeric($store)){
     $where.=sprintf(' and `Delivery Note Store Key`=%d ',$store);
   }

   $where.=$date_interval['mysql'];
   
 
switch ($state) {
case 'shortages':
    $where.=' and `Delivery Note Type` in ("Shortages","Replacement & Shortages")';
    break;
case 'replacements':
    $where.=' and `Delivery Note Type` in ("Replacement","Replacement & Shortages")';
    break;
case 'donations':
    $where.=' and `Delivery Note Type`="Donation"';
    break;
case 'samples':
    $where.=' and `Delivery Note Type`="Sample"';
    break;
case 'orders':
    $where.=' and `Delivery Note Type`="Order"';
    break;
case 'returned':
    $where.=' and `Delivery Note State`="Cancelled to Restock"';
    break;
case 'send':
    $where.=' and `Delivery Note State`="Dispatched"';
    break;
case 'ready':
    $where.=' and `Delivery Note State` in ("Packed","Approved")';
    break;
case 'packing':
    $where.=" and `Delivery Note State` in ('Picking & Packing','Packer Assigned','Picked','Packing')";
    break;
case 'picking':
    $where.=' and `Delivery Note State` in ("Picking & Packing","Picking")';
    break;

case 'ready_to_pick':
    $where.=' and `Delivery Note State` in ("Ready to be Picked","Picker Assigned")';
    break;
default:

    break;
}
   
   
   
   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
  elseif($f_field=='public_id' and $f_value!='')
    $wheref.=" and  `Delivery Note ID` like '".addslashes($f_value)."%'";
  elseif($f_field=='invoice' and $f_value!='')
    $wheref.=" and  `Delivery Note Invoices` like '".addslashes($f_value)."%'";
 elseif($f_field=='order' and $f_value!='')
    $wheref.=" and  `Delivery Note Order` like '".addslashes($f_value)."%'";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  total<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  total>=".$f_value."    ";
   


   

   
  $sql="select count(*) as total from `Delivery Note Dimension`   $where $wheref ";
  // print $sql ;
   $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $total=$row['total'];
  }
  mysql_free_result($result);
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Delivery Note Dimension`  $where";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$total_records=$row['total'];
	$filtered=$total_records-$total;
      }
      mysql_free_result($result);
  }
  $rtext=$total_records." ".ngettext('delivery note','delivery notes',$total_records);
  if($total_records>$number_results)
    $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
   else
    $rtext_rpp=sprintf("Showing all delivery notes");

  $filter_msg='';

     switch($f_field){
     case('public_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes starting with')." <b>$f_value</b>)";
       break;
     case('customer_name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes with customer')." <b>".$f_value."*</b>)";
       break;  
     case('minvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes with min value of')." <b>".money($f_value)."*</b>)";
       break;  
   case('maxvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes with max value of')." <b>".money($f_value)."*</b>)";
       break;  
 case('max'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days DN').")";
       break;  
     }



   
   $_order=$order;
   $_dir=$order_direction;

   
   if($order=='date' or $order=='')
     $order='`Delivery Note Date`';
   else if($order=='id')
     $order='`Delivery Note File As`';
   else if($order=='customer')
     $order='`Delivery Note Customer Name`';
   else if($order=='type')
     $order='`Delivery Note Type`';
 else if($order=='weight')
     $order='`Delivery Note Weight`';
 else if($order=='parcels')
     $order='`Delivery Note Parcel Type`,`Delivery Note Number Parcels`';

   
  $sql="select *  from `Delivery Note Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  // print $sql;

   $data=array();

   $res = mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $order_id=sprintf('<a href="dn.php?id=%d">%s</a>',$row['Delivery Note Key'],$row['Delivery Note ID']);
     $customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Delivery Note Customer Key'],$row['Delivery Note Customer Name']);


     $type=$row['Delivery Note Type'];

     switch($row['Delivery Note Parcel Type']){
     case('Pallet'):
       $parcel_type='P';
       break;
     case('Envelope'):
       $parcel_type='e';
       break;
     default:     
       $parcel_type='b';
       
     }

     if($row['Delivery Note Number Parcels']==''){
       $parcels='?';
     }elseif($row['Delivery Note Parcel Type']=='Pallet' and $row['Delivery Note Number Boxes']){
       $parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type.' ('.$row['Delivery Note Number Boxes'].' b)';
     }else{
       $parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type;
     }
if($row['Delivery Note State']=='Dispatched')
$date=strftime("%e %b %y", strtotime($row['Delivery Note Date']));
else
$date=strftime("%e %b %y", strtotime($row['Delivery Note Date Created']));
     $data[]=array(
		   'id'=>$order_id
		   ,'customer'=>$customer
		   ,'date'=>$date
		   ,'type'=>$type.($row['Delivery Note XHTML Orders']?' ('.$row['Delivery Note XHTML Orders'].')':'')
		   ,'orders'=>$row['Delivery Note XHTML Orders']
		   ,'invoices'=>$row['Delivery Note XHTML Invoices']
		   ,'weight'=>number($row['Delivery Note Weight'],1,true).' Kg'
		   ,'parcels'=>$parcels


);
   }
   mysql_free_result($res);

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}

function list_invoices(){
 
    if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $conf=$_SESSION['state']['report']['sales'];
    else
      $conf=$_SESSION['state']['orders']['invoices'];
    if(isset( $_REQUEST['sf']))
      $start_from=$_REQUEST['sf'];
    else
      $start_from=$conf['sf'];
    if(isset( $_REQUEST['nr']))
      $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];

if(isset( $_REQUEST['where']))
  $where=stripslashes($_REQUEST['where']);
   else
     $where=$conf['where'];
  
  if(isset( $_REQUEST['invoice_type']))
  $type=stripslashes($_REQUEST['invoice_type']);
   else
     $type=$conf['invoice_type'];
  
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
 else{
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
     $from=$conf['from'];
   else
     $from=$_SESSION['state']['orders']['from'];
 }

  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else{
    if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
      $to=$conf['to'];
    else
      $to=$_SESSION['state']['orders']['to'];
  }

   if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
   else{
     if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales')
       $view=$conf['view'];
     else
       $view=$_SESSION['state']['orders']['view'];

   }
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
//print $where;

   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

        
   if(isset($_REQUEST['saveto']) and $_REQUEST['saveto']=='report_sales'){
     
     $_SESSION['state']['report']['sales']['order']=$order;
     $_SESSION['state']['report']['sales']['order_dir']=$order_direction;
     $_SESSION['state']['report']['sales']['nr']=$number_results;
     $_SESSION['state']['report']['sales']['sf']=$start_from;
     $_SESSION['state']['report']['sales']['where']=$where;
     $_SESSION['state']['report']['sales']['f_field']=$f_field;
     $_SESSION['state']['report']['sales']['f_value']=$f_value;
     $_SESSION['state']['report']['sales']['to']=$to;
     $_SESSION['state']['report']['sales']['from']=$from;

if(isset($_REQUEST['store_key'])){
$store=$_REQUEST['store_key'];
$_SESSION['state']['report']['sales']['store']=$store;
}else
$store=$_SESSION['state']['report']['sales']['store'];

    $date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
    
   }else{
      if(isset( $_REQUEST['store_id'])    ){
     $store=$_REQUEST['store_id'];
     $_SESSION['state']['orders']['store']=$store;
   }else
     $store=$_SESSION['state']['orders']['store'];


     $_SESSION['state']['orders']['invoices']=array(
     'invoice_type'=>$type,
     'order'=>$order,
     'order_dir'=>$order_direction,
     'nr'=>$number_results,
     'sf'=>$start_from,
     'where'=>$where,
     'f_field'=>$f_field,
     'f_value'=>$f_value
     );
     $_SESSION['state']['orders']['view']=$view;
     $date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
     if($date_interval['error']){
       $date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
     }else{
       $_SESSION['state']['orders']['from']=$date_interval['from'];
       $_SESSION['state']['orders']['to']=$date_interval['to'];
     }
   }

   
 if(is_numeric($store)){
     $where.=sprintf(' and `Invoice Store Key`=%d ',$store);
   }


switch ($type) {
    case 'paid':
        $where.=' and `Invoice Paid`="Yes"';
        break;
    case 'to_pay':
        $where.=' and `Invoice Paid`!="Yes"';
        break;
    case 'invoices':
        $where.=' and `Invoice Title`="Invoice"';
        break;        
    case 'refunds':
        $where.=' and `Invoice Title`="Refund"';
        break;
    default:
 if(!isset($_REQUEST['saveto']) or $_REQUEST['saveto']!='report_sales'){   
    $_SESSION['state']['orders']['invoices']['invoice_type']='all';
    }
        break;
}


   $where.=$date_interval['mysql'];
   
   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))>=".$f_value."    ";
   elseif($f_field=='customer_name' and $f_value!='')
    $wheref.=" and  `Invoice Customer Name` like   '".addslashes($f_value)."%'";
  elseif( $f_field=='public_id' and $f_value!='')
    $wheref.=" and  `Invoice Public ID` like '".addslashes($f_value)."%'";
   
else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  `Invoice Total Amount`<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  `Invoice Total Amount`>=".$f_value."    ";
   


   

   
  $sql="select count(*) as total from `Invoice Dimension`   $where $wheref ";
  //  print $sql ;
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    $total=$row['total'];
  }
  mysql_free_result($res);
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Invoice Dimension`  $where";
       $res=mysql_query($sql);
  if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$total_records=$row['total'];
	$filtered=$total_records-$total;
      }
      mysql_free_result($res);
  }
  $rtext=$total_records." ".ngettext('invoice','invoices',$total_records);
  if($total_records>$number_results)
    $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
   else
    $rtext_rpp=sprintf("Showing all invoices");

  $filter_msg='';

     switch($f_field){
     case('public_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
       break;
     case('customer_name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with customer')." <b>".$f_value."*</b>)";
       break;  
     case('minvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
       break;  
   case('maxvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>)";
       break;  
 case('max'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
       break;  
     }



   
   $_order=$order;
   $_dir=$order_direction;

   
   if($order=='date')
     $order='`Invoice Date`';
   else if($order=='last_date')
     $order='`Invoice Last Updated Date`';
   else if($order=='id')
     $order='`Invoice File As`';
   else if($order=='state')
     $order='`Invoice Current Dispatch State`,`Invoice Current Payment State`';
   else if($order=='total_amount')
     $order='`Invoice Total Amount`';
else if($order=='customer')
     $order='`Invoice Customer Name`';
 else if($order=='state')
   $order='`Invoice Has Been Paid In Full`';
else if($order=='net')
     $order='`Invoice Total Net Amount`';
  $sql="select `Invoice Currency`,`Invoice Total Net Amount`,`Invoice Has Been Paid In Full`,`Invoice Key`,`Invoice XHTML Orders`,`Invoice XHTML Delivery Notes`,`Invoice Public ID`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice Date`,`Invoice Total Amount`  from `Invoice Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  // print $sql;

   $data=array();

   
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

     $order_id=sprintf('<a href="invoice.php?id=%d">%s</a>',$row['Invoice Key'],$row['Invoice Public ID']);
     $customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Invoice Customer Key'],$row['Invoice Customer Name']);
     if($row['Invoice Has Been Paid In Full']=='Yes')
       $state=_('Paid');
     else
       $state=_('No Paid');

     $data[]=array(
		   'id'=>$order_id
		   ,'customer'=>$customer
		   ,'date'=>strftime("%e %b %y", strtotime($row['Invoice Date']))
		   ,'total_amount'=>money($row['Invoice Total Amount'],$row['Invoice Currency'])
		   ,'net'=>money($row['Invoice Total Net Amount'],$row['Invoice Currency'])

		   ,'state'=>$state
		   ,'orders'=>$row['Invoice XHTML Orders']
		   ,'dns'=>$row['Invoice XHTML Delivery Notes']
		   );
   }
mysql_free_result($res);
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}






function transactions_to_process(){
 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];
   



   $where=' where `Order Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   $sql="select * from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where   ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   
   
   
   

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
   //   $total_charged+=$row['charge'];
//      $total_discounts+=$ndiscount;
//      $total_picks+=$row['dispatched'];
     $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
     $data[]=array(

		   'code'=>$code
		   ,'description'=>$row['Product XHTML Short Description']
		   ,'tariff_code'=>$row['Product Tariff Code']
		   ,'quantity'=>number($row['Order Quantity'])
		   ,'gross'=>money($row['Order Transaction Gross Amount'],$row['Order Currency Code'])
		   ,'discount'=>money($row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		   ,'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		   );
   }


 
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}

function transactions_cancelled(){
 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];
   



   $where=' where `Order Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   $sql="select * from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where   ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   
   
   
   

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
   //   $total_charged+=$row['charge'];
//      $total_discounts+=$ndiscount;
//      $total_picks+=$row['dispatched'];
     $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
     $data[]=array(

		   'code'=>$code
		   ,'description'=>$row['Product XHTML Short Description']
		   ,'tariff_code'=>$row['Product Tariff Code']
		   ,'quantity'=>number($row['Order Quantity'])
		   ,'gross'=>money($row['Order Transaction Gross Amount'],$row['Order Currency Code'])
		   ,'discount'=>money($row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		   ,'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		   );
   }


 
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}

function list_transactions_in_warehouse(){
 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['order']['id'];
   



   $where=' where `Order Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   $sql="select * from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where   ";
   
   //  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
   
   
   
   

   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
   //   $total_charged+=$row['charge'];
//      $total_discounts+=$ndiscount;
//      $total_picks+=$row['dispatched'];
     $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
     $data[]=array(

		   'code'=>$code
		   ,'description'=>$row['Product XHTML Short Description']
		   ,'tariff_code'=>$row['Product Tariff Code']
		   ,'quantity'=>number($row['Order Quantity'])
		   ,'gross'=>money($row['Order Transaction Gross Amount'])
		   ,'discount'=>money($row['Order Transaction Total Discount Amount'])
		   ,'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'])
		   );
   }


 
   

   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}




?>