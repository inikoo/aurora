<?php
/*
 File: Warehouse.php 

 This file contains the Warehouse Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Email.php');
include_once('class.Customer.php');
require("external_libs/mail/email_message.php");
class EmailSend extends DB_Table{

  var $areas=false;
  var $locations=false;
  
  function EmailSend() {

    $this->secret="A390%4m*eodO)PIPOldyhs.dpdbwid";
    $this->table_name='Email Send';
    $this->ignore_fields=array('Email Send Key','Email Send First Read Date','Email Send Last Read Date','Email Send Number Reads','Email Send Date');

  }


  function get_data($key,$tag){
    
    if($key=='id')
      $sql=sprintf("select *  from `Email Send Dimension` where `Email Send Key`=%d",$tag);
    
    else
      return;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Email Send Key'];
    }
      



  }
 


function create($data){

  



   $this->new=false;
   $base_data=$this->base_data();
  
    foreach($data as $key=>$value){
      if(array_key_exists($key,$base_data))
	$base_data[$key]=_trim($value);
    }

      $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Email Send Dimension` %s %s",$keys,$values);
    //  print $sql;
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->msg=_("Email to be Send Created");
      $this->get_data('id',$this->id);
   $this->new=true;
   return;
 }else{
   $this->msg=_(" Error can not create email to be send");
 }
}
 

  function get($key,$data=false){
    switch($key){
    case('Number Reads'):
      if($this->data['Email Send Number Reads']=='')
	return _('ND');
      else
	return number($this->data['Email Send Number Reads']);
      break;
    default:
      if(array_key_exits($key,$this->data))
	return $this->data[$key];
      else
	return '';
    }
  
  } 
   

  function prepare_email_variables(){
    $this->email_data['Images Server']='http://213.123.184.178';
    $this->email_data['Tracker Server']='http://213.123.184.178';
    $this->email_data['Content Server']='http://213.123.184.178';
    
    $this->email_data['Images Path']='/app_files/email/art/';
    $this->email_data['Tracker Path']='/app_files/email/';
    $this->email_data['Content Path']='/app_files/email/content';

    
  

  }



  function compose_gold_reminder_email($customer_key,$data=false){
  
    if(!is_array($data))
      $data=array();

     $subject=new Customer($customer_key);
     if($subject->data['Customer Main Plain Email']==''){
       $this->error=true;
       $this->msg='Customer with no email';
       return;
     }

     
     $this->prepare_email_variables();
     $this->email_data['Template']='emails/html_email_basic_template.html';
    
      $store=new Store($subject->data['Customer Store Key']);

      $this->email_data['Image Server Logo Filename']='email_header_'.$store->data['Store Code'].'.png';
   


      $this->email_data['Our Name']=$store->data['Store Contact Name'];
      $this->email_data['Our Company']=$store->data['Store Name'];

      $this->email_data['Our Telephone']=$store->data['Store Telephone'];
      $this->email_data['Our Email']=$store->data['Store Email'];
      $this->email_data['Our URL']=$store->data['Store URL'];


 
      $this->email_data['Our Position']='';

      
      if($subject->data['Customer Type']=='Company'){
	$this->email_data['To Company']=$subject->data['Customer Name'];
      }
      $this->email_data['To Greatings']=$subject->data['Customer Main Contact Name'];
      if($this->email_data['To Greatings']=='')
	$this->email_data['To Greatings']=$subject->data['Customer Name'];
      
      if($this->email_data['To Greatings']==$this->email_data['To Company'])
	$this->email_data['To Company']='';


      
      
      $this->email_data['From Email Address']=$store->data['Store Email'];




     


      $this->email_data['To Email Address']=$subject->data['Customer Main Plain Email'];

      $this->email_data['To Email Address']='rulovico@gmail.com';
            $this->email_data['To Email Address']='katka@aw-geschenke.com';

      foreach($data as $key=>$values){
	if(array_key_exists($key,$this->email_data))
	  $this->email_data[$key]=$values;
      }
      


       switch($store->data['Store Locale']){
      default:
	if($this->email_data['To Greatings']=='')
	  $this->email_data['To Greatings']='Sehr geehrte Damen und Herren';
	else
	  $this->email_data['To Greatings']='Sehr geehrte Damen und Herren';
	

	$this->email_data['Subject']="Gold Reward Reminder";
	$this->email_data['Content'][]=array(
				     'title'=>"Gold Reward Reminder"
				     ,'content'=>"We would like to take this opportinity to remind you about your Gold Reward. As you know provided that you order within 30 days of your last order, you maintain your Gold Reward status.<br><br>Your last order was on ".strftime("%x",strtotime($subject->data['Customer Last Order Date']))." so you need to re-order before ".strftime("%x",strtotime($subject->data['Customer Last Order Date'].' +30 day'))." to maintain your status.<br><br>If you have any questions or requests please don't hesitate to contact me."
				     );
      }
       $this->email_data['Content Text Only']= "Dear ".$this->email_data['To Greatings'].",\n\We would like to take this opportinity to remind about your Gold Reward. As you know provided that you order within 30 days of your last order, you maintain your Gold Reward status.\n\nYour last order was on ".strftime("%x",strtotime($subject->data['Customer Last Order Date']))." so you need to re-order before ".strftime("%x",strtotime($subject->data['Customer Last Order Date'].' +30 day'))." to maintain your status.\n\nIf you have any questions or requests please don't hesitate to contact me.\nKind Regards\n\n".$this->email_data['Our Name']."\n".$this->email_data['Our Position']."\n".$this->email_data['Our Company']."\n\n".$this->email_data['Our Telephone']."\n\nIf you don't want to receive this kind of email please let us know.";

       $recipient_type='Customer';
       $recipient_key=$subject->id;
 $email=new Email('email',$this->email_data['To Email Address']);

     $data=array(
		'Email Send Type'=>'Registration',
		'Email Send Type Key'=>0,
		'Email Send Recipient Type'=>$recipient_type,
		'Email Send Recipient Key'=>$recipient_key,
		'Email Key'=>$email->id
		);
    
    $this->create($data);
    $this->email_data['Tracker Key']=$this->id.'_'.md5($this->secret.$this->id);


   }
  function compose_registration_email($user_key,$data=false){
    if(!is_array($data))
      $data=array();


    $this->prepare_email_variables();


    $this->email_data['Template']='emails/html_email_basic_template.html';

    $user=new User($user_key);
    if(!$user->id){
      $this->error=true;
      $this->msg='User not found';
      return;
    }
    



    if(preg_match('/^Customer/',$user->data['User Type'])){
     
      $subject=new Customer($user->data['User Parent Key']);
      $store=new Store($subject->data['Customer Store Key']);

      $this->email_data['Image Server Logo Filename']='email_header_'.$store->data['Store Code'].'.png';


      $this->email_data['Our Name']=$store->data['Store Contact Name'];
      $this->email_data['Our Company']=$store->data['Store Name'];

      $this->email_data['Our Telephone']=$store->data['Store Telephone'];
      $this->email_data['Our Email']=$store->data['Store Email'];
      $this->email_data['Our URL']=$store->data['Store URL'];

    
      $this->email_data['From Email Address']=$store->data['Store Email'];

     
      //$this->email_data['To Email']=$user->['User Handle'];
      $this->email_data['To Email Address']='rulovico@gmail.com';
      //   $this->email_data['To Email Address']='raul@ancientwisdom.biz';
      
      
      $this->email_data['Our Position']='';

      
      if($subject->data['Customer Type']=='Company'){
	$this->email_data['To Company']=$subject->data['Customer Name'];
      }
      
      $this->email_data['To Name']=$subject->data['Customer Main Contact Name'];

      $this->email_data['To Greatings']=$subject->data['Customer Main Contact Name'];
      if($this->email_data['To Greatings']=='')
	$this->email_data['To Greatings']=$subject->data['Customer Name'];
      
      if($this->email_data['To Greatings']==$this->email_data['To Company'])
	$this->email_data['To Company']='';

      switch($store->data['Store Locale']){
      case('de_DE'):
	if($this->email_data['To Greatings']=='')
	  $this->email_data['To Greatings']='Sehr geehrte Damen und Herren';
	else
	  $this->email_data['To Greatings']='Sehr geehrte/r '.$this->email_data['To Greatings'];

	$this->email_data['Subject']="Vielen Dank f√ºr Ihre Registrierung bei ".$this->email_data['Our Company'];
	$this->email_data['Content'][]=array(
				     'title'=>"Vielen Dank f√ºr Ihre Registrierung bei ".$this->email_data['Our Company']."!"
				     ,'content'=>"Sehen Sie nun unsere Preise und bestellen Sie aus einer Vielzahl toller Produkte.<br/><br/>"
				     );
	
	break;
      default:
	if($this->email_data['To Greatings']=='')
	  $this->email_data['To Greatings']='Dear Sir/Madam';
	else
	  $this->email_data['To Greatings']='Dear '.$this->email_data['To Greatings'];
	

	$this->email_data['Subject']="Thank you for your registration with ".$this->email_data['Our Company'];
	$this->email_data['Content'][]=array(
				     'title'=>"Thank you for your registration with ".$this->email_data['Our Company']."!"
				     ,'content'=>"You will now be able to see our prices and order from our big range of products.<br/><br/>"
				     );
      }
      $this->email_data['Content Text Only']=$this->email_data['To Greatings']."\n\nThank you for your registration with ".$this->email_data['Our Company']."!\n\nYou will now be able to see our prices and order from our big range of products\n\nRemenber that your username is ".$user->data['User Handle']."\n\n".$this->email_data['Our Name']."\n".$this->email_data['Our Company'];

       $recipient_type='Customer';
       $recipient_key=$subject->id;
       
    }elseif($user->data['User Type']=='Supplier'){
      $subject=new Supplier($user->data['User Parent Key']);

    }else{
      $this->error=true;
      $this->msg='registration email no applicable';
      return;
      
    }

  foreach($data as $key=>$values){
	if(array_key_exists($key,$this->email_data))
	  $this->email_data[$key]=$values;
      }




    $email=new Email('email',$user->data['User Handle']);

     $data=array(
		'Email Send Type'=>'Registration',
		'Email Send Type Key'=>0,
		'Email Send Recipient Type'=>$recipient_type,
		'Email Send Recipient Key'=>$recipient_key,
		'Email Key'=>$email->id
		);
    
    $this->create($data);
    $this->email_data['Tracker Key']=$this->id.'_'.md5($this->secret.$this->id);
    
    
    
  }

function compose_lost_password_email($user_key,$data=false){
    if(!is_array($data))
      $data=array();


    $this->prepare_email_variables();


    $this->email_data['Template']='emails/html_email_basic_template.html';








    $user=new User($user_key);
    if(!$user->id){
      $this->error=true;
      $this->msg='User not found';
      return;
    }
    
    
$secret_key=$data['secret_key'];

$secret_data=json_encode(array('D'=>generatePassword(2,10).date('U') ,'C'=>$user_key ));
$encrypted_secret_data=base64_encode(AESEncryptCtr($secret_data,$secret_key,256));

    if(preg_match('/^Customer/',$user->data['User Type'])){
     
      $subject=new Customer($user->data['User Parent Key']);
      $store=new Store($subject->data['Customer Store Key']);

      $this->email_data['Image Server Logo Filename']='email_header_'.$store->data['Store Code'].'.png';


      $this->email_data['Our Name']=$store->data['Store Contact Name'];
      $this->email_data['Our Company']=$store->data['Store Name'];

      $this->email_data['Our Telephone']=$store->data['Store Telephone'];
      $this->email_data['Our Email']=$store->data['Store Email'];
      $this->email_data['Our URL']=$store->data['Store URL'];

    
      $this->email_data['From Email Address']=$store->data['Store Email'];

     
      //$this->email_data['To Email']=$user->['User Handle'];
      $this->email_data['To Email Address']='rulovico@gmail.com';
      //   $this->email_data['To Email Address']='raul@ancientwisdom.biz';
      
      
      $this->email_data['Our Position']='';

      
      if($subject->data['Customer Type']=='Company'){
	$this->email_data['To Company']=$subject->data['Customer Name'];
      }
      
      $this->email_data['To Name']=$subject->data['Customer Main Contact Name'];

      $this->email_data['To Greatings']=$subject->data['Customer Main Contact Name'];
      if($this->email_data['To Greatings']=='')
	$this->email_data['To Greatings']=$subject->data['Customer Name'];
      
      if($this->email_data['To Greatings']==$this->email_data['To Company'])
	$this->email_data['To Company']='';

      switch($store->data['Store Locale']){
      case('de_DE'):
	if($this->email_data['To Greatings']=='')
	  $this->email_data['To Greatings']='Sehr geehrte Damen und Herren';
	else
	  $this->email_data['To Greatings']='Sehr geehrte/r '.$this->email_data['To Greatings'];

	$this->email_data['Subject']="Vielen Dank f√ºr Ihre Registrierung bei ".$this->email_data['Our Company'];
	$this->email_data['Content'][]=array(
				     'title'=>"Vielen Dank f√ºr Ihre Registrierung bei ".$this->email_data['Our Company']."!"
				     ,'content'=>"Sehen Sie nun unsere Preise und bestellen Sie aus einer Vielzahl toller Produkte.<br/><br/>"
				     );
	
	break;
      default:
	if($this->email_data['To Greatings']=='')
	  $this->email_data['To Greatings']='Dear Sir/Madam';
	else
	  $this->email_data['To Greatings']='Dear '.$this->email_data['To Greatings'];
	

	$this->email_data['Subject']=$this->email_data['Our Company']." Password Reset Request";
	$this->email_data['Content'][]=array(
				     'title'=>$this->email_data['Our Company']." Password Reset Request"
				     ,'content'=>"We received request to reset the password associated with this email account.<br><br>
If you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.<br><br>
<b>Click the link below to reset your password</b>
<br><br>
<a href=\"http://".$this->email_data['Our URL']."/bd.php?p=".$encrypted_secret_data."\">".$this->email_data['Our URL']."/reset.php?p=".$encrypted_secret_data."</a>
<br></br>
If clicking the link doesn't work you can copy and paste it into your browser's address window. Once you have returned to ".$this->email_data['Our Company'].", you will be asked to choose a new password.
<br><br>
Thank you"
				     );
      }
      $this->email_data['Content Text Only']=$this->email_data['To Greatings']."\n\nWe received request to reset the password associated with this email account.\n\nIf you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.\n\nCopy and paste the following link to your browser's address window.\n\n http://".$this->email_data['Our URL']."/bd.php?p=".$encrypted_secret_data."\n\n Once you hace returned to ".$this->email_data['Our Company'].", you will be asked to choose a new password\n\nThank you \n\n".$this->email_data['Our Name']."\n".$this->email_data['Our Company'];
      print $this->email_data['Content Text Only'];
     
       $recipient_type='Customer';
       $recipient_key=$subject->id;
       
    }elseif($user->data['User Type']=='Supplier'){
      $subject=new Supplier($user->data['User Parent Key']);

    }else{
      $this->error=true;
      $this->msg='registration email no applicable';
      return;
      
    }

  foreach($data as $key=>$values){
	if(array_key_exists($key,$this->email_data))
	  $this->email_data[$key]=$values;
      }




    $email=new Email('email',$user->data['User Handle']);

     $data=array(
		'Email Send Type'=>'Registration',
		'Email Send Type Key'=>0,
		'Email Send Recipient Type'=>$recipient_type,
		'Email Send Recipient Key'=>$recipient_key,
		'Email Key'=>$email->id
		);
    
    $this->create($data);
    $this->email_data['Tracker Key']=$this->id.'_'.md5($this->secret.$this->id);
    
    
    
  }


  function send(){

    global $smarty;
    

/*
 *  Trying to guess your e-mail address.
 *  It is better that you change this line to your address explicitly.
 *  $from_address="me@mydomain.com";
 *  $from_name="My Name";
 */
	$from_address=$this->email_data['From Email Address'];
	
	$from_name=$this->email_data['Our Name'];

	$reply_name=$from_name;
	$reply_address=$from_address;
	$reply_address=$from_address;
	$error_delivery_name=$from_name;
	$error_delivery_address=$from_address;

/*
 *  Change these lines or else you will be mailing the class author.
 */
	$to_name=$this->email_data['To Name'];
	$to_address=$this->email_data['To Email Address'];
	
	$subject=$this->email_data['Subject'];
	$email_message=new email_message_class;
	$email_message->SetEncodedEmailHeader("To",$to_address,$to_name);
	$email_message->SetEncodedEmailHeader("From",$from_address,$from_name);
	$email_message->SetEncodedEmailHeader("Reply-To",$reply_address,$reply_name);
	$email_message->SetHeader("Sender",$from_address);
	
	//	print_r($this->email_data);

/*
 *  Set the Return-Path header to define the envelope sender address to which bounced messages are delivered.
 *  If you are using Windows, you need to use the smtp_message_class to set the return-path address.
 */
	if(defined("PHP_OS")
	&& strcmp(substr(PHP_OS,0,3),"WIN"))
		$email_message->SetHeader("Return-Path",$error_delivery_address);
	//print $subject;
	//	$subject=mb_convert_encoding($subject, 'ISO-8859-1', 'UTF-8');
	//print $subject;exit("caca");

	$email_message->SetEncodedHeader("Subject",mb_convert_encoding($subject, 'ISO-8859-1', 'UTF-8'));

/*
 *  An HTML message that requires any dependent files to be sent,
 *  like image files, style sheet files, HTML frame files, etc..,
 *  needs to be composed as a multipart/related message part.
 *  Different parts need to be created before they can be added
 *  later to the message.
 *
 *  Parts can be created from files that can be opened and read.
 *  The data content type needs to be specified. The can try to guess
 *  the content type automatically from the file name.
 */
	$image=array(
		"FileName"=>$this->email_data['Images Server'].$this->email_data['Images Path'].$this->email_data['Image Server Logo Filename'],
		"Content-Type"=>"automatic/name",
		"Disposition"=>"inline",
/*
 *  You can set the Cache option if you are going to send the same message
 *  to multiple users but this file part does not change.
 *
		"Cache"=>1
 */

		
	

	);
	//	$email_message->CreateFilePart($image,$image_header);

/*
 *  Parts that need to be referenced from other parts,
 *  like images that have to be hyperlinked from the HTML,
 *  are referenced with a special Content-ID string that
 *  the class creates when needed.
 */
//	$image_header_id=$email_message->GetPartContentID($image_header);

/*
 *  Many related file parts may be embedded in the message.
 */
	$image=array(
		     "FileName"=>$this->email_data['Tracker Server'].$this->email_data['Tracker Path'].'tracker.php?key='.$this->email_data['Tracker Key'],
		     "Content-Type"=>"image/png",
		     "Disposition"=>"inline",
		     );
	//	$email_message->CreateFilePart($image,$tracker);
	//	$tracker_image_id="cid:".$email_message->GetPartContentID($tracker);
	
	
	
	//$html_smarty->assign("$email_url",$this->email_data['Content Server'].$this->email_data['Content Path'].'mail_send.php?key='.$this->email_data['Email Send Encrypted Key']);
	
	$smarty->assign("our_company",$this->email_data['Our Company']);

	$smarty->assign("our_name",$this->email_data['Our Name']);
	$smarty->assign("our_position",$this->email_data['Our Position']);
	
	$smarty->assign("our_url",$this->email_data['Our URL']);
	$smarty->assign("our_telephone",$this->email_data['Our Telephone']);
	$smarty->assign("to_greatings",$this->email_data['To Greatings']);
	$smarty->assign("to_company",$this->email_data['To Company']);
	


	$smarty->assign("header_image",$this->email_data['Images Server'].$this->email_data['Images Path'].$this->email_data['Image Server Logo Filename']);
	$smarty->assign("tracker_image_id",$this->email_data['Tracker Server'].$this->email_data['Tracker Path'].'tracker.php?key='.$this->email_data['Tracker Key']);
	$smarty->assign("paragraphs",$this->email_data['Content']);



	$html_message=$smarty->fetch($this->email_data['Template']);

	$html_message=mb_convert_encoding($html_message, 'ISO-8859-1', 'UTF-8');
	

	$email_message->CreateQuotedPrintableHTMLPart($html_message,"",$html_part);




/*
 *  It is strongly recommended that when you send HTML messages,
 *  also provide an alternative text version of HTML page,
 *  even if it is just to say that the message is in HTML,
 *  because more and more people tend to delete HTML only
 *  messages assuming that HTML messages are spam.
 */
	$text_message=$this->email_data['Content Text Only'];
	$email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),"",$text_part);

/*
 *  Multiple alternative parts are gathered in multipart/alternative parts.
 *  It is important that the fanciest part, in this case the HTML part,
 *  is specified as the last part because that is the way that HTML capable
 *  mail programs will show that part and not the text version part.
 */
	$alternative_parts=array(
		$text_part,
		$html_part
	);
	$email_message->CreateAlternativeMultipart($alternative_parts,$alternative_part);

/*
 *  All related parts are gathered in a single multipart/related part.
 */
	$related_parts=array(
		$alternative_part,
		//	$image_header,
		//	$tracker
	);
	$email_message->AddRelatedMultipart($related_parts);


/*
 *  The message is now ready to be assembled and sent.
 *  Notice that most of the functions used before this point may fail due to
 *  programming errors in your script. You may safely ignore any errors until
 *  the message is sent to not bloat your scripts with too much error checking.
 */
	
	$error=$email_message->Send();
	if(strcmp($error,"")){
	  var_dump($email_message->parts);
	  print "$error\n";
	  return 0;
	}else
	  return 1;

  }

 

  function safe_utf8($string){
    $string=htmlentities($string,ENT_QUOTES,'UTF-8');
    $string = str_replace("&lt;","<",$string);
    $string = str_replace("&gt;",">",$string);
    $string = str_replace("&quot;",'"',$string);
    $string = str_replace("&amp;",'&',$string);
    
    return $string;
}


} 

 

?>