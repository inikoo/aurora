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
     if(preg_match('/^(create|new)/i',$arg1)){
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
    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }

    $address_data=array('Company Address Line 1'=>'','Company Address Town'=>'','Company Address Line 2'=>'','Company Address Line 3'=>'','Company Address Postal Code'=>'','Company Address Country Name'=>'','Company Address Country Primary Division'=>'','Company Address Country Secondary Division'=>'');
    


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
      //   print "$key\n";
      if(array_key_exists($key,$data)){
	$data[$key]=_trim($value);
      }

      if(array_key_exists($key,$address_data))
	$address_data[$key]=$value; 

    }

    if($data['Company Name']==''){
      $data['Company Name']=_('Unknown Name');
    }


    $contact=new Contact("find in company",$raw_data);

    $email=new Email("find in company",$data['Company Main Plain Email']);
    $address=new Address("find in company ",$address_data);
    $telephone=new Telecom("find in company",$data['Company Main Telephone']);
    

    if($contact->found or $email->found or $address->found   or $telephone->found){
      //ups found in another
      exit("found company data in another company\n");
    }
    
   

    $this->create($data,$address_data);

    

    

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



  
function create($raw_data,$raw_address_data=array()){
    
  
  
  $this->data=$this->base_data();
  foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$this->data)){
	$this->data[$key]=_trim($value);
      }
  }
  
  
    $file_as=$this->file_as($this->data['Company Name']);
    $this->data['Company ID']=$this->get_id();
  
    
    $contact=new Contact("find in company create",$raw_data);
    if($contact->error){
      exit("find_company: contact error\n");
    }
    
    $this->data['Company Main Contact Name']=$contact->display('name');
    $this->data['Company Main Contact Key']=$contact->id;
    
    
    if($this->data['Company Main Plain Email']!=''){
       
       $email_data['Email']=$this->data['Company Main Plain Email'];
       $email_data['Email Contact Name']=$this->data['Company Main Contact Name'];
       $email=new Email("find in company create",$email_data);
       if($email->error){
	 //Collect data about email found
	 print $email->msg."\n";
	 exit("find_company: email found\n");
       }
       
       $this->data['Company Main Plain Email']=$email->display('plain');
       $this->data['Company Main XHTML Email']=$email->display('xhtml');
       $this->data['Company Main Email Key']=$email->id;
       

     }

    


    if($this->data['Company Main Telephone']!=''){
      $telephone=new Telecom("find in company create",$this->data['Company Main Telephone']);
      if($telephone->error){
	//Collect data about telecom found
	exit("find_company: telephone found");
      }

      $this->data['Company Main Plain Telephone']=$telephone->display('plain');
      $this->data['Company Main Telephone']=$telephone->display('number');
      $this->data['Company Main Telephone Key']=$telephone->id; 
       
    }

     $address_data=array('Company Address Line 1'=>'','Company Address Town'=>'','Company Address Line 2'=>'','Company Address Line 3'=>'','Company Address Postal Code'=>'','Company Address Country Name'=>'','Company Address Country Primary Division'=>'','Company Address Country Secondary Division'=>'');
     foreach($raw_address_data as $key=>$value){
       if(array_key_exists($key,$address_data))
	 $address_data[$key]=$value; 
     }
     
         



     $address=new Address("find in company create",$address_data);
     if($address->error){
       exit("find_company: address found");
     }

    $this->data['Company Main Address Key']=$address->id;
    $this->data['Company Main XHTML Address']=$address->display('xhtml');
    $this->data['Company Main Plain Address']=$address->display('plain');
    $this->data['Company Main Country Key']=$address->data['Address Country Key'];
    $this->data['Company Main Country']=$address->data['Address Country Name'];
    $this->data['Company Main Location']=$address->display('location');
    
  

    $keys='';
    $values='';
    foreach($this->data as $key=>$value){
      $keys.=",`".$key."`";
      $values.=','.prepare_mysql($value,false);
    }
    $values=preg_replace('/^,/','',$values);
    $keys=preg_replace('/^,/','',$keys);

    $sql="insert into `Company Dimension` ($keys) values ($values)";
    //  print "$sql\n";
    
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();

      
      $contact->add_company(array(
				  'Company Key'=>$this->id
				  ));
      
           
      $contact->add_email(array(
				'Email Key'=>$this->data['Company Main Email Key']
				,'Email Type'=>'Work'
				));


      //  print_r($this->data);

      $contact->add_tel(array(
			      'Telecom Key'=>$this->data['Company Main Telephone Key']
			      ,'Telecom Type'=>'Work Telephone'
			      ));
                 
      $contact->add_tel(array(
			      'Telecom Key'=>$this->data['Company Main FAX Key']
			      ,'Telecom Type'=>'Office Fax'
			      ));




      $contact->add_address(array(
				  'Address Key'=>$this->data['Company Main Address Key']
				  ,'Address Type'=>'Work'
				  ,'Address Function'=>'Contact'
				  ,'Address Description'=>'Work Contact Address'
				  ));

     

      //create the DB bridges
      $this->add_email($this->data['Company Main Email Key']);
      $this->add_tel(array(
			   'Telecom Key'=>$this->data['Company Main Telephone Key']
			   ,'Telecom Type'=>'Office Telephone'
			   ));
      
      $this->add_tel(array(
			   'Telecom Key'=>$this->data['Company Main FAX Key']
			   ,'Telecom Type'=>'Office Fax'
			   ));
      $this->add_address(array(
			       'Address Key'=>$this->data['Company Main Address Key']
			       ,'Address Type'=>'Office'
			       ,'Address Function'=>'Contact'
			       ,'Address Description'=>'Company Address'
			       ));

/*       if($this->data['Company Main Telephone Key']){ */
/* 	$sql=sprintf("insert into `Telecom Bridge` (`Telecom Key`,`Subject Type`,`Subject Key`,`Telecom Description`,`Is Main`,`Is Active`,`Telecom Type`) values (%d,'Company',%d,%s,'Yes','Yes')" */
/* 		     ,$this->data['Company Main Telephone Key'] */
/* 		     ,$this->id */
/* 		     ,prepare_mysql('Company Telephone') */
/* 		     ,prepare_mysql('Office') */
/* 		     ); */
/* 	mysql_query($sql); */
/*       } */
      

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
    
    if(is_numeric($email_data)){
      $tmp=$email_data;
      unset($email_data);
      $email_data['Email Key']=$tmp;
    }
      

    if(preg_match('/from main contact/',$args)){
       $contact=new contact($this->data['Company Main Contact Key']);
       $email=new Email($contact->data['Contact Main Email Key']);
    }elseif(isset($email_data['Email Key'])){
      $email=new Email($email_data['Email Key']);
    }elseif(is_array($email_data)){
      $email=new Email('find in company create',$email_data['Email Key']);
      
    }else
       return;
    
    if($email->id){
      
      	$sql=sprintf("insert into `Email Bridge` (`Email Key`,`Subject Type`,`Subject Key`,`Email Description`,`Is Main`,`Is Active`) values (%d,'Company',%d,%s,'Yes','Yes')"
		     ,$email->id
		     ,$this->id
		     ,prepare_mysql('Company Email')
		     );
	mysql_query($sql);


    }
    
    
  /*   $contact=new contact($this->get('company main contact key')); */
/*     if($contact->id){ */
      
/*       $contact->add_email($email_data,$args); */
      
/*       if($contact->add_email){ */
/* 	$this->msg['email added']; */
/* 	if(preg_match('/principal/i',$args)){ */
/* 	  $sql=sprintf("update `Company Dimension` set `Company Main XHTML Email`=%s where `Company Key`=%d",prepare_mysql($contact->get('Contact Main XHTML Email')),$this->id); */
/* 	  mysql_query($sql); */
/* 	} */
	
/*       } */
/*     } */
    
    
    
  }


/* Method: add_tel
  Add/Update an telecom to the Company
  
  Search for an telecom record maching the telecom data *$data* if not found create a ne telecom record then add this record to the Contact


  Parameter:
  $data  -    array   telecom data
  $args -     string  options
  Return: 
  integer telecom key of the added/updated telecom
 */


 function add_tel($data,$args='principal'){

   if(is_numeric($data)){
     $tmp=$data;
     unset($data);
     $data['Email Key']=$tmp;
   }
   
   if(isset($data['Telecom Key'])){
     $telecom=new Telecom('id',$data['Telecom Key']);
   }else{
     if(!isset($data['Telecom Original Country Key']) or !$data['Telecom Original Country Key'])
       $data['Telecom Original Country Key']=$this->data['Contact Main Country Key'];
     $telecom=new telecom('find in company create',$data);
   }
   if($telecom->id){
     if($telecom->data['Telecom Technology Type']=='Mobile'){
	 $telecom_tipo='Company Main Telephone';
	 $telecom_tipo_plain='Company Main Plain Telephone';
       }else{
	 if(preg_match('/fax/i',$data['Telecom Type'])){
	 $telecom_tipo='Company Main FAX';
	 $telecom_tipo_plain='Company Main Plain FAX';
	 
	 }else{
	   $telecom_tipo='Company Main Telephone';
	   $telecom_tipo_plain='Company Main Plain Telephone';
	 }
       }

 if(!isset($data['Telecom Description']))
       $data['Telecom Description']=$telecom_tipo;

     // add bridge
  
     
     $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Subject Key`,`Subject Type`,`Telecom Type`,`Is Main`,`Telecom Description`) values (%d,%d,'Company',%s,%s,%s)  "
		  ,$telecom->id
		  ,$this->id
		  ,prepare_mysql($data['Telecom Type'])
		  ,prepare_mysql(preg_match('/principal/i',$args)?'Yes':'No')
		  ,prepare_mysql($data['Telecom Description'],false)
		  );
     mysql_query($sql);
     print "$sql\n";
     if(preg_match('/principal/i',$args)){
     
       
       	 $sql=sprintf("update `Company Dimension` set `%s`=%s and `%s`=%s and   where `Company Key`=%d"
		      ,$telecom_tipo
		      ,prepare_mysql($telecom->display('html'))
		      ,$telecom_tipo_plain
		      ,$telecom->display('plain')
		      ,$this->id
		      );
	 mysql_query($sql);
       
     }
   }

 }
    
/* Method: add_address
  Add/Update an address to the Contact
  
  Search for an address record maching the address data *$data* if not found create a ne address record then add this record to the Contact


  Parameter:
  $data  -    array   address data
  $args -     string  options
  Return: 
  integer address key of the added/updated address
 */

function add_address($data,$args='principal'){

  if(!$data)
    $address=new address('fuzzy all');
  elseif(is_numeric($data) )
    $address=new address('fuzzy country',$data);
  elseif(is_array($data)){

    if(isset($data['Address Key'])){

      $address=new address('id',$data['Address Key']);
    }    else
      $address=new address('find in company create',$data);

  }else
    $address=new address('fuzzy all');

  if(!$address->id){
    
    return;
    
  }
  
  $address_id=$address->id;
  $sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Subject Key`,`Address Key`,`Address Type`,`Address Function`,`Address Description`) values ('Company',%d,%d,%s,%s,%s)",
	       $this->id,
	       $address_id
	       ,prepare_mysql($data['Address Type'])
	       ,prepare_mysql($data['Address Function'])
	       ,prepare_mysql($data['Address Description'])
	       );
 
  if(!mysql_query($sql)){
    exit("$sql\n error can no create company address bridge");
  }
 if(preg_match('/principal/i',$args)){
 
   //    $plain_address=_trim($address->data['Street Number'].' '.$address->data['Street Name'].' '.$address->data['Address Town'].' '.$address->data['Postal Code'].' '.$address->data['Address Country Code']);
     $sql=sprintf("update `Company Dimension`  set `Com[Any Main Plain Address`=%s,`Com[Any Main Address Key`=%s ,`Com[Any main Location`=%s ,`Com[Any Main XHTML Address`=%s , `Com[Any Main Country Key`=%d,`Com[Any Main Country`=%s,`Com[Any Main Country Code`=%s where `Com[Any Key`=%d ",
		  prepare_mysql($address->display('plain')),
		  prepare_mysql($address_id),
		  prepare_mysql($address->data['Address Location']),
		  prepare_mysql($address->display('html')),
		  $address->data['Address Country Key'],
		  prepare_mysql($address->data['Address Country Name']),
		  prepare_mysql($address->data['Address Country Code']),
		  $this->id
		  );
 }
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