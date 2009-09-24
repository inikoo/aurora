<?php
require_once 'common.php';

require_once 'class.Warehouse.php';
require_once 'class.WarehouseArea.php';
require_once 'class.PartLocation.php';


if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }
$editor=array(
	      'Author Name'=>$user->data['User Alias'],
	      'Author Type'=>$user->data['User Type'],
	      'Author Key'=>$user->data['User Parent Key'],
	      'User Key'=>$user->id
	      ,'Date'=>date('Y-m-d H:i:s')
	      );


$tipo=$_REQUEST['tipo'];
switch($tipo){
case('add_part_to_location'):
  add_part_to_location();
  break;
case('new_area'):
  new_warehouse_area();
  break;
case('new_location'):
  new_location();
  break;
case('edit_warehouse_areas'):
  list_warehouse_areas_for_edition();
  break;
case('edit_locations'):
  list_locations_for_edition();
  break;
case('edit_warehouse_area'):
  update_warehouse_area();
  break;
case('edit_part_location'):
  update_part_location();
  break;
case('delete_area'):
  delete_warehouse_area();
  break;
case('delete_location'):
  delete_location();
  break;
case('delete_part_location'):
  delete_part_location();
  break;
case('delete_warehouse'):
  delete_warehouse();
  break;
case('lost_stock'):
  lost_stock();
  break;
 default:

   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }

function update_part_location(){
  global $editor;

   if(
     !isset($_REQUEST['location_key'])
     or !isset($_REQUEST['part_sku'])
     or !isset($_REQUEST['key'])
     or !isset($_REQUEST['newvalue'])
     ){
    $response=array('state'=>400,'action'=>'error','msg'=>'');
    echo json_encode($response);
     return;
  }
  

  $part_sku=$_REQUEST['part_sku'];
  $location_key=$_REQUEST['location_key'];
  $new_value=stripslashes(urldecode($_REQUEST['newvalue']));
  $traslator=array(
		   'qty'=>'Quantity On Hand',
		   'can_pick'=>'Can Pick',
		   );
  if(array_key_exists($_REQUEST['key'],$traslator)){
    $key=$traslator[$_REQUEST['key']];
  }else{
    $response=array('state'=>400,'action'=>'error','msg'=>'Unknown key '.$_REQUEST['key']);
    echo json_encode($response);
    return;
  }    
  


  $part_location=new PartLocation($part_sku,$location_key);
  if(!$part_location->ok){
    $response=array('state'=>400,'action'=>'error','msg'=>'');
    echo json_encode($response);
    return;
  }
  $data=array($key=>$new_value);
  $part_location->editor=$editor;
  $part_location->update($data);
  
  if($part_location->updated){
    $response=array('state'=>200,'action'=>'updates','msg'=>$part_location->msg,'newvalue'=>$part_location->data[$key],'stock'=>$part_location->part->get('Part Current Stock'));
     echo json_encode($response);
     return;
  }else{
    $response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
    echo json_encode($response);
     return;

  }
  

}

function update_warehouse_area(){
  
  if(
     !isset($_REQUEST['wa_key'])
     or !isset($_REQUEST['key'])
     or !isset($_REQUEST['newvalue'])
     ){
    $response=array('state'=>400,'action'=>'error','msg'=>'');
    echo json_encode($response);
     return;
  }
    
  
  $wa_key=$_REQUEST['wa_key'];
 
  $new_value=stripslashes(urldecode($_REQUEST['newvalue']));

  $traslator=array(
		   'code'=>'Warehouse Area Code',
		   'name'=>'Warehouse Area Name',
		   'description'=>'Warehouse Area Description'
		   );
  if(array_key_exists($_REQUEST['key'],$traslator)){
    $key=$traslator[$_REQUEST['key']];
  }else{
    $response=array('state'=>400,'action'=>'error','msg'=>'Unknown key '.$_REQUEST['key']);
    echo json_encode($response);
    return;
  }    


  $wa=new WarehouseArea($wa_key);
  if(!$wa->id){
    $response=array('state'=>400,'action'=>'error','msg'=>'');
    echo json_encode($response);
     return;
  }
  
  $wa->update(array($key=>$new_value));
  if($wa->updated){
    $response=array('state'=>200,'action'=>'updates','msg'=>$wa->msg,'newvalue'=>$wa->data[$key]);
     echo json_encode($response);
     return;
  }else{
    $response=array('state'=>400,'action'=>'nochange','msg'=>$wa->msg);
    echo json_encode($response);
     return;

  }
}

function list_warehouse_areas_for_edition(){
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
    
    $adata[]=array(
		   'wa_key'=>$row['Warehouse Area Key'],
		   'code'=>$row['Warehouse Area Code'],
		   'name'=>$row['Warehouse Area Name'],
		   'description'=>$row['Warehouse Area Description'],

		   
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

function new_location(){
global $editor;

if( !isset($_REQUEST['values']) ){
    $response=array('state'=>400,'msg'=>'Error no value');
    echo json_encode($response);
    return;
   }
   
   $tmp=preg_replace('/\\\"/','"',$_REQUEST['values']);
   $tmp=preg_replace('/\\\\\"/','"',$tmp);
   
   $raw_data=json_decode($tmp, true);
   if(!is_array($raw_data)){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }

   if(!isset($raw_data['Location Warehouse Area Key'])  or !is_numeric($raw_data['Location Warehouse Area Key']) ){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }



   print_r($raw_data);

   $warehouse_area=new WarehouseArea($raw_data['Location Warehouse Area Key']);
   
   if(!$warehouse_area->id){
     $response=array('state'=>400,'msg'=>'Wrong Warehouse Area');
     echo json_encode($response);
     return;
   }
	

   $raw_data['editor']=$editor;
 
   $warehouse_area->add_location($raw_data);
   if($warehouse_area->new_area){
     $response=array(
		     'state'=>200
		     ,'action'=>'created'
		     ,'msg'=>_('Location added to Warehouse Area')
		     );
     echo json_encode($response);
     return;
     
   }else{
     $response=array('state'=>200,'action'=>'nochange','msg'=>$warehouse_area->new_location_msg);
     echo json_encode($response);
     return;

   }
}

function new_warehouse_area(){
global $editor;

if( !isset($_REQUEST['values']) ){
    $response=array('state'=>400,'msg'=>'Error no value');
    echo json_encode($response);
    return;
   }
   
   $tmp=preg_replace('/\\\"/','"',$_REQUEST['values']);
   $tmp=preg_replace('/\\\\\"/','"',$tmp);
   
   $raw_data=json_decode($tmp, true);
   // print_r($raw_data);
   if(!is_array($raw_data)){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }

   if(!isset($raw_data['Warehouse Key'])  or !is_numeric($raw_data['Warehouse Key']) ){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }
   $warehouse=new warehouse($raw_data['Warehouse Key']);
   
   if(!$warehouse->id){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }
	

   $raw_data['editor']=$editor;
 
   $warehouse->add_area($raw_data);
   if($warehouse->new_area){
     $response=array(
		     'state'=>200
		     ,'action'=>'created'
		     ,'msg'=>_('Area added to Warehouse')
		     );
     echo json_encode($response);
     return;
     
   }else{
     $response=array('state'=>200,'action'=>'nochange','msg'=>$warehouse->new_area_msg);
     echo json_encode($response);
     return;

   }
}


function list_locations_for_edition(){
  if(isset( $_REQUEST['warehouse']) and  is_numeric( $_REQUEST['warehouse']))
    $warehouse_id=$_REQUEST['warehouse'];
  else
    $warehouse_id=$_SESSION['state']['warehouse']['id'];
  
   if(isset( $_REQUEST['warehouse_area']) and  is_numeric( $_REQUEST['warehouse_area']))
    $warehouse_area_id=$_REQUEST['warehouse_area'];
  else
    $warehouse_area_id=$_SESSION['state']['warehouse_area']['id'];




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
	$where=sprintf("where  `Location Warehouse Key`=%d",$warehouse_id);
	break;
      default:
	$where='where true';
	  
	  }   

      $filter_msg='';
      $wheref='';
      if($f_field=='area' and $f_value!='')
	$wheref.=" and  `Location Area Code` like '".addslashes($f_value)."%'";
      if($f_field=='code' and $f_value!='')
	$wheref.=" and  `Location Code` like '".addslashes($f_value)."%'";
   
   

   
   
   $sql="select count(*) as total from `Location Dimension`   $where $wheref";
   //  print $sql;
   $res = mysql_query($sql); 
   if($row=mysql_fetch_array($res)) {
     $total=$row['total'];
   }
   mysql_free_result($res);
   if($wheref==''){
     $filtered=0; $total_records=$total;
   }else{
     $sql="select count(*) as total `Location`   $where ";
     
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      	$total_records=$row['total'];
	$filtered=$total_records-$total;
	mysql_free_result($result);
     }

   }

 $rtext=$total_records." ".ngettext('location','locations',$total_records);
  if($total_records>$number_results)
    $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
  else
    $rtext_rpp=' ('._('Showing all').')';
   $_dir=$order_direction;
   $_order=$order;
   
   $order='`Location Code`';
   if($order=='used_for')
     $order='`Location Mainly Used For`';
   elseif($order=='code')
     $order='`Location Code`';
   elseif($order=='area')
     $order='`Location Area Code`';
   elseif($order=='max_slots')
     $order='`Location Max Slots`';
   elseif($order=='max_weight')
     $order='`Location Max Weight`';
  elseif($order=='max_vol')
     $order='`Location Max Volume`';

   $sql="select *  from `Location Dimension` left join `Warehouse Area Dimension` WAD on (`Location Warehouse Area Key`=WAD.`Warehouse Area Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

   

   // print $sql;
   $res = mysql_query($sql);
   $adata=array();
   
   $sum_active=0;
   
   
   while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
     $user_for=$row['Location Mainly Used For'];
     $area=$row['Warehouse Area Code'];
     $max_vol='';
     if($row['Location Max Volume']!='')
       $max_vol=number($row['Location Max Volume']).'L';
     $max_weight='';
     if($row['Location Max Weight']!='')
       $max_weight=number($row['Location Max Weight']).'Kg';
     
     $adata[]=array(
		    'location_key'=>$row['Location Key']
		    ,'code'=>$row['Location Code']
		    ,'used_for'=>$user_for
		    ,'max_slots'=>$row['Location Max Slots']
		    ,'area'=>$area
		    ,'max_vol'=>$max_vol
		    ,'max_weight'=>$max_weight
		    ,'delete'=>'<img alt="'._('Delete').'" src="art/icons/cross.png" />'
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


function delete_location(){
  if(!isset($_REQUEST['location_key'])){
    $response=array('state'=>400,'msg'=>'Error');
     echo json_encode($response);
     return;
  }
    
  $location_key=$_REQUEST['location_key'];
  $location=new Location($location_key);

  $location->delete();
  
  if($location->deleted){
    echo 'Ok';
    return;
    
  }else{
    echo $location->deleted_msg;
    return;
  }
  

}

function delete_part_location(){
  global $editor;
  if(!isset($_REQUEST['location_key'])
     or !isset($_REQUEST['part_sku'])
     ){
    $response=array('state'=>400,'msg'=>'Error 1');
     echo json_encode($response);
     return;
  }
    
  $location_key=$_REQUEST['location_key'];
  $part_sku=$_REQUEST['part_sku'];
  
  $part_location=new PartLocation($part_sku,$location_key);
  $part_location->editor=$editor;
  $part_location->delete();
  
  if($part_location->deleted){
    echo 'Ok';
    return;
    
  }else{
    echo $part_location->deleted_msg;
    return;
  }
  

}
function add_part_to_location(){
  global $editor;
  
  if(!isset($_REQUEST['location_key'])
     or !isset($_REQUEST['part_sku'])
     ){
    $response=array('state'=>400,'msg'=>'Error');
    echo json_encode($response);
    return;
  }
  
  $location_key=$_REQUEST['location_key'];
  $part_sku=$_REQUEST['part_sku'];
  $data=array(
	      'Location Key'=>$location_key
	      ,'Part SKU'=>$part_sku
	      ,'editor'=>$editor
	      );
  
  $part_location=new PartLocation('find',$data,'create');
  if($part_location->new){
    $response=array('state'=>200,'action'=>'added','msg'=>$part_location->msg);
     echo json_encode($response);
     return;
  }else{
    $response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
    echo json_encode($response);
    return;
  }
  


}


function lost_stock(){
  
  if(
     !isset($_REQUEST['location_key'])
     or !isset($_REQUEST['part_sku'])
     or !isset($_REQUEST['qty'])
     or !isset($_REQUEST['why'])
     or !isset($_REQUEST['action'])
     ){
    $response=array('state'=>400,'action'=>'error','msg'=>'');
    echo json_encode($response);
     return;
  }
    
  
  $location_key=$_REQUEST['location_key'];
  $part_sku=$_REQUEST['part_sku'];
  
  $new_value=stripslashes(urldecode($_REQUEST['newvalue']));

  $traslator=array(
		   'qty'=>'Quantity Lost',
		   'why'=>'Reason',
		   'action'=>'Action'
		   );
  
  foreach($_REQUEST as $key =>$value){
    if(array_key_exists($key,$traslator)){
      $data[$traslator[$key]]=$value;
    }    
    $part_location=new PartLocation($part_sku,$location_key);

 
   if(!$part_location->ok){
    $response=array('state'=>400,'action'=>'error','msg'=>'');
    echo json_encode($response);
    return;
  }
   $part_location->editor=$editor;
   $part_location->set_stock_as_lost($data);
   if($part_location->error){
     $response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
    echo json_encode($response);
     return;

   }else{
     $response=array('state'=>200,'action'=>'ok','msg'=>$part_location->msg,'qty'=>$part_location->data['Quantity On Hand'],'stock'=>$part_location->part->get('Part Current Stock'));
     echo json_encode($response);
     return;

   }
}

function move_stock(){
  
  if(
     !isset($_REQUEST['from_location_key'])
     or !isset($_REQUEST['to_location_key'])
     or !isset($_REQUEST['part_sku'])
     or !isset($_REQUEST['qty'])

     ){
    $response=array('state'=>400,'action'=>'error','msg'=>'');
    echo json_encode($response);
     return;
  }
    
  
  $location_key=$_REQUEST['from_location_key'];
  $part_sku=$_REQUEST['part_sku'];
  
  $new_value=stripslashes(urldecode($_REQUEST['newvalue']));

  $traslator=array(
		   'qty'=>'Quantity To Move',
		   'to_location_key'=>'Destination Key',
		   );
  
  foreach($_REQUEST as $key =>$value){
    if(array_key_exists($key,$traslator)){
      $data[$traslator[$key]]=$value;
    }    
    $part_location=new PartLocation($part_sku,$location_key);

 
   if(!$part_location->ok){
    $response=array('state'=>400,'action'=>'error','msg'=>'');
    echo json_encode($response);
    return;
  }
   $part_location->editor=$editor;
   $part_location->move_stock($data);
   if($part_location->error){
     $response=array('state'=>400,'action'=>'nochange','msg'=>$part_location->msg);
    echo json_encode($response);
     return;

   }else{
     $response=array('state'=>200,'action'=>'ok','msg'=>$part_location->msg,'qty'=>$part_location->data['Quantity On Hand'],'stock'=>$part_location->part->get('Part Current Stock'));
     echo json_encode($response);
     return;

   }
}


?>