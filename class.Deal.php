<?php
/*
 File: Deal.php

 This file contains the Deal Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/
include_once('class.DB_Table.php');

class Deal extends DB_Table {




    function Deal($a1,$a2=false) {

        $this->table_name='Deal';
        $this->ignore_fields=array('Deal Key');

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
            $sql=sprintf("select * from `Deal Dimension` where `Deal Key`=%d",$tag);
        //    elseif($tipo=='code')
        //  $sql=sprintf("select * from `Deal Dimension` where `Deal Code`=%s",prepare_mysql($tag));
        // print $sql;
        $result=mysql_query($sql);

        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
            $this->calculate_deal=create_function('$transaction_data,$customer_id,$date', $this->get('Deal Metadata'));
            $this->id=$this->data['Deal Key'];
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
        if(!($key=='Deal Begin Date' or  $key=='Deal Expiration Date' or   $key=='Deal Allowance Metadata' or  $key=='Deal Replace Metadata' ))
        $fields[]=$key;
        }
       
        $sql="select `Deal Key` from `Deal Dimension` where  true ";
        //print_r($fields);
        foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
        }
	//print "$sql\n";
        $result=mysql_query($sql);
        $num_results=mysql_num_rows($result);
        if ($num_results==1) {
            $row=mysql_fetch_array($result, MYSQL_ASSOC);
            $this->found=true;
            $this->get_data('id',$row['Deal Key']);
           
        }
        if($this->found){
	  $this->get_data('id',$this->found);
        }
        
        if($create and !$this->found){
        $this->create($data);
        
        }


    }



    function create($data) {

      if($data['Deal Trigger Key']=='')
	$data['Deal Trigger Key']=0;
        if ($data['Deal Allowance Type']=='Percentage Off' and preg_match('/Quantity Ordered/i',$data['Deal Terms Type'])) {
            //   print "***********";
            if (preg_match('/order \d+ or more/i',$data['Deal Terms Description'],$match))
                $a=preg_replace('/[^\d]/','',$match[0]);
            else {
                print "ohh no a not founD in deal class ".$data['Deal Terms Description']."\n";
                print_r($data);
                exit;
            }

            if (preg_match('/^\d+\%/i',$data['Deal Allowance Description'],$match))
                $b=.01*preg_replace('/\%/','',$match[0]);
            $data['Deal Allowance Metadata']="$a,$b";
            //    print_r($match);
        }
        if ($data['Deal Allowance Type']=='Percentage Off' and preg_match('/Order Interval/i',$data['Deal Terms Type'])) {
            //   print "***********";
            if (preg_match('/last order within \d+ days/i',$data['Deal Terms Description'],$match))
                $a=preg_replace('/[^\d]/','',$match[0]).' day';
            if (preg_match('/last order within \d+ .* month/i',$data['Deal Terms Description'],$match))
                $a=preg_replace('/[^\d]/','',$match[0]).' month';

            if (preg_match('/^\d+\%/i',$data['Deal Allowance Description'],$match))
                $b=.01*preg_replace('/\%/','',$match[0]);
            $data['Deal Allowance Metadata']="$a,$b";
            //    print_r($match);
        }
        elseif($data['Deal Allowance Type']=='Get Free' and preg_match('/Quantity Ordered^/i',$data['Deal Terms Type']) ) {

            $data['Deal Allowance Description']=preg_replace('/ one /',' 1 ',$data['Deal Allowance Description']);
            $data['Deal Allowance Description']=preg_replace('/ two /',' 2 ',$data['Deal Allowance Description']);
            $data['Deal Allowance Description']=preg_replace('/ three /',' 3 ',$data['Deal Allowance Description']);

            preg_match('/buy \d+/i',$data['Deal Allowance Description'],$match);
            $buy=_trim(preg_replace('/[^\d]/','',$match[0]));

            preg_match('/get \d+/i',$data['Deal Allowance Description'],$match);
            $get=_trim(preg_replace('/[^\d]/','',$match[0]));



            $data['Deal Allowance Metadata']=$buy.','.$get;
        }


        //print_r($data);

        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";
	    if($key=='Deal Replace Metadata')
	      $values.=prepare_mysql($value,false).",";
	    else
            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Deal Dimension` %s %s",$keys,$values);
        // print "$sql\n";
        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id',$this->id);
        } else {
            print "Error can not create deal  $sql\n";
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
 public static function parse_allowance_metadata($allowance_type,$allowance_description){
// print "$allowance_type,$allowance_description\n";
 switch($allowance_type){
   case('Percentage Off'):
     if (preg_match('/\d+((\.|\,)\d+)?\%/i',$allowance_description,$match)){
       $number=preg_replace('/\,/','.',$match[0]);
       $number=preg_replace('/\%/','',$number);
       return 0.01* (float) $number;
     }
      if (preg_match('/^(|.*\s+)free(\s+.*|)$/i',$allowance_description,$match)){
       return 1;
     }
     break;
   case('Get Free'):
     $allowance_description=translate_written_number($allowance_description);
     if(preg_match('/get \d+/i',$allowance_description,$match)){
//            print "** $allowance_description \n";

       return _trim(preg_replace('/[^\d]/','',$match[0]));
       }
     break;
   }
 }

    public static function parse_term_metadata($term_description_type,$term_description){
      
      $conditions=preg_split('/\s+AND\s+/',$term_description_type);
      $metadata='';
      foreach($conditions as $condition){
         $metadata.=','.Deal::parse_individual_term_metadata($condition,$term_description);
      }
      $metadata=preg_replace('/^\,/','',$metadata); 
      // print "------- $metadata\n";
      
       return $metadata;
      }
      
    public static function parse_individual_term_metadata($term_description_type,$term_description){
      //print "$term_description_type  => $term_description\n";
      switch($term_description_type){
      case('Family Quantity Ordered'):
      case('Product Quantity Ordered'):
      case('Department Quantity Ordered'):
      case('Store Quantity Ordered'):
	$term_description=translate_written_number($term_description);

	
	if (preg_match('/order \d+ or more/i',$term_description,$match))
	  return preg_replace('/[^\d]/','',$match[0]);
	

	break;
      case('Order Interval'):
	if (preg_match('/order (within|since|every) \d+ days?/i',$term_description,$match))
	  return preg_replace('/[^\d]/','',$match[0]).' day';
	if (preg_match('/order (within|since|every) \d+ months?/i',$term_description,$match))
	  return preg_replace('/[^\d]/','',$match[0]).' month';
	if (preg_match('/order (within|since|every) \d+ weeks?/i',$term_description,$match))
	  return preg_replace('/[^\d]/','',$match[0]).' week';
	break;
      case('Order Number'):
	if (preg_match('/(first|1st) (order|one)|order (for|the)? (first|1st) time/i',$term_description,$match))
	  return 1;
	if (preg_match('/(second|2nd) (order|one)|order (for|the)? (second|2nd) time/i',$term_description,$match))
	  return 2;
	if (preg_match('/(third|3nd) (order|one)|order (for|the)? (third|3nd) time/i',$term_description,$match))
	  return 3;
	if (preg_match('/order (number|no|\#)?\s*\d+/i',$term_description,$match))
	  return preg_replace('/[^\d]/','',$match[0]);

	break;
      case('Order Items Net Amount'):
      case('Order Total Net Amount'):
	list($currency,$amount)=parse_money($term_description);
	return "$currency $amount";
	
	break;
      case('Shipping Country'):	
	$regex='/orders? (shipped |send |to be send |d(ie)spached )?to .*$/i';
	if( preg_match($regex.'.*$',$term_description,$match)){
	  $country=_trim(preg_replace($regex,'',$match));
	  $country_code=Address::parse_country($country);
	}

	break;
      }
    }

}