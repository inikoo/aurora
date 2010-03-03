<?php
/*
 File: ar_porders.php 

 Ajax Server Anchor for the Order Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyrigh (c) 2010, Kaktus 
 
 Version 2.0
*/
require_once 'common.php';
if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }


$tipo=$_REQUEST['tipo'];

switch($tipo){
case('purchase_orders'):
  list_purchase_orders();
  break;
default:
  $response=array('state'=>404,'resp'=>_('Operation not found'));
  echo json_encode($response);
}



function list_purchase_orders(){


  if(isset($_REQUEST['parent'])){
    if($_REQUEST['parent']=='supplier')
      $_SESSION['state']['porders']['parent']='supplier';
    else
      $_SESSION['state']['porders']['parent']='none';
  }

  $parent=$_SESSION['state']['porders']['parent'];
  if($parent=='supplier'){
    if(isset($_REQUEST['parent_key']) and is_numeric($_REQUEST['parent_key'])){
      $_SESSION['state']['porders']['parent_key']=$_REQUEST['parent_key'];
    }

  }else
    $_SESSION['state']['porders']['parent_key']='';



  $parent_key=$_SESSION['state']['porders']['parent_key'];

  $conf=$_SESSION['state']['porders']['table'];
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
  else
    $from=$_SESSION['state']['porders']['table']['from'];
  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else
    $to=$_SESSION['state']['porders']['table']['to'];


   if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
  else
    $view=$_SESSION['state']['porders']['table']['view'];


   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['porders']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $_SESSION['state']['porders']['table']['view']=$view;
   $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
   if($date_interval['error']){
      $date_interval=prepare_mysql_dates($_SESSION['state']['porders']['table']['from'],$_SESSION['state']['porders']['table']['to']);
   }else{
     $_SESSION['state']['porders']['table']['from']=$date_interval['from'];
     $_SESSION['state']['porders']['table']['to']=$date_interval['to'];
   }

   if($parent=='supplier')
     $where.=sprintf(' and `Purchase Order Supplier Key`=%d',$parent_key);
 
   
//    switch($view){
//    case('all'):
//      break;
//    case('submited'):
//      $where.=' and porden.status_id==10 ';
//      break;
//    case('new'):
//      $where.=' and porden.status_id<10 ';
//      break;
//    case('received'):
//      $where.=' and porden.status_id>80 ';
//      break;
//    default:
     
     
//    }
   $where.=$date_interval['mysql'];
   
   $wheref='';

  if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
   elseif(($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  total<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  total>=".$f_value."    ";
   




   
   $sql="select count(*) as total from `Purchase Order Dimension`   $where $wheref ";

   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
     $total_records=$total;
  }else{
    
      $sql="select count(*) as total from `Purchase Order Dimension`   $where";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$total_records=$row['total'];
	$filtered=$row['total']-$total;
      }
      
  }
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
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     case('minvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;  
   case('maxvalue'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;  
 case('max'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').") <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;  
     }



   
   $_order=$order;
   $_dir=$order_direction;
   $order='`Purchase Order Last Updated Date`';

   
  $sql="select  `Purchase Order Last Updated Date`,`Purchase Order Currency Code`,`Purchase Order Current Dispatch State`,`Purchase Order Key`,`Purchase Order Public ID`,`Purchase Order Total Amount`,`Purchase Order Number Items` from  `Purchase Order Dimension`   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
  // print $sql;
  //  print $sql;
   $result=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){

     $status=$row['Purchase Order Current Dispatch State'];

     $data[]=array(
		   'id'=>'<a href="porder.php?id='.$row['Purchase Order Key'].'">'.$row['Purchase Order Public ID']."</a>",
		   'date'=>strftime("%e %b %Y %H:%M", strtotime($row['Purchase Order Last Updated Date'])),
		   'total'=>money($row['Purchase Order Total Amount'],$row['Purchase Order Currency Code']),
		   'items'=>number($row['Purchase Order Number Items']),
		   'status'=>$status
		   );
   }

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