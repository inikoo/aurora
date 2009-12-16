<?php
/*
 File: Page.php 

 This file contains the Page Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');

class Page extends DB_Table{
 
  var $new=false;
  
  function Page($arg1=false,$arg2=false,$arg3=false) {
  $this->table_name='Page';
  $this->ignore_fields=array('Page Key');


  if(!$arg1 and !$arg2){
    $this->error=true;
  }
  if(is_numeric($arg1)){
    $this->get_data('id',$arg1);
    return;
  }
     if(is_string($arg1) and !$arg2){
       $this->get_data('url',$arg1);
       return;
     }


     if(is_array($arg2) and preg_match('/create|new/i',$arg1)){
       $this->find($arg2,$arg3.' create');
       return;
     }
     if(  preg_match('/find/i',$arg1)){
       $this->find($arg2,$arg3);
       return;
     }
     
     $this->get_data($arg1,$arg2);

  }


  function get_data($tipo,$tag){
   
    if(preg_match('/url|address|www/i',$tipo))
      $sql=sprintf("select * from `Page Dimension` where  `Page URL`=%s",prepare_mysql($tag));
    else
      $sql=sprintf("select * from `Page Dimension` where  `Page Key`=%d",$tag);

   
    $result =mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Page Key'];
      $this->type=$this->data['Page Type'];

      if($this->type=='Shop'){
	$sql=sprintf("select * from `Page Shop Dimension` where  `Page Key`=%d",$this->id);
	 $result2 =mysql_query($sql);
	 if($row=mysql_fetch_array($result2, MYSQL_ASSOC)){
	   foreach($row as $key=>$value){
	     $this->data[$key]=$value;
	   }

	 }

      }elseif($this->type=='Internal'){
	$sql=sprintf("select * from `Page Internal Dimension` where  `Page Key`=%d",$this->id);
	 $result2 =mysql_query($sql);
	 if($row=mysql_fetch_array($result2, MYSQL_ASSOC)){
	   foreach($row as $key=>$value){
	     $this->data[$key]=$value;
	   }

	 }

      }


    }
  
}


function find($raw_data,$options){
   
    if(isset($raw_data['editor'])){
     foreach($raw_data['editor'] as $key=>$value){
       
       if(array_key_exists($key,$this->editor))
	 $this->editor[$key]=$value;
       
      }
   }


    $data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$data))
	$data[$key]=_trim($value);
     
      
    }

    $extra_data=array();
    if($data['Page Type']=='Internal'){
      $extra_data=$this->internal_base_data();
      foreach($raw_data as $key=>$value){
	if(array_key_exists($key,$extra_data))
	  $extra_data[$key]=_trim($value);
	   }
      
    }elseif($data['Page Type']=='Shop'){
      $extra_data=$this->shop_base_data();
      foreach($raw_data as $key=>$value){
	if(array_key_exists($key,$extra_data))
	  $extra_data[$key]=_trim($value);
      }
      
    }




    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
     $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }


    $sql=sprintf("select `Page Key` from `Page Dimension` where `Page URL`=%s"
		 ,prepare_mysql($data['Page URL'])
		 
		 );
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
      $this->found=true;
      $this->found_key=$row['Page Key'];
      $this->get_data('id',$this->found_key);
    }


    if(!$this->found and $create){
      $this->create($data,$extra_data);

    }

    
  }


function create_internal($data){
  $keys='(';
  $values='values(';
  $data['Page Key']=$this->id;
  foreach($data as $key=>$value) {
    $keys.="`$key`,";
    
	
    $values.=prepare_mysql($value).",";
  }
  $keys=preg_replace('/,$/',')',$keys);
  $values=preg_replace('/,$/',')',$values);
  $sql=sprintf("insert into `Page Internal Dimension` %s %s",$keys,$values);
  //print $sql;
  if (mysql_query($sql)) {
    $this->id=mysql_insert_id();
    $this->get_data('id',$this->id);
    
	
      }else{
	$this->error=true;
      }
     
  
}


  function create($data,$extra_data=false){

     $keys='(';
      $values='values(';
      foreach($data as $key=>$value) {
	$keys.="`$key`,";
	if (preg_match('/Page Title|Page Description/i',$key))
	  $values.="'".addslashes($value)."',";
	else
	  $values.=prepare_mysql($value).",";
      }
      $keys=preg_replace('/,$/',')',$keys);
      $values=preg_replace('/,$/',')',$values);
      $sql=sprintf("insert into `Page Dimension` %s %s",$keys,$values);

      if (mysql_query($sql)) {
	$this->id=mysql_insert_id();
	$this->get_data('id',$this->id);
		
	$this->update_valid_url();
	$this->update_working_url();
	
	if($this->data['Page Type']=='Internal'){
	  $this->create_internal($extra_data);
	}elseif($this->data['Page Type']=='Store'){
	  $this->create_store($extra_data);
	}



      }else{
	$this->error=true;
      }
     
     
  }


function update_working_url(){
    $old_value=$this->data['Page Working URL'];
    $this->data['Page Working URL']=$this->get_url_state($this->data['Page URL']);
    if($old_value!=$this->data['Page Working URL']){
      $sql=sprintf("update `Page Diemension` set `Page Working URL`=%s where `Page Key`=%d"
		   ,prepare_mysql($this->data['Page Working URL'])
		   ,$this->id
		   );
      mysql_query($sql);
    }

}

  function update_valid_url(){
    $old_value=$this->data['Page Valid URL'];
    $this->data['Page Valid URL']=($this->is_valid_url($this->data['Page URL'])?'Yes':'No');
    if($old_value!=$this->data['Page Valid URL']){
      $sql=sprintf("update `Page Diemension` set `Page Valid URL`=%s where `Page Key`=%d"
		   ,prepare_mysql($this->data['Page Valid URL'])
		   ,$this->id
		   );
      mysql_query($sql);
    }

  }



 function get($key){
   
  
   
    switch($key){
    case('link'):
      return $this->display();
      break;
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
    }
    return false;
 }






 function display($tipo='link'){

   switch($tipo){
   case('html'):
   case('xhtml'):
   case('link'):
   default:
     return '<a href="'.$this->data['Page URL'].'">'.$this->data['Page Title'].'</a>';
     
   }
   

 }


 function get_url_state($url){
   $state='Unknown';

   return $state;

 }
 
 function is_valid_url($url){
   if (preg_match("/^(http(s?):\\/\\/|ftp:\\/\\/{1})((\w+\.)+)\w{2,}(\/?)$/i", $url))
     return true;
   else
     return false;
   
 }

  /*
    Function: base_data
    Initialize data  array with the default field values
   */
  function internal_base_data(){
    $data=array();
    $result = mysql_query("SHOW COLUMNS FROM `Page Internal Dimension`");
    if (!$result) {
      echo 'Could not run query: ' . mysql_error();
     exit;
    }
    if (mysql_num_rows($result) > 0) {
     while ($row = mysql_fetch_assoc($result)) {
       if(!in_array($row['Field'],$this->ignore_fields))
	 $data[$row['Field']]=$row['Default'];
     }
   }
    return $data;
  }
/*
    Function: base_data
    Initialize data  array with the default field values
   */
  function shop_base_data(){
    $data=array();
    $result = mysql_query("SHOW COLUMNS FROM `Page Shop Dimension`");
    if (!$result) {
      echo 'Could not run query: ' . mysql_error();
     exit;
    }
    if (mysql_num_rows($result) > 0) {
     while ($row = mysql_fetch_assoc($result)) {
       if(!in_array($row['Field'],$this->ignore_fields))
	 $data[$row['Field']]=$row['Default'];
     }
   }
    return $data;
  }

  function update_thumbnail_key($image_key){

    


    $old_value=$this->data['Page Thumbnail Image Key'];
    if($old_value!=$image_key){
      $this->updated;
      $this->data['Page Thumbnail Image Key']=$image_key;
      
      $sql=sprintf("update `Page Dimension` set `Page Thumbnail Image Key`=%d ,`Page Snapshot Last Update`=NOW() where `Page Key`=%d "
		   ,$this->data['Page Thumbnail Image Key']
		   ,$this->id
		   );
      mysql_query($sql);

      $sql=sprintf("delete from  `Image Bridge` where `Subject Type`='Website' and `Subject Key`=%d "
		   ,$this->id
		   
		   );
      mysql_query($sql);
      
      if($this->data['Page Thumbnail Image Key']){
      $sql=sprintf("insert into `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`) values('Website',%d,%d)"
		   ,$this->id
		   ,$image_key
		   );
      //print $sql;
      mysql_query($sql);
      }
      
    }

  }

}
?>