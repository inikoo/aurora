<?php
/*
 File: Corporation.php 

 This file contains the Corporation Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2010, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Company.php');

class Corporation extends DB_Table{
  
 function Corporation($a1=false,$a2=false) {

    $this->table_name='Corporation';

    if($a1=='create'){
      $this->create($a2);
      
    }else
       $this->get_data();
  }
 function get_data(){
    
  
      $sql=sprintf("select * from `Corporation Dimension` ");
    
 
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=1;
      
     
      $this->company=new Company($this->data['Corporation Company Key']);
    }
      



  }
 function create($data){
   $this->new=false;
   
   $company=new Company('find create auto',$data);

$data['Corporation Company Key']=$company->id;

   $data['Corporation Company Name']=$company->data['Company Name'];

   
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
    $sql=sprintf("delete * from  `Corporation Dimension` " );
mysql_query($sql);
    $sql=sprintf("insert into `Corporation Dimension` %s %s",$keys,$values);
    
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->msg=_("Corporation Added");
      $this->get_data();
   $this->new=true;
   
  
   
   return;
 }else{
   $this->msg=_(" Error can not create warehouse");
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
  
  
   protected function update_field_switcher($field,$value,$options='') {


switch ($field) {
    case 'Company Name':

    case 'Corporation Name':
        $this->update_company_name($value);
        break;
    case('Corporation Currency'):
       $this->update_currency($value);
        break;
    default:
        $this->company->update_field_switcher($field,$value,$options);
        break;
}
       }
       
      
      function update_name($value){
 
  
    $sql=sprintf("update `Corporation Dimension` set `Corporation Name`=%s",prepare_mysql($value));
    mysql_query($sql);
  
      $this->updated=true;
    $this->new_value=$value;
    }
 
 
  
      
       
  function update_company_name($value){
 
    $this->company->update_field_switcher('Company Name',$value);
  
   $this->updated=$this->company->updated;
    $this->new_value=$this->company->data['Company Name'];
 
  }
  
  
  function update_currency($value){
  $value=strtoupper($value);
  $sql=sprintf("select * from kbase.`Currency Dimension` where `Currency Code`=%s",prepare_mysql($value));
  $res=mysql_query($sql);
  if($row=mysql_fetch_assoc($res)){
        
       $sql=sprintf ("update `Corporation Dimension` set `Corporation Currency`=%s",prepare_mysql($value));
    mysql_query($sql);
  
      $this->updated=true;
    $this->new_value=$value;
        
  }else{
    $this->error=true;
    $this->msg='Currency Code '.$value.' not valid';
  
  }
  }
  
}

?>