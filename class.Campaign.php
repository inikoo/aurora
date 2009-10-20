<?php
/*
 File: Campaign.php

 This file contains the Campaign Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

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
        //    elseif($tipo=='code')
        //  $sql=sprintf("select * from `Campaign Dimension` where `Campaign Code`=%s",prepare_mysql($tag));
        // print $sql;
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
        if(!($key=='Campaign Begin Date' or  !$key=='Campaign Expiration Date'))
        $fields[]=$key;
        }
       
        $sql="select `Campaign Key` from `Campaign Dimension` where  true ";
        //print_r($fields);
        foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
        }
       
        $result=mysql_query($sql);
        $num_results=mysql_num_rows($result);
        if ($num_results==1) {
            $row=mysql_fetch_array($result, MYSQL_ASSOC);
            $this->found=true;
            $this->get_data('id',$row['Campaign Key']);
           
        }
        if($this->found){
            $this->get_data($this->found);
        }
        
        if($create and !$this->found){
        $this->create($data);
        
        }


    }



    function create($data) {

        //print_r($data);
      if($data['Campaing Deal Terms Metadata']=='' and $data['Campaign Deal Terms Lock']=='Yes')
	$data['Campaing Deal Terms Metadata']=Deal::parse_term_metadata($data['Campaign Deal Terms Type'],$data['Campaign Deal Terms Description']);
      
      
      $keys='(';
      $values='values(';
      foreach($data as $key=>$value) {
	$keys.="`$key`,";
	if($data['Campaign Deal Terms Lock']=='No')
	 $values.=prepare_mysql($value,false).",";
	else
            $values.=prepare_mysql($value).",";
      }
      $keys=preg_replace('/,$/',')',$keys);
      $values=preg_replace('/,$/',')',$values);




        $sql=sprintf("insert into `Campaign Dimension` %s %s",$keys,$values);
        //print "$sql\n";
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
		       'Deal Allowance Metadata'=>''
		       );
      foreach($raw_data as $key=>$value) {
	if (array_key_exists($key,$base_data))
	  $base_data[$key]=$value;
      }
      $base_data['Campaign Key']=$this->id;


//print_r($base_data);

      if($base_data['Deal Allowance Lock']=='Yes')
	$base_data['Deal Allowance Metadata']=Deal::parse_allowance_metadata($base_data['Deal Allowance Type'],$base_data['Deal Allowance Description']);
      else
	$base_data['Deal Allowance Metadata']='';

//print_r($base_data);

      $keys='(';
      $values='values(';
      foreach($base_data as $key=>$value) {
	$keys.="`$key`,";
	    if($base_data['Deal Allowance Lock']=='No')
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

    }


}