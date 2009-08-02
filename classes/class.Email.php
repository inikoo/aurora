<?php
/*
 File: Email.php 

 This file contains the Email Class

 Each email has to be associated with a contact if no contac data is provided when the Email is created an anonimous contact will be created as well. 
 

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/


include_once('class.Contact.php');

/* class: Email
 Class to manage the *Email Dimension* table
*/



class Email extends DB_Table {

 /*  public  $data=array(); */
/*   public $id=false; */
/*   public  $new=false; */
/*   public $error=false; */
/*   public $updated=false; */
/*   public $msg=''; */

  
  public $updated_fields=array();
  
  /*
   Constructor: Email
   Initializes the class, trigger  Search/Load/Create for the data set

   If first argument is find it will try to match the data or create if not found 
     
   Parameters:
   arg1 -    Tag for the Search/Load/Create Options *or* the Contact Key for a simple object key search
   arg2 -    (optional) Data used to search or create the object

   Returns:
   void
       
   Example:
   (start example)
   // Load data from `Email Dimension` table where  `Email Key`=3
   $key=3;
   $email = New Email($key); 
       
   // Load data from `Email Dimension` table where  `Email`='raul@gmail.com'
   $email = New Email('raul@gmail.com'); 
       
   // Insert row to `Email Dimension` table
   $data=array();
   $email = New Email('new',$data); 
       

   (end example)

  */
  function Email($arg1=false,$arg2=false) {
    
    $this->table_name='Email';
    $this->ignore_fields=array('Email Key');


    if(!$arg1 and !$arg2){
      $this->error=true;
      $this->msg='No data provided';
      return;
    }
    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return;
    }
    if ($arg1=='new'){
      $this->create($arg2);
      return;
    }
    if(preg_match('/find/i',$arg1)){
      $this->find($arg2,$arg1);
      return;
    }
    $this->get_data($arg1,$arg2);
  }
  /*
   Method: get_data
   Load the data from the database

   See Also:
   <find>
  */
  function get_data($tipo,$tag){
    if($tipo=='id')
      $sql=sprintf("select * from `Email Dimension` where  `Email Key`=%d",$tag);
    elseif($tipo=='email')
      $sql=sprintf("select * from `Email Dimension` where  `Email`=%s",prepare_mysql($tag));
    else
      return;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Email Key'];
  }
 

  /*
   Method: find
   Given a set of email components try to find it on the database updating properties, if not found creates a new record

   Parmaters:
   $raw_data - associative array with the email data (DB fields as keys)
   $options - string 
   
   auto - the method will update/create the email with out asking for instructions 
   create|update - methos will create or update the email with the data provided
   

  */

  private function find($raw_data,$options=''){

    $this->found=false;
    $this->found_in=false;
    $this->found_out=false;
    $this->candiadate=array();
    $in_contacts=array();
    $mode='Contact';
    $parent='Contact';


    $create=false;
    if(preg_match('/create|update/i',$options)){
      $create=true;
    }
    $auto=false;
    if(preg_match('/auto/i',$options)){
      $auto=true;
    }
    
    
    if(!$raw_data){
      $this->new=false;
      $this->msg=_('Error no email data');
      if(preg_match('/exit on errors/',$options))
	exit($this->msg);
      return false;
    }
    


 
    

    if(is_string($raw_data)){
      $tmp=$raw_data;
      unset($raw_data);
      $raw_data['Email']=$tmp;
    }

 if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
	
      }
    }

  
    $data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$data))
	$data[$key]=$value;
    }

    if($data['Email']==''){
      $this->msg=_('No email provided');
      return false;
    }elseif($this->wrong_email($data['Email'])){
      $this->msg=_('Wrong email').": ".$data['Email'];
      $this->error=true;
      return false;
    }else
      $data['Email Validated']=($this->is_valid($data['Email'])?'Yes':'No');

    $data['Email']=$this->prepare_email($data['Email']);
    $subject=false;
    $subject_key=0;
    $subject_type='Contact';

    if(preg_match('/in contact \d+/',$options,$match)){
      $subject_key=preg_replace('/[^\d]/','',$match[0]);
      $subject_type='Contact';
      
      $mode='Contact in';
      $in_contacts=array($subject_key);


    }
    if(preg_match('/in company \d+/',$options,$match)){
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
    

    if($raw_data['Email']=='contact@thebigstink.co.uk'){
      print_r($raw_data);
      print "------------here \n";
    }
    

    $sql=sprintf("select T.`Email Key`,`Subject Key` from `Email Dimension` T left join `Email Bridge` TB  on (TB.`Email Key`=T.`Email Key`) where `Email`=%s and `Subject Type`='Contact'  "
		 ,prepare_mysql($raw_data['Email'])
		   );
    //  print "$sql\n";    
    $result=mysql_query($sql);
    $num_results=mysql_num_rows($result);
    if($num_results==0){
      $this->found=false;
      if(preg_match('/auto/i',$options)){
	// try to find possible matches (assuming the the client comit a mistake)
	$sql=sprintf("select `Subject Key`,`Email Key`,`Email Contact Name`,levenshtein(UPPER(%s),UPPER(`Email`)) as dist1,levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Email`))) as dist2, `Subject Key`  from `Email Dimension` left join `Email Bridge` on (`Email Bridge`.`Email Key`=`Email Dimension`.`Email Key`)  where dist1<=2 and  `Subject Type`='Contact'  order by dist1,dist2 limit 20"
		     ,prepare_mysql($raw_data['Email'])
		     ,prepare_mysql($raw_data['Email'])
		     );
	$result=mysql_query($sql);

	while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	  $dist=0.5*$row['dist1']+$row['dist2'];
	  if($dist==0)
	    $candidate[$row['Subject Key']]=1000;
	  else
	    $candidate[$row['Subject Key']]=100/$dist;
	  
	  if($raw_data['Email Contact Name']!=''){
	       $contact_distance=levenshtein(strtolower($raw_data['Email Contact Name']),strtolower($row['Email Contact Name']));
	       if($contact_distance==0){
		 if($raw_data['Email Contact Name']=='')
		   $candidate[$row['Subject Key']]+=50;
		 else
		   $candidate[$row['Subject Key']]+=300;
	       }
	       
	       
	       $candidate[$row['Subject Key']]+=(200/$contact_distance);
	       
	       
	     }
	  
	}
      }

      
    }else if($num_results==1){
	$this->found=true;

	$row=mysql_fetch_array($result, MYSQL_ASSOC);
	$this->found_key=$row['Subject Key'];
	$this->candidate[$row['Subject Key']]=1000;
	$this->get_data('id',$row['Email Key']);
	

      }else{// Found in more than one contact (that means tha two contacts share the same email) this shoaul not happen
      
      $this->error=true;
      // correct the data (delete duplicates)

      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$contact=new Contact($row['Subject Key']);
	print_r($contact->data);
      }


      exit("todo fix database for email duplicates \n$sql\n");
    }
      
  

    if($create){
      if($this->found)
	$this->update($data,$options);
      else{
	// not found
	if($auto){
	  usort($this->candidate);
	  foreach($this->candidate as $key =>$val){
	    if($val>=250){
	      $this->found=true;
	      $this->found=true;
	      if(in_array($key,$in_contact))
		$this->found_in=true;
	      else
		$this->found_out=true;

	      $this->get_data('id',$key);
	      $this->update($data,$options);
	      return;
	    }
	  }

	}

	$this->create($data,$options);

      }

    }

    
  
}




/*Method: create
 Creates a new email record

*/
protected function create($data,$options=''){
  
  // print_r($data);

  //print $this->editor;
  
  if(!$data){
    $this->new=false;
    $this->msg.=" Error no email data";
    $this->error=true;
    if(preg_match('/exit on errors/',$options))
      exit($this->msg);
    return false;
  }
    
  if(is_string($data))
    $data['Email']=$data;

  global $myconf;
    
  $this->data=$this->base_data();
  foreach($data as $key=>$value){
    if(array_key_exists($key,$this->data))
      $this->data[$key]=$value;
  }
    


  if($this->data['Email']==''){
    $this->new=false;
    $this->msg=_('No email provided');
    return false;
  }
    

  $sql=sprintf("select * from `Email Dimension` where `Email`=%s"
	       ,prepare_mysql($this->data['Email'])
	       );
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    print "Error trying to add a duplicate email:\n";
    print_r($data);
  }

    
  if(!preg_match('/do not validate|validated ok/',$options))
    if($this->is_valid($this->data['Email']))
      $this->data['Email Validated']='Yes';
  



  $sql=sprintf("insert into `Email Dimension`  (`Email`,`Email Contact Name`,`Email Validated`,`Email Correct`) values (%s,%s,%s,%s)"
	       ,prepare_mysql($this->data['Email'])
	       ,prepare_mysql($this->data['Email Contact Name'])
	       ,prepare_mysql($this->data['Email Validated'])
	       ,prepare_mysql($this->data['Email Correct'])
	       );

  if(mysql_query($sql)){
    $this->id = mysql_insert_id();
    $this->get_data('id',$this->id);
    $this->new=true;
      
    $this->msg=_('New Email');
    
     $history_data=array(
			 'note'=>_('Email Created')
			 ,'details'=>_trim(_('Email')." \"".$this->display('plain')."\"  "._('created'))
			 ,'action'=>'created'
			 );
      $this->add_history($history_data);
    

    if(preg_match('/anonimous|anonymous/',$options) ){
      $contact=new Contact('create anonimous');
      $contact->add_email(array(
				'Email Key'=>$this->id
				,'Email Type'=>'Unknown'
				));
    }


    


    return true;
  }else{
    $this->new=false;
    $this->error=true;
    $this->msg=_('Error can not create email');
    if(preg_match('/exit on errors/',$options)){
      print "Error can not create email;\n";exit;
    }
  }
     
     
}

function get($key){
  if(isset($this->data[$key]))
    return $this->data[$key];
   
  switch($key){
  case('link'):
    return $this->display();
    break;
  }
  $_key=ucfirst($key);
  if(isset($this->data[$_key]))
    return $this->data[$_key];
  print "Error $key not found in get from email\n";
  return false;
   
}



 /*Function: update_field_switcher
  */

protected function update_field_switcher($field,$value,$options=''){

  switch($field){
  case('Email'):
    $this->update_Email($value,$options);
    break;
  case('Email Validated'):
    $this->update_EmailValidated($value,$options);
    break;
  case('Email Correct'):
    $this->update_EmailCorrect($value,$options);
    break;
  case('Email Contact Name'):
    $this->update_EmailContactName($value,$options);
    break;
  default:
    $this->update_field($field,$value,$options);
  }
  
}




/*Method: update_Email
 Update email address
 
 Return error if no email is provided or if there is another record with the same email address, a warning is returned if email not valid

 When $options is strict return error if the email is not valid
*/

function update_Email($data,$options=''){
  //$this->error=false;
  //$this->warning=false;
  //$this->updated=false;
  if($data==''){
    $this->msg.=_('Email address can not be blank')."\n";
    $this->error=true;
    return;
  }
  
  $is_valid=$this->is_valid($data);
  if(!$is_valid){
    $this->msg.=_('Email is not valid')." ($data)\n";
    if(preg_match('/email strict/i',$options) ){
      $this->error=true;
      return;
    }
    $this->warning=true;
  }



  $old_value=$this->data['Email'];
  $sql=sprintf("update `Email Dimension` set `Email`=%s where `Email Key`=%d ",prepare_mysql($data),$this->id);
  mysql_query($sql);
  // print "$sql\n";
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg.=_('Email address can not be updated')."\n";
    $this->error=true;
    return;
  }elseif($affected==0){
    //$this->msg=_('Same value as the old record');
    
  }else{
    $this->msg.=_('Email updated')."\n";
    $this->data['Email']=$data;
    $this->updated=true;
    $this->update_EmailValidated($options);
    $this->updated_fields['Email']=array(
					 'Old Value'=>$old_value
					 ,'New Value'=>$this->data['Email']
					 );
    
    $history_data['action']='Email Address Changed';
    $history_data['details']=_('Email address changed')." ".$old_value." -> ".$this->data['Email'];
    $history_data['direct_object']='Email';
    $history_data['direct_object_key']=$this->id;
    $history_data['indirect_object']='Email Address';
    $history_data['indirect_object_key']=0;
    $this->add_history($history_data);


  }
  

}


/*Method: update_EmailValidated
 Update email address Is Valid field
*/
function update_EmailValidated($options=''){

  $is_valid=$this->is_valid($this->data['Email']);
  if($is_valid)
    $valid='Yes';
  else
    $valid='No';
  $sql=sprintf("update `Email Dimension` set `Email Validated`=%s where `Email Key`=%d ",prepare_mysql($valid),$this->id);
  mysql_query($sql);
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg.=' '._('Email Validated can not be updated')."\n";
    $this->error=true;
    return;
  }elseif($affected==0){
    //$this->msg.=' '._('Same value as the old record');
    
  }else{
    $this->msg.=' '._('Record updated')."\n";
    $this->updated=true;
    $this->updated_fields['Email Validated']=true;
  }
  

}
/*Method: update_EmailCorrect
  Update Email Correct field

*/
function update_EmailCorrect($data,$options=''){


  if(!($data=='Yes' or $data=='No' or $data=='Unknown')){
    $this->msg.=' '._('Field wrong value')." $data";
    $this->error=true;
    return;
  }
    

  $sql=sprintf("update `Email Dimension` set `Email Correct`=%s where `Email Key`=%d ",prepare_mysql($data),$this->id);

  mysql_query($sql);
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg.=' '._('Record can not be updated')."\n";
    $this->error=true;
    return;
  }elseif($affected==0){
    //$this->msg.=' '._('Same value as the old record');
    
  }else{
    $this->data['Email Correct']=$data;
    if($this->data['Email Correct']=='Yes')
      $this->msg.=' '._('Email confirmed as correct')."\n";
    else
      $this->msg.=' '._('Email confirmed as incorrect')."\n";

    $this->updated_fields['Email Correct']=true;
    $this->updated=true;

  }
  

}

/*Method: update_EmailContactName
 Update email contact name  field
*/
private function update_EmailContactName($data,$options=''){
  $this->error=false;
  $this->warning=false;
  $this->updated=false;

  $sql=sprintf("update `Email Dimension` set `Email Contact Name`=%s where `Email Key`=%d ",prepare_mysql($data,false),$this->id);
  mysql_query($sql);
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg.=' '._('Record can not be updated')."\n";
    $this->error=true;
    return;
  }elseif($affected==0){
    
    
  }else{
    $this->updated_fields['Email Contact Name']=true;
    $this->msg.=' '._('Record updated')."\n";
    $this->data['Email Contact Name']=$data;
    $this->updated=true;
  }
}










 


function display($tipo='link'){


  if(!isset($this->data['Email'])){
    print_r($this);
    exit;
  }

  switch($tipo){
  case('plain'):
    return $this->data['Email'];

  case('html'):
  case('xhtml'):
  case('link'):
  default:
    return '<a href="mailto:'.$this->data['Email'].'">'.$this->data['Email'].'</a>';
     
  }
   

}


/*
 Method: is_valid
 Check if the email is valid
 
 Returns:
 true or false
*/
 
public static function is_valid($email){
  // First, we check that there's one @ symbol, and that the lengths are right
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
    return false;
  }
 
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
      return false;
    }
  }
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
      return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
	return false;
      }
    }
  }
  return true;
}

public static function  prepare_email($email){
  
  $email_parts=split('@',$email);
  if(count($email_parts)==2){
    return $email_parts[0].'@'.strtolower($email_parts[1]);
  }else
    return $email;

}

public static function  wrong_email($email){
  
  if(!preg_match('/\@/',$email))
    return true;
  if(preg_match('/^\@|\@$/',$email))
    return true;
  $email_parts=split('@',$email);
  if(count($email_parts)!=2)
    return true;
  return false;

    }



}

?>