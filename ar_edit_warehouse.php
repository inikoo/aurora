<?php
require_once 'common.php';

require_once 'class.Warehouse.php';
require_once 'class.WarehouseArea.php';


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
	      );


$tipo=$_REQUEST['tipo'];
switch($tipo){
case('new_area'):
  new_warehouse_area();
  break;
case('edit_warehouse_areas'):
  list_warehouse_areas_for_edition();
  break;
 case('edit_warehouse_area'):
   update_warehouse_area();
   break;
 default:

   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
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


?>