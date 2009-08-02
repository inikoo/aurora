<?php
/*
 File: Mensajes.php

 This file contains the Mensajes Class

 About:
 Autor: Alberto Jacome Flores <alberto@logos-wissen.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');

/* class: Mensajes
 Class to manage the *Mensajes Dimension* table
*/

class Mensajes extends DB_Table {
  /*
   Constructor: Mensajes
   Initializes the class, trigger  Search/Load/Create for the data set

   If first argument is find it will try to match the data or create if not found 
     
   Parameters:
   arg1 -    Tag for the Search/Load/Create Options *or* the ID for a simple object key search
   arg2 -    (optional) Data used to search or create the object

   Returns:
   void
       
   Example:
   (start example)
   // Load data from `Message Dimension` table where `Message Key`=4
   $key=4;
   $mensaje = New Mensajes($key);
       
   // Load data from `Message Dimension` table where  `Message Title`='Becario PHP'
   $mensaje = New Mensajes('Becario PHP');
       
   // Insert row to `Mensajes Dimension` table
   $data=array();
   $mensajes = New Mensajes('new',$data);
   (end example)

  */
  function Mensajes($arg1=false,$arg2=false) {

    // checar estos dos miembros
    $this->table_name='Message';
    $this->ignore_fields=array('Message Key');


    if(!$arg1 and !$arg2){
      $this->error=true;
      $this->msg='No data provided';
      return;
    }
    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return;
    }
    if ($arg1=='new'){
      $this->create($arg2);
      return;
    }

  }
  /*
   Method: get_data
   Load the data from the database

   See Also:
   <find>
  */
  function get_data($tipo,$tag){
    if($tipo=='id'){
      $sql=sprintf("select * from 'Message Dimension' where  `Message Key`=%d",$tag);
      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
	$this->id=$this->data['Message Key'];
    }

protected function create($data,$options=''){
  if(!$data){
    $this->new=false;
    $this->msg.=" Error no message data";
    $this->error=true;
    if(preg_match('/exit on errors/',$options))
      exit($this->msg);
    return false;
  }
    
  if(is_string($data))
    $data['Message']=$data;

  global $myconf;

  $this->data=$this->base_data();
  foreach($data as $key=>$value){
    if(array_key_exists($key,$this->data))
      $this->data[$key]=$value;
  }

  if($this->data['Message']==''){
    $this->new=false;
    $this->msg=_('No message provided');
    return false;
  }

  $sql=sprintf("insert into 'Message Dimension' ('Message Author','Message Title','Message Location','Message Creation Date','Message', 'Message Show','Message Show From','Message Show To') values (%s,%s,%s,%s,%s,%s,%s,%s)"
	       ,prepare_mysql($this->data['Message Author'])
	       ,prepare_mysql($this->data['Message Title'])
	       ,prepare_mysql($this->data['Message Location'])
	       ,prepare_mysql($this->data['Message Creation Date'])
	       ,prepare_mysql($this->data['Message'])
	       ,prepare_mysql($this->data['Message Show'])
	       ,prepare_mysql($this->data['Message Show From'])
	       ,prepare_mysql($this->data['Message Show To'])
	       );

  if(mysql_query($sql)){
    $this->id = mysql_insert_id();
    $this->get_data('id',$this->id);
    $this->new=true;
      
    $this->msg=_('New Message');


  }else{
    $this->new=false;
    $this->error=true;
    $this->msg=_('Error can not create message');
    if(preg_match('/exit on errors/',$options)){
      print "Error can not create email;\n";exit;
    }
  }
     
     
}

function get($key){
  if(isset($this->data[$key]))
    return $this->data[$key];
   
  switch($key){
  case('link'):
    return $this->display();
    break;
  }
  $_key=ucfirst($key);
  if(isset($this->data[$_key]))
    return $this->data[$_key];
  print "Error $key not found in get from message\n";
  return false;
   
}


/*Method: update_Message
 Update Message
 
 Return error if no email is provided or if there is another record with the same message address, a warning is returned if message not valid

 When $options is strict return error if the email is not valid
*/

function update_Email($data,$options=''){
  if($data==''){
    $this->msg.=_('messages can not be blank')."\n";
    $this->error=true;
    return;
  }
  
  $sql=sprintf("update `Message Dimension` set `Message`=%s where `Message Key  `=%d ",prepare_mysql($data),$this->id);
  mysql_query($sql);
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg.=_('Message can not be updated')."\n";
    $this->error=true;
    return;
  }elseif($affected==0){
    $this->msg=_('Same value as the old record');
    
  }else{
    $this->msg.=_('Message updated')."\n";
    $this->data['Message']=$data;
    $this->updated=true;
  }
  

}

function display($tipo='link'){

  if(!isset($this->data['Message'])){
    print_r($this);
    exit;
  }

  switch($tipo){
  case('plain'):
    return $this->data['Message'];

  case('html'):
  case('xhtml'):
  case('link'):
     
  }
   

}

}

?>
