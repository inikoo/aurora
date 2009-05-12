<?
/*
 File: Staff.php 

 This file contains the Staff Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

require_once 'Name.php';
require_once 'Email.php';

class Staff{

  var $data=array();
  var $items=array();
  var $status_names=array();
  var $id;
  var $tipo;
  var $contact=false;




  function __construct($key='id',$data=false) {

     $this->status_names=array(0=>'new');


     if($key=='new' and is_array($data)){
       $this->create_order($data);
       if(!$this->id)
	 return;
       $key='id';
       $data=$this->id;
     }
     
     
     if(is_numeric($key) and !$data){
       $data=$key;
       $key='id';
     }
     $this->get_data($key,$data);
     

  }

  

  function get_data($key,$id){
    
    if($key=='alias')
      $sql=sprintf("select * from `Staff Dimension` where `Staff Alias`=%s",prepare_mysql($id));
    elseif($key=='id')
            $sql=sprintf("select * from  `Staff Dimension`     where `Staff Key`=%d",$id);
    else
      return;

   $result=mysql_query($sql);
   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
     $this->id=$this->data['Staff Key'];
    

  }

  function get($key){
    if(!$this->id)
      return;
     if(array_key_exists($key,$this->data))
      return $this->data[$key];
     switch($key){
     case('First Name'):
       if(!is_object($this->contact))
	 $this->contact=new Contact($this->data['Staff Contact Key']);
       if($this->contact->id)
	 return $this->contact->data['Contact First Name'];
       else
	 return '';
       break;
     case('Surname'):
       if(!is_object($this->contact))
	 $this->contact=new Contact($this->data['Staff Contact Key']);
       if($this->contact->id)
	 return $this->contact->data['Contact Surname'];
       else
	 return '';
       break;
     case('Email'):
       if(!is_object($this->contact))
	 $this->contact=new Contact($this->data['Staff Contact Key']);
       if($this->contact->id)
	 return strip_tags($this->contact->data['Contact Main XHTML Email']);
       else
	 return '';
       break;


     }

    
  }


}