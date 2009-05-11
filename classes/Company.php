<?
/*
 File: Company.php 

 This file contains the Company Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('Contact.php');
include_once('Telecom.php');
include_once('Email.php');
include_once('Address.php');
include_once('Name.php');
/* class: Company
 Class to manage the *Company Dimension* table
*/
class Company{
  // Array: data
  // Class data
  var $data=array();
  var $items=array();
  // Integer: id
  // Database Primary Key
  var $id=false;
  // Boolean: warning
  // True if a warning
  var $warning=false;
  // Boolean: error
  // True if error occuers
  var $error=false;
  // String: msg
  // Messages
  var $msg='';
  // Boolean: new
  // True if company has been created
  var $new=false;
 // Boolean: updated
  // True if company has been updated
  var $updated=false;


     /*
       Constructor: Company
     
       Initializes the class, Search/Load or Create for the data set 
     
      Parameters:
       arg1 -    (optional) Could be the tag for the Search Options or the Company Key for a simple object key search
       arg2 -    (optional) Data used to search or create the object

       Returns:
       void
       
       Example:
       (start example)
       // Load data from `Company Dimension` table where  `Company Key`=3
       $key=3;
       $company = New Company($key); 

        // Insert row to `Company Dimension` table
       $data=array();
       $company = New Company('new',$data); 
       

       (end example)

     */
  function Company($arg1=false,$arg2=false) {

     

     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return ;
     }
     if(preg_match('/create|new/i',$arg1)){
       $this->create($arg2);
       return;
     } if(preg_match('/find/i',$arg1)){
       $this->find($arg2,$arg1);
       return;
     }       
      $this->get_data($arg1,$arg2);
       return ;

 }


  /*
    Method: find
    Find Company with similar data
   
    Returns:
    Key of the Compnay found, if create is found in the options string  returns the new key
   */  
  function find($raw_data,$options){
    $create=false;

    if(preg_match('/create/i',$options)){
      $create=true;
    }
    if(preg_match('/update/i',$options)){
      $update=true;
    }


    if(preg_match('/from supplier/',$options)){
      foreach($raw_data as $key=>$val){
	$_key=preg_replace('/Supplier /','Company ',$key);
	$raw_data[$_key]=$val;
      }
      $mode='supplier';
    }elseif(preg_match('/from customer/',$options)){
      foreach($raw_data as $key=>$val){
	$_key=preg_replace('/Customer /','Company ',$key);
	$raw_data[$_key]=$val;
      }
      $mode='customer';
    }else{

      $mode='all';
    }

    $data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$data)){
	$data[$key]=_trim($value);
      }
    }

    //print_r($raw_data);
    //print_r($data);
    // Search for companies with the same email
    if($data['Company Main Plain Email']!=''){
      $sql=sprintf("select E.`Email Key` from `Email Telecom` E left join `Email Bridge` EB on (E.`Email Key`=EB.`Email Key`) where `Email`=%s and `Subject Type`='Company'",prepare_mysql($data['Company Main Plain Email']));
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->found=true;
	if($create){
	  if($update){
	    $this->update('all',$data);
	  }else{
	    $this->error=true;
	    $this->msg=_('Email found in other company');
	  }
	}
	return;
      }
    }

  if($data['Company Main Telephone']!=''){
    $telephone_data=Telecom::parse_number($data['Company Main Telephone']);
    print_r($telephone_data);
    exit;
    $plain_telephone=$telephone_data['Telephone Plain Number'];
      $sql=sprintf("select T.`Telecom Key` from `Telecom Dimension` T left join `Telecom Bridge` TB on (T.`Telecom Key`=TB.`Telecom Key`) where `Telecom Plain Number`=%s and `Subject Type`='Company'",prepare_mysql($plain_telephone));
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->found=true;
	if($create){
	  if($update){
	    $this->update('all',$data);
	  }else{
	    $this->error=true;
	    $this->msg=_('Telephone found in other company');
	  }
	}
	return;
      }
    }
    

    exit;


  }

  function get($key,$arg1=false){
    //  print $key."xxxxxxxx";
    
    if(array_key_exists($key,$this->data))
      return $this->data[$key];

    switch($key){
    case('departments'):
      if(!isset($this->departments))
	$this->load('departments');
      return $this->departments;
      break;
    case('department'):
      if(!isset($this->departments))
	$this->load('departments');
      if(is_numeric($arg1)){
	if(isset($this->departments[$arg1]))
	  return $this->departments[$arg1];
	else
	  return false;
      }
      if(is_string($arg1)){
	foreach($this->departments as $department){
	  if($department['company department code']==$arg1)
	    return $department;
	}
	return false;
      }
      
      
    }
   
     $_key=ucfirst($key);
    if(isset($this->data[$_key]))
      return $this->data[$_key];
    print "Error $key not found in get from address\n";

    return false;

  }


  function get_data($tipo,$id){
    $sql=sprintf("select * from `Company Dimension` where `Company Key`=%d",$id);
    // print $sql;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Company Key'];
    }
  }
/*
   Function: base_data
   Initialize data  array with the default field values
   */
private function base_data(){
   $data=array();

   $ignore_fields=array('Company Key');

   $result = mysql_query("SHOW COLUMNS FROM `Company Dimension`");
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
   return $data;
 }



  
  function create($raw_data){


    $this->data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$this->data)){
	$this->data[$key]=_trim($value);
      }
    }

    
    if($this->data['Company Name']==''){
      $this->data['Company Name']=_('Unknown Name');
    }

    $file_as=$this->file_as($this->data['Company Name']);
    $this->data['Company ID']=$this->get_id();
  
  
  //Create contact
  $known_contact=true;
  $main_contact=new Contact('Find in Company',$this->data);
  if(!$main_contact->id){
    //Create contact
    $contact_data['Contact Name']=$this->data['Company Main Contact'];
    $main_contact=new Contact('new',$contact_data);
    $this->data['Company Main Contact Name']=$main_contact->display('name');
    $this->data['Company Main Contact Key']=$main_contact->id;
    if($main_contact->data['Contact Fuzzy']=='Yes')
      $known_contact=false;
  }else{
    exit("contact already in database");
  }
  
  //Create email
  if($this->data['Company Main Plain Email']!=''){
    $mail_associted_with_contact=false;
    $main_email=new Email('Find in Company',$this->data['Company Main Plain Email']);
    if(!$main_email->$id){
    //Create contact
      $email_data['Email']=$this->data['Company Main Plain Email'];
      if(isset($raw_data['Email Contact Name']))
	$email_data['Email Contact Name']=$raw_data['Main Contact Name'];
    elseif($known_contact){
      $email_data['Email Contact Name']=$this->data['Main Contact Name'];
      $mail_associted_with_contact=true;
    }
    $main_email=new Email('new',$email_data);
    $this->data['Company Main XHTML Email']=$main_contact->display('xhtml');
    $this->data['Company Main Plain Email']=$main_contact->data['Email'];
    $this->data['Company Main Email Key']=$main_contact->id;
    }else{
      exit("email already in database");
	}
  }

  //Create Address
  $known_address=true;
  $main_address=new Address('Find in company',$this->data);
  if(!$main_address->id){
    //Create address
    foreach($raw_data as $key=>$value){
      if(preg_match('/address/i',$key)){
	$key=preg_replace('/^company\s*/i','',$key);
	$address_data[$key]=_trim($value);
      }
    }
    $main_address=new Address('new',$address_data);
    if(!$main_address->new){
      exit('Can not add addres in company '.$main_address->msg);
    }
    $this->data['Company Main Address Key']=$main_address->id;
    $this->data['Company Main Plain Address']=$main_address->display('plain');
    $this->data['Company Main XHTML Address']=$main_address->display('xhtml');
    $this->data['Company Main XHTML Address']=$main_address->display('location');


    if($main_address->data['Contact Fuzzy']=='Yes')
      $known_contact=false;
  }else{
    exit("contact already in database");
  }
  
  
   //Create telephone
  if($this->data['Company Main Telephone']!='' and Telecom::is_valid($this->data['Company Main Telephone'])){
    $telephone_associted_with_contact=false;
    $main_telephone=new Telecom('Find in Company',$this->data['Company Main Telecom']);
    if(!$main_telephone->$id){
    //Create contact
      $telephone_data['Telephone']=$this->data['Company Main Telephone'];
      if(isset($raw_data['Telephone Contact Name']))
	$telephone_data['Telephone Contact Name']=$raw_data['Main Contact Name'];
    elseif($known_contact){
      $telephone_data['Telephone Contact Name']=$this->data['Main Contact Name'];
      $telephone_associted_with_contact=true;
    }
    $main_telephone=new Telephone('new',$telephone_data);
    $this->data['Company Main XHTML Telephone']=$main_contact->display('xhtml');
    $this->data['Company Main Plain Telephone']=$main_contact->data['Telephone'];
    $this->data['Company Main Telephone Key']=$main_contact->id;
    }else{
      exit("telephone already in database");
	}
  }





     if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->get_data('id',$this->id);
     }else{
       print "Error, company can not be created";exit;
     }

  }

  function get_id(){
    
    $sql="select max(`Company ID`)  as company_id from `Company Dimension`";
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      if(!preg_match('/\d*/',_trim($row['company_id']),$match))
	$match[0]=1;
      $right_side=$match[0];
      $number=(double) $right_side;
      $number++;
      $id=$number;
    }else{
      $id=1;
    }  
    return $id;
  }

  function load($key=''){
    switch($key){
   
    case('contacts'):
    case('contact'):
      $this->contact=new Contact($this->data['contact_id']);
      if($this->contact->id){
	$this->contact->load('telecoms');
	$this->contact->load('contacts');
      }

    }
    
  }
  function add_page($page_data,$args='principal'){
    $url=$data['page url'];
    if(isset($data['page_type']) and preg_match('/internal/i',$data['page_type']))
      $email_type='Internal';
    else
      $email_type='External';
    $url_data=array(
		     'page description'=>'',
		     'page url'=>$url,
		     'page type'=>$email_type,
		     'page validated'=>0,
		     'page verified'=>0,
		     );
    
    if(isset($data['page description']) and $data['page description']!='')
      $url_data['page description']=$data['page description'];
    $page=new page('new',$url_data);
   if($email->new){
     
     $sql=sprintf("insert into  `Company Web Site Bridge` (`Page Key`, `Company Key`) values (%d,%d)  ",$page->id,$this->id);
     mysql_query($sql);
     if(preg_match('/principal/i',$args)){
     $sql=sprintf("update `Company Dimension` set `Company Main XHTML Page`=%s where `Contact Key`=%d",prepare_mysql($page->display('html')),$this->id);
     // print "$sql\n";
     mysql_query($sql);
     }

     $this->add_page=true;
   }else{
     $this->add_page=false;
     
   }

  }

  function add_email($email_data,$args='principal'){
    //  $emails=$this->get('emails');
    //  print_r($this->data);

    $contact=new contact($this->get('company main contact key'));
    if($contact->id){
    
      $contact->add_email($email_data,$args);
      
      if($contact->add_email){
	$this->msg['email added'];
	if(preg_match('/principal/i',$args)){
	  $sql=sprintf("update `Company Dimension` set `Company Main XHTML Email`=%s where `Company Key`=%d",prepare_mysql($contact->get('Contact Main XHTML Email')),$this->id);
	  mysql_query($sql);
	}
	
      }
    }
  }

 function add_tel($tel_data,$args='principal'){

   $tel_data['country key']=$this->get('Company main Country Key');
   $contact=new contact($this->get('company main contact key'));
   //print_r($this->data);
   if($contact->id){
   $contact->add_tel($tel_data,$args);
   
   if($contact->add_tel){
      $this->msg['telecom added'];
        if(preg_match('/principal/i',$args)){
	  $sql=sprintf("update `Company Dimension` set `Company Main Telephone`=%s where `Company Key`=%d",prepare_mysql($contact->get('Contact Main Telephone')),$this->id);
	  $this->db->exec($sql);
	  $sql=sprintf("update `Company Dimension` set `Company Main FAX`=%s where `Company Key`=%d",prepare_mysql($contact->get('Contact Main Fax')),$this->id);
	  mysql_query($sql);
	}

    }
 }else
   print "Error\n";
  }


function add_contact($data,$args='principal'){
  
  if(is_numeric($data))
    $contact=new Contact('id',$data);
  else
    $contact=new Contact('new',$data);
  
  if(!$contact->id)
    exit("can not find contact");

    $sql=sprintf("insert into  `Contact Bridge` (`Contact Key`, `Subject Key`,`Subject Type`,`Is Main`) values (%d,%d,'Company',%s,%s)  "
		   ,$contact->id
		   ,$this->id
		   ,prepare_mysql($telecom_tipo)
		   ,prepare_mysql(preg_match('/principal/i',$args)?'Yes':'No')
		   );
      mysql_query($sql);
      
      if(preg_match('/principal/i',$args)){
	$sql=sprintf("update `Contact Dimension` set `Company Main Contact`=%s and  `Company Main Contact Key`=%s,`Company Main Address Key`=%d,`Company Main XHTML Address`=%s,`Company Main Plain Address`=%s,`Company Main Country Key`=%d,`Company Main Country`=%s ,`Company Main Location`=%s,`Company Main Telephone`=%s,`Company Main Plain Telephone`=%,`Company Main Telephone Key`=%d,`Company Main FAX`=%s , `Company Main Plain FAX`=%s,`Company Main FAX Key`=%s ,`Company Main XHTML Email`=%s,`Company Main Plain Email`=%s, `Company Main Email Key`=%d  where `Company Key`=%d"
		     ,$contact->display('name')
		     ,$contact->id
		     ,$contact->data['Contact Main Address Key']
		     ,prepare_mysql($contact->data['Contact Main XHTML Address'])
		     ,prepare_mysql($contact->data['Contact Main Plain Address'])
		     ,$contact->data['Contact Main Country Key']
		     ,prepare_mysql($contact->data['Contact Main Country'])
		     ,prepare_mysql($contact->data['Contact Main Location'])
		     ,prepare_mysql($contact->data['Contact Main Telephone'])
		     ,prepare_mysql($contact->data['Contact Main Plain Telephone'])
		     ,$contact->data['Contact Main Telephone Key']
		     ,prepare_mysql($contact->data['Contact Main FAX'])
		     ,prepare_mysql($contact->data['Contact Main Plain FAX'])
		     ,$contact->data['Contact Main FAX Key']
		     ,prepare_mysql($contact->data['Contact Main XHTML Email'])
		     ,prepare_mysql($contact->data['Contact Main Plain Email'])
		     ,$contact->data['Contact Main Email Key']
		     ,$this->id
		     );
	mysql_query($sql);
	
	
      }
      


}

  function create_code($name){
    preg_replace('/[!a-z]/i','',$name);
    preg_replace('/^(the|el|la|les|los|a)\s+/i','',$name);
    preg_replace('/\s+(plc|inc|co|ltd)$/i','',$name);
    preg_split('/\s*/',$name);
    return $name;
  }

   function check_code($name){
    return $name;
  }
   function file_as($name){
    return $name;
  }

}

?>