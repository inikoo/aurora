<?php
/*
 File: Deal.php

 This file contains the Deal Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');

class DealMetadata extends DB_Table {




    function DealMetadata($a1,$a2=false) {

        $this->table_name='Deal';
        $this->ignore_fields=array('Deal Metadata Key');

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
            $sql=sprintf("select * from `Deal Metadata Dimension` where `Deal Metadata Key`=%d",$tag);
        //    elseif($tipo=='code')
        //  $sql=sprintf("select * from `Deal Metadata Dimension` where `Deal Code`=%s",prepare_mysql($tag));
        // print $sql;
        $result=mysql_query($sql);

        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
            $this->calculate_deal=create_function('$transaction_data,$customer_id,$date', $this->get('Deal Metadata'));
            $this->id=$this->data['Deal Metadata Key'];
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
        if(!($key=='Deal Metadata Begin Date' or  $key=='Deal Metadata Expiration Date' or   $key=='Deal Metadata Allowance'or   $key=='Deal Metadata Terms' or  $key=='Deal Metadata Replace' ))
        $fields[]=$key;
        }
       
        $sql="select `Deal Metadata Key` from `Deal Metadata Dimension` where  true ";
        //print_r($fields);
        foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
        }
	//	print "$sql\n";
        $result=mysql_query($sql);
        $num_results=mysql_num_rows($result);
        if ($num_results==1) {
            $row=mysql_fetch_array($result, MYSQL_ASSOC);
            $this->found=true;
            $this->get_data('id',$row['Deal Metadata Key']);
           
        }
        if($this->found){
	  $this->get_data('id',$this->found);
        }
        
        if($create and !$this->found){
        $this->create($data);
        
        }


    }



    function create($data) {

      if($data['Deal Metadata Trigger Key']=='')
	$data['Deal Metadata Trigger Key']=0;
      // print "-----------\n";print_r($data);
      if($data['Deal Metadata Allowance']=='' and $data['Deal Metadata Allowance Lock']=='No'){
	//	print "xcaca";
	$data['Deal Metadata Allowance']=Deal::parse_allowance_metadata($data['Deal Metadata Allowance Type'],$data['Deal Metadata Allowance Description']);
      }      if($data['Deal Metadata Terms']=='' and $data['Deal Metadata Terms Lock']=='No')
	$data['Deal Metadata Terms']=Deal::parse_term_metadata($data['Deal Metadata Terms Type'],$data['Deal Metadata Terms Description']);
      ///   print_r($data);
      //   exit;
        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";
	    if($key=='Deal Metadata Replace')
	      $values.=prepare_mysql($value,false).",";
	    else
            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Deal Metadata Dimension` %s %s",$keys,$values);
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
	case('Description'):
	case('Deal Description'):
	  return $this->data['Deal Metadata Terms Description'].' &rArr; '.$this->data['Deal Metadata Allowance Description'];
	  break;
        }

        return false;
    }

 public static function parse_allowance_metadata($allowance_type,$allowance_description){
   $conditions=preg_split('/\s+AND\s+/',$allowance_type);
   $metadata='';
  
   foreach($conditions as $condition){
     $metadata.=';'.Deal::parse_individual_allowance_metadata($condition,$allowance_description);
      }
   $metadata=preg_replace('/^;/','',$metadata); 
   // print "** $allowance_type,$allowance_description ->$metadata  \n";
   return $metadata;
 }


 public static function parse_individual_allowance_metadata($allowance_type,$allowance_description){
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
 case('Get Same Free'):
 case('Get Free'):
     $allowance_description=translate_written_number($allowance_description);
     $number=1;
     if(preg_match('/get \d+/i',$allowance_description,$match)){
//            print "** $allowance_description \n";

       $number=_trim(preg_replace('/[^\d]/','',$match[0]));
       }
     return $number;
     break;
   }
 }

    public static function parse_term_metadata($term_description_type,$term_description){
      
      $conditions=preg_split('/\s+AND\s+/',$term_description_type);
      $metadata='';
      foreach($conditions as $condition){
         $metadata.=';'.Deal::parse_individual_term_metadata($condition,$term_description);
      }
      $metadata=_trim(preg_replace('/^;/','',$metadata)); 
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
	
	//print("$term_description\n");
	$term_description=translate_written_number($term_description);
	


	if (preg_match('/^\d+$/i',$term_description,$match))
	  return $term_description;
	if (preg_match('/order \d+( or more)?/i',$term_description,$match))
	  return preg_replace('/[^\d]/','',$match[0]);
	if (preg_match('/buy \d+/i',$term_description,$match))
	  return preg_replace('/[^\d]/','',$match[0]);
	if (preg_match('/\d+ oder mehr/i',$term_description,$match))
	  return preg_replace('/[^\d]/','',$match[0]);

	break;
      case('Order Interval'):
	if (preg_match('/order (within|since|every) \d+ days?/i',$term_description,$match))
	  return preg_replace('/[^\d]/','',$match[0]).' day';
	if (preg_match('/order (within|since|every) \d+ (calendar )?months?/i',$term_description,$match))
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
      case('Order Items Gross Amount'):
	if (preg_match('/(less than|upto|up to)\s*(\$|\£|\€)?\d+/i',$term_description))
	  $conditional='<';
	if (preg_match('/(more than|over)\s*(\$|\£|\€)?\d+/i',$term_description))
	  $conditional='>';
	if (preg_match('/(equal|exactly)\s*(\$|\£|\€)?\d+/i',$term_description))
	  $conditional='>';
	list($currency,$amount)=parse_money($term_description);
	return _trim("$conditional $currency $amount");
		break;
      case('Shipping Country'):	
	$regex='/orders? (shipped |send |to be send |d(ie)spached )?to .*$/i';
	if( preg_match('/orders? (shipped |send |to be send |d(ie)spached )?to .*$/i',$term_description,$match)){
	  $country=_trim(preg_replace('/orders? (shipped |send |to be send |d(ie)spached )?to /i','',$match[0]));
	  //$country=_trim(preg_replace('/and order/i','',$country));

	  $country=_trim(preg_replace('/(and|\+|y|with) (value|customer|order).*/i','',$country));
	 
	  $country_code=Address::parse_country($country);
	  return $country_code;
	}

	break;
      }
    }

    function allowance_input_form(){
      $input_allowance=array();
      $allowances=preg_split('/\s+AND\s+/',$this->data['Deal Metadata Allowance Type']);
      $metadata=preg_split('/\s+|\s+/',$this->data['Deal Metadata Allowance']);
	foreach($allowances as $key=>$allowance){
	  $input_allowance[]=$this->allowance_individual_input_form($allowance,$metadata[$key]);
	}
	return $input_allowance;
    }
    
    
      function get_thin_allowance_description($allowance=false,$metadata=false){
        if(!$allowance)
            $allowance=$this->data['Deal Metadata Allowance Type'];
        if(!$metadata)
            $metadata=$this->data['Deal Metadata Allowance'];
            
            $label='';
            $value='';
            
               switch($allowance){
    case('Percentage Off'):
	$label=_('Discount');
	$value=percentage($metadata,1);
	break;

      }
            
        return array($label,$value);    
    }
    
    
    function allowance_individual_input_form($allowance,$metadata){


      $input_allowance=array();
      $input_allowance['Value Class']='';
  
   list($input_allowance['Label'],$input_allowance['Value'])=$this->get_thin_allowance_description($allowance,$metadata);
  

      if($this->data['Deal Metadata Allowance Lock']=='Yes'){
	$allowance_lock_img='<img src="art/icons/lock.png" alr="Locked"/>';
	$allowance_lock=true;
	$input_allowance['Value Class'].=' locked';
      }else{
	$allowance_lock_img='';
       $allowance_lock=false;
      }
      $input_allowance['Lock Label']=$allowance_lock_img;
      $input_allowance['Lock Value']=$allowance_lock;
      return $input_allowance;
    }

    function terms_input_form(){
      $input_terms=array();
      $terms=preg_split('/\s+AND\s+/',$this->data['Deal Metadata Terms Type']);
      $metadata=preg_split("/\;/",$this->data['Deal Metadata Terms']);

      //      print $this->data['Deal Metadata Terms Type']." ->  ". $this->data['Deal Metadata Terms']."\n";

      //print_r($metadata);
      //print "-------c ----\n";
      foreach($terms as $key=>$terms){
	$input_terms[]=$this->terms_individual_input_form($terms,$metadata[$key]);
      }
	return $input_terms;
    }
    
    
    
    function get_thin_terms_description($terms=false,$metadata=false){
        if(!$terms)
            $terms=$this->data['Deal Metadata Terms Type'];
        if(!$metadata)
            $metadata=$this->data['Deal Metadata Terms'];
            
            $label='';
            $value='';
            
               switch($terms){
      case('Order Interval'):
	$label=_('If').' '._('order within');
	$value=$metadata;
	break;
	 case('Family Quantity Ordered'):
	$label=_('If').' '._('order more than');
	$value=number($metadata);
	break;
      case('Shipping Country'):
	$label=_('If').' '._('Shipping Destination');
	
	$country=new Country ('code',$metadata);
	$value=$country->data['Country Name'];
	$input_terms['Value Class']='country';
	break;
      case('Order Items Net Amount'):
	$conditional='';
	if(preg_match('/^(\>|<|=|>=|<=)\s/',$metadata,$match)){
	    $conditional=_trim($match[0]);
	    $metadata=preg_replace("/^$conditional/",'',$metadata);
	  }

	  $label=_trim($terms.' '.$conditional);
	  $value=$metadata;
	break;


      }
            
        return array($label,$value);    
    }
    
    function terms_individual_input_form($terms,$metadata){
     
      $input_terms=array();
      $input_terms['Value Class']='';

      list($input_terms['Label'],$input_terms['Value'])=$this->get_thin_terms_description($terms,$metadata);

      //print "** $terms -> $metadata **\n";
   
      
      if($this->data['Deal Metadata Terms Lock']=='Yes'){
	$terms_lock_img='<img src="art/icons/lock.png" alr="Locked"/>';
	$terms_lock=true;
	$input_terms['Value Class'].=' locked';
      }else{
	$terms_lock_img='';
       $terms_lock=false;
	}			 
      $input_terms['Lock Label']=$terms_lock_img;
      $input_terms['Lock Value']=$terms_lock;

      return $input_terms;
    }
    

    function get_xhtml_status(){
      switch($this->data['Deal Metadata Status']){
      case('Active'):
	return '<img src="art/icons/accept.png" />';
	break;
      case('Finish'):
	return '<img src="art/icons/time_delete.png" />';
	break;
      case('Wating'):
	return '<img src="art/icons/clock_go.png" />';
	break;
      case('Suspended'):
	return '<img src="art/icons/stop.png" />';
	break;


      }

    }

   /*Function: update_field_switcher
   */
 function update_field_switcher($field,$value,$options=''){

   switch($field){
   case('term'):
     $this->update_term($value);
     break;
   case('allowance'):
     $this->update_allowance($value);
     break; 
   default:
     $base_data=$this->base_data();
     if(array_key_exists($field,$base_data)) {
       $this->update_field($field,$value,$options);
     }
  }
 }

 



    function update_term($thin_description){
      $this->updated=false;


      switch($this->data['Deal Metadata Terms Type']){
      case('Family Quantity Ordered'):
      case('Product Quantity Ordered'):
	if(!is_numeric($thin_description)){
	  $this->msg=_('Term should be numeric');
	  return;
	}elseif($thin_description<=0){
	    $this->msg=_('Term should be more than zero');
	    return;
	  }
	
	$term_description="order ".number($thin_description)." or more";

      }

      
      $term_metadata=$this->parse_term_metadata(
						    $this->data['Deal Metadata Terms Type']
						    ,$term_description
						    );
      if($term_metadata!=$this->data['Deal Metadata Terms']){

	$sql=sprintf("update `Deal Metadata Dimension` set `Deal Metadata Terms Description`=%s ,`Deal Metadata Terms`=%s where `Deal Metadata Key`=%d"
	,prepare_mysql($term_description)
	,prepare_mysql($term_metadata)
	,$this->id
	);

		mysql_query($sql);
$this->data['Deal Metadata Terms Description']=$term_description;
$this->data['Deal Metadata Terms']=$term_metadata;

    $this->updated=true;
  list($label,$new_thin_description)=$this->get_thin_terms_description();
	$this->new_value=$new_thin_description;

    }


    }

     function update_allowance($thin_description){
      $this->updated=false;


      switch($this->data['Deal Metadata Allowance Type']){
      case('Percentage Off'):
      $thin_description=preg_replace('/\s*%$/','',$thin_description);
      
	if(!is_numeric($thin_description)){
	  $this->msg=_('allowance should be numeric');
	  return;
	}
	    $thin_description=abs($thin_description);
	    
	        if($thin_description>100){
	    $this->msg=_('allowance can not be more than 100%');
	    return;
	  }
	
	$allowance_description=number($thin_description)."%";

      }

      
      $allowance_metadata=$this->parse_allowance_metadata(
						    $this->data['Deal Metadata Allowance Type']
						    ,$allowance_description
						    );
      if($allowance_metadata!=$this->data['Deal Metadata Allowance']){

	$sql=sprintf("update `Deal Metadata Dimension` set `Deal Metadata Allowance Description`=%s ,`Deal Metadata Allowance`=%s where `Deal Metadata Key`=%d"
	,prepare_mysql($allowance_description)
	,prepare_mysql($allowance_metadata)
	,$this->id
	);
	mysql_query($sql);
	$this->updated=true;
	$this->data['Deal Metadata Allowance Description']=$allowance_description;
$this->data['Deal Metadata Allowance']=$allowance_metadata;
list($label,$new_thin_description)=$this->get_thin_allowance_description();
	$this->new_value=$new_thin_description;
    }


    }
}

?>