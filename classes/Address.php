<?
/*
  File: Address.php 

  This file contains the Address Class

  About: 
  Autor: Raul Perusquia <rulovico@gmail.com>
 
  Copyright (c) 2009, Kaktus 
 
  Version 2.0





*/
include_once('DB_Table.php');
include_once('Country.php');
/* class: Address
   Class to manage the *Address Dimension* table
*/
class Address extends DB_Table{
  
  private $scope=false;
  private $scope_key=false;
  /*
    Constructor: Address
    Initializes the class, trigger  Search/Load/Create for the data set

    If first argument is find it will try to match the data or create if not found 
     
    Parameters:
    arg1 -    Tag for the Search/Load/Create Options *or* the Contact Key for a simple object key search
    arg2 -    (optional) Data used to search or create the object

    Returns:
    void
       
    Example:
    (start example)
    // Load data from `Address Dimension` table where  `Address Key`=3
    $key=3;
    $address = New Address($key); 
       
    // Load data from `Address Dimension` table where  `Address`='raul@gmail.com'
    $address = New Address('raul@gmail.com'); 
       
    // Insert row to `Address Dimension` table
    $data=array();
    $address = New Address('new',$data); 
      

    (end example)

  */
  function Address($arg1=false,$arg2=false) {

 
    $this->table_name='Address';
    $this->ignore_fields=array('Address Key','Address Data Last Update','Address Data Creation');

    if(!$arg1 and !$arg2){
      $this->error=true;
      $this->msg='No data provided';
      return;
    }
    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return;
    }
    if(preg_match('/find/i',$arg1)){
      $this->find($arg2,$arg1);
      return;
    }

    if($arg1=='new'){
      $this->create($arg2);
      return;
    }

    if($arg1=='fuzzy all'){
      $this->get_data('fuzzy all');
      return;
    }elseif($arg1=='fuzzy country'){
      if(!is_numeric($arg2)){
	$this->get_data('fuzzy all');
	return;
      }
      $country=new Country($arg2);
      if(is_numeric($arg2) and $country->get('Country Code')!='UNK'){
	$this->get_data('fuzzy country',$arg2);
	return;
      }else{
	$this->get_data('fuzzy all');
	return;
      }
	 
	 
    }
    
    $this->get_data($arg1,$arg2);
    
  }

  /*
    Method: get_data
    Load the data from the database

  */
  function get_data($tipo,$id=false){

    if($tipo=='id')
      $sql=sprintf("select * from `Address Dimension` where  `Address Key`=%d",$id);
    elseif('tipo'=='fuzzy country')
      $sql=sprintf("select * from `Address Dimension` where  `Address Fuzzy`='Yes' and `Address Fuzzy Type`='country' and `Address Country Key`=%d   ",$id);
    else
      $sql=sprintf("select * from `Address Dimension` where  `Address Fuzzy`='Yes' and `Address Fuzzy Type`='all' ",$id);


    

    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC))
      $this->id=$this->data['Address Key'];
    else{
      print "$sql\n  can not fpuns \n";
     
      // exit(" $sql\n can not open address");

    }
  }
  /*
    Method: find
    Given a set of address components try to find it on the database updating properties, if not found creates a new record
  */
  
  function find($raw_data,$options=''){
    //   print "$options\n";
    //   print_r($raw_data);
   
    $this->found=false;
    $this->found_in=false;
    $this->found_out=false;
    $this->candidate=array();
    $this->address_candidate=array();
    $in_contact=array();
    $mode='Contact';
    $parent='Contact';
    $create=false;
    $update=false;
    if(preg_match('/create/i',$options)){
      $create=true;
    }
     if(preg_match('/update/i',$options)){
      $update=true;
    }
    

    $auto=false;
    if(preg_match('/auto/i',$options)){
      $auto=true;
    }
    

    if(!$raw_data){
      $this->new=false;
      $this->msg=_('Error no address data');
      if(preg_match('/exit on errors/',$options))
	exit($this->msg);
      return false;
    }


    if(isset($raw_data['editor']) and is_array($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
	
      }
    }
    
    
    if(isset($raw_data['Street Data']))
      $raw_data['Address Line 3']=$raw_data['Street Data'];
    if(isset($raw_data['Address Building']))
      $raw_data['Address Line 2']=$raw_data['Address Building'];
    if(isset($raw_data['Address Internal']))
      $raw_data['Address Line 1']=$raw_data['Address Internal'];

    $data=$this->base_data();

    if(preg_match('/from Company|in company/i',$options)){
      foreach($raw_data as $key=>$val){
	$_key=preg_replace('/Company /','',$key);
	if(array_key_exists($_key,$data))
	  $data[$_key]=$val;
	if($_key=='Address Line 1' or $_key=='Address Line 2' or  $_key=='Address Line 3' or $_key=='Address Input Format')
	  $data[$_key]=$val;
      }
    }elseif(preg_match('/from contact|in contact/i',$options)){
      foreach($raw_data as $key=>$val){
	
	$_key=preg_replace('/^Contact( Home| Work)? /i','',$key);
	//	print "******** $key          ->  $_key\n";
	if(array_key_exists($_key,$data))
	  $data[$_key]=$val;
	if($_key=='Address Line 1' or $_key=='Address Line 2' or  $_key=='Address Line 3' or $_key=='Address Input Format')
	  $data[$_key]=$val;
      }
   

    }

    if(!isset($data['Address Input Format'])){
      $data['Address Input Format']='DB Fields';
      if(isset($data['Address Line 1']))
	$data['Address Input Format']='3 Line';
      else
	$data['Address Input Format']='DB Fields';
    }


   
    switch($data['Address Input Format']){
    case('3 Line'):
      $data=$this->prepare_3line($data);

      $data['Address Input Format']='DB Fields';
      break;
    case('DB Fields'):
      $data=$this->prepare_DBfields($data);
      break;
    }



    $this->raw_data=$data;



    if(!preg_match('/force create/i',$options,$match)){


    $subject_key=0;
    $subject_type='Contact';

    if(preg_match('/in contact \d+/i',$options,$match)){
      $subject_key=preg_replace('/[^\d]/','',$match[0]);
      $subject_type='Contact';

      $mode='Contact in';
      $in_contact=array($subject_key);


    }
    if(preg_match('/in company \d+/i',$options,$match)){
      $subject_key=preg_replace('/[^\d]/','',$match[0]);
      $subject_type='Company';
      $company=new Company($subject_key);
      $in_contact=$company->get_contact_keys();
      $mode='Company in';

    }elseif(preg_match('/company/',$options,$match)){
      $subject_type='Company';
      $mode='Company';
    }

    if($mode=='Contact')
      $options.=' anonymous';



    if($data['Address Fuzzy']=='Yes'){
      //if fuzzy only check in parent fuzzy sub space 

      $fields=array('Address Fuzzy','Address Street Number','Address Building','Address Street Name','Address Street Type','Address Town Secondary Division','Address Town Primary Division','Address Town','Address Country Primary Division','Address Country Secondary Division','Address Country Key','Address Postal Code','Military Address','Military Installation Address','Military Installation Name');

      $sql=sprintf("select A.`Address Key`,`Subject Key` from `Address Dimension` A  left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`)  where `Address Fuzzy`='Yes' and `Subject Type`='Contact' ");
      foreach($fields as $field){
	$sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
      }
      //  print "FUZZY $sql\n";
      $result=mysql_query($sql);
      $num_results=mysql_num_rows($result);
      if($num_results==0){
	// address not found
	$this->found=false;
       	

      }else if($num_results==1){
	$row=mysql_fetch_array($result, MYSQL_ASSOC);
	$this->found=true;
	$this->get_data('id',$row['Address Key']);
	if($mode=='Contact in' or $mode=='Company in'){
	  if(in_array($row['Subject Key'],$in_contact)){
	    $this->candidate[$row['Subject Key']]=110;
	    $this->found_in=true;
	    $this->found_out=false;
	  }else{
	    $this->candidate[$row['Subject Key']]=100;
	    $this->found_in=false;
	    $this->found_out=true;
	  }
	}else
	  $this->candidate[$row['Subject Key']]=100;

      }else{// Found in mora than one

	  
	  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	    	if($mode=='Contact in' or $mode=='Company in'){

		  if(in_array($row['Subject Key'],$in_contact)){
		    $this->candidate[$row['Subject Key']]=110;
		  }else{
		    $this->candidate[$row['Subject Key']]=100;
		  }
		}else
		  $this->candidate[$row['Subject Key']]=100;
	  }
	$this->msg.=_('Address found in')." $num_results ".ngettext('contact','contacts',$num_results);
	
      }
      
      
    }else{
      // Address not fuzzy
      // Try to find an exact match

      $fields=array('Address Fuzzy','Address Street Number','Address Building','Address Street Name','Address Street Type','Address Town Secondary Division','Address Town Primary Division','Address Town','Address Country Primary Division','Address Country Secondary Division','Address Country Key','Address Postal Code','Military Address','Military Installation Address','Military Installation Name');

      $sql="select A.`Address Key`,`Subject Key`,`Subject Type` from `Address Dimension`  A  left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`) where `Subject Type`='Contact' ";
      foreach($fields as $field){
	$sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
      }
      $result=mysql_query($sql);
      //      print "No fuzzy $sql\n";
      $num_results=mysql_num_rows($result);
      //   print "No fuzzy $num_results\n";
      if($num_results==0){
	$this->found=false;
       	
      }else if($num_results==1){
	$row=mysql_fetch_array($result, MYSQL_ASSOC);
	$this->found=true;
	$this->get_data('id',$row['Address Key']);
	if($mode=='Contact in' or $mode=='Company in'){
	  if(in_array($row['Subject Key'],$in_contact)){
	    $this->candidate[$row['Subject Key']]=110;
	    $this->found_in=true;
	    $this->found_out=false;
	  }else{
	    $this->candidate[$row['Subject Key']]=100;
	    $this->found_in=false;
	    $this->found_out=true;
	  }
	}else
	  $this->candidate[$row['Subject Key']]=100;

      }else{// address found in many contact
	  
	  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	    if($mode=='Contact in' or $mode=='Company in'){
	      if(in_array($row['Subject Key'],$in_contact)){
		$this->candidate[$row['Subject Key']]=110;
	      }else{
		$this->candidate[$row['Subject Key']]=100;
	      }
	    }else
	      $this->candidate[$row['Subject Key']]=100;
	    
	  }
	  $this->msg.=_('Address found in')." $num_results ".ngettext('contact','contacts',$num_results);
	    
      }

    }

    if(!$this->found and count($this->candidate)==0){
      // foound 1 additions
      if($data['Address Fuzzy']=='No'){
	//Special cases
	//a) when same (st number,street,town,d1,d2) but postal code on / off
	    $fields=array('Address Street Number','Address Building','Address Street Name','Address Street Type','Address Town Secondary Division','Address Town Primary Division','Address Town','Address Country Primary Division','Address Country Secondary Division','Address Country Key','Military Address','Military Installation Address','Military Installation Name');

      $sql="select A.`Address Key`,`Subject Key`,`Subject Type` from `Address Dimension`  A  left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`) where `Subject Type`='Contact' ";
      foreach($fields as $field){
	$sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
      }
      $result=mysql_query($sql);
      //print "No fuzzy $sql\n";
      $num_results=mysql_num_rows($result);
     
      if($num_results==1){
	$row=mysql_fetch_array($result, MYSQL_ASSOC);
	$this->found=true;
	$this->get_data('id',$row['Address Key']);
	if($mode=='Contact in' or $mode=='Company in'){
	  if(in_array($row['Subject Key'],$in_contact)){
	    $this->candidate[$row['Subject Key']]=100;
	    $this->found_in=true;
	    $this->found_out=false;
	  }else{
	    $this->candidate[$row['Subject Key']]=80;
	    $this->found_in=false;
	    $this->found_out=true;
	  }
	}else
	  $this->candidate[$row['Subject Key']]=90;

      }
	
	
      }


    }


    }// End no force create
    
  
    

    
    if($update){
      if($this->found){
	$this->update($data,$options);
	return;
      }
    
    }
      
    if($create and !$this->found){
      //   print_r($data);
      //    exit;
      $this->create($data,$options);
      
    }
    
    

 

  }


  /*Method: create
    Creates a new address record

   
    Parameter:
    An array with the data to be inserted in the database, a important key is *Address Input Format* which  can be: _3 Line_, _DB Fields_

    The country can be inputed using: Address Country Key, Address Country Code, Address Country 2 Alpha Code, Address Country Name, (Parsed in this order until positive match with Country Dimension table)

    Examples:
    (start example)
    // Example using 3 line input method
  
    $data=array(
    'Address Input Format'=>'3 Line'
    'Address Line 1'=>'3 Hobart Street'
    'Address Line 2'=>''
    'Address Line 3'=>''
    'Address Town'=>'Sheffield'
    'Address Region'=>''                      //State,county,province etc
    'Address Postal Code'=>'S11 4HD'
    'Address Country Name'=>'United Kindom')
   
    // Example using 3 line extended input method
   
    $data=array(
    'Address Input Format'=>'3 Line'
    'Address Line 1'=>'Hill House'
    'Address Line 2'=>'10 Kitchen Street'
    'Address Line 3'=>''
    'Address Town SubDivision'=>''   
    'Address Town Division'=>'Wakley'      
    'Address Town'=>'Sheffield'
    'Address SubRegion'=>'South Yorkshire'    //County,municipality,etc inside the region
    'Address Region'=>'England'               //State,county,province 
    'Address Postal Code'=>'S11 4HD'
    'Address Country Code'=>'GBR')
   
   
    (end example)

    See Also:
    <Address>
   
  */
  protected function create($data){

    //  print_r($data);

    if(!isset($data['Address Input Format'])){
      $data['Address Input Format']='DB Fields';
      if(isset($data['Address Address Line 1']))
	$data['Address Input Format']='3 Line';
      else
	$data['Address Input Format']='DB Fields';
    }


    //print_r($data);

    switch($data['Address Input Format']){
    case('3 Line'):
      $this->data=$this->prepare_3line($data);
      break;
    case('DB Fields'):
      $this->data=$this->prepare_DBfields($data);
      break;
    }

    
    $this->data['Address Plain']=$this->plain($this->data);
    $keys='';
    $values='';
    foreach($this->data as $key=>$value){
      
      if(!preg_match('/line \d|Address Input Format/i',$key) ){
	if(preg_match('/Address Data Creation/i',$key) ){
	  $keys.=",`".$key."`";
	  $values.=', Now()';
	}else{
	  $keys.=",`".$key."`";
	  $values.=','.prepare_mysql($value,false);
	}
      }
    }
    $values=preg_replace('/^,/','',$values);
    $keys=preg_replace('/^,/','',$keys);

    $sql="insert into `Address Dimension` ($keys) values ($values)";
    //print $sql;
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->data['Address Key']= $this->id;
      $this->new=true;
    }else{
      print "Error can not create address\n";exit;
	
    }
  }


  /*Method: update
    Switcher calling the apropiate update method
    Parameters:
    $data - associated array with Email Dimension fields
    */
  public function update($data,$options=''){


     if(isset($data['editor'])){
      foreach($data['editor'] as $key=>$value){

	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
		    
      }
    }


    // if($this->table_name=='Telecom'){
      // print_r($data);exit;
    // }
    $base_data=$this->base_data();
  
    foreach($data as $key=>$value){
      //print "$key $value  \n";
      if(array_key_exists($key,$base_data)){

	if($value!=$this->data[$key]){

	  $this->update_field_switcher($key,$value,$options);
	}
	
      }elseif(preg_match('/^Street Data$/',$key)){
	$this->update_field_switcher($key,$value,$options);
      }
    }
    
    if(!$this->updated)
      $this->msg.=' '._('Nothing to be updated')."\n";
  
  }







  /*
    Function:update
    Update the Record
  */
  // function update($data){
    
    
  // }
 function update_field_switcher($field,$value,$options=''){
   switch($field){
   case('Address Primary Postal Code'):
   case('Address Secondary Postal Code'):
   case('Address Location'):
   case('Address Plain'):
   case('Address Input Format'):
   case('Address Fuzzy'):
     break;
   case('Address Postal Code'):
     $data=$this->parse_postcode($value,$this->data['Address Country Code']);
     foreach($data as $postcode_field=>$postcode_value){
       if($postcode_field!='Address Postal Code')
	 $postcode_options=$options.' no history';
       else
	 $postcode_options=$options;
       $this->update_field($postcode_field,$postcode_value,$postcode_options);

     }
     break;
   case('Street Data'):
     $data=$this->parse_street($value,$this->data['Address Country Code']);
     foreach($data as $street_field=>$street_value){
       $this->update_field($street_field,$street_value,$options);
     }

     break;
   default:
     $this->update_field($field,$value,$options);
   }
 }

 function update_metadata($raw_data){

   
   foreach($raw_data as $key=>$value){
     if($key=='Type'){
       $this->update_address_type($value);
     }elseif($key=='Function'){
       $this->update_address_function($value);
     }
	 
   }

 }


 function update_address_type($raw_new_address_types){
   $updated=false;
   
   $new_address_types=array();
   $valid_types=array('Office','Shop','Warehouse','Other');
   foreach($raw_new_address_types as $raw_new_address_type){
     if(in_array($raw_new_address_type,$valid_types))
       $new_address_types[$raw_new_address_type]=$raw_new_address_type;
   }

   if(count($new_address_types)==0)
     $new_address_types['Other']=array('Other');
   //print_r($this->data['Type']);
   //print_r($new_address_types);

   foreach($this->data['Type'] as $type){
     if(!in_array($type,$new_address_types)){
       //print "deleting $type\n";
       $sql=sprintf("delete from `Address Bridge` where `Address Key`=%s and `Subject Type`=%s and `Subject Key`=%d  and `Address Type`=%s "
		    ,$this->id
		    ,prepare_mysql($this->scope)
		    ,$this->scope_key
		    ,prepare_mysql($type)
		    );
       //print "$sql\n";
       mysql_query($sql);
       
       $updated=true;
     }
   }
   
   foreach($new_address_types as $type){

     if(!in_array($type,$this->data['Type'])){
       foreach($this->data['Function'] as $function){
	 $sql=sprintf("select *  from `Address Bridge` where `Address Key`=%s and `Subject Type`=%s and `Subject Key`=%d and `Address Function`=%s "
		      ,$this->id
		      ,prepare_mysql($this->scope)
		      ,$this->scope_key
		      ,prepare_mysql($function)
		      );
	 $res=mysql_query($sql);
	 $active='Yes';
	 $main='No';
	 //	 print "$sql\n";
	 
	 if($row=mysql_fetch_array($res)){
	   $active=$row['Is Active'];
	   $main=$row['Is Main'];
	 }
	 
	 $sql=sprintf('insert into `Address Bridge` values (%d,%s,%d,%s,%s,%s,%s)'
		      ,$this->id
		      ,prepare_mysql($this->scope)
		      ,$this->scope_key
		      ,prepare_mysql($type)
		      ,prepare_mysql($function)
		      ,prepare_mysql($active,false)
		      ,prepare_mysql($main,false)
		      );
	 //print "$sql\n";
	 mysql_query($sql);
	 
	 $updated=true;
       }
     }
   }
   
   if($updated){
     // print "updated!!!";
     $this->load_metadata();
     
     $msg='';
     $this->msg_update.=$msg;
     $this->msg.=$msg;
     $this->updated=true;
     
   }

 }





  function update_address_function($value){

 }

 function get($key){

    
    if(array_key_exists($key,$this->data))
      return $this->data[$key];
   
    switch($key){
    case('Type'):
    case('Function'):


      if(!$this->scope)
	$this->set_scope();
      return $this->data[$key];
      break;
      

    case('country region'):
      if($this->get('Address Country Primary Division')!='')
	return $this->get('Address Country Primary Division');
      else
	return $this->get('Address Country Secondary Division');
      break;
    
      
    }
    
    // print_r($this->data);
    $_key=ucwords($key);
    if(array_key_exists($_key,$this->data))
      return $this->data[$_key];
    print_r($this->data);
    print "Error $key not found in get from address\n";
    asds();
    exit;
    return false;

  }

  function display($tipo=''){
    $separator="\n";
    switch($tipo){
    case('mini'):
      $street=_trim($this->data['Address Street Number'].' '.$this->data['Address Street Name'].' '.$this->data['Address Street Type']);
      $max_characters=26;
      if($strlen>$max_characters)
	$street=substr($street,$max_characters)."... ";
      $street.=', ';
      return $street.$this->location($this->data,'right');
      break;
    case('location'):
      return $this->location($this->data);
      break;
    case('plain'):
      return $this->plain($this->data);
      break;
    case('street'):
      return _trim($this->data['Address Street Number'].' '.$this->data['Address Street Name'].' '.$this->data['Address Street Type']);
      break;
    case('xhtml'):
    case('html'):
      $separator="<br/>";
     
    default:
      if($this->data['Military Address']=='Yes'){
	$address=$this->data['Military Installation Address'];
	$address_type=_trim($this->data['Military Installation Type']);
	if($address_type!='')
	  $address.=$separator.$address_type;
	$address_type=_trim($this->data['Address Postal Code']);
	if($address_type!='')
	  $address.=$separator.$address_type;
	$address.=$separator.$this->data['Address Country Name'];

      }else{
	//print_r($this->data);
	$address='';
	$header_address=_trim($this->data['Address Internal'].' '.$this->data['Address Building']);
	if($header_address!='')
	  $address.=$header_address.$separator;
	
	$street_address=$this->display('street');
	if($street_address!='')
	  $address.=$street_address.$separator;

     
	$subtown_address=$this->data['Address Town Secondary Division'];
	if($this->data['Address Town Primary Division'])
	  $subtown_address.=' ,'.$this->data['Address Town Primary Division'];
	$subtown_address=_trim($subtown_address);
	if($subtown_address!='')
	  $address.=$subtown_address.$separator;
	
	
	
     
	$town_address=_trim($this->data['Address Town']);
	if($town_address!='')
	  $address.=$town_address.$separator;

	$ps_address=_trim($this->data['Address Postal Code']);
	if($ps_address!='')
	  $address.=$ps_address.$separator;
     
	$address.=$this->data['Address Country Name'];
      }
      return _trim($address);
  
    case('header'):

      $separator=', ';
      $address='';
      $header_address=_trim($this->data['Address Internal'].' '.$this->data['Address Building']);
      if($header_address!='')
	$address.=$header_address.$separator;
     
      $street_address=_trim($this->data['Address Street Number'].' '.$this->data['Address Street Name'].' '.$this->data['Address Street Type']);
      if($street_address!='')
	$address.=$street_address.$separator;
     
     
      $subtown_address=$this->data['Address Town Secondary Division'];
      if($this->data['Address Town Primary Division'])
	$subtown_address.=' ,'.$this->data['Address Town Primary Division'];
      $subtown_address=_trim($subtown_address);
      if($subtown_address!='')
	$address.=$subtown_address.$separator;


      return _trim($address);

    }
   
   

  }
 
  /*
    Function: base_data
    Initializes an array with the default field values
   
    If argument contains '3 line' corresponding base is made
  */
  function base_data($args='replace'){
    $data=array();

    if(preg_match('/3 line/i',$args)){
      $data['Address Line 1']='';
      $data['Address Line 2']='';
      $data['Address Line 3']='';
      $data['Address Town SubDivision']='';
      $data['Address Town Division']='';
      $data['Address Town']='';
      $data['Address SubRegion']='';
      $data['Address Region']='';
      $data['Address Country Key']='';
      $data['Address Country Code']='';
      $data['Address Country Name']='';
      $data['Address Country Code']='';
      $data['Address Country 2 Alpha Code']='';

    }else{

      $ignore_fields=array('Address Key');
     
      $result = mysql_query("SHOW COLUMNS FROM `Address Dimension`");
      if (!$result) {
	echo 'Could not run query: ' . mysql_error();
	exit;
      }
      if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
	  if(!in_array($row['Field'],$ignore_fields))
	    $data[$row['Field']]=$row['Default'];
	}
      }
 
    }
    return $data;
  }


  /*
    Function: location
    Get the address location

    Parameter:
    $str -  _array_ location data
  */

  public static function location($data,$flag='left'){
    
    if($data['Military Address']=='Yes'){
      $location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($data['Address Country 2 Alpha Code']),$data['Address Country Code'],$data['Military Installation Type']);
    }else{
      
      if($data['Address Fuzzy']=='Yes'){
	if(preg_match('/country/i',$data['Address Fuzzy Type'])){
	  $location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($data['Address Country 2 Alpha Code']),$data['Address Country Code'],_('Unknown'));
	  return _trim($location);
	}elseif(preg_match('/town/i',$data['Address Fuzzy Type'])){
	  $location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($data['Address Country 2 Alpha Code']),$data['Address Country Code'],_('Somewhere in').' '.$data['Address Country Name']);
	  return _trim($location);
	}
      }

      if($flag=='none')
	$location=sprintf('%s %s',$data['Address Town'],$data['Address Country Code']);
      else if($flag=='right')
	$location=sprintf('%s <img src="art/flags/%s.gif" title="%s">',$data['Address Town'],strtolower($data['Address Country 2 Alpha Code']),$data['Address Country Code']);
      else
	$location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($data['Address Country 2 Alpha Code']),$data['Address Country Code'],$data['Address Town']);

      
    }
  
    return _trim($location);
  }




  /*
    Function: is_street
    Check if the string id like a street

    Parameter:
    $str -  _string_ line to be checked
  */
  function is_street($string){
    if($string=='')
      return false;

    $string=_trim($string);
    // if(preg_match('/^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|/\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))

    if(preg_match('/\s+rd\.?\s*$|\s+road\s*$|^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))
      return true;
    if(preg_match('/[a-z\-\#\,]{1,}\s*\d/i',$string))
      return true;

    if(preg_match('/\d.*[a-z]{1,}/i',$string))
      return true;

  

    return false;
  }
  /*
    Function: is_internal
    Check if the string id like a internal address

    Parameter:
    $str -  _string_ line to be checked
  */
  function is_internal($string){
    if($string=='')
      return false;
    // if(preg_match('/^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|/\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))

    if(preg_match('/lot\s*(n-)?\s*\d|suite\s*\d|shop\s*\d|apt\s*\d/i',$string))
      return true;
    else
      return false;
  }

  /*
    Function: get_country_d2_name
    Get the name of the Country SubDivision

    Parameters:
    $id - _integer_  *Country Secondary Division Key* in DB
  */
  function get_country_d2_name($id=''){
    if(!is_numeric($id))
      return '';
    $sql=sprintf("select `Country Secondary Division Name` as name from `Country Secondary Division Dimension` where `Country Secondary Division Key`=%d",$id);
    //  print $sql;
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      return $row['name'];
    }
    return '';
  }
  /*
    Function: get_country_d1_name
    Get the name of the Country Division

    Parameters:
    $id - _integer_  *Country Primary Division Key* in DB
  */
  function get_country_d1_name($id=''){
    if(!is_numeric($id))
      return '';
    $sql=sprintf("select `Country Primary Division Name` as name from `Country Primary Division Dimension` where `Country Primary Division Key`=%d",$id);
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      return $row['name'];
    }
    return '';
  }


  /*
    Function: is_country_d1
    Look if the string is in Country Primary Division Dimension DB table

    The search will be en the following fields:Name,Native Name,Local Native Name

    Parameter:
    $str -  _string_ Country Primary Division Name
  */
  public static  function is_country_d1($country_d1,$country_id){
    if($country_d1=='')
      return false;

    if($country_id>0)
      $sql=sprintf("select `Country Primary Division Key` as id from `Country Primary Division Dimension` where (`Country Primary Division Name`='%s' or `Country Primary Division Native Name`='%s' or `Country Primary Division Local Native Name`='%s') and `Country Key`=%d",addslashes($country_d1),addslashes($country_d1),addslashes($country_d1),$country_id);
    else
      $sql=sprintf("select `Country Primary Division Key` as id from `Country Primary Division Dimension` where (`Country Primary Division Name`='%s' or `Country Primary Division Native Name`='%s' or `Country Primary Division Local Native Name`='%s') ",addslashes($country_d1),addslashes($country_d1),addslashes($country_d1));

    //    print "$sql\n";
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      return true;
    }else
      return false;
  }

  /*
    Function: is_country_d2
    Look if the string is in Country Secondary Division Dimension DB table

    The search will be en the following fields:Name,Native Name,Local Native Name

    Parameter:
    $str -  _string_ Country Secondary Division Name
  */
  public static  function is_country_d2($str,$country_id){
    if($str=='')
      return false;

    if($country_id>0)
      $sql=sprintf("select `Country Secondary Division Key` as id from `Country Secondary Division Dimension` where (`Country Secondary Division Name`='%s' or `Country Secondary Division Native Name`='%s' or `Country Secondary Division Local Native Name`='%s') and `Country Key`=%d",addslashes($str),addslashes($str),addslashes($str),$country_id);
    else
      $sql=sprintf("select `Country Secondary Division Key` as id from `Country Secondary Division Dimension` where (`Country Secondary Division Name`='%s' or `Country Secondary Division Native Name`='%s' or `Country Secondary Division Local Native Name`='%s') ",addslashes($str),addslashes($str),addslashes($str));

    //    print "$sql\n";
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      return true;
    }else
      return false;
  }

  /*
    Function: is_country_key
    Look if is a valid country key

    Parameter:
    $key -  _integer_ Country Key in DB
  */
  public static  function is_country_key($key){
    //    print "----------- $key -------\n";
    if(!is_numeric($key) or $key<=0){
      return false;
    }
    $sql=sprintf("select `Country Key` from `Country Dimension`  where `Country Key`=%d",$key);
    //    PRINT $sql;
    $result = mysql_query($sql);
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
      return true;
    else
      return false;
  }

  /*
    Function: is_country_code
    Look if is a valid country 3 alpha code

    Parameter:
    $code -  _integer_ Country Code
  */
  public static  function is_country_code($code){

    if(!preg_match('/^[a-z]{3}$/i',$code))
      return false;

    $sql=sprintf("select `Country Key` from `Country Dimension`  where `Country Code`=%s",prepare_mysql($code));
    $result = mysql_query($sql) ;
    if($row = mysql_fetch_array($result, MYSQL_ASSOC))
      return true;
    else
      return false;
  }

  /*
    Function: is_country_2alpha_code
    Look if is a valid country 2 alpha code

    Parameter:
    $code -  _integer_ Country 2 Alpha Code
  */
  public static function is_country_2alpha_code($code){

    if(!preg_match('/^[a-z]{2}$/i',$code))
      return false;

    $sql=sprintf("select `Country Key` from `Country Dimension`  where `Country 2 Alpha Code`=%s",prepare_mysql($code));
    $result = mysql_query($sql) ;
    if($row = mysql_fetch_array($result, MYSQL_ASSOC))
      return true;
    else
      return false;
  }


  /*
    Function: is_town
    Look if the town is registed in the DB

    Parameters:
    $town - _string_ Town name
    $country_id - (optional) _integer_ Country Key in DB
  */
  public static function is_town($town,$country_id=0){
    if($town=='')
      return false;

    if($country_id>0)
      $sql=sprintf("select `Town Key` as id from `Town Dimension` where (`Town Name`='%s' or `Town Native Name`='%s' or `Town Local Native Name`='%s') and `Country Key`=%d",addslashes($town),addslashes($town),addslashes($town),$country_id);
    else
      $sql=sprintf("select `Town Key` as id from `Town Dimension` where (`Town Name`='%s' or `Town Native Name`='%s' or `Town Local Native Name`='%s') ",addslashes($town),addslashes($town),addslashes($town));

    //  print "$sql\n";
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      return true;
    }else
      return false;
  }
  /*
    Function: parse_postcode
    Analize an beautify the postal code

    Parameters:
    $postcode - _string_ the postal code
    $country_code - (optional) _string_ Country Code

    Todo:
    In the moment only for GBR
  */
  function parse_postcode($postcode,$country_code=''){
    global $myconf;

    if(!preg_match('/^[a-z]{3}$/i',$country_code)){
      $country_code=$myconf['country_code'];

    }
    $postcode=_trim($postcode);
    $data['Address Postal Code']=$postcode;
    $data['Address Primary Postal Code']='';
    $data['Address Secondary Postal Code']='';
    $data['Address Postal Code Separator']='';
    
    $country_code=strtoupper($country_code);
    switch($country_code){
    case 'GBR':
      $data['Address Postal Code Separator']=' ';
      $data['Address Postal Code']=preg_replace('/,?\s*scotland\s*$|united kingdom/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/\s/','',$data['Address Postal Code']);
      if(preg_match('/^bfpo\s*\d/i',$data['Address Postal Code']) ){
	$data['Address Postal Code']=preg_replace('/bfpo/i','BFPO ',$data['Address Postal Code']);
	$data['Address Primary Postal Code']='BFPO';
	$data['Address Secondary Postal Code']=preg_replace('/bfpo /i','',$data['Address Postal Code']);
      }
      else{
	$data['Address Postal Code']=substr($data['Address Postal Code'],0,strlen($data['Address Postal Code'])-3).' '.substr($data['Address Postal Code'],-3,3);
	$postcode_parts=preg_split('/ /',$data['Address Postal Code']);
	$data['Address Primary Postal Code']=$postcode_parts[0];
	$data['Address Secondary Postal Code']=$postcode_parts[1];
      }

      break;
    }
    return $data;
   
  }
  /*
    Function: is_valid_postcode
    Look if the postcode has a valid format

    Parameters:
    $postcode - _string_ 
    $country_id - (optional) _integer_ Country Key in DB

    Todo:
    In the moment onlu for GBR
  */
  function is_valid_postcode($postcode,$country_id){
    // print "------------------";
    $postcode=_trim($postcode);
    switch($country_id){
    case 30:

      if(preg_match('/^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {0,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA|BT\d{2}\s*\d[a-z]2)$/i',$postcode))
	return true;
      else
	return false;
      break;
    }
    return false;
   
  }
  /*
    Function: 
    Prepare the Country Data


  */
  public static function prepare_country_data($data){
    global $myconf;
   
    if($data['Address Country Key']=='' and
       $data['Address Country Code']=='' and
       $data['Address Country 2 Alpha Code']=='' and
       $data['Address Country Name']==''
       ){
    
      // try to get the counbtry form the town
      if($data['Address Country Primary Division']!=''){
	$country_in_other=new Country('find',$data['Address Country Primary Division']);
	if($country_in_other->id!=244){
	  $data['Address Country Key']=$country_in_other->id;
	  $data['Address Country Primary Division']='';
	}
      }  elseif($data['Address Country Secondary Division']!=''){
	$country_in_other=new Country('find',$data['Address Country Secondary Division']);
	if($country_in_other->id!=244){
	  $data['Address Country Key']=$country_in_other->id;
	  $data['Address Country Secondary Division']='';
	}
      }
      else if($data['Address Town']!=''){
	$country_in_other=new Country('find',$data['Address Town']);
	if($country_in_other->id!=244){
	  $data['Address Country Key']=$country_in_other->id;
	  $data['Address Town']='';
	}
      }

      if($data['Address Country Key']=='')
	$data['Address Country Key']=$myconf['country_id'];
      
    }

    
    
    
    if( $data['Address Country Key'] and   Address::is_country_key($data['Address Country Key'])){

      $country=new Country('id',$data['Address Country Key']);
    }elseif( $data['Address Country Code']!='UNK'  and Address::is_country_code($data['Address Country Code'])){

      $country=new Country('code',$data['Address Country Code']);
    }elseif($data['Address Country 2 Alpha Code']!='XX' and  Address::is_country_2alpha_code($data['Address Country 2 Alpha Code'])){
      $country=new Country('2 alpha code',$data['Address Country 2 Alpha Code']);
    }else{      

      $country=new Country('find',$data['Address Country Name']);
    }
    
    //  print_r($country);

    $data['Address Country Key']=$country->id;
    $data['Address Country Code']=$country->data['Country Code']; 
    $data['Address Country 2 Alpha Code']=$country->data['Country 2 Alpha Code'];
    $data['Address Country Name']=$country->data['Country Name'];
    $data['Address World Region']=$country->data['World Region'];
    $data['Address Continent']=$country->data['Continent'];
    // print_r($data);exit;
    return $data;
  }

  /*
    Function: parse_street
    Parse a street line in it components (number,street name,street type, etc)

    Parameters:
    $line - _string_ 
    $country_code - (optional)  Country Code in DB

    Todo:
    Country Id not used jet
  */
  public static function parse_street($line,$country_code='UNK'){

    // print "********** $line\n";

    $number='';
    $name='';
    $direction='';
    $type='';

    //extract number
    $line=_trim($line);
    
    if(preg_match('/^\#?\s*\d+(\,\d+\-\d+|\\\d+|\/\d+)?(bis)?[a-z]?\s*/i',$line,$match)){

      $number=$match[0];
      $len=strlen($number);
      $name=substr($line,$len);
    }elseif(preg_match('/(\#|no\.?)?\s*\d+(bis)?[a-z]?\s*$/i',$line,$match)){
     // print "--------".$match[0]."-------------";
      $number=$match[0];
      $len=strlen($number)+1;
      $name=substr($line,0,strlen($line)-$len);

    }else{
      $name=$line;
      
    }
   
    $name=preg_replace('/^\s*,\s*/','',$name);

    $name=_trim($name);
    $number=_trim($number);
    $regex='/\s(street|st\.?)$/i';
    if(preg_match($regex,$name,$match)){
      $type="Street";
      $name=preg_replace($regex,'',$name);
    }
    
    if(preg_match('/\s(road|rd\.?)$/i',$name,$match)){
      $type="Road";
      $name=preg_replace('/\s(road|rd\.?)$/i','',$name);
    }
    if(preg_match('/\s(close)$/i',$name,$match)){
      $type="Close";
      $name=preg_replace('/\s(close)$/i','',$name);
    }
    $regex='/\s(Av\.?|avenue|ave\.?)$/i';
    if(preg_match($regex,$name,$match)){
      $type="Avenue";
      $name=preg_replace($regex,'',$name);
    }
   

    $name=mb_ucwords(_trim($name));
    $return_data=array(
		 'Address Street Number'=>$number
		 ,'Address Street Name'=>$name
		 ,'Address Street Type'=>$type
		 ,'Address Street Direction'=>$direction);
    //    print_r($return_data);
    return $return_data;
   
  }


  /*Function:prepare_DBfields
    Cleans address data, look for common errors
  */
  public static function prepare_DBfields($raw_data){
    return $raw_data;
  }
  /*Function: prepare_3line
    Cleans address data, look for common errors

    Parameters:
    $raw_data - _array_ Data to be parsed
    $untrusted - _boleean_ 
  */

  public static function prepare_3line($raw_data,$args='untrusted'){
    global $myconf;

    //       print "========== ADDEWESS PARSING ================\n";
    //  print_r($raw_data);


    if(!isset($raw_data['Address Line 1']))
      $raw_data['Address Line 1']='';
    if(!isset($raw_data['Address Line 2']))
      $raw_data['Address Line 2']='';
    if(!isset($raw_data['Address Line 3']))
      $raw_data['Address Line 3']='';
    $empty=true;
    
    if(count($raw_data)>0){
    
    foreach($raw_data as $key=>$val){
      if($val!='' and ($key!='Address Fuzzy' and $key!='Address Data Last Update'and $key!='Address Input Format' and $key!='Military Address'  )){
	//	print "NO EMTOY KEY $key\n";
	$empty=false;
	break;
      }
    }
    }
    
   

    $untrusted=(preg_match('/untrusted/',$args)?true:false);
    $debug=(preg_match('/debug/',$args)?true:false);


    $data=array();
    // Equivalente to base data --------------------------------------
    $data=array();
    $ignore_fields=array('Address Key','Address Data Last Update','Address Data Creation');
    $result = mysql_query("SHOW COLUMNS FROM `Address Dimension`");
    if (!$result) {
      echo 'Could not run query: ' . mysql_error();
      exit;
    }
    if (mysql_num_rows($result) > 0) {
      while ($row = mysql_fetch_assoc($result)) {
	if(!in_array($row['Field'],$ignore_fields))
	  $data[$row['Field']]=$row['Default'];
      }
    }
    //-------------------------------------------------------------------

    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$data)){
	$data[$key]=_trim($value);
      }
    }
    



    if($empty){


      $country=new Country('code','UNK');
      $raw_data['Address Country Name']=$country->data['Country Name'];

      $data['Address Country Name']=$country->data['Country Name'];
      $data['Address Country Key']=$country->id;
      $data['Address Fuzzy']='Yes';
      $data['Address Fuzzy Type']='All';
    }
    //--------------------------------------------------------------------------
    // Common errors related to the country

    

    if(preg_match('/^St. Thomas.*Virgin Islands$/i',$data['Address Town'])){
      $data['Address Country Name']='Virgin Islands, U.S.';
      $data['Address Town']='St. Thomas';
    }
    
    if(preg_match('/La Reunion/i',$data['Address Country Name'])){
      $data['Address Country Name']='France';
      if($data['Address Country Primary Division']=='')
	$data['Address Country Primary Division']='La Reunion';
    }
    if(preg_match('/SCOTLAND|wales/i',$data['Address Country Name']))
      $data['Address Country Name']='United Kingdom';
    if(preg_match('/^england$|^inglaterra$/i',$data['Address Country Name'])){
      $data['Address Country Name']='United Kingdom';
      if($data['Address Country Primary Division']=='')
	$data['Address Country Primary Division']='England';
    }else if(preg_match('/^nor.*ireland$|n\.{2}ireland/i',$data['Address Country Name'])){
      $data['Address Country Name']='United Kingdom';
      if($data['Address Country Primary Division']=='')
	$data['Address Country Primary Division']='Northen Ireland';
    }else if(preg_match('/^r.*ireland$|^s.*ireland|^eire$/i',$data['Address Country Name'])){
      $data['Address Country Name']='Ireland';
    }else if(preg_match('/me.ico|m.xico/i',$data['Address Country Name'])){
      $data['Address Country Name']='Mexico';
    }else if(preg_match('/scotland|escocia/i',$data['Address Country Name'])){
      
      $data['Address Country Name']='United Kingdom';
      if($data['Address Country Primary Division']=='')
	$data['Address Country Primary Division']='Scotland';
    }else if(preg_match('/.*\s+(w|g)ales$/i',$data['Address Country Name'])){
      $data['Address Country Name']='United Kingdom';
      if($data['Address Country Primary Division']=='')
	$data['Address Country Primary Division']='Wales';
    }else if(preg_match('/canarias$/i',$data['Address Country Name'])){
      $data['Address Country Name']='Spain';
      if($data['Address Country Primary Division']=='')
	$data['Address Country Primary Division']='Canarias';
    }else if(preg_match('/^Channel Islands$/i',$data['Address Country Name'])){

      if($data['Address Country Primary Division']!=''){
	$data['Address Country Name']=$data['Address Country Primary Division'];
	$data['Address Country Primary Division']='';
	
      }else if($data['Address Country Secondary Division']!=''){
	$data['Address Country Name']=$data['Address Country Secondary Division'];
	$data['Address Country Secondary Division']='';
	
      } else if($data['Address Town']!=''){
	$data['Address Country Name']=$data['Address Town'];
	$data['Address Town']='';
	
      }
      

      
    }
    //-------------------------------------------------------------------------
 

    
    
    if($data['Address Country Name']==''){
      if($myconf['country_2acode']=='GB'){
	
	//	if(preg_match('/norfork/i,'$data['Address Country Secondary Division]']))
	//  $data['Address Country Name']='United Kingdom';
	
	if(Address::is_valid_postcode($data['Address Postal Code'],30)){
	  //	  print "cacacaca";
	  $data['Address Country Primary Division']=_trim($data['Address Country Primary Division'].' '.$data['Address Country Name']);
	  $data['Address Country Name']='United Kingdom';
	  
	}elseif(Address::is_valid_postcode($data['Address Country Name'],30)){
	  $data['Address Country Primary Division']=_trim($data['Address Country Primary Division'].' '.$data['Address Postal Code']);
	  $data['Address Postal Code']=$data['Address Country Name'];
	  $data['Address Country Name']='United Kingdom';
	}
      }elseif($myconf['country_2acode']=='ES'){
	
	//if( preg_match('/^\d{5}$/',_trim($raw_data['Address Country Name'])) and ( _trim($data['Address Postal Code'])=='' or preg_match('/^(spain|Espa.a)$/i',_trim($data['Address Postal Code'])))){
	if( preg_match('/^\d{5}$/',_trim($raw_data['Address Country Name']))
	    and ( _trim($data['Address Postal Code'])=='' or preg_match('/^(spain|espa.{0,2}a|ESPA.{0,2}A)$/i',_trim($data['Address Postal Code'])) )
	    ){

	  $data['Address Postal Code']=$data['Address Country Name'];
	  $data['Address Country Code']='ESP';
	  $data['Address Country Key']=false;
	  if(preg_match('/^(spain|Espa.{0,2}a)$/i',_trim($data['Address Country Primary Division']))){
	    $data['Address Country Primary Division']='';
	  }
	  
	  if(preg_match('/^(spain|Espa.{0,2}a)$/i',_trim($data['Address Country Secondary Division']))){
	    $data['Address Country Secondary Division']='';
	  }
	}
      }
    }
	  
    
 
 

 

    $data=Address::prepare_country_data($data);
    // print_r($data);
    //exit;
    // foreach($country as $key=>$value){
    //  if(array_key_exists($key,$data)){
    //	$data[$key]=_trim($value);
    //  }
    // }

    if($data['Address Country Code']=='UNK'){
      $_tmp=preg_replace('/^,|[,\.]$/','',$raw_data['Address Country Name']);
      $tmp=new Country('find',$_tmp);
      if($tmp->data['Country Code']!='UNK'){
	$data['Address Country Key']=$tmp->id;
	$data=Address::prepare_country_data($data);
      }
    }
    
  

       
  


  
    $_p=$data['Address Postal Code'];

    if(preg_match('/^\s*BFPO\s*\d{1,}\s*$/i',$_p)){
      $data['Address Country Name']='UK';
      $data=Address::prepare_country_data($data);
    

    //$data['Address Country Name']=preg_replace('/^,|[,\.]$/','',$data['Address Country Name']);
    //$tmp=new Country('find',$data['Address Country Name']);
    //$data['Address Country Key']=$tmp->id;
 
    }
  



    // Ok the country is already guessed, wat else ok depending of the country letys gloing to try to get the orthers bits of the address


    
    /*      print "________----------_________---------\n"; */
    /*     print_r($data); */
    /*     print_r($raw_data); */
    /*      print "___________________________________\n"; */
    

    // pushh all address up

    if($untrusted){
      // if only one line put it in the first one
      $number_lines=0;
      if($raw_data['Address Line 1']!='')
	$number_lines++;
      if($raw_data['Address Line 2']!='')
	$number_lines++;
      if($raw_data['Address Line 3']!='')
	$number_lines++;

      switch($number_lines){
      case(1):
	if($raw_data['Address Line 2']!=''){
	  $raw_data['Address Line 1']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	}elseif($raw_data['Address Line 3']!=''){
	  $raw_data['Address Line 1']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']='';
	}
	break;
      }

      
      // Special case only one line no twown no division
      
      if(
	 $number_lines==1 and 
	 $data['Address Town']=='' and 
	 $data['Address Country Primary Division']=='' and
	 $data['Address Country Secondary Division']==''
	 ){
	// try to sepatate
	//split by worlds
	$words=preg_split('/\s+/',$raw_data['Address Line 1']);

	$num_words=count($words);
	if($num_words>1){
	  if(Address::is_country_d1(
				    $words[$num_words-1],
				    $data['Address Country Key']
				    )){
	    $data['Address Country Primary Division']=array_pop($words);
	    $num_words=count($words);
	  }
	}
	if($num_words>1){
	  if(Address::is_country_d2(
				    $words[$num_words-1],
				    $data['Address Country Key']
				    )){
	    $data['Address Country Secondary Division']=array_pop($words);
	    $num_words=count($words);
	  }
	}
	if($num_words>1){
	  if(Address::is_town(
			      $words[$num_words-1],
			      $data['Address Country Key']
			      )){
	    $data['Address Town']=array_pop($words);
	    $num_words=count($words);
	  }
	}
	$raw_data['Address Line 1']=join(' ',$words);



      }



      //Change town if misplaced
      
      if($data['Address Town']=='') {

	if(Address::is_town($raw_data['Address Line 3'],$data['Address Country Key']) ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']='';
	}else if(Address::is_town($data['Address Country Secondary Division'],$data['Address Country Key']) ){
	  $data['Address Town']=$data['Address Country Secondary Division'];
	  $data['Address Country Secondary Division']='';
	}


      }// End town missplaced



      if(preg_match('/^\d[a-z]?(bis)?\s*,/',$raw_data['Address Line 1'])){
	$raw_data['Address Line 1']=preg_replace('/\s*,\s*/',' ',$raw_data['Address Line 1']);
      }
      if(preg_match('/^\d[a-z]?(bis)?\s*,/',$raw_data['Address Line 2'])){
	$raw_data['Address Line 2']=preg_replace('/\s*,\s*/',' ',$raw_data['Address Line 2']);
      }
      if(preg_match('/^\d[a-z]?(bis)?\s*,/',$raw_data['Address Line 3'])){
	$raw_data['Address Line 3']=preg_replace('/\s*,\s*/',' ',$raw_data['Address Line 3']);
      }
    
      $raw_data['Address Line 1']=preg_replace('/,\s*$/',' ',$raw_data['Address Line 1']);
      $raw_data['Address Line 2']=preg_replace('/,\s*$/',' ',$raw_data['Address Line 2']);
      $raw_data['Address Line 3']=preg_replace('/,\s*$/',' ',$raw_data['Address Line 3']);


      // this is going to ve dirty
      //print_r($data);
    
      if(Address::is_street($raw_data['Address Line 2']) and  $raw_data['Address Line 1']!=''  and $raw_data['Address Line 3']==''  ){
	$tmp=preg_split('/\s*,\s*/i',$raw_data['Address Line 1']);
	if(count($tmp)==2 and !preg_match('/^\d*$/i',$tmp[0])   and !preg_match('/^\d*$/i',$tmp[1]) ){
	  $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 1']=$tmp[0];
	  $raw_data['Address Line 2']=$tmp[1];


	}

      }
      //  print_r($data);

      //print $raw_data['Address Line 1']."----------------\n";
      // print $raw_data['Address Line 2']."----------------\n";



      if($raw_data['Address Line 1']==''){ 
	if($raw_data['Address Line 2']==''){
	  // if line 1 and 2  has not data
	  $raw_data['Address Line 1']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']='';
      

	}else{

	  if($raw_data['Address Line 3']==''){

	    $raw_data['Address Line 1']=$raw_data['Address Line 2'];
	    $raw_data['Address Line 2']='';
	    
	  }else{
	    $raw_data['Address Line 1']=$raw_data['Address Line 2'];
	    $raw_data['Address Line 2']=$raw_data['Address Line 3'];
	    $raw_data['Address Line 3']='';
	  }


	}
      
      }else if($raw_data['Address Line 2']==''){
	$raw_data['Address Line 2']=$raw_data['Address Line 3'];
	$raw_data['Address Line 3']='';
      }


   


      //then volter alas address


      //lets do it as an experiment if the only line is 1 has data
      // split the data in that line  to see what happens
      if($raw_data['Address Line 1']!='' and $raw_data['Address Line 2']=='' and $raw_data['Address Line 3']==''){// one line
	$splited_address=preg_split('/\s*,\s*/i',$raw_data['Address Line 1']);
	
	if(count($splited_address)==1){
	  $raw_data['Address Line 3']=$splited_address[0];
	  $raw_data['Address Line 1']='';
	}else if(count($splited_address)==2){
	  // ok separeta bu on li if the sub partes are not like numbers
	  $parte_0=_trim($splited_address[0]);
	  $parte_1=_trim($splited_address[1]);
	  



	  $raw_data['Address Line 1']='';
	  if(Address::is_internal($parte_0) and Address::is_street($parte_1)){

	    $raw_data['Address Line 1']=$parte_0;
	    $raw_data['Address Line 3']=$parte_1;
	  }elseif(Address::is_internal($parte_1) and Address::is_street($parte_0)){
	    $raw_data['Address Line 1']=$parte_1;
	    $raw_data['Address Line 3']=$parte_0;
	  }elseif(Address::is_street($parte_1) and Address::is_street($parte_0)){
	    $raw_data['Address Line 3']=$parte_0.', '.$parte_1;
	  }elseif(Address::is_street($parte_0) and !Address::is_street($parte_1)){
	    $raw_data['Address Line 3']=$parte_0;
	    $data['Address Town Primary Division'].=', '.$parte_1;
	    $data['Address Town Primary Division']=preg_replace('/^, /','',$data['Address Town Primary Division']);
	  }else
	     $raw_data['Address Line 1']=$parte_0.', '.$parte_1;
	  

	

	  // exit ("$raw_data['Address Line 3']\n");
	}else if(count($splited_address)==3){
	  $raw_data['Address Line 1']=$splited_address[0];
	  $raw_data['Address Line 2']=$splited_address[1];
	  $raw_data['Address Line 3']=$splited_address[2];
	}
      
      }else if($raw_data['Address Line 1']!='' and $raw_data['Address Line 2']!='' and  $raw_data['Address Line 3']==''){
	$raw_data['Address Line 3']=$raw_data['Address Line 2'];
	$raw_data['Address Line 2']=$raw_data['Address Line 1'];
	$raw_data['Address Line 1']='';
      }else{

	// print_r($data);
	$raw_data['Address Line 1']=$raw_data['Address Line 1'];
	$raw_data['Address Line 2']=$raw_data['Address Line 2'];
	$raw_data['Address Line 3']=$raw_data['Address Line 3'];

      }

      // print("a1 $raw_data['Address Line 1'] a2 $raw_data['Address Line 2'] a3 $raw_data['Address Line 3'] \n");


 
    

      $data['Address Town']=$data['Address Town'];
      $data['Address Town Secondary Division']=$data['Address Town Secondary Division'];
      $data['Address Town Primary Division']=$data['Address Town Primary Division'];

      //  print "1:$raw_data['Address Line 1'] 2:$raw_data['Address Line 2'] 3:$raw_data['Address Line 3'] t:$data['Address Town'] \n";

      $f_a1=($raw_data['Address Line 1']==''?false:true);
      $f_a2=($raw_data['Address Line 2']==''?false:true);
      $f_a3=($raw_data['Address Line 2']==''?false:true);



      $f_t=($data['Address Town']==''?false:true);
      $f_ta=($data['Address Town Secondary Division']==''?false:true);
      $f_td=($data['Address Town Primary Division']==''?false:true);

      $f_c1=($data['Address Country Primary Division']==''?false:true);
      $f_c2=($data['Address Country Secondary Division']==''?false:true);
      $t_t=Address::is_town($data['Address Town']);
      $t_c1=Address::is_town($data['Address Country Primary Division']);
      $t_c2=Address::is_town($data['Address Country Secondary Division']);

      $s_a1=Address::is_street($raw_data['Address Line 1']);
      $s_a2=Address::is_street($raw_data['Address Line 2']);
      $s_a3=Address::is_street($raw_data['Address Line 3']);
      $i_a1=Address::is_internal($raw_data['Address Line 1']);
      $i_a2=Address::is_internal($raw_data['Address Line 2']);
      $i_a3=Address::is_internal($raw_data['Address Line 3']);


      // especial case when to town is presente but the first division seems to be a town
      if(!$f_t){
	if($t_c1 and !$f_c2) {
	  // town is in primary division
	  $data['Address Town']=$data['Address Country Primary Division'];
	  $data['Address Country Primary Division']='';
	  $t_c1=false;
	  $f_c1=false;
	  $f_t=true;
	  $t_t=true;
	}elseif($t_c2 and !$f_c1){
	  $data['Address Town']=$data['Address Country Secondary Division'];
	  $data['Address Country Secondary Division']='';
	  $t_c2=false;
	  $f_c2=false;
	  $f_t=true;
	  $t_t=true;
	  

	}
	

	  

      }


      // print "Street grade 1-$s_a1 2-$s_a2 3-$s_a3 \n";
      //   print "Internal grade 1-$i_a1 2-$i_a2 3-$i_a3 \n";
      //   print "Filled grade 1-$f_a1 2-$f_a2 3-$f_a3 \n";
      //   exit;    
      if(!$f_a1 and $f_a2 and $f_a3){
     
	if($s_a2 and $i_a3){
       
	  $_a=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']=$_a;
	}
       
      }


      //   exit;

      // super special case
      //  if(!$f_a1 and $f_a2 and $f_a3 and )
      //print("a1 $raw_data['Address Line 1'] a2 $raw_data['Address Line 2'] a3 $raw_data['Address Line 3'] \n");
      $town_filled=false;
      // caso 1 all filled a1,a2 and a3
      if($f_a1 and $f_a2 and $f_a3){ // caso 1 all filled a1,a2 and a3
	//print "AAAAAAAA\n";
	if($s_a1 and !$s_a2 and !$s_a3){ //caso    soo  (moviing 2 )
      
	  if(!$f_ta and !$f_td and !$f_t){ // caso ooo (towns)
	    //print "AAAAAAAA\n";
	    $town_filled=true;
	    $data['Address Town']=$raw_data['Address Line 3'];
	    $data['Address Town Secondary Division']=$raw_data['Address Line 2'];
	    $raw_data['Address Line 3']=$raw_data['Address Line 1'];
	    $raw_data['Address Line 2']='';
	    $raw_data['Address Line 1']='';

	  }else if(!$f_ta and !$f_td and $f_t){// caso oot
	
	    $data['Address Town Primary Division']=$raw_data['Address Line 3'];
	    $data['Address Town Secondary Division']=$raw_data['Address Line 2'];
	    $raw_data['Address Line 3']=$raw_data['Address Line 1'];
	    $raw_data['Address Line 2']='';
	    $raw_data['Address Line 1']='';

	  }else{
	    $raw_data['Address Line 3']=$raw_data['Address Line 1'].', '.$raw_data['Address Line 2'].', '.$raw_data['Address Line 3'];
	    $raw_data['Address Line 2']='';
	    $raw_data['Address Line 1']='';
	  
	  }
	}else if ((!$s_a1 and $s_a2 and !$s_a3) OR ($s_a1 and $s_a2 and !$s_a3)){ //caso    oso OR  sso  (move one)
	  //  print "HOLAAAAAAAAAAAA";
	  if($s_a1 and $s_a2 and !$f_a3 and $f_t){ 
	    $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	    $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	    $raw_data['Address Line 1']='';
	 
	  }elseif(!$f_ta and !$f_td and !$f_t){ // caso ooo (towns)
	    $data['Address Town']=$raw_data['Address Line 3'];
	    $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	    $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	    $raw_data['Address Line 1']='';
	  }else if(!$f_ta and !$f_td and $f_t){// caso oot
	    $data['Address Town Secondary Division']=$raw_data['Address Line 3'];
	    $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	    $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	    $raw_data['Address Line 1']='';
	  }else{
	    $raw_data['Address Line 3']=$raw_data['Address Line 2'].', '.$raw_data['Address Line 3'];
	    $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	    $raw_data['Address Line 1']='';
	  }
	}

      }elseif(!$f_a1 and $f_a2 and $f_a3){ // case xoo
	
	//   print "1 $raw_data['Address Line 1'] 2 $raw_data['Address Line 2'] 3 $raw_data['Address Line 3'] \n";
	if($s_a2 and   !$i_a3 and !$s_a3  ){

	  
	  if(!$f_ta and !$f_td and !$f_t){ // caso ooo (towns)

	    $data['Address Town']=$raw_data['Address Line 3'];
	    $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	    $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	    $raw_data['Address Line 1']='';
	  }else if(!$f_ta and !$f_td and $f_t){// caso oot

	    $data['Address Town Secondary Division']=$raw_data['Address Line 3'];
	    $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	    $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	    $raw_data['Address Line 1']='';

	  }else{
       
	    $raw_data['Address Line 3']=$raw_data['Address Line 2'].', '.$raw_data['Address Line 3'];
	    $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	    $raw_data['Address Line 1']='';
	  }

	  
	}



      }

  


    }

    //  print_r($raw_data); 

    // print_r($data); 
    //     exit; 
    


    if(preg_match('/^P\.o\.box\s+\d+$|^po\s+\d+$|^p\.o\.\s+\d+$/i',$data['Address Town Secondary Division'])){
      
      $po=$data['Address Town Secondary Division'];
      $data['Address Town Secondary Division']='';
      $po=preg_replace('/^P\.o\.box\s+|^po\s+|^p\.o\.\s+/i','PO BOX ',$po);
      if($raw_data['Address Line 1']=='')
	$raw_data['Address Line 1']=$po;
      else
	$raw_data['Address Line 1']=$po.', '.$raw_data['Address Line 1'];
    
    }



    

    switch($data['Address Country Key']){
    case(30)://UK
      // ok try to determine the city from aour super database of cities and towns

      if(preg_match('/Andover.*\sHampshire/i',$data['Address Town']))
	$data['Address Town']='Andover';

      if($town_filled){
	if(Address::is_country_d2($data['Address Town'],30) and Address::is_town($data['Address Town Secondary Division'],30)){
	  $data['Address Country Secondary Division']=$data['Address Town'];	
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	
      }

    

      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']='';
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	}

      }






      

    
      break;
    
    case(78)://Italy
      $data['Address Postal Code']=preg_replace('/italy|italia/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/\s/i','',$data['Address Postal Code']);

      if($data['Address Town']=='Padova'){
	$data['Address Country Primary Division']='Veneto';
	$data['Address Country Secondary Division']='Padova';
      }
      if($data['Address Town']=='Mestre'){
	$data['Address Country Primary Division']='Venezia';
	$data['Address Country Secondary Division']='Veneto';
      }
 
      if(preg_match('/Genova\s*(\- Ge)?/i',$data['Address Town'])){
	$data['Address Country Primary Division']='Genoa';
	$data['Address Country Secondary Division']='Liguria';
	$data['Address Town']='Genova';
      }
 
      if(preg_match('/Spilamberto/i',$raw_data['Address Line 3']) and preg_match('/Modena/i',$data['Address Town'])){
	$data['Address Country Primary Division']='Emilia-Romagna';
	$data['Address Country Secondary Division']='Modena';
	$data['Address Town']='Spilamberto';
	$raw_data['Address Line 3']='';
      }
 
      if(preg_match('/Pescia/i',$raw_data['Address Line 3']) and preg_match('/Toscana/i',$data['Address Town'])){
	$data['Address Country Primary Division']='Toscana';
	$data['Address Country Secondary Division']='Pistoia';
	$data['Address Town']='Pescia';
	$raw_data['Address Line 3']='';
      }

      if( preg_match('/Villasor.*Cagliari/i',$data['Address Town'])){
	$data['Address Country Primary Division']='Sardinia';
	$data['Address Country Secondary Division']='Cagliari';
	$data['Address Town']='Villasor';
      }
      if( preg_match('/Nocera Superiore/i',$data['Address Town'])){
	$data['Address Country Primary Division']='Campania';
	$data['Address Country Secondary Division']='Salerno';
	$data['Address Town']='Nocera Superiore';
      }
      if( preg_match('/^Vicenza$/i',$data['Address Town'])){
	$data['Address Country Primary Division']='Veneto';
	$data['Address Country Secondary Division']='Vicenza';
	$data['Address Town']='Vicenza';
      }

      if( preg_match('/^Rome$/i',$data['Address Town'])){
	$data['Address Country Primary Division']='Lazio';
	$data['Address Country Secondary Division']='Rome';
	$data['Address Town']='Rome';
      }
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      if(preg_match('/^\d{2}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='000'.$data['Address Postal Code'];
      if(preg_match('/^\d{3}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='00'.$data['Address Postal Code'];

      if(preg_match('/^\d{4}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='0'.$data['Address Postal Code'];
      break;
    case(75)://Ireland

      // print "address1: $raw_data['Address Line 1']\n";
      //print "address2: $raw_data['Address Line 2']\n";
      //print "address3: $raw_data['Address Line 3']\n";
      //print "townarea: $data['Address Town Secondary Division']\n";
      //print "town: $data['Address Town']\n";
      //    print "country_d2: $data['Address Country Secondary Division']\n";
      //      print "postcode: $data['Address Postal Code']\n";
    
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
    
    


      $data['Address Country Secondary Division']=_trim($data['Address Country Secondary Division']);
      $data['Address Postal Code']=preg_replace('/County COrK/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/^co\.\s*|Republique of Ireland|Louth Ireland|ireland/i','',$data['Address Postal Code']);
      $data['Address Country Secondary Division']=preg_replace('/^co\.\s*|republic of ireland|republic of|ireland/i','',$data['Address Country Secondary Division']);
      $data['Address Country Secondary Division']=preg_replace('/(co|county)\s+[a-z]+$/i','',$data['Address Country Secondary Division']);
      $data['Address Country Secondary Division']=preg_replace('/(co|county)\s+[a-z]+,?\s*(ireland)?/i','',$data['Address Country Secondary Division']);
      $data['Address Country Secondary Division'] =preg_replace('/(co|county)\s+[a-z]+$/i','',$data['Address Country Secondary Division']);

      $data['Address Postal Code']=preg_replace('/\,+\s*^ireland$/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/(co|county)\s+[a-z]+,?\s*(ireland)?/i','',$data['Address Postal Code']);
      $data['Address Town']=preg_replace('/(co|county)\s+[a-z]+$/i','',$data['Address Town']);

      if($data['Address Town']=='Cork')
	$data['Address Postal Code']='';

      $data['Address Postal Code']=preg_replace('/co\s*Donegal|eire|republic of ireland|rep\? of Ireland|n\/a|^ireland$|/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Country Secondary Division']=_trim($data['Address Country Secondary Division']);
      //print "country_d2: $data['Address Country Secondary Division']\n";
      $data['Address Town']=preg_replace('/\-?\s*eire|\s*\-?\s*ireland/i','',$data['Address Town']);
      //exit;
      if($data['Address Country Secondary Division']=='Wesstmeath')
	$data['Address Country Secondary Division']='Westmeath';

      if($data['Address Town']=='Wesstmeath' or $data['Address Town']=='Westmeath' ){
	$data['Address Town']='';
      }

    

      if(Address::is_town($data['Address Town Secondary Division'],$data['Address Country Key']) and Address::is_country_d2($data['Address Town'],$data['Address Country Key'])){
	$county_d2=$data['Address Town'];
	$data['Address Town']=$data['Address Town Secondary Division'];
	$data['Address Town Secondary Division']='';

      }
      


      $data['Address Postal Code']=preg_replace('/Rep.?of/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=str_replace(',','',$data['Address Postal Code']);
      $data['Address Postal Code']=str_replace('.','',$data['Address Postal Code']);
      $data['Address Postal Code']=str_replace('DUBLIN','',$data['Address Postal Code']);
      $data['Address Postal Code']=str_replace('N/A','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/Republic\s?of/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/Erie/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/county/i','',$data['Address Postal Code']);
    
      $data['Address Postal Code']=preg_replace('/^co/i','County ',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/\s{2,}/',' ',$data['Address Postal Code']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);

      $valid_postalcodes=array('D1','D2','D3','D4','D5','D6','D6w','D7','D8','D9','D10','D11','D12','D13','D14','D15','D16','D17','D18','D20','D22','D24');

      if($data['Address Postal Code']!=''){
	$sql="select `Country Secondary Division Name` as name from `Country Secondary Division Dimension` where  `Country Key`=75 and `Country Secondary Division Name` like '%".$data['Address Postal Code']."%'";
	//print "$sql\n";
    
	$result=mysql_query($sql);
	if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      
	  $data['Address Postal Code']='';
	  $data['Address Country Secondary Division']=$row['name'];

	}    
      }
      // delete unganted  postcodes
      if(preg_match('/COMAYORepublicof|COGALWAY|RepublicofTIPPERARY|Republiqueof|NCW|eire|WD3|123|CoKerry,EIRE|COCORK|COOFFALY|WICKLOW|CoKerry/i',$data['Address Postal Code']))
	$data['Address Postal Code']='';

      if(preg_match('/^co\.?\s+|^country\s+/i',$data['Address Postal Code'])){
	$data['Address Postal Code']='';
	if($data['Address Country Secondary Division']=='')
	  $data['Address Country Secondary Division']=$data['Address Postal Code'];
	$data['Address Postal Code']='';
      }

      $data['Address Town']=preg_replace('/\s+ireland\s*/i','',$data['Address Town']);
      $data['Address Country Secondary Division']=preg_replace('/\s+ireland\s*/i','',$data['Address Country Secondary Division']);
	
    
      $data['Address Town']=preg_replace('/co\.\s*/i','Co ',$data['Address Town']);
      $data['Address Town']=preg_replace('/county\s+/i','Co ',$data['Address Town']);

      // print "$data['Address Town']";
      $split_town=preg_split('/\s*-\s*|\s*,\s*/i',$data['Address Town']);
      if(count($split_town)==2){
	if(preg_match('/^co\s+/i',$split_town[1])){
	  if($data['Address Country Secondary Division']=='')
	    $data['Address Country Secondary Division']=$split_town[1];
	  $data['Address Town']=$split_town[0];
	}

      }


      if(preg_match('/^co\s+/i' ,$data['Address Town'])){
	if($data['Address Country Secondary Division']=='')
	  $data['Address Country Secondary Division']=$data['Address Town'];
	$data['Address Town']=preg_replace('/^co\s+/i','',$data['Address Town']);
      }
      
      $data['Address Country Secondary Division']=preg_replace('/co\.?\s+/i','',$data['Address Country Secondary Division']);
      $data['Address Country Secondary Division']=preg_replace('/county\s+/i','',$data['Address Country Secondary Division']);
    
      if(preg_match('/\s*Cork\sCity\s*/i',$data['Address Town Secondary Division'])){
	$data['Address Town Secondary Division']=='';
	if($data['Address Town']=='')
	  $data['Address Town']='Cock';
      }
    
      if(preg_match('/^dublin\s+\d+$/i',$data['Address Town Secondary Division'])){

	if($data['Address Town']=='')
	  $data['Address Town']='Dublin';
	if($data['Address Town Primary Division']=='')
	  $data['Address Town Primary Division']=preg_replace('/dublin\s+/i','',$data['Address Town Secondary Division']);
	if($data['Address Postal Code']==preg_replace('/dublin\s+/i','',$data['Address Town Secondary Division']))
	  $data['Address Postal Code']='';
	$data['Address Town Secondary Division']=='';
      }


      if(preg_match('/^dublin\s*\d{1,2}$/i',$data['Address Postal Code'])){
	$data['Address Postal Code']=preg_replace('/^dublin\s*/i','',$data['Address Postal Code']);
      }
      $data['Address Town']=_trim($data['Address Town']);
    
      //  print "$data['Address Town'] +++++++++++++++\n";
      $data['Address Town']=preg_replace('/\s*,?\s*Leinster/i','',$data['Address Town']);
      if(preg_match('/^dublin\s*6w$/i',$data['Address Town'])){
	$data['Address Postal Code']='D6W';
	$data['Address Town']='Dublin';
      }

      //  print "$data['Address Town'] +++++++++++++++\n";
      if(preg_match('/^dublin\s*\-\s*\d$/i',$data['Address Town'])){
	$data['Address Postal Code']=preg_replace('/^dublin\s*\-\s*/i','',$data['Address Town']);
	$data['Address Town']='Dublin';
      }

      if(preg_match('/^dublin\s*d?\d{1,2}$/i',$data['Address Town'])){
	$data['Address Postal Code']=preg_replace('/^dublin\s*/i','',$data['Address Town']);
	$data['Address Town']='Dublin';
      }
     
      if(is_numeric($data['Address Postal Code']))
	$data['Address Postal Code']='D'.$data['Address Postal Code'];


      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']='';
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	}
      }
      $data['Address Country Secondary Division']=mb_ucwords($data['Address Country Secondary Division']);

      $data['Address Postal Code']=str_replace('-','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/MUNSTER|County RK/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      break; 

    case(89)://Canada
      $data['Address Postal Code']=preg_replace('/\s*canada\s*/i','',$data['Address Postal Code']);

      if($data['Address Country Secondary Division']!='' and $data['Address Country Primary Division']==''){
	$data['Address Country Primary Division']=$data['Address Country Secondary Division'];
	$data['Address Country Secondary Division']='';
      }
      break;
    case(208)://Czech Republic
      $data['Address Postal Code']=preg_replace('/\s*Czech Republic\s*/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/\s*/i','',$data['Address Postal Code']);
      break;
    case(108)://Cypruss
      $data['Address Postal Code']=preg_replace('/\s*cyprus\s*/i','',$data['Address Postal Code']);

      $data['Address Postal Code']=preg_replace('/^cy\-?/i','',$data['Address Postal Code']);

      if($data['Address Town']=='Lefkosia (Nicosia)')
	$data['Address Town']='Nicosia';
      if($data['Address Town']=='Limassol City Centre')
	$data['Address Town']='Limassol';
       
      if($data['Address Town']=='Cyprus')
	$data['Address Town']='';

      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']='';
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	}
      }

      break;
    case(240):
      $data['Address Town']=preg_replace('/\,?\s*Guernsey Islands$/i','',$data['Address Town']);
      $data['Address Town']=preg_replace('/\,?\s*Guernsey$/i','',$data['Address Town']);
      $data['Address Town']=preg_replace('/\,?\s*Channel Islands$/i','',$data['Address Town']);
      $data['Address Town']=preg_replace('/\,?\s*CI$/i','',$data['Address Town']);
      $data['Address Town']=preg_replace('/\,?\s*C.I.$/i','',$data['Address Town']);

      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  if(!preg_match('/^rue\s/i',$raw_data['Address Line 3'])){
	    $data['Address Town']=$raw_data['Address Line 3'];
	    $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	    $raw_data['Address Line 2']='';
	  }
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	}

      

      
      }
     




      break;
    case(104):// Greece
      $data['Address Postal Code']=preg_replace('/greece/i','',$data['Address Postal Code']);

      $data['Address Postal Code']=preg_replace('/^(GK|T\.?k\.?)/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/\s/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);

      if(preg_match('/^(Attica|Ionian Islands)$/i',$data['Address Town']))
	$data['Address Town']='';
      if($data['Address Country Primary Division']=='Attoka'){
	$data['Address Country Primary Division']='Attica';

      }
      if($data['Address Town']=='Athens')
	$data['Address Country Primary Division']='Attica';
      if($data['Address Town']=='Salamina')
	$data['Address Country Primary Division']='Attica';
      if($data['Address Town']=='Corfu'){
	$data['Address Town']='';
	$data['Address Country Primary Division']='Ionian Islands';
	$data['Address Country Secondary Division']='Corfu';
      }
      if($data['Address Town']=='Kefalonia')
	$data['Address Country Primary Division']='Ionian Islands';
      if($data['Address Town']=='Thessaloniki')
	$data['Address Country Primary Division']='Central Macedonia';

      if($data['Address Town']=='Xania - Krete'){
	$data['Address Country Primary Division']='Crete';
	$data['Address Town']='Xania';
      }
      if($data['Address Town']=='Salamina - Tsami'){
	$data['Address Country Primary Division']='Attica';
	$data['Address Town']='Salamina';
	if($data['Address Town Secondary Division']=='')
	  $data['Address Town Secondary Division']='Tsami';
      }


      break;
      
    case(229)://USA
      if($data['Address Country Secondary Division']!='' and $data['Address Country Primary Division']==''){
	$data['Address Country Primary Division']=$data['Address Country Secondary Division'];
	$data['Address Country Secondary Division']='';
      }
      $data['Address Town']=_trim($data['Address Town']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      //apo address
      if(preg_match('/^(09|96|340)\d+$/',$data['Address Postal Code'])){
	
	
	$military_base='Yes';
	
	$raw_data['Address Line 1']=_trim($raw_data['Address Line 1'].' '.$raw_data['Address Line 2'].' '.$raw_data['Address Line 3'].' '.$data['Address Town'].' '.$data['Address Country Secondary Division'].' '.$data['Address Country Primary Division']);
	$raw_data['Address Line 2']='';
	$raw_data['Address Line 3']='';
	$data['Address Town']='';
	$data['Address Country Secondary Division']='';
	$data['Address Country Primary Division']='';
	$military_installation['address']=$raw_data['Address Line 1'];
	$military_installation['military base country key']='';
	$military_installation['military base postal code']=$data['Address Postal Code'];
	if(preg_match('/apo ae$/i',$raw_data['Address Line 1']) or preg_match('/\sapo ae\s+/i',$raw_data['Address Line 1'])){
	  $raw_data['Address Line 1']=_trim(preg_replace('/apo ae/i','',$raw_data['Address Line 1']));
	  $military_installation['military base type']='APO AE';
	}
      }
      

    
      $data['Address Town']=preg_replace('/Lousiana/i','Louisiana',$data['Address Town']);
      
      $data['Address Country Primary Division']=_trim($data['Address Country Primary Division']);
      if(preg_match('/^[a-z]\s*[a-z]$/i',$data['Address Country Primary Division']))
	$data['Address Country Primary Division']=preg_replace('/\s/','',$data['Address Country Primary Division']);
    
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/united states of america/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/\s*u\s*s\s*a\s*|^United States\s+|United Stated|usa|^united states$|^united states of america$|^america$/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
    
      if($data['Address Country Primary Division']==''){
	$regex='/\s*\-?\s*[a-z]{2}\.?\s*\-?\s*/i';
	if(preg_match($regex,$data['Address Postal Code'],$match)){
	  $data['Address Country Primary Division']=preg_replace('/[^a-z]/i','',$match[0]);
	  $data['Address Postal Code']=preg_replace($regex,'',$data['Address Postal Code']);
	}
	$regex='/\([a-z]{2}\)/i';
	if(preg_match($regex,$data['Address Town'],$match)){
	  $data['Address Country Primary Division']=preg_replace('/[^a-z]/i','',$match[0]);
	  $data['Address Town']=preg_replace($regex,'',$data['Address Town']);
	}
	$regex='/\s{1,}\-?\s*[a-z]{2}\.?$/i';
	if(preg_match($regex,$data['Address Town'],$match)){
	  $data['Address Country Primary Division']=preg_replace('/[^a-z]/i','',$match[0]);
	  $data['Address Town']=preg_replace($regex,'',$data['Address Town']);
	}
	if(Address::is_country_d1($data['Address Town'],229) and $data['Address Town Secondary Division']!=''){
	  $data['Address Country Primary Division']=$data['Address Town'];
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
      }


      //   print "$data['Address Postal Code'] ******** ";
      if($data['Address Postal Code']=='' and preg_match('/\s*\d{4,5}\s*/',$data['Address Town'],$match)){
	$data['Address Postal Code']=trim(trim($match[0]));
	$data['Address Town']=_trim(preg_replace('/\s*\d{4,5}\s*/','',$data['Address Town']));
      }

      $data['Address Town']=preg_replace('/\s*\-\s*$/','',$data['Address Town']);

      $town_split=preg_split('/\s*\-\s*|\s*,\s*/',$data['Address Town']);

      $data['Address Country Primary Division']=_trim($data['Address Country Primary Division']);

      if(count($town_split)==2 and Address::is_country_d1($town_split[1],229)){

	$data['Address Country Primary Division']=$town_split[1];
	$data['Address Town']=$town_split[0];

      

      }
    
      
      
      if($data['Address Country Primary Division']=='N Y')
	$data['Address Country Primary Division']='New York';

      $states=array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');
      if(strlen($data['Address Country Primary Division'])==2){
	if (array_key_exists(strtoupper($data['Address Country Primary Division']), $states)) {
	  $data['Address Country Primary Division']=$states[strtoupper($data['Address Country Primary Division'])];
	}
      }
    
      if($data['Address Country Primary Division']==$data['Address Country Secondary Division'])
	$data['Address Country Secondary Division']='';
      
      if($data['Address Town Primary Division']=='Brooklyn' and $data['Address Town']=='New York'){
	$data['Address Country Primary Division']='New York';
      }
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      if(preg_match('/^d{4}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='0'.$data['Address Postal Code'];
    
      break;
    case(105)://Croatia
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/croatia/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/^hr-?/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      break;
    case(160)://Portugal
    
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/portugal/i','',$data['Address Postal Code']);
      $data['Address Town']=preg_replace('/\-?\s*portugal/i','',$data['Address Town']);
    

      if($data['Address Postal Code']=='' and preg_match('/\s*\d{4}\s*/',$data['Address Town'],$match)){
	$data['Address Postal Code']=trim(trim($match[0]));
	$data['Address Town']=_trim(preg_replace('/\s*\d{4}\s*/','',$data['Address Town']));
      }
    

      //   if(preg_match('/algarve/i'$data['Address Town']))
    

      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	  $raw_data['Address Line 1']='';
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	}
      }




      break;
    case(21)://Belgium
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/belgium/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/^b\-?/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $t=preg_split('/\s*,\s*/',$data['Address Town']);
      if(count($t)==2){
	if(Address::is_country_d1($t[1],$data['Address Country Key'])){
	  $data['Address Country Primary Division']=$t[1];
	  $data['Address Town']=$t[0];
	}


      }

      $data['Address Town']=_trim($data['Address Town']);
      if(Address::is_country_d1($data['Address Town'],$data['Address Country Primary Division']) and $data['Address Country Primary Division']==''  and ($raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='') ){
	$data['Address Country Primary Division']=$data['Address Town'];
	$data['Address Town']='';

      }
      if($data['Address Town']=='West Vlaanderen')
	$data['Address Town']=='West-Vlaanderen';

      if(Address::is_country_d1($data['Address Town'],$data['Address Country Primary Division']) and $data['Address Country Primary Division']==''  and $data['Address Town Secondary Division']!=''  ){
	$data['Address Country Primary Division']=$data['Address Town Secondary Division'];
	$data['Address Town Secondary Division']='';

      }




      break;


    case(80)://Austria
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/a\-?/i','',$data['Address Postal Code']);
      $data['Address Town']=_trim($data['Address Town']);
      if(Address::is_country_d1($data['Address Town'],$data['Address Country Key']) and $data['Address Country Primary Division']==''  and ($raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='') ){
	$data['Address Country Primary Division']=$data['Address Town'];
	$data['Address Town']='';

      }
      if(Address::is_country_d1($data['Address Town'],$data['Address Country Primary Division']) and $data['Address Country Primary Division']==''  and $data['Address Town Secondary Division']!=''  ){
	$data['Address Country Primary Division']=$data['Address Town Secondary Division'];
	$data['Address Town Secondary Division']='';

      }




      break;
    case(15)://Australia
      $data['Address Postal Code']=preg_replace('/\s*australia\s*/i','',$data['Address Postal Code']);
      $regex='/\(QLD\)/i';
      if(preg_match($regex,$data['Address Town'])){
	$data['Address Country Primary Division']='Queensland';
	$data['Address Town']=preg_replace($regex,'',$data['Address Town']);
      }
      $regex='/, Western Australia/i';
      if(preg_match($regex,$data['Address Town'])){
	$data['Address Country Primary Division']='Western Australia';
	$data['Address Town']=preg_replace($regex,'',$data['Address Town']);
      }

      if($data['Address Country Secondary Division']='' and $data['Address Country Primary Division']=='' ){
	$data['Address Country Primary Division']=$data['Address Country Secondary Division'];
	$data['Address Country Secondary Division']='';
      }
    



      $data['Address Town']=_trim($data['Address Town']);

      if(Address::is_country_d1($data['Address Town'],15) and $data['Address Town Secondary Division']!=''){
	$data['Address Country Primary Division']=$data['Address Town'];
	$data['Address Town']=$data['Address Town Secondary Division'];
	$data['Address Town Secondary Division']='';
	
      }


      if(Address::is_country_d1($data['Address Town'],15) and $data['Address Country Primary Division']==''  and ($raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='') ){
	$data['Address Country Primary Division']=$data['Address Town'];
	$data['Address Town']='';

      }

      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	  $raw_data['Address Line 1']='';
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	}
      }







      break;
    case(47)://Spain
      if(preg_match('/Majorca/i',$data['Address Town'])){
	$data['Address Country Secondary Division']='Islas Baleares';
	$data['Address Country Primary Division']='Islas Baleares';
	$data['Address Town']='';
      }
      if(preg_match('/Balearic Islands|Balearic Island/i',$data['Address Country Primary Division']))
	$data['Address Country Primary Division']='Balearic Islands';
      if(preg_match('/Balearic Islands|Balearic Island/i',$data['Address Country Secondary Division']))
	$data['Address Country Secondary Division']='Balearic Islands';




      if(preg_match('/Baleares/i',$raw_data['Address Line 3']) and preg_match('/Palma de Mallorca/i',$raw_data['Address Line 2'])){
	$data['Address Town']='Palma de Mallorca';
	$raw_data['Address Line 3']='';
	$raw_data['Address Line 2']='';
	$data['Address Country Primary Division']='Balearic Islands';
      }




      if(preg_match('/Zugena - Provincia Almeria/i',$data['Address Town'])){
	$data['Address Country Secondary Division']='Almeria';
	$data['Address Town']='Zugena';
      }
      if(preg_match('/Hinojares - Juen/i',$data['Address Town'])){
	$data['Address Country Secondary Division']='Jaen';
	$data['Address Town']='Hinojares';
      }


      if(preg_match('/Mijas Costa, Malaga/i',$data['Address Town'])){
	$data['Address Country Secondary Division']='Malaga';
	$data['Address Town']='Mijas Costa';
      }
      if(preg_match('/Calvia - Mallorca/i',$data['Address Town'])){
	$data['Address Town']='Calvia';
	$data['Address Country Primary Division']='Balearic Islands';
      } 

      if(preg_match('/Ciutadella - Menorca/i',$data['Address Town'])){
	$data['Address Town']='Ciutadella';
	$data['Address Country Primary Division']='Balearic Islands';
      } 
      if(preg_match('/Sax\s*(Alicante)/i',$data['Address Town'])){
	$data['Address Town']='Sax';
	$data['Address Country Secondary Division']='Alicante';
      } 


      if(preg_match('/malaga/i',$data['Address Town'])){
	if(preg_match('/Marbella/i',$raw_data['Address Line 3'])){
	  $raw_data['Address Line 3']='';
	  $data['Address Town']='Marbella';
	}

	 

      }

      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/spain/i','',$data['Address Postal Code']);

     
      if($data['Address Postal Code']=='' and preg_match('/\s*\d{4,5}\s*/',$data['Address Town'],$match)){
	$data['Address Postal Code']=_trim($match[0]);
	$data['Address Town']=_trim(preg_replace('/\s*\d{4,5}\s*/','',$data['Address Town']));
      }

    


      if(preg_match('/^\d{4}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='0'.$data['Address Postal Code'];

      $data['Address Country Primary Division']=_trim(preg_replace('/^Adaluc.a$/i','Andalusia',_trim($data['Address Country Primary Division'])));

      $data['Address Town']=_trim($data['Address Town']);

      if(preg_match('/El Cucador/i',$data['Address Town'])){
	$data['Address Town Secondary Division']='El Cucador';
	$data['Address Town']='Zurgena';
	$data['Address Country Secondary Division']='Almeria';
	$data['Address Country Primary Division']='Andalusia';
	$data['Address Postal Code']='04661';
	if($raw_data['Address Line 2']=='Cepsa Garage (Zugena)')
	  $raw_data['Address Line 2']='';
      }
      if(preg_match('/^Arona$/i',$data['Address Town'])){
	$data['Address Country Secondary Division']='Santa Cruz de Tenerife';
	$data['Address Country Primary Division']='Islas Canarias';

      }
      if(preg_match('/^Ceuta$/i',$data['Address Town'])){

	$data['Address Country Primary Division']='Ceuta';

      }




      break;
    case(126)://Malta
      $data['Address Postal Code']=preg_replace('/malta/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/\s/i','',$data['Address Postal Code']);

      if(preg_match('/[a-z]*/i',$data['Address Postal Code'],$ap) and preg_match('/[0-9]{1,}/i',$data['Address Postal Code'],$xxx))
	$data['Address Postal Code']=$ap[0].' '.$xxx[0];

      $data['Address Town']=preg_replace('/-?\s*malta|gozo\s*\-?/i','',$data['Address Town']);

      $data['Address Town']=_trim($data['Address Town']);

      break;
    case(110)://Latvia
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/Latvia/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/LV\s*\-?\s*/i','',$data['Address Postal Code']);
      $data['Address Town']=_trim($data['Address Town']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      if(preg_match('/^\d{4}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='LV-'.$data['Address Postal Code'];
      break;

    case(117)://Luxembourg
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/Luxembourg/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/L\s*\-?\s*/i','',$data['Address Postal Code']);
      $data['Address Town']=preg_replace('/\-?\s*Luxembourg/i','',$data['Address Town']);
      if($data['Address Town']=='')
	$data['Address Town']='Luxembourg';
      $data['Address Town']=_trim($data['Address Town']);
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      if(preg_match('/^\d{4}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='L-'.$data['Address Postal Code'];
      break;
    case(165)://France
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/FRANCE|french republic/i','',$data['Address Postal Code']);
      if($data['Address Postal Code']=='' and preg_match('/\s*\d{4,5}\s*/',$data['Address Town'],$match)){
	$data['Address Postal Code']=trim(trim($match[0]));
	$data['Address Town']=preg_replace('/\s*\d{4,5}\s*/','',$data['Address Town']);
      }

      if(preg_match('/Digne les Bains|Dignes les Bains/i',$data['Address Town']))
	$data['Address Town']='Digne-les-Bains';

      $data['Address Town']=preg_replace('/,\s*france\s*$/i','',$data['Address Town']);

      if($data['Address Town']=='St Cristophe - Charante'){
	$data['Address Town']='St Cristophe';
	$data['Address Country Secondary Division']='Charente';
	$data['Address Country Primary Division']='Poitou-Charentes';
      }
      if($data['Address Town']=='Cauro - Corse Du Sud'){
	$data['Address Town']='Cauro';
	$data['Address Country Secondary Division']='Corse Du Sud';
	$data['Address Country Primary Division']='Corse';
      }

      if($data['Address Town']=='Charente'){
	$data['Address Town']='';
	$data['Address Country Secondary Division']='Charente';
	$data['Address Country Primary Division']='Poitou-Charentes';
      }

      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	  $raw_data['Address Line 1']='';
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	}
      }
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      if(preg_match('/^\d{4}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='0'.$data['Address Postal Code'];
      break;

    case(196)://Switzerland
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/Switzerland/i','',$data['Address Postal Code']);

      if(preg_match('/^\d{4}\s+/',$data['Address Town'],$match)){
	if($data['Address Postal Code']=='' or $data['Address Postal Code']==trim($match[0])){
	  $data['Address Postal Code']=trim($match[0]);
	  $data['Address Town']=preg_replace('/^\d{4}\s+/','',$data['Address Town']);
	}
      }
    
      $data['Address Postal Code']=preg_replace('/^CH\-/i','',$data['Address Postal Code']);
      break;
    case(193)://Findland
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/findland|finland/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/^fi\s*\-?\s*/i','',$data['Address Postal Code']);

      if($raw_data['Address Line 3']=='Klaukkala' and $data['Address Town']=='Nurmijarvi'){
	$raw_data['Address Line 3']='';
	$data['Address Town']='Klaukkala';
      }
      if(preg_match('/^\d{3}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='00'.$data['Address Postal Code'];

      if(preg_match('/^\d{4}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='0'.$data['Address Postal Code'];

      break;
    

    case(242)://Isle of man
      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']='';
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	}
      
      }





  
      break;


    case(241)://Jersey

      $data['Address Town']=preg_replace('/^jersey$|^jersey\s*c\.?i\.?$/i','',$data['Address Town']);
      $data['Address Town']=preg_replace('/\,?\s*Channel Islands$/i','',$data['Address Town']);
      $data['Address Town']=preg_replace('/\,?\s*CI$/i','',$data['Address Town']);
      $data['Address Town']=preg_replace('/\,?\s*C.I.$/i','',$data['Address Town']);
      $data['Address Town']=preg_replace('/\-?\s*jersey$/i','',$data['Address Town']);
      $data['Address Country Secondary Division']=preg_replace('/\-?\s*jersey$|Jersy Channel Isles/i','',$data['Address Country Secondary Division']);
      //  print "1$raw_data['Address Line 1'] 2$raw_data['Address Line 2'] 3$raw_data['Address Line 3']\n";
      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	  $raw_data['Address Line 1']='';
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	}
      }






      $data['Address Town']=_trim($data['Address Town']);
      if($data['Address Town Secondary Division']=='' and  preg_match('/\w+\.?\s*St\.? Helier$/i',$data['Address Town']) ){
	$data['Address Town Secondary Division']=_trim( preg_replace('/St\.? Helier$/i','',$data['Address Town']));
	$data['Address Town']='St Helier';
      }

      $data['Address Town Secondary Division']=preg_replace('/\./','',$data['Address Town Secondary Division']);
      $data['Address Town']=preg_replace('/^St\s{1,}/','St. ',$data['Address Town']);
  
      break;

    case(171)://Sweden
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/sweden/i','',$data['Address Postal Code']);

      $data['Address Postal Code']=preg_replace('/^SE\-?/i','',$data['Address Postal Code']);
      if($data['Address Town']=='Malmo')
	$data['Address Town']='Malmö';
      if($data['Address Country Secondary Division']=='Sweden')
	$data['Address Country Secondary Division']='';
      if(preg_match('/Skaraborg/i',$data['Address Town']))
	$data['Address Town']='';
  
      $data['Address Postal Code']=preg_replace('/\s/','',$data['Address Postal Code']);

      if(Address::is_country_d1($data['Address Town'],171) and   $raw_data['Address Line 1']='' and $raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='' ){
	$data['Address Country Primary Division']=$data['Address Town'];
	$raw_data['Address Line 3']=$raw_data['Address Line 2'];
	$raw_data['Address Line 2']='';
      }
      if(Address::is_country_d1($data['Address Town'],171) and   $raw_data['Address Line 1']!='' and $raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='' ){
	$data['Address Country Primary Division']=$data['Address Town'];
	$raw_data['Address Line 3']=$raw_data['Address Line 2'];
	$raw_data['Address Line 2']=$raw_data['Address Line 1'];
	$raw_data['Address Line 1']='';
      }

      if($data['Address Country Secondary Division']!='' and $data['Address Country Primary Division']==''){
	$data['Address Country Primary Division']=$data['Address Country Secondary Division'];
	$data['Address Country Secondary Division']='';
      }

      $data['Address Postal Code']=preg_replace('/\s/','',$data['Address Postal Code']);

      break;
    case(149)://Norway
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/norway/i','',$data['Address Postal Code']);

      if(preg_match('/^no.\d+$/i',$data['Address Town'])){
	if($data['Address Postal Code']==''){
	  $data['Address Postal Code']=$data['Address Town'];
	  $data['Address Town']='';
	}
      }
      $data['Address Postal Code']=preg_replace('/^NO\s*\-?\s*/i','',$data['Address Postal Code']);

      $data['Address Postal Code']=preg_replace('/^N\-/i','',$data['Address Postal Code']);
      if(preg_match('/^\d{3}$/',$data['Address Postal Code']))
	$data['Address Postal Code']='0'.$data['Address Postal Code'];


      break; 
    case(2)://Netherlands
      $data['Address Town']=preg_replace('/Noord Brabant/i','Noord-Brabant',$data['Address Town']);
      $data['Address Country Primary Division']=preg_replace('/Noord Brabant/i','Noord-Brabant',$data['Address Country Primary Division']);
      $data['Address Country Secondary Division']=preg_replace('/Noord Brabant/i','Noord-Brabant',$data['Address Country Secondary Division']);
      $data['Address Town']=preg_replace('/Zuid Holland/i','Zuid-Holland',$data['Address Town']);
      $data['Address Country Primary Division']=preg_replace('/Zuid Holland/i','Zuid-Holland',$data['Address Country Primary Division']);
      $data['Address Country Secondary Division']=preg_replace('/Zuid Holland/i','Zuid-Holland',$data['Address Country Secondary Division']);
      $data['Address Town']=preg_replace('/Noord Holland/i','Noord-Holland',$data['Address Town']);
      $data['Address Country Primary Division']=preg_replace('/Noord Holland/i','Noord-Holland',$data['Address Country Primary Division']);
      $data['Address Country Secondary Division']=preg_replace('/Noord Holland/i','Noord-Holland',$data['Address Country Secondary Division']);
      $data['Address Town']=preg_replace('/Gerderland/i','Gelderland',$data['Address Town']);


      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/Netherlands|holland/i','',$data['Address Postal Code']);

      if($data['Address Postal Code']==''){
	if(preg_match('/\s*\d{4,6}\s*[a-z]{2}\s*/i',$data['Address Town'],$match2))
	  $data['Address Postal Code']=_trim($match2[0]);
      }
      $data['Address Postal Code']=strtoupper($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/\s/','',$data['Address Postal Code']);
      if(preg_match('/^\d{4}[a-z]{2}$/i',$data['Address Postal Code'])){
	$data['Address Town']=str_replace($data['Address Postal Code'],'',$data['Address Town']);
	$data['Address Town']=str_replace(strtolower($data['Address Postal Code']),'',$data['Address Town']);
	$_postcode=substr($data['Address Postal Code'],0,4).' '.substr($data['Address Postal Code'],4,2);
	$data['Address Postal Code']=$_postcode;
	$data['Address Town']=str_replace($data['Address Postal Code'],'',$data['Address Town']);
	$data['Address Town']=str_replace(strtolower($data['Address Postal Code']),'',$data['Address Town']);

      }
      $data['Address Town']=_trim($data['Address Town']);
      if(Address::is_country_d1($raw_data['Address Line 3'],2) and $data['Address Country Primary Division']=='' and $data['Address Town']==''   and ($raw_data['Address Line 1']!='' and $raw_data['Address Line 2']!='') ){
	$data['Address Country Primary Division']=$raw_data['Address Line 3'];
	$raw_data['Address Line 3']='';

      }

      if(Address::is_country_d1($data['Address Town'],2) and $data['Address Country Primary Division']=='' and (($raw_data['Address Line 1']!='' and $raw_data['Address Line 2']!='') or ($raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='') or ($raw_data['Address Line 1']!='' and $raw_data['Address Line 3']!='')  )   ){
	$data['Address Country Primary Division']=$data['Address Town'];
	$data['Address Town']='';

      }
   

      if($data['Address Town']=='NH'){
	$data['Address Country Primary Division']='North Holland';
	$data['Address Town']='';
      }

      if($data['Address Town']=='Zuid Holland'){
	$data['Address Country Primary Division']='Zuid Holland';
	$data['Address Town']='';
      }
      similar_text($data['Address Country Primary Division'],$data['Address Country Secondary Division'],$w);
      if($w>90){
	$data['Address Country Secondary Division']='';
      }

      if($data['Address Country Primary Division']=='' and $data['Address Country Secondary Division']!=''){
	$data['Address Country Primary Division']=$data['Address Country Secondary Division'];
	$data['Address Country Secondary Division']='';
      }

      if(preg_match('/Zuid.Holland|ZuidHolland/i',$data['Address Country Primary Division']))
	$data['Address Country Primary Division']='Zuid Holland';


      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	  $raw_data['Address Line 1']='';
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	  $raw_data['Address Line 3']=$raw_data['Address Line 1'];
	  $raw_data['Address Line 1']='';
	}
      }



      $town_split=preg_split('/\s*\-\s*|\s*,\s*/',$data['Address Town']);
      if(count($town_split)==2 and Address::is_country_d1($town_split[1],2)){
	$data['Address Country Primary Division']=$town_split[1];
	$data['Address Town']=$town_split[0];
      }
 
      if($raw_data['Address Line 1']!='' and $raw_data['Address Line 2']=='' and $raw_data['Address Line 3']==''){
	$raw_data['Address Line 3']=$raw_data['Address Line 1'];
	$raw_data['Address Line 1']='';
      }




      break; 


    case(177):// Germany
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/germany/i','',$data['Address Postal Code']);
      if($data['Address Country Secondary Division']!='' and $data['Address Country Primary Division']==''){
	$data['Address Country Primary Division']=$data['Address Country Secondary Division'];
	$data['Address Country Secondary Division']='';
      }
      

      $data['Address Town']=preg_replace('/NRW\s*$/i','',$data['Address Town']);


      if(preg_match('/^berlin$/i',$data['Address Town']))
	$data['Address Country Primary Division']='Berlin';
      if(preg_match('/^Hamburg$/i',$data['Address Town']))
	$data['Address Country Primary Division']='Hamburg';
      if(preg_match('/^Bremen$/i',$data['Address Town']))
	$data['Address Country Primary Division']='Bremen';

      if(preg_match('/^Nuernberg$/i',$data['Address Town']))
	$data['Address Town']='Nürnberg';
    
      if(preg_match('/^Osnabruek$/i',$data['Address Town'])){
	$data['Address Country Primary Division']='Niedersachsen';
	$data['Address Town']='Osnabrück';
      }
      if(preg_match('/^bavaria$/i',$data['Address Country Primary Division']))
	$data['Address Country Primary Division']='Bayern';


      $regex='/^\s*\d{5}\s+|\s+\d{5}\s*$/';
      if(preg_match($regex,$data['Address Town'],$match)){
	if($data['Address Postal Code']=='')$data['Address Postal Code']=trim($match[0]);
	$data['Address Town']=preg_replace($regex,'',$data['Address Town']);
      }


      if($data['Address Country Primary Division']==''){
	$data['Address Country Primary Division']=Address::get_country_d1_name($data['Address Town'],177);
      

      }



      break;
    case(201)://Denmark
      // FIx postcode in town
      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/denmark|Demnark/i','',$data['Address Postal Code']);
      $data['Address Postal Code']=preg_replace('/^dk\s*\-?\s*/i','',$data['Address Postal Code']);
      $data['Address Town']=_trim($data['Address Town']);

      if($data['Address Postal Code']=='' and preg_match('/^\d{4}\s+/',$data['Address Town'],$match)){
	$data['Address Postal Code']=trim($match[0]);
	$data['Address Town']=preg_replace('/^\d{4}\s+/','',$data['Address Town']);
      }

      $regex='/\s*2610 Rodovre\s*/i';
      if(preg_match($regex,$data['Address Town'],$match)){
	$data['Address Town']='Rodovre';
	$data['Address Postal Code']='2610';
      }
      $regex='/KBH K|Kobenhavn/i';
      if(preg_match($regex,$data['Address Town'],$match)){
	$data['Address Town']='Kobenhavn';
      }
      $regex='/Copenhagen/i';
      if(preg_match($regex,$data['Address Town'],$match)){
	$data['Address Town']='Copenhagen';
      }
      $regex='/Aarhus C/i';
      if(preg_match($regex,$data['Address Town'],$match)){
	$data['Address Town Secondary Division']='Aarhus C';
	$data['Address Town']='Aarhus';
      }


      $regex='/Odense\s*,?\s*/i';
      if(preg_match($regex,$data['Address Town'],$match)){
	$data['Address Town']='Odense';
      }
      $regex='/\s*Odense\s*/i';
      if(preg_match($regex,$raw_data['Address Line 3'],$match)){
	$raw_data['Address Line 3']='';
	$data['Address Town']='Odense';
      }

      $data['Address Postal Code']=_trim($data['Address Postal Code']);
      if(preg_match('/^\d{4}$/',$data['Address Postal Code'])){
	$data['Address Postal Code']='DK-'.$data['Address Postal Code'];
      }
      if(preg_match('/^KLD$/i',$raw_data['Address Line 3']))
	$raw_data['Address Line 3']='';
  
      if(preg_match('/^DK\- 7470 Karup J$/i',$raw_data['Address Line 3'])){
	$raw_data['Address Line 3']='';
	$data['Address Postal Code']='DK-7470';
	$data['Address Town']='Karup J';
      }
      
          
      if(preg_match('/Sjalland|Zealand|Sjælland|Sealand/i',$data['Address Country Secondary Division']))
	$data['Address Country Secondary Division']='';
    

       
      if(preg_match('/Sjalland|Zealand/i',$data['Address Town']))
	$data['Address Town']='';

       
      if($raw_data['Address Line 3']=='' and $raw_data['Address Line 2']!='' and  $raw_data['Address Line 1']=='' ){
	$raw_data['Address Line 3']=$raw_data['Address Line 2'];
	$raw_data['Address Line 2']=$raw_data['Address Line 1'];

      }


      if($raw_data['Address Line 3']=='' and $raw_data['Address Line 2']!='' and  $raw_data['Address Line 1']!='' ){
	$raw_data['Address Line 3']=$raw_data['Address Line 2'];
	$raw_data['Address Line 2']=$raw_data['Address Line 1'];
	$raw_data['Address Line 1']='';
      }

      if($data['Address Town']==''){
	if($data['Address Town Primary Division']!='' ){
	  $data['Address Town']=$data['Address Town Primary Division'];
	  $data['Address Town Primary Division']='';
	}
	elseif($data['Address Town Secondary Division']!=''){
	  $data['Address Town']=$data['Address Town Secondary Division'];
	  $data['Address Town Secondary Division']='';
	}
	elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	  $data['Address Town']=$raw_data['Address Line 3'];
	  $raw_data['Address Line 3']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']=$raw_data['Address Line 1'];
	  $raw_data['Address Line 1']='';
	}else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	  $data['Address Town']=$raw_data['Address Line 2'];
	  $raw_data['Address Line 2']='';
	  $raw_data['Address Line 3']=$raw_data['Address Line 1'];
	  $raw_data['Address Line 1']='';
	}
      }
    


    


      break; 
    default:
      $data['Address Postal Code']=$data['Address Postal Code'];
      $regex='/\s*'.$data['Address Country Name'].'\s*/i';
      $data['Address Postal Code']=preg_replace($regex,'',$data['Address Postal Code']);
    
    }


    if($raw_data['Address Line 3']=='' and $raw_data['Address Line 2']!='' and  $raw_data['Address Line 1']=='' ){
      $raw_data['Address Line 3']=$raw_data['Address Line 2'];
      $raw_data['Address Line 2']=$raw_data['Address Line 1'];
      
    }


    if($raw_data['Address Line 3']=='' and $raw_data['Address Line 2']!='' and  $raw_data['Address Line 1']!='' ){
      $raw_data['Address Line 3']=$raw_data['Address Line 2'];
      $raw_data['Address Line 2']=$raw_data['Address Line 1'];
      $raw_data['Address Line 1']='';
    }

    if($data['Address Town']==''){
      if($data['Address Town Primary Division']!='' ){
	$data['Address Town']=$data['Address Town Primary Division'];
	$data['Address Town Primary Division']='';
      }
      elseif($data['Address Town Secondary Division']!=''){
	$data['Address Town']=$data['Address Town Secondary Division'];
	$data['Address Town Secondary Division']='';
      }
      elseif($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ){
	$data['Address Town']=$raw_data['Address Line 3'];
	$raw_data['Address Line 3']=$raw_data['Address Line 2'];
	$raw_data['Address Line 2']=$raw_data['Address Line 1'];
	$raw_data['Address Line 1']='';
      }else if($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!=''){
	$data['Address Town']=$raw_data['Address Line 2'];
	$raw_data['Address Line 2']='';
	$raw_data['Address Line 3']=$raw_data['Address Line 1'];
	$raw_data['Address Line 1']='';
      }
    }
    
  


    // Country ids
    if($data['Address Country Primary Division']!=''){
      $sql=sprintf("select `Country Primary Division Key` as id  from  `Country Primary Division Dimension` where (`Country Primary Division Name`='%s' or `Country Primary Division Native Name`='%s' or `Country Primary Division Local Native Name`='%s' ) and `Country Key`=%d",addslashes($data['Address Country Primary Division']),addslashes($data['Address Country Primary Division']),addslashes($data['Address Country Primary Division']),$data['Address Country Key']);
      //  print "$sql\n";
 
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC))
	$data['Address Country Primary Division Key']=$row['id'];
    }


    if($data['Address Country Secondary Division']!=''){
      $sql=sprintf("select `Country Secondary Division Key`  as id, `Country Primary Division Key`   from `Country Secondary Division Dimension`   where (`Country Secondary Division Name`='%s' or `Country Secondary Division Native Name`='%s' or `Country Secondary Division Local Native Name`='%s' ) and `Country Key`=%d",addslashes($data['Address Country Secondary Division']),addslashes($data['Address Country Secondary Division']),addslashes($data['Address Country Secondary Division']),$data['Address Country Key']);
   

      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      
      
	$data['Address Country Secondary Division Key']=$row['id'];
	if(mysql_num_rows($result)==1){
	  $data['Address Country Primary Division Key']=$row['Country Primary Division Key'];
	}
      
      }
      else
	$data['Address Country Secondary Division Key']=0;
    }



    $sql=sprintf("select `Town Key` as id,`Country Secondary Division Key` , `Country Primary Division Key` from `Town Dimension` where (`Town Name`='%s' or `Town Native Name`='%s' or `Town Local Native Name`='%s' ) and `Country Key`=%d",addslashes($data['Address Town']),addslashes($data['Address Town']),addslashes($data['Address Town']),$data['Address Country Key']);
    //print $sql;
    $res = mysql_query($sql);  
 
    if(mysql_num_rows($res)==1){
   
      $row=mysql_fetch_array($res, MYSQL_ASSOC);
      $data['Address Town Key']=$row['id'];
      if($data['Address Country Secondary Division Key']==0)
	$data['Address Country Secondary Division Key']=$row['Country Secondary Division Key'];
      if($data['Address Country Primary Division Key']==0)
	$data['Address Country Primary Division Key']=$row['Country Primary Division Key'];
   
     
   
    }
    else
      $data['Address Town Key']=0;
 
   


    if(preg_match('/\d+\s*\-\s*\d+/',$raw_data['Address Line 3'])){
      $raw_data['Address Line 3']=preg_replace('/\s*\-\s*/','-',$raw_data['Address Line 3']);
    }
    if(preg_match('/\d+\s*\-\s*\d+/',$raw_data['Address Line 2'])){
      $raw_data['Address Line 2']=preg_replace('/\s*\-\s*/','-',$raw_data['Address Line 2']);
    }
    $raw_data['Address Line 1']=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$raw_data['Address Line 1']);
    $raw_data['Address Line 2']=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$raw_data['Address Line 2']);
    $raw_data['Address Line 3']=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$raw_data['Address Line 3']);
    $raw_data['Address Line 3']=  preg_replace('/^p o box\s+/i','PO BOX ',$raw_data['Address Line 3']);
    $raw_data['Address Line 3']=  preg_replace('/^NULL$/i','',$raw_data['Address Line 3']);

    $raw_data['Address Line 1']=preg_replace('/\s{2,}/',' ',$raw_data['Address Line 1']);
    $raw_data['Address Line 2']=preg_replace('/\s{2,}/',' ',$raw_data['Address Line 2']);
    $raw_data['Address Line 3']=preg_replace('/\s{2,}/',' ',$raw_data['Address Line 3']);
    $data['Address Town']=preg_replace('/\s{2,}/',' ',$data['Address Town']);
    $data['Address Town Primary Division']=preg_replace('/\s{2,}/',' ',$data['Address Town Primary Division']);
    $data['Address Town Secondary Division']=preg_replace('/\s{2,}/',' ',$data['Address Town Secondary Division']);
    $data['Address Town']=preg_replace('/(\,|\-)$\s*/','',$data['Address Town']);
  

    foreach($data as $key=>$val){
      if($key=='Address Postal Code' or $key=='Address Country Code' or $key=='Address Country 2 Alpha Code')
	$data[$key]=_trim($val);
      else
	$data[$key]=mb_ucwords(_trim($val));
    }


    $street_data=Address::parse_street(mb_ucwords(_trim($raw_data['Address Line 3'])));

    foreach($street_data as $key=>$value){
      if(array_key_exists($key,$data)){
	$data[$key]=_trim($value);
      }
    }
    $data['Address Building']=$raw_data['Address Line 2'];
    $data['Address Internal']=$raw_data['Address Line 1'];

    $postcode_data=Address::parse_postcode(
					   $data['Address Postal Code']
					   ,$data['Address Country Code']
					   );

    foreach($postcode_data as $key=>$value){
      if(array_key_exists($key,$data)){
	$data[$key]=_trim($value);
      }
    }


   

    $data['Address Fuzzy']='No';
    $data['Address Fuzzy Type']='';

    
    if($raw_data['Address Line 1']=='' 
       and $raw_data['Address Line 2']=='' 
       and $raw_data['Address Line 3']=='' ){
      $data['Address Fuzzy Type']='Street';
      $data['Address Fuzzy']='Yes';
    }




    if($data['Address Town']==''){
      $data['Address Fuzzy Type']='Country';
      $data['Address Fuzzy']='Yes';
    }
    if($data['Address Country Code']=='UNK'){
      if(!$empty){
	print "UNKNOWN COUNTRY\n";
	print_r($raw_data);
	//	exit;
      }
      $data['Address Fuzzy Type']='All';
      $data['Address Fuzzy']='Yes';
    }

     
    $data['Address Fuzzy Type']=preg_replace('/^,\s*/','',$data['Address Fuzzy Type']);

    $data['Address Location']=Address::location($data);
    //  print_r($data['Address Country 2 Alpha Code']);





    return $data;
  }

  /*
    Function:  plain
    OPlain addres ised as finger print and serach purpose 
  */

  function plain($data){


     


    $separator=' ';
    if($data['Military Address']=='Yes'){
      $address=$data['Military Installation Address'];
      $address_type=_trim($data['Military Installation Type']);
      if($address_type!='')
	$address.=$separator.$address_type;
      $address_type=_trim($data['Address Postal Code']);
      if($address_type!='')
	$address.=$separator.$address_type;
      $address.=$separator.$data['Address Country Code'];

    }else{
      //print_r($data);
      $address='';
      $header_address=_trim($data['Address Internal'].' '.$data['Address Building']);
      if($header_address!='')
	$address.=$header_address.$separator;
	
      $street_address=_trim($data['Address Street Number'].' '.$data['Address Street Name'].' '.$data['Address Street Type']);
      if($street_address!='')
	$address.=$street_address.$separator;

     
      $subtown_address=$data['Address Town Secondary Division'];
      if($data['Address Town Primary Division'])
	$subtown_address.=' ,'.$data['Address Town Primary Division'];
      $subtown_address=_trim($subtown_address);
      if($subtown_address!='')
	$address.=$subtown_address.$separator;


     
      $town_address=_trim($data['Address Town']);
      if($town_address!='')
	$address.=$town_address.$separator;

      $ps_address=_trim($data['Address Postal Code']);
      if($ps_address!='')
	$address.=$ps_address.$separator;
     
      $subcountry_address=$data['Address Country Secondary Division'];
      if($data['Address Country Primary Division'])
	$subcountry_address.=' '.$data['Address Country Primary Division'];
      $subcountry_address=_trim($subcountry_address);
      if($subcountry_address!='')
	$address.=$subcountry_address.$separator;
	
	
      $address.=$data['Address Country Code'];
    }

    if($data['Address Fuzzy']=='Yes'){
      $address='[FZ] '.$address;
	
    }
	

    return _trim($address);
      
      
  }
  

  /*
    Function: similarity
    Calculate the probability of been the same address 
    Returns:
    Probability of been the same address _float_ (0-1) 
  */

  function similarity($data,$address_key){

  
  }



 
   function set_scope($raw_scope='',$scope_key=0){
    $scope='Unknown';
    $raw_scope=_trim($raw_scope);
    if(preg_match('/^customers?$/i',$raw_scope)){
      $scope='Customer';
    }else if(preg_match('/^(contacts?|person)$/i',$raw_scope)){
      $scope='Contact';
    }else if(preg_match('/^(company?|bussiness)$/i',$raw_scope)){
      $scope='Company';
    }else if(preg_match('/^(supplier)$/i',$raw_scope)){
      $scope='Supplier';
    }else if(preg_match('/^(staff)$/i',$raw_scope)){
      $scope='Staff';
    }
    
    $this->scope=$scope;
    $this->scope_key=$scope_key;
    $this->load_metadata();
    
  }
  
  function load_metadata(){
    

    $this->data['Type']=array();
    $this->data['Function']=array();


    $where_scope=sprintf(' and `Subject Type`=%s',prepare_mysql($this->scope));
    
    $where_scope_key='';
    if($this->scope_key)
      $where_scope_key=sprintf(' and `Subject Key`=%d',$this->scope_key);

    


    $sql=sprintf("select * from `Address Bridge` where `Address Key`=%d %s  %s  order by `Is Main`"
		 ,$this->id
		 ,$where_scope
		 ,$where_scope_key
		 );
    $res=mysql_query($sql);

  
    $this->data['Address Type']=array();
    $this->data['Addresss Function']=array();
    $this->data['Address Is Main']=array();
    $this->associated_with_scope=false;
    while($row=mysql_fetch_array($res)){
      $this->associated_with_scope=true;
      $this->data['Addresss Type'][$row['Address Type']]=$row['Address Type'];
      $this->data['Addresss Function'][$row['Address Function']]=$row['Address Function'];
      $this->data['Address Is Main'][$row['Address Type']]=$row['Is Main'];
      $this->data['Address Is Active'][$row['Address Type']]=$row['Is Active'];

    }
    
    
  }

}    




?>