<?
include_once('Contact.php');
class Email{
  var $db;
  var $data=array();
  var $id=false;

  
  function __construct($arg1=false,$arg2=false) {
     $this->db =MDB2::singleton();

     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return;
     }
      if(is_array($arg2) and $arg1='new'){
       $this->create($arg2);
       return;
     }
      $this->get_data($arg1,$arg2);

  }


  function get_data($tipo,$tag){
    
    if($tipo=='id')
      $sql=sprintf("select * from email where  id=%d",$id);
    elseif($tipo=='email')
      $sql=sprintf("select * from email where  email=%d",$id);
    
   $result =& $this->db->query($sql);
    if($this->data=$result->fetchRow()){
      $this->id=$this->data['id'];
      return true;
    }
    return false;
}

  function create($data,$history_data=false){
   if($data['email']==''){
     $this->new_email=false;
     return false;
   }
   if($this->is_valid($data['email']))
     $validated='yes';
   else
     $validated='no';
   $sql=sprintf("insert into email  (email,contact,tipo,tipo_email,contact_id,validated) values (%s,%s,%d,%d)"
		,prepare_mysql($data['email'])
		,prepare_mysql($data['contact'])
		,$data['tipo']
		,$data['tipo_email']
		,$data['contact_id']
		,$validated
		);
   $affected=& $this->db->exec($sql);
   if (PEAR::isError($affected)){
      $this->new_email=false;
      return false;
   }else{
      $this->new_email=$this->db->lastInsertID();
      if(is_array($history_data))
	$this->save_history('new',$history_data);
      return true;
   }
     
     
 }

 function get($key){
    switch($key){
    case('link'):
      return $this->display();
      break;
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
    }
    return false;
 }

 function set($key,$value){
    switch($key){
    default:
      $this->data[$key]=$value;
    }

 }
 
 function update($data,$args=false,$history_data=false){
   $key=key($data);
   $value=$data['value'];
   switch($key){
   case('email'):
     if($value==''){
       $this->update_msg=_('The new email is empty');
       return false;
     }
     if(!$this->is_valid($value)){
       $this->update_msg=_('The new email is not valid');
       return false;
     }
     if($value==$this->get($key)){
       $this->update_msg=_('The new email is the same as the old one');
       return false;
     }
     $this->update_msg=_('Email changed to')." ".$value;

     break;     
   case('contact'):
     if($value==$this->get($key)){
       $this->update_msg=_('The new email contact  is the same as the old one');
       return false;
     }

     $this->update_msg=_('Email contact changed to')." ".$value;
     break;    
   case('tipo'):
     if($value==$this->get($key)){
       $this->update_msg=_('The new email type is the same as the old one');
       return false;
     }

     $this->old_value2=$this->get('tipo_email');
     switch($value){
     case(0):
       $this->set('tipo_email','work');
       break;
     case 1:
       $this->set('tipo_email','personal');
       break;
     case 2:
       $this->set('tipo_email','company');
       break;
     default:
       $this->update_msg=_('Wrong email type');
       return false;
     }

     $this->update_msg=_('Email type changed to')." ".$this->get('tipo_email');
     break; 
 case('tipo_email'):
    if($value==$this->get($key)){
       $this->update_msg=_('The new email type is the same as the old one');
       return false;
     }

     $this->old_value2=$this->get('tipo');
     switch($value){
     case('work'):
       $this->set('tipo',0);
       break;
     case ('personal'):
       $this->set('tipo_email',1);
       break;
     case ('company'):
       $this->set('tipo_email',2);
       break;
     default:
       $this->update_msg=_('Wrong email type');
       return false;
     }

     $this->update_msg=_('Email type changed to')." ".$this->get('tipo_email');
     break; 
   case('contact_id'):
     if($value==$this->get($key)){
       $this->update_msg=_('The new email contact the same as the old one');
       return false;
     }
     $contact=new Contact($value);
     if(!$contact->id){
       $this->update_msg=_('Contact do not exist');
       return false;
     }
     if($contact->get('has_email_id',$id)){
       $this->update_msg=_('Contact already has this email');
       return false;
     }
     
     break;

   default:
      $this->update_msg=_('Wrong update key');
      return false;
   }
   $this->old_value=$this->get($key);
   $this->set($key,$value);
   
   if(preg_match('/save/',$args)){
     $this->save($key,$history_data);
     if($key=='tipo'){
       $this->old_value=$this->old_value2;
       $this->save('tipo_email',$history_data);
     }elseif($key=='tipo_email'){
       $this->old_value=$this->old_value2;
       $this->save('tipo',$history_data);
     }

   }
 }



 function save($key,$history_data=false){
    switch($key){


    default:
      $sql=sprintf("update email set %s=%s where id=%d",$key,$this->get($key),$this->id);
      $this->db->exec($sql);
      if(is_array($history_data)){
	$this->save_history($key,$history_data);
      }
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
    // print $sql;
    $this->db->exec($sql);


 }
 


 function display($tipo='link'){

   switch($tipo){
   case('link'):
   default:
     return '<a href="mailto:'.$this->data['email'].'">'.$this->data['email'].'</a>';
     
   }
   

 }



 
 function is_valid($email){
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