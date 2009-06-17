<?
/*
 File: Mensajes.php

 This file contains the Mensajes Class

 About:
 Autor: Alberto Jacome Flores <alberto@logos-wissen.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
//include_once('ConexionJFA.php');
include_once('DB_Table.php');

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
   // Load data from `Mensajes Dimension` table where  `id_noticia`=4
   $key=4;
   $mensaje = New Mensaje($key);
       
   // Load data from `Mensajes Dimension` table where  `categoria`='Becario PHP'
   $mensaje = New Mensaje('Becario PHP');
       
   // Insert row to `Mensajes Dimension` table
   $data=array();
   $mensajes = New Mensaje('new',$data);
   (end example)

  */
  function Mensajes($arg1=false,$arg2=false) {

    // checar estos dos miembros
    $this->table_name='Menssage Dimension';
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

    //No es necesario find method
    //if(preg_match('/find/i',$arg1)){
    // $this->find($arg2,$arg1);
    //  return;
    //}
    $this->get_data($arg1,$arg2);
  }
  /*
   Method: get_data
   Load the data from the database

   See Also:
   <find>
  */
  function get_data($tipo,$tag){
    if($tipo=='id'){
      $sql=sprintf("select * from 'Mensajes Dimension' where  `Message Key`=%d",$tag);
      
    
      
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
    $data['mensaje']=$data;

  global $myconf;
    
  $this->data=$this->base_data();
  foreach($data as $key=>$value){
    if(array_key_exists($key,$this->data))
      $this->data[$key]=$value;
  }
    
  if($this->data['mensaje']==''){
    $this->new=false;
    $this->msg=_('No message provided');
    return false;
  }
  //TODO CAMBIAR
  $sql=sprintf("insert into 'mensajes dimension' ('autor','titulo','categoria','fecha','mensaje') values (%s,%s,%s,%s,%s)"
	       ,prepare_mysql($this->data['autor'])
	       ,prepare_mysql($this->data['titulo'])
	       ,prepare_mysql($this->data['categoria'])
	       ,prepare_mysql($this->data['fecha'])
	       ,prepare_mysql($this->data['mensaje'])
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
  
  $sql=sprintf("update `Mensajes Dimension` set `mensaje`=%s where `id_noticia  `=%d ",prepare_mysql($data),$this->id);
  mysql_query($sql);
  // print "$sql\n";
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg.=_('Message can not be updated')."\n";
    $this->error=true;
    return;
  }elseif($affected==0){
    //$this->msg=_('Same value as the old record');
    
  }else{
    $this->msg.=_('Message updated')."\n";
    $this->data['mensaje']=$data;
    $this->updated=true;
    $this->update_EmailValidated($options);
  }
  

}


function display($tipo='link'){


  if(!isset($this->data['mensaje'])){
    print_r($this);
    exit;
  }

  switch($tipo){
  case('plain'):
    return $this->data['mensaje'];

  case('html'):
  case('xhtml'):
  case('link'):
  default:
    return '<a href="mailto:'.$this->data['mensaje'].'">'.$this->data['mensaje'].'</a>';
     
  }
   

}

}

?>
