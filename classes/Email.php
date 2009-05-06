<?
/*
 File: Email.php 

 This file contains the Email Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('Contact.php');

/* class: Email
 Class to manage the *Email Dimension* table
*/
class Email{

  var $data=array();
  var $id=false;

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

     
    if(!$arg1 and !$arg2)
      return;
    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return;
    }
    if ($arg1='new'){
      $this->create($arg2);
      return;
    }
    if ($arg1='find'){
      $this->find_replace($arg2);
      return;
    }


    $this->get_data($arg1,$arg2);

  }
  /*
   Method: get_data
   Load the data from the database

   See Also:
   <find_replace>
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
   Function: base_data
   Initializes array with the default field values
  */
  function base_data($args='replace'){


    $data=array(
		'Email'=>''
		,'Email Contact Name'=>''
		,'Email Validated'=>'No'
		,'Email Correct'=>'Unknown'
		);

    if(preg_match('/not? replace/',$args))
      return $data;
    if(preg_match('/replace/',$args))
      $this->data=$data;
    return $data;
  }


  /*
   Method: find_replace
   Given a set of email components try to find it on the database updating properties, if not found creates a new record

   The default is to update/create 

  */

  Public function find_replace($data,$options=''){
    if(!$data){
      $this->new=false;
      $this->msg=_('Error no email data ');
      if(preg_match('/exit on errors/',$args))
	exit($this->msg);
      return false;
    }
    
    if(is_string($data))
      $data['Email']=$data;
    
    $base_data=$this->base_data;
    foreach($data as $key=>$value){
      if(is_key($key,$base_data))
	$base_data[$key]=$value;
    }

    if($base_data['Email']==''){
      $this->msg=_('no email provided');
      return false;
    }

    if(preg_match('/in contact \d+/',$options,$match))
      $in_contact=preg_replace('/[^\d]/','',$match[0]);
    else
      $in_contact=0;
    

    $sql=sprintf("select `Email Key`,`Contact Key`  from `Email Dimension` left join `Email Bridge` on (`Email Bridge`.`Email Key`=`Email Dimension`.`Email Key`)  where  `Subject Type`='Contact'  and   `Email`=%s",prepare_mysql($tag));
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      if($in_contact){
	if($in_contact==$row['Contact Key']){
	  $this->get_data('id',$row['Email Key']);
	  $this->update($data);
	  return $this->id;
	}else{
	  $contact=new Contact($row['Contact Key']);
	  $this->msg=_('Email found in another contact').sprintf('. %s (%d)',$contact->display('name'),$contact->id);
	  return 0;
	}


    }else{
      $this->get_data('id',$row['Email Key']);
      return $this->id;
    }
    }

  

    
  // Email not found
  // Search for possible matches
  $candidate=array();
  If($in_contact){
    $sql=sprintf("select `Email Key`,`Email Contact Name`,levenshtein(UPPER(%s),UPPER(`Email`)) as dist1,levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Email`))) as dist2, `Subject Key`  from `Email Dimension` left join `Email Bridge` on (`Email Bridge`.`Email Key`=`Email Dimension`.`Email Key`)  where dist1<=2 and  `Subject Type`='Contact' `Subject Key`=%d and  order by dist1,dist2 limit 20"
		 ,prepare_mysql($data['Email'])
		 ,prepare_mysql($data['Email'])
		 ,$in_contact
		 );
    $result=mysql_query($sql);
    
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $dist=0.5*$row['dist1']+$row['dist2'];
      if($dist==0)
	$candidate[$row['Email Key']]['score']=1000;
      else
	$candidate[$row['Email Key']]['score']=100/$dist;
      
      if($data['Email Contact Name']!=''){
	$contact_distance=levenshtein(strtolower($data['Email Contact Name']),strtolower($row['Email Contact Name']));
	if($contact_distance==0){
	  if($data['Email Contact Name']=='')
	    $candidate[$row['Email Key']]['score']+=50;
	  else
	    $candidate[$row['Email Key']]['score']+=300;
	}


	$candidate[$row['Email Key']]['score']+=(200/$contact_distance);
	
	
      }
      
    }
    

    
  }else{
    
    $sql=sprintf("select `Email Key`,`Email Contact Name`,levenshtein(UPPER(%s),UPPER(`Email`)) as dist1,levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Email`))) as dist2, `Subject Key`  from `Email Dimension` where dist1<=2  order by dist1,dist2 limit 20"
		 ,prepare_mysql($data['Email'])
		 ,prepare_mysql($data['Email'])
		 );
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $dist=0.5*$row['dist1']+$row['dist2'];
      if($dist==0)
	$candidate[$row['Email Key']]['score']=1000;
      else
	$candidate[$row['Email Key']]['score']=100/$dist;
    }

  }
  
  $num_candidates=count($candidate);
  
  if($num_candidates==0){
    // no maches create new
    $this->create($data);
    if($this->new)
      return $this->id;
    else
      return false;

  }

  asort($candidate);
  foreach ($candidate as $key => $val) {
    $email_key=$key;
    break;
  }
  $this->get_data('id',$email_key);
  $this->update($data);
  return $this->id;

  
  
}




/*Method: create
 Creates a new email record

*/
protected function create($data,$args=''){
    
  if(!$data){
    $this->new=false;
    $this->msg=_('Error no email data ');
    if(preg_match('/exit on errors/',$args))
      exit($this->msg);
    return false;
  }
    
  if(is_string($data))
    $data['Email']=$data;

  global $myconf;
    
  $this->base_data();
  foreach($data as $key=>$value){
    if(is_key($key,$this->data))
      $this->data[$key]=$value;
  }
    


  if($this->data['email']==''){
    $this->new=false;
    $this->msg=_('No email provided');
    return false;
  }
    
  if(!preg_match('/do not validate|validated ok/',$args))
    if($this->is_valid($this->data['Email']))
      $this->data['Email Validated']='Yes';
    
  $sql=sprintf("insert into `Email Dimension`  (`Email`,`Email Contact Name`,`Email Validated`,`Email Correct`) values (%s,%s,%s,%s,%d,%d)"
	       ,prepare_mysql($this->data['Email'])
	       ,prepare_mysql($this->data['Email Contact Name'])
	       ,$this->data['Email Validated']
	       ,$this->data['Email Correct']
	       );
   
  if(mysql_query($sql)){
    $this->id = mysql_insert_id();
    $this->get_data('id',$this->id);
    $this->new=true;
      
    $this->msg=_('New Email');
    return true;
  }else{
    $this->new=false;
    $this->msg=_('Error can not create email');
    if(preg_match('/exit on errors/',$args)){
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

function set($key,$value){
  switch($key){
  default:
    $this->data[$key]=$value;
  }

}
/*Method: update
 Switcher calling the apropiate update method
 
*/

function update($data,$args=''){
  $base_data=$this->base_data('no replace');
  foreach($data as $key=>$value){
    if(is_key($key,$base_data)){
      $function_name=preg_replace('\s','',ucwords($key));
      call_user_func(array($this, 'update_'.$function_name),$value,$args);
    }
      
  }

}

/*Method: update_Email
 Update email address
 
 Return error if no email is provided or if there is another record with the same email address, a warning is returned if email not valid

 When $args is strict return error if the email is not valid
*/

function update_Email($data,$args=''){
  $this->error=false;
  $this->warning=false;
  $this->updated=false;
  if($data!=''){
    $this->msg=_('Email address can not be blank');
    $this->error=true;
    return;
  }
  
  $is_valid=$this->is_valid($data);
  if(!$is_valid){
    $this->msg=_('Email is not valid')." ($data)";
    if(preg_match('/email strict/i',$args) ){
      $this->error=true;
      return;
    }
    $this->waring=true;
  }
  $sql=sprintf("update Email Dimension` set `Email`=%s where `Email Key`=%d ",prepare_data($data),$this->id);
  mysql_query($sql);
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg=_('Email address can not be updated');
    $this->error=true;
    return;
  }elseif($affected==0){
    $this->msg=_('Same value as the old record');
    
  }else{
    $this->msg=_('Record updated');
    $this->updated=true;
    $this->update_EmailValidated();
  }
  

}


/*Method: update_EmailValidated
 Update email address Is Valid field
*/
function update_EmailValidated($data,$args=''){
  $this->error=false;
  $this->warning=false;
  $this->updated=false;
  $is_valid=$this->is_valid($data);
  if($is_valid)
    $valid='Yes';
  else
    $valid='No';
  $sql=sprintf("update Email Dimension` set `Email Validated`=%s where `Email Key`=%d ",prepare_data($valid),$this->id);
  mysql_query($sql);
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg=_('Email Validated can not be updated');
    $this->error=true;
    return;
  }elseif($affected==0){
    $this->msg=_('Same value as the old record');
    
  }else{
    $this->msg=_('Record updated');
    $this->updated=true;

  }
  

}
/*Method: update_EmailCorrect
 Update *Email Correct* field
*/
function update_EmailCorrect($data,$args=''){
  $this->error=false;
  $this->warning=false;
  $this->updated=false;

  if(!($data=='Yes' or $data=='No' or $data=='Unknown')){
    $this->msg=_('Field wrong value')." $data";
    $this->error=true;
    return;
  }
    

  $sql=sprintf("update Email Dimension` set `Email Correct`=%s where `Email Key`=%d ",prepare_data($data),$this->id);
  mysql_query($sql);
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg=_('Record can not be updated');
    $this->error=true;
    return;
  }elseif($affected==0){
    $this->msg=_('Same value as the old record');
    
  }else{
    $this->msg=_('Record updated');
    $this->updated=true;

  }
  

}

/*Method: update_EmailContactName
 Update email contact name  field
*/
private function update_EmailContactName($data,$args=''){
  $this->error=false;
  $this->warning=false;
  $this->updated=false;

  $sql=sprintf("update Email Dimension` set `Email Contact Name`=%s where `Email Key`=%d ",prepare_data($data,false),$this->id);
  mysql_query($sql);
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg=_('Record can not be updated');
    $this->error=true;
    return;
  }elseif($affected==0){
    $this->msg=_('Same value as the old record');
    
  }else{
    $this->msg=_('Record updated');
    $this->updated=true;
  }
}









function save_history($key,$history_data){

  $old=$this->old_value;
  if($key=='new'){
    $old='';
    $new=$this->get('email');
  }else{
    $new=$this->get($key);
    $old=$this->old_value;
  }
  if(isset($history_data['msg'])){
    $note=$history_data['msg'];
  }else
    $note=$this->update_msg;

  if(
     isset($history_data['sujeto']) and 
     isset($history_data['sujeto_id'])and 
     isset($history_data['objeto']) and 
     isset($history_data['objeto_id'])
     ){
     
    $sujeto=$history_data['sujeto'];
    $sujeto_id=$history_data['sujeto_id'];
    $objeto=$history_data['objeto'];
    $objeto_id=$history_data['objeto_id'];
    if($key=='new')
      $tipo='NEW';
    else
      $tipo='CHGEML';


  }else{
    $sujeto='EMAIL';
    $sujeto_id=$this->$id;
    $objeto=$key;
    $objeto_id='';
    if($key=='new')
      $tipo='NEW';
    else
      $tipo='CHG';
    switch($key){
    case('email'):
      $objeto='EMAIL';
      break;
    case('contact'):
      $objeto='EMAILC';
      break;
    case('verified'):
      $objeto='EMAILV';
      break;
    case('tipo'):
      $objeto='EMAILT';
      break;
    case('contact_id'):
      $objeto='EMAILC';
      break;
    case('new'):
      $objeto='';
      break;
    }

  }

  $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
	       ,$date
	       ,prepare_mysql($sujeto)
	       ,prepare_mysql($sujeto_id)
	       ,prepare_mysql($objeto)
	       ,prepare_mysql($objeto_id)
	       ,prepare_mysql($action)
	       ,prepare_mysql($user_id)
	       ,prepare_mysql($old)	 
	       ,prepare_mysql($new)	 
	       ,prepare_mysql($note)); 

  mysql_query($sql);


}
 


function display($tipo='link'){

  switch($tipo){
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


}

?>