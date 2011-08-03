<?php
/*
 File: Warehouse.php 

 This file contains the Warehouse Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.WarehouseArea.php');
include_once('class.Location.php');

class Warehouse extends DB_Table{

  var $areas=false;
  var $locations=false;
  
  function Warehouse($a1,$a2=false,$a3=false) {

    $this->table_name='Warehouse';
    $this->ignore_fields=array('Warehouse Key');

    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }elseif($a1=='find'){
      $this->find($a2,$a3);
      
    }else
       $this->get_data($a1,$a2);
  }


  function get_data($key,$tag){
    
    if($key=='id')
      $sql=sprintf("select `Warehouse Key`,`Warehouse Code`,`Warehouse Name`,`Address Key`,`Warehouse Total Area` from `Warehouse Dimension` where `Warehouse Key`=%d",$tag);
    else if($key=='code')
      $sql=sprintf("select `Warehouse Key`,`Warehouse Code`,`Warehouse Name`,`Address Key`,`Warehouse Total Area`  from `Warehouse Dimension` where `Warehouse Code`=%s ",prepare_mysql($tag));
    else if($key=='name')
      $sql=sprintf("select `Warehouse Key`,`Warehouse Code`,`Warehouse Name`,`Address Key`,`Warehouse Total Area`  from `Warehouse Dimension` where `Warehouse Name`=%s ",prepare_mysql($tag));
    
    else
      return;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Warehouse Key'];
    }
      



  }
 
  function find($raw_data,$options){
  if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
      }
    }
    
    $this->found=false;
    $this->found_key=false;

    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }

    $data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$data))
	$data[$key]=_trim($value);
    }
    

    //    print_r($raw_data);

    if($data['Warehouse Code']=='' ){
      $this->error=true;
      $this->msg='Warehouse code empty';
      return;
    }

    if($data['Warehouse Name']=='')
      $data['Warehouse Name']=$data['Warehouse Code'];
    

    $sql=sprintf("select `Warehouse Key` from `Warehouse Dimension` where `Warehouse Code`=%s  "
		 ,prepare_mysql($data['Warehouse Code'])
		 ); 

    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->found=true;
      $this->found_key=$row['Warehouse Key'];
    }
   
   
    if($create and !$this->found){
      $this->create($data);
      return;
    }
    if($this->found)
      $this->get_data('id',$this->found_key);
    
    if($update and $this->found){

    }


  }

function create($data){
   $this->new=false;
   $base_data=$this->base_data();
  
    foreach($data as $key=>$value){
      if(array_key_exists($key,$base_data))
	$base_data[$key]=_trim($value);
    }

      $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Warehouse Dimension` %s %s",$keys,$values);
    
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->msg=_("Warehouse Added");
      $this->get_data('id',$this->id);
   $this->new=true;
   
   $sql="insert into `User Right Scope Bridge` values(1,'Warehouse',".$this->id.");";
      mysql_query($sql);
   
   return;
 }else{
   $this->msg=_(" Error can not create warehouse");
 }
}
 



  function xupdate($data){
    foreach($data as $key =>$value)
      switch($key){
      case('name'):
	$name=_trim($value);
	
	if($name==''){
	  $this->msg=_('Wrong warehouse name');
	  $this->update_ok=false;
	  return;
	}

	if($name==$this->get($tipo)){
	  $this->msg=_('Nothing to change');
	  $this->update_ok=false;
	  return;
	}

	$location=new Warehouse('name',$value);
	if($location->id){
	  $this->msg=_('Another ware house has the same name');
	  $this->update_ok=false;
	  return;
	}
	$this->data['name']=$name;
	$this->msg=_('Warehouse name changed');
	$this->update_ok=true;
	break;
      }
    
    
  }


  function load($key=''){
    switch($key){
    case('areas'):
      $sql=sprintf("select * from `Warehouse Area Dimension` where `Warehouse Key`=%d ",$this->id);
      
      $result =mysql_query($sql);
      $this->areas=array();
      while($row=mysql_fetch_array($result)){
 	$this->areas[$row['id']]=array(
 				       'id'=>$row['`Warehouse Area Key`'],
				       'code'=>$row['Warehouse Area Code'],
 				       );
      }
      break;

    }
      

  }


  function get($key,$data=false){
    switch($key){
    case('num_areas'):
    case('number_areas'):
      if(!$this->areas)
	$this->load('areas');
      return count($this->areas);
      break;
    case('areas'):
      if(!$this->areas)
	$this->load('areas');
      return $this->areas;
      break;
    case('area'):
      if(!$this->areas)
	$this->load('areas');
      if(isset($this->areas[$data['id']]))
	return $this->areas[$data['id']];
      break;
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
    }
    return '';
  } 
    

 function add_area($data){
   // print_r($data);
    $this->new_area=false;
    $data['Warehouse Key']=$this->id;
    $area= new WarehouseArea('find',$data,'create');
    $this->new_area_msg=$area->msg;
    if($area->new)
      $this->new_area=true;

  }
  
    function update_children(){
   $sql=sprintf('select count(*) as number from `Location Dimension` where `Location Warehouse Key`=%d',$this->id);
        $res=mysql_query($sql);
        $number_locations=0;
        if ($row=mysql_fetch_array($res)) {
            $number_locations=$row['number'];
        }

 $sql=sprintf('select count(*) as number from `Shelf Dimension` where `Shelf Warehouse Key`=%d',$this->id);
        $res=mysql_query($sql);
        $number_shelfs=0;
        if ($row=mysql_fetch_array($res)) {
            $number_shelfs=$row['number'];
        }

 $sql=sprintf('select count(*) as number from `Warehouse Area Dimension` where `Warehouse Key`=%d',$this->id);
        $res=mysql_query($sql);
        $number_areas=0;
        if ($row=mysql_fetch_array($res)) {
            $number_areas=$row['number'];
        }


        $sql=sprintf('update `Warehouse Dimension` set `Warehouse Number Locations`=%d ,`Warehouse Number Shelfs`=%d  ,`Warehouse Number Areas`=%d  where `Warehouse Key`=%d'
        ,$number_locations
        ,$number_shelfs
        ,$number_areas
        ,$this->id
        );
        mysql_query($sql);
        $this->get_data('id',$this->id);
  }
  
  
}

?>