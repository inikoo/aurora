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
case('location_stock_history'):
  history_stock_location();
  break;
case('parts_at_location'):
  parts_at_location();
  break;
case('find_warehouse_area'):
  find_warehouse_area();
  break;
case('find_location'):
  find_location();
  break;
case('locations'):
  list_location();
  break;
case('warehouse_areas'):
  list_warehouse_area();
  break;
default:

   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 
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
    $order='`Warehouse Area Code`';

  $data=array();
  $sql="select * from `Location Dimension` left join `Warehouse Area Dimension` WAD on (`Location Warehouse Area Key`=WAD.`Warehouse Area Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
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
      $area=$row['Warehouse Area Code'];
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
   

      $conf=$_SESSION['state']['warehouse']['warehouse_area'];
      //  $conf2=$_SESSION['state']['warehouse_area'];
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
     $locations=number($row['Locations']);
   
    $adata[]=array(
		   'code'=>$code,
		   'name'=>$name,
		   'locations'=>$locations,
		   
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

function find_warehouse_area(){
  if(!isset($_REQUEST['query']))
    $q='';
  else
    $q=$_REQUEST['query'];
  $where='';
  if(isset($_REQUEST['parent']) and $_REQUEST['parent']=='warehouse')
    $where=sprintf(' and `Warehouse Key`=%d ',$_SESSION['state']['warehouse']['id']);

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
     $sql=sprintf("select LD.`Location Key`,`Location Code`,IFNULL(`Quantity On Hand`,0) as `Quantity On Hand` from `Location Dimension` LD left join `Part Location Dimension` PLD on (PLD.`Location Key`=LD.`Location Key`)   where (`Location Code` like '%s%%' )  %s  order by `Location Code` limit 10 "
		 ,addslashes($q)
		 ,$where
		 );

   }else{
    $sql=sprintf("select `Location Key`,`Location Code`,0 as `Quantity On Hand` from `Location Dimension` LD where (`Location Code` like '%s%%'    )  %s  order by `Location Code` limit 10 "
		 ,addslashes($q)
		 ,$where
		 );
   }
   //print $sql;
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
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
   



   $sql=sprintf("select  *,IFNULL(`User Key`,-1) as user from `Inventory Transaction Fact`  $where $wheref order by $order $order_direction limit $start_from,$number_results ");
   // print $sql;
  $result=mysql_query($sql);
  $adata=array();
  while($data=mysql_fetch_array($result, MYSQL_ASSOC)){
    
    if($data['user']=-1)
      $author=_('Unknown');
    elseif($data['user']=0)
      $author=_('System');
    else
      $author=$data['user'];
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
		   ,'date'=>strftime("%a %e %b %Y %T", strtotime($data['Date'])),
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

		   'sku'=>sprintf('<a href="part_manage_stock.php?id=%d">%s</a>',$data['Part SKU'],$data['Part SKU'])
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
  
  

  $where=$where.sprintf(" and `Location Key`=%d ",$location_id);

   
  //   $where =$where.$view.sprintf(' and part_id=%d  %s',$part_id,$date_interval);
   
   $sql="select count(*) as total from `Part Location Dimension`   $where $wheref";
   //   print "$sql";
   
   $res = mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


     $total=$row['total'];
   }
   if($wheref==''){
       $filtered=0;
       $total_records=$total;
   }else{
     $sql="select  count(*) as total from `Part Location Dimension`  $where ";
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
   




   $sql=sprintf("select  * from `Part Location Dimension` PLD left join `Part Dimension` PD on (PD.`Part SKU`=PLD.`Part SKU`)    $where $wheref    order by $order $order_direction  ");


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
		   
		   'sku'=>sprintf('<a href="part_manage_stock.php?id=%d">SKU%05d</a>',$data['Part SKU'],$data['Part SKU'])
		   ,'part_sku'=>$data['Part SKU']
		   ,'location_key'=>$data['Location Key']

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


?>