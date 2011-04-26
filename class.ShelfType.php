<?php
/*
 File: Shelftype.php 

 This file contains the Shelf Type Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Warehouse.php');
include_once('class.WarehouseArea.php');

include_once('class.Location.php');


class ShelfType extends DB_Table{
  

  function ShelfType($arg1=false,$arg2=false,$arg3=false) {

    $this->table_name='Shelf Type';
    $this->ignore_fields=array('Shelf Type Key');

     if(preg_match('/^(new|create)$/i',$arg1) and is_array($arg2)){
       $this->create($arg2);
       return;
     }
     if(preg_match('/find/i',$arg1)){
       $this->find($arg2,$arg3);
       return;
     }
     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return;
     }
     $this->get_data($arg1,$arg2);
  }

  /*
   Method: find
   Find W Area with similar data
  */   
  
  function find($raw_data,$options){
  
    if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
		    
      }
    }
   
    
    $this->found=false;
    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }
    //  print_r($raw_data);
    $data=$this->base_data();
    foreach($raw_data as $key=>$val){
      $_key=$key;
      $data[$_key]=$val;
    }
    
    
   
    $sql=sprintf("select `Shelf Type Key` from `Shelf Type Dimension` where  `Shelf Type Name`=%s"
		 ,prepare_mysql($data['Shelf Type Name']));
    
    // print $sql;
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
      $this->found=true;
      $this->found_key=$row['Shelf Type Key'];
    }

    //what to do if found
    if($this->found){
      $this->get_data('id',$this->found_key);
    }
      

    if($create){
      if($this->found){
	$this->update($raw_data,$options);
      }else{

	$this->create($data,$options);

      }


    }
  }


    function create ($data,$options=''){



      $this->data=$this->base_data();
      foreach($data as $key=>$value){
	if(array_key_exists($key,$this->data))
	  $this->data[$key]=_trim($value);
      }

      if(!is_numeric($this->data['Shelf Type Rows']) or $this->data['Shelf Type Rows']<1 ){
	$this->data['Shelf Type Rows']=1;
      }
      if(!is_numeric($this->data['Shelf Type Columns']) or $this->data['Shelf Type Columns']<1 ){
	$this->data['Shelf Type Columns']=1;
      }



      if($this->data['Shelf Type Location Height']!=''){
	list($this->data['Shelf Type Location Height'],$dump)=parse_distance($this->data['Shelf Type Location Height']);
      }
       if($this->data['Shelf Type Location Length']!=''){
	list($this->data['Shelf Type Location Length'],$dump)=parse_distance($this->data['Shelf Type Location Length']);
      }
       if($this->data['Shelf Type Location Deep']!=''){
	list($this->data['Shelf Type Location Deep'],$dump)=parse_distance($this->data['Shelf Type Location Deep']);
      }
       if($this->data['Shelf Type Location Max Weight']!=''){
	list($this->data['Shelf Type Location Max Weight'],$dump)=parse_weight($this->data['Shelf Type Location Max Weight']);
      }
       if($this->data['Shelf Type Location Max Weight']!=''){
	list($this->data['Shelf Type Location Max Weight'],$dump)=parse_weight($this->data['Shelf Type Location Max Weight']);
      }

      
      if($this->data['Shelf Type Name']==''){
	$this->msg=('Shelf type name is a requeried value');
	$this->new=false;
	$this->error=true;
	return;
      }




     /*  $warehouse=new Warehouse('id',$this->data['Warehouse Key']); */
/*       if(!$warehouse->id){ */
/* 	$this->msg=('Wrong warehouse key'); */
/* 	$this->new=false; */
/* 	$this->error=true; */
/* 	return; */

/*       } */
      // print_r($this->data);

      $keys='(';$values='values(';
      foreach($this->data as $key=>$value){
	$keys.="`$key`,";
	$_mode=true;
	if($key=='Shelf Type Description')
	  $_mode=false;
	$values.=prepare_mysql($value,$_mode).",";
      }
      
      $keys=preg_replace('/,$/',')',$keys);
      $values=preg_replace('/,$/',')',$values);
      
      $sql=sprintf("insert into `Shelf Type Dimension` %s %s",$keys,$values);
      //print "$sql\n";
      // exit;
      if(mysql_query($sql)){
	$this->id= mysql_insert_id();
	$this->new=true;
	$this->get_data('id',$this->id);
	$note=_('Shelf Type Created');
	$details=_('Shelf Type')." ".$this->data['Shelf Type Name']." "._('created');
	
	
      }else{
	exit($sql);
      }
      
    }

  function get_data($key,$tag){
    // print "K: $key";
    if($key=='id')
      $sql=sprintf("select * from `Shelf Type Dimension` where `Shelf Type Key`=%d",$tag);
    else if($key=='name')
      $sql=sprintf("select  *  from `Shelf Type Dimension` where `Shelf Type Name`=%s ",prepare_mysql($tag));
    else
      return;

    //print $sql;

    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Shelf Type Key'];
    }
  }
  
 


  function load($key=''){
    switch($key){
    case('locations'):
      
      break;

    }
      

  }


  function get($key,$data=false){
    switch($key){
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
    }
    return '';
  } 
 

 

  
     
}

?>