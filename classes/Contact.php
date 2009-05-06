<?
/*
 File: Contact.php 

 This file contains the Contact Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/


include_once('Telecom.php');
include_once('Email.php');
include_once('Address.php');
include_once('Name.php');

/* class: Contact
 Class to manage the *Contact Dimension* table
*/

class Contact{
  
  // Array: data
  // Class data
  public $data=array();
  public  $emails=false;
  // Integer: id
  // Database Primary Key
  public  $id;

   /*
       Constructor: Contact
     
       Initializes the class, Search/Load or Create for the data set 
     
      Parameters:
       arg1 -    (optional) Could be the tag for the Search Options or the Contact Key for a simple object key search
       arg2 -    (optional) Data used to search or create the object

       Returns:
       void
       
       Example:
       (start example)
       // Load data from `Contact Dimension` table where  `Contact Key`=3
       $key=3;
       $contact = New Contact($key); 

        // Insert row to `Contact Dimension` table
       $data=array();
       $contact = New Contact('new',$data); 
       

       (end example)

     */
  function Contact($arg1=false,$arg2=false) {
    
    global $myconf;
    $this->unknown_name=$myconf['unknown_contact'];
    $this->unknown_informal_greeting=$myconf['unknown_informal_greting'];
    $this->unknown_formal_greeting=$myconf['unknown_formal_greting'];

    if(preg_match('/^new$/',$arg1)){

      $this->create($arg2);
      return;
    }

    if(is_numeric($arg1) and !$arg2){
      $this->get_data('id',$arg1);
      return;
    }
    $this->get_data($arg1,$arg2);


  }
  /* Function: get_data
       Load the data from de Database
     
     Parameters:
         $key  -  string Search Field
         $id  -  mixed Search Argument
      Return: void
     */

  Protected  function get_data($key,$id){

    
    if($key=='id')
      $sql=sprintf("SELECT * FROM `Contact Dimension` C where `Contact Key`=%d",$id); 
    else
      return;

    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Contact Key'];


  }

 /* Method: check_others
  Look for similar contacts in the Database
  
  Try to match a contact with the data provided if not found any candidate return 0

  Parameter:
  $data  -     array        Data to be compared with the contacts in the database
  
  Return: 
  integer  - $contact_key   integer     Contact Key of the most probable match or 0 if no match found
 */

  function check_others($data){
    
      $weight=array(
		   'Same Other ID'=>100
		   ,'Same Email'=>100
		   ,'Similar Email'=>20
		   );

      
      if($data['Contact Email']!=''){
	$has_email=true;
	$sql=sprintf("select `Email Key` from `Email Dimension` where `Email`=%s",prepare_mysql($data['Contact Email']));
	$result=mysql_query($sql);
	if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	  $email_key=$row['Email Key'];
	  $sql=sprintf("select `Subject Key` from `Email Bridge` where `Email Key`=%s and `Subject Type`='Contact'",prepare_mysql($email_key));
	  $result2=mysql_query($sql);
	  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	    // Email found assuming this is th contact
	    $contact_key=$row2['Subject Key'];
	    return $contact_key;
	  }
	}
      }else
	$has_email=false;

     $telephone=Telephone::display(Telephone::parse_telecom(array('Telecom Original Number'=>$data['Telephone']),$data['Country Key']));
     $contact_name= $this->parse_name($data['Name']);
    // Email not found check if we have a mantch in other id
     if($data['Customer Other ID']!=''){
       $no_other_id=false;
	$sql=sprintf("select `Contact Key`,`Contact Name`,`Contact Main Telephone` from `Customer Dimension` CD left join `Contact Bridge` CB on (CB.`Subject Key`=CD.`Customer Key`)  where `Subject Type`='Customer' and `Customer Other ID`=%s",prepare_mysql($data['Customer Other ID']));
	$result=mysql_query($sql);
	$num_rows = mysql_num_rows($result);
	if($num_rows==1){
	  $row=mysql_fetch_array($result, MYSQL_ASSOC);
	  $contact_key=$row2['Contact Key'];
	  return $contact_key;
	}elseif($num_rows>1){
	  // Get the candidates
	  
	  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	    $candidate[$row['Contact Key']]['field']=array('Contact Other ID');
	    $candidate[$row['Contact Key']]['points']=$weight['Same Other ID'];
	    // from this candoateed of one has the same name we wouls assume that this is the one
	    if($contact_name!='' and $contact_name==$row['Contact Name'])
	      return $row2['Contact Key'];
	    if($telephone!='' and $telephone==$row['Contact Main Telephone'])
	      return $row2['Contact Key'];

	    
	  }
	  



	}
     }else
       $no_other_id=true;
    



     //If contact has the same name ond same address
     //$addres_finger_print=preg_replace('/[^\d]/','',$data['Full Address']).$data['Address Town'].$data['Postal Code'];


     //if thas the same name,telephone and address get it
    




     if($has_email){
     //Get similar candidates from email
       
       $sql=sprintf("select levenshtein(UPPER(%s),UPPER(`Email`)) as dist1,levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Email`))) as dist2, `Subject Key`  from `Email Dimension` left join `Email Bridge` on (`Email Bridge`.`Email Key`=`Email Dimension`.`Email Key`)  where dist1<=2 and  `Subject Type`='Contact'   order by dist1,dist2 limit 20"
		    ,prepare_mysql($data['Contact Email'])
		    ,prepare_mysql($data['Contact Email'])
		    );
       $result=mysql_query($sql);
       while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	  $candidate[$row['Subject Key']]['field'][]='Contact Other ID';
	  $dist=0.5*$row['dist1']+$row['dist2'];
	  if($dist==0)
	    $candidate[$row['Subject Key']]['points']+=$weight['Same Other ID'];
	  else
	    $candidate[$row['Subject Key']]['points']=$weight['Similar Email']/$dist;
       
       }
     }
 

     //Get similar candidates from emailby name
     if($data['Contact Name']!=''){
     $sql=sprintf("select levenshtein(UPPER(%s),UPPER(`Contact Name`)) as dist1,levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Contact Name`))) as dist2, `Contact Key`  from `Contact Dimension`   where dist1<=3 and  `Subject Type`='Contact'   order by dist1,dist2 limit 20"
		  ,prepare_mysql($data['Contact Name'])
		  ,prepare_mysql($data['Contact Name'])
		  );
     $result=mysql_query($sql);
     while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $candidate[$row['Subject Key']]['field'][]='Contact Name';
       $dist=0.5*$row['dist1']+$row['dist2'];
       if($dist==0)
	 $candidate[$row['Subject Key']]['points']+=$weight['Same Contact Name'];
       else
	 $candidate[$row['Subject Key']]['points']=$weight['Similar Contact Name']/$dist;
       
     }
     }
     // Address finger print
     

  }

 /*
   Function: base_data
   Initialize data  array with the default field values
   */
private function base_data($args='replace'){

  $data=array();
   $ignore_fields=array('Contact Key');

   $result = mysql_query("SHOW COLUMNS FROM `Contact Dimension`");
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

   if(preg_match('/not? replace/',$args))
     return $data;
   if(preg_match('/replace/',$args))
     $this->data=$data;
  

   return $data;
   

 }

/* Method: create
 Create a new Contact record
 
 Parameter:
 $data -     array   Contact data 
 $optuioms - 
 Return: 
 mixed a object property
 
 Example:
 (example start)
 
 (example end)
 
*/
 function create ($data,$options=''){
   
   
   if(is_string($data))
     $data['Contact Name']=$data;
   
   $this->base_data();

   
   foreach($data as $key=>$value){
     if(isset($this->data[$key]))
       $this->data[$key]=_trim($value);
   }
      

   if(!preg_match('/components ok|components confirmed/i',$options))
     $this->parse_name($this->data['Contact Name']);
   
     $this->prepare_name_data($this->data);
    
     $this->data['Contact Name']=$this->display('name');


     if(!preg_match('/gender confirmed|gender ok/i',$options))
       
       $this->data['Contact Gender']=$this->gender($this->data);
     if(!preg_match('/grettings confirmed|grettings ok/i',$options)){

       $this->data['Contact Informal Greeting']=$this->display('informal gretting');
       $this->data['Contact Formal Greeting']=$this->display('formal gretting');
     }


    // print_r($this->data);
    
    if($this->data['Contact Name']==''){
      $this->data['Contact Name']=$this->unknown_name;
      $this->data['Contact File As']=$this->unknown_name;
      $this->data['Contact Informal Greeting']=$this->unknown_informal_greeting;
      $this->data['Contact Formal Greeting']=$this->unknown_formal_greeting;
    }
    


    $this->data['Contact File As']=$this->display('file_as');
  
    
    $this->data['Contact ID']=$this->get_id();
   
    $keys='(';$values='values(';
    foreach($this->data as $key=>$value){
      $keys.="`$key`,";
      if(preg_match('/plain /i',$key))
	$print_null=false;
      else
	$print_null=true;
      $values.=prepare_mysql($value,$print_null).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);

    $sql=sprintf("insert into `Contact Dimension` %s %s",$keys,$values);
   
    
    if(mysql_query($sql)){
      $this->id= mysql_insert_id();
      $this->new=true;
    }else{
      $this->msg=_("Error can not create contact");
      $this->new=false;
    }

  }
    
   /* Method: get
  Used to get properties of the class
  
  Try to match a contact with the data provided if not found any candidate return 0

  Parameter:
  $key  -     string        tag key of property to be returned 
  $data -     mixed  extra data or output custimize options
  Return: 
  mixed a object property
 */
  
  
  function get($key='',$data=false){
    

    if(array_key_exists($key,$this->data))
      return $this->data[$key];


    switch($key){
    case('has_email_id'):
      if(!$this->emails)
	$this->load('emails');
      return array_key_exists($data,$this->emails);
      break;
    case('main_email'):
   
      return $this->data['main']['email'];
      break;
    }
    
    exit("$key can not be found in contact class\n");

  }

  /* Method: add_email
  Add/Update an email to the Contact
  
  Search for an email record maching the email data *$data* if not found create a ne email record then add this record to the Contact


  Parameter:
  $data  -    array   email data
  $args -     string  options
  Return: 
  integer email key of the added/updated email
 */

  function add_email($data,$args='principal'){

    $email=$data['email'];
    if(isset($data['email_type']))
      $email_type=$data['email_type'];
    else
      $email_type='';
    $email_data=array(
		      'email description'=>'',
		      'email'=>$email,
		      'email type'=>'Unknown',
		      'email contact name'=>'',
		      'email validated'=>0,
		      'email verified'=>0,
		      );


    if($this->data['Contact Name']!=$this->unknown_name)
      $email_data['email Contact Name']=$this->data['contact name'];
    if(preg_match('/work/i',$email_type))
      $email_data['email type']='Work';
    if(preg_match('/personal/i',$email_type))
      $email_data['email type']='personal';
    if(preg_match('/other/i',$email_type))
      $email_data['email type']='other';

    $email=new email('new',$email_data);
    if($email->id){
     
      $sql=sprintf("insert into  `Email Bridge` (`Email Key`,`Subject Type`, `Subject Key`,`Is Main`,`Email Description`) values (%d,'Contact',%d,%s,%s)  "
		   ,$email->id
		   ,$this->id
		   ,prepare_mysql(preg_match('/principal/i',$args)?'Yes':'No')
		   ,prepare_mysql($email_data)
		   );
      mysql_query($sql);
      if(preg_match('/principal/i',$args)){
	$sql=sprintf("update `Contact Dimension` set `Contact Main XHTML Email`=%s ,`Contact Main Plain Email`=%s where `Contact Key`=%d"
		     ,prepare_mysql($email->display('html'))
		     ,prepare_mysql($email->data['Email'])
		     ,$this->id);
	$this->data['Contact Main XHTML Email']=$email->display('html');
	mysql_query($sql);
      }
     
      $this->add_email=$email->id;
    }else{
	$this->add_email=0;
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
  elseif(is_array($data))
    $address=new address('new',$data);
  
 
 if(!$address->id){
   print "Error can not create address";
   
 }
 
 $address_id=$address->id;
 
 $sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Subject Key`,`Address Key`,`Address Type`,`Address Function`,`Address Description`) values ('Contact',%d,%d,%s,%s,%s)",
	      $this->id,
	      $address_id
	      ,prepare_mysql($data['Address Type'])
	      ,prepare_mysql($data['Address Function'])
	      ,prepare_mysql($data['Address Description'])
	      );
 
 if(!mysql_query($sql))
   exit("$sql\n error can no create contact address bridge");

 if(preg_match('/principal/i',$args)){
 
     $plain_address=_trim($address->data['Street Number'].' '.$address->data['Street Name'].' '.$address->data['Address Town'].' '.$address->data['Postal Code'].' '.$address->data['Address Country Code']);
     $sql=sprintf("update `Contact Dimension`  set `Contact Main Plain Address`=%s,`Contact Main Address Key`=%s ,`Contact main Location`=%s ,`Contact Main XHTML Address`=%s , `Contact Main Country Key`=%d,`Contact Main Country`=%s,`Contact Main Country Code`=%s where `Contact Key`=%d ",
		  prepare_mysql($plain_address),
		  prepare_mysql($address_id),
		  prepare_mysql($address->get('Address Location')),
		  prepare_mysql($address->get('XHTML Address')),
		  $address->get('Address Country Key'),
		   prepare_mysql($address->get('Address Country Name')),
		  prepare_mysql($address->get('Address Country Code')),
		  $this->id
		  );
 }
}

/* Method: add_telecom
  Add/Update an telecom to the Contact
  
  Search for an telecom record maching the telecom data *$data* if not found create a ne telecom record then add this record to the Contact


  Parameter:
  $data  -    array   telecom data
  $args -     string  options
  Return: 
  integer telecom key of the added/updated telecom
 */
 function add_tel($data,$args='principal'){

   if(!isset($data['Telecom Original Country Key']) or !$data['Telecom Original Country Key'])
     $data['Telecom Original Country Key']=$this->data['Contact Main Country Key'];
   $telecom=new telecom('new',$data);
   if($telecom->id){
      
     if($telecom->get('Telecom Technology Type')=='Mobile')
       $telecom_tipo='Mobile';
     else
       $telecom_tipo=$data['Telecom Type'];
     
       $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Subject Key`,`Subject Type`,`Telecom Type`,`Is Main`) values (%d,%d,'Contact',%s,%s)  "
		    ,$telecom->id
		    ,$this->id
		    ,prepare_mysql($telecom_tipo)
		    ,prepare_mysql(preg_match('/principal/i',$args)?'Yes':'No')
		    );
       mysql_query($sql);
       //   print "$sql\n";

       if(preg_match('/principal/i',$args)){
	
	 if($telecom->get('Telecom Type')=='Mobile'){
	   $telecom_tipo='Contact Main Mobile';
	   $telecom_tipo_plain='Contact Main Plain Mobile';
	 }else{
	   if(preg_match('/fax/i',$data['Telecom Type'])){
	     $telecom_tipo='Contact Main FAX';
	     $telecom_tipo_plain='Contact Main Plain FAX';

	   }else{
	     $telecom_tipo='Contact Main Telephone';
	     $telecom_tipo_plain='Contact Main Plain Telephone';
	   }
	 }
	
	 $plain_number=preg_replace('/[^\d]/','',$telecom->display('html'));
	
	 $sql=sprintf("update `Contact Dimension` set `%s`=%s and `%s`=%s and   where `Contact Key`=%d"
		      ,$telecom_tipo
		      ,prepare_mysql($telecom->display('html'))
		      ,$telecom_tipo_plain
		      ,$plain_number
		      ,$this->id
		      );
	 //  print "$sql\n";
	 mysql_query($sql);
       }
       
       
       
       $this->add_telecom=$telecom->id;

       
     }else{
       $this->add_telecom=0;
	
     }

    

   }
 


  
  function update($values,$args='',$history_data=false){

    $key=key($data);
    $value=$data['value'];

    switch($key){
    case('main_email'):
      if($value==$this->get($key)){
	$this->msg=_('Same value');
	$this->updated=false;
	return;
      }
      $main_email=new email($value);
      if(!$main_email->id){
	$this->msg=_('Email not found');
	$this->updated=false;
	return;
      }
      $email_contact_id=$main_email->get('contact_id');
      if($email_contact_id==0 or $email_contact_id){
	$main_email->update(
			    array('contact_id'=>array('value'=>$this->id))
			    ,'save',$history_data
			    );
      }

      if($main_email->get('contact_id')){
	$this->msg=_('Email not found');
	$this->updated=false;
	return;
      }

      $this->old['main_email']=$this->data['main']['email'];
      $this->data['main_email']=$value;
      $this->data['main']['email']=$main_email->data['email'];
      $this->updated=true;
    case('gender'):
      if($value==$this->get($key)){
	$this->msg=_('Same value');
	$this->updated=false;
	return;
      }
      $valid_values=array('male','felame','neutro','unknown');
      if(!is_in_array($value,$valid_values)){
	$this->msg=_('Same value');
	$this->updated=false;
	return;
      }
      $this->old['gender']=$this->get($key);
      $this->data['gender']=$value;
       

    }

    
   
  }


  function save($key,$history_data=false){
    switch($key){
    case('main_email'):
      $sql=sprintf('update contact set %s=%s where id=%d',$key,prepare_mysql($this->data[$key]),$this->id);
      //	print "$sql\n";
      mysql_query($sql);

      if(is_array($history_data)){
	$this->save_history($key,$this->old[$key],$this->data['main']['email'],$history_data);
      }


      break;
    }

  }

  function save_history($key,$old,$new,$data){
   
  }

  function load($key=''){
    switch($key){
    case('telecoms'):
      $this->load('tels');
      $this->load('emails');
      break;
    case('tels'):
      $sql=sprintf("select telecom_id from telecom2contact left join telecom on (telecom.id=telecom_id) where contact_id=%d ",$this->id);
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	
	
	if($tel=new telecom($row['telecom_id']))
	  $this->tel[]=$tel;
      }
      break;
    case('emails'):
      //   $this->emails=array();
      //       $sql=sprintf("select id from email where contact_id=%d ",$this->id);
      //       $result =& $this->db->query($sql);

      //       while($row=$result->fetchRow()){
      // 	$email=new Email($row['id']);
      // 	$this->emails[$email->id]=array(
      // 					'email'=>$email->get('email'),
      // 				       'email_link'=>$email->get('link')
      // 				       );
      //       }
      break;
    case('contacts'):
      //    $sql=sprintf("select child_id from contact_relations where parent_id=%d ",$this->id);
      //       $result =& $this->db->query($sql);
      //       while($row=$result->fetchRow()){
      // 	if($child=new telecom($row['child_id']))
      // 	  $this->contacts[]=$child;
      //       }
      break;

    }
    
  }
  
 

  //  function get_date($key='',$tipo='dt'){
  //     if(isset($this->dates['ts_'.$key]) and is_numeric($this->dates['ts_'.$key]) ){

  //       switch($tipo){
  //       case('dt'):
  //       default:
  // 	return strftime("%e %B %Y %H:%M", $porder['date_expected']);
  //       }
  //     }else
  //       return false;
  //   }
  

  //   function file_as(){
    
  //     if($this->data['contact name']==$this->unknown_name)
  //       return $this->unknown_name;
  //     else
  //       return $this->data['contact name'];
  //   }

  function get_id(){
    
    $sql="select max(`Contact ID`)  as contact_id from `Contact Dimension`";
 $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      
      if(!preg_match('/\d*/',_trim($row['contact_id']),$match))
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



  function parse_address($data){
    $address_data=array(
			'Street Number'=>'',
			'Street Name'=>'',
			'Street Type'=>'',
			'Street Direction'=>'',
			'Post Box'=>'',
			'Suite'=>'',
			'City Subdivision'=>'',
			'City Division'=>'',
			'City'=>'',
			'District'=>'',
			'Second District'=>'',
			'State'=>'',
			'Region'=>'',
			'Address Country Key'=>'',
			'Address Country Name'=>'',
			'Address Country Code'=>'',
			'Address Country 2 Alpha Code'=>'',
			'Address World Region'=>'',
			'Address Continent'=>'',
			'Postal Code'=>'',
			'Primary Postal Code'=>'',
			'Secondary Postal Code'=>'',
			'Postal Code Separator'=>'',
			'Fuzzy Address'=>''
			);
    
    return $address_data;
  }
    /* Method: prepare_name_data
  Clean the Name data array
 */ 

  function prepare_name_data($data){

    if(isset($data['Contact Salutation']))
      $this->data['Contact Salutation']=mb_ucwords(_trim($data['Contact Salutation']));
    if(isset($data['Contact First Name']) or isset($data['Contact Middle Name']))
      $this->data['Contact First Name']=mb_ucwords(_trim($data['Contact First Name'].' '.$data['Contact Middle Name']));
    if(isset($data['Contact Surname']))
      $this->data['Contact Surname']=mb_ucwords(_trim($data['Contact Surname']));
    if(isset($data['Contact Suffix']))
      $this->data['Contact Suffix']=mb_ucwords(_trim($data['Contact Suffix']));
    if(isset($data['Contact Gender']) and ($data['Contact Gender']=='Male' or $data['Contact Gender']=='Female'))
      $this->data['Contact Gender']=_trim($data['Contact Gender']);
   
    if($this->data['Contact Gender']=='Unknown')
      $this->data['Contact Gender']=$this->gender($data);

   

 

 
  


  }

 /* Method: parse_name
  Parse a name detecting its components
  
Parameter:
string with the name to be parsed

 Returns:
 Array with the name componets: Contact Salutation, Contact First Name, Contact Surname, Contact Suffix

 */ 
  public static function parse_name($raw_name){



    $name=array(
		'prefix'=>'',
		'first'=>'',
		'middle'=>'',
		'last'=>'',
		'suffix'=>'',
		'alias'=>''
		);
     
    $raw_name=_trim($raw_name);
    $raw_name=preg_replace('/\./',' ',$raw_name);
    $names=preg_split('/\s+/',$raw_name);

    $parts=count($names);

    switch($parts){
    case(1):
      if(Contact::is_surname($names[0]))
	$name['last']=$names[0];
      else if(Contact::is_givenname($names[0]))
	$name['first']=$names[0];
      else if(Contact::is_prefix($names[0]))
	$name['prefix']=$names[0];
      else
	$name['first']=$names[0];
      break;
    case(2):
      // firt the most obious choise
    
      if(Contact::is_givenname($names[0])){
	$name['first']=$names[0];
	$name['last']=$names[1];
      

      }else if(Contact::is_givenname($names[0]) and   Contact::is_surname($names[1])){
	$name['first']=$names[0];
	$name['last']=$names[1];

      }else if( Contact::is_prefix($names[0]) and   Contact::is_surname($names[1])){
	$name['prefix']=$names[0];
	$name['last']=$names[1];
      }else if( Contact::is_prefix($names[0]) and   Contact::is_givenname($names[1])){
	$name['prefix']=$names[0];
	$name['first']=$names[1];
      }else if( Contact::is_surname($names[0]) and   Contact::is_surname($names[1])){
	$name['last']=$names[0].' '.$names[1];
      }else{
	$name['first']=$names[0];
	$name['last']=$names[1];

      }
      break;
    case(3):
      // firt the most obious choise
      
      
      if(!Contact::is_prefix($names[0]) and  strlen($names[1])==1   and   strlen($names[2])>1  ){
	$name['first']=$names[0];
	$name['middle']=$names[1];
	$name['last']=$names[2];
      }elseif( Contact::is_prefix($names[0])) {
	$name['prefix']=$names[0];
	$name['first']=$names[1];
	$name['last']=$names[2];

	
	// 	if(   Contact::is_givenname($names[1]) and   Contact::is_surname($names[2])){

	// 	  $name['first']=$names[1];
	// 	  $name['last']=$names[2];
	// 	}else if(    strlen($names[1])==1 and   Contact::is_surname($names[2])){
	  
	// 	  $name['first']=$names[1];
	// 	  $name['last']=$names[2];
	// 	}else if(   Contact::is_givenname($names[1])    and   Contact::is_givenname($names[2])){
	  
	// 	  $name['first']=$names[1].' '.$names[2];
	// 	}else if(  Contact::is_surname($names[1])    and   Contact::is_surname($names[2])){
	  
	// 	  $name['last']=$names[1].' '.$names[2];
	// 	}else{
	// 	  $name['first']=$names[1];
	// 	  $name['last']=$names[2];
	  
	// 	}
	

      }else if(  Contact::is_givenname($names[0])   and   Contact::is_givenname($names[1])  and   Contact::is_surname($names[2])){
	$name['first']=$names[0].' '.$names[1];
	$name['last']=$names[2];
      }else if(  Contact::is_givenname($names[0])   and   Contact::is_surname($names[1])  and   Contact::is_surname($names[2])){
	$name['first']=$names[0];
	$name['last']=$names[1].' '.$names[2];
      }else if( Contact::is_givenname($names[0]) and     strlen($names[1])==1 and   Contact::is_surname($names[2])){
	$name['first']=$names[0];
	$name['middle']=$names[1];
	$name['last']=$names[2];
      }else{
	$name['first']=$names[0];
	$name['last']=$names[1].' '.$names[2];
      }
      break;
    case(4):


      
      if( Contact::is_prefix($names[0])) {
	$name['prefix']=$names[0];
	
	if(  Contact::is_givenname($names[1]) and    strlen($names[2])==1 and  Contact::is_surname($names[3])){

	  $name['first']=$names[1];
	  $name['middle']=$names[2];
	  $name['last']=$names[3];
	}else if(  Contact::is_givenname($names[1]) and   Contact::is_givenname($names[2])  and  Contact::is_surname($names[3])){

	  $name['first']=$names[1].' '.$names[2];
	  $name['last']=$names[3];
	}else if( Contact::is_prefix($names[0]) and     Contact::is_givenname($names[1]) and   Contact::is_surname($names[2])  and  Contact::is_surname($names[3])){
	  
	  $name['first']=$names[1];
	  $name['last']=$names[2].' '.$names[3];
	  
	}else
	  $name['first']=$names[1].' '.$names[2];
	$name['last']=$names[3];
	

	// firt the most obious choise
      }else if(      Contact::is_givenname($names[0]) and Contact::is_givenname($names[1]) and    Contact::is_surname($names[2])  and  Contact::is_surname($names[3])     ){

	$name['first']=$names[0].' '.$names[1];
	$name['last']=$names[2].' '.$names[3];
      }else  if(      Contact::is_givenname($names[0]) and Contact::is_givenname($names[1]) and    Contact::is_givenname($names[2])  and  Contact::is_surname($names[3])     ){

	$name['first']=$names[0].' '.$names[1].' '.$names[2];
	$name['last']=$names[3];
      }else{
	$name['first']=$names[0];
	$name['last']=$names[1].' '.$names[2].' '.$names[3];
      }
      break;
    case(5):
      if( Contact::is_prefix($names[0]) and     Contact::is_givenname($names[1]) and   Contact::is_givenname($names[2])   and  Contact::is_surname($names[3]) and Contact::is_surname($names[4])  ){
	$name['prefix']=$names[0];
	$name['first']=$names[1].' '.$names[2];
	$name['first']=$names[3].' '.$names[4];
      }
      else
	$name['last']=join(' ',$names);
      break;
    default:
      $name['last']=join(' ',$names);
    
    }

  

    foreach($name as $key=>$value){
      $name[$key]=mb_ucwords($value);
     
    }
  


 
    $data['Contact Salutation']=_trim($name['prefix']);
    $data['Contact First Name']=_trim($name['first'].' '.$name['middle']);
    $data['Contact Surname']=_trim($name['last']);
    $data['Contact Suffix']=_trim($name['suffix']);
    
    return $data;
    


  }
  

 
  function display($tipo='name'){
    
  

    switch($tipo){
    case('name'):
      $name=_trim($this->data['Contact Salutation'].' '.$this->data['Contact First Name'].' '.$this->data['Contact Surname']);
      $name=preg_replace('/\s+/',' ',$name);
      return $name;
    case('file as'):
      $name=_trim($this->data['Contact Surname'].' '.$this->data['Contact First Name']);
      $name=preg_replace('/\s+/',' ',$name);
      return $name;
  

  case('informal gretting'):
      $gretting=_('Hello').' ';
      $name=_trim($this->data['Contact First Name']);
      $name=preg_replace('/\s+/',' ',$name);
      if(strlen($name)>1 and !preg_match('/^[a-z] [a-z]$/i',$name) and  !preg_match('/^[a-z] [a-z] [a-z]$/i',$name)  ) 
	return $gretting.$name;
  
   

    if(strlen($this->data['Contact Surname'])>1){
	$name=_trim(
		    $this->data['Contact Salutation'].' '.$this->data['Contact Surname']
		    );
   

	$name=preg_replace('/\s+/',' ',$name);
	return $gretting.$name;
      }
       
   return $this->unknown_informal_greeting;
      
      
    case('formal gretting'):
      $gretting=_('Dear').' ';
      if(strlen($this->data['Contact Surname'])>1){
	 
	if($this->data['Contact Salutation']!=''){
	  $name=_trim($this->data['Contact Salutation'].' '.$this->data['Contact Surname']);
	  return $gretting.$name;
	}elseif($this->data['Contact First Name']!=''){
	  $name=_trim($this->data['Contact First Name'].' '.$this->data['Contact Surname']);
	  return $gretting.$name;
	}
      }
      return $this->unknown_formal_greeting;	 
      
    }
    
    return false;
    
  }
  /*
   Method: gender
   Guess the gender from the name components

   Parameter:
   array with keys *Contact Salutation* and *Contact First Name*

   Return:
   Male,Felame,Unknown

   */
  
  public static function gender($data){
  
    $prefix=$data['Contact Salutation'];
    $first_name=$data['Contact First Name'];
    $sql=sprintf("select `Gender` from  `Salutation Dimension`  where `Salutation`=%s ",prepare_mysql($prefix));
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      
      if($row['Gender']=='Male' or $row['Gender']=='Female')
	return $row['Gender'];
    }
    
    
    $male=0;
    $felame=0;
    $names=preg_split('/\s+/',$first_name);
    foreach($names as $name){
      $sql=sprintf("select `Gender` as genero from  `First Name Dimension` where `First Name`=%s",prepare_mysql($name));
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	
	if($row['genero']=='Male')
	  $male++;
	if($row['genero']=='Felame')
	  $felame++;
      }
    }
    if($felame>$male)
      return 'Felame';
    else if ($male>$felame)
      return 'Male';
    else
      return 'Unknown';
  
  }
 /*
   Method: is_givenname
   Look for the First Name in the DB

   Parameter:
   string First Name

   Return:
   First Name Key of the First Name Dimension  DB record or 0 if not found

   */
  function is_givenname($name){
    $sql=sprintf("select `First Name Key` as id from  `First Name Dimension` where `First Name`=%s",prepare_mysql($name));
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      return $row['id'];
    }else
      return 0;
  }
 /*
   Method: is_surname
   Look for the Surname in the DB

   Parameter:
   string Surname

   Return:
   Key of the Surname Dimension  DB record or 0 if not found

   */


  function is_surname($name){

    $sql=sprintf("select `Surname` as id from  `Surname Dimension` where `Surname`=%s",prepare_mysql($name));
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      return $row['id'];
    }else
      return 0;
  }

 /*
   Method: is_prefix
   Look for the saludation in the DB

   Parameter:
   string Saludation

   Return:
   Key of the Saludation Dimension  DB record or 0 if not found

   */

  public static function is_prefix($name){
    $sql=sprintf("select `Salutation` as id from `Salutation Dimension`  where `Salutation`=%s",prepare_mysql($name));
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      return $row['id'];
    }else
      return 0;
  }


} 
 ?>