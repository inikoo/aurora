<?php
/*
 File: Site.php 

 This file contains the Site Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2010, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');

class Site extends DB_Table{
 
  var $new=false;
  
  function Site($arg1=false,$arg2=false) {
  $this->table_name='Site';
  $this->ignore_fields=array('Site Key');


  if(!$arg1 and !$arg2){
    $this->error=true;
    $this->msg='No arguments';
  }
  if(is_numeric($arg1)){
    $this->get_data('id',$arg1);
    return;
  }
    


     if(is_array($arg2) and preg_match('/create|new/i',$arg1)){
       $this->find($arg2,$arg3.' create');
       return;
     }
  
     
     $this->get_data($arg1,$arg2);

  }


  function get_data($tipo,$tag){
   

   $sql=sprintf("select * from `Site Dimension` where  `Site Key`=%d",$tag);
   
    $result =mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Site Key'];
    $this->data['Site Logo Data']=unserialize($this->data['Site Logo Data']);
$this->data['Site Logo Data']=unserialize($this->data['Site Logo Data']);
$this->data['Site Header Data']=unserialize($this->data['Site Header Data']);
$this->data['Site Footer Data']=unserialize($this->data['Site Footer Data']);
$this->data['Site Layout Data']=unserialize($this->data['Site Layout Data']);

}
    
  
}


function create($raw_data){


  
  
  
    $data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$data))
	$data[$key]=_trim($value);
     
      
    }

  
  
  $keys='(';
  $values='values(';
  foreach($data as $key=>$value) {
    $keys.="`$key`,";
    if (preg_match('/ Data$/i',$key))
	  $values.="'".serialize($value)."',";
    else
      $values.=prepare_mysql($value).",";
  }
  $keys=preg_replace('/,$/',')',$keys);
  $values=preg_replace('/,$/',')',$values);
  $sql=sprintf("insert into `Site Dimension` %s %s",$keys,$values);
  
  
  if (mysql_query($sql)) {
	$this->id=mysql_insert_id();
	$this->get_data('id',$this->id);

	
	
	
	
	
      }else{
	$this->error=true;$this->msg='Can not insert Site Dimension';
	exit("$sql\n");
      }
     
     
  }








 function get($key){
   
  
   
    switch($key){
   
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
    }
    return false;
 }










 function update_field_switcher($field,$value,$options=''){


   switch($field){

   default:
   $base_data=$this->base_data();
   if(array_key_exists($field,$base_data)) {
     
     if ($value!=$this->data[$field]) {
       
       $this->update_field($field,$value,$options);
     }
   }
   
   }

   
   
 }









}
?>
