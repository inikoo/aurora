<?php
include_once('common.php');
include_once('class.SendEmail.php');


$welcome_email_subject="Test subject";
$welcome_email_plain="Plain text message";
$welcome_email_html='<b>This is test html message</b><br/><img src="localhost/kaktus/track.php">';
$email_credential_key='1';
$handle=array('asdf@sadfg.info','migara64@gmail.com');
$access_key='AKIAJGTHT6POHWCQQNRQ';
$secret_key='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';

$data=array(
                
                  'subject'=>$welcome_email_subject,
                  'plain'=>$welcome_email_plain,
                  'email_credentials_key'=>$email_credential_key,
                  'to'=>$handle,
                  'html'=>$welcome_email_html,
				  'email_type'=>'Registration',
				  'recipient_type'=>'User',
				  'recipient_key'=>2,
				  'access_key'=>$access_key,
				  'secret_key'=>$secret_key,
				  'return_path'=>'migara@inikoo.com'
              );


$send_email=new SendEmail();
$send_email->set_method('amazon');
$send_email->smtp('HTML', $data);
$result=$send_email->send();			  
			  
?>