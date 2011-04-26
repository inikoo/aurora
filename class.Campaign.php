<?php
/*
 File: Campaign.php

 This file contains the Campaign Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');

class Campaign extends DB_Table {




    function Campaign($a1,$a2=false) {

        $this->table_name='Campaign';
        $this->ignore_fields=array('Campaign Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id',$a1);
        } else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
           $this->find($a2,'create');

        } elseif(preg_match('/find/i',$a1))
            $this->find($a2,$a1);
        else
            $this->get_data($a1,$a2);

    }

    function get_data($tipo,$tag) {

        if ($tipo=='id')
            $sql=sprintf("select * from `Campaign Dimension` where `Campaign Key`=%d",$tag);
            elseif($tipo=='code')
          $sql=sprintf("select * from `Campaign Dimension` where `Campaign Code`=%s",prepare_mysql($tag));
	    //  print $sql;
        $result=mysql_query($sql);

        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
            $this->id=$this->data['Campaign Key'];
        }
    }

    function find($raw_data,$options) {

        if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }

        $this->candidate=array();
        $this->found=false;
        $this->found_key=0;
        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }

        $data=$this->base_data();


        foreach($raw_data as $key=>$value) {

            if (array_key_exists($key,$data))
                $data[$key]=$value;

        }

        $fields=array();
        foreach($data as $key=>$value){
        if(!($key=='Campaign Begin Date' or  $key=='Campaign Expiration Date' or $key=='Campaign Deal Terms Metadata'   ))
	  $fields[]=$key;
        }
       
        $sql="select `Campaign Key` from `Campaign Dimension` where  true ";
	// print_r($fields);
        foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
        }
	//print "$sql\n";
        $result=mysql_query($sql);
        $num_results=mysql_num_rows($result);
        if ($num_results==1) {
            $row=mysql_fetch_array($result, MYSQL_ASSOC);
            $this->found=true;
	    $this->found_key=$row['Campaign Key'];
           
        }
        if($this->found){
	  $this->get_data('id',$this->found_key);
        }
        

        if($create and !$this->found){
        $this->create($data);
        
        }


    }



    function create($data) {

      
      if($data['Campaign Deal Terms Metadata']=='' and $data['Campaign Deal Terms Lock']=='Yes')
	$data['Campaign Deal Terms Metadata']=Deal::parse_term_metadata($data['Campaign Deal Terms Type'],$data['Campaign Deal Terms Description']);
      
      
      $keys='(';
      $values='values(';
      foreach($data as $key=>$value) {
	$keys.="`$key`,";
	if( $data['Campaign Deal Terms Lock']=='No'  and  $key=='Campaign Deal Terms Metadata'  )
	  $values.=prepare_mysql($value,false).",";
	else
	  $values.=prepare_mysql($value).",";
      }
      $keys=preg_replace('/,$/',')',$keys);
      $values=preg_replace('/,$/',')',$values);



      // print_r($data);
        $sql=sprintf("insert into `Campaign Dimension` %s %s",$keys,$values);
	// print "$sql\n";
        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id',$this->id);
        } else {
            print "Error can not create campaign  $sql\n";
            exit;

        }
    }

    function get($key='') {

        if (isset($this->data[$key]))
            return $this->data[$key];

        switch ($key) {

        }

        return false;
    }

    function add_deal_schema($raw_data){

      $base_data=array(
		       'Deal Name'=>$this->data['Campaign Name'],
		       'Deal Allowance Description'=>'',
		       'Deal Allowance Type'=>'',
		       'Deal Allowance Target'=>'',
		       'Deal Allowance Lock'=>'No',
		       'Deal Allowance Metadata'=>'',
		       'Deal Trigger'=>'',
		       'Deal Replace'=>'none',
		       'Deal Replace Metadata'=>''
		       );
      foreach($raw_data as $key=>$value) {
	if (array_key_exists($key,$base_data))
	  $base_data[$key]=$value;
      }
      $base_data['Campaign Key']=$this->id;
      $this->schema_found=false;
        $fields=array();
        foreach($base_data as $key=>$value){
	  if(!($key=='Deal Allowance Metadata'  or  $key=='Deal Replace Metadata'  ))
	  $fields[]=$key;
        }
       
        $sql="select `Deal Schema Key` from `Campaign Deal Schema` where  true ";
	// print_r($fields);
        foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($base_data[$field],false));
        }
	//print "$sql\n";
        $result=mysql_query($sql);
        $num_results=mysql_num_rows($result);
        if ($num_results==1) {
            $row=mysql_fetch_array($result, MYSQL_ASSOC);
            $this->schema_found=true;
	    
           
        }
        if(!$this->schema_found){
           
	  
	  if($base_data['Deal Allowance Lock']=='Yes')
	    $base_data['Deal Allowance Metadata']=Deal::parse_allowance_metadata($base_data['Deal Allowance Type'],$base_data['Deal Allowance Description']);
	  else
	    $base_data['Deal Allowance Metadata']='';
	  
	  //print_r($base_data);
	  
	  $keys='(';
	  $values='values(';
	  foreach($base_data as $key=>$value) {
	    $keys.="`$key`,";
	    //print "-> $key=>$value \n";
	    if( 
	       ($base_data['Deal Allowance Lock']=='No'  and  $key=='Deal Allowance Metadata') or 
	       ($base_data['Deal Replace']=='none'  and  $key=='Deal Replace Metadata')
		)
	      $values.=prepare_mysql($value,false).",";
	    else
	      $values.=prepare_mysql($value).",";
	  }
	  $keys=preg_replace('/,$/',')',$keys);
	  $values=preg_replace('/,$/',')',$values);
	  $sql=sprintf("insert into `Campaign Deal Schema` %s %s",$keys,$values);
	  if (mysql_query($sql)) {
	    $this->msg='Deal Schema Added';
	  } else {
	    print "Error can not add deal schema  $sql\n";
	    exit;
	    
	  }

	}else{
	  $this->msg='Deal Schema Found';
	}

    }

function find_schema($arg){
  $schema_data=array();
  $this->schema_found=false;
  if(is_string($arg)){
    $sql=sprintf("select * from `Campaign Deal Schema` where `Deal Name`=%s",prepare_mysql($arg));
    $res=mysql_query($sql);
    if($schema_data=mysql_fetch_array($res)){
     
       $this->schema_found=true;
    }
  }elseif(is_numeric($arg)){
    $sql=sprintf("select * from `Campaign Deal Schema` where `Deal Schema Key`=%d",$arg);
    $res=mysql_query($sql);
    if($schema_data=mysql_fetch_array($res)){
       $this->schema_found=true;
    }
  }


  return $schema_data;
}

function create_deal($deal_schema,$additional_data=array()){
  $this->deal_created=false;

  $schema_data=$this->find_schema($deal_schema);
  if($this->schema_found){
    
    $data['Deal Allowance Target']=$schema_data['Deal Allowance Target'];
    if(array_key_exists('Deal Allowance Target Key',$additional_data))
      $data['Deal Allowance Target Key']=$additional_data['Deal Allowance Target Key'];
    else
      $data['Deal Allowance Target Key']=0;
    

    switch($data['Deal Allowance Target']){
    case('Charge'):
      $target=new Charge($additional_data['Deal Allowance Target Key']);
      break;
     case('Shipping'):
      $target=new Shipping($additional_data['Deal Allowance Target Key']);
      break;  
       case('Family'):
      $target=new Family($additional_data['Deal Allowance Target Key']);
      break;  
   case('Department'):
      $target=new Department($additional_data['Deal Allowance Target Key']);
      break;  
case('Store'):
      $target=new Store($additional_data['Deal Allowance Target Key']);
      break; 
case('Customer'):
      $target=new Customer($additional_data['Deal Allowance Target Key']);
      break; 
case('Product'):
      $target=new Product($additional_data['Deal Allowance Target Key']);
      break; 
    default:
      exit("can not get target ".$data['Deal Allowance Target']."\n");
    }

    $schema_replaceable_columns=array('Deal Allowance Description','Deal Name');
    foreach($schema_replaceable_columns as $schema_replaceable_column){
      if(preg_match('/\[.+\]/',$schema_data[$schema_replaceable_column],$match)){
	$tag=preg_replace('/\[/','\\[',$match[0]);$tag=preg_replace('/\]/','\\]',$tag);
	$column=preg_replace('/(\[|\])/','',$match[0]);
	if($target->get($column)!=''){
	  $column_data=$target->get($column);
	  $schema_data[$schema_replaceable_column]=preg_replace("/$tag/",$column_data,$schema_data[$schema_replaceable_column]);
	}
      }
    }


    $data['Store Key']=$this->data['Store Key'];
  

    
    $data['Deal Trigger']=$schema_data['Deal Trigger'];


    $data['Campaign Deal Schema Key']=$schema_data['Deal Schema Key'];
    if(array_key_exists('Deal Trigger Key',$additional_data) and is_numeric($additional_data['Deal Trigger Key']) ){
      $data['Deal Trigger Key']=$additional_data['Deal Trigger Key'];
    }else
      $data['Deal Trigger Key']=0;

    $data['Deal Begin Date']=$this->data['Campaign Begin Date'];
    $data['Deal Expiration Date']=$this->data['Campaign Expiration Date'];
    //print_r($schema_data);
    $data['Deal Allowance Type']=$schema_data['Deal Allowance Type'];
    $data['Deal Name']=$schema_data['Deal Name'];
    $data['Deal Allowance Lock']=$schema_data['Deal Allowance Lock'];
    if($schema_data['Deal Allowance Lock']=='Yes'){
      $data['Deal Allowance Description']=$schema_data['Deal Allowance Description'];
      $data['Deal Allowance Metadata']=$schema_data['Deal Allowance Metadata'];
   
    
    }else{
      $data['Deal Allowance Description']=$additional_data['Deal Allowance Description'];
      $data['Deal Allowance Metadata']=Deal::parse_allowance_metadata($data['Deal Allowance Type'],$data['Deal Allowance Description']);


    }
    $data['Deal Terms Lock']=$this->data['Campaign Deal Terms Lock'];
    $data['Deal Terms Type']=$this->data['Campaign Deal Terms Type'];
    
    if($this->data['Campaign Deal Terms Lock']=='Yes'){
      $data['Deal Terms Description']=$this->data['Campaign Deal Terms Description'];
      $data['Deal Terms Metadata']=$this->data['Campaign Deal Terms Metadata'];
      
    }else{
      $data['Deal Terms Description']=$additional_data['Deal Terms Description'];
      $data['Deal Terms Metadata']=Deal::parse_term_metadata($data['Deal Terms Type'],$data['Deal Terms Description']);
    }
    //print_r($data);
 // exit;
 $deal=new Deal('find create',$data);
 

  }else{
    $this->msg='Schema not found';
    $this->error=true;
    
  }

}

}