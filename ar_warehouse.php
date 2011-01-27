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
require_once 'ar_edit_common.php';

if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){
case('location_stock_history'):
  history_stock_location();
  break;
  case('stock_history'):
  warehouse_stock_history();
  break;
case('parts_at_location'):
  parts_at_location();
  break;
case('find_warehouse_area'):
$data=prepare_values($_REQUEST,array(
			     'query'=>array('type'=>'string')
			     ,'parent_key'=>array('type'=>'string')
			     ));
  find_warehouse_area($data);
  break;
case('find_location'):
  find_location();
  break;
  case('find_shelf_type'):
   $data=prepare_values($_REQUEST,array(
			     'query'=>array('type'=>'string')
			     ));
  find_shelf_type($data);
  break;
case('locations'):
  list_locations();
  break;
  case('shelfs'):
  list_shelfs();
  break;
case('warehouse_areas'):
  list_warehouse_areas();
  break;
  case('warehouses'):
  list_warehouses();
  break;
case('part_categories'):
    list_part_categories();
    break;


default:

   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 
}

function list_locations(){
$conf=$_SESSION['state']['locations']['table'];
   
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
   
 if(isset( $_REQUEST['parent'])){
     $parent=$_REQUEST['parent'];
    $_SESSION['state']['locations']['parent']=$parent;
   }else
   $parent=$_SESSION['state']['locations']['parent'];
   
 
 
  
   $_SESSION['state']['locations']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   
   
   switch($parent){
   case('warehouse'):
   $where.=sprintf(' and `Location Warehouse Key`=%d',$_SESSION['state']['warehouse']['id']);
   break;
   case('warehouse_area'):
   $where.=sprintf(' and `Location Warehouse Area Key`=%d',$_SESSION['state']['warehouse_area']['id']);
   break;
   case('shelf'):
   $where.=sprintf(' and `Location Shelf Key`=%d',$_SESSION['state']['shelf']['id']);
   break;
   }
   
   
     $wheref='';
   if($f_field=='code' and $f_value!='')
     $wheref.=" and  `Location Code` like '".addslashes($f_value)."%'";
   

   
   
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
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only locations starting with')." <b>$f_value</b>";
       break;
     }
   }else
      $filter_msg='';
   
   
   $rtext=$total_records." ".ngettext('location','locations',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
  $_order=$order;
  $_dir=$order_direction;


  
  if($order=='parts')
    $order='`Location Distinct Parts`';
 elseif($order=='max_volumen')
    $order='`Location Max Volume`';
  elseif($order=='max_weight')
    $order='`Location Max Weight`';
  elseif($order=='tipo')
    $order='`Location Mainly Used For`';
 elseif($order=='area')
    $order='`Warehouse Area Code`';
elseif($order=='warehouse')
    $order='`Warehouse Code`';
else
   $order='`Location Code`';


  $data=array();
  $sql="select * from `Location Dimension` left join `Warehouse Area Dimension` WAD on (`Location Warehouse Area Key`=WAD.`Warehouse Area Key`) left join `Warehouse Dimension` WD on (`Location Warehouse Key`=WD.`Warehouse Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
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

    if($row['Warehouse Area Code']=='')
      $area=_('Unknown');
    else
      $area=sprintf('<a href="warehouse_area.php?id=%d">%s</a>',$row['Warehouse Area Key'],$row['Warehouse Area Code']);
            $warehouse=sprintf('<a href="warehouse.php?id=%d">%s</a>',$row['Warehouse Key'],$row['Warehouse Code']);

    $data[]=array(
		 'id'=>$row['Location Key']
		 ,'tipo'=>$tipo
		 ,'code'=>$code
		 ,'area'=>$area
		 ,'warehouse'=>$warehouse
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
function list_shelfs(){
$conf=$_SESSION['state']['shelfs']['table'];
   
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
   
 if(isset( $_REQUEST['parent'])){
     $parent=$_REQUEST['parent'];
    $_SESSION['state']['shelfs']['parent']=$parent;
   }else
   $parent=$_SESSION['state']['shelfs']['parent'];
   
 
 
  
   $_SESSION['state']['shelfs']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   
   
   switch($parent){
   case('warehouse'):
   $where.=sprintf(' and `Shelf Warehouse Key`=%d',$_SESSION['state']['warehouse']['id']);
   break;
   case('warehouse_area'):
   $where.=sprintf(' and `Shelf Area Key`=%d',$_SESSION['state']['warehouse_area']['id']);
   break;
   case('shelf'):
   $where.=sprintf(' and `Shelf Shelf Key`=%d',$_SESSION['state']['shelf']['id']);
   break;
   }
   
   
     $wheref='';
   if($f_field=='code' and $f_value!='')
     $wheref.=" and  `Shelf Code` like '".addslashes($f_value)."%'";
   

   
   
   $sql="select count(*) as total from `Shelf Dimension`    $where $wheref";
   // print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
   if($wheref==''){
       $filtered=0;
       $total_records=$total;
   }else{
     $sql="select count(*) as total from `Shelf Dimension`  $where ";

     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $total_records=$row['total'];
       $filtered=$row['total']-$total;
     }

   }

   




   if($total==0 and $filtered>0){
     switch($f_field){
     case('code'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any shelf name starting with")." <b>$f_value</b> ";
       break;
     }
   }elseif($filtered>0){
     switch($f_field){
     case('code'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only shelfs starting with')." <b>$f_value</b>";
       break;
     }
   }else
      $filter_msg='';
   
   
   $rtext=$total_records." ".ngettext('shelf','shelfs',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
  $_order=$order;
  $_dir=$order_direction;


    $order='`Shelf Code`';
  if($order=='parts')
    $order='`Shelf Distinct Parts`';
 if($order=='locations')
    $order='`Shelf Number Locations`';
 elseif($order=='area')
    $order='`Warehouse Area Code`';
elseif($order=='warehouse')
    $order='`Warehouse Code`';
  $data=array();
  $sql="select * from `Shelf Dimension` left join `Warehouse Area Dimension` WAD on (`Shelf Area Key`=WAD.`Warehouse Area Key`) left join `Warehouse Dimension` WD on (`Shelf Warehouse Key`=WD.`Warehouse Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
  //  print $sql;
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $code=sprintf('<a href="shelf.php?id=%d" >%s</a>',$row['Shelf Key'],$row['Shelf Code']);

   

    if($row['Warehouse Area Code']=='')
      $area=_('Unknown');
    else
      $area=sprintf('<a href="warehouse_area.php?id=%d">%s</a>',$row['Warehouse Area Key'],$row['Warehouse Area Code']);
            $warehouse=sprintf('<a href="warehouse.php?id=%d">%s</a>',$row['Warehouse Key'],$row['Warehouse Code']);

    $data[]=array(
		 'id'=>$row['Shelf Key']
		// ,'tipo'=>$tipo
		 ,'code'=>$code
		 ,'area'=>$area
		 ,'warehouse'=>$warehouse
		 		 ,'locations'=>number($row['Shelf Number Locations'])

		 ,'parts'=>number($row['Shelf Distinct Parts'])
		 
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
function list_warehouse_areas(){
 
   
      $conf=$_SESSION['state']['warehouse_areas']['table'];
     
     
     
     
      if(isset( $_REQUEST['parent'])){
	$parent=$_REQUEST['parent'];
     $_SESSION['state']['warehouse_areas']['parent']=$parent;
     } else
	$parent=$_SESSION['state']['warehouse_areas']['parent'];


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
      
      
    

      
      
      $_SESSION['state']['warehouse_area']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'parent'=>$parent);
     
      
      
      switch($parent){
      case('warehouse'):
         if(isset( $_REQUEST['warehouse']) and  is_numeric( $_REQUEST['warehouse']))
	$warehouse_id=$_REQUEST['warehouse'];
      else
	$warehouse_id=$_SESSION['state']['warehouse']['id'];
   

      
      
	$where=sprintf("where  `Warehouse Key`=%d",$warehouse_id);
	
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
   elseif($order=='locations')
     $order='`Warehouse Area Number Locations`';
  elseif($order=='shelfs')
     $order='`Warehouse Area Number Shelfs`';
 
   $sql="select *  from `Warehouse Area Dimension` WA left join `Warehouse Dimension` W  on (WA.`Warehouse Key`=W.`Warehouse Key`) $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
   // print $sql;
   $res = mysql_query($sql);
   $adata=array();
   
   $sum_active=0;
   
   
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $code=sprintf('<a href="warehouse_area.php?id=%d">%s</a>',$row['Warehouse Area Key'],$row['Warehouse Area Code']);
     $name=sprintf('<a href="warehouse_area.php?id=%d">%s</a>',$row['Warehouse Area Key'],$row['Warehouse Area Name']);
          $warehouse=sprintf('<a href="warehouse.php?id=%d">%s</a>',$row['Warehouse Key'],$row['Warehouse Code']);

     $locations=number($row['Warehouse Area Number Locations']);
        $shelfs=number($row['Warehouse Area Number Shelfs']);

    $adata[]=array(
		   'code'=>$code,
		   'name'=>$name,
		   'locations'=>$locations,
		   		   'shelfs'=>$shelfs,
		   		   'parts'=>number($row['Warehouse Area Distinct Parts']),
		   		   'warehouse'=>$warehouse,

		   'description'=>$row['Warehouse Area Description']
		   
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

function find_warehouse_area($data){

 $q=$data['query'];

$where='';
  if( $_REQUEST['parent_key'])
    $where=sprintf(' and `Warehouse Key`=%d ',$data['parent_key']);


    $sql=sprintf("select `Warehouse Area Key`,`Warehouse Area Code`,`Warehouse Area Name` from `Warehouse Area Dimension` where (`Warehouse Area Code`like '%s%%' or `Warehouse Area Name` like '%%%s%%'   )  %s  order by `Warehouse Area Name` limit 10 "
		 ,addslashes($q)
		 ,addslashes($q)
		 ,$where
		 );
    //print $sql;
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
      $adata[]=array(
		     
		     'key'=>$row['Warehouse Area Key'],
		     'code'=>$row['Warehouse Area Code'],
		     'name'=>$row['Warehouse Area Name'],
		     
		     );
    }
    $response=array('data'=>$adata);
   echo json_encode($response);


}

function find_shelf_type($data){
  
   $q=$data['query'];
  $where='';
    $sql=sprintf("select *  from `Shelf Type Dimension` where (`Shelf Type Name`like '%s%%' or `Shelf Type Description` like '%%%s%%'   )  %s  order by `Shelf Type Name` limit 10 "
		 ,addslashes($q)
		 ,addslashes($q)
		 ,$where
		 );
    //print $sql;
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
    
        $info=sprintf("<h3>%s</h3><p>%s</p>",$row['Shelf Type Name'],$row['Shelf Type Description']);
      $adata[]=array(
		      	    "key"=>$row['Shelf Type Key']
		      	    ,"name"=>$row['Shelf Type Name']
		      	    ,"description"=>$row['Shelf Type Description']
		      	    ,"type"=>$row['Shelf Type Type']
		      	    ,"rows"=>$row['Shelf Type Rows']
		      	    ,"columns"=>$row['Shelf Type Columns']
		      	    ,"l_height"=>$row['Shelf Type Location Height']
		      	    ,"l_length"=>$row['Shelf Type Location Length']
		      	    ,'l_deep'=>$row['Shelf Type Location Deep']
		      	    ,'l_weight'=>$row['Shelf Type Location Max Weight']
		      	    ,'l_volume'=>$row['Shelf Type Location Max Volume']
                    ,'info'=>$info
		    
		     
		     );
    }
    $response=array('data'=>$adata);
   echo json_encode($response);


}




function find_location(){
  if(!isset($_REQUEST['query']))
    $q='';
  else
    $q=$_REQUEST['query'];
  $where='';
  if(isset($_REQUEST['parent']) and $_REQUEST['parent']=='warehouse')
    $where=sprintf(' and `Warehouse Key`=%d ',$_SESSION['state']['warehouse']['id']);

   if(isset($_REQUEST['except_location']) )
    $where=sprintf(' and LD.`Location Key`!=%d ',$_REQUEST['except_location']);

   
   $part_sku=0;
   if(isset($_REQUEST['get_data'])){
     if(preg_match('/^sku\d+$/i',$_REQUEST['get_data']))
       $part_sku=preg_replace('/sku/','',$_REQUEST['get_data']);
   }


   if($part_sku){
     
      if(isset($_REQUEST['with'])){
	if($_REQUEST['with']=='stock')
	  $where.=sprintf(' and (`Quantity On Hand` IS NOT NULL and `Quantity On Hand`>0 ')   ;
      }
     $sql=sprintf("select LD.`Location Key`,`Location Code`,(select `Quantity On Hand` from `Part Location Dimension` t where t.`Location Key`=LD.`Location Key` and `Part SKU`=%d) as `Quantity On Hand` from `Location Dimension` LD    where (`Location Code` like '%s%%' )  %s  order by `Location Code` limit 10 "
		  ,$part_sku
		  ,addslashes($q)
		  ,$where
		 );

   }else{
    $sql=sprintf("select `Location Key`,`Location Code`,0 as `Quantity On Hand` from `Location Dimension` LD where (`Location Code` like '%s%%'    )  %s  order by `Location Code` limit 10 "
		 ,addslashes($q)
		 ,$where
		 );
   }
   //  print $sql;
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
      if(!is_numeric($row['Quantity On Hand']))
	$row['Quantity On Hand']=0;
      $adata[]=array(
		     
		     'key'=>$row['Location Key'],
		     'code'=>$row['Location Code'],
		     'stock'=>$row['Quantity On Hand']
		     );
    }
    $response=array('data'=>$adata);
   echo json_encode($response);


}


function history_stock_location(){
 

 $conf=$_SESSION['state']['location']['stock_history'];
 $location_id=$_SESSION['state']['location']['id'];
 if(isset( $_REQUEST['elements']))
     $elements=$_REQUEST['elements'];
   else
     $elements=$conf['elements'];

 if(isset( $_REQUEST['from']))
     $from=$_REQUEST['from'];
   else
     $from=$conf['from'];
  if(isset( $_REQUEST['to']))
     $to=$_REQUEST['to'];
   else
     $to=$conf['to'];
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
 
 
 list($date_interval,$error)=prepare_mysql_dates($from,$to);
  if($error){
    list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
  }else{
      $_SESSION['state']['product']['stock_history']['from']=$from;
      $_SESSION['state']['product']['stock_history']['to']=$to;
  }

  $_SESSION['state']['product']['stock_history']=
    array(
	  'order'=>$order,
	  'order_dir'=>$order_direction,
	  'nr'=>$number_results,
	  'sf'=>$start_from,
	  'where'=>$where,
	  'f_field'=>$f_field,
	  'f_value'=>$f_value,
	  'from'=>$from,
	  'to'=>$to,
	  'elements'=>$elements
	  );
    $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';

  


  $wheref='';

  $where=$where.sprintf(" and `History Type`='Normal' and `Location Key`=%d  ",$location_id);

   
  //   $where =$where.$view.sprintf(' and product_id=%d  %s',$product_id,$date_interval);
   
   $sql="select count(*) as total from  `Inventory Transaction Fact`  $where $wheref";
   //   print "$sql";
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $total=$row['total'];
   }
   if($wheref=='')
       $filtered=0;
   else{
     $sql="select count(*) as total from  `Inventory Transaction Fact`  $where ";
     
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $filtered=$row['total']-$total;
     }

   }
   
   
   if($total==0)
     $rtext=_('No stock movements');
   else
     $rtext=$total.' '.ngettext('stock operation','stock operations',$total);
   



   $sql=sprintf("select  *,IFNULL(ITF.`User Key`,-1) as user from `Inventory Transaction Fact` ITF left join `User Dimension` UD on (ITF.`User Key`=UD.`User Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ");
 // print $sql;
 
 $result=mysql_query($sql);
  $adata=array();
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){
    
    if($data['user']==-1)
      $author=_('Unknown');
    elseif($data['user']==0)
      $author=_('System');
    else{
      $author=$data['User Alias'];
    
    }
    
    $tipo=$data['Inventory Transaction Type'];
    

    if($tipo=='Move In' or $tipo=='Audit' or   $tipo=='Move Out' ) 
      $qty=number($data['Inventory Transaction Quantity']);
    else
      $qty='';
    
    $adata[]=array(

		   'author'=>$author
		   ,'tipo'=>$tipo
		   ,'diff_qty'=>$qty
		   ,'diff_amount'=>money($data['Inventory Transaction Amount'])
		   ,'note'=>$data['Note']
		   ,'date'=>strftime("%a %e %b %Y %T", strtotime($data['Date'].' UTC')),
		   );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'rtext'=>$rtext,
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

function historic_parts_at_location(){
   $conf=$_SESSION['state']['location']['parts'];
   $location_id=$_SESSION['state']['location']['id'];
   
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

 if(isset( $_REQUEST['date']))
     $date=$_REQUEST['date'];
   else
     $date=date("Y-m-d");
 

   
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
 
 


  $_SESSION['state']['location']['parts']=
    array(
	  'order'=>$order,
	  'order_dir'=>$order_direction,
	  //'nr'=>$number_results,
	  // 'sf'=>$start_from,
	  'where'=>$where,
	  'f_field'=>$f_field,
	  'f_value'=>$f_value,
	  //  'from'=>$from,
	  //  'to'=>$to,
	  //  'elements'=>$elements
	  );
    $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';

  


//  $view='';
//  foreach($elements as $key=>$val){
//    if(!$val)
//      $view.=' and op_tipo!='.$key;
//  }


  $wheref='';
//   if($f_field=='name' and $f_value!='')
//     $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";

  
  $start_from=0;
  $number_results=99999999;
  
  

  $where=$where.sprintf(" and `Location Key`=%d and Date=%s",$location_id,prepare_mysql($date));

   
  //   $where =$where.$view.sprintf(' and part_id=%d  %s',$part_id,$date_interval);
   
   $sql="select count(*) as total from `Inventory Spanshot Fact`   $where $wheref";
   //   print "$sql";
   
   $res = mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


     $total=$row['total'];
   }
   if($wheref==''){
       $filtered=0;
       $total_records=$total;
   }else{
     $sql="select  count(*) as total from `Inventory Spanshot Fact`  $where ";
     $res = mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

       $total_records=$row['total'];
       $filtered=$row['total']-$total;
     }

   }
   
   if($order=='sku')
     $order='PD.`Part SKU`';
   
   if($total_records==0)
     $rtext=_('No parts on this location');
   else
     $rtext=$total_records.' '.ngettext('part','parts',$total_records);
   
   if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
   




   $sql=sprintf("select  * from `Inventory Spanshot Fact` ISF left join `Part Dimension` PD on (PD.`Part SKU`=ISF.`Part SKU`)    $where $wheref    order by $order $order_direction  ");


  $adata=array();

 $res = mysql_query($sql);
 // print $sql;
 while($data=mysql_fetch_array($res, MYSQL_ASSOC)) {
    

   $loc_sku=$data['Location Key'].'_'.$data['Part SKU'];

    $adata[]=array(

		   'sku'=>sprintf('<a href="part.php?sku=%d">%05d</a>',$data['Part SKU'],$data['Part SKU'])
		   ,'description'=>$data['Part XHTML Description']
		   ,'current_qty'=>sprintf('<span  used="0"  value="%s" id="s%s"  onclick="fill_value(%s,%d,%d)">%s</span>',$data['Quantity On Hand'],$loc_sku,$data['Quantity On Hand'],$data['Location Key'],$data['Part SKU'],number($data['Quantity On Hand']))
		   ,'changed_qty'=>sprintf('<span   used="0" id="cs%s"  onclick="change_reset(\'%s\',%d)"   ">0</span>',$loc_sku,$loc_sku,$data['Part SKU'])
		   ,'new_qty'=>sprintf('<span  used="0"  value="%s" id="ns%s"  onclick="fill_value(%s,%d,%d)">%s</span>',$data['Quantity On Hand'],$loc_sku,$data['Quantity On Hand'],$data['Location Key'],$data['Part SKU'],number($data['Quantity On Hand']))
		   ,'_qty_move'=>'<input id="qm'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
		   ,'_qty_change'=>'<input id="qc'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
		   ,'_qty_damaged'=>'<input id="qd'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
		   ,'note'=>'<input  id="n'.$loc_sku.'" type="text" value="" style="width:100px">'
		   ,'delete'=>($data['Quantity On Hand']==0?'<img onclick="remove_prod('.$data['Location Key'].','.$data['Part SKU'].')" style="cursor:pointer" title="'._('Remove').' '.$data['Part SKU'].'" alt="'._('Desassociate Product').'" src="art/icons/cross.png".>':'')
		   );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'rtext'=>$rtext,
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


function parts_at_location(){
   $conf=$_SESSION['state']['location']['parts'];
   $location_id=$_SESSION['state']['location']['id'];
   
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

 if(isset( $_REQUEST['date']))
     $date=$_REQUEST['date'];
   else
     $date=date("Y-m-d");
 

   
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
 
 


  $_SESSION['state']['location']['parts']=
    array(
	  'order'=>$order,
	  'order_dir'=>$order_direction,
	  //'nr'=>$number_results,
	  // 'sf'=>$start_from,
	  'where'=>$where,
	  'f_field'=>$f_field,
	  'f_value'=>$f_value,
	  //  'from'=>$from,
	  //  'to'=>$to,
	  //  'elements'=>$elements
	  );
    $_order=$order;
   $_dir=$order_direction;
   $filter_msg='';

  


//  $view='';
//  foreach($elements as $key=>$val){
//    if(!$val)
//      $view.=' and op_tipo!='.$key;
//  }


  $wheref='';
//   if($f_field=='name' and $f_value!='')
//     $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";

  
  $start_from=0;
  $number_results=99999999;
  
  

  $where=$where.sprintf(" and PLD.`Location Key`=%d ",$location_id);

   
  //   $where =$where.$view.sprintf(' and part_id=%d  %s',$part_id,$date_interval);
   
   $sql="select count(*) as total from `Part Location Dimension` PLD  $where $wheref";
   //   print "$sql";
   
   $res = mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


     $total=$row['total'];
   }
   if($wheref==''){
       $filtered=0;
       $total_records=$total;
   }else{
     $sql="select  count(*) as total from `Part Location Dimension` PLD $where ";
     $res = mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

       $total_records=$row['total'];
       $filtered=$row['total']-$total;
     }

   }
   
   if($order=='sku')
     $order='PD.`Part SKU`';
   
   if($total_records==0)
     $rtext=_('No parts on this location');
   else
     $rtext=$total_records.' '.ngettext('part','parts',$total_records);
   
   if($total_records>$number_results)
    $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
   




   $sql=sprintf("select  * from `Part Location Dimension` PLD left join `Part Dimension` PD on (PD.`Part SKU`=PLD.`Part SKU`) left join `Location Dimension` LD on (LD.`Location Key`=PLD.`Location Key`)    $where $wheref    order by $order $order_direction  ");


  $adata=array();
  
  $res = mysql_query($sql);
 // print $sql;
  while($data=mysql_fetch_array($res, MYSQL_ASSOC)) {
    
    
    
    if($data['Part Current Stock']==0 or !is_numeric($data['Quantity On Hand'])){
      $move='';
    }else{
      if($data['Quantity On Hand']==0)
	$move='<img src="art/icons/package_come.png" alt="'._('Move').'" />';
      else
	$move='<img src="art/icons/package_go.png" alt="'._('Move').'" />';
      
    }
    


   
    $adata[]=array(
		   
		   'sku'=>sprintf('<a href="part.php?id=%d&edit_stock=1">SKU%05d</a>',$data['Part SKU'],$data['Part SKU'])
		   ,'part_sku'=>$data['Part SKU']
		   ,'location_key'=>$data['Location Key']
		   ,'location'=>$data['Location Code']

		   ,'description'=>$data['Part XHTML Description'].' ('.$data['Part XHTML Currently Used In'].')'
		   ,'qty'=>number($data['Quantity On Hand'])
		   ,'can_pick'=>($data['Can Pick']=='Yes'?_('Yes'):_('No'))
		   ,'move'=>$move
		   ,'audit'=>'<img src="art/icons/page_white_edit.png" alt="'._('Audit').'" />'
		   ,'lost'=>($data['Quantity On Hand']==0?'':'<img src="art/icons/package_delete.png" alt="'._('Set stock as damaged/lost').'" />')
		   ,'delete'=>($data['Quantity On Hand']==0?'<img src="art/icons/cross.png"  alt="'._('Free location').'" />':'')
		   ,'number_locations'=>$data['Part Distinct Locations']
		   ,'number_qty'=>$data['Quantity On Hand']
		   ,'part_stock'=>$data['Part Current Stock']
		   );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'rtext'=>$rtext,
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
function list_warehouses(){
 
    

      $conf=$_SESSION['state']['warehouses']['table'];
     
      $conf_table='warehouses';


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
      
      
    

      
      
      $_SESSION['state'][$conf_table]['table']=array(
      'order'=>$order,
      'order_dir'=>$order_direction,
      'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
     
      
      
   
      $filter_msg='';
      $wheref='';
      if($f_field=='name' and $f_value!='')
	$wheref.=" and  `Warehouse Name` like '".addslashes($f_value)."%'";
      if($f_field=='code' and $f_value!='')
	$wheref.=" and  `Warehouse Code` like '".addslashes($f_value)."%'";
   
   

   
   
   $sql="select count(*) as total from `Warehouse Dimension`   $where $wheref";
   $res = mysql_query($sql); 
   if($row=mysql_fetch_array($res)) {
     $total=$row['total'];
   }
   mysql_free_result($res);
   if($wheref==''){
     $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total `Warehouse Dimension`   $where ";
     
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      	$total_records=$row['total'];
	$filtered=$total_records-$total;
	mysql_free_result($result);
     }

   }

 $rtext=$total_records." ".ngettext('warehouse','warehouses',$total_records);
  if($total_records>$number_results)
    $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
  else
    $rtext_rpp=' ('._('Showing all').')';
   $_dir=$order_direction;
   $_order=$order;
   
   $order='`Warehouse Code`';
   if($order=='name')
     $order='`Warehouse Name`';
   elseif($order=='code')
     $order='`Warehouse Code`';
 

 
   $sql="select *  from `Warehouse Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
   // print $sql;
   $res = mysql_query($sql);
   $adata=array();
   
   $sum_active=0;
   
   
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $code=sprintf('<a href="warehouse.php?id=%d">%s</a>',$row['Warehouse Key'],$row['Warehouse Code']);
     $name=sprintf('<a href="warehouse.php?id=%d">%s</a>',$row['Warehouse Key'],$row['Warehouse Name']);
     //$locations=number($row['Locations']);
   
    $adata[]=array(
            'id'=>$row['Warehouse Key'],
		   'code'=>$code,
		   'name'=>$name,
		   //'locations'=>$locations,
		   //'description'=>$row['Warehouse Area Description']
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

function warehouse_stock_history() {
    $conf=$_SESSION['state']['warehouse_stock_history']['table'];
    $warehouse_key=$_SESSION['state']['warehouse']['id'];
    if (isset( $_REQUEST['elements']))
        $elements=$_REQUEST['elements'];
    else
        $elements=$conf['elements'];

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];

    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];
    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


  if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];




    list($date_interval,$error)=prepare_mysql_dates($from,$to);
    if ($error) {
        list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
    } else {
        $_SESSION['state']['warehouse_stock_history']['table']['from']=$from;
        $_SESSION['state']['warehouse_stock_history']['table']['to']=$to;
    }

    $_SESSION['state']['warehouse_stock_history']['table']=
        array(
            'order'=>$order,
            'type'=>$type,
            'order_dir'=>$order_direction,
            'nr'=>$number_results,
            'sf'=>$start_from,
            'where'=>$where,
            'f_field'=>$f_field,
            'f_value'=>$f_value,
            'from'=>$from,
            'to'=>$to,
            'elements'=>$elements,
            'f_show'=>$_SESSION['state']['warehouse_stock_history']['table']['f_show']
        );
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';

    $wheref='';





   switch ($type) {
        case 'month':
           $group=' group by DATE_FORMAT(%Y%m)   ';
            break;
             case 'day':
              $group=' group by `Date`   ';
            break;
        default:
             $group=' group by YEARWEEK(`Date`)   ';
            break;
    }    




    $where=$where.sprintf(" and `Warehouse Key`=%d ",$warehouse_key);
    $sql="select count(*) as total from `Inventory Spanshot Fact`     $where $wheref $group";
 
    $result=mysql_query($sql);
    $total=mysql_num_rows($result);
    
    
    
    
   
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Inventory Spanshot Fact`   $where  $group";
        
     

$total_records=$result;
$filtered=$total_records-$total;

    }




    switch ($type) {
        case 'month':
               $rtext=$total_records.' '.ngettext('months','month',$total);
            break;
             case 'day':
               $rtext=$total_records.' '.ngettext('days','days',$total);
            break;
        default:
             $rtext=$total_records.' '.ngettext('week','weeks',$total);
            break;
    }    
  
    
    
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';



    if ($total_records==0) {
        $rtext=_('No stock history');
        $rtext_rpp='';
    }


$order='`Date`';

    $sql=sprintf("select  GROUP_CONCAT(distinct '<a href=\"location.php?id=',ISF.`Location Key`,'\">',`Location Code`,'<a/>') as locations,`Date`, ( select  sum(`Quantity On Hand`) from `Inventory Spanshot Fact` OISF where `Part SKU`=%d and OISF.`Date`=ISF.`Date`  )as `Quantity On Hand`, ( select  sum(`Value At Cost`) from `Inventory Spanshot Fact` OISF where `Part SKU`=%d and OISF.`Date`=ISF.`Date`  )as `Value At Cost`,sum(`Sold Amount`) as `Sold Amount`,sum(`Value Comercial`) as `Value Comercial`,sum(`Storing Cost`) as `Storing Cost`,sum(`Quantity Sold`) as `Quantity Sold`,sum(`Quantity In`) as `Quantity In`,sum(`Quantity Lost`) as `Quantity Lost`  from `Inventory Spanshot Fact` ISF left join `Location Dimension` L on (ISF.`Location key`=L.`Location key`)  $where $wheref   $group order by $order $order_direction  limit $start_from,$number_results "
     ,$warehouse_key
    ,$warehouse_key
    );


   
    $result=mysql_query($sql);
    $adata=array();
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


        $adata[]=array(

                     'date'=>strftime("%A %d/%m/%Y", strtotime($data['Date']))
                            ,'locations'=>$data['locations']
                                         ,'quantity'=>number($data['Quantity On Hand'])
                                                     ,'value'=>money($data['Value At Cost'])
                                                              ,'sold_qty'=>number($data['Quantity Sold'])
                                                                          ,'in_qty'=>number($data['Quantity In'])
                                                                                    ,'lost_qty'=>number($data['Quantity Lost'])
                 );
    }

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

function list_part_categories() {
   $conf=$_SESSION['state']['part_categories']['subcategories'];
    $conf2=$_SESSION['state']['part_categories'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
        $number_results=$conf['nr'];

    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['exchange_type'])) {
        $exchange_type=addslashes($_REQUEST['exchange_type']);
        $_SESSION['state']['part_categories']['exchange_type']=$exchange_type;
    } else
        $exchange_type=$conf2['exchange_type'];

    if (isset( $_REQUEST['exchange_value'])) {
        $exchange_value=addslashes($_REQUEST['exchange_value']);
        $_SESSION['state']['part_categories']['exchange_value']=$exchange_value;
    } else
        $exchange_value=$conf2['exchange_value'];

    if (isset( $_REQUEST['show_default_currency'])) {
        $show_default_currency=addslashes($_REQUEST['show_default_currency']);
        $_SESSION['state']['part_categories']['show_default_currency']=$show_default_currency;
    } else
        $show_default_currency=$conf2['show_default_currency'];




    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
        $_SESSION['state']['part_categories']['percentages']=$percentages;
    } else
        $percentages=$_SESSION['state']['part_categories']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
        $_SESSION['state']['part_categories']['period']=$period;
    } else
        $period=$_SESSION['state']['part_categories']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
        $_SESSION['state']['part_categories']['avg']=$avg;
    } else
        $avg=$_SESSION['state']['part_categories']['avg'];

    if (isset( $_REQUEST['stores_mode'])) {
        $stores_mode=$_REQUEST['stores_mode'];
        $_SESSION['state']['part_categories']['stores_mode']=$stores_mode;
    } else
        $stores_mode=$_SESSION['state']['part_categories']['stores_mode'];

    $_SESSION['state']['part_categories']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);

    if (isset( $_REQUEST['category'])) {
        $root_category=$_REQUEST['category'];
        $_SESSION['state']['part_categories']['category']=$avg;
    } else
        $root_category=$_SESSION['state']['part_categories']['category_key'];



    $store_key=$_SESSION['state']['store']['id'];

    $where=sprintf("where `Category Subject`='Part' and  `Category Parent Key`=%d",$root_category);
    //  $where=sprintf("where `Category Subject`='Product'  ");

    if ($stores_mode=='grouped')
        $group=' group by S.`Category Key`';
    else
        $group='';

    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Category Name` like '%".addslashes($f_value)."%'";




    $sql="select count(*) as total   from `Category Dimension`   $where $wheref";

//$sql=" describe `Category Dimension`;";
// $sql="select *  from `Category Dimension` where `Category Parent Key`=1 ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $total=$row['total'];
//   print_r($row);
    }
    mysql_free_result($res);

//exit;
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Category Dimension` S  left join `Category Bridge` CB on (CB.`Category Key`=S.`Category Key`)  left join `Customer Dimension` CD on (CD.`Customer Key`=CB.`Subject Key`) $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('category','categories',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

    if ($order=='families')
        $order='`Product Category Families`';
    elseif($order=='departments')
    $order='`Product Category Departments`';
    elseif($order=='code')
    $order='`Product Category Code`';
    elseif($order=='todo')
    $order='`Product Category In Process Products`';
    elseif($order=='discontinued')
    $order='`Product Category In Process Products`';
    else if ($order=='profit') {
        if ($period=='all')
            $order='`Product Category Total Profit`';
        elseif($period=='year')
        $order='`Product Category 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Product Category 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Product Category 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Product Category 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Product Category Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Product Category 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Product Category 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Product Category 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Product Category 1 Week Acc Invoiced Amount`';

    }
    elseif($order=='name')
    $order='`Category Name`';
    elseif($order=='active')
    $order='`Product Category For Public Sale Products`';
    elseif($order=='outofstock')
    $order='`Product Category Out Of Stock Products`';
    elseif($order=='stock_error')
    $order='`Product Category Unknown Stock Products`';
    elseif($order=='surplus')
    $order='`Product Category Surplus Availability Products`';
    elseif($order=='optimal')
    $order='`Product Category Optimal Availability Products`';
    elseif($order=='low')
    $order='`Product Category Low Availability Products`';
    elseif($order=='critical')
    $order='`Product Category Critical Availability Products`';





    $sql="select S.`Category Key`, `Category Name` from `Category Dimension` S  left join `Category Bridge` CB on (CB.`Category Key`=S.`Category Key`)  left join `Part Dimension` PD on (PD.`Part SKU`=CB.`Subject Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";
// print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);
    $adata=array();
    $sum_sales=0;
    $sum_profit=0;
    $sum_outofstock=0;
    $sum_low=0;
    $sum_optimal=0;
    $sum_critical=0;
    $sum_surplus=0;
    $sum_unknown=0;
    $sum_departments=0;
    $sum_families=0;
    $sum_todo=0;
    $sum_discontinued=0;

    $DC_tag='';
    if ($exchange_type=='day2day' and $show_default_currency  )
        $DC_tag=' DC';

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        //$name=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Category Key'],$row['Product Category Name']);
        //$code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Category Key'],$row['Product Category Code']);



        if ($stores_mode=='grouped')
            $name=sprintf('<a href="part_categories.php?id=%d">%s</a>',$row['Category Key'],$row['Category Name']);
        else
            $name=$row['Category Key'].' '.$row['Category Name']." (".$row['Category Store Key'].")";
        $adata[]=array(
                     //'go'=>sprintf("<a href='edit_category.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Category Key']),
                     'id'=>$row['Category Key'],
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
