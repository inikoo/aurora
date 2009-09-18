<?php
/*
 File: ar_warehouse.php 

 Ajax Server Anchor for the warehouse Clases

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
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
case('locations'):
  list_locations();
  break;
case('warehouse_area'):
  list_warehouse_area();
  
  break;
default:

   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }
}

function list_location(){
$conf=$_SESSION['state']['warehouse']['locations'];
   
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
   

   
   
 
   $wheref='';
   if($f_field=='code' and $f_value!='')
     $wheref.=" and  `Location Code` like '".addslashes($f_value)."%'";
   

  
   $_SESSION['state']['warehouse']['locations']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   
   
   $sql="select count(*) as total from `Location Dimension`    $where $wheref";
   // print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
   if($wheref==''){
       $filtered=0;
       $total_records=$total;
   }else{
     $sql="select count(*) as total from `Location Dimension`  $where ";

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $total_records=$row['total'];
       $filtered=$row['total']-$total;
     }

   }

   




   if($total==0 and $filtered>0){
     switch($f_field){
     case('code'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any location name starting with")." <b>$f_value</b> ";
       break;
     }
   }elseif($filtered>0){
     switch($f_field){
     case('code'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only locations starting with')." <b>$f_value</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     }
   }else
      $filter_msg='';
   
   
   $rtext=$total_records." ".ngettext('location','locations',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
  $_order=$order;
  $_dir=$order_direction;

  if($order=='code')
    $order='`Location Code`';
  elseif($order=='parts')
    $order='`Location Distinct Parts`';
 elseif($order=='max_volumen')
    $order='`Location Max Volume`';
  elseif($order=='max_weight')
    $order='`Location Max Weight`';
  elseif($order=='tipo')
    $order='`Location Mainly Used For`';
 elseif($order=='area')
    $order='`Location Area`';

  $data=array();
  $sql="select * from `Location Dimension`  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
  //  print $sql;
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $code=sprintf('<a href="location.php?id=%d" >%s</a>',$row['Location Key'],$row['Location Code']);
    $tipo=$row['Location Mainly Used For'];

    if($row['Location Max Weight']=='' or $row['Location Max Weight']<=0)
      $max_weight=_('Unknown');
    else
      $max_weight=number($row['Location Max Weight'])._('Kg');
    if($row['Location Max Volume']==''  or $row['Location Max Volume']<=0)
      $max_vol=_('Unknown');
    else
      $max_vol=number($row['Location Max Volume'])._('L');

    if($row['Location Area']=='')
      $area=_('Unknown');
    else
      $area=$row['Location Area'];
    $data[]=array(
		 'id'=>$row['Location Key']
		 ,'tipo'=>$tipo
		 ,'code'=>$code
		 ,'area'=>$area
		 ,'parts'=>number($row['Location Distinct Parts'])
		 ,'max_weight'=>$max_weight
		 ,'max_volumen'=>$max_vol
		 );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 	'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'rtext'=>$rtext,
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

function list_warehouse_area(){
 
      if(isset( $_REQUEST['warehouse']) and  is_numeric( $_REQUEST['warehouse']))
	$warehouse_id=$_REQUEST['warehouse'];
      else
	$warehouse_id=$_SESSION['state']['warehouse']['id'];
   

      $conf=$_SESSION['state']['warehouse_area']['table'];
      $conf2=$_SESSION['state']['warehouse_area'];
      $conf_table='warehouse_area';

      if(isset( $_REQUEST['parent']))
	$parent=$_REQUEST['parent'];
      else
	$parent=$conf['parent'];


      if(isset( $_REQUEST['sf'])){
	$start_from=$_REQUEST['sf'];
	
	
      }else
	$start_from=$conf['sf'];
      if(isset( $_REQUEST['nr'])){
	$number_results=$_REQUEST['nr'];
	if($start_from>0){
	  $page=floor($start_from/$number_results);
	  $start_from=$start_from-$page;
	}
	
      }else
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
      
      
    

      
      
      $_SESSION['state'][$conf_table]['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'parent'=>$parent);
     
      
      
      switch($parent){
      case('warehouse'):
	$where=sprintf("where  `Warehouse Key`=%d",$warehouse_id);
	break;
      default:
	$where='where true';
	  
	  }   

      $filter_msg='';
      $wheref='';
      if($f_field=='name' and $f_value!='')
	$wheref.=" and  `Warehouse Area Name` like '".addslashes($f_value)."%'";
      if($f_field=='code' and $f_value!='')
	$wheref.=" and  `Warehouse Area Code` like '".addslashes($f_value)."%'";
   
   

   
   
   $sql="select count(*) as total from `Warehouse Area Dimension`   $where $wheref";

   $res = mysql_query($sql); 
   if($row=mysql_fetch_array($res)) {
     $total=$row['total'];
   }
   mysql_free_result($res);
   if($wheref==''){
     $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total `Warehouse Area Dimension`   $where ";
     
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      	$total_records=$row['total'];
	$filtered=$total_records-$total;
	mysql_free_result($result);
     }

   }

 $rtext=$total_records." ".ngettext('area','areas',$total_records);
  if($total_records>$number_results)
    $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
  else
    $rtext_rpp=' ('._('Showing all').')';
   $_dir=$order_direction;
   $_order=$order;
   
   $order='`Warehouse Area Code`';
   if($order=='name')
     $order='`Warehouse Area Name`';
   elseif($order=='code')
     $order='`Warehouse Area Code`';
 

 
   $sql="select *  from `Warehouse Area Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
   // print $sql;
   $res = mysql_query($sql);
   $adata=array();
   
   $sum_active=0;
   
   
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $code=sprintf('<a href="warehouse_area.php?id=%d">%s</a>',$row['Warehouse Area Key'],$row['Warehouse Area Code']);
     $name=sprintf('<a href="warehouse_area.php?id=%d">%s</a>',$row['Warehouse Area Key'],$row['Warehouse Area Name']);
     
   
    $adata[]=array(
		  'code'=>$code,
		   'name'=>$name,
		   
		   
		   );
  }
  mysql_free_result($res);

  





  
  
  $response=array('resultset'=>
		  array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
			)
		   );
   echo json_encode($response);
}

?>